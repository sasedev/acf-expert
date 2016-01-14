<?php

namespace Acf\AdminBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\Docgroupbank;
use Acf\AdminBundle\Form\Docgroupbank\UpdateLabelTForm as DocgroupbankUpdateLabelTForm;
use Acf\AdminBundle\Form\Docgroupbank\UpdateOtherInfosTForm as DocgroupbankUpdateOtherInfosTForm;
use Acf\AdminBundle\Form\Docgroupbank\UpdateDocsTForm as DocgroupbankUpdateDocsTForm;
use Acf\AdminBundle\Form\Docgroupbank\UpdateParentTForm as DocgroupbankUpdateParentTForm;
use Acf\AdminBundle\Form\Doc\NewTForm as DocNewTForm;
use Acf\DataBundle\Entity\Doc;
use Acf\DataBundle\Entity\Trace;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class DocgroupbankController extends BaseController
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

	public function deleteAction($uid)
	{
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_admin_company_list');
		}
		$em = $this->getEntityManager();
		try {
			$docgroupbank = $em->getRepository('AcfDataBundle:Docgroupbank')->find($uid);

			if (null == $docgroupbank) {
				$this->flashMsgSession('warning', $this->translate('Docgroupbank.delete.notfound'));
			} else {
				$em->remove($docgroupbank);
				$em->flush();

				$this->flashMsgSession(
					'success',
					$this->translate('Docgroupbank.delete.success', array('%docgroupbank%' => $docgroupbank->getLabel()))
				);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());

			$this->flashMsgSession('error', $this->translate('Docgroupbank.delete.failure'));
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
			$docgroupbank = $em->getRepository('AcfDataBundle:Docgroupbank')->find($uid);

			if (null == $docgroupbank) {
				$this->flashMsgSession('warning', $this->translate('Docgroupbank.edit.notfound'));
			} else {
				$traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($docgroupbank->getId(), Trace::AE_DOCGROUPBANK);
				$this->gvars['traces'] = array_reverse($traces);
				$docgroupbankUpdateLabelForm = $this->createForm(DocgroupbankUpdateLabelTForm::class, $docgroupbank);
				$docgroupbankUpdateOtherInfosForm = $this->createForm(DocgroupbankUpdateOtherInfosTForm::class, $docgroupbank);
				$docgroupbankUpdateParentForm = $this->createForm(DocgroupbankUpdateParentTForm::class, $docgroupbank, array('selfUrl' => $docgroupbank->getPageUrlFull(), 'company' => $docgroupbank->getCompany()));
				$docgroupbankUpdateDocsForm = $this->createForm(DocgroupbankUpdateDocsTForm::class, $docgroupbank, array('company' => $docgroupbank->getCompany()));

				$doc = new Doc();
				$doc->setCompany($docgroupbank->getCompany());
				$docNewForm = $this->createForm(DocNewTForm::class, $doc, array('company' => $docgroupbank->getCompany()));


				$this->gvars['docgroupbank'] = $docgroupbank;
				$this->gvars['doc'] = $doc;
				$this->gvars['DocgroupbankUpdateLabelForm'] = $docgroupbankUpdateLabelForm->createView();
				$this->gvars['DocgroupbankUpdateOtherInfosForm'] = $docgroupbankUpdateOtherInfosForm->createView();
				$this->gvars['DocgroupbankUpdateParentForm'] = $docgroupbankUpdateParentForm->createView();
				$this->gvars['DocgroupbankUpdateDocsForm'] = $docgroupbankUpdateDocsForm->createView();
				$this->gvars['DocNewForm'] = $docNewForm->createView();

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
				$this->getSession()->remove('tabActive');

				$this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
				$this->getSession()->remove('stabActive');



				$this->gvars['pagetitle'] = $this->translate('pagetitle.docgroupbank.edit', array('%docgroupbank%' => $docgroupbank->getLabel()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.docgroupbank.edit.txt', array('%docgroupbank%' => $docgroupbank->getLabel()));

				return $this->renderResponse('AcfAdminBundle:Docgroupbank:edit.html.twig', $this->gvars);
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
			$urlFrom = $this->generateUrl('_admin_company_list');
		}

		$em = $this->getEntityManager();
		try {
			$docgroupbank = $em->getRepository('AcfDataBundle:Docgroupbank')->find($uid);

			if (null == $docgroupbank) {
				$this->flashMsgSession('warning', $this->translate('Docgroupbank.edit.notfound'));
			} else {
				$traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($docgroupbank->getId(), Trace::AE_DOCGROUPBANK);
				$this->gvars['traces'] = array_reverse($traces);
				$docgroupbankUpdateLabelForm = $this->createForm(DocgroupbankUpdateLabelTForm::class, $docgroupbank);
				$docgroupbankUpdateOtherInfosForm = $this->createForm(DocgroupbankUpdateOtherInfosTForm::class, $docgroupbank);
				$docgroupbankUpdateParentForm = $this->createForm(DocgroupbankUpdateParentTForm::class, $docgroupbank, array('selfUrl' => $docgroupbank->getPageUrlFull(), 'company' => $docgroupbank->getCompany()));
				$docgroupbankUpdateDocsForm = $this->createForm(DocgroupbankUpdateDocsTForm::class, $docgroupbank, array('company' => $docgroupbank->getCompany()));

				$doc = new Doc();
				$doc->setCompany($docgroupbank->getCompany());
				$docNewForm = $this->createForm(DocNewTForm::class, $doc, array('company' => $docgroupbank->getCompany()));



				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
				$this->getSession()->remove('tabActive');

				$this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
				$this->getSession()->remove('stabActive');

				$request = $this->getRequest();
				$reqData = $request->request->all();

				$cloneDocgroupbank = clone $docgroupbank;

				if (isset($reqData['DocgroupbankUpdateLabelForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$docgroupbankUpdateLabelForm->handleRequest($request);
					if ($docgroupbankUpdateLabelForm->isValid()) {
						$em->persist($docgroupbank);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('Docgroupbank.edit.success', array('%docgroupbank%' => $docgroupbank->getLabel()))
						);

						$this->traceEntity($cloneDocgroupbank, $docgroupbank);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($docgroupbank);

						$this->flashMsgSession('error', $this->translate('Docgroupbank.edit.failure', array('%docgroupbank%' => $docgroupbank->getLabel())));
					}
				} elseif (isset($reqData['DocgroupbankUpdateOtherInfosForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$docgroupbankUpdateOtherInfosForm->handleRequest($request);
					if ($docgroupbankUpdateOtherInfosForm->isValid()) {
						$em->persist($docgroupbank);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('Docgroupbank.edit.success', array('%docgroupbank%' => $docgroupbank->getLabel()))
						);

						$this->traceEntity($cloneDocgroupbank, $docgroupbank);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($docgroupbank);

						$this->flashMsgSession('error', $this->translate('Docgroupbank.edit.failure', array('%docgroupbank%' => $docgroupbank->getLabel())));
					}
				} elseif (isset($reqData['DocgroupbankUpdateParentForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$docgroupbankUpdateParentForm->handleRequest($request);
					if ($docgroupbankUpdateParentForm->isValid()) {
						$em->persist($docgroupbank);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('Docgroupbank.edit.success', array('%docgroupbank%' => $docgroupbank->getLabel()))
						);

						$this->traceEntity($cloneDocgroupbank, $docgroupbank);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($docgroupbank);

						$this->flashMsgSession('error', $this->translate('Docgroupbank.edit.failure', array('%docgroupbank%' => $docgroupbank->getLabel())));
					}
				} elseif (isset($reqData['DocNewForm'])) {
					$this->gvars['tabActive'] = 3;
					$this->getSession()->set('tabActive', 3);
					$this->gvars['stabActive'] = 1;
					$this->getSession()->set('stabActive', 1);
					$docNewForm->handleRequest($request);
					if ($docNewForm->isValid()) {
						$docFiles = $docNewForm['fileName']->getData();

						$docDir = $this->getParameter('kernel.root_dir').'/../web/res/docs';

						$docNames = "";

						foreach ($docFiles as $docFile) {

							$originalName = $docFile->getClientOriginalName();
							$fileName = sha1(uniqid(mt_rand(), true)) . '.' . strtolower($docFile->getClientOriginalExtension());
							$mimeType = $docFile->getMimeType();
							$docFile->move($docDir, $fileName);

							$size = filesize($docDir.'/'.$fileName);
							$md5 = md5_file($docDir.'/'.$fileName);

							$doc = new Doc();
							$doc->setCompany($docgroupbank->getCompany());

							$doc->setFileName($fileName);
							$doc->setOriginalName($originalName);
							$doc->setSize($size);
							$doc->setMimeType($mimeType);
							$doc->setMd5($md5);
							$doc->setDescription($docNewForm['description']->getData());
							$em->persist($doc);

							$docgroupbank->addDoc($doc);

							$docNames .= $doc->getOriginalName()." ";
						}

						$em->persist($docgroupbank);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('Doc.add.success', array('%doc%' => $docNames))
						);
						$this->gvars['stabActive'] = 3;
						$this->getSession()->set('stabActive', 3);

						$this->traceEntity($cloneDocgroupbank, $docgroupbank);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($docgroupbank);

						$this->flashMsgSession('error', $this->translate('Doc.add.failure'));
					}
				} elseif (isset($reqData['DocgroupbankUpdateDocsForm'])) {
					$this->gvars['tabActive'] = 3;
					$this->getSession()->set('tabActive', 3);
					$this->gvars['stabActive'] = 2;
					$this->getSession()->set('stabActive', 2);
					$docgroupbankUpdateDocsForm->handleRequest($request);
					if ($docgroupbankUpdateDocsForm->isValid()) {
						$em->persist($docgroupbank);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('Docgroupbank.edit.success', array('%docgroupbank%' => $docgroupbank->getLabel()))
						);
						$this->gvars['stabActive'] = 3;
						$this->getSession()->set('stabActive', 3);

						$this->traceEntity($cloneDocgroupbank, $docgroupbank);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($docgroupbank);

						$this->flashMsgSession('error', $this->translate('Docgroupbank.edit.failure', array('%docgroupbank%' => $docgroupbank->getLabel())));
					}
				}

				$this->gvars['docgroupbank'] = $docgroupbank;
				$this->gvars['doc'] = $doc;
				$this->gvars['DocgroupbankUpdateLabelForm'] = $docgroupbankUpdateLabelForm->createView();
				$this->gvars['DocgroupbankUpdateOtherInfosForm'] = $docgroupbankUpdateOtherInfosForm->createView();
				$this->gvars['DocgroupbankUpdateParentForm'] = $docgroupbankUpdateParentForm->createView();
				$this->gvars['DocgroupbankUpdateDocsForm'] = $docgroupbankUpdateDocsForm->createView();
				$this->gvars['DocNewForm'] = $docNewForm->createView();



				$this->gvars['pagetitle'] = $this->translate('pagetitle.docgroupbank.edit', array('%docgroupbank%' => $docgroupbank->getLabel()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.docgroupbank.edit.txt', array('%docgroupbank%' => $docgroupbank->getLabel()));

				return $this->renderResponse('AcfAdminBundle:Docgroupbank:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());
		}

		return $this->redirect($urlFrom);
	}

	public function childsAction(Docgroupbank $parent) {
		$em = $this->getEntityManager();
		$dg = $em->getRepository('AcfDataBundle:Docgroupbank')->find($parent);
		$this->gvars['parent'] = $dg;
		return $this->renderResponse('AcfAdminBundle:Docgroupbank:childs.html.twig', $this->gvars);
	}

	protected function traceEntity(Docgroupbank $cloneDocgroupbank, Docgroupbank $docgroupbank) {

		$curUser = $this->getSecurityTokenStorage()->getToken()->getUser();
		$trace = new Trace();
		$trace->setActionId($docgroupbank->getId());
		$trace->setActionType(Trace::AT_UPDATE);
		$trace->setUserId($curUser->getId());
		$trace->setCompanyId($docgroupbank->getCompany()->getId());
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

		$trace->setActionEntity(Trace::AE_DOCGROUPBANK);
		$trace->setActionId2($docgroupbank->getCompany()->getId());
		$trace->setActionEntity2(Trace::AE_COMPANY);

		$msg = "";

		if ($cloneDocgroupbank->getLabel() != $docgroupbank->getLabel()) {
			$msg .= "<tr><td>".$this->translate('Docgroupbank.label.label').'</td><td>';
			if ($cloneDocgroupbank->getLabel() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneDocgroupbank->getLabel();
			}
			$msg .= "</td><td>";
			if ($docgroupbank->getLabel() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $docgroupbank->getLabel();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneDocgroupbank->getParent() != $docgroupbank->getParent() && (($cloneDocgroupbank->getParent() == null && $docgroupbank->getParent() != null) || ($cloneDocgroupbank->getParent() != null && $docgroupbank->getParent() == null) || ($docgroupbank->getParent() != null && $cloneDocgroupbank->getParent() != null && $cloneDocgroupbank->getParent()->getPageUrlFull() != $docgroupbank->getParent()->getPageUrlFull()))) {
			$msg .= "<tr><td>".$this->translate('Docgroupbank.parent.label').'</td><td>';
			if ($cloneDocgroupbank->getParent() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneDocgroupbank->getParent()->getLabel();
			}
			$msg .= "</td><td>";
			if ($docgroupbank->getParent() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $docgroupbank->getParent()->getLabel();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneDocgroupbank->getOtherInfos() != $docgroupbank->getOtherInfos()) {
			$msg .= "<tr><td>".$this->translate('Docgroupbank.otherInfos.label').'</td><td>';
			if ($cloneDocgroupbank->getOtherInfos() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneDocgroupbank->getOtherInfos();
			}
			$msg .= "</td><td>";
			if ($docgroupbank->getOtherInfos() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $docgroupbank->getOtherInfos();
			}
			$msg .= "</td></tr>";
		}

		if (\count(\array_diff($docgroupbank->getDocs()->toArray(), $cloneDocgroupbank->getDocs()->toArray())) != 0 || \count(\array_diff($cloneDocgroupbank->getDocs()->toArray(), $docgroupbank->getDocs()->toArray())) != 0) {
			$msg .= "<tr><td>".$this->translate('Docgroupbank.docs.label').'</td><td>';
			if (\count($cloneDocgroupbank->getDocs()) == 0) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= "<ul>";
				foreach ($cloneDocgroupbank->getDocs() as $doc) {
					$msg .= '<li><a href="'.$this->generateUrl('_admin_doc_editGet', array('uid' => $doc->getId())).'">'.$doc->getOriginalName().'</a></li>';
				}
				$msg .= "<ul>";
			}
			$msg .= "</td><td>";
			if (\count($docgroupbank->getDocs()) == 0) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= "<ul>";
				foreach ($docgroupbank->getDocs() as $doc) {
					$msg .= '<li><a href="'.$this->generateUrl('_admin_doc_editGet', array('uid' => $doc->getId())).'">'.$doc->getOriginalName().'</a></li>';
				}
				$msg .= "<ul>";


			}
			$msg .= "</td></tr>";
		}

		if ($msg != "") {

			$msg = $table_begin.$msg.$table_end;

			$trace->setMsg(
				$this->translate(
					'Docgroupbank.traceEdit',
					array('%docgroupbank%' => $docgroupbank->getLabel(), '%company%' => $docgroupbank->getCompany()->getCorporateName())
					).$msg
				);
			$trace->setDtCrea(new \DateTime('now'));
			$em = $this->getEntityManager();
			$em->persist($trace);
			$em->flush();
		}
	}

}
