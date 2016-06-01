<?php
namespace Acf\AdminBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\Bank;
use Acf\AdminBundle\Form\Bank\UpdateLabelTForm as BankUpdateLabelTForm;
use Acf\AdminBundle\Form\Bank\UpdateNumberTForm as BankUpdateNumberTForm;
use Acf\AdminBundle\Form\Bank\UpdateAgencyTForm as BankUpdateAgencyTForm;
use Acf\AdminBundle\Form\Bank\UpdateRibTForm as BankUpdateRibTForm;
use Acf\AdminBundle\Form\Bank\UpdateOtherInfosTForm as BankUpdateOtherInfosTForm;
use Acf\AdminBundle\Form\Bank\UpdateDocsTForm as BankUpdateDocsTForm;
use Acf\AdminBundle\Form\Doc\NewTForm as DocNewTForm;
use Acf\DataBundle\Entity\Doc;
use Acf\DataBundle\Entity\ConstantStr;
use Acf\DataBundle\Entity\Trace;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class BankController extends BaseController
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
            $bank = $em->getRepository('AcfDataBundle:Bank')->find($uid);

            if (null == $bank) {
                $this->flashMsgSession('warning', $this->translate('Bank.delete.notfound'));
            } else {
                $em->remove($bank);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('Bank.delete.success', array(
                    '%bank%' => $bank->getLabel()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('Bank.delete.failure'));
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
            $bank = $em->getRepository('AcfDataBundle:Bank')->find($uid);

            if (null == $bank) {
                $this->flashMsgSession('warning', $this->translate('Bank.edit.notfound'));
            } else {
                $traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($bank->getId(), Trace::AE_BANK);
                $this->gvars['traces'] = array_reverse($traces);
                $bankUpdateLabelForm = $this->createForm(BankUpdateLabelTForm::class, $bank);
                $bankUpdateNumberForm = $this->createForm(BankUpdateNumberTForm::class, $bank);
                $bankUpdateAgencyForm = $this->createForm(BankUpdateAgencyTForm::class, $bank);
                $bankUpdateRibForm = $this->createForm(BankUpdateRibTForm::class, $bank);
                $bankUpdateOtherInfosForm = $this->createForm(BankUpdateOtherInfosTForm::class, $bank);
                $bankUpdateDocsForm = $this->createForm(BankUpdateDocsTForm::class, $bank, array(
                    'company' => $bank->getCompany()
                ));

                $doc = new Doc();
                $doc->setCompany($bank->getCompany());
                $docNewForm = $this->createForm(DocNewTForm::class, $doc, array(
                    'company' => $bank->getCompany()
                ));

                $this->gvars['bank'] = $bank;
                $this->gvars['doc'] = $doc;
                $this->gvars['BankUpdateLabelForm'] = $bankUpdateLabelForm->createView();
                $this->gvars['BankUpdateNumberForm'] = $bankUpdateNumberForm->createView();
                $this->gvars['BankUpdateAgencyForm'] = $bankUpdateAgencyForm->createView();
                $this->gvars['BankUpdateRibForm'] = $bankUpdateRibForm->createView();
                $this->gvars['BankUpdateOtherInfosForm'] = $bankUpdateOtherInfosForm->createView();
                $this->gvars['BankUpdateDocsForm'] = $bankUpdateDocsForm->createView();
                $this->gvars['DocNewForm'] = $docNewForm->createView();

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');

                $this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
                $this->getSession()->remove('stabActive');

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

                $this->gvars['pagetitle'] = $this->translate('pagetitle.bank.edit', array(
                    '%bank%' => $bank->getLabel()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.bank.edit.txt', array(
                    '%bank%' => $bank->getLabel()
                ));

                return $this->renderResponse('AcfAdminBundle:Bank:edit.html.twig', $this->gvars);
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
            $bank = $em->getRepository('AcfDataBundle:Bank')->find($uid);

            if (null == $bank) {
                $this->flashMsgSession('warning', $this->translate('Bank.edit.notfound'));
            } else {
                $traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($bank->getId(), Trace::AE_BANK);
                $this->gvars['traces'] = array_reverse($traces);
                $bankUpdateLabelForm = $this->createForm(BankUpdateLabelTForm::class, $bank);
                $bankUpdateNumberForm = $this->createForm(BankUpdateNumberTForm::class, $bank);
                $bankUpdateAgencyForm = $this->createForm(BankUpdateAgencyTForm::class, $bank);
                $bankUpdateRibForm = $this->createForm(BankUpdateRibTForm::class, $bank);
                $bankUpdateOtherInfosForm = $this->createForm(BankUpdateOtherInfosTForm::class, $bank);
                $bankUpdateDocsForm = $this->createForm(BankUpdateDocsTForm::class, $bank, array(
                    'company' => $bank->getCompany()
                ));

                $doc = new Doc();
                $doc->setCompany($bank->getCompany());
                $docNewForm = $this->createForm(DocNewTForm::class, $doc, array(
                    'company' => $bank->getCompany()
                ));

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
                $this->getSession()->remove('stabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                $cloneBank = clone $bank;

                if (isset($reqData['BankUpdateLabelForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $bankUpdateLabelForm->handleRequest($request);
                    if ($bankUpdateLabelForm->isValid()) {
                        $em->persist($bank);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Bank.edit.success', array(
                            '%bank%' => $bank->getLabel()
                        )));

                        $this->traceEntity($cloneBank, $bank);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($bank);

                        $this->flashMsgSession('error', $this->translate('Bank.edit.failure', array(
                            '%bank%' => $bank->getLabel()
                        )));
                    }
                } elseif (isset($reqData['BankUpdateNumberForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $bankUpdateNumberForm->handleRequest($request);
                    if ($bankUpdateNumberForm->isValid()) {
                        $em->persist($bank);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Bank.edit.success', array(
                            '%bank%' => $bank->getLabel()
                        )));

                        $this->traceEntity($cloneBank, $bank);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($bank);

                        $this->flashMsgSession('error', $this->translate('Bank.edit.failure', array(
                            '%bank%' => $bank->getLabel()
                        )));
                    }
                } elseif (isset($reqData['BankUpdateAgencyForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $bankUpdateAgencyForm->handleRequest($request);
                    if ($bankUpdateAgencyForm->isValid()) {
                        $em->persist($bank);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Bank.edit.success', array(
                            '%bank%' => $bank->getLabel()
                        )));

                        $this->traceEntity($cloneBank, $bank);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($bank);

                        $this->flashMsgSession('error', $this->translate('Bank.edit.failure', array(
                            '%bank%' => $bank->getLabel()
                        )));
                    }
                } elseif (isset($reqData['BankUpdateRibForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $bankUpdateRibForm->handleRequest($request);
                    if ($bankUpdateRibForm->isValid()) {
                        $em->persist($bank);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Bank.edit.success', array(
                            '%bank%' => $bank->getLabel()
                        )));

                        $this->traceEntity($cloneBank, $bank);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($bank);

                        $this->flashMsgSession('error', $this->translate('Bank.edit.failure', array(
                            '%bank%' => $bank->getLabel()
                        )));
                    }
                } elseif (isset($reqData['BankUpdateOtherInfosForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $bankUpdateOtherInfosForm->handleRequest($request);
                    if ($bankUpdateOtherInfosForm->isValid()) {
                        $em->persist($bank);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Bank.edit.success', array(
                            '%bank%' => $bank->getLabel()
                        )));

                        $this->traceEntity($cloneBank, $bank);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($bank);

                        $this->flashMsgSession('error', $this->translate('Bank.edit.failure', array(
                            '%bank%' => $bank->getLabel()
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
                            $doc->setCompany($bank->getCompany());

                            $doc->setFileName($fileName);
                            $doc->setOriginalName($originalName);
                            $doc->setSize($size);
                            $doc->setMimeType($mimeType);
                            $doc->setMd5($md5);
                            $doc->setDescription($docNewForm['description']->getData());
                            $em->persist($doc);

                            $bank->addDoc($doc);

                            $docNames .= $doc->getOriginalName() . ' ';
                        }

                        $em->persist($bank);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Doc.add.success', array(
                            '%doc%' => $docNames
                        )));
                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        $this->traceEntity($cloneBank, $bank);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($bank);

                        $this->flashMsgSession('error', $this->translate('Doc.add.failure'));
                    }
                } elseif (isset($reqData['BankUpdateDocsForm'])) {
                    $this->gvars['tabActive'] = 3;
                    $this->getSession()->set('tabActive', 3);
                    $this->gvars['stabActive'] = 2;
                    $this->getSession()->set('stabActive', 2);
                    $bankUpdateDocsForm->handleRequest($request);
                    if ($bankUpdateDocsForm->isValid()) {
                        $em->persist($bank);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Bank.edit.success', array(
                            '%bank%' => $bank->getLabel()
                        )));
                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        $this->traceEntity($cloneBank, $bank);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($bank);

                        $this->flashMsgSession('error', $this->translate('Bank.edit.failure', array(
                            '%bank%' => $bank->getLabel()
                        )));
                    }
                }

                $this->gvars['bank'] = $bank;
                $this->gvars['doc'] = $doc;
                $this->gvars['BankUpdateLabelForm'] = $bankUpdateLabelForm->createView();
                $this->gvars['BankUpdateNumberForm'] = $bankUpdateNumberForm->createView();
                $this->gvars['BankUpdateAgencyForm'] = $bankUpdateAgencyForm->createView();
                $this->gvars['BankUpdateRibForm'] = $bankUpdateRibForm->createView();
                $this->gvars['BankUpdateOtherInfosForm'] = $bankUpdateOtherInfosForm->createView();
                $this->gvars['BankUpdateDocsForm'] = $bankUpdateDocsForm->createView();
                $this->gvars['DocNewForm'] = $docNewForm->createView();

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

                $this->gvars['pagetitle'] = $this->translate('pagetitle.bank.edit', array(
                    '%bank%' => $bank->getLabel()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.bank.edit.txt', array(
                    '%bank%' => $bank->getLabel()
                ));

                return $this->renderResponse('AcfAdminBundle:Bank:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }

    protected function traceEntity(Bank $cloneBank, Bank $bank)
    {
        $curUser = $this->getSecurityTokenStorage()
            ->getToken()
            ->getUser();
        $trace = new Trace();
        $trace->setActionId($bank->getId());
        $trace->setActionType(Trace::AT_UPDATE);
        $trace->setUserId($curUser->getId());
        $trace->setCompanyId($bank->getCompany()
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

        $trace->setActionEntity(Trace::AE_BANK);
        $trace->setActionId2($bank->getCompany()
        ->getId());
        $trace->setActionEntity2(Trace::AE_COMPANY);

        $msg = '';

        if ($cloneBank->getLabel() != $bank->getLabel()) {
            $msg .= '<tr><td>' . $this->translate('Bank.label.label') . '</td><td>';
            if ($cloneBank->getLabel() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneBank->getLabel();
            }
            $msg .= '</td><td>';
            if ($bank->getLabel() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $bank->getLabel();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneBank->getNumber() != $bank->getNumber()) {
            $msg .= '<tr><td>' . $this->translate('Bank.number.label') . '</td><td>';
            if ($cloneBank->getNumber() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneBank->getNumberFormated();
            }
            $msg .= '</td><td>';
            if ($bank->getNumber() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $bank->getNumberFormated();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneBank->getAgency() != $bank->getAgency()) {
            $msg .= '<tr><td>' . $this->translate('Bank.agency.label') . '</td><td>';
            if ($cloneBank->getAgency() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneBank->getAgency();
            }
            $msg .= '</td><td>';
            if ($bank->getAgency() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $bank->getAgency();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneBank->getRib() != $bank->getRib()) {
            $msg .= '<tr><td>' . $this->translate('Bank.rib.label') . '</td><td>';
            if ($cloneBank->getRib() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneBank->getRib();
            }
            $msg .= '</td><td>';
            if ($bank->getRib() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $bank->getRib();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneBank->getContact() != $bank->getContact()) {
            $msg .= '<tr><td>' . $this->translate('Bank.contact.label') . '</td><td>';
            if ($cloneBank->getContact() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneBank->getContact();
            }
            $msg .= '</td><td>';
            if ($bank->getContact() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $bank->getContact();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneBank->getTel() != $bank->getTel()) {
            $msg .= '<tr><td>' . $this->translate('Bank.tel.label') . '</td><td>';
            if ($cloneBank->getTel() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneBank->getTel();
            }
            $msg .= '</td><td>';
            if ($bank->getTel() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $bank->getTel();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneBank->getFax() != $bank->getFax()) {
            $msg .= '<tr><td>' . $this->translate('Bank.fax.label') . '</td><td>';
            if ($cloneBank->getFax() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneBank->getFax();
            }
            $msg .= '</td><td>';
            if ($bank->getFax() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $bank->getFax();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneBank->getEmail() != $bank->getEmail()) {
            $msg .= '<tr><td>' . $this->translate('Bank.email.label') . '</td><td>';
            if ($cloneBank->getEmail() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneBank->getEmail();
            }
            $msg .= '</td><td>';
            if ($bank->getEmail() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $bank->getEmail();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneBank->getOtherInfos() != $bank->getOtherInfos()) {
            $msg .= '<tr><td>' . $this->translate('Bank.otherInfos.label') . '</td><td>';
            if ($cloneBank->getOtherInfos() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneBank->getOtherInfos();
            }
            $msg .= '</td><td>';
            if ($bank->getOtherInfos() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $bank->getOtherInfos();
            }
            $msg .= '</td></tr>';
        }

        if (\count(\array_diff($bank->getDocs()->toArray(), $cloneBank->getDocs()->toArray())) != 0 || \count(\array_diff($cloneBank->getDocs()->toArray(), $bank->getDocs()->toArray())) != 0) {
            $msg .= '<tr><td>' . $this->translate('Bank.docs.label') . '</td><td>';
            if (\count($cloneBank->getDocs()) == 0) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= '<ul>';
                foreach ($cloneBank->getDocs() as $doc) {
                    $msg .= '<li><a href="' . $this->generateUrl('_admin_doc_editGet', array(
                        'uid' => $doc->getId()
                    )) . '">' . $doc->getOriginalName() . '</a></li>';
                }
                $msg .= '<ul>';
            }
            $msg .= '</td><td>';
            if (\count($bank->getDocs()) == 0) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= '<ul>';
                foreach ($bank->getDocs() as $doc) {
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

            $trace->setMsg($this->translate('Bank.traceEdit', array(
                '%bank%' => $bank->getLabel(),
                '%company%' => $bank->getCompany()
                    ->getCorporateName()
            )) . $msg);
            $trace->setDtCrea(new \DateTime('now'));
            $em = $this->getEntityManager();
            $em->persist($trace);
            $em->flush();
        }
    }
}
