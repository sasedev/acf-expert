<?php
namespace Acf\AdminBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\Docgroupperso;
use Acf\AdminBundle\Form\Docgroupperso\UpdateLabelTForm as DocgrouppersoUpdateLabelTForm;
use Acf\AdminBundle\Form\Docgroupperso\UpdateOtherInfosTForm as DocgrouppersoUpdateOtherInfosTForm;
use Acf\AdminBundle\Form\Docgroupperso\UpdateDocsTForm as DocgrouppersoUpdateDocsTForm;
use Acf\AdminBundle\Form\Docgroupperso\UpdateParentTForm as DocgrouppersoUpdateParentTForm;
use Acf\AdminBundle\Form\Doc\NewTForm as DocNewTForm;
use Acf\DataBundle\Entity\Doc;
use Acf\DataBundle\Entity\Trace;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class DocgrouppersoController extends BaseController
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

	public function addDocAction($uid)
	{
		$this->getSession()->set('tabActive', 3);
		$this->getSession()->set('stabActive', 1);

		return $this->redirect($this->generateUrl('_admin_docgroupperso_editGet', array(
			'uid' => $uid
		)));
	}

	/**
	 *
	 * @param string $uid
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteAction($uid)
	{
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_admin_company_list');
		}
		$em = $this->getEntityManager();
		try {
			$docgroupperso = $em->getRepository('AcfDataBundle:Docgroupperso')->find($uid);

			if (null == $docgroupperso) {
				$this->flashMsgSession('warning', $this->translate('Docgroupperso.delete.notfound'));
			} else {
				$em->remove($docgroupperso);
				$em->flush();

				$this->flashMsgSession('success', $this->translate('Docgroupperso.delete.success', array(
					'%docgroupperso%' => $docgroupperso->getLabel()
				)));
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

			$this->flashMsgSession('error', $this->translate('Docgroupperso.delete.failure'));
		}

		return $this->redirect($urlFrom);
	}

	/**
	 *
	 * @param string $uid
	 * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function editGetAction($uid)
	{
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_admin_company_list');
		}

		$em = $this->getEntityManager();
		try {
			$docgroupperso = $em->getRepository('AcfDataBundle:Docgroupperso')->find($uid);

			if (null == $docgroupperso) {
				$this->flashMsgSession('warning', $this->translate('Docgroupperso.edit.notfound'));
			} else {
				$traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($docgroupperso->getId(), Trace::AE_DOCGROUPPERSO);
				$this->gvars['traces'] = array_reverse($traces);
				$docgrouppersoUpdateLabelForm = $this->createForm(DocgrouppersoUpdateLabelTForm::class, $docgroupperso);
				$docgrouppersoUpdateOtherInfosForm = $this->createForm(DocgrouppersoUpdateOtherInfosTForm::class, $docgroupperso);
				$docgrouppersoUpdateParentForm = $this->createForm(DocgrouppersoUpdateParentTForm::class, $docgroupperso, array(
					'selfUrl' => $docgroupperso->getPageUrlFull(),
					'company' => $docgroupperso->getCompany()
				));
				$docgrouppersoUpdateDocsForm = $this->createForm(DocgrouppersoUpdateDocsTForm::class, $docgroupperso, array(
					'company' => $docgroupperso->getCompany()
				));

				$doc = new Doc();
				$doc->setCompany($docgroupperso->getCompany());
				$docNewForm = $this->createForm(DocNewTForm::class, $doc, array(
					'company' => $docgroupperso->getCompany()
				));

				$this->gvars['docgroupperso'] = $docgroupperso;
				$this->gvars['doc'] = $doc;
				$this->gvars['DocgrouppersoUpdateLabelForm'] = $docgrouppersoUpdateLabelForm->createView();
				$this->gvars['DocgrouppersoUpdateOtherInfosForm'] = $docgrouppersoUpdateOtherInfosForm->createView();
				$this->gvars['DocgrouppersoUpdateParentForm'] = $docgrouppersoUpdateParentForm->createView();
				$this->gvars['DocgrouppersoUpdateDocsForm'] = $docgrouppersoUpdateDocsForm->createView();
				$this->gvars['DocNewForm'] = $docNewForm->createView();

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
				$this->getSession()->remove('tabActive');

				$this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
				$this->getSession()->remove('stabActive');

				$this->gvars['pagetitle'] = $this->translate('pagetitle.docgroupperso.edit', array(
					'%docgroupperso%' => $docgroupperso->getLabel()
				));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.docgroupperso.edit.txt', array(
					'%docgroupperso%' => $docgroupperso->getLabel()
				));

				return $this->renderResponse('AcfAdminBundle:Docgroupperso:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
		}

		return $this->redirect($urlFrom);
	}

	/**
	 *
	 * @param string $uid
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function editPostAction($uid)
	{
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_admin_company_list');
		}

		$em = $this->getEntityManager();
		try {
			$docgroupperso = $em->getRepository('AcfDataBundle:Docgroupperso')->find($uid);

			if (null == $docgroupperso) {
				$this->flashMsgSession('warning', $this->translate('Docgroupperso.edit.notfound'));
			} else {
				$traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($docgroupperso->getId(), Trace::AE_DOCGROUPPERSO);
				$this->gvars['traces'] = array_reverse($traces);
				$docgrouppersoUpdateLabelForm = $this->createForm(DocgrouppersoUpdateLabelTForm::class, $docgroupperso);
				$docgrouppersoUpdateOtherInfosForm = $this->createForm(DocgrouppersoUpdateOtherInfosTForm::class, $docgroupperso);
				$docgrouppersoUpdateParentForm = $this->createForm(DocgrouppersoUpdateParentTForm::class, $docgroupperso, array(
					'selfUrl' => $docgroupperso->getPageUrlFull(),
					'company' => $docgroupperso->getCompany()
				));
				$docgrouppersoUpdateDocsForm = $this->createForm(DocgrouppersoUpdateDocsTForm::class, $docgroupperso, array(
					'company' => $docgroupperso->getCompany()
				));

				$doc = new Doc();
				$doc->setCompany($docgroupperso->getCompany());
				$docNewForm = $this->createForm(DocNewTForm::class, $doc, array(
					'company' => $docgroupperso->getCompany()
				));

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
				$this->getSession()->remove('tabActive');

				$this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
				$this->getSession()->remove('stabActive');

				$request = $this->getRequest();
				$reqData = $request->request->all();

				$cloneDocgroupperso = clone $docgroupperso;

				if (isset($reqData['DocgrouppersoUpdateLabelForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$docgrouppersoUpdateLabelForm->handleRequest($request);
					if ($docgrouppersoUpdateLabelForm->isValid()) {
						$em->persist($docgroupperso);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Docgroupperso.edit.success', array(
							'%docgroupperso%' => $docgroupperso->getLabel()
						)));

						$this->traceEntity($cloneDocgroupperso, $docgroupperso);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($docgroupperso);

						$this->flashMsgSession('error', $this->translate('Docgroupperso.edit.failure', array(
							'%docgroupperso%' => $docgroupperso->getLabel()
						)));
					}
				} elseif (isset($reqData['DocgrouppersoUpdateOtherInfosForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$docgrouppersoUpdateOtherInfosForm->handleRequest($request);
					if ($docgrouppersoUpdateOtherInfosForm->isValid()) {
						$em->persist($docgroupperso);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Docgroupperso.edit.success', array(
							'%docgroupperso%' => $docgroupperso->getLabel()
						)));

						$this->traceEntity($cloneDocgroupperso, $docgroupperso);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($docgroupperso);

						$this->flashMsgSession('error', $this->translate('Docgroupperso.edit.failure', array(
							'%docgroupperso%' => $docgroupperso->getLabel()
						)));
					}
				} elseif (isset($reqData['DocgrouppersoUpdateParentForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$docgrouppersoUpdateParentForm->handleRequest($request);
					if ($docgrouppersoUpdateParentForm->isValid()) {
						$em->persist($docgroupperso);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Docgroupperso.edit.success', array(
							'%docgroupperso%' => $docgroupperso->getLabel()
						)));

						$this->traceEntity($cloneDocgroupperso, $docgroupperso);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($docgroupperso);

						$this->flashMsgSession('error', $this->translate('Docgroupperso.edit.failure', array(
							'%docgroupperso%' => $docgroupperso->getLabel()
						)));
					}
				} elseif (isset($reqData['DocNewForm'])) {
					$this->gvars['tabActive'] = 3;
					$this->getSession()->set('tabActive', 3);
					$this->gvars['stabActive'] = 1;
					$this->getSession()->set('stabActive', 1);
					$docNewForm->handleRequest($request);
					if ($docNewForm->isValid()) {
						$docFiles = $docNewForm['fileName']->getData();
						$docs = array();

						$docDir = $this->getParameter('kernel.root_dir') . '/../web/res/docs';

						$docNames = '';

						foreach ($docFiles as $docFile) {

							$originalName = $docFile->getClientOriginalName();
							$fileName = sha1(uniqid(mt_rand(), true)) . '.' . strtolower($docFile->getClientOriginalExtension());
							$mimeType = $docFile->getMimeType();
							$docFile->move($docDir, $fileName);

							$size = filesize($docDir . '/' . $fileName);
							$md5 = md5_file($docDir . '/' . $fileName);

							$doc = new Doc();
							$doc->setCompany($docgroupperso->getCompany());

							$doc->setFileName($fileName);
							$doc->setOriginalName($originalName);
							$doc->setSize($size);
							$doc->setMimeType($mimeType);
							$doc->setMd5($md5);
							$doc->setDescription($docNewForm['description']->getData());
							$em->persist($doc);

							$docgroupperso->addDoc($doc);

							$docNames .= $doc->getOriginalName() . ' ';

							$docs[] = $doc;
						}

						$em->persist($docgroupperso);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Doc.add.success', array(
							'%doc%' => $docNames
						)));

						$from = $this->getParameter('mail_from');
						$fromName = $this->getParameter('mail_from_name');
						$subject = $this->translate('_mail.newdocsCloud.subject', array(), 'messages');

						$company = $docgroupperso->getCompany();
						$acfCloudRole = $em->getRepository('AcfDataBundle:Role')->findOneBy(array(
							'name' => 'ROLE_CLIENT1'
						));

						$users = array();
						foreach ($company->getUsers() as $user) {
							if ($user->hasRole($acfCloudRole)) {
								$users[] = $user;
							}
						}

						if (\count($users) != 0) {
							foreach ($users as $user) {
								$mvars = array();
								$mvars['company'] = $company;
								$mvars['docs'] = $docs;
								$message = \Swift_Message::newInstance();
								$message->setFrom($from, $fromName);
								$message->addTo($user->getEmail(), $user->getFullname());
								$message->setSubject($subject);
								$mvars['logo'] = $message->embed(\Swift_Image::fromPath($this->getParameter('kernel.root_dir') . '/../web/bundles/acfres/images/logo_acf.jpg'));
								$message->setBody($this->renderView('AcfAdminBundle:Doc:sendmail.html.twig', $mvars), 'text/html');
								$this->sendmail($message);
							}
						}
						$this->gvars['stabActive'] = 3;
						$this->getSession()->set('stabActive', 3);

						$this->traceEntity($cloneDocgroupperso, $docgroupperso);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($docgroupperso);

						$this->flashMsgSession('error', $this->translate('Doc.add.failure'));
					}
				} elseif (isset($reqData['DocgrouppersoUpdateDocsForm'])) {
					$this->gvars['tabActive'] = 3;
					$this->getSession()->set('tabActive', 3);
					$this->gvars['stabActive'] = 2;
					$this->getSession()->set('stabActive', 2);
					$docgrouppersoUpdateDocsForm->handleRequest($request);
					if ($docgrouppersoUpdateDocsForm->isValid()) {
						$em->persist($docgroupperso);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Docgroupperso.edit.success', array(
							'%docgroupperso%' => $docgroupperso->getLabel()
						)));
						$this->gvars['stabActive'] = 3;
						$this->getSession()->set('stabActive', 3);

						$this->traceEntity($cloneDocgroupperso, $docgroupperso);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($docgroupperso);

						$this->flashMsgSession('error', $this->translate('Docgroupperso.edit.failure', array(
							'%docgroupperso%' => $docgroupperso->getLabel()
						)));
					}
				}

				$this->gvars['docgroupperso'] = $docgroupperso;
				$this->gvars['doc'] = $doc;
				$this->gvars['DocgrouppersoUpdateLabelForm'] = $docgrouppersoUpdateLabelForm->createView();
				$this->gvars['DocgrouppersoUpdateOtherInfosForm'] = $docgrouppersoUpdateOtherInfosForm->createView();
				$this->gvars['DocgrouppersoUpdateParentForm'] = $docgrouppersoUpdateParentForm->createView();
				$this->gvars['DocgrouppersoUpdateDocsForm'] = $docgrouppersoUpdateDocsForm->createView();
				$this->gvars['DocNewForm'] = $docNewForm->createView();

				$this->gvars['pagetitle'] = $this->translate('pagetitle.docgroupperso.edit', array(
					'%docgroupperso%' => $docgroupperso->getLabel()
				));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.docgroupperso.edit.txt', array(
					'%docgroupperso%' => $docgroupperso->getLabel()
				));

				return $this->renderResponse('AcfAdminBundle:Docgroupperso:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
		}

		return $this->redirect($urlFrom);
	}

	/**
	 *
	 * @param Docgroupperso $parent
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function childsAction(Docgroupperso $parent)
	{
		$em = $this->getEntityManager();
		$dg = $em->getRepository('AcfDataBundle:Docgroupperso')->find($parent);
		$this->gvars['parent'] = $dg;

		return $this->renderResponse('AcfAdminBundle:Docgroupperso:childs.html.twig', $this->gvars);
	}

	protected function traceEntity(Docgroupperso $cloneDocgroupperso, Docgroupperso $docgroupperso)
	{
		$curUser = $this->getSecurityTokenStorage()->getToken()->getUser();
		$trace = new Trace();
		$trace->setActionId($docgroupperso->getId());
		$trace->setActionType(Trace::AT_UPDATE);
		$trace->setUserId($curUser->getId());
		$trace->setCompanyId($docgroupperso->getCompany()->getId());
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

		$tableBegin = ': <br><table class="table table-bordered table-condensed table-hover table-striped">';
		$tableBegin .= '<thead><tr><th class="text-left">' . $this->translate('Entity.field') . '</th>';
		$tableBegin .= '<th class="text-left">' . $this->translate('Entity.oldVal') . '</th>';
		$tableBegin .= '<th class="text-left">' . $this->translate('Entity.newVal') . '</th></tr></thead><tbody>';

		$tableEnd = '</tbody></table>';

		$trace->setActionEntity(Trace::AE_DOCGROUPPERSO);
		$trace->setActionId2($docgroupperso->getCompany()->getId());
		$trace->setActionEntity2(Trace::AE_COMPANY);

		$msg = '';

		if ($cloneDocgroupperso->getLabel() != $docgroupperso->getLabel()) {
			$msg .= '<tr><td>' . $this->translate('Docgroupperso.label.label') . '</td><td>';
			if ($cloneDocgroupperso->getLabel() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneDocgroupperso->getLabel();
			}
			$msg .= '</td><td>';
			if ($docgroupperso->getLabel() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $docgroupperso->getLabel();
			}
			$msg .= '</td></tr>';
		}

		if ($cloneDocgroupperso->getParent() != $docgroupperso->getParent() && (($cloneDocgroupperso->getParent() == null && $docgroupperso->getParent() != null) || ($cloneDocgroupperso->getParent() != null && $docgroupperso->getParent() == null) || ($docgroupperso->getParent() != null && $cloneDocgroupperso->getParent() != null && $cloneDocgroupperso->getParent()->getPageUrlFull() != $docgroupperso->getParent()->getPageUrlFull()))) {
			$msg .= '<tr><td>' . $this->translate('Docgroupperso.parent.label') . '</td><td>';
			if ($cloneDocgroupperso->getParent() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneDocgroupperso->getParent()->getLabel();
			}
			$msg .= '</td><td>';
			if ($docgroupperso->getParent() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $docgroupperso->getParent()->getLabel();
			}
			$msg .= '</td></tr>';
		}

		if ($cloneDocgroupperso->getOtherInfos() != $docgroupperso->getOtherInfos()) {
			$msg .= '<tr><td>' . $this->translate('Docgroupperso.otherInfos.label') . '</td><td>';
			if ($cloneDocgroupperso->getOtherInfos() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneDocgroupperso->getOtherInfos();
			}
			$msg .= '</td><td>';
			if ($docgroupperso->getOtherInfos() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $docgroupperso->getOtherInfos();
			}
			$msg .= '</td></tr>';
		}

		if (\count(\array_diff($docgroupperso->getDocs()->toArray(), $cloneDocgroupperso->getDocs()->toArray())) != 0 || \count(\array_diff($cloneDocgroupperso->getDocs()->toArray(), $docgroupperso->getDocs()->toArray())) != 0) {
			$msg .= '<tr><td>' . $this->translate('Docgroupperso.docs.label') . '</td><td>';
			if (\count($cloneDocgroupperso->getDocs()) == 0) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= '<ul>';
				foreach ($cloneDocgroupperso->getDocs() as $doc) {
					$msg .= '<li><a href="' . $this->generateUrl('_admin_doc_editGet', array(
						'uid' => $doc->getId()
					)) . '">' . $doc->getOriginalName() . '</a></li>';
				}
				$msg .= '<ul>';
			}
			$msg .= '</td><td>';
			if (\count($docgroupperso->getDocs()) == 0) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= '<ul>';
				foreach ($docgroupperso->getDocs() as $doc) {
					$msg .= '<li><a href="' . $this->generateUrl('_admin_doc_editGet', array(
						'uid' => $doc->getId()
					)) . '">' . $doc->getOriginalName() . '</a></li>';
				}
				$msg .= '<ul>';
			}
			$msg .= '</td></tr>';
		}

		if ($msg != '') {

			$msg = $tableBegin . $msg . $tableEnd;

			$trace->setMsg($this->translate('Docgroupperso.traceEdit', array(
				'%docgroupperso%' => $docgroupperso->getLabel(),
				'%company%' => $docgroupperso->getCompany()->getCorporateName()
			)) . $msg);
			$trace->setDtCrea(new \DateTime('now'));
			$em = $this->getEntityManager();
			$em->persist($trace);
			$em->flush();
		}
	}
}
