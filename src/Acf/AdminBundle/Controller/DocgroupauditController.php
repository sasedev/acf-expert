<?php
namespace Acf\AdminBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\Docgroupaudit;
use Acf\AdminBundle\Form\Docgroupaudit\UpdateLabelTForm as DocgroupauditUpdateLabelTForm;
use Acf\AdminBundle\Form\Docgroupaudit\UpdateOtherInfosTForm as DocgroupauditUpdateOtherInfosTForm;
use Acf\AdminBundle\Form\Docgroupaudit\UpdateDocsTForm as DocgroupauditUpdateDocsTForm;
use Acf\AdminBundle\Form\Docgroupaudit\UpdateParentTForm as DocgroupauditUpdateParentTForm;
use Acf\AdminBundle\Form\Doc\NewTForm as DocNewTForm;
use Acf\DataBundle\Entity\Doc;
use Acf\DataBundle\Entity\Trace;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class DocgroupauditController extends BaseController
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
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_company_list');
        }
        $em = $this->getEntityManager();
        try {
            $docgroupaudit = $em->getRepository('AcfDataBundle:Docgroupaudit')->find($uid);

            if (null == $docgroupaudit) {
                $this->flashMsgSession('warning', $this->translate('Docgroupaudit.delete.notfound'));
            } else {
                $em->remove($docgroupaudit);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('Docgroupaudit.delete.success', array(
                    '%docgroupaudit%' => $docgroupaudit->getLabel()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('Docgroupaudit.delete.failure'));
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
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_company_list');
        }

        $em = $this->getEntityManager();
        try {
            $docgroupaudit = $em->getRepository('AcfDataBundle:Docgroupaudit')->find($uid);

            if (null == $docgroupaudit) {
                $this->flashMsgSession('warning', $this->translate('Docgroupaudit.edit.notfound'));
            } else {
                $traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($docgroupaudit->getId(), Trace::AE_DOCGROUPAUDIT);
                $this->gvars['traces'] = array_reverse($traces);
                $docgroupauditUpdateLabelForm = $this->createForm(DocgroupauditUpdateLabelTForm::class, $docgroupaudit);
                $docgroupauditUpdateOtherInfosForm = $this->createForm(DocgroupauditUpdateOtherInfosTForm::class, $docgroupaudit);
                $docgroupauditUpdateParentForm = $this->createForm(DocgroupauditUpdateParentTForm::class, $docgroupaudit, array(
                    'selfUrl' => $docgroupaudit->getPageUrlFull(),
                    'company' => $docgroupaudit->getCompany()
                ));
                $docgroupauditUpdateDocsForm = $this->createForm(DocgroupauditUpdateDocsTForm::class, $docgroupaudit, array(
                    'company' => $docgroupaudit->getCompany()
                ));

                $doc = new Doc();
                $doc->setCompany($docgroupaudit->getCompany());
                $docNewForm = $this->createForm(DocNewTForm::class, $doc, array(
                    'company' => $docgroupaudit->getCompany()
                ));

                $this->gvars['docgroupaudit'] = $docgroupaudit;
                $this->gvars['doc'] = $doc;
                $this->gvars['DocgroupauditUpdateLabelForm'] = $docgroupauditUpdateLabelForm->createView();
                $this->gvars['DocgroupauditUpdateOtherInfosForm'] = $docgroupauditUpdateOtherInfosForm->createView();
                $this->gvars['DocgroupauditUpdateParentForm'] = $docgroupauditUpdateParentForm->createView();
                $this->gvars['DocgroupauditUpdateDocsForm'] = $docgroupauditUpdateDocsForm->createView();
                $this->gvars['DocNewForm'] = $docNewForm->createView();

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');

                $this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
                $this->getSession()->remove('stabActive');

                $this->gvars['pagetitle'] = $this->translate('pagetitle.docgroupaudit.edit', array(
                    '%docgroupaudit%' => $docgroupaudit->getLabel()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.docgroupaudit.edit.txt', array(
                    '%docgroupaudit%' => $docgroupaudit->getLabel()
                ));

                return $this->renderResponse('AcfAdminBundle:Docgroupaudit:edit.html.twig', $this->gvars);
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
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_company_list');
        }

        $em = $this->getEntityManager();
        try {
            $docgroupaudit = $em->getRepository('AcfDataBundle:Docgroupaudit')->find($uid);

            if (null == $docgroupaudit) {
                $this->flashMsgSession('warning', $this->translate('Docgroupaudit.edit.notfound'));
            } else {
                $traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($docgroupaudit->getId(), Trace::AE_DOCGROUPAUDIT);
                $this->gvars['traces'] = array_reverse($traces);
                $docgroupauditUpdateLabelForm = $this->createForm(DocgroupauditUpdateLabelTForm::class, $docgroupaudit);
                $docgroupauditUpdateOtherInfosForm = $this->createForm(DocgroupauditUpdateOtherInfosTForm::class, $docgroupaudit);
                $docgroupauditUpdateParentForm = $this->createForm(DocgroupauditUpdateParentTForm::class, $docgroupaudit, array(
                    'selfUrl' => $docgroupaudit->getPageUrlFull(),
                    'company' => $docgroupaudit->getCompany()
                ));
                $docgroupauditUpdateDocsForm = $this->createForm(DocgroupauditUpdateDocsTForm::class, $docgroupaudit, array(
                    'company' => $docgroupaudit->getCompany()
                ));

                $doc = new Doc();
                $doc->setCompany($docgroupaudit->getCompany());
                $docNewForm = $this->createForm(DocNewTForm::class, $doc, array(
                    'company' => $docgroupaudit->getCompany()
                ));

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
                $this->getSession()->remove('stabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                $cloneDocgroupaudit = clone $docgroupaudit;

                if (isset($reqData['DocgroupauditUpdateLabelForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $docgroupauditUpdateLabelForm->handleRequest($request);
                    if ($docgroupauditUpdateLabelForm->isValid()) {
                        $em->persist($docgroupaudit);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Docgroupaudit.edit.success', array(
                            '%docgroupaudit%' => $docgroupaudit->getLabel()
                        )));

                        $this->traceEntity($cloneDocgroupaudit, $docgroupaudit);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($docgroupaudit);

                        $this->flashMsgSession('error', $this->translate('Docgroupaudit.edit.failure', array(
                            '%docgroupaudit%' => $docgroupaudit->getLabel()
                        )));
                    }
                } elseif (isset($reqData['DocgroupauditUpdateOtherInfosForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $docgroupauditUpdateOtherInfosForm->handleRequest($request);
                    if ($docgroupauditUpdateOtherInfosForm->isValid()) {
                        $em->persist($docgroupaudit);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Docgroupaudit.edit.success', array(
                            '%docgroupaudit%' => $docgroupaudit->getLabel()
                        )));

                        $this->traceEntity($cloneDocgroupaudit, $docgroupaudit);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($docgroupaudit);

                        $this->flashMsgSession('error', $this->translate('Docgroupaudit.edit.failure', array(
                            '%docgroupaudit%' => $docgroupaudit->getLabel()
                        )));
                    }
                } elseif (isset($reqData['DocgroupauditUpdateParentForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $docgroupauditUpdateParentForm->handleRequest($request);
                    if ($docgroupauditUpdateParentForm->isValid()) {
                        $em->persist($docgroupaudit);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Docgroupaudit.edit.success', array(
                            '%docgroupaudit%' => $docgroupaudit->getLabel()
                        )));

                        $this->traceEntity($cloneDocgroupaudit, $docgroupaudit);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($docgroupaudit);

                        $this->flashMsgSession('error', $this->translate('Docgroupaudit.edit.failure', array(
                            '%docgroupaudit%' => $docgroupaudit->getLabel()
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
                            $doc->setCompany($docgroupaudit->getCompany());

                            $doc->setFileName($fileName);
                            $doc->setOriginalName($originalName);
                            $doc->setSize($size);
                            $doc->setMimeType($mimeType);
                            $doc->setMd5($md5);
                            $doc->setDescription($docNewForm['description']->getData());
                            $em->persist($doc);

                            $docgroupaudit->addDoc($doc);

                            $docNames .= $doc->getOriginalName() . ' ';
                        }

                        $em->persist($docgroupaudit);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Doc.add.success', array(
                            '%doc%' => $docNames
                        )));
                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        $this->traceEntity($cloneDocgroupaudit, $docgroupaudit);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($docgroupaudit);

                        $this->flashMsgSession('error', $this->translate('Doc.add.failure'));
                    }
                } elseif (isset($reqData['DocgroupauditUpdateDocsForm'])) {
                    $this->gvars['tabActive'] = 3;
                    $this->getSession()->set('tabActive', 3);
                    $this->gvars['stabActive'] = 2;
                    $this->getSession()->set('stabActive', 2);
                    $docgroupauditUpdateDocsForm->handleRequest($request);
                    if ($docgroupauditUpdateDocsForm->isValid()) {
                        $em->persist($docgroupaudit);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Docgroupaudit.edit.success', array(
                            '%docgroupaudit%' => $docgroupaudit->getLabel()
                        )));
                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        $this->traceEntity($cloneDocgroupaudit, $docgroupaudit);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($docgroupaudit);

                        $this->flashMsgSession('error', $this->translate('Docgroupaudit.edit.failure', array(
                            '%docgroupaudit%' => $docgroupaudit->getLabel()
                        )));
                    }
                }

                $this->gvars['docgroupaudit'] = $docgroupaudit;
                $this->gvars['doc'] = $doc;
                $this->gvars['DocgroupauditUpdateLabelForm'] = $docgroupauditUpdateLabelForm->createView();
                $this->gvars['DocgroupauditUpdateOtherInfosForm'] = $docgroupauditUpdateOtherInfosForm->createView();
                $this->gvars['DocgroupauditUpdateParentForm'] = $docgroupauditUpdateParentForm->createView();
                $this->gvars['DocgroupauditUpdateDocsForm'] = $docgroupauditUpdateDocsForm->createView();
                $this->gvars['DocNewForm'] = $docNewForm->createView();

                $this->gvars['pagetitle'] = $this->translate('pagetitle.docgroupaudit.edit', array(
                    '%docgroupaudit%' => $docgroupaudit->getLabel()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.docgroupaudit.edit.txt', array(
                    '%docgroupaudit%' => $docgroupaudit->getLabel()
                ));

                return $this->renderResponse('AcfAdminBundle:Docgroupaudit:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }

    /**
     *
     * @param Docgroupaudit $parent
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function childsAction(Docgroupaudit $parent)
    {
        $em = $this->getEntityManager();
        $dg = $em->getRepository('AcfDataBundle:Docgroupaudit')->find($parent);
        $this->gvars['parent'] = $dg;

        return $this->renderResponse('AcfAdminBundle:Docgroupaudit:childs.html.twig', $this->gvars);
    }

    protected function traceEntity(Docgroupaudit $cloneDocgroupaudit, Docgroupaudit $docgroupaudit)
    {
        $curUser = $this->getSecurityTokenStorage()
            ->getToken()
            ->getUser();
        $trace = new Trace();
        $trace->setActionId($docgroupaudit->getId());
        $trace->setActionType(Trace::AT_UPDATE);
        $trace->setUserId($curUser->getId());
        $trace->setCompanyId($docgroupaudit->getCompany()
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

        $trace->setActionEntity(Trace::AE_DOCGROUPAUDIT);
        $trace->setActionId2($docgroupaudit->getCompany()
        ->getId());
        $trace->setActionEntity2(Trace::AE_COMPANY);

        $msg = '';

        if ($cloneDocgroupaudit->getLabel() != $docgroupaudit->getLabel()) {
            $msg .= '<tr><td>' . $this->translate('Docgroupaudit.label.label') . '</td><td>';
            if ($cloneDocgroupaudit->getLabel() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneDocgroupaudit->getLabel();
            }
            $msg .= '</td><td>';
            if ($docgroupaudit->getLabel() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $docgroupaudit->getLabel();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneDocgroupaudit->getParent() != $docgroupaudit->getParent() && (($cloneDocgroupaudit->getParent() == null && $docgroupaudit->getParent() != null) || ($cloneDocgroupaudit->getParent() != null && $docgroupaudit->getParent() == null) || ($docgroupaudit->getParent() != null && $cloneDocgroupaudit->getParent() != null && $cloneDocgroupaudit->getParent()->getPageUrlFull() != $docgroupaudit->getParent()->getPageUrlFull()))) {
            $msg .= '<tr><td>' . $this->translate('Docgroupaudit.parent.label') . '</td><td>';
            if ($cloneDocgroupaudit->getParent() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneDocgroupaudit->getParent()->getLabel();
            }
            $msg .= '</td><td>';
            if ($docgroupaudit->getParent() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $docgroupaudit->getParent()->getLabel();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneDocgroupaudit->getOtherInfos() != $docgroupaudit->getOtherInfos()) {
            $msg .= '<tr><td>' . $this->translate('Docgroupaudit.otherInfos.label') . '</td><td>';
            if ($cloneDocgroupaudit->getOtherInfos() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneDocgroupaudit->getOtherInfos();
            }
            $msg .= '</td><td>';
            if ($docgroupaudit->getOtherInfos() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $docgroupaudit->getOtherInfos();
            }
            $msg .= '</td></tr>';
        }

        if (\count(\array_diff($docgroupaudit->getDocs()->toArray(), $cloneDocgroupaudit->getDocs()->toArray())) != 0 || \count(\array_diff($cloneDocgroupaudit->getDocs()->toArray(), $docgroupaudit->getDocs()->toArray())) != 0) {
            $msg .= '<tr><td>' . $this->translate('Docgroupaudit.docs.label') . '</td><td>';
            if (\count($cloneDocgroupaudit->getDocs()) == 0) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= '<ul>';
                foreach ($cloneDocgroupaudit->getDocs() as $doc) {
                    $msg .= '<li><a href="' . $this->generateUrl('_admin_doc_editGet', array(
                        'uid' => $doc->getId()
                    )) . '">' . $doc->getOriginalName() . '</a></li>';
                }
                $msg .= '<ul>';
            }
            $msg .= '</td><td>';
            if (\count($docgroupaudit->getDocs()) == 0) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= '<ul>';
                foreach ($docgroupaudit->getDocs() as $doc) {
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

            $trace->setMsg($this->translate('Docgroupaudit.traceEdit', array(
                '%docgroupaudit%' => $docgroupaudit->getLabel(),
                '%company%' => $docgroupaudit->getCompany()
                    ->getCorporateName()
            )) . $msg);
            $trace->setDtCrea(new \DateTime('now'));
            $em = $this->getEntityManager();
            $em->persist($trace);
            $em->flush();
        }
    }
}
