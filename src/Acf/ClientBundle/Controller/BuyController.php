<?php

namespace Acf\ClientBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\Buy;
use Acf\ClientBundle\Form\Buy\UpdateNumberTForm as BuyUpdateNumberTForm;
use Acf\ClientBundle\Form\Buy\UpdateDtActivationTForm as BuyUpdateDtActivationTForm;
use Acf\ClientBundle\Form\Buy\UpdateBillTForm as BuyUpdateBillTForm;
use Acf\ClientBundle\Form\Buy\UpdateRelationTForm as BuyUpdateRelationTForm;
use Acf\ClientBundle\Form\Buy\UpdateLabelTForm as BuyUpdateLabelTForm;
use Acf\ClientBundle\Form\Buy\UpdateDeviseTForm as BuyUpdateDeviseTForm;
use Acf\ClientBundle\Form\Buy\UpdateConversionRateTForm as BuyUpdateConversionRateTForm;
use Acf\ClientBundle\Form\Buy\UpdateVatTForm as BuyUpdateVatTForm;
use Acf\ClientBundle\Form\Buy\UpdateVatDeviseTForm as BuyUpdateVatDeviseTForm;
use Acf\ClientBundle\Form\Buy\UpdateStampTForm as BuyUpdateStampTForm;
use Acf\ClientBundle\Form\Buy\UpdateStampDeviseTForm as BuyUpdateStampDeviseTForm;
use Acf\ClientBundle\Form\Buy\UpdateBalanceTtcTForm as BuyUpdateBalanceTtcTForm;
use Acf\ClientBundle\Form\Buy\UpdateBalanceTtcDeviseTForm as BuyUpdateBalanceTtcDeviseTForm;
use Acf\ClientBundle\Form\Buy\UpdateRegimeTForm as BuyUpdateRegimeTForm;
use Acf\ClientBundle\Form\Buy\UpdateWithholdingTForm as BuyUpdateWithholdingTForm;
use Acf\ClientBundle\Form\Buy\UpdateNatureTForm as BuyUpdateNatureTForm;
use Acf\ClientBundle\Form\Buy\UpdateBalanceNetTForm as BuyUpdateBalanceNetTForm;
use Acf\ClientBundle\Form\Buy\UpdateBalanceNetDeviseTForm as BuyUpdateBalanceNetDeviseTForm;
use Acf\ClientBundle\Form\Buy\UpdatePaymentTypeTForm as BuyUpdatePaymentTypeTForm;
use Acf\ClientBundle\Form\Buy\UpdateTransactionStatusTForm as BuyUpdateTransactionStatusTForm;
use Acf\ClientBundle\Form\Buy\UpdateAccountTForm as BuyUpdateAccountTForm;
use Acf\ClientBundle\Form\Buy\UpdateOtherInfosTForm as BuyUpdateOtherInfosTForm;
use Acf\ClientBundle\Form\Buy\UpdateDocsTForm as BuyUpdateDocsTForm;
use Acf\ClientBundle\Form\Doc\NewTForm as DocNewTForm;
use Acf\DataBundle\Entity\Doc;
use Acf\DataBundle\Entity\ConstantStr;
use Acf\DataBundle\Entity\CompanyUser;
use Acf\DataBundle\Entity\Trace;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class BuyController extends BaseController
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

	public function deleteAction($uid)
	{
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_client_homepage');
		}
		$em = $this->getEntityManager();
		try {
			$buy = $em->getRepository('AcfDataBundle:Buy')->find($uid);
			
			if (null == $buy) {
				$this->flashMsgSession('warning', $this->translate('Buy.delete.notfound'));
			} else {
				
				$sc = $this->getSecurityTokenStorage();
				$user = $sc->getToken()->getUser();
				
				$company = $buy->getMonthlyBalance()->getCompany();
				
				$companyUser = $em->getRepository('AcfDataBundle:CompanyUser')->findOneBy(array('company' => $company, 'user' => $user));
				if (null == $companyUser || $companyUser->getDeleteBuys() == CompanyUser::CANT) {
					$this->flashMsgSession('error', $this->translate('CompanyUser.accessForbidden'));
					return $this->redirect($this->generateUrl('_client_homepage'));
				}
				$this->gvars['companyUser'] = $companyUser;
				$this->gvars['menu_active'] = 'client' . $company->getId();
				
				$em->remove($buy);
				$em->flush();
				
				$this->flashMsgSession('success', $this->translate('Buy.delete.success', array('%buy%' => $buy->getNumber())));
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
			
			$this->flashMsgSession('error', $this->translate('Buy.delete.failure'));
		}
		
		return $this->redirect($urlFrom);
	}

	public function editGetAction($uid)
	{
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_client_homepage');
		}
		
		$em = $this->getEntityManager();
		try {
			$buy = $em->getRepository('AcfDataBundle:Buy')->find($uid);
			
			if (null == $buy) {
				$this->flashMsgSession('warning', $this->translate('Buy.edit.notfound'));
			} else {
				
				$sc = $this->getSecurityTokenStorage();
				$user = $sc->getToken()->getUser();
				
				$company = $buy->getMonthlyBalance()->getCompany();
				
				$companyUser = $em->getRepository('AcfDataBundle:CompanyUser')->findOneBy(array('company' => $company, 'user' => $user));
				if (null == $companyUser) {
					$this->flashMsgSession('error', $this->translate('CompanyUser.accessForbidden'));
					return $this->redirect($this->generateUrl('_client_homepage'));
				}
				$this->gvars['companyUser'] = $companyUser;
				$this->gvars['menu_active'] = 'client' . $company->getId();
				
				$buyUpdateNumberForm = $this->createForm(BuyUpdateNumberTForm::class, $buy);
				$buyUpdateDtActivationForm = $this->createForm(BuyUpdateDtActivationTForm::class, $buy);
				$buyUpdateBillForm = $this->createForm(BuyUpdateBillTForm::class, $buy);
				$buyUpdateRelationForm = $this->createForm(BuyUpdateRelationTForm::class, $buy, 
					array('monthlybalance' => $buy->getMonthlyBalance()));
				$buyUpdateLabelForm = $this->createForm(BuyUpdateLabelTForm::class, $buy);
				$buyUpdateDeviseForm = $this->createForm(BuyUpdateDeviseTForm::class, $buy);
				$buyUpdateConversionRateForm = $this->createForm(BuyUpdateConversionRateTForm::class, $buy);
				$buyUpdateVatForm = $this->createForm(BuyUpdateVatTForm::class, $buy);
				$buyUpdateVatDeviseForm = $this->createForm(BuyUpdateVatDeviseTForm::class, $buy);
				$buyUpdateStampForm = $this->createForm(BuyUpdateStampTForm::class, $buy);
				$buyUpdateStampDeviseForm = $this->createForm(BuyUpdateStampDeviseTForm::class, $buy);
				$buyUpdateBalanceTtcForm = $this->createForm(BuyUpdateBalanceTtcTForm::class, $buy);
				$buyUpdateBalanceTtcDeviseForm = $this->createForm(BuyUpdateBalanceTtcDeviseTForm::class, $buy);
				$buyUpdateRegimeForm = $this->createForm(BuyUpdateRegimeTForm::class, $buy);
				$buyUpdateWithholdingForm = $this->createForm(BuyUpdateWithholdingTForm::class, $buy, 
					array('monthlybalance' => $buy->getMonthlyBalance()));
				$buyUpdateBalanceNetForm = $this->createForm(BuyUpdateBalanceNetTForm::class, $buy);
				$buyUpdateBalanceNetDeviseForm = $this->createForm(BuyUpdateBalanceNetDeviseTForm::class, $buy);
				$buyUpdatePaymentTypeForm = $this->createForm(BuyUpdatePaymentTypeTForm::class, $buy);
				$buyUpdateTransactionStatusForm = $this->createForm(BuyUpdateTransactionStatusTForm::class, $buy);
				$buyUpdateAccountForm = $this->createForm(BuyUpdateAccountTForm::class, $buy, 
					array('monthlybalance' => $buy->getMonthlyBalance()));
				$buyUpdateNatureForm = $this->createForm(BuyUpdateNatureTForm::class, $buy, 
					array('monthlybalance' => $buy->getMonthlyBalance()));
				$buyUpdateOtherInfosForm = $this->createForm(BuyUpdateOtherInfosTForm::class, $buy);
				$buyUpdateDocsForm = $this->createForm(BuyUpdateDocsTForm::class, $buy, array('company' => $buy->getCompany()));
				
				$doc = new Doc();
				$doc->setCompany($buy->getCompany());
				$docNewForm = $this->createForm(DocNewTForm::class, $doc, array('company' => $buy->getCompany()));
				
				$this->gvars['buy'] = $buy;
				$this->gvars['doc'] = $doc;
				$this->gvars['BuyUpdateNumberForm'] = $buyUpdateNumberForm->createView();
				$this->gvars['BuyUpdateDtActivationForm'] = $buyUpdateDtActivationForm->createView();
				$this->gvars['BuyUpdateBillForm'] = $buyUpdateBillForm->createView();
				$this->gvars['BuyUpdateRelationForm'] = $buyUpdateRelationForm->createView();
				$this->gvars['BuyUpdateLabelForm'] = $buyUpdateLabelForm->createView();
				$this->gvars['BuyUpdateDeviseForm'] = $buyUpdateDeviseForm->createView();
				$this->gvars['BuyUpdateConversionRateForm'] = $buyUpdateConversionRateForm->createView();
				$this->gvars['BuyUpdateVatForm'] = $buyUpdateVatForm->createView();
				$this->gvars['BuyUpdateVatDeviseForm'] = $buyUpdateVatDeviseForm->createView();
				$this->gvars['BuyUpdateStampForm'] = $buyUpdateStampForm->createView();
				$this->gvars['BuyUpdateStampDeviseForm'] = $buyUpdateStampDeviseForm->createView();
				$this->gvars['BuyUpdateBalanceTtcForm'] = $buyUpdateBalanceTtcForm->createView();
				$this->gvars['BuyUpdateBalanceTtcDeviseForm'] = $buyUpdateBalanceTtcDeviseForm->createView();
				$this->gvars['BuyUpdateRegimeForm'] = $buyUpdateRegimeForm->createView();
				$this->gvars['BuyUpdateWithholdingForm'] = $buyUpdateWithholdingForm->createView();
				$this->gvars['BuyUpdateBalanceNetForm'] = $buyUpdateBalanceNetForm->createView();
				$this->gvars['BuyUpdateBalanceNetDeviseForm'] = $buyUpdateBalanceNetDeviseForm->createView();
				$this->gvars['BuyUpdatePaymentTypeForm'] = $buyUpdatePaymentTypeForm->createView();
				$this->gvars['BuyUpdateTransactionStatusForm'] = $buyUpdateTransactionStatusForm->createView();
				$this->gvars['BuyUpdateAccountForm'] = $buyUpdateAccountForm->createView();
				$this->gvars['BuyUpdateNatureForm'] = $buyUpdateNatureForm->createView();
				$this->gvars['BuyUpdateOtherInfosForm'] = $buyUpdateOtherInfosForm->createView();
				$this->gvars['BuyUpdateDocsForm'] = $buyUpdateDocsForm->createView();
				$this->gvars['DocNewForm'] = $docNewForm->createView();
				
				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
				$this->getSession()->remove('tabActive');
				
				$this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
				$this->getSession()->remove('stabActive');
				
				$suppliersConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array('name' => 'suppliersPrefix'));
				if (null == $suppliersConstStr) {
					$suppliersConstStr = new ConstantStr();
					$suppliersConstStr->setName('suppliersPrefix');
					$suppliersConstStr->setValue('401');
					$em->persist($suppliersConstStr);
					$em->flush();
				}
				$suppliersPrefix = $suppliersConstStr->getValue();
				$this->gvars['suppliersPrefix'] = $suppliersPrefix;
				
				$this->gvars['pagetitle'] = $this->translate('pagetitle.buy.edit', array('%buy%' => $buy->getNumber()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.buy.edit.txt', array('%buy%' => $buy->getNumber()));
				
				return $this->renderResponse('AcfClientBundle:Buy:edit.html.twig', $this->gvars);
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
			$buy = $em->getRepository('AcfDataBundle:Buy')->find($uid);
			
			if (null == $buy) {
				$this->flashMsgSession('warning', $this->translate('Buy.edit.notfound'));
			} else {
				
				$sc = $this->getSecurityTokenStorage();
				$user = $sc->getToken()->getUser();
				
				$company = $buy->getMonthlyBalance()->getCompany();
				
				$companyUser = $em->getRepository('AcfDataBundle:CompanyUser')->findOneBy(array('company' => $company, 'user' => $user));
				if (null == $companyUser || $companyUser->getEditBuys() == CompanyUser::CANT) {
					$this->flashMsgSession('error', $this->translate('CompanyUser.accessForbidden'));
					return $this->redirect($this->generateUrl('_client_homepage'));
				}
				$this->gvars['companyUser'] = $companyUser;
				$this->gvars['menu_active'] = 'client' . $company->getId();
				
				$buyUpdateNumberForm = $this->createForm(BuyUpdateNumberTForm::class, $buy);
				$buyUpdateDtActivationForm = $this->createForm(BuyUpdateDtActivationTForm::class, $buy);
				$buyUpdateBillForm = $this->createForm(BuyUpdateBillTForm::class, $buy);
				$buyUpdateRelationForm = $this->createForm(BuyUpdateRelationTForm::class, $buy, 
					array('monthlybalance' => $buy->getMonthlyBalance()));
				$buyUpdateLabelForm = $this->createForm(BuyUpdateLabelTForm::class, $buy);
				$buyUpdateDeviseForm = $this->createForm(BuyUpdateDeviseTForm::class, $buy);
				$buyUpdateConversionRateForm = $this->createForm(BuyUpdateConversionRateTForm::class, $buy);
				$buyUpdateVatForm = $this->createForm(BuyUpdateVatTForm::class, $buy);
				$buyUpdateVatDeviseForm = $this->createForm(BuyUpdateVatDeviseTForm::class, $buy);
				$buyUpdateStampForm = $this->createForm(BuyUpdateStampTForm::class, $buy);
				$buyUpdateStampDeviseForm = $this->createForm(BuyUpdateStampDeviseTForm::class, $buy);
				$buyUpdateBalanceTtcForm = $this->createForm(BuyUpdateBalanceTtcTForm::class, $buy);
				$buyUpdateBalanceTtcDeviseForm = $this->createForm(BuyUpdateBalanceTtcDeviseTForm::class, $buy);
				$buyUpdateRegimeForm = $this->createForm(BuyUpdateRegimeTForm::class, $buy);
				$buyUpdateWithholdingForm = $this->createForm(BuyUpdateWithholdingTForm::class, $buy, 
					array('monthlybalance' => $buy->getMonthlyBalance()));
				$buyUpdateBalanceNetForm = $this->createForm(BuyUpdateBalanceNetTForm::class, $buy);
				$buyUpdateBalanceNetDeviseForm = $this->createForm(BuyUpdateBalanceNetDeviseTForm::class, $buy);
				$buyUpdatePaymentTypeForm = $this->createForm(BuyUpdatePaymentTypeTForm::class, $buy);
				$buyUpdateTransactionStatusForm = $this->createForm(BuyUpdateTransactionStatusTForm::class, $buy);
				$buyUpdateAccountForm = $this->createForm(BuyUpdateAccountTForm::class, $buy, 
					array('monthlybalance' => $buy->getMonthlyBalance()));
				$buyUpdateNatureForm = $this->createForm(BuyUpdateNatureTForm::class, $buy, 
					array('monthlybalance' => $buy->getMonthlyBalance()));
				$buyUpdateOtherInfosForm = $this->createForm(BuyUpdateOtherInfosTForm::class, $buy);
				$buyUpdateDocsForm = $this->createForm(BuyUpdateDocsTForm::class, $buy, array('company' => $buy->getCompany()));
				
				$doc = new Doc();
				$doc->setCompany($buy->getCompany());
				$docNewForm = $this->createForm(DocNewTForm::class, $doc, array('company' => $buy->getCompany()));
				
				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
				$this->getSession()->remove('tabActive');
				
				$this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
				$this->getSession()->remove('stabActive');
				
				$request = $this->getRequest();
				$reqData = $request->request->all();
				
				$cloneBuy = clone $buy;
				
				if (isset($reqData['BuyUpdateNumberForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$buyUpdateNumberForm->handleRequest($request);
					if ($buyUpdateNumberForm->isValid()) {
						$em->persist($buy);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Buy.edit.success', array('%buy%' => $buy->getNumber())));
						
						$this->traceEntity($cloneBuy, $buy);
						
						return $this->redirect($urlFrom);
					} else {
						$em->refresh($buy);
						
						$this->flashMsgSession('error', $this->translate('Buy.edit.failure', array('%buy%' => $buy->getNumber())));
					}
				} elseif (isset($reqData['BuyUpdateDtActivationForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$buyUpdateDtActivationForm->handleRequest($request);
					if ($buyUpdateDtActivationForm->isValid()) {
						$em->persist($buy);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Buy.edit.success', array('%buy%' => $buy->getNumber())));
						
						$this->traceEntity($cloneBuy, $buy);
						
						return $this->redirect($urlFrom);
					} else {
						$em->refresh($buy);
						
						$this->flashMsgSession('error', $this->translate('Buy.edit.failure', array('%buy%' => $buy->getNumber())));
					}
				} elseif (isset($reqData['BuyUpdateBillForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$buyUpdateBillForm->handleRequest($request);
					if ($buyUpdateBillForm->isValid()) {
						$em->persist($buy);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Buy.edit.success', array('%buy%' => $buy->getNumber())));
						
						$this->traceEntity($cloneBuy, $buy);
						
						return $this->redirect($urlFrom);
					} else {
						$em->refresh($buy);
						
						$this->flashMsgSession('error', $this->translate('Buy.edit.failure', array('%buy%' => $buy->getNumber())));
					}
				} elseif (isset($reqData['BuyUpdateRelationForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$buyUpdateRelationForm->handleRequest($request);
					if ($buyUpdateRelationForm->isValid()) {
						$em->persist($buy);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Buy.edit.success', array('%buy%' => $buy->getNumber())));
						
						$this->traceEntity($cloneBuy, $buy);
						
						return $this->redirect($urlFrom);
					} else {
						$em->refresh($buy);
						
						$this->flashMsgSession('error', $this->translate('Buy.edit.failure', array('%buy%' => $buy->getNumber())));
					}
				} elseif (isset($reqData['BuyUpdateLabelForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$buyUpdateLabelForm->handleRequest($request);
					if ($buyUpdateLabelForm->isValid()) {
						$em->persist($buy);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Buy.edit.success', array('%buy%' => $buy->getNumber())));
						
						$this->traceEntity($cloneBuy, $buy);
						
						return $this->redirect($urlFrom);
					} else {
						$em->refresh($buy);
						
						$this->flashMsgSession('error', $this->translate('Buy.edit.failure', array('%buy%' => $buy->getNumber())));
					}
				} elseif (isset($reqData['BuyUpdateNatureForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$buyUpdateNatureForm->handleRequest($request);
					if ($buyUpdateNatureForm->isValid()) {
						$em->persist($buy);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Buy.edit.success', array('%buy%' => $buy->getNumber())));
						
						$this->traceEntity($cloneBuy, $buy);
						
						return $this->redirect($urlFrom);
					} else {
						$em->refresh($buy);
						
						$this->flashMsgSession('error', $this->translate('Buy.edit.failure', array('%buy%' => $buy->getNumber())));
					}
				} elseif (isset($reqData['BuyUpdateDeviseForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$buyUpdateDeviseForm->handleRequest($request);
					if ($buyUpdateDeviseForm->isValid()) {
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
						$em->persist($buy);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Buy.edit.success', array('%buy%' => $buy->getNumber())));
						
						$this->traceEntity($cloneBuy, $buy);
						
						return $this->redirect($urlFrom);
					} else {
						$em->refresh($buy);
						
						$this->flashMsgSession('error', $this->translate('Buy.edit.failure', array('%buy%' => $buy->getNumber())));
					}
				} elseif (isset($reqData['BuyUpdateConversionRateForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$buyUpdateConversionRateForm->handleRequest($request);
					if ($buyUpdateConversionRateForm->isValid()) {
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
						$em->persist($buy);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Buy.edit.success', array('%buy%' => $buy->getNumber())));
						
						$this->traceEntity($cloneBuy, $buy);
						
						return $this->redirect($urlFrom);
					} else {
						$em->refresh($buy);
						
						$this->flashMsgSession('error', $this->translate('Buy.edit.failure', array('%buy%' => $buy->getNumber())));
					}
				} elseif (isset($reqData['BuyUpdateVatForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$buyUpdateVatForm->handleRequest($request);
					if ($buyUpdateVatForm->isValid()) {
						$buy->setConversionRate(1);
						$buy->setVatDevise($buy->getVat());
						$em->persist($buy);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Buy.edit.success', array('%buy%' => $buy->getNumber())));
						
						$this->traceEntity($cloneBuy, $buy);
						
						return $this->redirect($urlFrom);
					} else {
						$em->refresh($buy);
						
						$this->flashMsgSession('error', $this->translate('Buy.edit.failure', array('%buy%' => $buy->getNumber())));
					}
				} elseif (isset($reqData['BuyUpdateVatDeviseForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$buyUpdateVatDeviseForm->handleRequest($request);
					if ($buyUpdateVatDeviseForm->isValid()) {
						$buy->setVat($buy->getVatDevise() * $buy->getConversionRate());
						$em->persist($buy);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Buy.edit.success', array('%buy%' => $buy->getNumber())));
						
						$this->traceEntity($cloneBuy, $buy);
						
						return $this->redirect($urlFrom);
					} else {
						$em->refresh($buy);
						
						$this->flashMsgSession('error', $this->translate('Buy.edit.failure', array('%buy%' => $buy->getNumber())));
					}
				} elseif (isset($reqData['BuyUpdateStampForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$buyUpdateStampForm->handleRequest($request);
					if ($buyUpdateStampForm->isValid()) {
						$buy->setConversionRate(1);
						$buy->setStampDevise($buy->getStamp());
						$em->persist($buy);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Buy.edit.success', array('%buy%' => $buy->getNumber())));
						
						$this->traceEntity($cloneBuy, $buy);
						
						return $this->redirect($urlFrom);
					} else {
						$em->refresh($buy);
						
						$this->flashMsgSession('error', $this->translate('Buy.edit.failure', array('%buy%' => $buy->getNumber())));
					}
				} elseif (isset($reqData['BuyUpdateStampDeviseForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$buyUpdateStampDeviseForm->handleRequest($request);
					if ($buyUpdateStampDeviseForm->isValid()) {
						$buy->setStamp($buy->getStampDevise() * $buy->getConversionRate());
						$em->persist($buy);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Buy.edit.success', array('%buy%' => $buy->getNumber())));
						
						$this->traceEntity($cloneBuy, $buy);
						
						return $this->redirect($urlFrom);
					} else {
						$em->refresh($buy);
						
						$this->flashMsgSession('error', $this->translate('Buy.edit.failure', array('%buy%' => $buy->getNumber())));
					}
				} elseif (isset($reqData['BuyUpdateBalanceTtcForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$buyUpdateBalanceTtcForm->handleRequest($request);
					if ($buyUpdateBalanceTtcForm->isValid()) {
						$buy->setConversionRate(1);
						$buy->setBalanceTtcDevise($buy->getBalanceTtc());
						$em->persist($buy);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Buy.edit.success', array('%buy%' => $buy->getNumber())));
						
						$this->traceEntity($cloneBuy, $buy);
						
						return $this->redirect($urlFrom);
					} else {
						$em->refresh($buy);
						
						$this->flashMsgSession('error', $this->translate('Buy.edit.failure', array('%buy%' => $buy->getNumber())));
					}
				} elseif (isset($reqData['BuyUpdateBalanceTtcDeviseForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$buyUpdateBalanceTtcDeviseForm->handleRequest($request);
					if ($buyUpdateBalanceTtcDeviseForm->isValid()) {
						$buy->setBalanceTtc($buy->getBalanceTtcDevise() * $buy->getConversionRate());
						$em->persist($buy);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Buy.edit.success', array('%buy%' => $buy->getNumber())));
						
						$this->traceEntity($cloneBuy, $buy);
						
						return $this->redirect($urlFrom);
					} else {
						$em->refresh($buy);
						
						$this->flashMsgSession('error', $this->translate('Buy.edit.failure', array('%buy%' => $buy->getNumber())));
					}
				} elseif (isset($reqData['BuyUpdateRegimeForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$buyUpdateRegimeForm->handleRequest($request);
					if ($buyUpdateRegimeForm->isValid()) {
						$em->persist($buy);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Buy.edit.success', array('%buy%' => $buy->getNumber())));
						
						$this->traceEntity($cloneBuy, $buy);
						
						return $this->redirect($urlFrom);
					} else {
						$em->refresh($buy);
						
						$this->flashMsgSession('error', $this->translate('Buy.edit.failure', array('%buy%' => $buy->getNumber())));
					}
				} elseif (isset($reqData['BuyUpdateWithholdingForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$buyUpdateWithholdingForm->handleRequest($request);
					if ($buyUpdateWithholdingForm->isValid()) {
						$em->persist($buy);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Buy.edit.success', array('%buy%' => $buy->getNumber())));
						
						$this->traceEntity($cloneBuy, $buy);
						
						return $this->redirect($urlFrom);
					} else {
						$em->refresh($buy);
						
						$this->flashMsgSession('error', $this->translate('Buy.edit.failure', array('%buy%' => $buy->getNumber())));
					}
				} elseif (isset($reqData['BuyUpdateBalanceNetForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$buyUpdateBalanceNetForm->handleRequest($request);
					if ($buyUpdateBalanceNetForm->isValid()) {
						$buy->setConversionRate(1);
						$buy->setBalanceNetDevise($buy->getBalanceNet());
						$em->persist($buy);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Buy.edit.success', array('%buy%' => $buy->getNumber())));
						
						$this->traceEntity($cloneBuy, $buy);
						
						return $this->redirect($urlFrom);
					} else {
						$em->refresh($buy);
						
						$this->flashMsgSession('error', $this->translate('Buy.edit.failure', array('%buy%' => $buy->getNumber())));
					}
				} elseif (isset($reqData['BuyUpdateBalanceNetDeviseForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$buyUpdateBalanceNetDeviseForm->handleRequest($request);
					if ($buyUpdateBalanceNetDeviseForm->isValid()) {
						$buy->setBalanceNet($buy->getBalanceNetDevise() * $buy->getConversionRate());
						$em->persist($buy);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Buy.edit.success', array('%buy%' => $buy->getNumber())));
						
						$this->traceEntity($cloneBuy, $buy);
						
						return $this->redirect($urlFrom);
					} else {
						$em->refresh($buy);
						
						$this->flashMsgSession('error', $this->translate('Buy.edit.failure', array('%buy%' => $buy->getNumber())));
					}
				} elseif (isset($reqData['BuyUpdatePaymentTypeForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$buyUpdatePaymentTypeForm->handleRequest($request);
					if ($buyUpdatePaymentTypeForm->isValid()) {
						$em->persist($buy);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Buy.edit.success', array('%buy%' => $buy->getNumber())));
						
						$this->traceEntity($cloneBuy, $buy);
						
						return $this->redirect($urlFrom);
					} else {
						$em->refresh($buy);
						
						$this->flashMsgSession('error', $this->translate('Buy.edit.failure', array('%buy%' => $buy->getNumber())));
					}
				} elseif (isset($reqData['BuyUpdateTransactionStatusForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$buyUpdateTransactionStatusForm->handleRequest($request);
					if ($buyUpdateTransactionStatusForm->isValid()) {
						$em->persist($buy);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Buy.edit.success', array('%buy%' => $buy->getNumber())));
						
						$this->traceEntity($cloneBuy, $buy);
						
						return $this->redirect($urlFrom);
					} else {
						$em->refresh($buy);
						
						$this->flashMsgSession('error', $this->translate('Buy.edit.failure', array('%buy%' => $buy->getNumber())));
					}
				} elseif (isset($reqData['BuyUpdateAccountForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$buyUpdateAccountForm->handleRequest($request);
					if ($buyUpdateAccountForm->isValid()) {
						$em->persist($buy);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Buy.edit.success', array('%buy%' => $buy->getNumber())));
						
						$this->traceEntity($cloneBuy, $buy);
						
						return $this->redirect($urlFrom);
					} else {
						$em->refresh($buy);
						
						$this->flashMsgSession('error', $this->translate('Buy.edit.failure', array('%buy%' => $buy->getNumber())));
					}
				} elseif (isset($reqData['BuyUpdateNatureForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$buyUpdateNatureForm->handleRequest($request);
					if ($buyUpdateNatureForm->isValid()) {
						$em->persist($buy);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Buy.edit.success', array('%buy%' => $buy->getNumber())));
						
						$this->traceEntity($cloneBuy, $buy);
						
						return $this->redirect($urlFrom);
					} else {
						$em->refresh($buy);
						
						$this->flashMsgSession('error', $this->translate('Buy.edit.failure', array('%buy%' => $buy->getNumber())));
					}
				} elseif (isset($reqData['BuyUpdateOtherInfosForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$buyUpdateOtherInfosForm->handleRequest($request);
					if ($buyUpdateOtherInfosForm->isValid()) {
						$em->persist($buy);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Buy.edit.success', array('%buy%' => $buy->getNumber())));
						
						return $this->redirect($urlFrom);
					} else {
						$em->refresh($buy);
						
						$this->flashMsgSession('error', $this->translate('Buy.edit.failure', array('%buy%' => $buy->getNumber())));
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
							$doc->setCompany($buy->getCompany());
							
							$doc->setFileName($fileName);
							$doc->setOriginalName($originalName);
							$doc->setSize($size);
							$doc->setMimeType($mimeType);
							$doc->setMd5($md5);
							$doc->addTransaction($buy);
							$em->persist($doc);
							
							$docNames .= $doc->getOriginalName() . " ";
						}
						
						$em->persist($buy);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Doc.add.success', array('%doc%' => $docNames)));
						$this->newDocNotifyAdmin($buy, $docNames);
						
						$this->gvars['stabActive'] = 3;
						$this->getSession()->set('stabActive', 3);
						
						$this->traceEntity($cloneBuy, $buy);
						
						return $this->redirect($urlFrom);
					} else {
						$em->refresh($buy);
						
						$this->flashMsgSession('error', $this->translate('Doc.add.failure'));
					}
				} elseif (isset($reqData['BuyUpdateDocsForm'])) {
					$this->gvars['tabActive'] = 3;
					$this->getSession()->set('tabActive', 3);
					$this->gvars['stabActive'] = 2;
					$this->getSession()->set('stabActive', 2);
					$buyUpdateDocsForm->handleRequest($request);
					if ($buyUpdateDocsForm->isValid()) {
						$em->persist($buy);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Buy.edit.success', array('%buy%' => $buy->getNumber())));
						$this->gvars['stabActive'] = 3;
						$this->getSession()->set('stabActive', 3);
						
						$this->traceEntity($cloneBuy, $buy);
						
						return $this->redirect($urlFrom);
					} else {
						$em->refresh($buy);
						
						$this->flashMsgSession('error', $this->translate('Buy.edit.failure', array('%buy%' => $buy->getNumber())));
					}
				}
				
				$this->gvars['buy'] = $buy;
				$this->gvars['doc'] = $doc;
				$this->gvars['BuyUpdateNumberForm'] = $buyUpdateNumberForm->createView();
				$this->gvars['BuyUpdateDtActivationForm'] = $buyUpdateDtActivationForm->createView();
				$this->gvars['BuyUpdateBillForm'] = $buyUpdateBillForm->createView();
				$this->gvars['BuyUpdateRelationForm'] = $buyUpdateRelationForm->createView();
				$this->gvars['BuyUpdateLabelForm'] = $buyUpdateLabelForm->createView();
				$this->gvars['BuyUpdateDeviseForm'] = $buyUpdateDeviseForm->createView();
				$this->gvars['BuyUpdateConversionRateForm'] = $buyUpdateConversionRateForm->createView();
				$this->gvars['BuyUpdateVatForm'] = $buyUpdateVatForm->createView();
				$this->gvars['BuyUpdateVatDeviseForm'] = $buyUpdateVatDeviseForm->createView();
				$this->gvars['BuyUpdateStampForm'] = $buyUpdateStampForm->createView();
				$this->gvars['BuyUpdateStampDeviseForm'] = $buyUpdateStampDeviseForm->createView();
				$this->gvars['BuyUpdateBalanceTtcForm'] = $buyUpdateBalanceTtcForm->createView();
				$this->gvars['BuyUpdateBalanceTtcDeviseForm'] = $buyUpdateBalanceTtcDeviseForm->createView();
				$this->gvars['BuyUpdateRegimeForm'] = $buyUpdateRegimeForm->createView();
				$this->gvars['BuyUpdateWithholdingForm'] = $buyUpdateWithholdingForm->createView();
				$this->gvars['BuyUpdateBalanceNetForm'] = $buyUpdateBalanceNetForm->createView();
				$this->gvars['BuyUpdateBalanceNetDeviseForm'] = $buyUpdateBalanceNetDeviseForm->createView();
				$this->gvars['BuyUpdatePaymentTypeForm'] = $buyUpdatePaymentTypeForm->createView();
				$this->gvars['BuyUpdateTransactionStatusForm'] = $buyUpdateTransactionStatusForm->createView();
				$this->gvars['BuyUpdateAccountForm'] = $buyUpdateAccountForm->createView();
				$this->gvars['BuyUpdateNatureForm'] = $buyUpdateNatureForm->createView();
				$this->gvars['BuyUpdateOtherInfosForm'] = $buyUpdateOtherInfosForm->createView();
				$this->gvars['BuyUpdateDocsForm'] = $buyUpdateDocsForm->createView();
				$this->gvars['DocNewForm'] = $docNewForm->createView();
				
				$suppliersConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array('name' => 'suppliersPrefix'));
				if (null == $suppliersConstStr) {
					$suppliersConstStr = new ConstantStr();
					$suppliersConstStr->setName('suppliersPrefix');
					$suppliersConstStr->setValue('401');
					$em->persist($suppliersConstStr);
					$em->flush();
				}
				$suppliersPrefix = $suppliersConstStr->getValue();
				$this->gvars['suppliersPrefix'] = $suppliersPrefix;
				
				$this->gvars['pagetitle'] = $this->translate('pagetitle.buy.edit', array('%buy%' => $buy->getNumber()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.buy.edit.txt', array('%buy%' => $buy->getNumber()));
				
				return $this->renderResponse('AcfClientBundle:Buy:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
		}
		
		return $this->redirect($urlFrom);
	}

	protected function newDocNotifyAdmin(Buy $buy, $docNames)
	{
		$from = $this->getParameter('mail_from');
		$fromName = $this->getParameter('mail_from_name');
		$subject = $this->translate('_mail.newdocs.subject', array(), 'messages');
		
		$user = $this->getSecurityTokenStorage()
			->getToken()
			->getUser();
		$company = $buy->getCompany();
		
		$admins = $company->getAdmins();
		foreach ($admins as $admin) {
			$mvars = array();
			$mvars['user'] = $user->getFullName();
			$mvars['company'] = $company->getCorporateName();
			$mvars['docNames'] = $docNames;
			
			$message = \Swift_Message::newInstance()->setFrom($from, $fromName)
				->setTo($admin->getEmail(), $admin->getFullname())
				->setSubject($subject)
				->setBody($this->renderView('AcfClientBundle:Mail:newdoc.html.twig', $mvars), 'text/html');
			
			$this->sendmail($message);
		}
	}

	protected function traceEntity(Buy $cloneBuy, Buy $buy)
	{
		$curUser = $this->getSecurityTokenStorage()
			->getToken()
			->getUser();
		$trace = new Trace();
		$trace->setActionId($buy->getId());
		$trace->setActionType(Trace::AT_UPDATE);
		$trace->setUserId($curUser->getId());
		$trace->setCompanyId($buy->getCompany()
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
		
		$trace->setActionEntity(Trace::AE_BUY);
		$trace->setActionId2($buy->getMonthlyBalance()
			->getCompany()
			->getId());
		$trace->setActionEntity2(Trace::AE_COMPANY);
		$trace->setActionId3($buy->getMonthlyBalance()
			->getId());
		$trace->setActionEntity2(Trace::AE_MBPURCHASE);
		
		$msg = "";
		
		if ($cloneBuy->getNumber() != $buy->getNumber()) {
			$msg .= "<tr><td>" . $this->translate('Buy.number.label') . '</td><td>';
			if ($cloneBuy->getNumber() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneBuy->getNumberFormated();
			}
			$msg .= "</td><td>";
			if ($buy->getNumber() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $buy->getNumberFormated();
			}
			$msg .= "</td></tr>";
		}
		
		if ($cloneBuy->getDtActivation() != $buy->getDtActivation()) {
			$msg .= "<tr><td>" . $this->translate('Buy.dtActivation.label') . '</td><td>';
			if ($cloneBuy->getDtActivation() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneBuy->getDtActivation()->format('Y-m-d');
			}
			$msg .= "</td><td>";
			if ($buy->getDtActivation() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $buy->getDtActivation()->format('Y-m-d');
			}
			$msg .= "</td></tr>";
		}
		
		if ($cloneBuy->getBill() != $buy->getBill()) {
			$msg .= "<tr><td>" . $this->translate('Buy.bill.label') . '</td><td>';
			if ($cloneBuy->getBill() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneBuy->getBill();
			}
			$msg .= "</td><td>";
			if ($buy->getBill() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $buy->getBill();
			}
			$msg .= "</td></tr>";
		}
		
		if ($cloneBuy->getRelation() != $buy->getRelation()) {
			$msg .= "<tr><td>" . $this->translate('Buy.relation.label') . '</td><td>';
			if ($cloneBuy->getRelation() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneBuy->getRelation()->getLabel();
			}
			$msg .= "</td><td>";
			if ($buy->getRelation() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $buy->getRelation()->getLabel();
			}
			$msg .= "</td></tr>";
		}
		
		if ($cloneBuy->getLabel() != $buy->getLabel()) {
			$msg .= "<tr><td>" . $this->translate('Buy.label.label') . '</td><td>';
			if ($cloneBuy->getLabel() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneBuy->getLabel();
			}
			$msg .= "</td><td>";
			if ($buy->getLabel() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $buy->getLabel();
			}
			$msg .= "</td></tr>";
		}
		
		if ($cloneBuy->getDevise() != $buy->getDevise()) {
			$msg .= "<tr><td>" . $this->translate('Buy.devise.label') . '</td><td>';
			if ($cloneBuy->getDevise() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneBuy->getDevise();
			}
			$msg .= "</td><td>";
			if ($buy->getDevise() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $buy->getDevise();
			}
			$msg .= "</td></tr>";
		}
		
		if ($cloneBuy->getConversionRate() != $buy->getConversionRate()) {
			$msg .= "<tr><td>" . $this->translate('Buy.conversionRate.label') . '</td><td>';
			if ($cloneBuy->getConversionRate() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneBuy->getConversionRate();
			}
			$msg .= "</td><td>";
			if ($buy->getConversionRate() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $buy->getConversionRate();
			}
			$msg .= "</td></tr>";
		}
		
		if ($cloneBuy->getVat() != $buy->getVat()) {
			$msg .= "<tr><td>" . $this->translate('Buy.vat.label') . '</td><td>';
			if ($cloneBuy->getVat() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneBuy->getVat();
			}
			$msg .= "</td><td>";
			if ($buy->getVat() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $buy->getVat();
			}
			$msg .= "</td></tr>";
		}
		
		if ($cloneBuy->getStamp() != $buy->getStamp()) {
			$msg .= "<tr><td>" . $this->translate('Buy.stamp.label') . '</td><td>';
			if ($cloneBuy->getStamp() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneBuy->getStamp();
			}
			$msg .= "</td><td>";
			if ($buy->getStamp() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $buy->getStamp();
			}
			$msg .= "</td></tr>";
		}
		
		if ($cloneBuy->getBalanceTtc() != $buy->getBalanceTtc()) {
			$msg .= "<tr><td>" . $this->translate('Buy.balanceTtc.label') . '</td><td>';
			if ($cloneBuy->getBalanceTtc() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneBuy->getBalanceTtc();
			}
			$msg .= "</td><td>";
			if ($buy->getBalanceTtc() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $buy->getBalanceTtc();
			}
			$msg .= "</td></tr>";
		}
		
		if ($cloneBuy->getBalanceNet() != $buy->getBalanceNet()) {
			$msg .= "<tr><td>" . $this->translate('Buy.balanceNet.label') . '</td><td>';
			if ($cloneBuy->getBalanceNet() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneBuy->getBalanceNet();
			}
			$msg .= "</td><td>";
			if ($buy->getBalanceNet() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $buy->getBalanceNet();
			}
			$msg .= "</td></tr>";
		}
		
		if ($cloneBuy->getVatDevise() != $buy->getVatDevise()) {
			$msg .= "<tr><td>" . $this->translate('Buy.vatDevise.label') . '</td><td>';
			if ($cloneBuy->getVatDevise() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneBuy->getVatDevise();
			}
			$msg .= "</td><td>";
			if ($buy->getVatDevise() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $buy->getVatDevise();
			}
			$msg .= "</td></tr>";
		}
		
		if ($cloneBuy->getStampDevise() != $buy->getStampDevise()) {
			$msg .= "<tr><td>" . $this->translate('Buy.stampDevise.label') . '</td><td>';
			if ($cloneBuy->getStampDevise() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneBuy->getStampDevise();
			}
			$msg .= "</td><td>";
			if ($buy->getStampDevise() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $buy->getStampDevise();
			}
			$msg .= "</td></tr>";
		}
		
		if ($cloneBuy->getBalanceTtcDevise() != $buy->getBalanceTtcDevise()) {
			$msg .= "<tr><td>" . $this->translate('Buy.balanceTtcDevise.label') . '</td><td>';
			if ($cloneBuy->getBalanceTtcDevise() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneBuy->getBalanceTtcDevise();
			}
			$msg .= "</td><td>";
			if ($buy->getBalanceTtcDevise() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $buy->getBalanceTtcDevise();
			}
			$msg .= "</td></tr>";
		}
		
		if ($cloneBuy->getBalanceNetDevise() != $buy->getBalanceNetDevise()) {
			$msg .= "<tr><td>" . $this->translate('Buy.balanceNetDevise.label') . '</td><td>';
			if ($cloneBuy->getBalanceNetDevise() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneBuy->getBalanceNetDevise();
			}
			$msg .= "</td><td>";
			if ($buy->getBalanceNetDevise() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $buy->getBalanceNetDevise();
			}
			$msg .= "</td></tr>";
		}
		
		if ($cloneBuy->getVatInfo() != $buy->getVatInfo()) {
			$msg .= "<tr><td>" . $this->translate('Buy.vatInfo.label') . '</td><td>';
			if ($cloneBuy->getVatInfo() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $this->translate('Buy.vatInfo.' . $cloneBuy->getVatInfo());
			}
			$msg .= "</td><td>";
			if ($buy->getVatInfo() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $this->translate('Buy.vatInfo.' . $buy->getVatInfo());
			}
			$msg .= "</td></tr>";
		}
		
		if ($cloneBuy->getRegime() != $buy->getRegime()) {
			$msg .= "<tr><td>" . $this->translate('Buy.regime.label') . '</td><td>';
			if ($cloneBuy->getRegime() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $this->translate('Buy.regime.' . $cloneBuy->getRegime());
			}
			$msg .= "</td><td>";
			if ($buy->getRegime() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $this->translate('Buy.regime.' . $buy->getRegime());
			}
			$msg .= "</td></tr>";
		}
		
		if ($cloneBuy->getWithholding() != $buy->getWithholding()) {
			$msg .= "<tr><td>" . $this->translate('Buy.withholding.label') . '</td><td>';
			if ($cloneBuy->getWithholding() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneBuy->getWithholding()->getLabel();
			}
			$msg .= "</td><td>";
			if ($buy->getWithholding() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $buy->getWithholding()->getLabel();
			}
			$msg .= "</td></tr>";
		}
		
		if ($cloneBuy->getPaymentType() != $buy->getPaymentType()) {
			$msg .= "<tr><td>" . $this->translate('Buy.paymentType.label') . '</td><td>';
			if ($cloneBuy->getPaymentType() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $this->translate('Transaction.paymentType.' . $cloneBuy->getPaymentType());
			}
			$msg .= "</td><td>";
			if ($buy->getPaymentType() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $this->translate('Transaction.paymentType.' . $buy->getPaymentType());
			}
			$msg .= "</td></tr>";
		}
		
		if ($cloneBuy->getDtPayment() != $buy->getDtPayment()) {
			$msg .= "<tr><td>" . $this->translate('Buy.dtPayment.label') . '</td><td>';
			if ($cloneBuy->getDtPayment() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneBuy->getDtPayment()->format('Y-m-d');
			}
			$msg .= "</td><td>";
			if ($buy->getDtPayment() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $buy->getDtActivation()->format('Y-m-d');
			}
			$msg .= "</td></tr>";
		}
		
		if ($cloneBuy->getAccount() != $buy->getAccount()) {
			$msg .= "<tr><td>" . $this->translate('Buy.account.label') . '</td><td>';
			if ($cloneBuy->getAccount() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneBuy->getAccount()->getLabel();
			}
			$msg .= "</td><td>";
			if ($buy->getAccount() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $buy->getAccount()->getLabel();
			}
			$msg .= "</td></tr>";
		}
		
		if ($cloneBuy->getNature() != $buy->getNature()) {
			$msg .= "<tr><td>" . $this->translate('Buy.nature.label') . '</td><td>';
			if ($cloneBuy->getNature() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneBuy->getNature()->getLabel();
			}
			$msg .= "</td><td>";
			if ($buy->getNature() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $buy->getNature()->getLabel();
			}
			$msg .= "</td></tr>";
		}
		
		if ($cloneBuy->getTransactionStatus() != $buy->getTransactionStatus()) {
			$msg .= "<tr><td>" . $this->translate('Buy.transactionStatus.label') . '</td><td>';
			if ($cloneBuy->getTransactionStatus() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $this->translate('Transaction.transactionStatus.' . $cloneBuy->getTransactionStatus());
			}
			$msg .= "</td><td>";
			if ($buy->getTransactionStatus() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $this->translate('Transaction.transactionStatus.' . $buy->getTransactionStatus());
			}
			$msg .= "</td></tr>";
		}
		
		if ($cloneBuy->getValidated() != $buy->getValidated()) {
			$msg .= "<tr><td>" . $this->translate('Buy.validated.label') . '</td><td>';
			if ($cloneBuy->getValidated() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $this->translate('Transaction.validated.' . $cloneBuy->getValidated());
			}
			$msg .= "</td><td>";
			if ($buy->getValidated() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $this->translate('Transaction.validated.' . $buy->getValidated());
			}
			$msg .= "</td></tr>";
		}
		
		if ($cloneBuy->getOtherInfos() != $buy->getOtherInfos()) {
			$msg .= "<tr><td>" . $this->translate('Buy.otherInfos.label') . '</td><td>';
			if ($cloneBuy->getOtherInfos() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneBuy->getOtherInfos();
			}
			$msg .= "</td><td>";
			if ($buy->getOtherInfos() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $buy->getOtherInfos();
			}
			$msg .= "</td></tr>";
		}
		
		if (\count(\array_diff($buy->getDocs()->toArray(), $cloneBuy->getDocs()->toArray())) != 0 ||
			 \count(\array_diff($cloneBuy->getDocs()->toArray(), $buy->getDocs()->toArray())) != 0) {
			$msg .= "<tr><td>" . $this->translate('Buy.docs.label') . '</td><td>';
			if (\count($cloneBuy->getDocs()) == 0) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= "<ul>";
				foreach ($cloneBuy->getDocs() as $doc) {
					$msg .= '<li><a href="' . $this->generateUrl('_admin_doc_editGet', array('uid' => $doc->getId())) . '">' .
						 $doc->getOriginalName() . '</a></li>';
				}
				$msg .= "<ul>";
			}
			$msg .= "</td><td>";
			if (\count($buy->getDocs()) == 0) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= "<ul>";
				foreach ($buy->getDocs() as $doc) {
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
				$this->translate('Buy.traceEdit', 
					array('%buy%' => $buy->getLabel(), '%mbpurchase%' => $buy->getMonthlyBalance()
						->getRef(), '%company%' => $buy->getCompany()
						->getCorporateName())) . $msg);
			$trace->setDtCrea(new \DateTime('now'));
			$em = $this->getEntityManager();
			$em->persist($trace);
			$em->flush();
		}
	}
}
