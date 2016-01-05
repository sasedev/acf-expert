<?php

namespace Acf\ClientBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\Docgroupcomptable;
use Acf\ClientBundle\Form\Docgroupcomptable\UpdateDocsTForm as DocgroupcomptableUpdateDocsTForm;
use Acf\ClientBundle\Form\Doc\NewTForm as DocNewTForm;
use Acf\DataBundle\Entity\Doc;
use Acf\DataBundle\Entity\CompanyUser;
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
		$this->gvars['menu_active'] = 'clienthome';
	}

	public function editGetAction($uid)
	{
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_client_homepage');
		}

		$em = $this->getEntityManager();
		try {
			$docgroupcomptable = $em->getRepository('AcfDataBundle:Docgroupcomptable')->find($uid);

			if (null == $docgroupcomptable) {
				$this->flashMsgSession('warning', $this->translate('Docgroupcomptable.edit.notfound'));
			} else {

				$sc = $this->getSecurityTokenStorage();
				$user = $sc->getToken()->getUser();

				$company = $docgroupcomptable->getCompany();

				$companyUser = $em->getRepository('AcfDataBundle:CompanyUser')->findOneBy(array('company' => $company, 'user' => $user));
				if (null == $companyUser) {
					$this->flashMsgSession('error', $this->translate('CompanyUser.accessForbidden'));
					return $this->redirect($this->generateUrl('_client_homepage'));
				}
				$this->gvars['companyUser'] = $companyUser;
				$this->gvars['menu_active'] = 'client' . $company->getId();

				$docgroupcomptableUpdateDocsForm = $this->createForm(DocgroupcomptableUpdateDocsTForm::class, $docgroupcomptable,
					array('company' => $docgroupcomptable->getCompany()));

				$doc = new Doc();
				$doc->setCompany($docgroupcomptable->getCompany());
				$docNewForm = $this->createForm(DocNewTForm::class, $doc, array('company' => $docgroupcomptable->getCompany()));

				$this->gvars['docgroupcomptable'] = $docgroupcomptable;
				$this->gvars['doc'] = $doc;
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

				return $this->renderResponse('AcfClientBundle:Docgroupcomptable:edit.html.twig', $this->gvars);
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
			$urlFrom = $this->generateUrl('_client_homepage');
		}

		$em = $this->getEntityManager();
		try {
			$docgroupcomptable = $em->getRepository('AcfDataBundle:Docgroupcomptable')->find($uid);

			if (null == $docgroupcomptable) {
				$this->flashMsgSession('warning', $this->translate('Docgroupcomptable.edit.notfound'));
			} else {

				$sc = $this->getSecurityTokenStorage();
				$user = $sc->getToken()->getUser();

				$company = $docgroupcomptable->getCompany();

				$companyUser = $em->getRepository('AcfDataBundle:CompanyUser')->findOneBy(array('company' => $company, 'user' => $user));
				if (null == $companyUser || $companyUser->getEditDocgroupComptables() == CompanyUser::CANT) {
					$this->flashMsgSession('error', $this->translate('CompanyUser.accessForbidden'));
					return $this->redirect($this->generateUrl('_client_homepage'));
				}
				$this->gvars['companyUser'] = $companyUser;
				$this->gvars['menu_active'] = 'client' . $company->getId();

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

				if (isset($reqData['DocNewForm'])) {
					$this->gvars['tabActive'] = 3;
					$this->getSession()->set('tabActive', 3);
					$this->gvars['stabActive'] = 1;
					$this->getSession()->set('stabActive', 1);
					$docNewForm->handleRequest($request);
					if ($docNewForm->isValid()) {
						$docFiles = $docNewForm['fileName']->getData();
						$docs = array();

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

							$docs[] = $doc;

							$docNames .= $doc->getOriginalName() . " ";
						}

						$em->persist($docgroupcomptable);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Doc.add.success', array('%doc%' => $docNames)));
						$this->newDocNotifyAdmin($docgroupcomptable, $docs);
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
				$this->gvars['DocgroupcomptableUpdateDocsForm'] = $docgroupcomptableUpdateDocsForm->createView();
				$this->gvars['DocNewForm'] = $docNewForm->createView();

				$this->gvars['pagetitle'] = $this->translate('pagetitle.docgroupcomptable.edit',
					array('%docgroupcomptable%' => $docgroupcomptable->getLabel()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.docgroupcomptable.edit.txt',
					array('%docgroupcomptable%' => $docgroupcomptable->getLabel()));

				return $this->renderResponse('AcfClientBundle:Docgroupcomptable:edit.html.twig', $this->gvars);
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
		return $this->renderResponse('AcfClientBundle:Docgroupcomptable:childs.html.twig', $this->gvars);
	}

	protected function newDocNotifyAdmin(Docgroupcomptable $dg, $docs)
	{
		$from = $this->getParameter('mail_from');
		$fromName = $this->getParameter('mail_from_name');
		$subject = $this->translate('_mail.newdocs.subject', array(), 'messages');

		$user = $this->getSecurityTokenStorage()
			->getToken()
			->getUser();
		$company = $dg->getCompany();

		$admins = $company->getAdmins();
		if (\count($admins) != 0) {
			$mvars = array();
			$mvars['dg'] = $dg;
			$mvars['user'] = $user;
			$mvars['company'] = $company;
			$mvars['docs'] = $docs;
			$message = \Swift_Message::newInstance();
			$message->setFrom($from, $fromName);
			foreach ($admins as $admin) {
				$message->addTo($admin->getEmail(), $admin->getFullname());
			}
			$message->setSubject($subject);
			$message->setBody($this->renderView('AcfClientBundle:Mail:Docgroupcomptablenewdoc.html.twig', $mvars), 'text/html');
			$this->sendmail($message);
		}

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

		if ($cloneDocgroupcomptable->getParent() != $docgroupcomptable->getParent() &&
			(($cloneDocgroupcomptable->getParent() == null && $docgroupcomptable->getParent() != null) ||
			($cloneDocgroupcomptable->getParent() != null && $docgroupcomptable->getParent() == null) ||
			($docgroupcomptable->getParent() != null && $cloneDocgroupcomptable->getParent() != null &&
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
