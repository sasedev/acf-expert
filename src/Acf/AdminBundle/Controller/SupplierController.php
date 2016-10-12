<?php
namespace Acf\AdminBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\Supplier;
use Acf\AdminBundle\Form\Supplier\UpdateTForm as SupplierUpdateTForm;
use Acf\AdminBundle\Form\Supplier\UpdateDocsTForm as SupplierUpdateDocsTForm;
use Acf\AdminBundle\Form\Doc\NewTForm as DocNewTForm;
use Acf\DataBundle\Entity\Doc;
use Acf\DataBundle\Entity\ConstantStr;
use Acf\DataBundle\Entity\Trace;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class SupplierController extends BaseController
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
            $supplier = $em->getRepository('AcfDataBundle:Supplier')->find($uid);

            if (null == $supplier) {
                $this->flashMsgSession('warning', $this->translate('Supplier.delete.notfound'));
            } else {
                $em->remove($supplier);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('Supplier.delete.success', array(
                    '%supplier%' => $supplier->getLabel()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('Supplier.delete.failure'));
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
            $supplier = $em->getRepository('AcfDataBundle:Supplier')->find($uid);

            if (null == $supplier) {
                $this->flashMsgSession('warning', $this->translate('Supplier.edit.notfound'));
            } else {
                $traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($supplier->getId(), Trace::AE_SUPPLIER);
                $this->gvars['traces'] = array_reverse($traces);
                $supplierUpdateForm = $this->createForm(SupplierUpdateTForm::class, $supplier);
                $supplierUpdateDocsForm = $this->createForm(SupplierUpdateDocsTForm::class, $supplier, array(
                    'company' => $supplier->getCompany()
                ));

                $doc = new Doc();
                $doc->setCompany($supplier->getCompany());
                $docNewForm = $this->createForm(DocNewTForm::class, $doc, array(
                    'company' => $supplier->getCompany()
                ));

                $this->gvars['supplier'] = $supplier;
                $this->gvars['doc'] = $doc;
                $this->gvars['SupplierUpdateForm'] = $supplierUpdateForm->createView();
                $this->gvars['SupplierUpdateDocsForm'] = $supplierUpdateDocsForm->createView();
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

                $this->gvars['pagetitle'] = $this->translate('pagetitle.supplier.edit', array(
                    '%supplier%' => $supplier->getLabel()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.supplier.edit.txt', array(
                    '%supplier%' => $supplier->getLabel()
                ));

                return $this->renderResponse('AcfAdminBundle:Supplier:edit.html.twig', $this->gvars);
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
            $supplier = $em->getRepository('AcfDataBundle:Supplier')->find($uid);

            if (null == $supplier) {
                $this->flashMsgSession('warning', $this->translate('Supplier.edit.notfound'));
            } else {
                $traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($supplier->getId(), Trace::AE_SUPPLIER);
                $this->gvars['traces'] = array_reverse($traces);
                $supplierUpdateForm = $this->createForm(SupplierUpdateTForm::class, $supplier);
                $supplierUpdateDocsForm = $this->createForm(SupplierUpdateDocsTForm::class, $supplier, array(
                    'company' => $supplier->getCompany()
                ));

                $doc = new Doc();
                $doc->setCompany($supplier->getCompany());
                $docNewForm = $this->createForm(DocNewTForm::class, $doc, array(
                    'company' => $supplier->getCompany()
                ));

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
                $this->getSession()->remove('stabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                $cloneSupplier = clone $supplier;

                if (isset($reqData['SupplierUpdateForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $supplierUpdateForm->handleRequest($request);
                    if ($supplierUpdateForm->isValid()) {
                        $em->persist($supplier);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Supplier.edit.success', array(
                            '%supplier%' => $supplier->getLabel()
                        )));

                        $this->traceEntity($cloneSupplier, $supplier);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($supplier);

                        $this->flashMsgSession('error', $this->translate('Supplier.edit.failure', array(
                            '%supplier%' => $supplier->getLabel()
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
                            $doc->setCompany($supplier->getCompany());

                            $doc->setFileName($fileName);
                            $doc->setOriginalName($originalName);
                            $doc->setSize($size);
                            $doc->setMimeType($mimeType);
                            $doc->setMd5($md5);
                            $doc->setDescription($docNewForm['description']->getData());
                            $em->persist($doc);

                            $supplier->addDoc($doc);

                            $docNames .= $doc->getOriginalName() . ' ';

                            $docs[] = $doc;
                        }

                        $em->persist($supplier);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Doc.add.success', array(
                            '%doc%' => $docNames
                        )));

                        $from = $this->getParameter('mail_from');
                        $fromName = $this->getParameter('mail_from_name');
                        $subject = $this->translate('_mail.newdocsCloud.subject', array(), 'messages');

                        $company = $supplier->getCompany();
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

                        $this->traceEntity($cloneSupplier, $supplier);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($supplier);

                        $this->flashMsgSession('error', $this->translate('Doc.add.failure'));
                    }
                } elseif (isset($reqData['SupplierUpdateDocsForm'])) {
                    $this->gvars['tabActive'] = 3;
                    $this->getSession()->set('tabActive', 3);
                    $this->gvars['stabActive'] = 2;
                    $this->getSession()->set('stabActive', 2);
                    $supplierUpdateDocsForm->handleRequest($request);
                    if ($supplierUpdateDocsForm->isValid()) {
                        $em->persist($supplier);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Supplier.edit.success', array(
                            '%supplier%' => $supplier->getLabel()
                        )));
                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        $this->traceEntity($cloneSupplier, $supplier);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($supplier);

                        $this->flashMsgSession('error', $this->translate('Supplier.edit.failure', array(
                            '%supplier%' => $supplier->getLabel()
                        )));
                    }
                }

                $this->gvars['supplier'] = $supplier;
                $this->gvars['doc'] = $doc;
                $this->gvars['SupplierUpdateForm'] = $supplierUpdateForm->createView();
                $this->gvars['SupplierUpdateDocsForm'] = $supplierUpdateDocsForm->createView();
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

                $this->gvars['pagetitle'] = $this->translate('pagetitle.supplier.edit', array(
                    '%supplier%' => $supplier->getLabel()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.supplier.edit.txt', array(
                    '%supplier%' => $supplier->getLabel()
                ));

                return $this->renderResponse('AcfAdminBundle:Supplier:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }

    protected function traceEntity(Supplier $cloneSupplier, Supplier $supplier)
    {
        $curUser = $this->getSecurityTokenStorage()
            ->getToken()
            ->getUser();
        $trace = new Trace();
        $trace->setActionId($supplier->getId());
        $trace->setActionType(Trace::AT_UPDATE);
        $trace->setUserId($curUser->getId());
        $trace->setCompanyId($supplier->getCompany()
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

        $trace->setActionEntity(Trace::AE_SUPPLIER);
        $trace->setActionId2($supplier->getCompany()
            ->getId());
        $trace->setActionEntity2(Trace::AE_COMPANY);

        $msg = '';

        if ($cloneSupplier->getLabel() != $supplier->getLabel()) {
            $msg .= '<tr><td>' . $this->translate('Supplier.label.label') . '</td><td>';
            if ($cloneSupplier->getLabel() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSupplier->getLabel();
            }
            $msg .= '</td><td>';
            if ($supplier->getLabel() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $supplier->getLabel();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSupplier->getNumber() != $supplier->getNumber()) {
            $msg .= '<tr><td>' . $this->translate('Supplier.number.label') . '</td><td>';
            if ($cloneSupplier->getNumber() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSupplier->getNumberFormated();
            }
            $msg .= '</td><td>';
            if ($supplier->getNumber() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $supplier->getNumberFormated();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSupplier->getFisc() != $supplier->getFisc()) {
            $msg .= '<tr><td>' . $this->translate('Supplier.fisc.label') . '</td><td>';
            if ($cloneSupplier->getFisc() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSupplier->getFisc();
            }
            $msg .= '</td><td>';
            if ($supplier->getFisc() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $supplier->getFisc();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSupplier->getPhysicaltype() != $supplier->getPhysicaltype()) {
            $msg .= '<tr><td>' . $this->translate('Supplier.physicaltype.label') . '</td><td>';
            if ($cloneSupplier->getPhysicaltype() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('Supplier.physicaltype.' . $cloneSupplier->getPhysicaltype());
            }
            $msg .= '</td><td>';
            if ($supplier->getSexe() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('Supplier.physicaltype.' . $supplier->getPhysicaltype());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSupplier->getCin() != $supplier->getCin()) {
            $msg .= '<tr><td>' . $this->translate('Supplier.cin.label') . '</td><td>';
            if ($cloneSupplier->getCin() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSupplier->getCin();
            }
            $msg .= '</td><td>';
            if ($supplier->getCin() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $supplier->getCin();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSupplier->getPassport() != $supplier->getPassport()) {
            $msg .= '<tr><td>' . $this->translate('Supplier.passport.label') . '</td><td>';
            if ($cloneSupplier->getPassport() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSupplier->getPassport();
            }
            $msg .= '</td><td>';
            if ($supplier->getPassport() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $supplier->getPassport();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSupplier->getCommercialRegister() != $supplier->getCommercialRegister()) {
            $msg .= '<tr><td>' . $this->translate('Supplier.commercialRegister.label') . '</td><td>';
            if ($cloneSupplier->getCommercialRegister() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSupplier->getCommercialRegister();
            }
            $msg .= '</td><td>';
            if ($supplier->getCommercialRegister() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $supplier->getCommercialRegister();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSupplier->getStreetNum() != $supplier->getStreetNum()) {
            $msg .= '<tr><td>' . $this->translate('Supplier.streetNum.label') . '</td><td>';
            if ($cloneSupplier->getStreetNum() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSupplier->getStreetNum();
            }
            $msg .= '</td><td>';
            if ($supplier->getStreetNum() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $supplier->getStreetNum();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSupplier->getAddress() != $supplier->getAddress()) {
            $msg .= '<tr><td>' . $this->translate('Supplier.address.label') . '</td><td>';
            if ($cloneSupplier->getAddress() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSupplier->getAddress();
            }
            $msg .= '</td><td>';
            if ($supplier->getAddress() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $supplier->getAddress();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSupplier->getAddress2() != $supplier->getAddress2()) {
            $msg .= '<tr><td>' . $this->translate('Supplier.address2.label') . '</td><td>';
            if ($cloneSupplier->getAddress2() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSupplier->getAddress2();
            }
            $msg .= '</td><td>';
            if ($supplier->getAddress2() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $supplier->getAddress2();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSupplier->getTown() != $supplier->getTown()) {
            $msg .= '<tr><td>' . $this->translate('Supplier.town.label') . '</td><td>';
            if ($cloneSupplier->getTown() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSupplier->getTown();
            }
            $msg .= '</td><td>';
            if ($supplier->getTown() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $supplier->getTown();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSupplier->getZipCode() != $supplier->getZipCode()) {
            $msg .= '<tr><td>' . $this->translate('Supplier.zipCode.label') . '</td><td>';
            if ($cloneSupplier->getZipCode() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSupplier->getZipCode();
            }
            $msg .= '</td><td>';
            if ($supplier->getZipCode() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $supplier->getZipCode();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSupplier->getCountry() != $supplier->getCountry()) {
            $msg .= '<tr><td>' . $this->translate('Supplier.country.label') . '</td><td>';
            if ($cloneSupplier->getCountry() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSupplier->getCountry();
            }
            $msg .= '</td><td>';
            if ($supplier->getCountry() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $supplier->getCountry();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSupplier->getPhone() != $supplier->getPhone()) {
            $msg .= '<tr><td>' . $this->translate('Supplier.phone.label') . '</td><td>';
            if ($cloneSupplier->getPhone() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSupplier->getPhone();
            }
            $msg .= '</td><td>';
            if ($supplier->getPhone() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $supplier->getPhone();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSupplier->getMobile() != $supplier->getMobile()) {
            $msg .= '<tr><td>' . $this->translate('Supplier.mobile.label') . '</td><td>';
            if ($cloneSupplier->getMobile() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSupplier->getMobile();
            }
            $msg .= '</td><td>';
            if ($supplier->getMobile() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $supplier->getMobile();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSupplier->getFax() != $supplier->getFax()) {
            $msg .= '<tr><td>' . $this->translate('Supplier.fax.label') . '</td><td>';
            if ($cloneSupplier->getFax() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSupplier->getFax();
            }
            $msg .= '</td><td>';
            if ($supplier->getFax() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $supplier->getFax();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSupplier->getEmail() != $supplier->getEmail()) {
            $msg .= '<tr><td>' . $this->translate('Supplier.email.label') . '</td><td>';
            if ($cloneSupplier->getEmail() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSupplier->getEmail();
            }
            $msg .= '</td><td>';
            if ($supplier->getEmail() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $supplier->getEmail();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneSupplier->getOtherInfos() != $supplier->getOtherInfos()) {
            $msg .= '<tr><td>' . $this->translate('Supplier.otherInfos.label') . '</td><td>';
            if ($cloneSupplier->getOtherInfos() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneSupplier->getOtherInfos();
            }
            $msg .= '</td><td>';
            if ($supplier->getOtherInfos() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $supplier->getOtherInfos();
            }
            $msg .= '</td></tr>';
        }

        if (\count(\array_diff($supplier->getDocs()->toArray(), $cloneSupplier->getDocs()->toArray())) != 0 || \count(\array_diff($cloneSupplier->getDocs()->toArray(), $supplier->getDocs()->toArray())) != 0) {
            $msg .= '<tr><td>' . $this->translate('Supplier.docs.label') . '</td><td>';
            if (\count($cloneSupplier->getDocs()) == 0) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= '<ul>';
                foreach ($cloneSupplier->getDocs() as $doc) {
                    $msg .= '<li><a href="' . $this->generateUrl('_admin_doc_editGet', array(
                        'uid' => $doc->getId()
                    )) . '">' . $doc->getOriginalName() . '</a></li>';
                }
                $msg .= '<ul>';
            }
            $msg .= '</td><td>';
            if (\count($supplier->getDocs()) == 0) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= '<ul>';
                foreach ($supplier->getDocs() as $doc) {
                    $msg .= '<li><a href="' . $this->generateUrl('_admin_doc_editGet', array(
                        'uid' => $doc->getId()
                    )) . '">' . $doc->getOriginalName() . '</a></li>';
                }
                $msg .= '<ul>';
            }
            $msg .= '</td></tr>';
        }

        if (\count(\array_diff($supplier->getSectors()->toArray(), $cloneSupplier->getSectors()->toArray())) != 0 || \count(\array_diff($cloneSupplier->getSectors()->toArray(), $supplier->getSectors()->toArray())) != 0) {
            $msg .= '<tr><td>' . $this->translate('Supplier.sectors.label') . '</td><td>';
            if (\count($cloneSupplier->getSectors()) == 0) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= '<ul>';
                foreach ($cloneSupplier->getSectors() as $sector) {
                    $msg .= '<li>' . $sector->getLabel() . '</a></li>';
                }
                $msg .= '<ul>';
            }
            $msg .= '</td><td>';
            if (\count($supplier->getSectors()) == 0) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= '<ul>';
                foreach ($supplier->getSectors() as $sector) {
                    $msg .= '<li>' . $sector->getLabel() . '</a></li>';
                }
                $msg .= '<ul>';
            }
            $msg .= '</td></tr>';
        }

        if ($msg != '') {

            $msg = $tableBegin . $msg . $tableEnd;

            $trace->setMsg($this->translate('Supplier.traceEdit', array(
                '%supplier%' => $supplier->getLabel(),
                '%company%' => $supplier->getCompany()
                    ->getCorporateName()
            )) . $msg);
            $trace->setDtCrea(new \DateTime('now'));
            $em = $this->getEntityManager();
            $em->persist($trace);
            $em->flush();
        }
    }
}
