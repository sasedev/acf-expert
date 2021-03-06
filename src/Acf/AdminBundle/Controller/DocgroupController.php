<?php
namespace Acf\AdminBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\Docgroup;
use Acf\AdminBundle\Form\Docgroup\UpdateLabelTForm as DocgroupUpdateLabelTForm;
use Acf\AdminBundle\Form\Docgroup\UpdateOtherInfosTForm as DocgroupUpdateOtherInfosTForm;
use Acf\AdminBundle\Form\Docgroup\UpdateDocsTForm as DocgroupUpdateDocsTForm;
use Acf\AdminBundle\Form\Docgroup\UpdateParentTForm as DocgroupUpdateParentTForm;
use Acf\AdminBundle\Form\Doc\NewTForm as DocNewTForm;
use Acf\DataBundle\Entity\Doc;
use Acf\DataBundle\Entity\Trace;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class DocgroupController extends BaseController
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

		return $this->redirect($this->generateUrl('_admin_docgroup_editGet', array(
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
			$docgroup = $em->getRepository('AcfDataBundle:Docgroup')->find($uid);

			if (null == $docgroup) {
				$this->flashMsgSession('warning', $this->translate('Docgroup.delete.notfound'));
			} else {
				$em->remove($docgroup);
				$em->flush();

				$this->flashMsgSession('success', $this->translate('Docgroup.delete.success', array(
					'%docgroup%' => $docgroup->getLabel()
				)));
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

			$this->flashMsgSession('error', $this->translate('Docgroup.delete.failure'));
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
			$docgroup = $em->getRepository('AcfDataBundle:Docgroup')->find($uid);

			if (null == $docgroup) {
				$this->flashMsgSession('warning', $this->translate('Docgroup.edit.notfound'));
			} else {
				$traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($docgroup->getId(), Trace::AE_DOCGROUP);
				$this->gvars['traces'] = array_reverse($traces);
				$docgroupUpdateLabelForm = $this->createForm(DocgroupUpdateLabelTForm::class, $docgroup);
				$docgroupUpdateOtherInfosForm = $this->createForm(DocgroupUpdateOtherInfosTForm::class, $docgroup);
				$docgroupUpdateParentForm = $this->createForm(DocgroupUpdateParentTForm::class, $docgroup, array(
					'selfUrl' => $docgroup->getPageUrlFull(),
					'company' => $docgroup->getCompany()
				));
				$docgroupUpdateDocsForm = $this->createForm(DocgroupUpdateDocsTForm::class, $docgroup, array(
					'company' => $docgroup->getCompany()
				));

				$doc = new Doc();
				$doc->setCompany($docgroup->getCompany());
				$docNewForm = $this->createForm(DocNewTForm::class, $doc, array(
					'company' => $docgroup->getCompany()
				));

				$this->gvars['docgroup'] = $docgroup;
				$this->gvars['doc'] = $doc;
				$this->gvars['DocgroupUpdateLabelForm'] = $docgroupUpdateLabelForm->createView();
				$this->gvars['DocgroupUpdateOtherInfosForm'] = $docgroupUpdateOtherInfosForm->createView();
				$this->gvars['DocgroupUpdateParentForm'] = $docgroupUpdateParentForm->createView();
				$this->gvars['DocgroupUpdateDocsForm'] = $docgroupUpdateDocsForm->createView();
				$this->gvars['DocNewForm'] = $docNewForm->createView();

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
				$this->getSession()->remove('tabActive');

				$this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
				$this->getSession()->remove('stabActive');

				$this->gvars['pagetitle'] = $this->translate('pagetitle.docgroup.edit', array(
					'%docgroup%' => $docgroup->getLabel()
				));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.docgroup.edit.txt', array(
					'%docgroup%' => $docgroup->getLabel()
				));

				return $this->renderResponse('AcfAdminBundle:Docgroup:edit.html.twig', $this->gvars);
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
			$docgroup = $em->getRepository('AcfDataBundle:Docgroup')->find($uid);

			if (null == $docgroup) {
				$this->flashMsgSession('warning', $this->translate('Docgroup.edit.notfound'));
			} else {
				$traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($docgroup->getId(), Trace::AE_DOCGROUP);
				$this->gvars['traces'] = array_reverse($traces);
				$docgroupUpdateLabelForm = $this->createForm(DocgroupUpdateLabelTForm::class, $docgroup);
				$docgroupUpdateOtherInfosForm = $this->createForm(DocgroupUpdateOtherInfosTForm::class, $docgroup);
				$docgroupUpdateParentForm = $this->createForm(DocgroupUpdateParentTForm::class, $docgroup, array(
					'selfUrl' => $docgroup->getPageUrlFull(),
					'company' => $docgroup->getCompany()
				));
				$docgroupUpdateDocsForm = $this->createForm(DocgroupUpdateDocsTForm::class, $docgroup, array(
					'company' => $docgroup->getCompany()
				));

				$doc = new Doc();
				$doc->setCompany($docgroup->getCompany());
				$docNewForm = $this->createForm(DocNewTForm::class, $doc, array(
					'company' => $docgroup->getCompany()
				));

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
				$this->getSession()->remove('tabActive');

				$this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
				$this->getSession()->remove('stabActive');

				$request = $this->getRequest();
				$reqData = $request->request->all();

				$cloneDocgroup = clone $docgroup;

				if (isset($reqData['DocgroupUpdateLabelForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$docgroupUpdateLabelForm->handleRequest($request);
					if ($docgroupUpdateLabelForm->isValid()) {
						$em->persist($docgroup);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Docgroup.edit.success', array(
							'%docgroup%' => $docgroup->getLabel()
						)));

						$this->traceEntity($cloneDocgroup, $docgroup);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($docgroup);

						$this->flashMsgSession('error', $this->translate('Docgroup.edit.failure', array(
							'%docgroup%' => $docgroup->getLabel()
						)));
					}
				} elseif (isset($reqData['DocgroupUpdateOtherInfosForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$docgroupUpdateOtherInfosForm->handleRequest($request);
					if ($docgroupUpdateOtherInfosForm->isValid()) {
						$em->persist($docgroup);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Docgroup.edit.success', array(
							'%docgroup%' => $docgroup->getLabel()
						)));

						$this->traceEntity($cloneDocgroup, $docgroup);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($docgroup);

						$this->flashMsgSession('error', $this->translate('Docgroup.edit.failure', array(
							'%docgroup%' => $docgroup->getLabel()
						)));
					}
				} elseif (isset($reqData['DocgroupUpdateParentForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$docgroupUpdateParentForm->handleRequest($request);
					if ($docgroupUpdateParentForm->isValid()) {
						$em->persist($docgroup);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Docgroup.edit.success', array(
							'%docgroup%' => $docgroup->getLabel()
						)));

						$this->traceEntity($cloneDocgroup, $docgroup);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($docgroup);

						$this->flashMsgSession('error', $this->translate('Docgroup.edit.failure', array(
							'%docgroup%' => $docgroup->getLabel()
						)));
					}
				} elseif (isset($reqData['DocNewForm'])) {
					$this->gvars['tabActive'] = 3;
					$this->getSession()->set('tabActive', 3);
					$this->gvars['stabActive'] = 1;
					$this->getSession()->set('stabActive', 1);
					$docNewForm->handleRequest($request);
					if ($docNewForm->isValid()) {
						$docs = array();
						$docFiles = $docNewForm['fileName']->getData();

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
							$doc->setCompany($docgroup->getCompany());

							$doc->setFileName($fileName);
							$doc->setOriginalName($originalName);
							$doc->setSize($size);
							$doc->setMimeType($mimeType);
							$doc->setMd5($md5);
							$doc->setDescription($docNewForm['description']->getData());
							$em->persist($doc);

							$docgroup->addDoc($doc);

							$docNames .= $doc->getOriginalName() . ' ';

							$docs[] = $doc;
						}

						$em->persist($docgroup);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Doc.add.success', array(
							'%doc%' => $docNames
						)));

						$from = $this->getParameter('mail_from');
						$fromName = $this->getParameter('mail_from_name');
						$subject = $this->translate('_mail.newdocsCloud.subject', array(), 'messages');

						$company = $docgroup->getCompany();
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

						$this->traceEntity($cloneDocgroup, $docgroup);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($docgroup);

						$this->flashMsgSession('error', $this->translate('Doc.add.failure'));
					}
				} elseif (isset($reqData['DocgroupUpdateDocsForm'])) {
					$this->gvars['tabActive'] = 3;
					$this->getSession()->set('tabActive', 3);
					$this->gvars['stabActive'] = 2;
					$this->getSession()->set('stabActive', 2);
					$docgroupUpdateDocsForm->handleRequest($request);
					if ($docgroupUpdateDocsForm->isValid()) {
						$em->persist($docgroup);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Docgroup.edit.success', array(
							'%docgroup%' => $docgroup->getLabel()
						)));
						$this->gvars['stabActive'] = 3;
						$this->getSession()->set('stabActive', 3);

						$this->traceEntity($cloneDocgroup, $docgroup);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($docgroup);

						$this->flashMsgSession('error', $this->translate('Docgroup.edit.failure', array(
							'%docgroup%' => $docgroup->getLabel()
						)));
					}
				}

				$this->gvars['docgroup'] = $docgroup;
				$this->gvars['doc'] = $doc;
				$this->gvars['DocgroupUpdateLabelForm'] = $docgroupUpdateLabelForm->createView();
				$this->gvars['DocgroupUpdateOtherInfosForm'] = $docgroupUpdateOtherInfosForm->createView();
				$this->gvars['DocgroupUpdateParentForm'] = $docgroupUpdateParentForm->createView();
				$this->gvars['DocgroupUpdateDocsForm'] = $docgroupUpdateDocsForm->createView();
				$this->gvars['DocNewForm'] = $docNewForm->createView();

				$this->gvars['pagetitle'] = $this->translate('pagetitle.docgroup.edit', array(
					'%docgroup%' => $docgroup->getLabel()
				));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.docgroup.edit.txt', array(
					'%docgroup%' => $docgroup->getLabel()
				));

				return $this->renderResponse('AcfAdminBundle:Docgroup:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
		}

		return $this->redirect($urlFrom);
	}

	/**
	 *
	 * @param Docgroup $parent
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function childsAction(Docgroup $parent)
	{
		$em = $this->getEntityManager();
		$dg = $em->getRepository('AcfDataBundle:Docgroup')->find($parent);
		$this->gvars['parent'] = $dg;

		return $this->renderResponse('AcfAdminBundle:Docgroup:childs.html.twig', $this->gvars);
	}

	protected function traceEntity(Docgroup $cloneDocgroup, Docgroup $docgroup)
	{
		$curUser = $this->getSecurityTokenStorage()->getToken()->getUser();
		$trace = new Trace();
		$trace->setActionId($docgroup->getId());
		$trace->setActionType(Trace::AT_UPDATE);
		$trace->setUserId($curUser->getId());
		$trace->setCompanyId($docgroup->getCompany()->getId());
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

		$trace->setActionEntity(Trace::AE_DOCGROUP);
		$trace->setActionId2($docgroup->getCompany()->getId());
		$trace->setActionEntity2(Trace::AE_COMPANY);

		$msg = '';

		if ($cloneDocgroup->getLabel() != $docgroup->getLabel()) {
			$msg .= '<tr><td>' . $this->translate('Docgroup.label.label') . '</td><td>';
			if ($cloneDocgroup->getLabel() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneDocgroup->getLabel();
			}
			$msg .= '</td><td>';
			if ($docgroup->getLabel() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $docgroup->getLabel();
			}
			$msg .= '</td></tr>';
		}

		if ($cloneDocgroup->getParent() != $docgroup->getParent() && (($cloneDocgroup->getParent() == null && $docgroup->getParent() != null) || ($cloneDocgroup->getParent() != null && $docgroup->getParent() == null) || ($docgroup->getParent() != null && $cloneDocgroup->getParent() != null && $cloneDocgroup->getParent()->getPageUrlFull() != $docgroup->getParent()->getPageUrlFull()))) {
			$msg .= '<tr><td>' . $this->translate('Docgroup.parent.label') . '</td><td>';
			if ($cloneDocgroup->getParent() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneDocgroup->getParent()->getLabel();
			}
			$msg .= '</td><td>';
			if ($docgroup->getParent() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $docgroup->getParent()->getLabel();
			}
			$msg .= '</td></tr>';
		}

		if ($cloneDocgroup->getOtherInfos() != $docgroup->getOtherInfos()) {
			$msg .= '<tr><td>' . $this->translate('Docgroup.otherInfos.label') . '</td><td>';
			if ($cloneDocgroup->getOtherInfos() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneDocgroup->getOtherInfos();
			}
			$msg .= '</td><td>';
			if ($docgroup->getOtherInfos() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $docgroup->getOtherInfos();
			}
			$msg .= '</td></tr>';
		}

		if (\count(\array_diff($docgroup->getDocs()->toArray(), $cloneDocgroup->getDocs()->toArray())) != 0 || \count(\array_diff($cloneDocgroup->getDocs()->toArray(), $docgroup->getDocs()->toArray())) != 0) {
			$msg .= '<tr><td>' . $this->translate('Docgroup.docs.label') . '</td><td>';
			if (\count($cloneDocgroup->getDocs()) == 0) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= '<ul>';
				foreach ($cloneDocgroup->getDocs() as $doc) {
					$msg .= '<li><a href="' . $this->generateUrl('_admin_doc_editGet', array(
						'uid' => $doc->getId()
					)) . '">' . $doc->getOriginalName() . '</a></li>';
				}
				$msg .= '<ul>';
			}
			$msg .= '</td><td>';
			if (\count($docgroup->getDocs()) == 0) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= '<ul>';
				foreach ($docgroup->getDocs() as $doc) {
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

			$trace->setMsg($this->translate('Docgroup.traceEdit', array(
				'%docgroup%' => $docgroup->getLabel(),
				'%company%' => $docgroup->getCompany()->getCorporateName()
			)) . $msg);
			$trace->setDtCrea(new \DateTime('now'));
			$em = $this->getEntityManager();
			$em->persist($trace);
			$em->flush();
		}
	}
}
