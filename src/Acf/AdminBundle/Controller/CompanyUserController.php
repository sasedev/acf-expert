<?php
namespace Acf\AdminBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\CompanyUser;
use Acf\AdminBundle\Form\CompanyUser\UpdateTForm as CompanyUserUpdateTForm;
use Acf\DataBundle\Entity\Role;
use Acf\DataBundle\Entity\Trace;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class CompanyUserController extends BaseController
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
            $companyUser = $em->getRepository('AcfDataBundle:CompanyUser')->find($uid);

            if (null == $companyUser) {
                $this->flashMsgSession('warning', $this->translate('CompanyUser.delete.notfound'));
            } else {

                $em->remove($companyUser);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('CompanyUser.delete.success', array(
                    '%company%' => $companyUser->getCompany()
                        ->getCorporateName(),
                    '%user%' => $companyUser->getUser()
                        ->getFullName()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('CompanyUser.delete.failure'));
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
            $companyUser = $em->getRepository('AcfDataBundle:CompanyUser')->find($uid);

            if (null == $companyUser) {
                $this->flashMsgSession('warning', $this->translate('CompanyUser.edit.notfound'));
            } else {
                $traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($companyUser->getId(), Trace::AE_CUSER);
                $this->gvars['traces'] = array_reverse($traces);
                $companyUserUpdateForm = $this->createForm(CompanyUserUpdateTForm::class, $companyUser);

                $this->gvars['companyUser'] = $companyUser;
                $this->gvars['CompanyUserUpdateForm'] = $companyUserUpdateForm->createView();

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');

                $this->gvars['pagetitle'] = $this->translate('pagetitle.companyUser.edit', array(
                    '%company%' => $companyUser->getCompany()
                        ->getCorporateName(),
                    '%user%' => $companyUser->getUser()
                        ->getFullName()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.companyUser.edit.txt', array(
                    '%company%' => $companyUser->getCompany()
                        ->getCorporateName(),
                    '%user%' => $companyUser->getUser()
                        ->getFullName()
                ));

                return $this->renderResponse('AcfAdminBundle:CompanyUser:edit.html.twig', $this->gvars);
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
            $companyUser = $em->getRepository('AcfDataBundle:CompanyUser')->find($uid);

            if (null == $companyUser) {
                $this->flashMsgSession('warning', $this->translate('CompanyUser.edit.notfound'));
            } else {
                $traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($companyUser->getId(), Trace::AE_CUSER);
                $this->gvars['traces'] = array_reverse($traces);
                $companyUserUpdateForm = $this->createForm(CompanyUserUpdateTForm::class, $companyUser);

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                $cloneCompanyUser = clone $companyUser;

                if (isset($reqData['CompanyUserUpdateForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyUserUpdateForm->handleRequest($request);
                    if ($companyUserUpdateForm->isValid()) {
                        $user = $companyUser->getUser();
                        $hasClientRole = false;
                        foreach ($user->getUserRoles() as $role) {
                            if ($role->getName() == 'ROLE_CLIENT1') {
                                $hasClientRole = true;
                            }
                        }
                        if (!$hasClientRole) {

                            $roleClient = $em->getRepository('AcfDataBundle:Role')->findOneBy(array(
                                'name' => 'ROLE_CLIENT1'
                            ));
                            if (null == $roleClient) {
                                $roleClient = new Role();
                                $roleClient->setName('ROLE_CLIENT1');
                            }

                            $user->addUserRole($roleClient);
                            $em->persist($user);
                        }

                        $em->persist($companyUser);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('CompanyUser.edit.success', array(
                            '%company%' => $companyUser->getCompany()
                                ->getCorporateName(),
                            '%user%' => $companyUser->getUser()
                                ->getFullName()
                        )));

                        $this->traceEntity($cloneCompanyUser, $companyUser);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($companyUser);

                        $this->flashMsgSession('error', $this->translate('CompanyUser.edit.failure', array(
                            '%company%' => $companyUser->getCompany()
                                ->getCorporateName(),
                            '%user%' => $companyUser->getUser()
                                ->getFullName()
                        )));
                    }
                }

                $this->gvars['companyUser'] = $companyUser;
                $this->gvars['CompanyUserUpdateForm'] = $companyUserUpdateForm->createView();

                $this->gvars['pagetitle'] = $this->translate('pagetitle.companyUser.edit', array(
                    '%company%' => $companyUser->getCompany()
                        ->getCorporateName(),
                    '%user%' => $companyUser->getUser()
                        ->getFullName()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.companyUser.edit.txt', array(
                    '%company%' => $companyUser->getCompany()
                        ->getCorporateName(),
                    '%user%' => $companyUser->getUser()
                        ->getFullName()
                ));

                return $this->renderResponse('AcfAdminBundle:CompanyUser:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }

    protected function traceEntity(CompanyUser $cloneCompanyUser, CompanyUser $companyUser)
    {
        $curUser = $this->getSecurityTokenStorage()
            ->getToken()
            ->getUser();
        $trace = new Trace();
        $trace->setActionId($companyUser->getId());
        $trace->setActionType(Trace::AT_UPDATE);
        $trace->setUserId($curUser->getId());
        $trace->setCompanyId($companyUser->getCompany()
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

        $trace->setActionEntity(Trace::AE_CUSER);
        $trace->setActionId2($companyUser->getCompany()
        ->getId());
        $trace->setActionEntity2(Trace::AE_COMPANY);

        $msg = '';

        if ($cloneCompanyUser->getEditCompanyinfos() != $companyUser->getEditCompanyinfos()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.editCompanyinfos.label') . '</td><td>';
            if ($cloneCompanyUser->getEditCompanyinfos() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getEditCompanyinfos());
            }
            $msg .= '</td><td>';
            if ($companyUser->getEditCompanyinfos() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getEditCompanyinfos());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getAddAddresses() != $companyUser->getAddAddresses()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.addAddresses.label') . '</td><td>';
            if ($cloneCompanyUser->getAddAddresses() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getAddAddresses());
            }
            $msg .= '</td><td>';
            if ($companyUser->getAddAddresses() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getAddAddresses());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getEditAddresses() != $companyUser->getEditAddresses()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.editAddresses.label') . '</td><td>';
            if ($cloneCompanyUser->getEditAddresses() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getEditAddresses());
            }
            $msg .= '</td><td>';
            if ($companyUser->getEditAddresses() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getEditAddresses());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getDeleteAddresses() != $companyUser->getDeleteAddresses()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.deleteAddresses.label') . '</td><td>';
            if ($cloneCompanyUser->getDeleteAddresses() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getDeleteAddresses());
            }
            $msg .= '</td><td>';
            if ($companyUser->getDeleteAddresses() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getDeleteAddresses());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getAddPhones() != $companyUser->getAddPhones()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.addPhones.label') . '</td><td>';
            if ($cloneCompanyUser->getAddPhones() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getAddPhones());
            }
            $msg .= '</td><td>';
            if ($companyUser->getAddPhones() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getAddPhones());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getEditPhones() != $companyUser->getEditPhones()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.editPhones.label') . '</td><td>';
            if ($cloneCompanyUser->getEditPhones() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getEditPhones());
            }
            $msg .= '</td><td>';
            if ($companyUser->getEditPhones() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getEditPhones());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getDeletePhones() != $companyUser->getDeletePhones()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.deletePhones.label') . '</td><td>';
            if ($cloneCompanyUser->getDeletePhones() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getDeletePhones());
            }
            $msg .= '</td><td>';
            if ($companyUser->getDeletePhones() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getDeletePhones());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getAddFrames() != $companyUser->getAddFrames()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.addFrames.label') . '</td><td>';
            if ($cloneCompanyUser->getAddFrames() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getAddFrames());
            }
            $msg .= '</td><td>';
            if ($companyUser->getAddFrames() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getAddFrames());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getEditFrames() != $companyUser->getEditFrames()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.editFrames.label') . '</td><td>';
            if ($cloneCompanyUser->getEditFrames() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getEditFrames());
            }
            $msg .= '</td><td>';
            if ($companyUser->getEditFrames() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getEditFrames());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getDeleteFrames() != $companyUser->getDeleteFrames()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.deleteFrames.label') . '</td><td>';
            if ($cloneCompanyUser->getDeleteFrames() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getDeleteFrames());
            }
            $msg .= '</td><td>';
            if ($companyUser->getDeleteFrames() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getDeleteFrames());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getAddDocs() != $companyUser->getAddDocs()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.addDocs.label') . '</td><td>';
            if ($cloneCompanyUser->getAddDocs() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getAddDocs());
            }
            $msg .= '</td><td>';
            if ($companyUser->getAddDocs() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getAddDocs());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getEditDocs() != $companyUser->getEditDocs()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.editDocs.label') . '</td><td>';
            if ($cloneCompanyUser->getEditDocs() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getEditDocs());
            }
            $msg .= '</td><td>';
            if ($companyUser->getEditDocs() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getEditDocs());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getDeleteDocs() != $companyUser->getDeleteDocs()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.deleteDocs.label') . '</td><td>';
            if ($cloneCompanyUser->getDeleteDocs() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getDeleteDocs());
            }
            $msg .= '</td><td>';
            if ($companyUser->getDeleteDocs() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getDeleteDocs());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getAddSuppliers() != $companyUser->getAddSuppliers()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.addSuppliers.label') . '</td><td>';
            if ($cloneCompanyUser->getAddSuppliers() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getAddSuppliers());
            }
            $msg .= '</td><td>';
            if ($companyUser->getAddSuppliers() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getAddSuppliers());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getEditSuppliers() != $companyUser->getEditSuppliers()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.editSuppliers.label') . '</td><td>';
            if ($cloneCompanyUser->getEditSuppliers() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getEditSuppliers());
            }
            $msg .= '</td><td>';
            if ($companyUser->getEditSuppliers() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getEditSuppliers());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getDeleteSuppliers() != $companyUser->getDeleteSuppliers()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.deleteSuppliers.label') . '</td><td>';
            if ($cloneCompanyUser->getDeleteSuppliers() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getDeleteSuppliers());
            }
            $msg .= '</td><td>';
            if ($companyUser->getDeleteSuppliers() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getDeleteSuppliers());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getAddCustomers() != $companyUser->getAddCustomers()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.addCustomers.label') . '</td><td>';
            if ($cloneCompanyUser->getAddCustomers() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getAddCustomers());
            }
            $msg .= '</td><td>';
            if ($companyUser->getAddCustomers() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getAddCustomers());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getEditCustomers() != $companyUser->getEditCustomers()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.editCustomers.label') . '</td><td>';
            if ($cloneCompanyUser->getEditCustomers() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getEditCustomers());
            }
            $msg .= '</td><td>';
            if ($companyUser->getEditCustomers() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getEditCustomers());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getDeleteCustomers() != $companyUser->getDeleteCustomers()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.deleteCustomers.label') . '</td><td>';
            if ($cloneCompanyUser->getDeleteCustomers() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getDeleteCustomers());
            }
            $msg .= '</td><td>';
            if ($companyUser->getDeleteCustomers() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getDeleteCustomers());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getAddSales() != $companyUser->getAddSales()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.addSales.label') . '</td><td>';
            if ($cloneCompanyUser->getAddSales() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getAddSales());
            }
            $msg .= '</td><td>';
            if ($companyUser->getAddSales() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getAddSales());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getEditSales() != $companyUser->getEditSales()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.editSales.label') . '</td><td>';
            if ($cloneCompanyUser->getEditSales() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getEditSales());
            }
            $msg .= '</td><td>';
            if ($companyUser->getEditSales() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getEditSales());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getDeleteSales() != $companyUser->getDeleteSales()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.deleteSales.label') . '</td><td>';
            if ($cloneCompanyUser->getDeleteSales() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getDeleteSales());
            }
            $msg .= '</td><td>';
            if ($companyUser->getDeleteSales() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getDeleteSales());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getAddBuys() != $companyUser->getAddBuys()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.addBuys.label') . '</td><td>';
            if ($cloneCompanyUser->getAddBuys() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getAddBuys());
            }
            $msg .= '</td><td>';
            if ($companyUser->getAddBuys() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getAddBuys());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getEditBuys() != $companyUser->getEditBuys()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.editBuys.label') . '</td><td>';
            if ($cloneCompanyUser->getEditBuys() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getEditBuys());
            }
            $msg .= '</td><td>';
            if ($companyUser->getEditBuys() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getEditBuys());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getDeleteBuys() != $companyUser->getDeleteBuys()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.deleteBuys.label') . '</td><td>';
            if ($cloneCompanyUser->getDeleteBuys() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getDeleteBuys());
            }
            $msg .= '</td><td>';
            if ($companyUser->getDeleteBuys() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getDeleteBuys());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getAddDocgroupComptables() != $companyUser->getAddDocgroupComptables()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.addDocgroupComptables.label') . '</td><td>';
            if ($cloneCompanyUser->getAddDocgroupComptables() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getAddDocgroupComptables());
            }
            $msg .= '</td><td>';
            if ($companyUser->getAddDocgroupComptables() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getAddDocgroupComptables());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getEditDocgroupComptables() != $companyUser->getEditDocgroupComptables()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.editDocgroupComptables.label') . '</td><td>';
            if ($cloneCompanyUser->getEditDocgroupComptables() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getEditDocgroupComptables());
            }
            $msg .= '</td><td>';
            if ($companyUser->getEditDocgroupComptables() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getEditDocgroupComptables());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getAddDocgroupBanks() != $companyUser->getAddDocgroupBanks()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.addDocgroupBanks.label') . '</td><td>';
            if ($cloneCompanyUser->getAddDocgroupBanks() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getAddDocgroupBanks());
            }
            $msg .= '</td><td>';
            if ($companyUser->getAddDocgroupBanks() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getAddDocgroupBanks());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getEditDocgroupBanks() != $companyUser->getEditDocgroupBanks()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.editDocgroupBanks.label') . '</td><td>';
            if ($cloneCompanyUser->getEditDocgroupBanks() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getEditDocgroupBanks());
            }
            $msg .= '</td><td>';
            if ($companyUser->getEditDocgroupBanks() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getEditDocgroupBanks());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getAddDocgroupJuridics() != $companyUser->getAddDocgroupJuridics()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.addDocgroupJuridics.label') . '</td><td>';
            if ($cloneCompanyUser->getAddDocgroupJuridics() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getAddDocgroupJuridics());
            }
            $msg .= '</td><td>';
            if ($companyUser->getAddDocgroupJuridics() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getAddDocgroupJuridics());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getEditDocgroupJuridics() != $companyUser->getEditDocgroupJuridics()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.editDocgroupJuridics.label') . '</td><td>';
            if ($cloneCompanyUser->getEditDocgroupJuridics() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getEditDocgroupJuridics());
            }
            $msg .= '</td><td>';
            if ($companyUser->getEditDocgroupJuridics() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getEditDocgroupJuridics());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getAddDocgroupFiscals() != $companyUser->getAddDocgroupFiscals()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.addDocgroupFiscals.label') . '</td><td>';
            if ($cloneCompanyUser->getAddDocgroupFiscals() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getAddDocgroupFiscals());
            }
            $msg .= '</td><td>';
            if ($companyUser->getAddDocgroupFiscals() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getAddDocgroupFiscals());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getEditDocgroupFiscals() != $companyUser->getEditDocgroupFiscals()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.editDocgroupFiscals.label') . '</td><td>';
            if ($cloneCompanyUser->getEditDocgroupFiscals() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getEditDocgroupFiscals());
            }
            $msg .= '</td><td>';
            if ($companyUser->getEditDocgroupFiscals() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getEditDocgroupFiscals());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getAddDocgroupPersos() != $companyUser->getAddDocgroupPersos()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.addDocgroupPersos.label') . '</td><td>';
            if ($cloneCompanyUser->getAddDocgroupPersos() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getAddDocgroupPersos());
            }
            $msg .= '</td><td>';
            if ($companyUser->getAddDocgroupPersos() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getAddDocgroupPersos());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getEditDocgroupPersos() != $companyUser->getEditDocgroupPersos()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.editDocgroupPersos.label') . '</td><td>';
            if ($cloneCompanyUser->getEditDocgroupPersos() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getEditDocgroupPersos());
            }
            $msg .= '</td><td>';
            if ($companyUser->getEditDocgroupPersos() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getEditDocgroupPersos());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getAddDocgroupSysts() != $companyUser->getAddDocgroupSysts()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.addDocgroupSysts.label') . '</td><td>';
            if ($cloneCompanyUser->getAddDocgroupSysts() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getAddDocgroupSysts());
            }
            $msg .= '</td><td>';
            if ($companyUser->getAddDocgroupSysts() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getAddDocgroupSysts());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyUser->getEditDocgroupSysts() != $companyUser->getEditDocgroupSysts()) {
            $msg .= '<tr><td>' . $this->translate('CompanyUser.editDocgroupSysts.label') . '</td><td>';
            if ($cloneCompanyUser->getEditDocgroupSysts() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $cloneCompanyUser->getEditDocgroupSysts());
            }
            $msg .= '</td><td>';
            if ($companyUser->getEditDocgroupSysts() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('CompanyUser.tf.' . $companyUser->getEditDocgroupSysts());
            }
            $msg .= '</td></tr>';
        }

        if ($msg != '') {

            $msg = $tableBegin . $msg . $tableEnd;

            $trace->setMsg($this->translate('CompanyUser.traceEdit', array(
                '%user%' => $companyUser->getUser()
                    ->getFullName(),
                '%company%' => $companyUser->getCompany()
                    ->getCorporateName()
            )) . $msg);
            $trace->setDtCrea(new \DateTime('now'));
            $em = $this->getEntityManager();
            $em->persist($trace);
            $em->flush();
        }
    }
}
