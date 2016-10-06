<?php
namespace Acf\AdminBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\Docgroupfiscal;
use Acf\AdminBundle\Form\Docgroupfiscal\UpdateLabelTForm as DocgroupfiscalUpdateLabelTForm;
use Acf\AdminBundle\Form\Docgroupfiscal\UpdateOtherInfosTForm as DocgroupfiscalUpdateOtherInfosTForm;
use Acf\AdminBundle\Form\Docgroupfiscal\UpdateDocsTForm as DocgroupfiscalUpdateDocsTForm;
use Acf\AdminBundle\Form\Docgroupfiscal\UpdateParentTForm as DocgroupfiscalUpdateParentTForm;
use Acf\AdminBundle\Form\Doc\NewTForm as DocNewTForm;
use Acf\DataBundle\Entity\Doc;
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
            $docgroupfiscal = $em->getRepository('AcfDataBundle:Docgroupfiscal')->find($uid);

            if (null == $docgroupfiscal) {
                $this->flashMsgSession('warning', $this->translate('Docgroupfiscal.delete.notfound'));
            } else {
                $em->remove($docgroupfiscal);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('Docgroupfiscal.delete.success', array(
                    '%docgroupfiscal%' => $docgroupfiscal->getLabel()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('Docgroupfiscal.delete.failure'));
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
            $docgroupfiscal = $em->getRepository('AcfDataBundle:Docgroupfiscal')->find($uid);

            if (null == $docgroupfiscal) {
                $this->flashMsgSession('warning', $this->translate('Docgroupfiscal.edit.notfound'));
            } else {
                $traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($docgroupfiscal->getId(), Trace::AE_DOCGROUPFISCAL);
                $this->gvars['traces'] = array_reverse($traces);
                $docgroupfiscalUpdateLabelForm = $this->createForm(DocgroupfiscalUpdateLabelTForm::class, $docgroupfiscal);
                $docgroupfiscalUpdateOtherInfosForm = $this->createForm(DocgroupfiscalUpdateOtherInfosTForm::class, $docgroupfiscal);
                $docgroupfiscalUpdateParentForm = $this->createForm(DocgroupfiscalUpdateParentTForm::class, $docgroupfiscal, array(
                    'selfUrl' => $docgroupfiscal->getPageUrlFull(),
                    'company' => $docgroupfiscal->getCompany()
                ));
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
                $this->gvars['DocgroupfiscalUpdateLabelForm'] = $docgroupfiscalUpdateLabelForm->createView();
                $this->gvars['DocgroupfiscalUpdateOtherInfosForm'] = $docgroupfiscalUpdateOtherInfosForm->createView();
                $this->gvars['DocgroupfiscalUpdateParentForm'] = $docgroupfiscalUpdateParentForm->createView();
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

                return $this->renderResponse('AcfAdminBundle:Docgroupfiscal:edit.html.twig', $this->gvars);
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
            $docgroupfiscal = $em->getRepository('AcfDataBundle:Docgroupfiscal')->find($uid);

            if (null == $docgroupfiscal) {
                $this->flashMsgSession('warning', $this->translate('Docgroupfiscal.edit.notfound'));
            } else {
                $traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($docgroupfiscal->getId(), Trace::AE_DOCGROUPFISCAL);
                ;
                $this->gvars['traces'] = array_reverse($traces);
                $docgroupfiscalUpdateLabelForm = $this->createForm(DocgroupfiscalUpdateLabelTForm::class, $docgroupfiscal);
                $docgroupfiscalUpdateOtherInfosForm = $this->createForm(DocgroupfiscalUpdateOtherInfosTForm::class, $docgroupfiscal);
                $docgroupfiscalUpdateParentForm = $this->createForm(DocgroupfiscalUpdateParentTForm::class, $docgroupfiscal, array(
                    'selfUrl' => $docgroupfiscal->getPageUrlFull(),
                    'company' => $docgroupfiscal->getCompany()
                ));
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

                if (isset($reqData['DocgroupfiscalUpdateLabelForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $docgroupfiscalUpdateLabelForm->handleRequest($request);
                    if ($docgroupfiscalUpdateLabelForm->isValid()) {
                        $em->persist($docgroupfiscal);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Docgroupfiscal.edit.success', array(
                            '%docgroupfiscal%' => $docgroupfiscal->getLabel()
                        )));

                        $this->traceEntity($cloneDocgroupfiscal, $docgroupfiscal);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($docgroupfiscal);

                        $this->flashMsgSession('error', $this->translate('Docgroupfiscal.edit.failure', array(
                            '%docgroupfiscal%' => $docgroupfiscal->getLabel()
                        )));
                    }
                } elseif (isset($reqData['DocgroupfiscalUpdateOtherInfosForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $docgroupfiscalUpdateOtherInfosForm->handleRequest($request);
                    if ($docgroupfiscalUpdateOtherInfosForm->isValid()) {
                        $em->persist($docgroupfiscal);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Docgroupfiscal.edit.success', array(
                            '%docgroupfiscal%' => $docgroupfiscal->getLabel()
                        )));

                        $this->traceEntity($cloneDocgroupfiscal, $docgroupfiscal);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($docgroupfiscal);

                        $this->flashMsgSession('error', $this->translate('Docgroupfiscal.edit.failure', array(
                            '%docgroupfiscal%' => $docgroupfiscal->getLabel()
                        )));
                    }
                } elseif (isset($reqData['DocgroupfiscalUpdateParentForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $docgroupfiscalUpdateParentForm->handleRequest($request);
                    if ($docgroupfiscalUpdateParentForm->isValid()) {
                        $em->persist($docgroupfiscal);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Docgroupfiscal.edit.success', array(
                            '%docgroupfiscal%' => $docgroupfiscal->getLabel()
                        )));

                        $this->traceEntity($cloneDocgroupfiscal, $docgroupfiscal);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($docgroupfiscal);

                        $this->flashMsgSession('error', $this->translate('Docgroupfiscal.edit.failure', array(
                            '%docgroupfiscal%' => $docgroupfiscal->getLabel()
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
                            $doc->setCompany($docgroupfiscal->getCompany());

                            $doc->setFileName($fileName);
                            $doc->setOriginalName($originalName);
                            $doc->setSize($size);
                            $doc->setMimeType($mimeType);
                            $doc->setMd5($md5);
                            $doc->setDescription($docNewForm['description']->getData());
                            $em->persist($doc);

                            $docgroupfiscal->addDoc($doc);

                            $docNames .= $doc->getOriginalName() . ' ';
                        }

                        $em->persist($docgroupfiscal);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Doc.add.success', array(
                            '%doc%' => $docNames
                        )));
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
                $this->gvars['DocgroupfiscalUpdateLabelForm'] = $docgroupfiscalUpdateLabelForm->createView();
                $this->gvars['DocgroupfiscalUpdateOtherInfosForm'] = $docgroupfiscalUpdateOtherInfosForm->createView();
                $this->gvars['DocgroupfiscalUpdateParentForm'] = $docgroupfiscalUpdateParentForm->createView();
                $this->gvars['DocgroupfiscalUpdateDocsForm'] = $docgroupfiscalUpdateDocsForm->createView();
                $this->gvars['DocNewForm'] = $docNewForm->createView();

                $this->gvars['pagetitle'] = $this->translate('pagetitle.docgroupfiscal.edit', array(
                    '%docgroupfiscal%' => $docgroupfiscal->getLabel()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.docgroupfiscal.edit.txt', array(
                    '%docgroupfiscal%' => $docgroupfiscal->getLabel()
                ));

                return $this->renderResponse('AcfAdminBundle:Docgroupfiscal:edit.html.twig', $this->gvars);
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

        return $this->renderResponse('AcfAdminBundle:Docgroupfiscal:childs.html.twig', $this->gvars);
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
