<?php
namespace Acf\ClientBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\Docgroupfiscal;
use Acf\ClientBundle\Form\Docgroupfiscal\UpdateDocsTForm as DocgroupfiscalUpdateDocsTForm;
use Acf\ClientBundle\Form\Doc\NewTForm as DocNewTForm;
use Acf\DataBundle\Entity\Doc;
use Acf\DataBundle\Entity\CompanyUser;
use Acf\DataBundle\Entity\Trace;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class DocgroupfiscalController extends BaseController
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

    public function addDocAction($uid)
    {
        $this->getSession()->set('tabActive', 3);
        $this->getSession()->set('stabActive', 1);

        return $this->redirect($this->generateUrl('_client_docgroupfiscal_editGet', array(
            'uid' => $uid
        )));
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
            $docgroupfiscal = $em->getRepository('AcfDataBundle:Docgroupfiscal')->find($uid);

            if (null == $docgroupfiscal) {
                $this->flashMsgSession('warning', $this->translate('Docgroupfiscal.edit.notfound'));
            } else {

                $sc = $this->getSecurityTokenStorage();
                $user = $sc->getToken()->getUser();

                $company = $docgroupfiscal->getCompany();

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

                $docgroupfiscalUpdateDocsForm = $this->createForm(DocgroupfiscalUpdateDocsTForm::class, $docgroupfiscal, array(
                    'company' => $docgroupfiscal->getCompany()
                ));

                $doc = new Doc();
                $doc->setCompany($docgroupfiscal->getCompany());
                $docNewForm = $this->createForm(DocNewTForm::class, $doc, array(
                    'company' => $docgroupfiscal->getCompany()
                ));

                $this->gvars['docgroupfiscal'] = $docgroupfiscal;
                $this->gvars['doc'] = $doc;
                $this->gvars['DocgroupfiscalUpdateDocsForm'] = $docgroupfiscalUpdateDocsForm->createView();
                $this->gvars['DocNewForm'] = $docNewForm->createView();

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');

                $this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
                $this->getSession()->remove('stabActive');

                $this->gvars['pagetitle'] = $this->translate('pagetitle.docgroupfiscal.edit', array(
                    '%docgroupfiscal%' => $docgroupfiscal->getLabel()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.docgroupfiscal.edit.txt', array(
                    '%docgroupfiscal%' => $docgroupfiscal->getLabel()
                ));

                return $this->renderResponse('AcfClientBundle:Docgroupfiscal:edit.html.twig', $this->gvars);
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
            $docgroupfiscal = $em->getRepository('AcfDataBundle:Docgroupfiscal')->find($uid);

            if (null == $docgroupfiscal) {
                $this->flashMsgSession('warning', $this->translate('Docgroupfiscal.edit.notfound'));
            } else {

                $sc = $this->getSecurityTokenStorage();
                $user = $sc->getToken()->getUser();

                $company = $docgroupfiscal->getCompany();

                $companyUser = $em->getRepository('AcfDataBundle:CompanyUser')->findOneBy(array(
                    'company' => $company,
                    'user' => $user
                ));
                if (null == $companyUser || $companyUser->getEditDocgroupFiscals() == CompanyUser::CANT) {
                    $this->flashMsgSession('error', $this->translate('CompanyUser.accessForbidden'));

                    return $this->redirect($this->generateUrl('_client_homepage'));
                }
                $currentMonth = date('m');
                if ($company->getCurrentMonth() != $currentMonth) {
                    $company->setCurrentMonth($currentMonth);
                    $company->setCurrentMonthDocs(0);
                    $em->persist($company);
                    $em->flush();
                }
                $this->gvars['companyUser'] = $companyUser;
                $this->gvars['menu_active'] = 'client' . $company->getId();

                $docgroupfiscalUpdateDocsForm = $this->createForm(DocgroupfiscalUpdateDocsTForm::class, $docgroupfiscal, array(
                    'company' => $docgroupfiscal->getCompany()
                ));

                $doc = new Doc();
                $doc->setCompany($docgroupfiscal->getCompany());
                $docNewForm = $this->createForm(DocNewTForm::class, $doc, array(
                    'company' => $docgroupfiscal->getCompany()
                ));

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
                $this->getSession()->remove('stabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                $cloneDocgroupfiscal = clone $docgroupfiscal;

                if (isset($reqData['DocNewForm'])) {
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
                            $doc->setCompany($docgroupfiscal->getCompany());

                            $doc->setFileName($fileName);
                            $doc->setOriginalName($originalName);
                            $doc->setSize($size);
                            $doc->setMimeType($mimeType);
                            $doc->setMd5($md5);
                            $doc->setDescription($docNewForm['description']->getData());
                            $em->persist($doc);

                            $docgroupfiscal->addDoc($doc);
                            $company->setCurrentMonthDocs($company->getCurrentMonthDocs() + 1);
                            $em->persist($company);

                            $docs[] = $doc;

                            $docNames .= $doc->getOriginalName() . ' ';
                        }

                        $em->persist($docgroupfiscal);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Doc.add.success', array(
                            '%doc%' => $docNames
                        )));
                        $this->newDocNotifyAdmin($docgroupfiscal, $docs);
                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        $this->traceEntity($cloneDocgroupfiscal, $docgroupfiscal);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($docgroupfiscal);

                        $this->flashMsgSession('error', $this->translate('Doc.add.failure'));
                    }
                } elseif (isset($reqData['DocgroupfiscalUpdateDocsForm'])) {
                    $this->gvars['tabActive'] = 3;
                    $this->getSession()->set('tabActive', 3);
                    $this->gvars['stabActive'] = 2;
                    $this->getSession()->set('stabActive', 2);
                    $docgroupfiscalUpdateDocsForm->handleRequest($request);
                    if ($docgroupfiscalUpdateDocsForm->isValid()) {
                        $em->persist($docgroupfiscal);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Docgroupfiscal.edit.success', array(
                            '%docgroupfiscal%' => $docgroupfiscal->getLabel()
                        )));
                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        $this->traceEntity($cloneDocgroupfiscal, $docgroupfiscal);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($docgroupfiscal);

                        $this->flashMsgSession('error', $this->translate('Docgroupfiscal.edit.failure', array(
                            '%docgroupfiscal%' => $docgroupfiscal->getLabel()
                        )));
                    }
                }

                $this->gvars['docgroupfiscal'] = $docgroupfiscal;
                $this->gvars['doc'] = $doc;
                $this->gvars['DocgroupfiscalUpdateDocsForm'] = $docgroupfiscalUpdateDocsForm->createView();
                $this->gvars['DocNewForm'] = $docNewForm->createView();

                $this->gvars['pagetitle'] = $this->translate('pagetitle.docgroupfiscal.edit', array(
                    '%docgroupfiscal%' => $docgroupfiscal->getLabel()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.docgroupfiscal.edit.txt', array(
                    '%docgroupfiscal%' => $docgroupfiscal->getLabel()
                ));

                return $this->renderResponse('AcfClientBundle:Docgroupfiscal:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }

    /**
     *
     * @param Docgroupfiscal $parent
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function childsAction(Docgroupfiscal $parent)
    {
        $em = $this->getEntityManager();
        $dg = $em->getRepository('AcfDataBundle:Docgroupfiscal')->find($parent);
        $this->gvars['parent'] = $dg;

        return $this->renderResponse('AcfClientBundle:Docgroupfiscal:childs.html.twig', $this->gvars);
    }

    protected function newDocNotifyAdmin(Docgroupfiscal $dg, $docs)
    {
        $from = $this->getParameter('mail_from');
        $fromName = $this->getParameter('mail_from_name');
        $subject = $this->translate('_mail.newdocs.subject', array(), 'messages');

        $user = $this->getSecurityTokenStorage()
            ->getToken()
            ->getUser();
        $company = $dg->getCompany();

        $admins = $company->getAdmins();
        if (\count($admins) != 0) {
            $mvars = array();
            $mvars['dg'] = $dg;
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
            $message->setBody($this->renderView('AcfClientBundle:Mail:Docgroupfiscalnewdoc.html.twig', $mvars), 'text/html');
            $this->sendmail($message);
        }
    }

    protected function traceEntity(Docgroupfiscal $cloneDocgroupfiscal, Docgroupfiscal $docgroupfiscal)
    {
        $curUser = $this->getSecurityTokenStorage()
            ->getToken()
            ->getUser();
        $trace = new Trace();
        $trace->setActionId($docgroupfiscal->getId());
        $trace->setActionType(Trace::AT_UPDATE);
        $trace->setUserId($curUser->getId());
        $trace->setCompanyId($docgroupfiscal->getCompany()
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

        $trace->setActionEntity(Trace::AE_DOCGROUPFISCAL);
        $trace->setActionId2($docgroupfiscal->getCompany()
            ->getId());
        $trace->setActionEntity2(Trace::AE_COMPANY);

        $msg = '';

        if ($cloneDocgroupfiscal->getLabel() != $docgroupfiscal->getLabel()) {
            $msg .= '<tr><td>' . $this->translate('Docgroupfiscal.label.label') . '</td><td>';
            if ($cloneDocgroupfiscal->getLabel() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneDocgroupfiscal->getLabel();
            }
            $msg .= '</td><td>';
            if ($docgroupfiscal->getLabel() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $docgroupfiscal->getLabel();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneDocgroupfiscal->getParent() != $docgroupfiscal->getParent() && (($cloneDocgroupfiscal->getParent() == null && $docgroupfiscal->getParent() != null) || ($cloneDocgroupfiscal->getParent() != null && $docgroupfiscal->getParent() == null) || ($docgroupfiscal->getParent() != null && $cloneDocgroupfiscal->getParent() != null && $cloneDocgroupfiscal->getParent()->getPageUrlFull() != $docgroupfiscal->getParent()->getPageUrlFull()))) {
            $msg .= '<tr><td>' . $this->translate('Docgroupfiscal.parent.label') . '</td><td>';
            if ($cloneDocgroupfiscal->getParent() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneDocgroupfiscal->getParent()->getLabel();
            }
            $msg .= '</td><td>';
            if ($docgroupfiscal->getParent() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $docgroupfiscal->getParent()->getLabel();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneDocgroupfiscal->getOtherInfos() != $docgroupfiscal->getOtherInfos()) {
            $msg .= '<tr><td>' . $this->translate('Docgroupfiscal.otherInfos.label') . '</td><td>';
            if ($cloneDocgroupfiscal->getOtherInfos() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneDocgroupfiscal->getOtherInfos();
            }
            $msg .= '</td><td>';
            if ($docgroupfiscal->getOtherInfos() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $docgroupfiscal->getOtherInfos();
            }
            $msg .= '</td></tr>';
        }

        if (\count(\array_diff($docgroupfiscal->getDocs()->toArray(), $cloneDocgroupfiscal->getDocs()->toArray())) != 0 || \count(\array_diff($cloneDocgroupfiscal->getDocs()->toArray(), $docgroupfiscal->getDocs()->toArray())) != 0) {
            $msg .= '<tr><td>' . $this->translate('Docgroupfiscal.docs.label') . '</td><td>';
            if (\count($cloneDocgroupfiscal->getDocs()) == 0) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= '<ul>';
                foreach ($cloneDocgroupfiscal->getDocs() as $doc) {
                    $msg .= '<li><a href="' . $this->generateUrl('_admin_doc_editGet', array(
                        'uid' => $doc->getId()
                    )) . '">' . $doc->getOriginalName() . '</a></li>';
                }
                $msg .= '<ul>';
            }
            $msg .= '</td><td>';
            if (\count($docgroupfiscal->getDocs()) == 0) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= '<ul>';
                foreach ($docgroupfiscal->getDocs() as $doc) {
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

            $trace->setMsg($this->translate('Docgroupfiscal.traceEdit', array(
                '%docgroupfiscal%' => $docgroupfiscal->getLabel(),
                '%company%' => $docgroupfiscal->getCompany()
                    ->getCorporateName()
            )) . $msg);
            $trace->setDtCrea(new \DateTime('now'));
            $em = $this->getEntityManager();
            $em->persist($trace);
            $em->flush();
        }
    }
}
