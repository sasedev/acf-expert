<?php
namespace Acf\ClientBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\ClientBundle\Form\Buy\NewTForm as BuyNewTForm;
use Acf\ClientBundle\Form\MBPurchase\UpdateDocsTForm as MBPurchaseUpdateDocsTForm;
use Acf\ClientBundle\Form\Doc\NewTForm as DocNewTForm;
use Acf\DataBundle\Entity\Doc;
use Acf\DataBundle\Entity\Buy;
use Acf\DataBundle\Entity\MBPurchase;
use Acf\DataBundle\Entity\ConstantStr;
use Acf\DataBundle\Entity\CompanyUser;
use Acf\DataBundle\Entity\Trace;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class MBPurchaseController extends BaseController
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

	/**
	 *
	 * @param string $uid
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function editGetAction($uid)
	{
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_client_homepage');
		}

		$em = $this->getEntityManager();
		try {
			$mbpurchase = $em->getRepository('AcfDataBundle:MBPurchase')->find($uid);

			if (null == $mbpurchase) {
				$this->flashMsgSession('warning', $this->translate('MBPurchase.edit.notfound'));
			} else {

				$sc = $this->getSecurityTokenStorage();
				$user = $sc->getToken()->getUser();

				$company = $mbpurchase->getCompany();

				$companyUser = $em->getRepository('AcfDataBundle:CompanyUser')->findOneBy(array(
					'company' => $company,
					'user' => $user
				));
				if (null == $companyUser) {
					$this->flashMsgSession('error', $this->translate('CompanyUser.accessForbidden'));

					return $this->redirect($this->generateUrl('_client_homepage'));
				}
				$this->gvars['companyUser'] = $companyUser;
				$this->gvars['menu_active'] = 'client' . $company->getId();

				$buy = new Buy();
				$buy->setMonthlyBalance($mbpurchase);
				$buyNewForm = $this->createForm(BuyNewTForm::class, $buy, array(
					'monthlybalance' => $mbpurchase
				));

				$mbpurchaseUpdateDocsForm = $this->createForm(MBPurchaseUpdateDocsTForm::class, $mbpurchase, array(
					'company' => $mbpurchase->getCompany()
				));

				$doc = new Doc();
				$doc->setCompany($mbpurchase->getCompany());
				$docNewForm = $this->createForm(DocNewTForm::class, $doc, array(
					'company' => $mbpurchase->getCompany()
				));

				$this->gvars['mbpurchase'] = $mbpurchase;
				$this->gvars['buy'] = $buy;
				$this->gvars['doc'] = $doc;
				$this->gvars['BuyNewForm'] = $buyNewForm->createView();
				$this->gvars['MBPurchaseUpdateDocsForm'] = $mbpurchaseUpdateDocsForm->createView();
				$this->gvars['DocNewForm'] = $docNewForm->createView();

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
				$this->getSession()->remove('tabActive');

				$this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
				$this->getSession()->remove('stabActive');

				$suppliersConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
					'name' => 'suppliersPrefix'
				));
				if (null == $suppliersConstStr) {
					$suppliersConstStr = new ConstantStr();
					$suppliersConstStr->setName('suppliersPrefix');
					$suppliersConstStr->setValue('401');
					$em->persist($suppliersConstStr);
					$em->flush();
				}
				$suppliersPrefix = $suppliersConstStr->getValue();
				$this->gvars['suppliersPrefix'] = $suppliersPrefix;

				$this->gvars['pagetitle'] = $this->translate('pagetitle.mbpurchase.edit', array(
					'%mbpurchase%' => $mbpurchase->getRef()
				));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.mbpurchase.edit.txt', array(
					'%mbpurchase%' => $mbpurchase->getRef()
				));

				return $this->renderResponse('AcfClientBundle:MBPurchase:edit.html.twig', $this->gvars);
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
			$urlFrom = $this->generateUrl('_client_homepage');
		}

		$em = $this->getEntityManager();
		try {
			$mbpurchase = $em->getRepository('AcfDataBundle:MBPurchase')->find($uid);

			if (null == $mbpurchase) {
				$this->flashMsgSession('warning', $this->translate('MBPurchase.edit.notfound'));
			} else {

				$sc = $this->getSecurityTokenStorage();
				$user = $sc->getToken()->getUser();

				$company = $mbpurchase->getCompany();

				$companyUser = $em->getRepository('AcfDataBundle:CompanyUser')->findOneBy(array(
					'company' => $company,
					'user' => $user
				));
				if (null == $companyUser || $companyUser->getEditBuys() == CompanyUser::CANT) {
					$this->flashMsgSession('error', $this->translate('CompanyUser.accessForbidden'));

					return $this->redirect($this->generateUrl('_client_homepage'));
				}
				$this->gvars['companyUser'] = $companyUser;
				$this->gvars['menu_active'] = 'client' . $company->getId();

				$buy = new Buy();
				$buy->setMonthlyBalance($mbpurchase);
				$buyNewForm = $this->createForm(BuyNewTForm::class, $buy, array(
					'monthlybalance' => $mbpurchase
				));
				$mbpurchaseUpdateDocsForm = $this->createForm(MBPurchaseUpdateDocsTForm::class, $mbpurchase, array(
					'company' => $mbpurchase->getCompany()
				));

				$doc = new Doc();
				$doc->setCompany($mbpurchase->getCompany());
				$docNewForm = $this->createForm(DocNewTForm::class, $doc, array(
					'company' => $mbpurchase->getCompany()
				));

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
				$this->getSession()->remove('tabActive');

				$this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
				$this->getSession()->remove('stabActive');

				$request = $this->getRequest();
				$reqData = $request->request->all();

				$cloneMBPurchase = clone $mbpurchase;

				if (isset($reqData['BuyNewForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$this->gvars['stabActive'] = 2;
					$this->getSession()->set('stabActive', 2);
					$buyNewForm->handleRequest($request);
					if ($buyNewForm->isValid()) {
						$buy->setNumber($mbpurchase->getCount());
						if ($buy->getDevise() == 'TND') {
							$buy->setConversionRate(1);
							$buy->setVatDevise($buy->getVat());
							$buy->setStampDevise($buy->getStamp());
							$buy->setBalanceTtcDevise($buy->getBalanceTtc());
							$buy->setBalanceNetDevise($buy->getBalanceNet());
						} else {
							$buy->setVat($buy->getVatDevise() * $buy->getConversionRate());
							$buy->setStamp($buy->getStampDevise() * $buy->getConversionRate());
							$buy->setBalanceTtc($buy->getBalanceTtcDevise() * $buy->getConversionRate());
							$buy->setBalanceNet($buy->getBalanceNetDevise() * $buy->getConversionRate());
						}
						foreach ($buyNewForm->get('docs') as $docNewForm) {
							$docFile = $docNewForm['fileName']->getData();
							$docDir = $this->getParameter('kernel.root_dir') . '/../web/res/docs';
							$originalName = $docFile->getClientOriginalName();
							$fileName = sha1(uniqid(mt_rand(), true)) . '.' . strtolower($docFile->getClientOriginalExtension());
							$mimeType = $docFile->getMimeType();
							$docFile->move($docDir, $fileName);
							$size = filesize($docDir . '/' . $fileName);
							$md5 = md5_file($docDir . '/' . $fileName);

							$doc = $docNewForm->getData();
							$doc->setCompany($mbpurchase->getCompany());
							$doc->setFileName($fileName);
							$doc->setOriginalName($originalName);
							$doc->setSize($size);
							$doc->setMimeType($mimeType);
							$doc->setMd5($md5);
							$doc->setDescription($docNewForm['description']->getData());
							$em->persist($doc);

							$buy->addDoc($doc);
						}
						$em->persist($buy);
						$em->flush();
						$mbpurchase->updateCount();
						$em->persist($mbpurchase);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Buy.add.success', array(
							'%buy%' => $buy->getNumber()
						)));
						$this->gvars['tabActive'] = 1;
						$this->getSession()->set('tabActive', 1);
						$this->gvars['stabActive'] = 1;
						$this->getSession()->set('stabActive', 1);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($mbpurchase);

						$this->flashMsgSession('error', $this->translate('Buy.add.failure'));
					}
				} elseif (isset($reqData['DocNewForm'])) {
					$this->gvars['tabActive'] = 4;
					$this->getSession()->set('tabActive', 4);
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
							$doc->setCompany($mbpurchase->getCompany());

							$doc->setFileName($fileName);
							$doc->setOriginalName($originalName);
							$doc->setSize($size);
							$doc->setMimeType($mimeType);
							$doc->setMd5($md5);
							$doc->setDescription($docNewForm['description']->getData());
							$em->persist($doc);

							$mbpurchase->addDoc($doc);

							$docs[] = $doc;

							$docNames .= $doc->getOriginalName() . ' ';
						}

						$em->persist($mbpurchase);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Doc.add.success', array(
							'%doc%' => $docNames
						)));
						$this->newDocNotifyAdmin($mbpurchase, $docs);
						$this->gvars['stabActive'] = 3;
						$this->getSession()->set('stabActive', 3);

						$this->traceEntity($cloneMBPurchase, $mbpurchase);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($mbpurchase);

						$this->flashMsgSession('error', $this->translate('Doc.add.failure'));
					}
				} elseif (isset($reqData['MBPurchaseUpdateDocsForm'])) {
					$this->gvars['tabActive'] = 4;
					$this->getSession()->set('tabActive', 4);
					$this->gvars['stabActive'] = 2;
					$this->getSession()->set('stabActive', 2);
					$mbpurchaseUpdateDocsForm->handleRequest($request);
					if ($mbpurchaseUpdateDocsForm->isValid()) {
						$em->persist($mbpurchase);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('MBPurchase.edit.success', array(
							'%mbpurchase%' => $mbpurchase->getRef()
						)));
						$this->gvars['stabActive'] = 3;
						$this->getSession()->set('stabActive', 3);

						$this->traceEntity($cloneMBPurchase, $mbpurchase);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($mbpurchase);

						$this->flashMsgSession('error', $this->translate('MBPurchase.edit.failure', array(
							'%mbpurchase%' => $mbpurchase->getRef()
						)));
					}
				}

				$this->gvars['mbpurchase'] = $mbpurchase;
				$this->gvars['buy'] = $buy;
				$this->gvars['doc'] = $doc;
				$this->gvars['BuyNewForm'] = $buyNewForm->createView();
				$this->gvars['MBPurchaseUpdateDocsForm'] = $mbpurchaseUpdateDocsForm->createView();
				$this->gvars['DocNewForm'] = $docNewForm->createView();

				$suppliersConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
					'name' => 'suppliersPrefix'
				));
				if (null == $suppliersConstStr) {
					$suppliersConstStr = new ConstantStr();
					$suppliersConstStr->setName('suppliersPrefix');
					$suppliersConstStr->setValue('401');
					$em->persist($suppliersConstStr);
					$em->flush();
				}
				$suppliersPrefix = $suppliersConstStr->getValue();
				$this->gvars['suppliersPrefix'] = $suppliersPrefix;

				$this->gvars['pagetitle'] = $this->translate('pagetitle.mbpurchase.edit', array(
					'%mbpurchase%' => $mbpurchase->getRef()
				));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.mbpurchase.edit.txt', array(
					'%mbpurchase%' => $mbpurchase->getRef()
				));

				return $this->renderResponse('AcfClientBundle:MBPurchase:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
		}

		return $this->redirect($urlFrom);
	}

	protected function newDocNotifyAdmin(MBPurchase $mp, $docs)
	{
		$from = $this->getParameter('mail_from');
		$fromName = $this->getParameter('mail_from_name');
		$subject = $this->translate('_mail.newdocs.subject', array(), 'messages');

		$user = $this->getSecurityTokenStorage()->getToken()->getUser();
		$company = $mp->getCompany();

		$admins = $company->getAdmins();
		if (\count($admins) != 0) {
			$mvars = array();
			$mvars['mp'] = $mp;
			$mvars['user'] = $user;
			$mvars['company'] = $company;
			$mvars['docs'] = $docs;
			$message = \Swift_Message::newInstance();
			$message->setFrom($from, $fromName);
			foreach ($admins as $admin) {
				$message->addTo($admin->getEmail(), $admin->getFullname());
			}
			$message->setSubject($subject);
			$mvars['logo'] = $message->embed(\Swift_Image::fromPath($this->getParameter('kernel.root_dir') . '/../web/bundles/acfres/images/logo_acf.jpg'));
			$message->setBody($this->renderView('AcfClientBundle:Mail:MBPurchasenewdoc.html.twig', $mvars), 'text/html');
			$this->sendmail($message);
		}
	}

	protected function traceEntity(MBPurchase $cloneMBPurchase, MBPurchase $mbpurchase)
	{
		$curUser = $this->getSecurityTokenStorage()->getToken()->getUser();
		$trace = new Trace();
		$trace->setActionId($mbpurchase->getId());
		$trace->setActionType(Trace::AT_UPDATE);
		$trace->setUserId($curUser->getId());
		$trace->setCompanyId($mbpurchase->getCompany()->getId());
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

		$trace->setActionEntity(Trace::AE_MBPURCHASE);
		$trace->setActionId2($mbpurchase->getCompany()->getId());
		$trace->setActionEntity2(Trace::AE_COMPANY);

		$msg = '';

		if ($cloneMBPurchase->getCount() != $mbpurchase->getCount()) {
			$msg .= '<tr><td>' . $this->translate('MBPurchase.count.label') . '</td><td>';
			if ($cloneMBPurchase->getCount() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneMBPurchase->getCount();
			}
			$msg .= '</td><td>';
			if ($mbpurchase->getCount() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $mbpurchase->getCount();
			}
			$msg .= '</td></tr>';
		}

		if (\count(\array_diff($mbpurchase->getDocs()->toArray(), $cloneMBPurchase->getDocs()->toArray())) != 0 || \count(\array_diff($cloneMBPurchase->getDocs()->toArray(), $mbpurchase->getDocs()->toArray())) != 0) {
			$msg .= '<tr><td>' . $this->translate('MBPurchase.docs.label') . '</td><td>';
			if (\count($cloneMBPurchase->getDocs()) == 0) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= '<ul>';
				foreach ($cloneMBPurchase->getDocs() as $doc) {
					$msg .= '<li><a href="' . $this->generateUrl('_admin_doc_editGet', array(
						'uid' => $doc->getId()
					)) . '">' . $doc->getOriginalName() . '</a></li>';
				}
				$msg .= '<ul>';
			}
			$msg .= '</td><td>';
			if (\count($mbpurchase->getDocs()) == 0) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= '<ul>';
				foreach ($mbpurchase->getDocs() as $doc) {
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

			$trace->setMsg($this->translate('MBPurchase.traceEdit', array(
				'%mbpurchase%' => $mbpurchase->getRef(),
				'%company%' => $mbpurchase->getCompany()->getCorporateName()
			)) . $msg);
			$trace->setDtCrea(new \DateTime('now'));
			$em = $this->getEntityManager();
			$em->persist($trace);
			$em->flush();
		}
	}
}
