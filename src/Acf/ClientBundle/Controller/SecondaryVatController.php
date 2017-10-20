<?php
namespace Acf\ClientBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\SecondaryVat;
use Acf\ClientBundle\Form\SecondaryVat\UpdateVatTForm as SecondaryVatUpdateVatTForm;
use Acf\ClientBundle\Form\SecondaryVat\UpdateBalanceTtcTForm as SecondaryVatUpdateBalanceTtcTForm;
use Acf\ClientBundle\Form\SecondaryVat\UpdateVatInfoTForm as SecondaryVatUpdateVatInfoTForm;
use Acf\ClientBundle\Form\SecondaryVat\UpdateBalanceNetTForm as SecondaryVatUpdateBalanceNetTForm;
use Acf\DataBundle\Entity\ConstantStr;
use Acf\DataBundle\Entity\CompanyUser;
use Acf\DataBundle\Entity\Trace;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class SecondaryVatController extends BaseController
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($uid)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_client_homepage');
        }
        $em = $this->getEntityManager();
        try {
            $secondaryVat = $em->getRepository('AcfDataBundle:SecondaryVat')->find($uid);

            if (null == $secondaryVat) {
                $this->flashMsgSession('warning', $this->translate('SecondaryVat.delete.notfound'));
            } else {

                $sc = $this->getSecurityTokenStorage();
                $user = $sc->getToken()->getUser();

                $company = $secondaryVat->getSale()
                    ->getMonthlyBalance()
                    ->getCompany();

                $companyUser = $em->getRepository('AcfDataBundle:CompanyUser')->findOneBy(array(
                    'company' => $company,
                    'user' => $user
                ));
                if (null == $companyUser || $companyUser->getDeleteSales() == CompanyUser::CANT) {
                    $this->flashMsgSession('error', $this->translate('CompanyUser.accessForbidden'));

                    return $this->redirect($this->generateUrl('_client_homepage'));
                }
                $this->gvars['companyUser'] = $companyUser;
                $this->gvars['menu_active'] = 'client' . $company->getId();

                $em->remove($secondaryVat);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('SecondaryVat.delete.success', array(
                    '%sale%' => $secondaryVat->getSale()
                        ->getNumber(),
                    '%secondaryVat%' => $secondaryVat->getVatInfo()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('SecondaryVat.delete.failure'));
        }

        return $this->redirect($urlFrom);
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
            $secondaryVat = $em->getRepository('AcfDataBundle:SecondaryVat')->find($uid);

            if (null == $secondaryVat) {
                $this->flashMsgSession('warning', $this->translate('SecondaryVat.edit.notfound'));
            } else {

                $sc = $this->getSecurityTokenStorage();
                $user = $sc->getToken()->getUser();

                $company = $secondaryVat->getSale()
                    ->getMonthlyBalance()
                    ->getCompany();

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

                $secondaryVatUpdateVatForm = $this->createForm(SecondaryVatUpdateVatTForm::class, $secondaryVat);
                $secondaryVatUpdateBalanceTtcForm = $this->createForm(SecondaryVatUpdateBalanceTtcTForm::class, $secondaryVat);
                $secondaryVatUpdateVatInfoForm = $this->createForm(SecondaryVatUpdateVatInfoTForm::class, $secondaryVat);
                $secondaryVatUpdateBalanceNetForm = $this->createForm(SecondaryVatUpdateBalanceNetTForm::class, $secondaryVat);

                $this->gvars['secondaryVat'] = $secondaryVat;
                $this->gvars['SecondaryVatUpdateVatForm'] = $secondaryVatUpdateVatForm->createView();
                $this->gvars['SecondaryVatUpdateBalanceTtcForm'] = $secondaryVatUpdateBalanceTtcForm->createView();
                $this->gvars['SecondaryVatUpdateVatInfoForm'] = $secondaryVatUpdateVatInfoForm->createView();
                $this->gvars['SecondaryVatUpdateBalanceNetForm'] = $secondaryVatUpdateBalanceNetForm->createView();

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

                $this->gvars['pagetitle'] = $this->translate('pagetitle.secondaryVat.edit', array(
                    '%sale%' => $secondaryVat->getSale()
                        ->getNumber(),
                    '%secondaryVat%' => $secondaryVat->getVatInfo()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.secondaryVat.edit.txt', array(
                    '%sale%' => $secondaryVat->getSale()
                        ->getNumber(),
                    '%secondaryVat%' => $secondaryVat->getVatInfo()
                ));

                return $this->renderResponse('AcfClientBundle:SecondaryVat:edit.html.twig', $this->gvars);
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
            $secondaryVat = $em->getRepository('AcfDataBundle:SecondaryVat')->find($uid);

            if (null == $secondaryVat) {
                $this->flashMsgSession('warning', $this->translate('SecondaryVat.edit.notfound'));
            } else {

                $sc = $this->getSecurityTokenStorage();
                $user = $sc->getToken()->getUser();

                $company = $secondaryVat->getSale()
                    ->getMonthlyBalance()
                    ->getCompany();

                $companyUser = $em->getRepository('AcfDataBundle:CompanyUser')->findOneBy(array(
                    'company' => $company,
                    'user' => $user
                ));
                if (null == $companyUser || $companyUser->getEditSales() == CompanyUser::CANT) {
                    $this->flashMsgSession('error', $this->translate('CompanyUser.accessForbidden'));

                    return $this->redirect($this->generateUrl('_client_homepage'));
                }
                $this->gvars['companyUser'] = $companyUser;
                $this->gvars['menu_active'] = 'client' . $company->getId();

                $secondaryVatUpdateVatForm = $this->createForm(SecondaryVatUpdateVatTForm::class, $secondaryVat);
                $secondaryVatUpdateBalanceTtcForm = $this->createForm(SecondaryVatUpdateBalanceTtcTForm(), $secondaryVat);
                $secondaryVatUpdateVatInfoForm = $this->createForm(SecondaryVatUpdateVatInfoTForm::class, $secondaryVat);
                $secondaryVatUpdateBalanceNetForm = $this->createForm(SecondaryVatUpdateBalanceNetTForm::class, $secondaryVat);

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
                $this->getSession()->remove('stabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                $cloneSecondaryVat = clone $secondaryVat;

                if (isset($reqData['SecondaryVatUpdateVatInfoForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $secondaryVatUpdateVatInfoForm->handleRequest($request);
                    if ($secondaryVatUpdateVatInfoForm->isValid()) {
                        $em->persist($secondaryVat);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('SecondaryVat.edit.success', array(
                            '%sale%' => $secondaryVat->getSale()
                                ->getNumber(),
                            '%secondaryVat%' => $secondaryVat->getVatInfo()
                        )));

                        $this->traceEntity($cloneSecondaryVat, $secondaryVat);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($secondaryVat);

                        $this->flashMsgSession('error', $this->translate('SecondaryVat.edit.failure', array(
                            '%sale%' => $secondaryVat->getSale()
                                ->getNumber(),
                            '%secondaryVat%' => $secondaryVat->getVatInfo()
                        )));
                    }
                } elseif (isset($reqData['SecondaryVatUpdateVatForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $secondaryVatUpdateVatForm->handleRequest($request);
                    if ($secondaryVatUpdateVatForm->isValid()) {
                        $em->persist($secondaryVat);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('SecondaryVat.edit.success', array(
                            '%sale%' => $secondaryVat->getSale()
                                ->getNumber(),
                            '%secondaryVat%' => $secondaryVat->getVatInfo()
                        )));

                        $this->traceEntity($cloneSecondaryVat, $secondaryVat);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($secondaryVat);

                        $this->flashMsgSession('error', $this->translate('SecondaryVat.edit.failure', array(
                            '%sale%' => $secondaryVat->getSale()
                                ->getNumber(),
                            '%secondaryVat%' => $secondaryVat->getVatInfo()
                        )));
                    }
                } elseif (isset($reqData['SecondaryVatUpdateBalanceTtcForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $secondaryVatUpdateBalanceTtcForm->handleRequest($request);
                    if ($secondaryVatUpdateBalanceTtcForm->isValid()) {
                        $em->persist($secondaryVat);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('SecondaryVat.edit.success', array(
                            '%sale%' => $secondaryVat->getSale()
                                ->getNumber(),
                            '%secondaryVat%' => $secondaryVat->getVatInfo()
                        )));

                        $this->traceEntity($cloneSecondaryVat, $secondaryVat);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($secondaryVat);

                        $this->flashMsgSession('error', $this->translate('SecondaryVat.edit.failure', array(
                            '%sale%' => $secondaryVat->getSale()
                                ->getNumber(),
                            '%secondaryVat%' => $secondaryVat->getVatInfo()
                        )));
                    }
                } elseif (isset($reqData['SecondaryVatUpdateBalanceNetForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $secondaryVatUpdateBalanceNetForm->handleRequest($request);
                    if ($secondaryVatUpdateBalanceNetForm->isValid()) {
                        $em->persist($secondaryVat);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('SecondaryVat.edit.success', array(
                            '%sale%' => $secondaryVat->getSale()
                                ->getNumber(),
                            '%secondaryVat%' => $secondaryVat->getVatInfo()
                        )));

                        $this->traceEntity($cloneSecondaryVat, $secondaryVat);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($secondaryVat);

                        $this->flashMsgSession('error', $this->translate('SecondaryVat.edit.failure', array(
                            '%sale%' => $secondaryVat->getSale()
                                ->getNumber(),
                            '%secondaryVat%' => $secondaryVat->getVatInfo()
                        )));
                    }
                }

                $this->gvars['secondaryVat'] = $secondaryVat;
                $this->gvars['SecondaryVatUpdateVatForm'] = $secondaryVatUpdateVatForm->createView();
                $this->gvars['SecondaryVatUpdateBalanceTtcForm'] = $secondaryVatUpdateBalanceTtcForm->createView();
                $this->gvars['SecondaryVatUpdateVatInfoForm'] = $secondaryVatUpdateVatInfoForm->createView();
                $this->gvars['SecondaryVatUpdateBalanceNetForm'] = $secondaryVatUpdateBalanceNetForm->createView();

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

                $this->gvars['pagetitle'] = $this->translate('pagetitle.secondaryVat.edit', array(
                    '%sale%' => $secondaryVat->getSale()
                        ->getNumber(),
                    '%secondaryVat%' => $secondaryVat->getVatInfo()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.secondaryVat.edit.txt', array(
                    '%sale%' => $secondaryVat->getSale()
                        ->getNumber(),
                    '%secondaryVat%' => $secondaryVat->getVatInfo()
                ));

                return $this->renderResponse('AcfClientBundle:SecondaryVat:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }

    protected function traceEntity(SecondaryVat $cloneSecondaryVat, SecondaryVat $secondaryVat)
    {
        $curUser = $this->getSecurityTokenStorage()
            ->getToken()
            ->getUser();
        $trace = new Trace();
        $trace->setActionId($secondaryVat->getId());
        $trace->setActionType(Trace::AT_UPDATE);
        $trace->setUserId($curUser->getId());
        $trace->setCompanyId($secondaryVat->getCompany()
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

        $trace->setActionEntity(Trace::AE_SECONDARYVAT);
        $trace->setActionId2($secondaryVat->getSale()
            ->getMonthlyBalance()
            ->getCompany()
            ->getId());
        $trace->setActionEntity2(Trace::AE_COMPANY);
        $trace->setActionId3($secondaryVat->getSale()
            ->getMonthlyBalance()
            ->getId());
        $trace->setActionEntity2(Trace::AE_MBSALE);
        $trace->setActionId3($secondaryVat->getSale()
            ->getId());
        $trace->setActionEntity2(Trace::AE_SALE);

        $msg = '';

        if ($cloneSecondaryVat->getVatInfo() != $secondaryVat->getVatInfo()) {
            $msg .= '<tr><td>' . $this->translate('SecondaryVat.vatInfo.label') . '</td><td>';
            if ($cloneSecondaryVat->getVatInfo() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSecondaryVat->getVatInfo();
            }
            $msg .= '</td><td>';
            if ($secondaryVat->getVatInfo() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $secondaryVat->getVatInfo();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSecondaryVat->getBalanceNet() != $secondaryVat->getBalanceNet()) {
            $msg .= '<tr><td>' . $this->translate('SecondaryVat.balanceNet.label') . '</td><td>';
            if ($cloneSecondaryVat->getBalanceNet() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSecondaryVat->getBalanceNet();
            }
            $msg .= '</td><td>';
            if ($secondaryVat->getBalanceNet() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $secondaryVat->getBalanceNet();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSecondaryVat->getBalanceTtc() != $secondaryVat->getBalanceTtc()) {
            $msg .= '<tr><td>' . $this->translate('SecondaryVat.balanceTtc.label') . '</td><td>';
            if ($cloneSecondaryVat->getBalanceTtc() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSecondaryVat->getBalanceTtc();
            }
            $msg .= '</td><td>';
            if ($secondaryVat->getBalanceTtc() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $secondaryVat->getBalanceTtc();
            }
            $msg .= '</td></tr>';
        }

        if ($msg != '') {

            $msg = $tableBegin . $msg . $tableEnd;

            $trace->setMsg($this->translate('SecondaryVat.traceEdit', array(
                '%secondaryVat%' => $secondaryVat->getVatInfo(),
                '%sale%' => $secondaryVat->getSale()
                    ->getLabel(),
                '%mbsale%' => $secondaryVat->getSale()
                    ->getMonthlyBalance()
                    ->getRef(),
                '%company%' => $secondaryVat->getSale()
                    ->getMonthlyBalance()
                    ->getCompany()
                    ->getCorporateName()
            )) . $msg);
            $trace->setDtCrea(new \DateTime('now'));
            $em = $this->getEntityManager();
            $em->persist($trace);
            $em->flush();
        }
    }
}