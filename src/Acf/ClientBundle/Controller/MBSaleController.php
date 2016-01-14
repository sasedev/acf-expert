<?php

namespace Acf\ClientBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\ClientBundle\Form\Sale\NewTForm as SaleNewTForm;
use Acf\ClientBundle\Form\MBSale\UpdateDocsTForm as MBSaleUpdateDocsTForm;
use Acf\ClientBundle\Form\Doc\NewTForm as DocNewTForm;
use Acf\DataBundle\Entity\Doc;
use Acf\DataBundle\Entity\Sale;
use Acf\DataBundle\Entity\MBSale;
use Acf\DataBundle\Entity\ConstantStr;
use Acf\DataBundle\Entity\CompanyUser;
use Acf\DataBundle\Entity\Trace;

/**
 *
 * @author sasedev
 */
class MBSaleController extends BaseController
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
			$mbsale = $em->getRepository('AcfDataBundle:MBSale')->find($uid);

			if (null == $mbsale) {
				$this->flashMsgSession('warning', $this->translate('MBSale.edit.notfound'));
			} else {

				$sc = $this->getSecurityTokenStorage();
				$user = $sc->getToken()->getUser();

				$company = $mbsale->getCompany();

				$companyUser = $em->getRepository('AcfDataBundle:CompanyUser')->findOneBy(array('company' => $company, 'user' => $user));
				if (null == $companyUser) {
					$this->flashMsgSession('error', $this->translate('CompanyUser.accessForbidden'));
					return $this->redirect($this->generateUrl('_client_homepage'));
				}
				$this->gvars['companyUser'] = $companyUser;
				$this->gvars['menu_active'] = 'client' . $company->getId();

				$sale = new Sale();
				$sale->setMonthlyBalance($mbsale);
				$saleNewForm = $this->createForm(SaleNewTForm::class, $sale, array('monthlybalance' => $mbsale));
				$mbsaleUpdateDocsForm = $this->createForm(MBSaleUpdateDocsTForm::class, $mbsale, array('company' => $mbsale->getCompany()));

				$doc = new Doc();
				$doc->setCompany($mbsale->getCompany());
				$docNewForm = $this->createForm(DocNewTForm::class, $doc, array('company' => $mbsale->getCompany()));

				$this->gvars['mbsale'] = $mbsale;
				$this->gvars['sale'] = $sale;
				$this->gvars['doc'] = $doc;
				$this->gvars['SaleNewForm'] = $saleNewForm->createView();
				$this->gvars['MBSaleUpdateDocsForm'] = $mbsaleUpdateDocsForm->createView();
				$this->gvars['DocNewForm'] = $docNewForm->createView();

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
				$this->getSession()->remove('tabActive');

				$this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
				$this->getSession()->remove('stabActive');

				$customersConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array('name' => 'customersPrefix'));
				if (null == $customersConstStr) {
					$customersConstStr = new ConstantStr();
					$customersConstStr->setName('customersPrefix');
					$customersConstStr->setValue('411');
					$em->persist($customersConstStr);
					$em->flush();
				}
				$customersPrefix = $customersConstStr->getValue();
				$this->gvars['customersPrefix'] = $customersPrefix;

				$this->gvars['pagetitle'] = $this->translate('pagetitle.mbsale.edit', array('%mbsale%' => $mbsale->getRef()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.mbsale.edit.txt', array('%mbsale%' => $mbsale->getRef()));

				return $this->renderResponse('AcfClientBundle:MBSale:edit.html.twig', $this->gvars);
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
			$mbsale = $em->getRepository('AcfDataBundle:MBSale')->find($uid);

			if (null == $mbsale) {
				$this->flashMsgSession('warning', $this->translate('MBSale.edit.notfound'));
			} else {

				$sc = $this->getSecurityTokenStorage();
				$user = $sc->getToken()->getUser();

				$company = $mbsale->getCompany();

				$companyUser = $em->getRepository('AcfDataBundle:CompanyUser')->findOneBy(array('company' => $company, 'user' => $user));
				if (null == $companyUser || $companyUser->getEditSales() == CompanyUser::CANT) {
					$this->flashMsgSession('error', $this->translate('CompanyUser.accessForbidden'));
					return $this->redirect($this->generateUrl('_client_homepage'));
				}
				$this->gvars['companyUser'] = $companyUser;
				$this->gvars['menu_active'] = 'client' . $company->getId();

				$sale = new Sale();
				$sale->setMonthlyBalance($mbsale);
				$saleNewForm = $this->createForm(SaleNewTForm::class, $sale, array('monthlybalance' => $mbsale));
				$mbsaleUpdateDocsForm = $this->createForm(MBSaleUpdateDocsTForm::class, $mbsale, array('company' => $mbsale->getCompany()));

				$doc = new Doc();
				$doc->setCompany($mbsale->getCompany());
				$docNewForm = $this->createForm(DocNewTForm::class, $doc, array('company' => $mbsale->getCompany()));

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
				$this->getSession()->remove('tabActive');

				$this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
				$this->getSession()->remove('stabActive');

				$request = $this->getRequest();
				$reqData = $request->request->all();

				$cloneMBSale = clone $mbsale;

				if (isset($reqData['SaleNewForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$this->gvars['stabActive'] = 2;
					$this->getSession()->set('stabActive', 2);
					$saleNewForm->handleRequest($request);
					if ($saleNewForm->isValid()) {
						$sale->setNumber($mbsale->getCount());
						if ($sale->getDevise() == 'TND') {
							$sale->setConversionRate(1);
							$sale->setVatDevise($sale->getVat());
							$sale->setStampDevise($sale->getStamp());
							$sale->setBalanceTtcDevise($sale->getBalanceTtc());
							$sale->setBalanceNetDevise($sale->getBalanceNet());
						} else {
							$sale->setVat($sale->getVatDevise() * $sale->getConversionRate());
							$sale->setStamp($sale->getStampDevise() * $sale->getConversionRate());
							$sale->setBalanceTtc($sale->getBalanceTtcDevise() * $sale->getConversionRate());
							$sale->setBalanceNet($sale->getBalanceNetDevise() * $sale->getConversionRate());
						}
						foreach ($saleNewForm->get('docs') as $docNewForm) {
							$docFile = $docNewForm['fileName']->getData();
							$docDir = $this->getParameter('kernel.root_dir') . '/../web/res/docs';
							$originalName = $docFile->getClientOriginalName();
							$fileName = sha1(uniqid(mt_rand(), true)) . '.' . strtolower($docFile->getClientOriginalExtension());
							$mimeType = $docFile->getMimeType();
							$docFile->move($docDir, $fileName);
							$size = filesize($docDir . '/' . $fileName);
							$md5 = md5_file($docDir . '/' . $fileName);

							$doc = $docNewForm->getData();
							$doc->setCompany($mbsale->getCompany());
							$doc->setFileName($fileName);
							$doc->setOriginalName($originalName);
							$doc->setSize($size);
							$doc->setMimeType($mimeType);
							$doc->setMd5($md5);
							$doc->setDescription($docNewForm['description']->getData());
							$em->persist($doc);

							$sale->addDoc($doc);
						}
						$em->persist($sale);
						foreach ($saleNewForm->get('secondaryVats') as $secondaryVatNewForm) {
							$secondaryVat = $secondaryVatNewForm->getData();
							$secondaryVat->setSale($sale);
							$sale->addSecondaryVat($secondaryVat);
							$em->persist($secondaryVat);
							$em->persist($sale);
						}
						$em->flush();
						$mbsale->updateCount();
						$em->persist($mbsale);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Sale.add.success', array('%sale%' => $sale->getNumber())));
						$this->gvars['tabActive'] = 1;
						$this->getSession()->set('tabActive', 1);
						$this->gvars['stabActive'] = 1;
						$this->getSession()->set('stabActive', 1);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($mbsale);

						$this->flashMsgSession('error', $this->translate('Sale.add.failure'));
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

						$docNames = "";

						foreach ($docFiles as $docFile) {

							$originalName = $docFile->getClientOriginalName();
							$fileName = sha1(uniqid(mt_rand(), true)) . '.' . strtolower($docFile->getClientOriginalExtension());
							$mimeType = $docFile->getMimeType();
							$docFile->move($docDir, $fileName);

							$size = filesize($docDir . '/' . $fileName);
							$md5 = md5_file($docDir . '/' . $fileName);

							$doc = new Doc();
							$doc->setCompany($mbsale->getCompany());

							$doc->setFileName($fileName);
							$doc->setOriginalName($originalName);
							$doc->setSize($size);
							$doc->setMimeType($mimeType);
							$doc->setMd5($md5);
							$doc->setDescription($docNewForm['description']->getData());
							$em->persist($doc);

							$mbsale->addDoc($doc);

							$docs[] = $doc;

							$docNames .= $doc->getOriginalName() . " ";
						}

						$em->persist($mbsale);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Doc.add.success', array('%doc%' => $docNames)));
						$this->newDocNotifyAdmin($mbsale, $docs);
						$this->gvars['stabActive'] = 3;
						$this->getSession()->set('stabActive', 3);

						$this->traceEntity($cloneMBSale, $mbsale);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($mbsale);

						$this->flashMsgSession('error', $this->translate('Doc.add.failure'));
					}
				} elseif (isset($reqData['MBSaleUpdateDocsForm'])) {
					$this->gvars['tabActive'] = 4;
					$this->getSession()->set('tabActive', 4);
					$this->gvars['stabActive'] = 2;
					$this->getSession()->set('stabActive', 2);
					$mbsaleUpdateDocsForm->handleRequest($request);
					if ($mbsaleUpdateDocsForm->isValid()) {
						$em->persist($mbsale);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('MBSale.edit.success', array('%mbsale%' => $mbsale->getRef())));
						$this->gvars['stabActive'] = 3;
						$this->getSession()->set('stabActive', 3);

						$this->traceEntity($cloneMBSale, $mbsale);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($mbsale);

						$this->flashMsgSession('error', $this->translate('MBSale.edit.failure', array('%mbsale%' => $mbsale->getRef())));
					}
				}

				$this->gvars['mbsale'] = $mbsale;
				$this->gvars['sale'] = $sale;
				$this->gvars['doc'] = $doc;
				$this->gvars['SaleNewForm'] = $saleNewForm->createView();
				$this->gvars['MBSaleUpdateDocsForm'] = $mbsaleUpdateDocsForm->createView();
				$this->gvars['DocNewForm'] = $docNewForm->createView();

				$customersConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array('name' => 'customersPrefix'));
				if (null == $customersConstStr) {
					$customersConstStr = new ConstantStr();
					$customersConstStr->setName('customersPrefix');
					$customersConstStr->setValue('411');
					$em->persist($customersConstStr);
					$em->flush();
				}
				$customersPrefix = $customersConstStr->getValue();
				$this->gvars['customersPrefix'] = $customersPrefix;

				$this->gvars['pagetitle'] = $this->translate('pagetitle.mbsale.edit', array('%mbsale%' => $mbsale->getRef()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.mbsale.edit.txt', array('%mbsale%' => $mbsale->getRef()));

				return $this->renderResponse('AcfClientBundle:MBSale:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
		}

		return $this->redirect($urlFrom);
	}

	protected function newDocNotifyAdmin(MBSale $ms, $docs)
	{
		$from = $this->getParameter('mail_from');
		$fromName = $this->getParameter('mail_from_name');
		$subject = $this->translate('_mail.newdocs.subject', array(), 'messages');

		$user = $this->getSecurityTokenStorage()
			->getToken()
			->getUser();
		$company = $ms->getCompany();

		$admins = $company->getAdmins();
		if (\count($admins) != 0) {
			$mvars = array();
			$mvars['ms'] = $ms;
			$mvars['user'] = $user;
			$mvars['company'] = $company;
			$mvars['docs'] = $docs;
			$message = \Swift_Message::newInstance();
			$message->setFrom($from, $fromName);
			foreach ($admins as $admin) {
				$message->addTo($admin->getEmail(), $admin->getFullname());
			}
			$message->setSubject($subject);
			$message->setBody($this->renderView('AcfClientBundle:Mail:MBSalenewdoc.html.twig', $mvars), 'text/html');
			$this->sendmail($message);
		}

	}

	protected function traceEntity(MBSale $cloneMBSale, MBSale $mbsale)
	{
		$curUser = $this->getSecurityTokenStorage()
			->getToken()
			->getUser();
		$trace = new Trace();
		$trace->setActionId($mbsale->getId());
		$trace->setActionType(Trace::AT_UPDATE);
		$trace->setUserId($curUser->getId());
		$trace->setCompanyId($mbsale->getCompany()
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

		$trace->setActionEntity(Trace::AE_MBSALE);
		$trace->setActionId2($mbsale->getCompany()
			->getId());
		$trace->setActionEntity2(Trace::AE_COMPANY);

		$msg = "";

		if ($cloneMBSale->getCount() != $mbsale->getCount()) {
			$msg .= "<tr><td>" . $this->translate('MBSale.count.label') . '</td><td>';
			if ($cloneMBSale->getCount() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneMBSale->getCount();
			}
			$msg .= "</td><td>";
			if ($mbsale->getCount() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $mbsale->getCount();
			}
			$msg .= "</td></tr>";
		}

		if (\count(\array_diff($mbsale->getDocs()->toArray(), $cloneMBSale->getDocs()->toArray())) != 0 ||
			 \count(\array_diff($cloneMBSale->getDocs()->toArray(), $mbsale->getDocs()->toArray())) != 0) {
			$msg .= "<tr><td>" . $this->translate('MBSale.docs.label') . '</td><td>';
			if (\count($cloneMBSale->getDocs()) == 0) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= "<ul>";
				foreach ($cloneMBSale->getDocs() as $doc) {
					$msg .= '<li><a href="' . $this->generateUrl('_admin_doc_editGet', array('uid' => $doc->getId())) . '">' .
						 $doc->getOriginalName() . '</a></li>';
				}
				$msg .= "<ul>";
			}
			$msg .= "</td><td>";
			if (\count($mbsale->getDocs()) == 0) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= "<ul>";
				foreach ($mbsale->getDocs() as $doc) {
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
				$this->translate('MBSale.traceEdit',
					array('%mbsale%' => $mbsale->getLabel(), '%company%' => $mbsale->getCompany()
						->getCorporateName())) . $msg);
			$trace->setDtCrea(new \DateTime('now'));
			$em = $this->getEntityManager();
			$em->persist($trace);
			$em->flush();
		}
	}
}
