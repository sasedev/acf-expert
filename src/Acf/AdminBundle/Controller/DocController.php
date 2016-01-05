<?php

namespace Acf\AdminBundle\Controller;

use Acf\DataBundle\Entity\Doc;
use Acf\AdminBundle\Form\Doc\UpdateContentTForm as DocUpdateContentTForm;
use Acf\AdminBundle\Form\Doc\UpdateDescriptionTForm as DocUpdateDescriptionTForm;
use Acf\AdminBundle\Form\Doc\UpdateOriginalNameTForm as DocUpdateOriginalNameTForm;
use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Acf\DataBundle\Entity\Trace;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class DocController extends BaseController
{

	/**
	 *
	 * @var array
	 */
	protected $gvars = array();

	/**
	 * Constructor
	 */
	public function __construct()
	{

		$this->gvars['menu_active'] = 'company';

	}

	public function downloadAction($uid)
	{
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_admin_company_list');
		}
		$em = $this->getEntityManager();
		try {
			$doc = $em->getRepository('AcfDataBundle:Doc')->find($uid);

			if (null == $doc) {
				$logger = $this->getLogger();
				$logger->addError('Document inconnu');
				$this->flashMsgSession('warning', $this->translate('Doc.download.notfound'));
			} else {
				$docDir = $this->getParameter('kernel.root_dir').'/../web/res/docs';
				$fileName = $doc->getFileName();

				try {
					$dlFile = new File($docDir.'/'.$fileName);
					$response = new StreamedResponse(function() use($dlFile) {
						$handle = fopen($dlFile->getRealPath(), 'r');
						while (!feof($handle)) {
							$buffer = fread($handle, 1024);
							echo $buffer;
							flush();
						}
						fclose($handle);
					});

					$response->headers->set('Content-Type', $doc->getMimeType());
					$response->headers->set('Cache-Control', '');
					$response->headers->set('Content-Length', $doc->getSize());
					$response->headers->set('Last-Modified', gmdate('D, d M Y H:i:s', $doc->getDtUpdate()->getTimestamp()));
					$fallback = $this->normalize($doc->getOriginalName());

					$contentDisposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $doc->getOriginalName(), $fallback);
					$response->headers->set('Content-Disposition', $contentDisposition);

					$doc->setNbrDownloads($doc->getNbrDownloads() + 1);
					$em->persist($doc);
					$em->flush();

					return $response;
				} catch (FileNotFoundException $fnfex) {
					$logger = $this->getLogger();
					$logger->addError('Fichier introuvable ou autre erreur');
					$logger->addError($fnfex->getMessage());
					$this->flashMsgSession('error', $fnfex->getMessage());
					$this->flashMsgSession('warning', $this->translate('Doc.download.notfound'));
				}
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());
			$this->flashMsgSession('error', $e->getMessage());
			$this->flashMsgSession('warning', $this->translate('Doc.download.notfound'));
		}

		return $this->redirect($urlFrom);

	}

	public function deleteAction($uid)
	{
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('_admin_company_list'));
		}
		$em = $this->getEntityManager();
		try {
			$doc = $em->getRepository('AcfDataBundle:Doc')->find($uid);

			if (null == $doc) {
				$this->flashMsgSession('warning', $this->translate('Doc.delete.notfound'));
			} else {
				$em->remove($doc);
				$em->flush();

				$this->flashMsgSession(
					'success',
					$this->translate('Doc.delete.success', array('%doc%' => $doc->getOriginalName()))
				);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());

			$this->flashMsgSession('error', $this->translate('Doc.delete.failure'));
		}

		return $this->redirect($urlFrom);

	}

	public function editGetAction($uid)
	{
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_admin_company_list');
		}

		$em = $this->getEntityManager();
		try {
			$doc = $em->getRepository('AcfDataBundle:Doc')->find($uid);

			if (null == $doc) {
				$this->flashMsgSession('warning', $this->translate('Doc.edit.notfound'));
			} else {
				$traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($doc->getId(), Trace::AE_DOC);
				$this->gvars['traces'] = array_reverse($traces);
				$docUpdateDescriptionForm = $this->createForm(DocUpdateDescriptionTForm::class, $doc);
				$docUpdateContentForm = $this->createForm(DocUpdateContentTForm::class, $doc);
				$docUpdateOriginalNameForm = $this->createForm(DocUpdateOriginalNameTForm::class, $doc);

				$this->gvars['doc'] = $doc;
				$this->gvars['DocUpdateDescriptionForm'] = $docUpdateDescriptionForm->createView();
				$this->gvars['DocUpdateContentForm'] = $docUpdateContentForm->createView();
				$this->gvars['DocUpdateOriginalNameForm'] = $docUpdateOriginalNameForm->createView();

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
				$this->getSession()->remove('tabActive');



				$this->gvars['pagetitle'] = $this->translate('pagetitle.doc.edit', array('%doc%' => $doc->getOriginalName()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.doc.edit.txt', array('%doc%' => $doc->getOriginalName()));

				return $this->renderResponse('AcfAdminBundle:Doc:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());
		}

		return $this->redirect($urlFrom);
	}


	public function editPostAction($uid)
	{
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('_admin_company_list'));
		}

		$em = $this->getEntityManager();
		try {
			$doc = $em->getRepository('AcfDataBundle:Doc')->find($uid);

			if (null == $doc) {
				$this->flashMsgSession('warning', $this->translate('Doc.edit.notfound'));
			} else {
				$traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($doc->getId(), Trace::AE_DOC);
				$this->gvars['traces'] = array_reverse($traces);
				$docUpdateDescriptionForm = $this->createForm(DocUpdateDescriptionTForm::class, $doc);
				$docUpdateContentForm = $this->createForm(DocUpdateContentTForm::class, $doc);
				$docUpdateOriginalNameForm = $this->createForm(DocUpdateOriginalNameTForm::class, $doc);


				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
				$this->getSession()->remove('tabActive');

				$request = $this->getRequest();
				$reqData = $request->request->all();

				$cloneDoc = clone $doc;

				if (isset($reqData['DocUpdateDescriptionForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$docUpdateDescriptionForm->handleRequest($request);
					if ($docUpdateDescriptionForm->isValid()) {
						$em->persist($doc);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('Doc.edit.success', array('%doc%' => $doc->getOriginalName()))
						);

						$this->traceEntity($cloneDoc, $doc);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($doc);

						$this->flashMsgSession(
							'error',
							$this->translate('Doc.edit.failure', array('%doc%' => $doc->getOriginalName()))
						);
					}
				} elseif (isset($reqData['DocUpdateContentForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$docUpdateContentForm->handleRequest($request);
					if ($docUpdateContentForm->isValid()) {

						$docFile = $docUpdateContentForm['doc']->getData();

						$docDir = $this->getParameter('kernel.root_dir').'/../web/res/docs';

						$originalName = $docFile->getClientOriginalName();
						$fileName = sha1(uniqid(mt_rand(), true)).'.'.strtolower($docFile->getClientOriginalExtension());
						$mimeType = $docFile->getMimeType();
						$docFile->move($docDir, $fileName);

						$size = filesize($docDir.'/'.$fileName);
						$md5 = md5_file($docDir.'/'.$fileName);

						$doc->setFileName($fileName);
						$doc->setOriginalName($originalName);
						$doc->setSize($size);
						$doc->setMimeType($mimeType);
						$doc->setMd5($md5);

						$em->persist($doc);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('Doc.edit.success', array('%doc%' => $doc->getOriginalName()))
						);

						$this->traceEntity($cloneDoc, $doc);

						return $this->redirect($urlFrom);

					} else {
						$em->refresh($doc);

						$this->flashMsgSession(
							'error',
							$this->translate('Doc.add.failure')
						);
					}
				} elseif (isset($reqData['DocUpdateOriginalNameForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$docUpdateOriginalNameForm->handleRequest($request);
					if ($docUpdateOriginalNameForm->isValid()) {
						$em->persist($doc);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('Doc.edit.success', array('%doc%' => $doc->getOriginalName()))
						);

						$this->traceEntity($cloneDoc, $doc);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($doc);

						$this->flashMsgSession(
							'error',
							$this->translate('Doc.edit.failure', array('%doc%' => $doc->getOriginalName()))
						);
					}
				}

				$this->gvars['doc'] = $doc;
				$this->gvars['DocUpdateDescriptionForm'] = $docUpdateDescriptionForm->createView();
				$this->gvars['DocUpdateContentForm'] = $docUpdateContentForm->createView();
				$this->gvars['DocUpdateOriginalNameForm'] = $docUpdateOriginalNameForm->createView();

				$this->gvars['pagetitle'] = $this->translate('pagetitle.doc.edit', array('%doc%' => $doc->getOriginalName()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.doc.edit.txt', array('%doc%' => $doc->getOriginalName()));

				return $this->renderResponse('AcfAdminBundle:Doc:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());
		}

		return $this->redirect($urlFrom);

	}

	protected function traceEntity(Doc $cloneDoc, Doc $doc) {

		$curUser = $this->getSecurityTokenStorage()->getToken()->getUser();
		$trace = new Trace();
		$trace->setActionId($doc->getId());
		$trace->setActionType(Trace::AT_UPDATE);
		$trace->setUserId($curUser->getId());
		$trace->setCompanyId($doc->getCompany()->getId());
		$trace->setUserFullname($curUser->getFullName());
		if (! $this->hasRole('ROLE_SUPERADMIN')) {
			if (! $this->hasRole('ROLE_ADMIN')) {
				$trace->setUserType(Trace::UT_CLIENT);
			} else {
				$trace->setUserType(Trace::UT_ADMIN);
			}

		} else {
			$trace->setUserType(Trace::UT_SUPERADMIN);
		}



		$table_begin = ': <br><table class="table table-bordered table-condensed table-hover table-striped">';
		$table_begin .= '<thead><tr><th class="text-left">'.$this->translate('Entity.field').'</th>';
		$table_begin .= '<th class="text-left">'.$this->translate('Entity.oldVal').'</th>';
		$table_begin .= '<th class="text-left">'.$this->translate('Entity.newVal').'</th></tr></thead><tbody>';

		$table_end = '</tbody></table>';

		$trace->setActionEntity(Trace::AE_DOC);
		$trace->setActionId2($doc->getCompany()->getId());
		$trace->setActionEntity2(Trace::AE_COMPANY);

		$msg = "";

		if ($cloneDoc->getFileName() != $doc->getFileName()) {
			$msg .= "<tr><td>".$this->translate('Doc.fileName.label').'</td><td>';
			if ($cloneDoc->getFileName() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneDoc->getFileName();
			}
			$msg .= "</td><td>";
			if ($doc->getFileName() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $doc->getFileName();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneDoc->getSize() != $doc->getSize()) {
			$msg .= "<tr><td>".$this->translate('Doc.size.label').'</td><td>';
			if ($cloneDoc->getSize() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneDoc->getSize();
			}
			$msg .= "</td><td>";
			if ($doc->getSize() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $doc->getSize();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneDoc->getMimeType() != $doc->getMimeType()) {
			$msg .= "<tr><td>".$this->translate('Doc.mimeType.label').'</td><td>';
			if ($cloneDoc->getMimeType() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneDoc->getMimeType();
			}
			$msg .= "</td><td>";
			if ($doc->getMimeType() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $doc->getMimeType();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneDoc->getMd5() != $doc->getMd5()) {
			$msg .= "<tr><td>".$this->translate('Doc.md5.label').'</td><td>';
			if ($cloneDoc->getMd5() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneDoc->getMd5();
			}
			$msg .= "</td><td>";
			if ($doc->getMd5() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $doc->getMd5();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneDoc->getOriginalName() != $doc->getOriginalName()) {
			$msg .= "<tr><td>".$this->translate('Doc.originalName.label').'</td><td>';
			if ($cloneDoc->getOriginalName() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneDoc->getOriginalName();
			}
			$msg .= "</td><td>";
			if ($doc->getOriginalName() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $doc->getOriginalName();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneDoc->getDescription() != $doc->getDescription()) {
			$msg .= "<tr><td>".$this->translate('Doc.description.label').'</td><td>';
			if ($cloneDoc->getDescription() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneDoc->getDescription();
			}
			$msg .= "</td><td>";
			if ($doc->getDescription() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $doc->getDescription();
			}
			$msg .= "</td></tr>";
		}

		if ($msg != "") {

			$msg = $table_begin.$msg.$table_end;

			$trace->setMsg(
				$this->translate(
					'Doc.traceEdit',
					array('%doc%' => $doc->getOriginalName() , '%company%' => $doc->getCompany()->getCorporateName())
					).$msg
				);
			$trace->setDtCrea(new \DateTime('now'));
			$em = $this->getEntityManager();
			$em->persist($trace);
			$em->flush();
		}
	}

}
