<?php
namespace Acf\AdminBundle\Controller;

use Acf\DataBundle\Entity\LiasseFolder;
use Acf\DataBundle\Entity\LiasseDoc;
use Acf\AdminBundle\Form\LiasseDoc\NewTForm as LiasseDocNewTForm;
use Acf\AdminBundle\Form\LiasseFolder\UpdateTitleTForm as LiasseFolderUpdateTitleTForm;
use Acf\AdminBundle\Form\LiasseFolder\UpdateParentTForm as LiasseFolderUpdateParentTForm;
use Sasedev\Commons\SharedBundle\Controller\BaseController;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class LiasseFolderController extends BaseController
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
        $this->gvars['menu_active'] = 'liasseFolder';
    }

    public function addDocAction($uid)
    {
        $this->getSession()->set('tabActive', 3);
        $this->getSession()->set('stabActive', 1);

        return $this->redirect($this->generateUrl('_admin_liasseFolder_editGet', array(
            'uid' => $uid
        )));
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
            $urlFrom = $this->generateUrl('_admin_liasseFolder_list');
        }
        $em = $this->getEntityManager();
        try {
            $liasseFolder = $em->getRepository('AcfDataBundle:LiasseFolder')->find($uid);

            if (null == $liasseFolder) {
                $this->flashMsgSession('warning', $this->translate('LiasseFolder.delete.notfound'));
            } else {
                $em->remove($liasseFolder);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('LiasseFolder.delete.success', array(
                    '%liasseFolder%' => $liasseFolder->getTitle()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('LiasseFolder.delete.failure'));
        }

        return $this->redirect($urlFrom);
    }

    /**
     *
     * @param string $uid
     *
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
            $liasseFolder = $em->getRepository('AcfDataBundle:LiasseFolder')->find($uid);

            if (null == $liasseFolder) {
                $this->flashMsgSession('warning', $this->translate('LiasseFolder.edit.notfound'));
            } else {
                $liasseFolderUpdateTitleForm = $this->createForm(LiasseFolderUpdateTitleTForm::class, $liasseFolder);
                $liasseFolderUpdateParentForm = $this->createForm(LiasseFolderUpdateParentTForm::class, $liasseFolder, array(
                    'selfUrl' => $liasseFolder->getPageUrlFull()
                ));

                $liasseDoc = new LiasseDoc();
                $liasseDoc->setFolder($liasseFolder);
                $liasseDocNewForm = $this->createForm(LiasseDocNewTForm::class, $liasseDoc, array(
                    'folder' => $liasseFolder
                ));

                $this->gvars['liasseFolder'] = $liasseFolder;
                $this->gvars['liasseDoc'] = $liasseDoc;
                $this->gvars['LiasseFolderUpdateTitleForm'] = $liasseFolderUpdateTitleForm->createView();
                $this->gvars['LiasseFolderUpdateParentForm'] = $liasseFolderUpdateParentForm->createView();
                $this->gvars['LiasseDocNewForm'] = $liasseDocNewForm->createView();

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');
                $this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
                $this->getSession()->remove('stabActive');

                $this->gvars['pagetitle'] = $this->translate('pagetitle.liasseFolder.edit', array(
                    '%liasseFolder%' => $liasseFolder->getTitle()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.liasseFolder.edit.txt', array(
                    '%liasseFolder%' => $liasseFolder->getTitle()
                ));

                return $this->renderResponse('AcfAdminBundle:LiasseFolder:edit.html.twig', $this->gvars);
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
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editPostAction($uid)
    {
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            return $this->redirect($this->generateUrl('_admin_company_list'));
        }

        $em = $this->getEntityManager();
        try {
            $liasseFolder = $em->getRepository('AcfDataBundle:LiasseFolder')->find($uid);

            if (null == $liasseFolder) {
                $this->flashMsgSession('warning', $this->translate('LiasseFolder.edit.notfound'));
            } else {

                $liasseFolderUpdateTitleForm = $this->createForm(LiasseFolderUpdateTitleTForm::class, $liasseFolder);
                $liasseFolderUpdateParentForm = $this->createForm(LiasseFolderUpdateParentTForm::class, $liasseFolder, array(
                    'selfUrl' => $liasseFolder->getPageUrlFull()
                ));

                $liasseDoc = new LiasseDoc();
                $liasseDoc->setFolder($liasseFolder);
                $liasseDocNewForm = $this->createForm(LiasseDocNewTForm::class, $liasseDoc, array(
                    'folder' => $liasseFolder
                ));

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');
                $this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
                $this->getSession()->remove('stabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                if (isset($reqData['LiasseFolderUpdateTitleForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $liasseFolderUpdateTitleForm->handleRequest($request);
                    if ($liasseFolderUpdateTitleForm->isValid()) {
                        $em->persist($liasseFolder);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('LiasseFolder.edit.success', array(
                            '%liasseFolder%' => $liasseFolder->getTitle()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($liasseFolder);

                        $this->flashMsgSession('error', $this->translate('LiasseFolder.edit.failure', array(
                            '%liasseFolder%' => $liasseFolder->getTitle()
                        )));
                    }
                } elseif (isset($reqData['LiasseFolderUpdateParentForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $liasseFolderUpdateParentForm->handleRequest($request);
                    if ($liasseFolderUpdateParentForm->isValid()) {
                        $em->persist($liasseFolder);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('LiasseFolder.edit.success', array(
                            '%liasseFolder%' => $liasseFolder->getTitle()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($liasseFolder);

                        $this->flashMsgSession('error', $this->translate('LiasseFolder.edit.failure', array(
                            '%liasseFolder%' => $liasseFolder->getTitle()
                        )));
                    }
                } elseif (isset($reqData['LiasseDocNewForm'])) {
                    $this->gvars['tabActive'] = 3;
                    $this->getSession()->set('tabActive', 3);
                    $this->gvars['stabActive'] = 2;
                    $this->getSession()->set('stabActive', 2);
                    $liasseDocNewForm->handleRequest($request);
                    if ($liasseDocNewForm->isValid()) {
                        $liasseDocFile = $liasseDocNewForm['fileName']->getData();

                        $liasseDocDir = $this->getParameter('kernel.root_dir') . '/../web/res/liasseDocs';

                        $originalName = $liasseDocFile->getClientOriginalName();
                        $fileName = sha1(uniqid(mt_rand(), true)) . '.' . strtolower($liasseDocFile->getClientOriginalExtension());
                        $mimeType = $liasseDocFile->getMimeType();
                        $liasseDocFile->move($liasseDocDir, $fileName);

                        $size = filesize($liasseDocDir . '/' . $fileName);
                        $md5 = md5_file($liasseDocDir . '/' . $fileName);

                        $liasseDoc->setFileName($fileName);
                        $liasseDoc->setOriginalName($originalName);
                        $liasseDoc->setSize($size);
                        $liasseDoc->setMimeType($mimeType);
                        $liasseDoc->setMd5($md5);
                        $em->persist($liasseDoc);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('LiasseDoc.add.success', array(
                            '%liasseDoc%' => $liasseDoc->getTitle()
                        )));

                        return $this->redirect($urlFrom);
                    } else {

                        $this->gvars['stabActive'] = 1;
                        $this->getSession()->set('stabActive', 1);

                        $em->refresh($liasseFolder);

                        $this->flashMsgSession('error', $this->translate('LiasseDoc.add.failure'));
                    }
                }

                $this->gvars['liasseFolder'] = $liasseFolder;
                $this->gvars['liasseDoc'] = $liasseDoc;
                $this->gvars['LiasseFolderUpdateTitleForm'] = $liasseFolderUpdateTitleForm->createView();
                $this->gvars['LiasseFolderUpdateParentForm'] = $liasseFolderUpdateParentForm->createView();
                $this->gvars['LiasseDocNewForm'] = $liasseDocNewForm->createView();

                $this->gvars['pagetitle'] = $this->translate('pagetitle.liasseFolder.edit', array(
                    '%liasseFolder%' => $liasseFolder->getTitle()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.liasseFolder.edit.txt', array(
                    '%liasseFolder%' => $liasseFolder->getTitle()
                ));

                return $this->renderResponse('AcfAdminBundle:LiasseFolder:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }

    /**
     *
     * @param LiasseFolder $parent
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function childsAction(LiasseFolder $parent)
    {
        $em = $this->getEntityManager();
        $dg = $em->getRepository('AcfDataBundle:LiasseFolder')->find($parent);
        $this->gvars['parent'] = $dg;

        return $this->renderResponse('AcfAdminBundle:LiasseFolder:childs.html.twig', $this->gvars);
    }
}
