<?php
namespace Acf\ClientBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\Docgroup;
use Acf\ClientBundle\Form\Docgroup\UpdateDocsTForm as DocgroupUpdateDocsTForm;
use Acf\ClientBundle\Form\Doc\NewTForm as DocNewTForm;
use Acf\DataBundle\Entity\Doc;
use Acf\DataBundle\Entity\CompanyUser;
use Acf\DataBundle\Entity\Trace;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class DocgroupController extends BaseController
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

        return $this->redirect($this->generateUrl('_client_docgroup_editGet', array(
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
            $docgroup = $em->getRepository('AcfDataBundle:Docgroup')->find($uid);

            if (null == $docgroup) {
                $this->flashMsgSession('warning', $this->translate('Docgroup.edit.notfound'));
            } else {

                $sc = $this->getSecurityTokenStorage();
                $user = $sc->getToken()->getUser();

                $company = $docgroup->getCompany();

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

                $docgroupUpdateDocsForm = $this->createForm(DocgroupUpdateDocsTForm::class, $docgroup, array(
                    'company' => $docgroup->getCompany()
                ));

                $doc = new Doc();
                $doc->setCompany($docgroup->getCompany());
                $docNewForm = $this->createForm(DocNewTForm::class, $doc, array(
                    'company' => $docgroup->getCompany()
                ));

                $this->gvars['docgroup'] = $docgroup;
                $this->gvars['doc'] = $doc;
                $this->gvars['DocgroupUpdateDocsForm'] = $docgroupUpdateDocsForm->createView();
                $this->gvars['DocNewForm'] = $docNewForm->createView();

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');

                $this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
                $this->getSession()->remove('stabActive');

                $this->gvars['pagetitle'] = $this->translate('pagetitle.docgroup.edit', array(
                    '%docgroup%' => $docgroup->getLabel()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.docgroup.edit.txt', array(
                    '%docgroup%' => $docgroup->getLabel()
                ));

                return $this->renderResponse('AcfClientBundle:Docgroup:edit.html.twig', $this->gvars);
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
            $docgroup = $em->getRepository('AcfDataBundle:Docgroup')->find($uid);

            if (null == $docgroup) {
                $this->flashMsgSession('warning', $this->translate('Docgroup.edit.notfound'));
            } else {

                $sc = $this->getSecurityTokenStorage();
                $user = $sc->getToken()->getUser();

                $company = $docgroup->getCompany();

                $companyUser = $em->getRepository('AcfDataBundle:CompanyUser')->findOneBy(array(
                    'company' => $company,
                    'user' => $user
                ));
                if (null == $companyUser || $companyUser->getEditDocgroupJuridics() == CompanyUser::CANT) {
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

                $docgroupUpdateDocsForm = $this->createForm(DocgroupUpdateDocsTForm::class, $docgroup, array(
                    'company' => $docgroup->getCompany()
                ));

                $doc = new Doc();
                $doc->setCompany($docgroup->getCompany());
                $docNewForm = $this->createForm(DocNewTForm::class, $doc, array(
                    'company' => $docgroup->getCompany()
                ));

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
                $this->getSession()->remove('stabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                $cloneDocgroup = clone $docgroup;

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
                            $doc->setCompany($docgroup->getCompany());

                            $doc->setFileName($fileName);
                            $doc->setOriginalName($originalName);
                            $doc->setSize($size);
                            $doc->setMimeType($mimeType);
                            $doc->setMd5($md5);
                            $doc->setDescription($docNewForm['description']->getData());
                            $em->persist($doc);

                            $docgroup->addDoc($doc);
                            $company->setCurrentMonthDocs($company->getCurrentMonthDocs() + 1);
                            $em->persist($company);

                            $docs[] = $doc;

                            $docNames .= $doc->getOriginalName() . ' ';
                        }

                        $em->persist($docgroup);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Doc.add.success', array(
                            '%doc%' => $docNames
                        )));
                        $this->newDocNotifyAdmin($docgroup, $docs);
                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        $this->traceEntity($cloneDocgroup, $docgroup);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($docgroup);

                        $this->flashMsgSession('error', $this->translate('Doc.add.failure'));
                    }
                } elseif (isset($reqData['DocgroupUpdateDocsForm'])) {
                    $this->gvars['tabActive'] = 3;
                    $this->getSession()->set('tabActive', 3);
                    $this->gvars['stabActive'] = 2;
                    $this->getSession()->set('stabActive', 2);
                    $docgroupUpdateDocsForm->handleRequest($request);
                    if ($docgroupUpdateDocsForm->isValid()) {
                        $em->persist($docgroup);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Docgroup.edit.success', array(
                            '%docgroup%' => $docgroup->getLabel()
                        )));
                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        $this->traceEntity($cloneDocgroup, $docgroup);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($docgroup);

                        $this->flashMsgSession('error', $this->translate('Docgroup.edit.failure', array(
                            '%docgroup%' => $docgroup->getLabel()
                        )));
                    }
                }

                $this->gvars['docgroup'] = $docgroup;
                $this->gvars['doc'] = $doc;
                $this->gvars['DocgroupUpdateDocsForm'] = $docgroupUpdateDocsForm->createView();
                $this->gvars['DocNewForm'] = $docNewForm->createView();

                $this->gvars['pagetitle'] = $this->translate('pagetitle.docgroup.edit', array(
                    '%docgroup%' => $docgroup->getLabel()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.docgroup.edit.txt', array(
                    '%docgroup%' => $docgroup->getLabel()
                ));

                return $this->renderResponse('AcfClientBundle:Docgroup:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }

    /**
     *
     * @param Docgroup $parent
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function childsAction(Docgroup $parent)
    {
        $em = $this->getEntityManager();
        $dg = $em->getRepository('AcfDataBundle:Docgroup')->find($parent);
        $this->gvars['parent'] = $dg;

        return $this->renderResponse('AcfClientBundle:Docgroup:childs.html.twig', $this->gvars);
    }

    protected function newDocNotifyAdmin(Docgroup $dg, $docs)
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
            $message->setBody($this->renderView('AcfClientBundle:Mail:Docgroupnewdoc.html.twig', $mvars), 'text/html');
            $this->sendmail($message);
        }
    }

    protected function traceEntity(Docgroup $cloneDocgroup, Docgroup $docgroup)
    {
        $curUser = $this->getSecurityTokenStorage()
            ->getToken()
            ->getUser();
        $trace = new Trace();
        $trace->setActionId($docgroup->getId());
        $trace->setActionType(Trace::AT_UPDATE);
        $trace->setUserId($curUser->getId());
        $trace->setCompanyId($docgroup->getCompany()
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

        $trace->setActionEntity(Trace::AE_DOCGROUP);
        $trace->setActionId2($docgroup->getCompany()
            ->getId());
        $trace->setActionEntity2(Trace::AE_COMPANY);

        $msg = '';

        if ($cloneDocgroup->getLabel() != $docgroup->getLabel()) {
            $msg .= '<tr><td>' . $this->translate('Docgroup.label.label') . '</td><td>';
            if ($cloneDocgroup->getLabel() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneDocgroup->getLabel();
            }
            $msg .= '</td><td>';
            if ($docgroup->getLabel() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $docgroup->getLabel();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneDocgroup->getParent() != $docgroup->getParent() && (($cloneDocgroup->getParent() == null && $docgroup->getParent() != null) || ($cloneDocgroup->getParent() != null && $docgroup->getParent() == null) || ($docgroup->getParent() != null && $cloneDocgroup->getParent() != null && $cloneDocgroup->getParent()->getPageUrlFull() != $docgroup->getParent()->getPageUrlFull()))) {
            $msg .= '<tr><td>' . $this->translate('Docgroup.parent.label') . '</td><td>';
            if ($cloneDocgroup->getParent() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneDocgroup->getParent()->getLabel();
            }
            $msg .= '</td><td>';
            if ($docgroup->getParent() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $docgroup->getParent()->getLabel();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneDocgroup->getOtherInfos() != $docgroup->getOtherInfos()) {
            $msg .= '<tr><td>' . $this->translate('Docgroup.otherInfos.label') . '</td><td>';
            if ($cloneDocgroup->getOtherInfos() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneDocgroup->getOtherInfos();
            }
            $msg .= '</td><td>';
            if ($docgroup->getOtherInfos() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $docgroup->getOtherInfos();
            }
            $msg .= '</td></tr>';
        }

        if (\count(\array_diff($docgroup->getDocs()->toArray(), $cloneDocgroup->getDocs()->toArray())) != 0 || \count(\array_diff($cloneDocgroup->getDocs()->toArray(), $docgroup->getDocs()->toArray())) != 0) {
            $msg .= '<tr><td>' . $this->translate('Docgroup.docs.label') . '</td><td>';
            if (\count($cloneDocgroup->getDocs()) == 0) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= '<ul>';
                foreach ($cloneDocgroup->getDocs() as $doc) {
                    $msg .= '<li><a href="' . $this->generateUrl('_admin_doc_editGet', array(
                        'uid' => $doc->getId()
                    )) . '">' . $doc->getOriginalName() . '</a></li>';
                }
                $msg .= '<ul>';
            }
            $msg .= '</td><td>';
            if (\count($docgroup->getDocs()) == 0) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= '<ul>';
                foreach ($docgroup->getDocs() as $doc) {
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

            $trace->setMsg($this->translate('Docgroup.traceEdit', array(
                '%docgroup%' => $docgroup->getLabel(),
                '%company%' => $docgroup->getCompany()
                    ->getCorporateName()
            )) . $msg);
            $trace->setDtCrea(new \DateTime('now'));
            $em = $this->getEntityManager();
            $em->persist($trace);
            $em->flush();
        }
    }
}
