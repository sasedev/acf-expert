<?php
namespace Acf\AdminBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\Sale;
use Acf\AdminBundle\Form\Sale\UpdateNumberTForm as SaleUpdateNumberTForm;
use Acf\AdminBundle\Form\Sale\UpdateDtActivationTForm as SaleUpdateDtActivationTForm;
use Acf\AdminBundle\Form\Sale\UpdateBillTForm as SaleUpdateBillTForm;
use Acf\AdminBundle\Form\Sale\UpdateRelationTForm as SaleUpdateRelationTForm;
use Acf\AdminBundle\Form\Sale\UpdateLabelTForm as SaleUpdateLabelTForm;
use Acf\AdminBundle\Form\Sale\UpdateDeviseTForm as SaleUpdateDeviseTForm;
use Acf\AdminBundle\Form\Sale\UpdateConversionRateTForm as SaleUpdateConversionRateTForm;
use Acf\AdminBundle\Form\Sale\UpdateVatTForm as SaleUpdateVatTForm;
use Acf\AdminBundle\Form\Sale\UpdateVatDeviseTForm as SaleUpdateVatDeviseTForm;
use Acf\AdminBundle\Form\Sale\UpdateStampTForm as SaleUpdateStampTForm;
use Acf\AdminBundle\Form\Sale\UpdateStampDeviseTForm as SaleUpdateStampDeviseTForm;
use Acf\AdminBundle\Form\Sale\UpdateBalanceTtcTForm as SaleUpdateBalanceTtcTForm;
use Acf\AdminBundle\Form\Sale\UpdateBalanceTtcDeviseTForm as SaleUpdateBalanceTtcDeviseTForm;
use Acf\AdminBundle\Form\Sale\UpdateVatInfoTForm as SaleUpdateVatInfoTForm;
use Acf\AdminBundle\Form\Sale\UpdateRegimeTForm as SaleUpdateRegimeTForm;
use Acf\AdminBundle\Form\Sale\UpdateWithholdingTForm as SaleUpdateWithholdingTForm;
use Acf\AdminBundle\Form\Sale\UpdateBalanceNetTForm as SaleUpdateBalanceNetTForm;
use Acf\AdminBundle\Form\Sale\UpdateBalanceNetDeviseTForm as SaleUpdateBalanceNetDeviseTForm;
use Acf\AdminBundle\Form\Sale\UpdatePaymentTypeTForm as SaleUpdatePaymentTypeTForm;
use Acf\AdminBundle\Form\Sale\UpdateTransactionStatusTForm as SaleUpdateTransactionStatusTForm;
use Acf\AdminBundle\Form\Sale\UpdateValidatedTForm as SaleUpdateValidatedTForm;
use Acf\AdminBundle\Form\Sale\UpdateAccountTForm as SaleUpdateAccountTForm;
use Acf\AdminBundle\Form\Sale\UpdateOtherInfosTForm as SaleUpdateOtherInfosTForm;
use Acf\AdminBundle\Form\Sale\UpdateDocsTForm as SaleUpdateDocsTForm;
use Acf\AdminBundle\Form\SecondaryVat\NewTForm as SecondaryVatNewTForm;
use Acf\DataBundle\Entity\SecondaryVat;
use Acf\AdminBundle\Form\Doc\NewTForm as DocNewTForm;
use Acf\DataBundle\Entity\Doc;
use Acf\DataBundle\Entity\ConstantStr;
use Acf\DataBundle\Entity\Trace;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class SaleController extends BaseController
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
            $sale = $em->getRepository('AcfDataBundle:Sale')->find($uid);

            if (null == $sale) {
                $this->flashMsgSession('warning', $this->translate('Sale.delete.notfound'));
            } else {
                $em->remove($sale);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('Sale.delete.success', array(
                    '%sale%' => $sale->getNumber()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('Sale.delete.failure'));
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
            $sale = $em->getRepository('AcfDataBundle:Sale')->find($uid);

            if (null == $sale) {
                $this->flashMsgSession('warning', $this->translate('Sale.edit.notfound'));
            } else {
                $traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($sale->getId(), Trace::AE_SALE);
                $this->gvars['traces'] = array_reverse($traces);
                $saleUpdateNumberForm = $this->createForm(SaleUpdateNumberTForm::class, $sale);
                $saleUpdateDtActivationForm = $this->createForm(SaleUpdateDtActivationTForm::class, $sale);
                $saleUpdateBillForm = $this->createForm(SaleUpdateBillTForm::class, $sale);
                $saleUpdateRelationForm = $this->createForm(SaleUpdateRelationTForm::class, $sale, array(
                    'monthlybalance' => $sale->getMonthlyBalance()
                ));
                $saleUpdateLabelForm = $this->createForm(SaleUpdateLabelTForm::class, $sale);
                $saleUpdateDeviseForm = $this->createForm(SaleUpdateDeviseTForm::class, $sale);
                $saleUpdateConversionRateForm = $this->createForm(SaleUpdateConversionRateTForm::class, $sale);
                $saleUpdateVatForm = $this->createForm(SaleUpdateVatTForm::class, $sale);
                $saleUpdateVatDeviseForm = $this->createForm(SaleUpdateVatDeviseTForm::class, $sale);
                $saleUpdateStampForm = $this->createForm(SaleUpdateStampTForm::class, $sale);
                $saleUpdateStampDeviseForm = $this->createForm(SaleUpdateStampDeviseTForm::class, $sale);
                $saleUpdateBalanceTtcForm = $this->createForm(SaleUpdateBalanceTtcTForm::class, $sale);
                $saleUpdateBalanceTtcDeviseForm = $this->createForm(SaleUpdateBalanceTtcDeviseTForm::class, $sale);
                $saleUpdateVatInfoForm = $this->createForm(SaleUpdateVatInfoTForm::class, $sale);
                $saleUpdateRegimeForm = $this->createForm(SaleUpdateRegimeTForm::class, $sale);
                $saleUpdateWithholdingForm = $this->createForm(SaleUpdateWithholdingTForm::class, $sale, array(
                    'monthlybalance' => $sale->getMonthlyBalance()
                ));
                $saleUpdateBalanceNetForm = $this->createForm(SaleUpdateBalanceNetTForm::class, $sale);
                $saleUpdateBalanceNetDeviseForm = $this->createForm(SaleUpdateBalanceNetDeviseTForm::class, $sale);
                $saleUpdatePaymentTypeForm = $this->createForm(SaleUpdatePaymentTypeTForm::class, $sale);
                $saleUpdateTransactionStatusForm = $this->createForm(SaleUpdateTransactionStatusTForm::class, $sale);
                $saleUpdateValidatedForm = $this->createForm(SaleUpdateValidatedTForm::class, $sale);
                $saleUpdateAccountForm = $this->createForm(SaleUpdateAccountTForm::class, $sale, array(
                    'monthlybalance' => $sale->getMonthlyBalance()
                ));
                $saleUpdateOtherInfosForm = $this->createForm(SaleUpdateOtherInfosTForm::class, $sale);
                $saleUpdateDocsForm = $this->createForm(SaleUpdateDocsTForm::class, $sale, array(
                    'company' => $sale->getCompany()
                ));

                $secondaryVat = new SecondaryVat();
                $secondaryVat->setSale($sale);
                $secondaryVatNewForm = $this->createForm(SecondaryVatNewTForm::class, $secondaryVat, array(
                    'sale' => $sale
                ));

                $doc = new Doc();
                $doc->setCompany($sale->getCompany());
                $docNewForm = $this->createForm(DocNewTForm::class, $doc, array(
                    'company' => $sale->getCompany()
                ));

                $this->gvars['sale'] = $sale;
                $this->gvars['secondaryVat'] = $secondaryVat;
                $this->gvars['doc'] = $doc;
                $this->gvars['SaleUpdateNumberForm'] = $saleUpdateNumberForm->createView();
                $this->gvars['SaleUpdateDtActivationForm'] = $saleUpdateDtActivationForm->createView();
                $this->gvars['SaleUpdateBillForm'] = $saleUpdateBillForm->createView();
                $this->gvars['SaleUpdateRelationForm'] = $saleUpdateRelationForm->createView();
                $this->gvars['SaleUpdateLabelForm'] = $saleUpdateLabelForm->createView();
                $this->gvars['SaleUpdateDeviseForm'] = $saleUpdateDeviseForm->createView();
                $this->gvars['SaleUpdateConversionRateForm'] = $saleUpdateConversionRateForm->createView();
                $this->gvars['SaleUpdateVatForm'] = $saleUpdateVatForm->createView();
                $this->gvars['SaleUpdateVatDeviseForm'] = $saleUpdateVatDeviseForm->createView();
                $this->gvars['SaleUpdateStampForm'] = $saleUpdateStampForm->createView();
                $this->gvars['SaleUpdateStampDeviseForm'] = $saleUpdateStampDeviseForm->createView();
                $this->gvars['SaleUpdateBalanceTtcForm'] = $saleUpdateBalanceTtcForm->createView();
                $this->gvars['SaleUpdateBalanceTtcDeviseForm'] = $saleUpdateBalanceTtcDeviseForm->createView();
                $this->gvars['SaleUpdateVatInfoForm'] = $saleUpdateVatInfoForm->createView();
                $this->gvars['SaleUpdateRegimeForm'] = $saleUpdateRegimeForm->createView();
                $this->gvars['SaleUpdateWithholdingForm'] = $saleUpdateWithholdingForm->createView();
                $this->gvars['SaleUpdateBalanceNetForm'] = $saleUpdateBalanceNetForm->createView();
                $this->gvars['SaleUpdateBalanceNetDeviseForm'] = $saleUpdateBalanceNetDeviseForm->createView();
                $this->gvars['SaleUpdatePaymentTypeForm'] = $saleUpdatePaymentTypeForm->createView();
                $this->gvars['SaleUpdateTransactionStatusForm'] = $saleUpdateTransactionStatusForm->createView();
                $this->gvars['SaleUpdateValidatedForm'] = $saleUpdateValidatedForm->createView();
                $this->gvars['SaleUpdateAccountForm'] = $saleUpdateAccountForm->createView();
                $this->gvars['SaleUpdateOtherInfosForm'] = $saleUpdateOtherInfosForm->createView();
                $this->gvars['SaleUpdateDocsForm'] = $saleUpdateDocsForm->createView();
                $this->gvars['SecondaryVatNewForm'] = $secondaryVatNewForm->createView();
                $this->gvars['DocNewForm'] = $docNewForm->createView();

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');

                $this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
                $this->getSession()->remove('stabActive');

                $customersConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
                    'name' => 'customersPrefix'
                ));
                if (null == $customersConstStr) {
                    $customersConstStr = new ConstantStr();
                    $customersConstStr->setName('customersPrefix');
                    $customersConstStr->setValue('411');
                    $em->persist($customersConstStr);
                    $em->flush();
                }
                $customersPrefix = $customersConstStr->getValue();
                $this->gvars['customersPrefix'] = $customersPrefix;

                $this->gvars['pagetitle'] = $this->translate('pagetitle.sale.edit', array(
                    '%sale%' => $sale->getNumber()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.sale.edit.txt', array(
                    '%sale%' => $sale->getNumber()
                ));

                return $this->renderResponse('AcfAdminBundle:Sale:edit.html.twig', $this->gvars);
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
            $sale = $em->getRepository('AcfDataBundle:Sale')->find($uid);

            if (null == $sale) {
                $this->flashMsgSession('warning', $this->translate('Sale.edit.notfound'));
            } else {
                $traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($sale->getId(), Trace::AE_SALE);
                $this->gvars['traces'] = array_reverse($traces);
                $saleUpdateNumberForm = $this->createForm(SaleUpdateNumberTForm::class, $sale);
                $saleUpdateDtActivationForm = $this->createForm(SaleUpdateDtActivationTForm::class, $sale);
                $saleUpdateBillForm = $this->createForm(SaleUpdateBillTForm::class, $sale);
                $saleUpdateRelationForm = $this->createForm(SaleUpdateRelationTForm::class, $sale, array(
                    'monthlybalance' => $sale->getMonthlyBalance()
                ));
                $saleUpdateLabelForm = $this->createForm(SaleUpdateLabelTForm::class, $sale);
                $saleUpdateDeviseForm = $this->createForm(SaleUpdateDeviseTForm::class, $sale);
                $saleUpdateConversionRateForm = $this->createForm(SaleUpdateConversionRateTForm::class, $sale);
                $saleUpdateVatForm = $this->createForm(SaleUpdateVatTForm::class, $sale);
                $saleUpdateVatDeviseForm = $this->createForm(SaleUpdateVatDeviseTForm::class, $sale);
                $saleUpdateStampForm = $this->createForm(SaleUpdateStampTForm::class, $sale);
                $saleUpdateStampDeviseForm = $this->createForm(SaleUpdateStampDeviseTForm::class, $sale);
                $saleUpdateBalanceTtcForm = $this->createForm(SaleUpdateBalanceTtcTForm::class, $sale);
                $saleUpdateBalanceTtcDeviseForm = $this->createForm(SaleUpdateBalanceTtcDeviseTForm::class, $sale);
                $saleUpdateVatInfoForm = $this->createForm(SaleUpdateVatInfoTForm::class, $sale);
                $saleUpdateRegimeForm = $this->createForm(SaleUpdateRegimeTForm::class, $sale);
                $saleUpdateWithholdingForm = $this->createForm(SaleUpdateWithholdingTForm::class, $sale, array(
                    'monthlybalance' => $sale->getMonthlyBalance()
                ));
                $saleUpdateBalanceNetForm = $this->createForm(SaleUpdateBalanceNetTForm::class, $sale);
                $saleUpdateBalanceNetDeviseForm = $this->createForm(SaleUpdateBalanceNetDeviseTForm::class, $sale);
                $saleUpdatePaymentTypeForm = $this->createForm(SaleUpdatePaymentTypeTForm::class, $sale);
                $saleUpdateTransactionStatusForm = $this->createForm(SaleUpdateTransactionStatusTForm::class, $sale);
                $saleUpdateValidatedForm = $this->createForm(SaleUpdateValidatedTForm::class, $sale);
                $saleUpdateAccountForm = $this->createForm(SaleUpdateAccountTForm::class, $sale, array(
                    'monthlybalance' => $sale->getMonthlyBalance()
                ));
                $saleUpdateOtherInfosForm = $this->createForm(SaleUpdateOtherInfosTForm::class, $sale);
                $saleUpdateDocsForm = $this->createForm(SaleUpdateDocsTForm::class, $sale, array(
                    'company' => $sale->getCompany()
                ));

                $secondaryVat = new SecondaryVat();
                $secondaryVat->setSale($sale);
                $secondaryVatNewForm = $this->createForm(SecondaryVatNewTForm::class, $secondaryVat, array(
                    'sale' => $sale
                ));

                $doc = new Doc();
                $doc->setCompany($sale->getCompany());
                $docNewForm = $this->createForm(DocNewTForm::class, $doc, array(
                    'company' => $sale->getCompany()
                ));

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
                $this->getSession()->remove('stabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                $cloneSale = clone $sale;

                if (isset($reqData['SaleUpdateNumberForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $saleUpdateNumberForm->handleRequest($request);
                    if ($saleUpdateNumberForm->isValid()) {
                        $em->persist($sale);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Sale.edit.success', array(
                            '%sale%' => $sale->getNumber()
                        )));

                        $this->traceEntity($cloneSale, $sale);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($sale);

                        $this->flashMsgSession('error', $this->translate('Sale.edit.failure', array(
                            '%sale%' => $sale->getNumber()
                        )));
                    }
                } elseif (isset($reqData['SaleUpdateDtActivationForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $saleUpdateDtActivationForm->handleRequest($request);
                    if ($saleUpdateDtActivationForm->isValid()) {
                        $em->persist($sale);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Sale.edit.success', array(
                            '%sale%' => $sale->getNumber()
                        )));

                        $this->traceEntity($cloneSale, $sale);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($sale);

                        $this->flashMsgSession('error', $this->translate('Sale.edit.failure', array(
                            '%sale%' => $sale->getNumber()
                        )));
                    }
                } elseif (isset($reqData['SaleUpdateBillForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $saleUpdateBillForm->handleRequest($request);
                    if ($saleUpdateBillForm->isValid()) {
                        $em->persist($sale);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Sale.edit.success', array(
                            '%sale%' => $sale->getNumber()
                        )));

                        $this->traceEntity($cloneSale, $sale);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($sale);

                        $this->flashMsgSession('error', $this->translate('Sale.edit.failure', array(
                            '%sale%' => $sale->getNumber()
                        )));
                    }
                } elseif (isset($reqData['SaleUpdateRelationForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $saleUpdateRelationForm->handleRequest($request);
                    if ($saleUpdateRelationForm->isValid()) {
                        $em->persist($sale);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Sale.edit.success', array(
                            '%sale%' => $sale->getNumber()
                        )));

                        $this->traceEntity($cloneSale, $sale);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($sale);

                        $this->flashMsgSession('error', $this->translate('Sale.edit.failure', array(
                            '%sale%' => $sale->getNumber()
                        )));
                    }
                } elseif (isset($reqData['SaleUpdateLabelForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $saleUpdateLabelForm->handleRequest($request);
                    if ($saleUpdateLabelForm->isValid()) {
                        $em->persist($sale);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Sale.edit.success', array(
                            '%sale%' => $sale->getNumber()
                        )));

                        $this->traceEntity($cloneSale, $sale);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($sale);

                        $this->flashMsgSession('error', $this->translate('Sale.edit.failure', array(
                            '%sale%' => $sale->getNumber()
                        )));
                    }
                } elseif (isset($reqData['SaleUpdateDeviseForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $saleUpdateDeviseForm->handleRequest($request);
                    if ($saleUpdateDeviseForm->isValid()) {
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
                        $em->persist($sale);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Sale.edit.success', array(
                            '%sale%' => $sale->getNumber()
                        )));

                        $this->traceEntity($cloneSale, $sale);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($sale);

                        $this->flashMsgSession('error', $this->translate('Sale.edit.failure', array(
                            '%sale%' => $sale->getNumber()
                        )));
                    }
                } elseif (isset($reqData['SaleUpdateConversionRateForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $saleUpdateConversionRateForm->handleRequest($request);
                    if ($saleUpdateConversionRateForm->isValid()) {
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
                        $em->persist($sale);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Sale.edit.success', array(
                            '%sale%' => $sale->getNumber()
                        )));

                        $this->traceEntity($cloneSale, $sale);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($sale);

                        $this->flashMsgSession('error', $this->translate('Sale.edit.failure', array(
                            '%sale%' => $sale->getNumber()
                        )));
                    }
                } elseif (isset($reqData['SaleUpdateVatForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $saleUpdateVatForm->handleRequest($request);
                    if ($saleUpdateVatForm->isValid()) {
                        $sale->setConversionRate(1);
                        $sale->setVatDevise($sale->getVat());
                        $em->persist($sale);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Sale.edit.success', array(
                            '%sale%' => $sale->getNumber()
                        )));

                        $this->traceEntity($cloneSale, $sale);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($sale);

                        $this->flashMsgSession('error', $this->translate('Sale.edit.failure', array(
                            '%sale%' => $sale->getNumber()
                        )));
                    }
                } elseif (isset($reqData['SaleUpdateVatDeviseForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $saleUpdateVatDeviseForm->handleRequest($request);
                    if ($saleUpdateVatDeviseForm->isValid()) {
                        $sale->setVat($sale->getVatDevise() * $sale->getConversionRate());
                        $em->persist($sale);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Sale.edit.success', array(
                            '%sale%' => $sale->getNumber()
                        )));

                        $this->traceEntity($cloneSale, $sale);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($sale);

                        $this->flashMsgSession('error', $this->translate('Sale.edit.failure', array(
                            '%sale%' => $sale->getNumber()
                        )));
                    }
                } elseif (isset($reqData['SaleUpdateStampForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $saleUpdateStampForm->handleRequest($request);
                    if ($saleUpdateStampForm->isValid()) {
                        $sale->setConversionRate(1);
                        $sale->setStampDevise($sale->getStamp());
                        $em->persist($sale);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Sale.edit.success', array(
                            '%sale%' => $sale->getNumber()
                        )));

                        $this->traceEntity($cloneSale, $sale);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($sale);

                        $this->flashMsgSession('error', $this->translate('Sale.edit.failure', array(
                            '%sale%' => $sale->getNumber()
                        )));
                    }
                } elseif (isset($reqData['SaleUpdateStampDeviseForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $saleUpdateStampDeviseForm->handleRequest($request);
                    if ($saleUpdateStampDeviseForm->isValid()) {
                        $sale->setStamp($sale->getStampDevise() * $sale->getConversionRate());
                        $em->persist($sale);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Sale.edit.success', array(
                            '%sale%' => $sale->getNumber()
                        )));

                        $this->traceEntity($cloneSale, $sale);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($sale);

                        $this->flashMsgSession('error', $this->translate('Sale.edit.failure', array(
                            '%sale%' => $sale->getNumber()
                        )));
                    }
                } elseif (isset($reqData['SaleUpdateBalanceTtcForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $saleUpdateBalanceTtcForm->handleRequest($request);
                    if ($saleUpdateBalanceTtcForm->isValid()) {
                        $sale->setConversionRate(1);
                        $sale->setBalanceTtcDevise($sale->getBalanceTtc());
                        $em->persist($sale);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Sale.edit.success', array(
                            '%sale%' => $sale->getNumber()
                        )));

                        $this->traceEntity($cloneSale, $sale);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($sale);

                        $this->flashMsgSession('error', $this->translate('Sale.edit.failure', array(
                            '%sale%' => $sale->getNumber()
                        )));
                    }
                } elseif (isset($reqData['SaleUpdateVatInfoForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $saleUpdateVatInfoForm->handleRequest($request);
                    if ($saleUpdateVatInfoForm->isValid()) {
                        $em->persist($sale);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Sale.edit.success', array(
                            '%sale%' => $sale->getNumber()
                        )));

                        $this->traceEntity($cloneSale, $sale);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($sale);

                        $this->flashMsgSession('error', $this->translate('Sale.edit.failure', array(
                            '%sale%' => $sale->getNumber()
                        )));
                    }
                } elseif (isset($reqData['SaleUpdateRegimeForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $saleUpdateRegimeForm->handleRequest($request);
                    if ($saleUpdateRegimeForm->isValid()) {
                        $em->persist($sale);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Sale.edit.success', array(
                            '%sale%' => $sale->getNumber()
                        )));

                        $this->traceEntity($cloneSale, $sale);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($sale);

                        $this->flashMsgSession('error', $this->translate('Sale.edit.failure', array(
                            '%sale%' => $sale->getNumber()
                        )));
                    }
                } elseif (isset($reqData['SaleUpdateWithholdingForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $saleUpdateWithholdingForm->handleRequest($request);
                    if ($saleUpdateWithholdingForm->isValid()) {
                        $em->persist($sale);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Sale.edit.success', array(
                            '%sale%' => $sale->getNumber()
                        )));

                        $this->traceEntity($cloneSale, $sale);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($sale);

                        $this->flashMsgSession('error', $this->translate('Sale.edit.failure', array(
                            '%sale%' => $sale->getNumber()
                        )));
                    }
                } elseif (isset($reqData['SaleUpdateBalanceNetDeviseForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $saleUpdateBalanceNetDeviseForm->handleRequest($request);
                    if ($saleUpdateBalanceNetDeviseForm->isValid()) {
                        $sale->setBalanceNet($sale->getBalanceNetDevise() * $sale->getConversionRate());
                        $em->persist($sale);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Sale.edit.success', array(
                            '%sale%' => $sale->getNumber()
                        )));

                        $this->traceEntity($cloneSale, $sale);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($sale);

                        $this->flashMsgSession('error', $this->translate('Sale.edit.failure', array(
                            '%sale%' => $sale->getNumber()
                        )));
                    }
                } elseif (isset($reqData['SaleUpdateBalanceNetForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $saleUpdateBalanceNetForm->handleRequest($request);
                    if ($saleUpdateBalanceNetForm->isValid()) {
                        $sale->setConversionRate(1);
                        $sale->setBalanceNetDevise($sale->getBalanceNet());
                        $em->persist($sale);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Sale.edit.success', array(
                            '%sale%' => $sale->getNumber()
                        )));

                        $this->traceEntity($cloneSale, $sale);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($sale);

                        $this->flashMsgSession('error', $this->translate('Sale.edit.failure', array(
                            '%sale%' => $sale->getNumber()
                        )));
                    }
                } elseif (isset($reqData['SaleUpdatePaymentTypeForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $saleUpdatePaymentTypeForm->handleRequest($request);
                    if ($saleUpdatePaymentTypeForm->isValid()) {
                        $em->persist($sale);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Sale.edit.success', array(
                            '%sale%' => $sale->getNumber()
                        )));

                        $this->traceEntity($cloneSale, $sale);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($sale);

                        $this->flashMsgSession('error', $this->translate('Sale.edit.failure', array(
                            '%sale%' => $sale->getNumber()
                        )));
                    }
                } elseif (isset($reqData['SaleUpdateTransactionStatusForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $saleUpdateTransactionStatusForm->handleRequest($request);
                    if ($saleUpdateTransactionStatusForm->isValid()) {
                        $em->persist($sale);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Sale.edit.success', array(
                            '%sale%' => $sale->getNumber()
                        )));

                        $this->traceEntity($cloneSale, $sale);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($sale);

                        $this->flashMsgSession('error', $this->translate('Sale.edit.failure', array(
                            '%sale%' => $sale->getNumber()
                        )));
                    }
                } elseif (isset($reqData['SaleUpdateValidatedForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $saleUpdateValidatedForm->handleRequest($request);
                    if ($saleUpdateValidatedForm->isValid()) {
                        $em->persist($sale);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Sale.edit.success', array(
                            '%sale%' => $sale->getNumber()
                        )));

                        $this->traceEntity($cloneSale, $sale);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($sale);

                        $this->flashMsgSession('error', $this->translate('Sale.edit.failure', array(
                            '%sale%' => $sale->getNumber()
                        )));
                    }
                } elseif (isset($reqData['SaleUpdateAccountForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $saleUpdateAccountForm->handleRequest($request);
                    if ($saleUpdateAccountForm->isValid()) {
                        $em->persist($sale);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Sale.edit.success', array(
                            '%sale%' => $sale->getNumber()
                        )));

                        $this->traceEntity($cloneSale, $sale);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($sale);

                        $this->flashMsgSession('error', $this->translate('Sale.edit.failure', array(
                            '%sale%' => $sale->getNumber()
                        )));
                    }
                } elseif (isset($reqData['SaleUpdateOtherInfosForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $saleUpdateOtherInfosForm->handleRequest($request);
                    if ($saleUpdateOtherInfosForm->isValid()) {
                        $em->persist($sale);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Sale.edit.success', array(
                            '%sale%' => $sale->getNumber()
                        )));

                        $this->traceEntity($cloneSale, $sale);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($sale);

                        $this->flashMsgSession('error', $this->translate('Sale.edit.failure', array(
                            '%sale%' => $sale->getNumber()
                        )));
                    }
                } elseif (isset($reqData['SecondaryVatNewForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $secondaryVatNewForm->handleRequest($request);
                    if ($secondaryVatNewForm->isValid()) {
                        $em->persist($secondaryVat);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('SecondaryVat.add.success', array(
                            '%sale%' => $sale->getNumber(),
                            '%secondaryVat%' => $this->translate('SecondaryVat.vatInfo.' . $secondaryVat->getVatInfo())
                        )));
                        $this->gvars['tabActive'] = 1;
                        $this->getSession()->set('tabActive', 1);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($sale);

                        $this->flashMsgSession('error', $this->translate('SecondaryVat.add.failure'));
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
                            $doc->setCompany($sale->getCompany());

                            $doc->setFileName($fileName);
                            $doc->setOriginalName($originalName);
                            $doc->setSize($size);
                            $doc->setMimeType($mimeType);
                            $doc->setMd5($md5);
                            $doc->setDescription($docNewForm['description']->getData());
                            $em->persist($doc);

                            $sale->addDoc($doc);

                            $docNames .= $doc->getOriginalName() . ' ';

                            $docs[] = $doc;
                        }

                        $em->persist($sale);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Doc.add.success', array(
                            '%doc%' => $docNames
                        )));

                        $from = $this->getParameter('mail_from');
                        $fromName = $this->getParameter('mail_from_name');
                        $subject = $this->translate('_mail.newdocsCloud.subject', array(), 'messages');

                        $company = $sale->getCompany();
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
                                $message->setBody($this->renderView('AcfAdminBundle:Doc:sendmail.html.twig', $mvars), 'text/html');
                                $this->sendmail($message);
                            }
                        }
                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        $this->traceEntity($cloneSale, $sale);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($sale);

                        $this->flashMsgSession('error', $this->translate('Doc.add.failure'));
                    }
                } elseif (isset($reqData['SaleUpdateDocsForm'])) {
                    $this->gvars['tabActive'] = 3;
                    $this->getSession()->set('tabActive', 3);
                    $this->gvars['stabActive'] = 2;
                    $this->getSession()->set('stabActive', 2);
                    $saleUpdateDocsForm->handleRequest($request);
                    if ($saleUpdateDocsForm->isValid()) {
                        $em->persist($sale);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Sale.edit.success', array(
                            '%sale%' => $sale->getNumber()
                        )));
                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        $this->traceEntity($cloneSale, $sale);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($sale);

                        $this->flashMsgSession('error', $this->translate('Sale.edit.failure', array(
                            '%sale%' => $sale->getNumber()
                        )));
                    }
                }

                $this->gvars['sale'] = $sale;
                $this->gvars['secondaryVat'] = $secondaryVat;
                $this->gvars['doc'] = $doc;
                $this->gvars['SaleUpdateNumberForm'] = $saleUpdateNumberForm->createView();
                $this->gvars['SaleUpdateDtActivationForm'] = $saleUpdateDtActivationForm->createView();
                $this->gvars['SaleUpdateBillForm'] = $saleUpdateBillForm->createView();
                $this->gvars['SaleUpdateRelationForm'] = $saleUpdateRelationForm->createView();
                $this->gvars['SaleUpdateLabelForm'] = $saleUpdateLabelForm->createView();
                $this->gvars['SaleUpdateDeviseForm'] = $saleUpdateDeviseForm->createView();
                $this->gvars['SaleUpdateConversionRateForm'] = $saleUpdateConversionRateForm->createView();
                $this->gvars['SaleUpdateVatForm'] = $saleUpdateVatForm->createView();
                $this->gvars['SaleUpdateVatDeviseForm'] = $saleUpdateVatDeviseForm->createView();
                $this->gvars['SaleUpdateStampForm'] = $saleUpdateStampForm->createView();
                $this->gvars['SaleUpdateStampDeviseForm'] = $saleUpdateStampDeviseForm->createView();
                $this->gvars['SaleUpdateBalanceTtcForm'] = $saleUpdateBalanceTtcForm->createView();
                $this->gvars['SaleUpdateBalanceTtcDeviseForm'] = $saleUpdateBalanceTtcDeviseForm->createView();
                $this->gvars['SaleUpdateVatInfoForm'] = $saleUpdateVatInfoForm->createView();
                $this->gvars['SaleUpdateRegimeForm'] = $saleUpdateRegimeForm->createView();
                $this->gvars['SaleUpdateWithholdingForm'] = $saleUpdateWithholdingForm->createView();
                $this->gvars['SaleUpdateBalanceNetForm'] = $saleUpdateBalanceNetForm->createView();
                $this->gvars['SaleUpdateBalanceNetDeviseForm'] = $saleUpdateBalanceNetDeviseForm->createView();
                $this->gvars['SaleUpdatePaymentTypeForm'] = $saleUpdatePaymentTypeForm->createView();
                $this->gvars['SaleUpdateTransactionStatusForm'] = $saleUpdateTransactionStatusForm->createView();
                $this->gvars['SaleUpdateValidatedForm'] = $saleUpdateValidatedForm->createView();
                $this->gvars['SaleUpdateAccountForm'] = $saleUpdateAccountForm->createView();
                $this->gvars['SaleUpdateOtherInfosForm'] = $saleUpdateOtherInfosForm->createView();
                $this->gvars['SaleUpdateDocsForm'] = $saleUpdateDocsForm->createView();
                $this->gvars['SecondaryVatNewForm'] = $secondaryVatNewForm->createView();
                $this->gvars['DocNewForm'] = $docNewForm->createView();

                $customersConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
                    'name' => 'customersPrefix'
                ));
                if (null == $customersConstStr) {
                    $customersConstStr = new ConstantStr();
                    $customersConstStr->setName('customersPrefix');
                    $customersConstStr->setValue('411');
                    $em->persist($customersConstStr);
                    $em->flush();
                }
                $customersPrefix = $customersConstStr->getValue();
                $this->gvars['customersPrefix'] = $customersPrefix;

                $this->gvars['pagetitle'] = $this->translate('pagetitle.sale.edit', array(
                    '%sale%' => $sale->getNumber()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.sale.edit.txt', array(
                    '%sale%' => $sale->getNumber()
                ));

                return $this->renderResponse('AcfAdminBundle:Sale:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }

    protected function traceEntity(Sale $cloneSale, Sale $sale)
    {
        $curUser = $this->getSecurityTokenStorage()
            ->getToken()
            ->getUser();
        $trace = new Trace();
        $trace->setActionId($sale->getId());
        $trace->setActionType(Trace::AT_UPDATE);
        $trace->setUserId($curUser->getId());
        $trace->setCompanyId($sale->getCompany()
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

        $tableBegin = ': <br><table class="table table-bordered table-condensed table-hover table-striped">';
        $tableBegin .= '<thead><tr><th class="text-left">' . $this->translate('Entity.field') . '</th>';
        $tableBegin .= '<th class="text-left">' . $this->translate('Entity.oldVal') . '</th>';
        $tableBegin .= '<th class="text-left">' . $this->translate('Entity.newVal') . '</th></tr></thead><tbody>';

        $tableEnd = '</tbody></table>';

        $trace->setActionEntity(Trace::AE_SALE);
        $trace->setActionId2($sale->getMonthlyBalance()
            ->getCompany()
            ->getId());
        $trace->setActionEntity2(Trace::AE_COMPANY);
        $trace->setActionId3($sale->getMonthlyBalance()
            ->getId());
        $trace->setActionEntity2(Trace::AE_MBSALE);

        $msg = '';

        if ($cloneSale->getNumber() != $sale->getNumber()) {
            $msg .= '<tr><td>' . $this->translate('Sale.number.label') . '</td><td>';
            if ($cloneSale->getNumber() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSale->getNumberFormated();
            }
            $msg .= '</td><td>';
            if ($sale->getNumber() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $sale->getNumberFormated();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSale->getDtActivation() != $sale->getDtActivation()) {
            $msg .= '<tr><td>' . $this->translate('Sale.dtActivation.label') . '</td><td>';
            if ($cloneSale->getDtActivation() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSale->getDtActivation()->format('Y-m-d');
            }
            $msg .= '</td><td>';
            if ($sale->getDtActivation() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $sale->getDtActivation()->format('Y-m-d');
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSale->getBill() != $sale->getBill()) {
            $msg .= '<tr><td>' . $this->translate('Sale.bill.label') . '</td><td>';
            if ($cloneSale->getBill() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSale->getBill();
            }
            $msg .= '</td><td>';
            if ($sale->getBill() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $sale->getBill();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSale->getRelation() != $sale->getRelation()) {
            $msg .= '<tr><td>' . $this->translate('Sale.relation.label') . '</td><td>';
            if ($cloneSale->getRelation() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSale->getRelation()->getLabel();
            }
            $msg .= '</td><td>';
            if ($sale->getRelation() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $sale->getRelation()->getLabel();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSale->getLabel() != $sale->getLabel()) {
            $msg .= '<tr><td>' . $this->translate('Sale.label.label') . '</td><td>';
            if ($cloneSale->getLabel() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSale->getLabel();
            }
            $msg .= '</td><td>';
            if ($sale->getLabel() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $sale->getLabel();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSale->getDevise() != $sale->getDevise()) {
            $msg .= '<tr><td>' . $this->translate('Sale.devise.label') . '</td><td>';
            if ($cloneSale->getDevise() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSale->getDevise();
            }
            $msg .= '</td><td>';
            if ($sale->getDevise() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $sale->getDevise();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSale->getConversionRate() != $sale->getConversionRate()) {
            $msg .= '<tr><td>' . $this->translate('Sale.conversionRate.label') . '</td><td>';
            if ($cloneSale->getConversionRate() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSale->getConversionRate();
            }
            $msg .= '</td><td>';
            if ($sale->getConversionRate() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $sale->getConversionRate();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSale->getVat() != $sale->getVat()) {
            $msg .= '<tr><td>' . $this->translate('Sale.vat.label') . '</td><td>';
            if ($cloneSale->getVat() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSale->getVat();
            }
            $msg .= '</td><td>';
            if ($sale->getVat() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $sale->getVat();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSale->getStamp() != $sale->getStamp()) {
            $msg .= '<tr><td>' . $this->translate('Sale.stamp.label') . '</td><td>';
            if ($cloneSale->getStamp() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSale->getStamp();
            }
            $msg .= '</td><td>';
            if ($sale->getStamp() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $sale->getStamp();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSale->getBalanceTtc() != $sale->getBalanceTtc()) {
            $msg .= '<tr><td>' . $this->translate('Sale.balanceTtc.label') . '</td><td>';
            if ($cloneSale->getBalanceTtc() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSale->getBalanceTtc();
            }
            $msg .= '</td><td>';
            if ($sale->getBalanceTtc() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $sale->getBalanceTtc();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSale->getBalanceNet() != $sale->getBalanceNet()) {
            $msg .= '<tr><td>' . $this->translate('Sale.balanceNet.label') . '</td><td>';
            if ($cloneSale->getBalanceNet() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSale->getBalanceNet();
            }
            $msg .= '</td><td>';
            if ($sale->getBalanceNet() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $sale->getBalanceNet();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSale->getVatDevise() != $sale->getVatDevise()) {
            $msg .= '<tr><td>' . $this->translate('Sale.vatDevise.label') . '</td><td>';
            if ($cloneSale->getVatDevise() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSale->getVatDevise();
            }
            $msg .= '</td><td>';
            if ($sale->getVatDevise() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $sale->getVatDevise();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSale->getStampDevise() != $sale->getStampDevise()) {
            $msg .= '<tr><td>' . $this->translate('Sale.stampDevise.label') . '</td><td>';
            if ($cloneSale->getStampDevise() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSale->getStampDevise();
            }
            $msg .= '</td><td>';
            if ($sale->getStampDevise() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $sale->getStampDevise();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSale->getBalanceTtcDevise() != $sale->getBalanceTtcDevise()) {
            $msg .= '<tr><td>' . $this->translate('Sale.balanceTtcDevise.label') . '</td><td>';
            if ($cloneSale->getBalanceTtcDevise() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSale->getBalanceTtcDevise();
            }
            $msg .= '</td><td>';
            if ($sale->getBalanceTtcDevise() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $sale->getBalanceTtcDevise();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSale->getBalanceNetDevise() != $sale->getBalanceNetDevise()) {
            $msg .= '<tr><td>' . $this->translate('Sale.balanceNetDevise.label') . '</td><td>';
            if ($cloneSale->getBalanceNetDevise() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSale->getBalanceNetDevise();
            }
            $msg .= '</td><td>';
            if ($sale->getBalanceNetDevise() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $sale->getBalanceNetDevise();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSale->getVatInfo() != $sale->getVatInfo()) {
            $msg .= '<tr><td>' . $this->translate('Sale.vatInfo.label') . '</td><td>';
            if ($cloneSale->getVatInfo() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('Sale.vatInfo.' . $cloneSale->getVatInfo());
            }
            $msg .= '</td><td>';
            if ($sale->getVatInfo() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('Sale.vatInfo.' . $sale->getVatInfo());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSale->getRegime() != $sale->getRegime()) {
            $msg .= '<tr><td>' . $this->translate('Sale.regime.label') . '</td><td>';
            if ($cloneSale->getRegime() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('Sale.regime.' . $cloneSale->getRegime());
            }
            $msg .= '</td><td>';
            if ($sale->getRegime() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('Sale.regime.' . $sale->getRegime());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSale->getWithholding() != $sale->getWithholding()) {
            $msg .= '<tr><td>' . $this->translate('Sale.withholding.label') . '</td><td>';
            if ($cloneSale->getWithholding() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSale->getWithholding()->getLabel();
            }
            $msg .= '</td><td>';
            if ($sale->getWithholding() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $sale->getWithholding()->getLabel();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSale->getPaymentType() != $sale->getPaymentType()) {
            $msg .= '<tr><td>' . $this->translate('Sale.paymentType.label') . '</td><td>';
            if ($cloneSale->getPaymentType() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('Transaction.paymentType.' . $cloneSale->getPaymentType());
            }
            $msg .= '</td><td>';
            if ($sale->getPaymentType() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('Transaction.paymentType.' . $sale->getPaymentType());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSale->getDtPayment() != $sale->getDtPayment()) {
            $msg .= '<tr><td>' . $this->translate('Sale.dtPayment.label') . '</td><td>';
            if ($cloneSale->getDtPayment() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSale->getDtPayment()->format('Y-m-d');
            }
            $msg .= '</td><td>';
            if ($sale->getDtPayment() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $sale->getDtActivation()->format('Y-m-d');
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSale->getAccount() != $sale->getAccount()) {
            $msg .= '<tr><td>' . $this->translate('Sale.account.label') . '</td><td>';
            if ($cloneSale->getAccount() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSale->getAccount()->getLabel();
            }
            $msg .= '</td><td>';
            if ($sale->getAccount() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $sale->getAccount()->getLabel();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSale->getNature() != $sale->getNature()) {
            $msg .= '<tr><td>' . $this->translate('Sale.nature.label') . '</td><td>';
            if ($cloneSale->getNature() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSale->getNature()->getLabel();
            }
            $msg .= '</td><td>';
            if ($sale->getNature() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $sale->getNature()->getLabel();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSale->getTransactionStatus() != $sale->getTransactionStatus()) {
            $msg .= '<tr><td>' . $this->translate('Sale.transactionStatus.label') . '</td><td>';
            if ($cloneSale->getTransactionStatus() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('Transaction.transactionStatus.' . $cloneSale->getTransactionStatus());
            }
            $msg .= '</td><td>';
            if ($sale->getTransactionStatus() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('Transaction.transactionStatus.' . $sale->getTransactionStatus());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSale->getValidated() != $sale->getValidated()) {
            $msg .= '<tr><td>' . $this->translate('Sale.validated.label') . '</td><td>';
            if ($cloneSale->getValidated() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('Transaction.validated.' . $cloneSale->getValidated());
            }
            $msg .= '</td><td>';
            if ($sale->getValidated() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('Transaction.validated.' . $sale->getValidated());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSale->getOtherInfos() != $sale->getOtherInfos()) {
            $msg .= '<tr><td>' . $this->translate('Sale.otherInfos.label') . '</td><td>';
            if ($cloneSale->getOtherInfos() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSale->getOtherInfos();
            }
            $msg .= '</td><td>';
            if ($sale->getOtherInfos() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $sale->getOtherInfos();
            }
            $msg .= '</td></tr>';
        }

        if (\count(\array_diff($sale->getDocs()->toArray(), $cloneSale->getDocs()->toArray())) != 0 || \count(\array_diff($cloneSale->getDocs()->toArray(), $sale->getDocs()->toArray())) != 0) {
            $msg .= '<tr><td>' . $this->translate('Sale.docs.label') . '</td><td>';
            if (\count($cloneSale->getDocs()) == 0) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= '<ul>';
                foreach ($cloneSale->getDocs() as $doc) {
                    $msg .= '<li><a href="' . $this->generateUrl('_admin_doc_editGet', array(
                        'uid' => $doc->getId()
                    )) . '">' . $doc->getOriginalName() . '</a></li>';
                }
                $msg .= '<ul>';
            }
            $msg .= '</td><td>';
            if (\count($sale->getDocs()) == 0) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= '<ul>';
                foreach ($sale->getDocs() as $doc) {
                    $msg .= '<li><a href="' . $this->generateUrl('_admin_doc_editGet', array(
                        'uid' => $doc->getId()
                    )) . '">' . $doc->getOriginalName() . '</a></li>';
                }
                $msg .= '<ul>';
            }
            $msg .= '</td></tr>';
        }

        if (\count(\array_diff($sale->getSecondaryVats()->toArray(), $cloneSale->getSecondaryVats()->toArray())) != 0 || \count(\array_diff($cloneSale->getSecondaryVats()->toArray(), $sale->getSecondaryVats()->toArray())) != 0) {
            $msg .= '<tr><td>' . $this->translate('Sale.secondaryVats.label') . '</td><td>';
            if (\count($cloneSale->getSecondaryVats()) == 0) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= '<ul>';
                foreach ($cloneSale->getSecondaryVats() as $secondaryVat) {
                    $msg .= '<li><a href="' . $this->generateUrl('_admin_secondaryVat_editGet', array(
                        'uid' => $secondaryVat->getId()
                    )) . '">' . $secondaryVat->getVat() . '</a></li>';
                }
                $msg .= '<ul>';
            }
            $msg .= '</td><td>';
            if (\count($sale->getSecondaryVats()) == 0) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= '<ul>';
                foreach ($sale->getSecondaryVats() as $secondaryVat) {
                    $msg .= '<li><a href="' . $this->generateUrl('_admin_secondaryVat_editGet', array(
                        'uid' => $secondaryVat->getId()
                    )) . '">' . $secondaryVat->getVat() . '</a></li>';
                }
                $msg .= '<ul>';
            }
            $msg .= '</td></tr>';
        }

        if ($msg != '') {

            $msg = $tableBegin . $msg . $tableEnd;

            $trace->setMsg($this->translate('Sale.traceEdit', array(
                '%sale%' => $sale->getLabel(),
                '%mbsale%' => $sale->getMonthlyBalance()
                    ->getRef(),
                '%company%' => $sale->getCompany()
                    ->getCorporateName()
            )) . $msg);
            $trace->setDtCrea(new \DateTime('now'));
            $em = $this->getEntityManager();
            $em->persist($trace);
            $em->flush();
        }
    }
}
