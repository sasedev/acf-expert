<?php
namespace Acf\AdminBundle\Controller;

use Acf\AdminBundle\Form\BulletinInfoContent\UpdateTitleTForm as BulletinInfoContentUpdateTitleTForm;
use Acf\AdminBundle\Form\BulletinInfoContent\UpdateTForm as BulletinInfoContentUpdateTForm;
use Sasedev\Commons\SharedBundle\Controller\BaseController;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 * @version $Id$
 * @license MIT
 */
class BulletinInfoContentController extends BaseController
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
        $this->gvars['menu_active'] = 'bulletinInfo';
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
            $urlFrom = $this->generateUrl('_admin_bulletinInfo_list');
        }
        $em = $this->getEntityManager();
        try {
            $bulletinInfoContent = $em->getRepository('AcfDataBundle:BulletinInfoContent')->find($uid);

            if (null == $bulletinInfoContent) {
                $this->flashMsgSession('warning', $this->translate('BulletinInfoContent.delete.notfound'));
            } else {
                $em->remove($bulletinInfoContent);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('BulletinInfoContent.delete.success', array(
                    '%bulletinInfoContent%' => $bulletinInfoContent->getTitle()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('BulletinInfoContent.delete.failure'));
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
            $urlFrom = $this->generateUrl('_admin_bulletinInfoContent_list');
        }

        $em = $this->getEntityManager();
        try {
            $bulletinInfoContent = $em->getRepository('AcfDataBundle:BulletinInfoContent')->find($uid);

            if (null == $bulletinInfoContent) {
                $this->flashMsgSession('warning', $this->translate('BulletinInfoContent.edit.notfound'));
            } else {
                $bulletinInfoContentUpdateTitleForm = $this->createForm(BulletinInfoContentUpdateTitleTForm::class, $bulletinInfoContent);
                $bulletinInfoContentUpdateForm = $this->createForm(BulletinInfoContentUpdateTForm::class, $bulletinInfoContent);

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');

                $this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
                $this->getSession()->remove('stabActive');

                $this->gvars['bulletinInfoContent'] = $bulletinInfoContent;

                $this->gvars['BulletinInfoContentUpdateTitleForm'] = $bulletinInfoContentUpdateTitleForm->createView();
                $this->gvars['BulletinInfoContentUpdateForm'] = $bulletinInfoContentUpdateForm->createView();

                $this->gvars['pagetitle'] = $this->translate('pagetitle.bulletinInfoContent.edit', array(
                    '%bulletinInfoContent%' => $bulletinInfoContent->getTitle()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.bulletinInfoContent.edit.txt', array(
                    '%bulletinInfoContent%' => $bulletinInfoContent->getTitle()
                ));

                return $this->renderResponse('AcfAdminBundle:BulletinInfoContent:edit.html.twig', $this->gvars);
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
            $urlFrom = $this->generateUrl('_admin_bulletinInfoContent_list');
        }

        $em = $this->getEntityManager();
        try {
            $bulletinInfoContent = $em->getRepository('AcfDataBundle:BulletinInfoContent')->find($uid);

            if (null == $bulletinInfoContent) {
                $this->flashMsgSession('warning', $this->translate('BulletinInfoContent.edit.notfound'));
            } else {
                $bulletinInfoContentUpdateTitleForm = $this->createForm(BulletinInfoContentUpdateTitleTForm::class, $bulletinInfoContent);
                $bulletinInfoContentUpdateForm = $this->createForm(BulletinInfoContentUpdateTForm::class, $bulletinInfoContent);

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
                $this->getSession()->remove('stabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                if (isset($reqData['BulletinInfoContentUpdateTitleForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $bulletinInfoContentUpdateTitleForm->handleRequest($request);
                    if ($bulletinInfoContentUpdateTitleForm->isValid()) {
                        $em->persist($bulletinInfoContent);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('BulletinInfoContent.edit.success', array(
                            '%bulletinInfoContent%' => $bulletinInfoContent->getTitle()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($bulletinInfoContent);

                        $this->flashMsgSession('error', $this->translate('BulletinInfoContent.edit.failure', array(
                            '%bulletinInfoContent%' => $bulletinInfoContent->getTitle()
                        )));
                    }
                } elseif (isset($reqData['BulletinInfoContentUpdateForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $bulletinInfoContentUpdateForm->handleRequest($request);
                    if ($bulletinInfoContentUpdateForm->isValid()) {
                        $em->persist($bulletinInfoContent);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('BulletinInfoContent.edit.success', array(
                            '%bulletinInfoContent%' => $bulletinInfoContent->getTitle()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($bulletinInfoContent);

                        $this->flashMsgSession('error', $this->translate('BulletinInfoContent.edit.failure', array(
                            '%bulletinInfoContent%' => $bulletinInfoContent->getTitle()
                        )));
                    }
                }

                $this->gvars['bulletinInfoContent'] = $bulletinInfoContent;

                $this->gvars['BulletinInfoContentUpdateTitleForm'] = $bulletinInfoContentUpdateTitleForm->createView();
                $this->gvars['BulletinInfoContentUpdateForm'] = $bulletinInfoContentUpdateForm->createView();

                $this->gvars['pagetitle'] = $this->translate('pagetitle.bulletinInfoContent.edit', array(
                    '%bulletinInfoContent%' => $bulletinInfoContent->getTitle()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.bulletinInfoContent.edit.txt', array(
                    '%bulletinInfoContent%' => $bulletinInfoContent->getTitle()
                ));

                return $this->renderResponse('AcfAdminBundle:BulletinInfoContent:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }
}
