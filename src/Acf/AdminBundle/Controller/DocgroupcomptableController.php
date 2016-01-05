<?php

namespace Acf\AdminBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\Docgroupcomptable;
use Acf\AdminBundle\Form\Docgroupcomptable\UpdateLabelTForm as DocgroupcomptableUpdateLabelTForm;
use Acf\AdminBundle\Form\Docgroupcomptable\UpdateOtherInfosTForm as DocgroupcomptableUpdateOtherInfosTForm;
use Acf\AdminBundle\Form\Docgroupcomptable\UpdateDocsTForm as DocgroupcomptableUpdateDocsTForm;
use Acf\AdminBundle\Form\Docgroupcomptable\UpdateParentTForm as DocgroupcomptableUpdateParentTForm;
use Acf\AdminBundle\Form\Doc\NewTForm as DocNewTForm;
use Acf\DataBundle\Entity\Doc;
use Acf\DataBundle\Entity\Trace;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class DocgroupcomptableController extends BaseController
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
			$docgroupcomptable = $em->getRepository('AcfDataBundle:Docgroupcomptable')->find($uid);

			if (null == $docgroupcomptable) {
				$this->flashMsgSession('warning', $this->translate('Docgroupcomptable.delete.notfound'));
			} else {
				$em->remove($docgroupcomptable);
				$em->flush();

				$this->flashMsgSession('success',
					$this->translate('Docgroupcomptable.delete.success', array('%docgroupcomptable%' => $docgroupcomptable->getLabel())));
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

			$this->flashMsgSession('error', $this->translate('Docgroupcomptable.delete.failure'));
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
			$docgroupcomptable = $em->getRepository('AcfDataBundle:Docgroupcomptable')->find($uid);

			if (null == $docgroupcomptable) {
				$this->flashMsgSession('warning', $this->translate('Docgroupcomptable.edit.notfound'));
			} else {
				$traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($docgroupcomptable->getId(),
					Trace::AE_DOCGROUPCOMPTABLE);
				$this->gvars['traces'] = array_reverse($traces);
				$docgroupcomptableUpdateLabelForm = $this->createForm(DocgroupcomptableUpdateLabelTForm::class, $docgroupcomptable);
				$docgroupcomptableUpdateOtherInfosForm = $this->createForm(DocgroupcomptableUpdateOtherInfosTForm::class,
					$docgroupcomptable);
				$docgroupcomptableUpdateParentForm = $this->createForm(DocgroupcomptableUpdateParentTForm::class, $docgroupcomptable,
					array('selfUrl' => $docgroupcomptable->getPageUrlFull(), 'company' => $docgroupcomptable->getCompany()));
				$docgroupcomptableUpdateDocsForm = $this->createForm(DocgroupcomptableUpdateDocsTForm::class, $docgroupcomptable,
					array('company' => $docgroupcomptable->getCompany()));

				$doc = new Doc();
				$doc->setCompany($docgroupcomptable->getCompany());
				$docNewForm = $this->createForm(DocNewTForm::class, $doc, array('company' => $docgroupcomptable->getCompany()));

				$this->gvars['docgroupcomptable'] = $docgroupcomptable;
				$this->gvars['doc'] = $doc;
				$this->gvars['DocgroupcomptableUpdateLabelForm'] = $docgroupcomptableUpdateLabelForm->createView();
				$this->gvars['DocgroupcomptableUpdateOtherInfosForm'] = $docgroupcomptableUpdateOtherInfosForm->createView();
				$this->gvars['DocgroupcomptableUpdateParentForm'] = $docgroupcomptableUpdateParentForm->createView();
				$this->gvars['DocgroupcomptableUpdateDocsForm'] = $docgroupcomptableUpdateDocsForm->createView();
				$this->gvars['DocNewForm'] = $docNewForm->createView();

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
				$this->getSession()->remove('tabActive');

				$this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
				$this->getSession()->remove('stabActive');

				$this->gvars['pagetitle'] = $this->translate('pagetitle.docgroupcomptable.edit',
					array('%docgroupcomptable%' => $docgroupcomptable->getLabel()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.docgroupcomptable.edit.txt',
					array('%docgroupcomptable%' => $docgroupcomptable->getLabel()));

				return $this->renderResponse('AcfAdminBundle:Docgroupcomptable:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
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
			$docgroupcomptable = $em->getRepository('AcfDataBundle:Docgroupcomptable')->find($uid);

			if (null == $docgroupcomptable) {
				$this->flashMsgSession('warning', $this->translate('Docgroupcomptable.edit.notfound'));
			} else {
				$traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($docgroupcomptable->getId(),
					Trace::AE_DOCGROUPCOMPTABLE);
				$this->gvars['traces'] = array_reverse($traces);
				$docgroupcomptableUpdateLabelForm = $this->createForm(DocgroupcomptableUpdateLabelTForm::class, $docgroupcomptable);
				$docgroupcomptableUpdateOtherInfosForm = $this->createForm(DocgroupcomptableUpdateOtherInfosTForm::class,
					$docgroupcomptable);
				$docgroupcomptableUpdateParentForm = $this->createForm(DocgroupcomptableUpdateParentTForm::class, $docgroupcomptable,
					array('selfUrl' => $docgroupcomptable->getPageUrlFull(), 'company' => $docgroupcomptable->getCompany()));
				$docgroupcomptableUpdateDocsForm = $this->createForm(DocgroupcomptableUpdateDocsTForm::class, $docgroupcomptable,
					array('company' => $docgroupcomptable->getCompany()));

				$doc = new Doc();
				$doc->setCompany($docgroupcomptable->getCompany());
				$docNewForm = $this->createForm(DocNewTForm::class, $doc, array('company' => $docgroupcomptable->getCompany()));

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
				$this->getSession()->remove('tabActive');

				$this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
				$this->getSession()->remove('stabActive');

				$request = $this->getRequest();
				$reqData = $request->request->all();

				$cloneDocgroupcomptable = clone $docgroupcomptable;

				if (isset($reqData['DocgroupcomptableUpdateLabelForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$docgroupcomptableUpdateLabelForm->handleRequest($request);
					if ($docgroupcomptableUpdateLabelForm->isValid()) {
						$em->persist($docgroupcomptable);
						$em->flush();
						$this->flashMsgSession('success',
							$this->translate('Docgroupcomptable.edit.success',
								array('%docgroupcomptable%' => $docgroupcomptable->getLabel())));

						$this->traceEntity($cloneDocgroupcomptable, $docgroupcomptable);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($docgroupcomptable);

						$this->flashMsgSession('error',
							$this->translate('Docgroupcomptable.edit.failure',
								array('%docgroupcomptable%' => $docgroupcomptable->getLabel())));
					}
				} elseif (isset($reqData['DocgroupcomptableUpdateOtherInfosForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$docgroupcomptableUpdateOtherInfosForm->handleRequest($request);
					if ($docgroupcomptableUpdateOtherInfosForm->isValid()) {
						$em->persist($docgroupcomptable);
						$em->flush();
						$this->flashMsgSession('success',
							$this->translate('Docgroupcomptable.edit.success',
								array('%docgroupcomptable%' => $docgroupcomptable->getLabel())));

						$this->traceEntity($cloneDocgroupcomptable, $docgroupcomptable);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($docgroupcomptable);

						$this->flashMsgSession('error',
							$this->translate('Docgroupcomptable.edit.failure',
								array('%docgroupcomptable%' => $docgroupcomptable->getLabel())));
					}
				} elseif (isset($reqData['DocgroupcomptableUpdateParentForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$docgroupcomptableUpdateParentForm->handleRequest($request);
					if ($docgroupcomptableUpdateParentForm->isValid()) {
						$em->persist($docgroupcomptable);
						$em->flush();
						$this->flashMsgSession('success',
							$this->translate('Docgroupcomptable.edit.success',
								array('%docgroupcomptable%' => $docgroupcomptable->getLabel())));

						$this->traceEntity($cloneDocgroupcomptable, $docgroupcomptable);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($docgroupcomptable);

						$this->flashMsgSession('error',
							$this->translate('Docgroupcomptable.edit.failure',
								array('%docgroupcomptable%' => $docgroupcomptable->getLabel())));
					}
				} elseif (isset($reqData['DocNewForm'])) {
					$this->gvars['tabActive'] = 3;
					$this->getSession()->set('tabActive', 3);
					$this->gvars['stabActive'] = 1;
					$this->getSession()->set('stabActive', 1);
					$docNewForm->handleRequest($request);
					if ($docNewForm->isValid()) {
						$docFiles = $docNewForm['fileName']->getData();

						$docDir = $this->getParameter('kernel.root_dir') . '/../web/res/docs';

						$docNames = "";

						foreach ($docFiles as $docFile) {

							$originalName = $docFile->getClientOriginalName();
							$fileName = sha1(uniqid(mt_rand(), true)) . '.' . strtolower($docFile->getClientOriginalExtension());
							$mimeType = $docFile->getMimeType();
							$docFile->move($docDir, $fileName);

							$size = filesize($docDir . '/' . $fileName);
							$md5 = md5_file($docDir . '/' . $fileName);

							$doc = new Doc();
							$doc->setCompany($docgroupcomptable->getCompany());

							$doc->setFileName($fileName);
							$doc->setOriginalName($originalName);
							$doc->setSize($size);
							$doc->setMimeType($mimeType);
							$doc->setMd5($md5);
							$doc->addGroupcomptable($docgroupcomptable);
							$em->persist($doc);

							$docNames .= $doc->getOriginalName() . " ";
						}

						$em->persist($docgroupcomptable);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Doc.add.success', array('%doc%' => $docNames)));
						$this->gvars['stabActive'] = 3;
						$this->getSession()->set('stabActive', 3);

						$this->traceEntity($cloneDocgroupcomptable, $docgroupcomptable);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($docgroupcomptable);

						$this->flashMsgSession('error', $this->translate('Doc.add.failure'));
					}
				} elseif (isset($reqData['DocgroupcomptableUpdateDocsForm'])) {
					$this->gvars['tabActive'] = 3;
					$this->getSession()->set('tabActive', 3);
					$this->gvars['stabActive'] = 2;
					$this->getSession()->set('stabActive', 2);
					$docgroupcomptableUpdateDocsForm->handleRequest($request);
					if ($docgroupcomptableUpdateDocsForm->isValid()) {
						$em->persist($docgroupcomptable);
						$em->flush();
						$this->flashMsgSession('success',
							$this->translate('Docgroupcomptable.edit.success',
								array('%docgroupcomptable%' => $docgroupcomptable->getLabel())));
						$this->gvars['stabActive'] = 3;
						$this->getSession()->set('stabActive', 3);

						$this->traceEntity($cloneDocgroupcomptable, $docgroupcomptable);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($docgroupcomptable);

						$this->flashMsgSession('error',
							$this->translate('Docgroupcomptable.edit.failure',
								array('%docgroupcomptable%' => $docgroupcomptable->getLabel())));
					}
				}

				$this->gvars['docgroupcomptable'] = $docgroupcomptable;
				$this->gvars['doc'] = $doc;
				$this->gvars['DocgroupcomptableUpdateLabelForm'] = $docgroupcomptableUpdateLabelForm->createView();
				$this->gvars['DocgroupcomptableUpdateOtherInfosForm'] = $docgroupcomptableUpdateOtherInfosForm->createView();
				$this->gvars['DocgroupcomptableUpdateParentForm'] = $docgroupcomptableUpdateParentForm->createView();
				$this->gvars['DocgroupcomptableUpdateDocsForm'] = $docgroupcomptableUpdateDocsForm->createView();
				$this->gvars['DocNewForm'] = $docNewForm->createView();

				$this->gvars['pagetitle'] = $this->translate('pagetitle.docgroupcomptable.edit',
					array('%docgroupcomptable%' => $docgroupcomptable->getLabel()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.docgroupcomptable.edit.txt',
					array('%docgroupcomptable%' => $docgroupcomptable->getLabel()));

				return $this->renderResponse('AcfAdminBundle:Docgroupcomptable:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
		}

		return $this->redirect($urlFrom);
	}

	public function childsAction(Docgroupcomptable $parent)
	{
		$em = $this->getEntityManager();
		$dg = $em->getRepository('AcfDataBundle:Docgroupcomptable')->find($parent);
		$this->gvars['parent'] = $dg;
		return $this->renderResponse('AcfAdminBundle:Docgroupcomptable:childs.html.twig', $this->gvars);
	}

	protected function traceEntity(Docgroupcomptable $cloneDocgroupcomptable, Docgroupcomptable $docgroupcomptable)
	{
		$curUser = $this->getSecurityTokenStorage()
			->getToken()
			->getUser();
		$trace = new Trace();
		$trace->setActionId($docgroupcomptable->getId());
		$trace->setActionType(Trace::AT_UPDATE);
		$trace->setUserId($curUser->getId());
		$trace->setCompanyId($docgroupcomptable->getCompany()
			->getId());
		$trace->setUserFullname($curUser->getFullName());
		if (!$this->hasRole('ROLE_SUPERADMIN')) {
			if (!$this->hasRole('ROLE_ADMIN')) {
				$trace->setUserType(Trace::UT_CLIENT);
			} else {
				$trace->setUserType(Trace::UT_ADMIN);
			}
		} else {
			$trace->setUserType(Trace::UT_SUPERADMIN);
		}

		$table_begin = ': <br><table class="table table-bordered table-condensed table-hover table-striped">';
		$table_begin .= '<thead><tr><th class="text-left">' . $this->translate('Entity.field') . '</th>';
		$table_begin .= '<th class="text-left">' . $this->translate('Entity.oldVal') . '</th>';
		$table_begin .= '<th class="text-left">' . $this->translate('Entity.newVal') . '</th></tr></thead><tbody>';

		$table_end = '</tbody></table>';

		$trace->setActionEntity(Trace::AE_DOCGROUPCOMPTABLE);
		$trace->setActionId2($docgroupcomptable->getCompany()
			->getId());
		$trace->setActionEntity2(Trace::AE_COMPANY);

		$msg = "";

		if ($cloneDocgroupcomptable->getLabel() != $docgroupcomptable->getLabel()) {
			$msg .= "<tr><td>" . $this->translate('Docgroupcomptable.label.label') . '</td><td>';
			if ($cloneDocgroupcomptable->getLabel() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneDocgroupcomptable->getLabel();
			}
			$msg .= "</td><td>";
			if ($docgroupcomptable->getLabel() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $docgroupcomptable->getLabel();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneDocgroupcomptable->getParent() != $docgroupcomptable->getParent() && (($cloneDocgroupcomptable->getParent() == null &&
			$docgroupcomptable->getParent() != null) ||
			($cloneDocgroupcomptable->getParent() != null && $docgroupcomptable->getParent() == null) || ($docgroupcomptable->getParent() !=
			null && $cloneDocgroupcomptable->getParent() != null &&
			$cloneDocgroupcomptable->getParent()->getPageUrlFull() != $docgroupcomptable->getParent()->getPageUrlFull()))) {
			$msg .= "<tr><td>" . $this->translate('Docgroupcomptable.parent.label') . '</td><td>';
			if ($cloneDocgroupcomptable->getParent() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneDocgroupcomptable->getParent()->getLabel();
			}
			$msg .= "</td><td>";
			if ($docgroupcomptable->getParent() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $docgroupcomptable->getParent()->getLabel();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneDocgroupcomptable->getOtherInfos() != $docgroupcomptable->getOtherInfos()) {
			$msg .= "<tr><td>" . $this->translate('Docgroupcomptable.otherInfos.label') . '</td><td>';
			if ($cloneDocgroupcomptable->getOtherInfos() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneDocgroupcomptable->getOtherInfos();
			}
			$msg .= "</td><td>";
			if ($docgroupcomptable->getOtherInfos() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $docgroupcomptable->getOtherInfos();
			}
			$msg .= "</td></tr>";
		}

		if (\count(\array_diff($docgroupcomptable->getDocs()->toArray(), $cloneDocgroupcomptable->getDocs()->toArray())) != 0 ||
			\count(\array_diff($cloneDocgroupcomptable->getDocs()->toArray(), $docgroupcomptable->getDocs()->toArray())) != 0) {
			$msg .= "<tr><td>" . $this->translate('Docgroupcomptable.docs.label') . '</td><td>';
			if (\count($cloneDocgroupcomptable->getDocs()) == 0) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= "<ul>";
				foreach ($cloneDocgroupcomptable->getDocs() as $doc) {
					$msg .= '<li><a href="' . $this->generateUrl('_admin_doc_editGet', array('uid' => $doc->getId())) . '">' .
						$doc->getOriginalName() . '</a></li>';
				}
				$msg .= "<ul>";
			}
			$msg .= "</td><td>";
			if (\count($docgroupcomptable->getDocs()) == 0) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= "<ul>";
				foreach ($docgroupcomptable->getDocs() as $doc) {
					$msg .= '<li><a href="' . $this->generateUrl('_admin_doc_editGet', array('uid' => $doc->getId())) . '">' .
						$doc->getOriginalName() . '</a></li>';
				}
				$msg .= "<ul>";
			}
			$msg .= "</td></tr>";
		}

		if ($msg != "") {

			$msg = $table_begin . $msg . $table_end;

			$trace->setMsg(
				$this->translate('Docgroupcomptable.traceEdit',
					array('%docgroupcomptable%' => $docgroupcomptable->getLabel(),
						'%company%' => $docgroupcomptable->getCompany()
							->getCorporateName())) . $msg);
			$trace->setDtCrea(new \DateTime('now'));
			$em = $this->getEntityManager();
			$em->persist($trace);
			$em->flush();
		}
	}
}
