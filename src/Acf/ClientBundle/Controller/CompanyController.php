<?php
namespace Acf\ClientBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\Company;
use Acf\ClientBundle\Form\Company\UpdateTypeTForm as CompanyUpdateTypeTForm;
use Acf\ClientBundle\Form\Company\UpdateCorporateNameTForm as CompanyUpdateCorporateNameTForm;
use Acf\ClientBundle\Form\Company\UpdateFiscTForm as CompanyUpdateFiscTForm;
use Acf\ClientBundle\Form\Company\UpdateTribunalTForm as CompanyUpdateTribunalTForm;
use Acf\ClientBundle\Form\Company\UpdatePhysicaltypeTForm as CompanyUpdatePhysicaltypeTForm;
use Acf\ClientBundle\Form\Company\UpdateCnssTForm as CompanyUpdateCnssTForm;
use Acf\ClientBundle\Form\Company\UpdateCnssBureauTForm as CompanyUpdateCnssBureauTForm;
use Acf\ClientBundle\Form\Company\UpdateSectorsTForm as CompanyUpdateSectorsTForm;
use Acf\ClientBundle\Form\Company\UpdatePhoneTForm as CompanyUpdatePhoneTForm;
use Acf\ClientBundle\Form\Company\UpdateMobileTForm as CompanyUpdateMobileTForm;
use Acf\ClientBundle\Form\Company\UpdateFaxTForm as CompanyUpdateFaxTForm;
use Acf\ClientBundle\Form\Company\UpdateEmailTForm as CompanyUpdateEmailTForm;
use Acf\ClientBundle\Form\Company\UpdateAdrTForm as CompanyUpdateAdrTForm;
use Acf\ClientBundle\Form\Company\UpdateOtherInfosTForm as CompanyUpdateOtherInfosTForm;
use Acf\ClientBundle\Form\Company\UpdateActionvnTForm as CompanyUpdateActionvnTForm;
use Acf\ClientBundle\Form\Address\NewTForm as AddressNewTForm;
use Acf\ClientBundle\Form\Phone\NewTForm as PhoneNewTForm;
use Acf\ClientBundle\Form\CompanyFrame\NewTForm as CompanyFrameNewTForm;
use Acf\ClientBundle\Form\Doc\NewTForm as DocNewTForm;
use Acf\ClientBundle\Form\Customer\NewTForm as CustomerNewTForm;
use Acf\ClientBundle\Form\Supplier\NewTForm as SupplierNewTForm;
use Acf\ClientBundle\Form\MBPurchase\NewTForm as MBPurchaseNewTForm;
use Acf\ClientBundle\Form\MBSale\NewTForm as MBSaleNewTForm;
use Acf\DataBundle\Entity\CompanyFrame;
use Acf\DataBundle\Entity\Customer;
use Acf\DataBundle\Entity\Supplier;
use Acf\DataBundle\Entity\Address;
use Acf\DataBundle\Entity\Phone;
use Acf\DataBundle\Entity\ConstantStr;
use Acf\DataBundle\Entity\MBSale;
use Acf\DataBundle\Entity\MBPurchase;
use Acf\DataBundle\Entity\Docgroupcomptable;
use Acf\DataBundle\Entity\Docgroup;
use Acf\DataBundle\Entity\Docgroupfiscal;
use Acf\DataBundle\Entity\Docgroupperso;
use Acf\DataBundle\Entity\Docgroupsyst;
use Acf\DataBundle\Entity\Docgroupbank;
use Acf\DataBundle\Entity\Docgroupaudit;
use Acf\DataBundle\Entity\Doc;
use Acf\DataBundle\Entity\CompanyUser;
use Acf\DataBundle\Entity\Trace;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class CompanyController extends BaseController
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
            $company = $em->getRepository('AcfDataBundle:Company')->find($uid);

            if (null == $company) {
                $this->flashMsgSession('warning', $this->translate('Company.edit.notfound'));
            } else {

                $sc = $this->getSecurityTokenStorage();
                $user = $sc->getToken()->getUser();

                $companyUser = $em->getRepository('AcfDataBundle:CompanyUser')->findOneBy(array(
                    'company' => $company,
                    'user' => $user
                ));
                if (null == $companyUser) {
                    return $this->redirect($this->generateUrl('_client_homepage'));
                }
                $this->gvars['companyUser'] = $companyUser;
                $this->gvars['menu_active'] = 'client' . $company->getId();

                $companyUpdateTypeForm = $this->createForm(CompanyUpdateTypeTForm::class, $company);
                $companyUpdateCorporateNameForm = $this->createForm(CompanyUpdateCorporateNameTForm::class, $company);
                $companyUpdateFiscForm = $this->createForm(CompanyUpdateFiscTForm::class, $company);
                $companyUpdateTribunalForm = $this->createForm(CompanyUpdateTribunalTForm::class, $company);
                $companyUpdatePhysicaltypeForm = $this->createForm(CompanyUpdatePhysicaltypeTForm::class, $company);
                $companyUpdateCnssForm = $this->createForm(CompanyUpdateCnssTForm::class, $company);
                $companyUpdateCnssBureauForm = $this->createForm(CompanyUpdateCnssBureauTForm::class, $company);
                $companyUpdateSectorsForm = $this->createForm(CompanyUpdateSectorsTForm::class, $company);
                $companyUpdatePhoneForm = $this->createForm(CompanyUpdatePhoneTForm::class, $company);
                $companyUpdateMobileForm = $this->createForm(CompanyUpdateMobileTForm::class, $company);
                $companyUpdateFaxForm = $this->createForm(CompanyUpdateFaxTForm::class, $company);
                $companyUpdateEmailForm = $this->createForm(CompanyUpdateEmailTForm::class, $company);
                $companyUpdateAdrForm = $this->createForm(CompanyUpdateAdrTForm::class, $company);
                $companyUpdateOtherInfosForm = $this->createForm(CompanyUpdateOtherInfosTForm::class, $company);
                $companyUpdateActionvnForm = $this->createForm(CompanyUpdateActionvnTForm::class, $company);

                $address = new Address();
                $address->setCompany($company);
                $addressNewForm = $this->createForm(AddressNewTForm::class, $address, array(
                    'company' => $company
                ));

                $phone = new Phone();
                $phone->setCompany($company);
                $phoneNewForm = $this->createForm(PhoneNewTForm::class, $phone, array(
                    'company' => $company
                ));

                $companyFrame = new CompanyFrame();
                $companyFrame->setCompany($company);
                $companyFrameNewForm = $this->createForm(CompanyFrameNewTForm::class, $companyFrame, array(
                    'company' => $company
                ));

                $doc = new Doc();
                $doc->setCompany($company);
                $docNewForm = $this->createForm(DocNewTForm::class, $doc, array(
                    'company' => $company
                ));

                $customer = new Customer();
                $customer->setCompany($company);
                $customerNewForm = $this->createForm(CustomerNewTForm::class, $customer, array(
                    'company' => $company
                ));

                $supplier = new Supplier();
                $supplier->setCompany($company);
                $supplierNewForm = $this->createForm(SupplierNewTForm::class, $supplier, array(
                    'company' => $company
                ));

                $mbsale = new MBSale();
                $mbsale->setCompany($company);
                $mbsaleNewForm = $this->createForm(MBSaleNewTForm::class, $mbsale, array(
                    'company' => $company
                ));

                $mbpurchase = new MBPurchase();
                $mbpurchase->setCompany($company);
                $mbpurchaseNewForm = $this->createForm(MBPurchaseNewTForm::class, $mbpurchase, array(
                    'company' => $company
                ));

                $this->gvars['company'] = $company;
                $this->gvars['address'] = $address;
                $this->gvars['phone'] = $phone;
                $this->gvars['companyFrame'] = $companyFrame;
                $this->gvars['doc'] = $doc;
                $this->gvars['customer'] = $customer;
                $this->gvars['supplier'] = $supplier;
                $this->gvars['docgroupcomptables'] = $em->getRepository('AcfDataBundle:Docgroupcomptable')->getRoots($company);
                $this->gvars['docgroups'] = $em->getRepository('AcfDataBundle:Docgroup')->getRoots($company);
                $this->gvars['docgroupfiscals'] = $em->getRepository('AcfDataBundle:Docgroupfiscal')->getRoots($company);
                $this->gvars['docgrouppersos'] = $em->getRepository('AcfDataBundle:Docgroupperso')->getRoots($company);
                $this->gvars['docgroupsysts'] = $em->getRepository('AcfDataBundle:Docgroupsyst')->getRoots($company);
                $this->gvars['docgroupbanks'] = $em->getRepository('AcfDataBundle:Docgroupbank')->getRoots($company);
                $this->gvars['docgroupaudits'] = $em->getRepository('AcfDataBundle:Docgroupaudit')->getRoots($company);

                $this->gvars['CompanyUpdateTypeForm'] = $companyUpdateTypeForm->createView();
                $this->gvars['CompanyUpdateCorporateNameForm'] = $companyUpdateCorporateNameForm->createView();
                $this->gvars['CompanyUpdateFiscForm'] = $companyUpdateFiscForm->createView();
                $this->gvars['CompanyUpdateTribunalForm'] = $companyUpdateTribunalForm->createView();
                $this->gvars['CompanyUpdatePhysicaltypeForm'] = $companyUpdatePhysicaltypeForm->createView();
                $this->gvars['CompanyUpdateCnssForm'] = $companyUpdateCnssForm->createView();
                $this->gvars['CompanyUpdateCnssBureauForm'] = $companyUpdateCnssBureauForm->createView();
                $this->gvars['CompanyUpdateSectorsForm'] = $companyUpdateSectorsForm->createView();
                $this->gvars['CompanyUpdatePhoneForm'] = $companyUpdatePhoneForm->createView();
                $this->gvars['CompanyUpdateMobileForm'] = $companyUpdateMobileForm->createView();
                $this->gvars['CompanyUpdateFaxForm'] = $companyUpdateFaxForm->createView();
                $this->gvars['CompanyUpdateEmailForm'] = $companyUpdateEmailForm->createView();
                $this->gvars['CompanyUpdateAdrForm'] = $companyUpdateAdrForm->createView();
                $this->gvars['CompanyUpdateOtherInfosForm'] = $companyUpdateOtherInfosForm->createView();
                $this->gvars['CompanyUpdateActionvnForm'] = $companyUpdateActionvnForm->createView();
                $this->gvars['AddressNewForm'] = $addressNewForm->createView();
                $this->gvars['PhoneNewForm'] = $phoneNewForm->createView();
                $this->gvars['CompanyFrameNewForm'] = $companyFrameNewForm->createView();
                $this->gvars['DocNewForm'] = $docNewForm->createView();
                $this->gvars['CustomerNewForm'] = $customerNewForm->createView();
                $this->gvars['SupplierNewForm'] = $supplierNewForm->createView();
                $this->gvars['MBSaleNewForm'] = $mbsaleNewForm->createView();
                $this->gvars['MBPurchaseNewForm'] = $mbpurchaseNewForm->createView();

                $mbsaleYears = $em->getRepository('AcfDataBundle:MBSale')->getAllYearByCompany($company);
                $mbpurchaseYears = $em->getRepository('AcfDataBundle:MBPurchase')->getAllYearByCompany($company);

                $this->gvars['mbsaleYears'] = $mbsaleYears;
                $this->gvars['mbpurchaseYears'] = $mbpurchaseYears;

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

                $banksConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
                    'name' => 'banksPrefix'
                ));
                if (null == $banksConstStr) {
                    $banksConstStr = new ConstantStr();
                    $banksConstStr->setName('banksPrefix');
                    $banksConstStr->setValue('532');
                    $em->persist($banksConstStr);
                    $em->flush();
                }
                $banksPrefix = $banksConstStr->getValue();
                $this->gvars['banksPrefix'] = $banksPrefix;

                $fundsConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
                    'name' => 'fundsPrefix'
                ));
                if (null == $fundsConstStr) {
                    $fundsConstStr = new ConstantStr();
                    $fundsConstStr->setName('fundsPrefix');
                    $fundsConstStr->setValue('540');
                    $em->persist($fundsConstStr);
                    $em->flush();
                }
                $fundsPrefix = $fundsConstStr->getValue();
                $this->gvars['fundsPrefix'] = $fundsPrefix;

                $withholdingsConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
                    'name' => 'withholdingsPrefix'
                ));
                if (null == $withholdingsConstStr) {
                    $withholdingsConstStr = new ConstantStr();
                    $withholdingsConstStr->setName('withholdingsPrefix');
                    $withholdingsConstStr->setValue('432');
                    $em->persist($withholdingsConstStr);
                    $em->flush();
                }
                $withholdingsPrefix = $withholdingsConstStr->getValue();
                $this->gvars['withholdingsPrefix'] = $withholdingsPrefix;

                $this->gvars['pagetitle'] = $this->translate('pagetitle.company.edit', array(
                    '%company%' => $company->getCorporateName()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.company.edit.txt', array(
                    '%company%' => $company->getCorporateName()
                ));

                return $this->renderResponse('AcfClientBundle:Company:edit.html.twig', $this->gvars);
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
            $company = $em->getRepository('AcfDataBundle:Company')->find($uid);

            if (null == $company) {
                $this->flashMsgSession('warning', $this->translate('Company.edit.notfound'));
            } else {

                $sc = $this->getSecurityTokenStorage();
                $user = $sc->getToken()->getUser();

                $companyUser = $em->getRepository('AcfDataBundle:CompanyUser')->findOneBy(array(
                    'company' => $company,
                    'user' => $user
                ));
                if (null == $companyUser) {
                    return $this->redirect($this->generateUrl('_client_homepage'));
                }
                $this->gvars['companyUser'] = $companyUser;
                $this->gvars['menu_active'] = 'client' . $company->getId();

                $companyUpdateTypeForm = $this->createForm(CompanyUpdateTypeTForm::class, $company);
                $companyUpdateCorporateNameForm = $this->createForm(CompanyUpdateCorporateNameTForm::class, $company);
                $companyUpdateFiscForm = $this->createForm(CompanyUpdateFiscTForm::class, $company);
                $companyUpdateTribunalForm = $this->createForm(CompanyUpdateTribunalTForm::class, $company);
                $companyUpdatePhysicaltypeForm = $this->createForm(CompanyUpdatePhysicaltypeTForm::class, $company);
                $companyUpdateCnssForm = $this->createForm(CompanyUpdateCnssTForm::class, $company);
                $companyUpdateCnssBureauForm = $this->createForm(CompanyUpdateCnssBureauTForm::class, $company);
                $companyUpdateSectorsForm = $this->createForm(CompanyUpdateSectorsTForm::class, $company);
                $companyUpdatePhoneForm = $this->createForm(CompanyUpdatePhoneTForm::class, $company);
                $companyUpdateMobileForm = $this->createForm(CompanyUpdateMobileTForm::class, $company);
                $companyUpdateFaxForm = $this->createForm(CompanyUpdateFaxTForm::class, $company);
                $companyUpdateEmailForm = $this->createForm(CompanyUpdateEmailTForm::class, $company);
                $companyUpdateAdrForm = $this->createForm(CompanyUpdateAdrTForm::class, $company);
                $companyUpdateOtherInfosForm = $this->createForm(CompanyUpdateOtherInfosTForm::class, $company);
                $companyUpdateActionvnForm = $this->createForm(CompanyUpdateActionvnTForm::class, $company);

                $address = new Address();
                $address->setCompany($company);
                $addressNewForm = $this->createForm(AddressNewTForm::class, $address, array(
                    'company' => $company
                ));

                $phone = new Phone();
                $phone->setCompany($company);
                $phoneNewForm = $this->createForm(PhoneNewTForm::class, $phone, array(
                    'company' => $company
                ));

                $companyFrame = new CompanyFrame();
                $companyFrame->setCompany($company);
                $companyFrameNewForm = $this->createForm(CompanyFrameNewTForm::class, $companyFrame, array(
                    'company' => $company
                ));

                $doc = new Doc();
                $doc->setCompany($company);
                $docNewForm = $this->createForm(DocNewTForm::class, $doc, array(
                    'company' => $company
                ));

                $customer = new Customer();
                $customer->setCompany($company);
                $customerNewForm = $this->createForm(CustomerNewTForm::class, $customer, array(
                    'company' => $company
                ));

                $supplier = new Supplier();
                $supplier->setCompany($company);
                $supplierNewForm = $this->createForm(SupplierNewTForm::class, $supplier, array(
                    'company' => $company
                ));

                $mbsale = new MBSale();
                $mbsale->setCompany($company);
                $mbsaleNewForm = $this->createForm(MBSaleNewTForm::class, $mbsale, array(
                    'company' => $company
                ));

                $mbpurchase = new MBPurchase();
                $mbpurchase->setCompany($company);
                $mbpurchaseNewForm = $this->createForm(MBPurchaseNewTForm::class, $mbpurchase, array(
                    'company' => $company
                ));

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
                $this->getSession()->remove('stabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                $cloneCompany = clone $company;

                if (isset($reqData['CompanyUpdateTypeForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyUpdateTypeForm->handleRequest($request);
                    if ($companyUpdateTypeForm->isValid()) {
                        $em->persist($company);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Company.edit.success', array(
                            '%company%' => $company->getCorporateName()
                        )));

                        $this->traceEntity($cloneCompany, $company);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Company.edit.failure', array(
                            '%company%' => $company->getCorporateName()
                        )));
                    }
                } elseif (isset($reqData['CompanyUpdateCorporateNameForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyUpdateCorporateNameForm->handleRequest($request);
                    if ($companyUpdateCorporateNameForm->isValid()) {
                        $em->persist($company);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Company.edit.success', array(
                            '%company%' => $company->getCorporateName()
                        )));

                        $this->traceEntity($cloneCompany, $company);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Company.edit.failure', array(
                            '%company%' => $company->getCorporateName()
                        )));
                    }
                } elseif (isset($reqData['CompanyUpdateFiscForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyUpdateFiscForm->handleRequest($request);
                    if ($companyUpdateFiscForm->isValid()) {
                        $em->persist($company);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Company.edit.success', array(
                            '%company%' => $company->getCorporateName()
                        )));

                        $this->traceEntity($cloneCompany, $company);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Company.edit.failure', array(
                            '%company%' => $company->getCorporateName()
                        )));
                    }
                } elseif (isset($reqData['CompanyUpdateTribunalForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyUpdateTribunalForm->handleRequest($request);
                    if ($companyUpdateTribunalForm->isValid()) {
                        $em->persist($company);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Company.edit.success', array(
                            '%company%' => $company->getCorporateName()
                        )));

                        $this->traceEntity($cloneCompany, $company);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Company.edit.failure', array(
                            '%company%' => $company->getCorporateName()
                        )));
                    }
                } elseif (isset($reqData['CompanyUpdatePhysicaltypeForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyUpdatePhysicaltypeForm->handleRequest($request);
                    if ($companyUpdatePhysicaltypeForm->isValid()) {
                        $em->persist($company);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Company.edit.success', array(
                            '%company%' => $company->getCorporateName()
                        )));

                        $this->traceEntity($cloneCompany, $company);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Company.edit.failure', array(
                            '%company%' => $company->getCorporateName()
                        )));
                    }
                } elseif (isset($reqData['CompanyUpdateCnssForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyUpdateCnssForm->handleRequest($request);
                    if ($companyUpdateCnssForm->isValid()) {
                        $em->persist($company);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Company.edit.success', array(
                            '%company%' => $company->getCorporateName()
                        )));

                        $this->traceEntity($cloneCompany, $company);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Company.edit.failure', array(
                            '%company%' => $company->getCorporateName()
                        )));
                    }
                } elseif (isset($reqData['CompanyUpdateCnssBureauForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyUpdateCnssBureauForm->handleRequest($request);
                    if ($companyUpdateCnssBureauForm->isValid()) {
                        $em->persist($company);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Company.edit.success', array(
                            '%company%' => $company->getCorporateName()
                        )));

                        $this->traceEntity($cloneCompany, $company);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Company.edit.failure', array(
                            '%company%' => $company->getCorporateName()
                        )));
                    }
                } elseif (isset($reqData['CompanyUpdateSectorsForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyUpdateSectorsForm->handleRequest($request);
                    if ($companyUpdateSectorsForm->isValid()) {
                        $em->persist($company);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Company.edit.success', array(
                            '%company%' => $company->getCorporateName()
                        )));

                        $this->traceEntity($cloneCompany, $company);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Company.edit.failure', array(
                            '%company%' => $company->getCorporateName()
                        )));
                    }
                } elseif (isset($reqData['CompanyUpdatePhoneForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyUpdatePhoneForm->handleRequest($request);
                    if ($companyUpdatePhoneForm->isValid()) {
                        $em->persist($company);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Company.edit.success', array(
                            '%company%' => $company->getCorporateName()
                        )));

                        $this->traceEntity($cloneCompany, $company);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Company.edit.failure', array(
                            '%company%' => $company->getCorporateName()
                        )));
                    }
                } elseif (isset($reqData['CompanyUpdateMobileForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyUpdateMobileForm->handleRequest($request);
                    if ($companyUpdateMobileForm->isValid()) {
                        $em->persist($company);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Company.edit.success', array(
                            '%company%' => $company->getCorporateName()
                        )));

                        $this->traceEntity($cloneCompany, $company);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Company.edit.failure', array(
                            '%company%' => $company->getCorporateName()
                        )));
                    }
                } elseif (isset($reqData['CompanyUpdateFaxForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyUpdateFaxForm->handleRequest($request);
                    if ($companyUpdateFaxForm->isValid()) {
                        $em->persist($company);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Company.edit.success', array(
                            '%company%' => $company->getCorporateName()
                        )));

                        $this->traceEntity($cloneCompany, $company);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Company.edit.failure', array(
                            '%company%' => $company->getCorporateName()
                        )));
                    }
                } elseif (isset($reqData['CompanyUpdateEmailForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyUpdateEmailForm->handleRequest($request);
                    if ($companyUpdateEmailForm->isValid()) {
                        $em->persist($company);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Company.edit.success', array(
                            '%company%' => $company->getCorporateName()
                        )));

                        $this->traceEntity($cloneCompany, $company);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Company.edit.failure', array(
                            '%company%' => $company->getCorporateName()
                        )));
                    }
                } elseif (isset($reqData['CompanyUpdateAdrForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyUpdateAdrForm->handleRequest($request);
                    if ($companyUpdateAdrForm->isValid()) {
                        $em->persist($company);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Company.edit.success', array(
                            '%company%' => $company->getCorporateName()
                        )));

                        $this->traceEntity($cloneCompany, $company);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Company.edit.failure', array(
                            '%company%' => $company->getCorporateName()
                        )));
                    }
                } elseif (isset($reqData['CompanyUpdateOtherInfosForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyUpdateOtherInfosForm->handleRequest($request);
                    if ($companyUpdateOtherInfosForm->isValid()) {
                        $em->persist($company);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Company.edit.success', array(
                            '%company%' => $company->getCorporateName()
                        )));

                        $this->traceEntity($cloneCompany, $company);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Company.edit.failure', array(
                            '%company%' => $company->getCorporateName()
                        )));
                    }
                } elseif (isset($reqData['CompanyUpdateActionvnForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyUpdateActionvnForm->handleRequest($request);
                    if ($companyUpdateActionvnForm->isValid()) {
                        $em->persist($company);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Company.edit.success', array(
                            '%company%' => $company->getCorporateName()
                        )));

                        $this->traceEntity($cloneCompany, $company);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Company.edit.failure', array(
                            '%company%' => $company->getCorporateName()
                        )));
                    }
                } elseif (isset($reqData['AddressNewForm'])) {
                    $this->gvars['tabActive'] = 3;
                    $this->getSession()->set('tabActive', 3);
                    $addressNewForm->handleRequest($request);
                    if ($addressNewForm->isValid()) {
                        $em->persist($address);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Address.add.success', array(
                            '%address%' => $address->getLabel()
                        )));

                        $this->gvars['stabActive'] = 2;
                        $this->getSession()->set('stabActive', 2);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Address.add.failure'));
                    }
                } elseif (isset($reqData['PhoneNewForm'])) {
                    $this->gvars['tabActive'] = 4;
                    $this->getSession()->set('tabActive', 4);
                    $phoneNewForm->handleRequest($request);
                    if ($phoneNewForm->isValid()) {
                        $em->persist($phone);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Phone.add.success', array(
                            '%phone%' => $phone->getLabel()
                        )));

                        $this->gvars['stabActive'] = 2;
                        $this->getSession()->set('stabActive', 2);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Phone.add.failure'));
                    }
                } elseif (isset($reqData['CompanyFrameNewForm'])) {
                    $this->gvars['tabActive'] = 5;
                    $this->getSession()->set('tabActive', 5);
                    $companyFrameNewForm->handleRequest($request);
                    if ($companyFrameNewForm->isValid()) {
                        $em->persist($companyFrame);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('CompanyFrame.add.success', array(
                            '%companyFrame%' => $companyFrame->getFullName()
                        )));

                        $this->gvars['stabActive'] = 2;
                        $this->getSession()->set('stabActive', 2);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('CompanyFrame.add.failure'));
                    }
                } elseif (isset($reqData['DocNewForm'])) {
                    $this->gvars['tabActive'] = 7;
                    $this->getSession()->set('tabActive', 7);
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
                            $doc->setCompany($company);
                            $doc->setFileName($fileName);
                            $doc->setOriginalName($originalName);
                            $doc->setSize($size);
                            $doc->setMimeType($mimeType);
                            $doc->setMd5($md5);
                            $doc->setDescription($docNewForm['description']->getData());
                            $em->persist($doc);

                            $docs[] = $doc;

                            $docNames .= $doc->getOriginalName() . ' ';
                        }
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('Doc.add.success', array(
                            '%doc%' => $docNames
                        )));
                        $this->newDocNotifyAdmin($company, $docs);

                        $this->gvars['stabActive'] = 2;
                        $this->getSession()->set('stabActive', 2);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Doc.add.failure'));
                    }
                } elseif (isset($reqData['CustomerNewForm'])) {
                    $this->gvars['tabActive'] = 22;
                    $this->getSession()->set('tabActive', 22);
                    $this->gvars['stabActive'] = 1;
                    $this->getSession()->set('stabActive', 1);
                    $customerNewForm->handleRequest($request);
                    if ($customerNewForm->isValid()) {
                        $em->persist($customer);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Customer.add.success', array(
                            '%customer%' => $customer->getLabel()
                        )));

                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Customer.add.failure'));
                    }
                } elseif (isset($reqData['SupplierNewForm'])) {
                    $this->gvars['tabActive'] = 23;
                    $this->getSession()->set('tabActive', 23);
                    $this->gvars['stabActive'] = 1;
                    $this->getSession()->set('stabActive', 1);
                    $supplierNewForm->handleRequest($request);
                    if ($supplierNewForm->isValid()) {
                        $em->persist($supplier);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Supplier.add.success', array(
                            '%supplier%' => $supplier->getLabel()
                        )));

                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Supplier.add.failure'));
                    }
                } elseif (isset($reqData['MBSaleNewForm'])) {
                    $this->gvars['tabActive'] = 27;
                    $this->getSession()->set('tabActive', 27);
                    $mbsaleNewForm->handleRequest($request);
                    if ($mbsaleNewForm->isValid()) {
                        $mbsale->generateRef();
                        $em->persist($mbsale);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('MBSale.add.success', array(
                            '%mbsale%' => $mbsale->getRef()
                        )));

                        $this->gvars['stabActive'] = 2;
                        $this->getSession()->set('stabActive', 2);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('MBSale.add.failure'));
                    }
                } elseif (isset($reqData['MBPurchaseNewForm'])) {
                    $this->gvars['tabActive'] = 28;
                    $this->getSession()->set('tabActive', 28);
                    $mbpurchaseNewForm->handleRequest($request);
                    if ($mbpurchaseNewForm->isValid()) {
                        $mbpurchase->generateRef();
                        $em->persist($mbpurchase);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('MBPurchase.add.success', array(
                            '%mbpurchase%' => $mbpurchase->getRef()
                        )));

                        $this->gvars['stabActive'] = 2;
                        $this->getSession()->set('stabActive', 2);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('MBPurchase.add.failure'));
                    }
                }

                $this->gvars['company'] = $company;
                $this->gvars['address'] = $address;
                $this->gvars['phone'] = $phone;
                $this->gvars['companyFrame'] = $companyFrame;
                $this->gvars['doc'] = $doc;
                $this->gvars['customer'] = $customer;
                $this->gvars['supplier'] = $supplier;
                $this->gvars['docgroupcomptables'] = $em->getRepository('AcfDataBundle:Docgroupcomptable')->getRoots($company);
                $this->gvars['docgroups'] = $em->getRepository('AcfDataBundle:Docgroup')->getRoots($company);
                $this->gvars['docgroupfiscals'] = $em->getRepository('AcfDataBundle:Docgroupfiscal')->getRoots($company);
                $this->gvars['docgrouppersos'] = $em->getRepository('AcfDataBundle:Docgroupperso')->getRoots($company);
                $this->gvars['docgroupsysts'] = $em->getRepository('AcfDataBundle:Docgroupsyst')->getRoots($company);
                $this->gvars['docgroupbanks'] = $em->getRepository('AcfDataBundle:Docgroupbank')->getRoots($company);
                $this->gvars['docgroupaudits'] = $em->getRepository('AcfDataBundle:Docgroupaudit')->getRoots($company);

                $this->gvars['CompanyUpdateTypeForm'] = $companyUpdateTypeForm->createView();
                $this->gvars['CompanyUpdateCorporateNameForm'] = $companyUpdateCorporateNameForm->createView();
                $this->gvars['CompanyUpdateFiscForm'] = $companyUpdateFiscForm->createView();
                $this->gvars['CompanyUpdateTribunalForm'] = $companyUpdateTribunalForm->createView();
                $this->gvars['CompanyUpdatePhysicaltypeForm'] = $companyUpdatePhysicaltypeForm->createView();
                $this->gvars['CompanyUpdateCnssForm'] = $companyUpdateCnssForm->createView();
                $this->gvars['CompanyUpdateCnssBureauForm'] = $companyUpdateCnssBureauForm->createView();
                $this->gvars['CompanyUpdateSectorsForm'] = $companyUpdateSectorsForm->createView();
                $this->gvars['CompanyUpdatePhoneForm'] = $companyUpdatePhoneForm->createView();
                $this->gvars['CompanyUpdateMobileForm'] = $companyUpdateMobileForm->createView();
                $this->gvars['CompanyUpdateFaxForm'] = $companyUpdateFaxForm->createView();
                $this->gvars['CompanyUpdateEmailForm'] = $companyUpdateEmailForm->createView();
                $this->gvars['CompanyUpdateAdrForm'] = $companyUpdateAdrForm->createView();
                $this->gvars['CompanyUpdateOtherInfosForm'] = $companyUpdateOtherInfosForm->createView();
                $this->gvars['CompanyUpdateActionvnForm'] = $companyUpdateActionvnForm->createView();
                $this->gvars['AddressNewForm'] = $addressNewForm->createView();
                $this->gvars['PhoneNewForm'] = $phoneNewForm->createView();
                $this->gvars['CompanyFrameNewForm'] = $companyFrameNewForm->createView();
                $this->gvars['DocNewForm'] = $docNewForm->createView();
                $this->gvars['CustomerNewForm'] = $customerNewForm->createView();
                $this->gvars['SupplierNewForm'] = $supplierNewForm->createView();
                $this->gvars['MBSaleNewForm'] = $mbsaleNewForm->createView();
                $this->gvars['MBPurchaseNewForm'] = $mbpurchaseNewForm->createView();

                $mbsaleYears = $em->getRepository('AcfDataBundle:MBSale')->getAllYearByCompany($company);
                $mbpurchaseYears = $em->getRepository('AcfDataBundle:MBPurchase')->getAllYearByCompany($company);

                $this->gvars['mbsaleYears'] = $mbsaleYears;
                $this->gvars['mbpurchaseYears'] = $mbpurchaseYears;

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

                $banksConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
                    'name' => 'banksPrefix'
                ));
                if (null == $banksConstStr) {
                    $banksConstStr = new ConstantStr();
                    $banksConstStr->setName('banksPrefix');
                    $banksConstStr->setValue('532');
                    $em->persist($banksConstStr);
                    $em->flush();
                }
                $banksPrefix = $banksConstStr->getValue();
                $this->gvars['banksPrefix'] = $banksPrefix;

                $fundsConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
                    'name' => 'fundsPrefix'
                ));
                if (null == $fundsConstStr) {
                    $fundsConstStr = new ConstantStr();
                    $fundsConstStr->setName('fundsPrefix');
                    $fundsConstStr->setValue('540');
                    $em->persist($fundsConstStr);
                    $em->flush();
                }
                $fundsPrefix = $fundsConstStr->getValue();
                $this->gvars['fundsPrefix'] = $fundsPrefix;

                $withholdingsConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
                    'name' => 'withholdingsPrefix'
                ));
                if (null == $withholdingsConstStr) {
                    $withholdingsConstStr = new ConstantStr();
                    $withholdingsConstStr->setName('withholdingsPrefix');
                    $withholdingsConstStr->setValue('432');
                    $em->persist($withholdingsConstStr);
                    $em->flush();
                }
                $withholdingsPrefix = $withholdingsConstStr->getValue();
                $this->gvars['withholdingsPrefix'] = $withholdingsPrefix;

                $this->gvars['pagetitle'] = $this->translate('pagetitle.company.edit', array(
                    '%company%' => $company->getCorporateName()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.company.edit.txt', array(
                    '%company%' => $company->getCorporateName()
                ));

                return $this->renderResponse('AcfClientBundle:Company:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }

    protected function newDocNotifyAdmin(Company $company, $docs)
    {
        $from = $this->getParameter('mail_from');
        $fromName = $this->getParameter('mail_from_name');
        $subject = $this->translate('_mail.newdocs.subject', array(), 'messages');

        $user = $this->getSecurityTokenStorage()
            ->getToken()
            ->getUser();

        $admins = $company->getAdmins();
        if (\count($admins) != 0) {
            $mvars = array();
            $mvars['user'] = $user;
            $mvars['company'] = $company;
            $mvars['docs'] = $docs;
            $message = \Swift_Message::newInstance();
            $message->setFrom($from, $fromName);
            foreach ($admins as $admin) {
                $message->addTo($admin->getEmail(), $admin->getFullname());
            }
            $message->setSubject($subject);
            $message->setBody($this->renderView('AcfClientBundle:Mail:Companynewdoc.html.twig', $mvars), 'text/html');
            $this->sendmail($message);
        }
    }

    protected function traceEntity(Company $cloneCompany, Company $company)
    {
        $curUser = $this->getSecurityTokenStorage()
            ->getToken()
            ->getUser();
        $trace = new Trace();
        $trace->setActionId($company->getId());
        $trace->setActionType(Trace::AT_UPDATE);
        $trace->setUserId($curUser->getId());
        $trace->setCompanyId($company->getId());
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

        $trace->setActionEntity(Trace::AE_COMPANY);

        $msg = '';

        if ($cloneCompany->getRef() != $company->getRef()) {
            $msg .= '<tr><td>' . $this->translate('Company.ref.label') . '</td><td>';
            if ($cloneCompany->getRef() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getRef();
            }
            $msg .= '</td><td>';
            if ($company->getRef() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getRef();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getCorporateName() != $company->getCorporateName()) {
            $msg .= '<tr><td>' . $this->translate('Company.corporateName.label') . '</td><td>';
            if ($cloneCompany->getCorporateName() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getCorporateName();
            }
            $msg .= '</td><td>';
            if ($company->getCorporateName() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getCorporateName();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getType() != $company->getType()) {
            $msg .= '<tr><td>' . $this->translate('Company.type.label') . '</td><td>';
            if ($cloneCompany->getType() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getType()->getLabel();
            }
            $msg .= '</td><td>';
            if ($company->getType() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getType()->getLabel();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getTribunal() != $company->getTribunal()) {
            $msg .= '<tr><td>' . $this->translate('Company.tribunal.label') . '</td><td>';
            if ($cloneCompany->getTribunal() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getTribunal();
            }
            $msg .= '</td><td>';
            if ($company->getTribunal() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getTribunal();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getFisc() != $company->getFisc()) {
            $msg .= '<tr><td>' . $this->translate('Company.fisc.label') . '</td><td>';
            if ($cloneCompany->getFisc() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getFisc();
            }
            $msg .= '</td><td>';
            if ($company->getFisc() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getFisc();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getCommercialRegister() != $company->getCommercialRegister()) {
            $msg .= '<tr><td>' . $this->translate('Company.commercialRegister.label') . '</td><td>';
            if ($cloneCompany->getCommercialRegister() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getCommercialRegister();
            }
            $msg .= '</td><td>';
            if ($company->getCommercialRegister() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getCommercialRegister();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getCommercialRegisterBureau() != $company->getCommercialRegisterBureau()) {
            $msg .= '<tr><td>' . $this->translate('Company.commercialRegisterBureau.label') . '</td><td>';
            if ($cloneCompany->getCommercialRegisterBureau() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getCommercialRegisterBureau();
            }
            $msg .= '</td><td>';
            if ($company->getCommercialRegisterBureau() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getCommercialRegisterBureau();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getCnss() != $company->getCnss()) {
            $msg .= '<tr><td>' . $this->translate('Company.cnss.label') . '</td><td>';
            if ($cloneCompany->getCnss() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getCnss();
            }
            $msg .= '</td><td>';
            if ($company->getCnss() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getCnss();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getCnssBureau() != $company->getCnssBureau()) {
            $msg .= '<tr><td>' . $this->translate('Company.cnssBureau.label') . '</td><td>';
            if ($cloneCompany->getCnssBureau() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getCnssBureau();
            }
            $msg .= '</td><td>';
            if ($company->getCnssBureau() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getCnssBureau();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getPhysicalType() != $company->getPhysicalType()) {
            $msg .= '<tr><td>' . $this->translate('Company.physicalType.label') . '</td><td>';
            if ($cloneCompany->getPhysicalType() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('Company.physicalType.' . $cloneCompany->getPhysicalType());
            }
            $msg .= '</td><td>';
            if ($company->getPhysicalType() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('Company.physicalType.' . $company->getPhysicalType());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getCin() != $company->getCin()) {
            $msg .= '<tr><td>' . $this->translate('Company.cin.label') . '</td><td>';
            if ($cloneCompany->getCin() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getCin();
            }
            $msg .= '</td><td>';
            if ($company->getCin() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getCin();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getPassport() != $company->getPassport()) {
            $msg .= '<tr><td>' . $this->translate('Company.passport.label') . '</td><td>';
            if ($cloneCompany->getPassport() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getPassport();
            }
            $msg .= '</td><td>';
            if ($company->getPassport() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getPassport();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getCustomsCode() != $company->getCustomsCode()) {
            $msg .= '<tr><td>' . $this->translate('Company.customsCode.label') . '</td><td>';
            if ($cloneCompany->getCustomsCode() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getCustomsCode();
            }
            $msg .= '</td><td>';
            if ($company->getCustomsCode() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getCustomsCode();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getActionvn() != $company->getActionvn()) {
            $msg .= '<tr><td>' . $this->translate('Company.actionvn.label') . '</td><td>';
            if ($cloneCompany->getActionvn() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getActionvn();
            }
            $msg .= '</td><td>';
            if ($company->getActionvn() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getActionvn();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getStreetNum() != $company->getStreetNum()) {
            $msg .= '<tr><td>' . $this->translate('Company.streetNum.label') . '</td><td>';
            if ($cloneCompany->getStreetNum() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getStreetNum();
            }
            $msg .= '</td><td>';
            if ($company->getStreetNum() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getStreetNum();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getAddress() != $company->getAddress()) {
            $msg .= '<tr><td>' . $this->translate('Company.address.label') . '</td><td>';
            if ($cloneCompany->getAddress() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getAddress();
            }
            $msg .= '</td><td>';
            if ($company->getAddress() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getAddress();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getAddress2() != $company->getAddress2()) {
            $msg .= '<tr><td>' . $this->translate('Company.address2.label') . '</td><td>';
            if ($cloneCompany->getAddress2() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getAddress2();
            }
            $msg .= '</td><td>';
            if ($company->getAddress2() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getAddress2();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getTown() != $company->getTown()) {
            $msg .= '<tr><td>' . $this->translate('Company.town.label') . '</td><td>';
            if ($cloneCompany->getTown() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getTown();
            }
            $msg .= '</td><td>';
            if ($company->getTown() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getTown();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getZipCode() != $company->getZipCode()) {
            $msg .= '<tr><td>' . $this->translate('Company.zipCode.label') . '</td><td>';
            if ($cloneCompany->getZipCode() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getZipCode();
            }
            $msg .= '</td><td>';
            if ($company->getZipCode() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getZipCode();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getCountry() != $company->getCountry()) {
            $msg .= '<tr><td>' . $this->translate('Company.country.label') . '</td><td>';
            if ($cloneCompany->getCountry() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getCountry();
            }
            $msg .= '</td><td>';
            if ($company->getCountry() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getCountry();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getPhone() != $company->getPhone()) {
            $msg .= '<tr><td>' . $this->translate('Company.phone.label') . '</td><td>';
            if ($cloneCompany->getPhone() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getPhone();
            }
            $msg .= '</td><td>';
            if ($company->getPhone() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getPhone();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getMobile() != $company->getMobile()) {
            $msg .= '<tr><td>' . $this->translate('Company.mobile.label') . '</td><td>';
            if ($cloneCompany->getMobile() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getMobile();
            }
            $msg .= '</td><td>';
            if ($company->getMobile() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getMobile();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getFax() != $company->getFax()) {
            $msg .= '<tr><td>' . $this->translate('Company.fax.label') . '</td><td>';
            if ($cloneCompany->getFax() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getFax();
            }
            $msg .= '</td><td>';
            if ($company->getFax() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getFax();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getEmail() != $company->getEmail()) {
            $msg .= '<tr><td>' . $this->translate('Company.email.label') . '</td><td>';
            if ($cloneCompany->getEmail() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getEmail();
            }
            $msg .= '</td><td>';
            if ($company->getEmail() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getEmail();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getOtherInfos() != $company->getOtherInfos()) {
            $msg .= '<tr><td>' . $this->translate('Company.otherInfos.label') . '</td><td>';
            if ($cloneCompany->getOtherInfos() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getOtherInfos();
            }
            $msg .= '</td><td>';
            if ($company->getOtherInfos() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getOtherInfos();
            }
            $msg .= '</td></tr>';
        }

        if (\count(\array_diff($company->getSectors()->toArray(), $cloneCompany->getSectors()->toArray())) != 0 || \count(\array_diff($cloneCompany->getSectors()->toArray(), $company->getSectors()->toArray())) != 0) {
            $msg .= '<tr><td>' . $this->translate('Company.sectors.label') . '</td><td>';
            if (\count($cloneCompany->getSectors()) == 0) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= '<ul>';
                foreach ($cloneCompany->getSectors() as $sector) {
                    $msg .= '<li>' . $sector->getLabel() . '</li>';
                }
                $msg .= '<ul>';
            }
            $msg .= '</td><td>';
            if (\count($company->getSectors()) == 0) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= '<ul>';
                foreach ($company->getSectors() as $sector) {
                    $msg .= '<li>' . $sector->getLabel() . '</li>';
                }
                $msg .= '<ul>';
            }
            $msg .= '</td></tr>';
        }

        if ($msg != '') {

            $msg = $tableBegin . $msg . $tableEnd;

            $trace->setMsg($this->translate('Company.traceEdit', array(
                '%company%' => $company->getCorporateName()
            )) . $msg);
            $trace->setDtCrea(new \DateTime('now'));
            $em = $this->getEntityManager();
            $em->persist($trace);
            $em->flush();
        }
    }
}
