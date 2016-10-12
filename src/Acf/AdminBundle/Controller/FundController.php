<?php
namespace Acf\AdminBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\Fund;
use Acf\AdminBundle\Form\Fund\UpdateLabelTForm as FundUpdateLabelTForm;
use Acf\AdminBundle\Form\Fund\UpdateNumberTForm as FundUpdateNumberTForm;
use Acf\AdminBundle\Form\Fund\UpdateOtherInfosTForm as FundUpdateOtherInfosTForm;
use Acf\AdminBundle\Form\Fund\UpdateDocsTForm as FundUpdateDocsTForm;
use Acf\AdminBundle\Form\Doc\NewTForm as DocNewTForm;
use Acf\DataBundle\Entity\Doc;
use Acf\DataBundle\Entity\ConstantStr;
use Acf\DataBundle\Entity\Trace;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class FundController extends BaseController
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
            $fund = $em->getRepository('AcfDataBundle:Fund')->find($uid);

            if (null == $fund) {
                $this->flashMsgSession('warning', $this->translate('Fund.delete.notfound'));
            } else {
                $em->remove($fund);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('Fund.delete.success', array(
                    '%fund%' => $fund->getLabel()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('Fund.delete.failure'));
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
            $fund = $em->getRepository('AcfDataBundle:Fund')->find($uid);

            if (null == $fund) {
                $this->flashMsgSession('warning', $this->translate('Fund.edit.notfound'));
            } else {
                $traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($fund->getId(), Trace::AE_FUND);
                $this->gvars['traces'] = array_reverse($traces);
                $fundUpdateLabelForm = $this->createForm(FundUpdateLabelTForm::class, $fund);
                $fundUpdateNumberForm = $this->createForm(FundUpdateNumberTForm::class, $fund);
                $fundUpdateOtherInfosForm = $this->createForm(FundUpdateOtherInfosTForm::class, $fund);
                $fundUpdateDocsForm = $this->createForm(FundUpdateDocsTForm::class, $fund, array(
                    'company' => $fund->getCompany()
                ));

                $doc = new Doc();
                $doc->setCompany($fund->getCompany());
                $docNewForm = $this->createForm(DocNewTForm::class, $doc, array(
                    'company' => $fund->getCompany()
                ));

                $this->gvars['fund'] = $fund;
                $this->gvars['doc'] = $doc;
                $this->gvars['FundUpdateLabelForm'] = $fundUpdateLabelForm->createView();
                $this->gvars['FundUpdateNumberForm'] = $fundUpdateNumberForm->createView();
                $this->gvars['FundUpdateOtherInfosForm'] = $fundUpdateOtherInfosForm->createView();
                $this->gvars['FundUpdateDocsForm'] = $fundUpdateDocsForm->createView();
                $this->gvars['DocNewForm'] = $docNewForm->createView();

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');

                $this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
                $this->getSession()->remove('stabActive');

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

                $this->gvars['pagetitle'] = $this->translate('pagetitle.fund.edit', array(
                    '%fund%' => $fund->getLabel()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.fund.edit.txt', array(
                    '%fund%' => $fund->getLabel()
                ));

                return $this->renderResponse('AcfAdminBundle:Fund:edit.html.twig', $this->gvars);
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
            $fund = $em->getRepository('AcfDataBundle:Fund')->find($uid);

            if (null == $fund) {
                $this->flashMsgSession('warning', $this->translate('Fund.edit.notfound'));
            } else {
                $traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($fund->getId(), Trace::AE_FUND);
                $this->gvars['traces'] = array_reverse($traces);
                $fundUpdateLabelForm = $this->createForm(FundUpdateLabelTForm::class, $fund);
                $fundUpdateNumberForm = $this->createForm(FundUpdateNumberTForm::class, $fund);
                $fundUpdateOtherInfosForm = $this->createForm(FundUpdateOtherInfosTForm::class, $fund);
                $fundUpdateDocsForm = $this->createForm(FundUpdateDocsTForm::class, $fund, array(
                    'company' => $fund->getCompany()
                ));

                $doc = new Doc();
                $doc->setCompany($fund->getCompany());
                $docNewForm = $this->createForm(DocNewTForm::class, $doc, array(
                    'company' => $fund->getCompany()
                ));

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
                $this->getSession()->remove('stabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                $cloneFund = clone $fund;

                if (isset($reqData['FundUpdateLabelForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $fundUpdateLabelForm->handleRequest($request);
                    if ($fundUpdateLabelForm->isValid()) {
                        $em->persist($fund);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Fund.edit.success', array(
                            '%fund%' => $fund->getLabel()
                        )));

                        $this->traceEntity($cloneFund, $fund);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($fund);

                        $this->flashMsgSession('error', $this->translate('Fund.edit.failure', array(
                            '%fund%' => $fund->getLabel()
                        )));
                    }
                } elseif (isset($reqData['FundUpdateNumberForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $fundUpdateNumberForm->handleRequest($request);
                    if ($fundUpdateNumberForm->isValid()) {
                        $em->persist($fund);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Fund.edit.success', array(
                            '%fund%' => $fund->getLabel()
                        )));

                        $this->traceEntity($cloneFund, $fund);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($fund);

                        $this->flashMsgSession('error', $this->translate('Fund.edit.failure', array(
                            '%fund%' => $fund->getLabel()
                        )));
                    }
                } elseif (isset($reqData['FundUpdateOtherInfosForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $fundUpdateOtherInfosForm->handleRequest($request);
                    if ($fundUpdateOtherInfosForm->isValid()) {
                        $em->persist($fund);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Fund.edit.success', array(
                            '%fund%' => $fund->getLabel()
                        )));

                        $this->traceEntity($cloneFund, $fund);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($fund);

                        $this->flashMsgSession('error', $this->translate('Fund.edit.failure', array(
                            '%fund%' => $fund->getLabel()
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
                            $doc->setCompany($fund->getCompany());

                            $doc->setFileName($fileName);
                            $doc->setOriginalName($originalName);
                            $doc->setSize($size);
                            $doc->setMimeType($mimeType);
                            $doc->setMd5($md5);
                            $doc->setDescription($docNewForm['description']->getData());
                            $em->persist($doc);

                            $fund->addDoc($doc);

                            $docNames .= $doc->getOriginalName() . ' ';

                            $docs[] = $doc;
                        }

                        $em->persist($fund);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Doc.add.success', array(
                            '%doc%' => $docNames
                        )));

                        $from = $this->getParameter('mail_from');
                        $fromName = $this->getParameter('mail_from_name');
                        $subject = $this->translate('_mail.newdocsCloud.subject', array(), 'messages');

                        $company = $fund->getCompany();
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

                        $this->traceEntity($cloneFund, $fund);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($fund);

                        $this->flashMsgSession('error', $this->translate('Doc.add.failure'));
                    }
                } elseif (isset($reqData['FundUpdateDocsForm'])) {
                    $this->gvars['tabActive'] = 3;
                    $this->getSession()->set('tabActive', 3);
                    $this->gvars['stabActive'] = 2;
                    $this->getSession()->set('stabActive', 2);
                    $fundUpdateDocsForm->handleRequest($request);
                    if ($fundUpdateDocsForm->isValid()) {
                        $em->persist($fund);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Fund.edit.success', array(
                            '%fund%' => $fund->getLabel()
                        )));
                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        $this->traceEntity($cloneFund, $fund);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($fund);

                        $this->flashMsgSession('error', $this->translate('Fund.edit.failure', array(
                            '%fund%' => $fund->getLabel()
                        )));
                    }
                }

                $this->gvars['fund'] = $fund;
                $this->gvars['doc'] = $doc;
                $this->gvars['FundUpdateLabelForm'] = $fundUpdateLabelForm->createView();
                $this->gvars['FundUpdateNumberForm'] = $fundUpdateNumberForm->createView();
                $this->gvars['FundUpdateOtherInfosForm'] = $fundUpdateOtherInfosForm->createView();
                $this->gvars['FundUpdateDocsForm'] = $fundUpdateDocsForm->createView();
                $this->gvars['DocNewForm'] = $docNewForm->createView();

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

                $this->gvars['pagetitle'] = $this->translate('pagetitle.fund.edit', array(
                    '%fund%' => $fund->getLabel()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.fund.edit.txt', array(
                    '%fund%' => $fund->getLabel()
                ));

                return $this->renderResponse('AcfAdminBundle:Fund:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }

    protected function traceEntity(Fund $cloneFund, Fund $fund)
    {
        $curUser = $this->getSecurityTokenStorage()
            ->getToken()
            ->getUser();
        $trace = new Trace();
        $trace->setActionId($fund->getId());
        $trace->setActionType(Trace::AT_UPDATE);
        $trace->setUserId($curUser->getId());
        $trace->setUserFullname($curUser->getFullName());
        $trace->setCompanyId($fund->getCompany()
            ->getId());
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

        $trace->setActionEntity(Trace::AE_FUND);
        $trace->setActionId2($fund->getCompany()
            ->getId());
        $trace->setActionEntity2(Trace::AE_COMPANY);

        $msg = '';

        if ($cloneFund->getLabel() != $fund->getLabel()) {
            $msg .= '<tr><td>' . $this->translate('Fund.label.label') . '</td><td>';
            if ($cloneFund->getLabel() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneFund->getLabel();
            }
            $msg .= '</td><td>';
            if ($fund->getLabel() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $fund->getLabel();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneFund->getNumber() != $fund->getNumber()) {
            $msg .= '<tr><td>' . $this->translate('Fund.number.label') . '</td><td>';
            if ($cloneFund->getNumber() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneFund->getNumberFormated();
            }
            $msg .= '</td><td>';
            if ($fund->getNumber() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $fund->getNumberFormated();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneFund->getOtherInfos() != $fund->getOtherInfos()) {
            $msg .= '<tr><td>' . $this->translate('Fund.otherInfos.label') . '</td><td>';
            if ($cloneFund->getOtherInfos() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneFund->getOtherInfos();
            }
            $msg .= '</td><td>';
            if ($fund->getOtherInfos() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $fund->getOtherInfos();
            }
            $msg .= '</td></tr>';
        }

        if (\count(\array_diff($fund->getDocs()->toArray(), $cloneFund->getDocs()->toArray())) != 0 || \count(\array_diff($cloneFund->getDocs()->toArray(), $fund->getDocs()->toArray())) != 0) {
            $msg .= '<tr><td>' . $this->translate('Fund.docs.label') . '</td><td>';
            if (\count($cloneFund->getDocs()) == 0) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= '<ul>';
                foreach ($cloneFund->getDocs() as $doc) {
                    $msg .= '<li><a href="' . $this->generateUrl('_admin_doc_editGet', array(
                        'uid' => $doc->getId()
                    )) . '">' . $doc->getOriginalName() . '</a></li>';
                }
                $msg .= '<ul>';
            }
            $msg .= '</td><td>';
            if (\count($fund->getDocs()) == 0) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= '<ul>';
                foreach ($fund->getDocs() as $doc) {
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

            $trace->setMsg($this->translate('Fund.traceEdit', array(
                '%fund%' => $fund->getLabel(),
                '%company%' => $fund->getCompany()
                    ->getCorporateName()
            )) . $msg);
            $trace->setDtCrea(new \DateTime('now'));
            $em = $this->getEntityManager();
            $em->persist($trace);
            $em->flush();
        }
    }
}
