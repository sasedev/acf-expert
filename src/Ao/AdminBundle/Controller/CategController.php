<?php
namespace Ao\AdminBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Ao\AdminBundle\Form\AoCateg\NewTForm as AoCategNewTForm;
use Ao\AdminBundle\Form\AoCateg\UpdateTitleTForm as AoCategUpdateTitleTForm;
use Ao\AdminBundle\Form\AoCateg\UpdatePriorityTForm as AoCategUpdatePriorityTForm;
use Ao\AdminBundle\Form\AoSubCateg\NewTForm as AoSubCategNewTForm;
use Acf\DataBundle\Entity\AoCateg;
use Acf\DataBundle\Entity\AoSubCateg;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class CategController extends BaseController
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
        $this->gvars['menu_active'] = 'aocateg';
    }

    public function listAction()
    {
        $em = $this->getEntityManager();
        $categs = $em->getRepository('AcfDataBundle:AoCateg')->getAll();
        $this->gvars['categs'] = $categs;

        $this->gvars['pagetitle'] = $this->translate('pagetitle.aoCateg.list');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.aoCateg.list');

        return $this->renderResponse('AoAdminBundle:AoCateg:list.html.twig', $this->gvars);
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addGetAction()
    {
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('ao_admin_categ_list'));
        }
        $categ = new AoCateg();
        $categNewForm = $this->createForm(AoCategNewTForm::class, $categ);
        $this->gvars['categ'] = $categ;
        $this->gvars['CategNewForm'] = $categNewForm->createView();

        $this->gvars['pagetitle'] = $this->translate('pagetitle.aoCateg.add');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.aoCateg.add.txt');
        $this->gvars['smenu_active'] = 'add';

        return $this->renderResponse('AoAdminBundle:AoCateg:add.html.twig', $this->gvars);
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addPostAction()
    {
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('ao_admin_categ_list'));
        }
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            return $this->redirect($this->generateUrl('ao_admin_categ_addGet'));
        }

        $categ = new AoCateg();
        $categNewForm = $this->createForm(AoCategNewTForm::class, $categ);

        $request = $this->getRequest();
        $reqData = $request->request->all();

        if (isset($reqData['AoCategNewForm'])) {
            $categNewForm->handleRequest($request);
            if ($categNewForm->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($categ);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('AoCateg.add.success', array(
                    '%categ%' => $categ->getTitle()
                )));

                return $this->redirect($this->generateUrl('ao_admin_categ_editGet', array(
                    'uid' => $categ->getId()
                )));
            } else {
                $this->flashMsgSession('error', $this->translate('AoCateg.add.failure'));
            }
        }

        $this->gvars['categ'] = $categ;
        $this->gvars['CategNewForm'] = $categNewForm->createView();

        $this->gvars['pagetitle'] = $this->translate('pagetitle.aoCateg.add');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.aoCateg.add.txt');
        $this->gvars['smenu_active'] = 'add';

        return $this->renderResponse('AoAdminBundle:AoCateg:add.html.twig', $this->gvars);
    }

    /**
     *
     * @param string $uid
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($uid)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('ao_admin_categ_list');
        }
        $em = $this->getEntityManager();
        try {
            $categ = $em->getRepository('AcfDataBundle:AoCateg')->find($uid);

            if (null == $categ) {
                $this->flashMsgSession('warning', $this->translate('AoCateg.delete.notfound'));
            } else {
                $em->remove($categ);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('AoCateg.delete.success', array(
                    '%categ%' => $categ->getTitle()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('AoCateg.delete.failure'));
        }

        return $this->redirect($urlFrom);
    }

    /**
     *
     * @param string $uid
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editGetAction($uid)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('ao_admin_categ_list');
        }
        $em = $this->getEntityManager();
        try {
            $categ = $em->getRepository('AcfDataBundle:AoCateg')->find($uid);

            if (null == $categ) {
                $this->flashMsgSession('warning', $this->translate('AoCateg.edit.notfound'));
            } else {
                $categUpdateTitleForm = $this->createForm(AoCategUpdateTitleTForm::class, $categ);
                $categUpdatePriorityForm = $this->createForm(AoCategUpdatePriorityTForm::class, $categ);

                $subCateg = new AoSubCateg();
                $subCateg->setCateg($categ);
                $subCategNewForm = $this->createForm(AoSubCategNewTForm::class, $subCateg, array(
                    'categ' => $categ
                ));

                $this->gvars['categ'] = $categ;
                $this->gvars['subCateg'] = $subCateg;

                $this->gvars['CategUpdateTitleForm'] = $categUpdateTitleForm->createView();
                $this->gvars['CategUpdatePriorityForm'] = $categUpdatePriorityForm->createView();

                $this->gvars['SubCategNewForm'] = $subCategNewForm->createView();

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');

                $this->gvars['pagetitle'] = $this->translate('pagetitle.aoCateg.edit', array(
                    '%categ%' => $categ->getTitle()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.aoCateg.edit.txt', array(
                    '%categ%' => $categ->getTitle()
                ));

                return $this->renderResponse('AoAdminBundle:AoCateg:edit.html.twig', $this->gvars);
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editPostAction($uid)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('ao_admin_categ_list');
        }
        $em = $this->getEntityManager();
        try {
            $categ = $em->getRepository('AcfDataBundle:AoCateg')->find($uid);

            if (null == $categ) {
                $this->flashMsgSession('warning', $this->translate('AoCateg.edit.notfound'));
            } else {
                $categUpdateTitleForm = $this->createForm(AoCategUpdateTitleTForm::class, $categ);
                $categUpdatePriorityForm = $this->createForm(AoCategUpdatePriorityTForm::class, $categ);

                $subCateg = new AoSubCateg();
                $subCateg->setCateg($categ);
                $subCategNewForm = $this->createForm(AoSubCategNewTForm::class, $subCateg, array(
                    'categ' => $categ
                ));

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                if (isset($reqData['AoCategUpdateTitleForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $categUpdateTitleForm->handleRequest($request);
                    if ($categUpdateTitleForm->isValid()) {
                        $em->persist($categ);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoCateg.edit.success', array(
                            '%categ%' => $categ->getTitle()
                        )));

                        return $this->redirect($this->generateUrl('ao_admin_categ_editGet', array(
                            'uid' => $categ->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoCateg.edit.failure'));
                    }
                } elseif (isset($reqData['AoCategUpdatePriorityForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $categUpdatePriorityForm->handleRequest($request);
                    if ($categUpdatePriorityForm->isValid()) {
                        $em->persist($categ);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoCateg.edit.success', array(
                            '%categ%' => $categ->getTitle()
                        )));

                        return $this->redirect($this->generateUrl('ao_admin_categ_editGet', array(
                            'uid' => $categ->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoCateg.edit.failure'));
                    }
                } elseif (isset($reqData['AoSubCategNewForm'])) {
                    $this->gvars['tabActive'] = 3;
                    $this->getSession()->set('tabActive', 3);
                    $subCategNewForm->handleRequest($request);
                    if ($subCategNewForm->isValid()) {
                        $em->persist($subCateg);
                        $em->flush();

                        $this->gvars['tabActive'] = 1;
                        $this->getSession()->set('tabActive', 1);

                        $this->flashMsgSession('success', $this->translate('AoSubCateg.add.success', array(
                            '%subCateg%' => $subCateg->getRef()
                        )));

                        return $this->redirect($this->generateUrl('ao_admin_subcateg_editGet', array(
                            'uid' => $subCateg->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoSubCateg.add.failure'));
                    }
                }

                $this->gvars['categ'] = $categ;
                $this->gvars['subCateg'] = $subCateg;

                $this->gvars['CategUpdateTitleForm'] = $categUpdateTitleForm->createView();
                $this->gvars['CategUpdatePriorityForm'] = $categUpdatePriorityForm->createView();

                $this->gvars['SubCategNewForm'] = $subCategNewForm->createView();

                $this->gvars['pagetitle'] = $this->translate('pagetitle.aoCateg.edit', array(
                    '%categ%' => $categ->getTitle()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.aoCateg.edit.txt', array(
                    '%categ%' => $categ->getTitle()
                ));

                return $this->renderResponse('AoAdminBundle:AoCateg:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }
}

