<?php
namespace Acf\AdminBundle\Controller;

use Acf\DataBundle\Entity\BiFolder;
use Acf\DataBundle\Entity\BiDoc;
use Acf\AdminBundle\Form\BiDoc\NewTForm as BiDocNewTForm;
use Acf\AdminBundle\Form\BiFolder\NewTForm as BiFolderNewTForm;
use Acf\AdminBundle\Form\BiFolder\UpdateTitleTForm as BiFolderUpdateTitleTForm;
use Acf\AdminBundle\Form\BiFolder\UpdateParentTForm as BiFolderUpdateParentTForm;
use Sasedev\Commons\SharedBundle\Controller\BaseController;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class BiFolderController extends BaseController
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
        $this->gvars['menu_active'] = 'biFolder';
    }

    public function addDocAction($uid)
    {
        $this->getSession()->set('tabActive', 3);
        $this->getSession()->set('stabActive', 1);

        return $this->redirect($this->generateUrl('_admin_biFolder_editGet', array(
            'uid' => $uid
        )));
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }
        $em = $this->getEntityManager();
        $biFolders = $em->getRepository('AcfDataBundle:BiFolder')->getRoots();
        $this->gvars['biFolders'] = $biFolders;

        $this->gvars['smenu_active'] = 'list';
        $this->gvars['pagetitle'] = $this->translate('pagetitle.biFolder.list');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.biFolder.list.txt');

        return $this->renderResponse('AcfAdminBundle:BiFolder:list.html.twig', $this->gvars);
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addGetAction()
    {
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }
        $biFolder = new BiFolder();
        $biFolderNewForm = $this->createForm(BiFolderNewTForm::class, $biFolder);
        $this->gvars['biFolder'] = $biFolder;
        $this->gvars['BiFolderNewForm'] = $biFolderNewForm->createView();

        $this->gvars['pagetitle'] = $this->translate('pagetitle.biFolder.add');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.biFolder.add.txt');
        $this->gvars['smenu_active'] = 'add';

        return $this->renderResponse('AcfAdminBundle:BiFolder:add.html.twig', $this->gvars);
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addPostAction()
    {
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            return $this->redirect($this->generateUrl('_admin_biFolder_addGet'));
        }

        $biFolder = new BiFolder();
        $biFolderNewForm = $this->createForm(BiFolderNewTForm::class, $biFolder);
        $this->gvars['biFolder'] = $biFolder;

        $request = $this->getRequest();
        $reqData = $request->request->all();

        if (isset($reqData['BiFolderNewForm'])) {
            $biFolderNewForm->handleRequest($request);
            if ($biFolderNewForm->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($biFolder);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('BiFolder.add.success', array(
                    '%biFolder%' => $biFolder->getTitle()
                )));

                return $this->redirect($this->generateUrl('_admin_biFolder_editGet', array(
                    'uid' => $biFolder->getId()
                )));
            } else {
                $this->flashMsgSession('error', $this->translate('BiFolder.add.failure'));
            }
        }
        $this->gvars['BiFolderNewForm'] = $biFolderNewForm->createView();

        $this->gvars['pagetitle'] = $this->translate('pagetitle.biFolder.add');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.biFolder.add.txt');
        $this->gvars['smenu_active'] = 'add';

        return $this->renderResponse('AcfAdminBundle:BiFolder:add.html.twig', $this->gvars);
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
            $urlFrom = $this->generateUrl('_admin_biFolder_list');
        }
        $em = $this->getEntityManager();
        try {
            $biFolder = $em->getRepository('AcfDataBundle:BiFolder')->find($uid);

            if (null == $biFolder) {
                $this->flashMsgSession('warning', $this->translate('BiFolder.delete.notfound'));
            } else {
                $em->remove($biFolder);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('BiFolder.delete.success', array(
                    '%biFolder%' => $biFolder->getTitle()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('BiFolder.delete.failure'));
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
            $urlFrom = $this->generateUrl('_admin_biFolder_list');
        }

        $em = $this->getEntityManager();
        try {
            $biFolder = $em->getRepository('AcfDataBundle:BiFolder')->find($uid);

            if (null == $biFolder) {
                $this->flashMsgSession('warning', $this->translate('BiFolder.edit.notfound'));
            } else {
                $biFolderUpdateTitleForm = $this->createForm(BiFolderUpdateTitleTForm::class, $biFolder);
                $biFolderUpdateParentForm = $this->createForm(BiFolderUpdateParentTForm::class, $biFolder, array(
                    'selfUrl' => $biFolder->getPageUrlFull()
                ));

                $biDoc = new BiDoc();
                $biDoc->setFolder($biFolder);
                $biDocNewForm = $this->createForm(BiDocNewTForm::class, $biDoc, array(
                    'folder' => $biFolder
                ));

                $this->gvars['biFolder'] = $biFolder;
                $this->gvars['biDoc'] = $biDoc;
                $this->gvars['BiFolderUpdateTitleForm'] = $biFolderUpdateTitleForm->createView();
                $this->gvars['BiFolderUpdateParentForm'] = $biFolderUpdateParentForm->createView();
                $this->gvars['BiDocNewForm'] = $biDocNewForm->createView();

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');
                $this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
                $this->getSession()->remove('stabActive');

                $this->gvars['pagetitle'] = $this->translate('pagetitle.biFolder.edit', array(
                    '%biFolder%' => $biFolder->getTitle()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.biFolder.edit.txt', array(
                    '%biFolder%' => $biFolder->getTitle()
                ));

                return $this->renderResponse('AcfAdminBundle:BiFolder:edit.html.twig', $this->gvars);
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
            return $this->redirect($this->generateUrl('_admin_biFolder_list'));
        }

        $em = $this->getEntityManager();
        try {
            $biFolder = $em->getRepository('AcfDataBundle:BiFolder')->find($uid);

            if (null == $biFolder) {
                $this->flashMsgSession('warning', $this->translate('BiFolder.edit.notfound'));
            } else {

                $biFolderUpdateTitleForm = $this->createForm(BiFolderUpdateTitleTForm::class, $biFolder);
                $biFolderUpdateParentForm = $this->createForm(BiFolderUpdateParentTForm::class, $biFolder, array(
                    'selfUrl' => $biFolder->getPageUrlFull()
                ));

                $biDoc = new BiDoc();
                $biDoc->setFolder($biFolder);
                $biDocNewForm = $this->createForm(BiDocNewTForm::class, $biDoc, array(
                    'folder' => $biFolder
                ));

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');
                $this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
                $this->getSession()->remove('stabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                if (isset($reqData['BiFolderUpdateTitleForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $biFolderUpdateTitleForm->handleRequest($request);
                    if ($biFolderUpdateTitleForm->isValid()) {
                        $em->persist($biFolder);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('BiFolder.edit.success', array(
                            '%biFolder%' => $biFolder->getTitle()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($biFolder);

                        $this->flashMsgSession('error', $this->translate('BiFolder.edit.failure', array(
                            '%biFolder%' => $biFolder->getTitle()
                        )));
                    }
                } elseif (isset($reqData['BiFolderUpdateParentForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $biFolderUpdateParentForm->handleRequest($request);
                    if ($biFolderUpdateParentForm->isValid()) {
                        $em->persist($biFolder);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('BiFolder.edit.success', array(
                            '%biFolder%' => $biFolder->getTitle()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($biFolder);

                        $this->flashMsgSession('error', $this->translate('BiFolder.edit.failure', array(
                            '%biFolder%' => $biFolder->getTitle()
                        )));
                    }
                } elseif (isset($reqData['BiDocNewForm'])) {
                    $this->gvars['tabActive'] = 3;
                    $this->getSession()->set('tabActive', 3);
                    $this->gvars['stabActive'] = 2;
                    $this->getSession()->set('stabActive', 2);
                    $biDocNewForm->handleRequest($request);
                    if ($biDocNewForm->isValid()) {
                        $biDocFile = $biDocNewForm['fileName']->getData();

                        $biDocDir = $this->getParameter('kernel.root_dir') . '/../web/res/biDocs';

                        $originalName = $biDocFile->getClientOriginalName();
                        $fileName = sha1(uniqid(mt_rand(), true)) . '.' . strtolower($biDocFile->getClientOriginalExtension());
                        $mimeType = $biDocFile->getMimeType();
                        $biDocFile->move($biDocDir, $fileName);

                        $size = filesize($biDocDir . '/' . $fileName);
                        $md5 = md5_file($biDocDir . '/' . $fileName);

                        $biDoc->setFileName($fileName);
                        $biDoc->setOriginalName($originalName);
                        $biDoc->setSize($size);
                        $biDoc->setMimeType($mimeType);
                        $biDoc->setMd5($md5);
                        $em->persist($biDoc);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('BiDoc.add.success', array(
                            '%biDoc%' => $biDoc->getTitle()
                        )));

                        return $this->redirect($urlFrom);
                    } else {

                        $this->gvars['stabActive'] = 1;
                        $this->getSession()->set('stabActive', 1);

                        $em->refresh($biFolder);

                        $this->flashMsgSession('error', $this->translate('BiDoc.add.failure'));
                    }
                }

                $this->gvars['biFolder'] = $biFolder;
                $this->gvars['biDoc'] = $biDoc;
                $this->gvars['BiFolderUpdateTitleForm'] = $biFolderUpdateTitleForm->createView();
                $this->gvars['BiFolderUpdateParentForm'] = $biFolderUpdateParentForm->createView();
                $this->gvars['BiDocNewForm'] = $biDocNewForm->createView();

                $this->gvars['pagetitle'] = $this->translate('pagetitle.biFolder.edit', array(
                    '%biFolder%' => $biFolder->getTitle()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.biFolder.edit.txt', array(
                    '%biFolder%' => $biFolder->getTitle()
                ));

                return $this->renderResponse('AcfAdminBundle:BiFolder:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }

    /**
     *
     * @param BiFolder $parent
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function childsAction(BiFolder $parent)
    {
        $em = $this->getEntityManager();
        $dg = $em->getRepository('AcfDataBundle:BiFolder')->find($parent);
        $this->gvars['parent'] = $dg;

        return $this->renderResponse('AcfAdminBundle:BiFolder:childs.html.twig', $this->gvars);
    }
}
