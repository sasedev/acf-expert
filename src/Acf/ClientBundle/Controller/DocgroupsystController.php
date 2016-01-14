<?php

namespace Acf\ClientBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\Docgroupsyst;
use Acf\ClientBundle\Form\Docgroupsyst\UpdateDocsTForm as DocgroupsystUpdateDocsTForm;
use Acf\ClientBundle\Form\Doc\NewTForm as DocNewTForm;
use Acf\DataBundle\Entity\Doc;
use Acf\DataBundle\Entity\CompanyUser;
use Acf\DataBundle\Entity\Trace;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class DocgroupsystController extends BaseController
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
			$docgroupsyst = $em->getRepository('AcfDataBundle:Docgroupsyst')->find($uid);

			if (null == $docgroupsyst) {
				$this->flashMsgSession('warning', $this->translate('Docgroupsyst.edit.notfound'));
			} else {

				$sc = $this->getSecurityTokenStorage();
				$user = $sc->getToken()->getUser();

				$company = $docgroupsyst->getCompany();

				$companyUser = $em->getRepository('AcfDataBundle:CompanyUser')->findOneBy(array('company' => $company, 'user' => $user));
				if (null == $companyUser) {
					$this->flashMsgSession('error', $this->translate('CompanyUser.accessForbidden'));
					return $this->redirect($this->generateUrl('_client_homepage'));
				}
				$this->gvars['companyUser'] = $companyUser;
				$this->gvars['menu_active'] = 'client' . $company->getId();

				$docgroupsystUpdateDocsForm = $this->createForm(DocgroupsystUpdateDocsTForm::class, $docgroupsyst,
					array('company' => $docgroupsyst->getCompany()));

				$doc = new Doc();
				$doc->setCompany($docgroupsyst->getCompany());
				$docNewForm = $this->createForm(DocNewTForm::class, $doc, array('company' => $docgroupsyst->getCompany()));

				$this->gvars['docgroupsyst'] = $docgroupsyst;
				$this->gvars['doc'] = $doc;
				$this->gvars['DocgroupsystUpdateDocsForm'] = $docgroupsystUpdateDocsForm->createView();
				$this->gvars['DocNewForm'] = $docNewForm->createView();

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
				$this->getSession()->remove('tabActive');

				$this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
				$this->getSession()->remove('stabActive');

				$this->gvars['pagetitle'] = $this->translate('pagetitle.docgroupsyst.edit',
					array('%docgroupsyst%' => $docgroupsyst->getLabel()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.docgroupsyst.edit.txt',
					array('%docgroupsyst%' => $docgroupsyst->getLabel()));

				return $this->renderResponse('AcfClientBundle:Docgroupsyst:edit.html.twig', $this->gvars);
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
			$docgroupsyst = $em->getRepository('AcfDataBundle:Docgroupsyst')->find($uid);

			if (null == $docgroupsyst) {
				$this->flashMsgSession('warning', $this->translate('Docgroupsyst.edit.notfound'));
			} else {

				$sc = $this->getSecurityTokenStorage();
				$user = $sc->getToken()->getUser();

				$company = $docgroupsyst->getCompany();

				$companyUser = $em->getRepository('AcfDataBundle:CompanyUser')->findOneBy(array('company' => $company, 'user' => $user));
				if (null == $companyUser || $companyUser->getEditDocgroupSysts() == CompanyUser::CANT) {
					$this->flashMsgSession('error', $this->translate('CompanyUser.accessForbidden'));
					return $this->redirect($this->generateUrl('_client_homepage'));
				}
				$this->gvars['companyUser'] = $companyUser;
				$this->gvars['menu_active'] = 'client' . $company->getId();

				$docgroupsystUpdateDocsForm = $this->createForm(DocgroupsystUpdateDocsTForm::class, $docgroupsyst,
					array('company' => $docgroupsyst->getCompany()));

				$doc = new Doc();
				$doc->setCompany($docgroupsyst->getCompany());
				$docNewForm = $this->createForm(DocNewTForm::class, $doc, array('company' => $docgroupsyst->getCompany()));

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
				$this->getSession()->remove('tabActive');

				$this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
				$this->getSession()->remove('stabActive');

				$request = $this->getRequest();
				$reqData = $request->request->all();

				$cloneDocgroupsyst = clone $docgroupsyst;

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
							$doc->setCompany($docgroupsyst->getCompany());

							$doc->setFileName($fileName);
							$doc->setOriginalName($originalName);
							$doc->setSize($size);
							$doc->setMimeType($mimeType);
							$doc->setMd5($md5);
							$doc->setDescription($docNewForm['description']->getData());
							$em->persist($doc);

							$docgroupsyst->addDoc($doc);

							$docs[] = $doc;

							$docNames .= $doc->getOriginalName() . " ";
						}

						$em->persist($docgroupsyst);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Doc.add.success', array('%doc%' => $docNames)));
						$this->newDocNotifyAdmin($docgroupsyst, $docs);
						$this->gvars['stabActive'] = 3;
						$this->getSession()->set('stabActive', 3);

						$this->traceEntity($cloneDocgroupsyst, $docgroupsyst);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($docgroupsyst);

						$this->flashMsgSession('error', $this->translate('Doc.add.failure'));
					}
				} elseif (isset($reqData['DocgroupsystUpdateDocsForm'])) {
					$this->gvars['tabActive'] = 3;
					$this->getSession()->set('tabActive', 3);
					$this->gvars['stabActive'] = 2;
					$this->getSession()->set('stabActive', 2);
					$docgroupsystUpdateDocsForm->handleRequest($request);
					if ($docgroupsystUpdateDocsForm->isValid()) {
						$em->persist($docgroupsyst);
						$em->flush();
						$this->flashMsgSession('success',
							$this->translate('Docgroupsyst.edit.success', array('%docgroupsyst%' => $docgroupsyst->getLabel())));
						$this->gvars['stabActive'] = 3;
						$this->getSession()->set('stabActive', 3);

						$this->traceEntity($cloneDocgroupsyst, $docgroupsyst);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($docgroupsyst);

						$this->flashMsgSession('error',
							$this->translate('Docgroupsyst.edit.failure', array('%docgroupsyst%' => $docgroupsyst->getLabel())));
					}
				}

				$this->gvars['docgroupsyst'] = $docgroupsyst;
				$this->gvars['doc'] = $doc;
				$this->gvars['DocgroupsystUpdateDocsForm'] = $docgroupsystUpdateDocsForm->createView();
				$this->gvars['DocNewForm'] = $docNewForm->createView();

				$this->gvars['pagetitle'] = $this->translate('pagetitle.docgroupsyst.edit',
					array('%docgroupsyst%' => $docgroupsyst->getLabel()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.docgroupsyst.edit.txt',
					array('%docgroupsyst%' => $docgroupsyst->getLabel()));

				return $this->renderResponse('AcfClientBundle:Docgroupsyst:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
		}

		return $this->redirect($urlFrom);
	}

	public function childsAction(Docgroupsyst $parent) {
		$em = $this->getEntityManager();
		$dg = $em->getRepository('AcfDataBundle:Docgroupsyst')->find($parent);
		$this->gvars['parent'] = $dg;
		return $this->renderResponse('AcfClientBundle:Docgroupsyst:childs.html.twig', $this->gvars);
	}

	protected function newDocNotifyAdmin(Docgroupsyst $dg, $docs)
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
			$message->setBody($this->renderView('AcfClientBundle:Mail:Docgroupsystnewdoc.html.twig', $mvars), 'text/html');
			$this->sendmail($message);
		}

	}

	protected function traceEntity(Docgroupsyst $cloneDocgroupsyst, Docgroupsyst $docgroupsyst)
	{
		$curUser = $this->getSecurityTokenStorage()
			->getToken()
			->getUser();
		$trace = new Trace();
		$trace->setActionId($docgroupsyst->getId());
		$trace->setActionType(Trace::AT_UPDATE);
		$trace->setUserId($curUser->getId());
		$trace->setCompanyId($docgroupsyst->getCompany()
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

		$trace->setActionEntity(Trace::AE_DOCGROUPSYST);
		$trace->setActionId2($docgroupsyst->getCompany()
			->getId());
		$trace->setActionEntity2(Trace::AE_COMPANY);

		$msg = "";

		if ($cloneDocgroupsyst->getLabel() != $docgroupsyst->getLabel()) {
			$msg .= "<tr><td>" . $this->translate('Docgroupsyst.label.label') . '</td><td>';
			if ($cloneDocgroupsyst->getLabel() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneDocgroupsyst->getLabel();
			}
			$msg .= "</td><td>";
			if ($docgroupsyst->getLabel() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $docgroupsyst->getLabel();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneDocgroupsyst->getParent() != $docgroupsyst->getParent() &&
			(($cloneDocgroupsyst->getParent() == null && $docgroupsyst->getParent() != null) ||
			($cloneDocgroupsyst->getParent() != null && $docgroupsyst->getParent() == null) ||
			($docgroupsyst->getParent() != null && $cloneDocgroupsyst->getParent() != null &&
			$cloneDocgroupsyst->getParent()->getPageUrlFull() != $docgroupsyst->getParent()->getPageUrlFull()))) {
			$msg .= "<tr><td>" . $this->translate('Docgroupsyst.parent.label') . '</td><td>';
			if ($cloneDocgroupsyst->getParent() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneDocgroupsyst->getParent()->getLabel();
			}
			$msg .= "</td><td>";
			if ($docgroupsyst->getParent() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $docgroupsyst->getParent()->getLabel();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneDocgroupsyst->getOtherInfos() != $docgroupsyst->getOtherInfos()) {
			$msg .= "<tr><td>" . $this->translate('Docgroupsyst.otherInfos.label') . '</td><td>';
			if ($cloneDocgroupsyst->getOtherInfos() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneDocgroupsyst->getOtherInfos();
			}
			$msg .= "</td><td>";
			if ($docgroupsyst->getOtherInfos() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $docgroupsyst->getOtherInfos();
			}
			$msg .= "</td></tr>";
		}

		if (\count(\array_diff($docgroupsyst->getDocs()->toArray(), $cloneDocgroupsyst->getDocs()->toArray())) != 0 ||
			\count(\array_diff($cloneDocgroupsyst->getDocs()->toArray(), $docgroupsyst->getDocs()->toArray())) != 0) {
			$msg .= "<tr><td>" . $this->translate('Docgroupsyst.docs.label') . '</td><td>';
			if (\count($cloneDocgroupsyst->getDocs()) == 0) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= "<ul>";
				foreach ($cloneDocgroupsyst->getDocs() as $doc) {
					$msg .= '<li><a href="' . $this->generateUrl('_admin_doc_editGet', array('uid' => $doc->getId())) . '">' .
						$doc->getOriginalName() . '</a></li>';
				}
				$msg .= "<ul>";
			}
			$msg .= "</td><td>";
			if (\count($docgroupsyst->getDocs()) == 0) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= "<ul>";
				foreach ($docgroupsyst->getDocs() as $doc) {
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
				$this->translate('Docgroupsyst.traceEdit',
					array('%docgroupsyst%' => $docgroupsyst->getLabel(), '%company%' => $docgroupsyst->getCompany()
						->getCorporateName())) . $msg);
			$trace->setDtCrea(new \DateTime('now'));
			$em = $this->getEntityManager();
			$em->persist($trace);
			$em->flush();
		}
	}
}
