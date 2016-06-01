<?php
namespace Acf\AdminBundle\Controller;

use Acf\DataBundle\Entity\ConstantStr;
use Acf\AdminBundle\Form\ConstantStr\NewTForm as ConstantStrNewTForm;
use Acf\AdminBundle\Form\ConstantStr\UpdateDescriptionTForm as ConstantStrUpdateDescriptionTForm;
use Acf\AdminBundle\Form\ConstantStr\UpdateValueTForm as ConstantStrUpdateValueTForm;
use Sasedev\Commons\SharedBundle\Controller\BaseController;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class ConstantStrController extends BaseController
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
        $this->gvars['menu_active'] = 'constantStr';
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        if (!$this->hasRole('ROLE_SUPERSUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }
        $em = $this->getEntityManager();
        $constantStrs = $em->getRepository('AcfDataBundle:ConstantStr')->getAll();
        $this->gvars['constantStrs'] = $constantStrs;

        $this->gvars['smenu_active'] = 'list';
        $this->gvars['pagetitle'] = $this->translate('pagetitle.constantStr.list');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.constantStr.list.txt');

        return $this->renderResponse('AcfAdminBundle:ConstantStr:list.html.twig', $this->gvars);
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addGetAction()
    {
        if (!$this->hasRole('ROLE_SUPERSUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }
        $constantStr = new ConstantStr();
        $constantStrNewForm = $this->createForm(ConstantStrNewTForm::class, $constantStr);
        $this->gvars['constantStr'] = $constantStr;
        $this->gvars['ConstantStrNewForm'] = $constantStrNewForm->createView();

        $this->gvars['pagetitle'] = $this->translate('pagetitle.constantStr.add');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.constantStr.add.txt');
        $this->gvars['smenu_active'] = 'add';

        return $this->renderResponse('AcfAdminBundle:ConstantStr:add.html.twig', $this->gvars);
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addPostAction()
    {
        if (!$this->hasRole('ROLE_SUPERSUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            return $this->redirect($this->generateUrl('_admin_constantStr_addGet'));
        }

        $constantStr = new ConstantStr();
        $constantStrNewForm = $this->createForm(ConstantStrNewTForm::class, $constantStr);
        $this->gvars['constantStr'] = $constantStr;

        $request = $this->getRequest();
        $reqData = $request->request->all();

        if (isset($reqData['ConstantStrNewForm'])) {
            $constantStrNewForm->handleRequest($request);
            if ($constantStrNewForm->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($constantStr);
                $em->flush();
                $this->flashMsgSession('success', $this->translate('ConstantStr.add.success', array(
                    '%constantStr%' => $constantStr->getName()
                )));

                return $this->redirect($this->generateUrl('_admin_constantStr_editGet', array(
                    'uid' => $constantStr->getId()
                )));
            } else {
                $this->flashMsgSession('error', $this->translate('ConstantStr.add.failure'));
            }
        }
        $this->gvars['ConstantStrNewForm'] = $constantStrNewForm->createView();

        $this->gvars['pagetitle'] = $this->translate('pagetitle.constantStr.add');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.constantStr.add.txt');
        $this->gvars['smenu_active'] = 'add';

        return $this->renderResponse('AcfAdminBundle:ConstantStr:add.html.twig', $this->gvars);
    }

    /**
     *
     * @param string $uid
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($uid)
    {
        if (!$this->hasRole('ROLE_SUPERSUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_constantStr_list');
        }
        $em = $this->getEntityManager();
        try {
            $constantStr = $em->getRepository('AcfDataBundle:ConstantStr')->find($uid);

            if (null == $constantStr) {
                $this->flashMsgSession('warning', $this->translate('ConstantStr.delete.notfound'));
            } else {
                $em->remove($constantStr);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('ConstantStr.delete.success', array(
                    '%constantStr%' => $constantStr->getName()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('ConstantStr.delete.failure'));
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
        if (!$this->hasRole('ROLE_SUPERSUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_constantStr_list');
        }

        $em = $this->getEntityManager();
        try {
            $constantStr = $em->getRepository('AcfDataBundle:ConstantStr')->find($uid);

            if (null == $constantStr) {
                $this->flashMsgSession('warning', $this->translate('ConstantStr.edit.notfound'));
            } else {
                $constantStrUpdateDescriptionForm = $this->createForm(ConstantStrUpdateDescriptionTForm::class, $constantStr);
                $constantStrUpdateValueForm = $this->createForm(ConstantStrUpdateValueTForm::class, $constantStr);

                $this->gvars['constantStr'] = $constantStr;
                $this->gvars['ConstantStrUpdateDescriptionForm'] = $constantStrUpdateDescriptionForm->createView();
                $this->gvars['ConstantStrUpdateValueForm'] = $constantStrUpdateValueForm->createView();

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');

                $this->gvars['pagetitle'] = $this->translate('pagetitle.constantStr.edit', array(
                    '%constantStr%' => $constantStr->getName()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.constantStr.edit.txt', array(
                    '%constantStr%' => $constantStr->getName()
                ));

                return $this->renderResponse('AcfAdminBundle:ConstantStr:edit.html.twig', $this->gvars);
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
        if (!$this->hasRole('ROLE_SUPERSUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            return $this->redirect($this->generateUrl('_admin_constantStr_list'));
        }

        $em = $this->getEntityManager();
        try {
            $constantStr = $em->getRepository('AcfDataBundle:ConstantStr')->find($uid);

            if (null == $constantStr) {
                $this->flashMsgSession('warning', $this->translate('ConstantStr.edit.notfound'));
            } else {
                $constantStrUpdateDescriptionForm = $this->createForm(ConstantStrUpdateDescriptionTForm::class, $constantStr);
                $constantStrUpdateValueForm = $this->createForm(ConstantStrUpdateValueTForm::class, $constantStr);

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                if (isset($reqData['ConstantStrUpdateDescriptionForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $constantStrUpdateDescriptionForm->handleRequest($request);
                    if ($constantStrUpdateDescriptionForm->isValid()) {
                        $em->persist($constantStr);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('ConstantStr.edit.success', array(
                            '%constantStr%' => $constantStr->getName()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($constantStr);

                        $this->flashMsgSession('error', $this->translate('ConstantStr.edit.failure', array(
                            '%constantStr%' => $constantStr->getName()
                        )));
                    }
                } elseif (isset($reqData['ConstantStrUpdateValueForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $constantStrUpdateValueForm->handleRequest($request);
                    if ($constantStrUpdateValueForm->isValid()) {
                        $em->persist($constantStr);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('ConstantStr.edit.success', array(
                            '%constantStr%' => $constantStr->getName()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($constantStr);

                        $this->flashMsgSession('error', $this->translate('ConstantStr.edit.failure', array(
                            '%constantStr%' => $constantStr->getName()
                        )));
                    }
                }

                $this->gvars['constantStr'] = $constantStr;
                $this->gvars['ConstantStrUpdateDescriptionForm'] = $constantStrUpdateDescriptionForm->createView();
                $this->gvars['ConstantStrUpdateValueForm'] = $constantStrUpdateValueForm->createView();

                $this->gvars['pagetitle'] = $this->translate('pagetitle.constantStr.edit', array(
                    '%constantStr%' => $constantStr->getName()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.constantStr.edit.txt', array(
                    '%constantStr%' => $constantStr->getName()
                ));

                return $this->renderResponse('AcfAdminBundle:ConstantStr:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }
}
