<?php
namespace Acf\AdminBundle\Controller;

use Acf\DataBundle\Entity\FeedRead;
use Acf\AdminBundle\Form\FeedRead\NewTForm as FeedReadNewTForm;
use Acf\AdminBundle\Form\FeedRead\UpdateUrlTForm as FeedReadUpdateUrlTForm;
use Acf\AdminBundle\Form\FeedRead\UpdateNbrDaysTForm as FeedReadUpdateNbrDaysTForm;
use Acf\AdminBundle\Form\FeedRead\UpdateNbrItemsTForm as FeedReadUpdateNbrItemsTForm;
use Sasedev\Commons\SharedBundle\Controller\BaseController;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class FeedReadController extends BaseController
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
        $this->gvars['menu_active'] = 'feedRead';
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
        $feedReads = $em->getRepository('AcfDataBundle:FeedRead')->getAll();
        $this->gvars['feedReads'] = $feedReads;

        $this->gvars['smenu_active'] = 'list';
        $this->gvars['pagetitle'] = $this->translate('pagetitle.feedRead.list');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.feedRead.list.txt');

        return $this->renderResponse('AcfAdminBundle:FeedRead:list.html.twig', $this->gvars);
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
        $feedRead = new FeedRead();
        $feedReadNewForm = $this->createForm(FeedReadNewTForm::class, $feedRead);
        $this->gvars['feedRead'] = $feedRead;
        $this->gvars['FeedReadNewForm'] = $feedReadNewForm->createView();

        $this->gvars['pagetitle'] = $this->translate('pagetitle.feedRead.add');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.feedRead.add.txt');
        $this->gvars['smenu_active'] = 'add';

        return $this->renderResponse('AcfAdminBundle:FeedRead:add.html.twig', $this->gvars);
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
            return $this->redirect($this->generateUrl('_admin_feedRead_addGet'));
        }

        $feedRead = new FeedRead();
        $feedReadNewForm = $this->createForm(FeedReadNewTForm::class, $feedRead);
        $this->gvars['feedRead'] = $feedRead;

        $request = $this->getRequest();
        $reqData = $request->request->all();

        if (isset($reqData['FeedReadNewForm'])) {
            $feedReadNewForm->handleRequest($request);
            if ($feedReadNewForm->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($feedRead);
                $em->flush();
                $this->flashMsgSession('success', $this->translate('FeedRead.add.success', array(
                    '%feedRead%' => $feedRead->getUrl()
                )));

                return $this->redirect($this->generateUrl('_admin_feedRead_editGet', array(
                    'uid' => $feedRead->getId()
                )));
            } else {
                $this->flashMsgSession('error', $this->translate('FeedRead.add.failure'));
            }
        }
        $this->gvars['FeedReadNewForm'] = $feedReadNewForm->createView();

        $this->gvars['pagetitle'] = $this->translate('pagetitle.feedRead.add');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.feedRead.add.txt');
        $this->gvars['smenu_active'] = 'add';

        return $this->renderResponse('AcfAdminBundle:FeedRead:add.html.twig', $this->gvars);
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
            $urlFrom = $this->generateUrl('_admin_feedRead_list');
        }
        $em = $this->getEntityManager();
        try {
            $feedRead = $em->getRepository('AcfDataBundle:FeedRead')->find($uid);

            if (null == $feedRead) {
                $this->flashMsgSession('warning', $this->translate('FeedRead.delete.notfound'));
            } else {
                $em->remove($feedRead);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('FeedRead.delete.success', array(
                    '%feedRead%' => $feedRead->getUrl()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('FeedRead.delete.failure'));
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
            $urlFrom = $this->generateUrl('_admin_feedRead_list');
        }

        $em = $this->getEntityManager();
        try {
            $feedRead = $em->getRepository('AcfDataBundle:FeedRead')->find($uid);

            if (null == $feedRead) {
                $this->flashMsgSession('warning', $this->translate('FeedRead.edit.notfound'));
            } else {
                $feedReadUpdateUrlForm = $this->createForm(FeedReadUpdateUrlTForm::class, $feedRead);
                $feedReadUpdateNbrDaysForm = $this->createForm(FeedReadUpdateNbrDaysTForm::class, $feedRead);
                $feedReadUpdateNbrItemsForm = $this->createForm(FeedReadUpdateNbrItemsTForm::class, $feedRead);

                $this->gvars['feedRead'] = $feedRead;
                $this->gvars['FeedReadUpdateUrlForm'] = $feedReadUpdateUrlForm->createView();
                $this->gvars['FeedReadUpdateNbrDaysForm'] = $feedReadUpdateNbrDaysForm->createView();
                $this->gvars['FeedReadUpdateNbrItemsForm'] = $feedReadUpdateNbrItemsForm->createView();

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');

                $this->gvars['pagetitle'] = $this->translate('pagetitle.feedRead.edit', array(
                    '%feedRead%' => $feedRead->getUrl()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.feedRead.edit.txt', array(
                    '%feedRead%' => $feedRead->getUrl()
                ));

                return $this->renderResponse('AcfAdminBundle:FeedRead:edit.html.twig', $this->gvars);
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
            return $this->redirect($this->generateUrl('_admin_feedRead_list'));
        }

        $em = $this->getEntityManager();
        try {
            $feedRead = $em->getRepository('AcfDataBundle:FeedRead')->find($uid);

            if (null == $feedRead) {
                $this->flashMsgSession('warning', $this->translate('FeedRead.edit.notfound'));
            } else {
                $feedReadUpdateUrlForm = $this->createForm(FeedReadUpdateUrlTForm::class, $feedRead);
                $feedReadUpdateNbrDaysForm = $this->createForm(FeedReadUpdateNbrDaysTForm::class, $feedRead);
                $feedReadUpdateNbrItemsForm = $this->createForm(FeedReadUpdateNbrItemsTForm::class, $feedRead);

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                if (isset($reqData['FeedReadUpdateUrlForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $feedReadUpdateUrlForm->handleRequest($request);
                    if ($feedReadUpdateUrlForm->isValid()) {
                        $em->persist($feedRead);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('FeedRead.edit.success', array(
                            '%feedRead%' => $feedRead->getUrl()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($feedRead);

                        $this->flashMsgSession('error', $this->translate('FeedRead.edit.failure', array(
                            '%feedRead%' => $feedRead->getUrl()
                        )));
                    }
                } elseif (isset($reqData['FeedReadUpdateNbrDaysForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $feedReadUpdateNbrDaysForm->handleRequest($request);
                    if ($feedReadUpdateNbrDaysForm->isValid()) {
                        $em->persist($feedRead);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('FeedRead.edit.success', array(
                            '%feedRead%' => $feedRead->getUrl()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($feedRead);

                        $this->flashMsgSession('error', $this->translate('FeedRead.edit.failure', array(
                            '%feedRead%' => $feedRead->getUrl()
                        )));
                    }
                } elseif (isset($reqData['FeedReadUpdateNbrItemsForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $feedReadUpdateNbrItemsForm->handleRequest($request);
                    if ($feedReadUpdateNbrItemsForm->isValid()) {
                        $em->persist($feedRead);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('FeedRead.edit.success', array(
                            '%feedRead%' => $feedRead->getUrl()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($feedRead);

                        $this->flashMsgSession('error', $this->translate('FeedRead.edit.failure', array(
                            '%feedRead%' => $feedRead->getUrl()
                        )));
                    }
                }

                $this->gvars['feedRead'] = $feedRead;
                $this->gvars['FeedReadUpdateUrlForm'] = $feedReadUpdateUrlForm->createView();
                $this->gvars['FeedReadUpdateNbrDaysForm'] = $feedReadUpdateNbrDaysForm->createView();
                $this->gvars['FeedReadUpdateNbrItemsForm'] = $feedReadUpdateNbrItemsForm->createView();

                $this->gvars['pagetitle'] = $this->translate('pagetitle.feedRead.edit', array(
                    '%feedRead%' => $feedRead->getUrl()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.feedRead.edit.txt', array(
                    '%feedRead%' => $feedRead->getUrl()
                ));

                return $this->renderResponse('AcfAdminBundle:FeedRead:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }
}
