<?php
namespace Acf\AdminBundle\Controller;

use Acf\DataBundle\Entity\Vat;
use Acf\AdminBundle\Form\Vat\NewTForm as VatNewTForm;
use Acf\AdminBundle\Form\Vat\UpdateTitleTForm as VatUpdateTitleTForm;
use Sasedev\Commons\SharedBundle\Controller\BaseController;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class VatController extends BaseController
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
        $this->gvars['menu_active'] = 'vat';
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
        $vats = $em->getRepository('AcfDataBundle:Vat')->getAll();
        $this->gvars['vats'] = $vats;

        $this->gvars['smenu_active'] = 'list';
        $this->gvars['pagetitle'] = $this->translate('pagetitle.vat.list');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.vat.list.txt');

        return $this->renderResponse('AcfAdminBundle:Vat:list.html.twig', $this->gvars);
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
        $vat = new Vat();
        $vatNewForm = $this->createForm(VatNewTForm::class, $vat);
        $this->gvars['vat'] = $vat;
        $this->gvars['VatNewForm'] = $vatNewForm->createView();

        $this->gvars['pagetitle'] = $this->translate('pagetitle.vat.add');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.vat.add.txt');
        $this->gvars['smenu_active'] = 'add';

        return $this->renderResponse('AcfAdminBundle:Vat:add.html.twig', $this->gvars);
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
            return $this->redirect($this->generateUrl('_admin_vat_addGet'));
        }

        $vat = new Vat();
        $vatNewForm = $this->createForm(VatNewTForm::class, $vat);

        $request = $this->getRequest();
        $reqData = $request->request->all();

        if (isset($reqData['VatNewForm'])) {
            $vatNewForm->handleRequest($request);
            if ($vatNewForm->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($vat);
                $em->flush();
                $this->flashMsgSession('success', $this->translate('Vat.add.success', array(
                    '%vat%' => $vat->getTitle()
                )));

                return $this->redirect($this->generateUrl('_admin_vat_editGet', array(
                    'uid' => $vat->getId()
                )));
            } else {
                $this->flashMsgSession('error', $this->translate('Vat.add.failure'));
            }
        }
        $this->gvars['VatNewForm'] = $vatNewForm->createView();
        $this->gvars['vat'] = $vat;

        $this->gvars['pagetitle'] = $this->translate('pagetitle.vat.add');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.vat.add.txt');
        $this->gvars['smenu_active'] = 'add';

        return $this->renderResponse('AcfAdminBundle:Vat:add.html.twig', $this->gvars);
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
            $urlFrom = $this->generateUrl('_admin_vat_list');
        }
        $em = $this->getEntityManager();
        try {
            $vat = $em->getRepository('AcfDataBundle:Vat')->find($uid);

            if (null == $vat) {
                $this->flashMsgSession('warning', $this->translate('Vat.delete.notfound'));
            } else {
                $em->remove($vat);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('Vat.delete.success', array(
                    '%vat%' => $vat->getTitle()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('Vat.delete.failure'));
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
            $urlFrom = $this->generateUrl('_admin_vat_list');
        }

        $em = $this->getEntityManager();
        try {
            $vat = $em->getRepository('AcfDataBundle:Vat')->find($uid);

            if (null == $vat) {
                $this->flashMsgSession('warning', $this->translate('Vat.edit.notfound'));
            } else {
                $vatUpdateTitleForm = $this->createForm(VatUpdateTitleTForm::class, $vat);

                $this->gvars['vat'] = $vat;
                $this->gvars['VatUpdateTitleForm'] = $vatUpdateTitleForm->createView();

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');

                $this->gvars['pagetitle'] = $this->translate('pagetitle.vat.edit', array(
                    '%vat%' => $vat->getTitle()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.vat.edit.txt', array(
                    '%vat%' => $vat->getTitle()
                ));

                return $this->renderResponse('AcfAdminBundle:Vat:edit.html.twig', $this->gvars);
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
            return $this->redirect($this->generateUrl('_admin_vat_list'));
        }

        $em = $this->getEntityManager();
        try {
            $vat = $em->getRepository('AcfDataBundle:Vat')->find($uid);

            if (null == $vat) {
                $this->flashMsgSession('warning', $this->translate('Vat.edit.notfound'));
            } else {
                $vatUpdateTitleForm = $this->createForm(VatUpdateTitleTForm::class, $vat);

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                if (isset($reqData['VatUpdateTitleForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $vatUpdateTitleForm->handleRequest($request);
                    if ($vatUpdateTitleForm->isValid()) {
                        $em->persist($vat);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Vat.edit.success', array(
                            '%vat%' => $vat->getTitle()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($vat);

                        $this->flashMsgSession('error', $this->translate('Vat.edit.failure', array(
                            '%vat%' => $vat->getTitle()
                        )));
                    }
                }

                $this->gvars['vat'] = $vat;
                $this->gvars['VatUpdateTitleForm'] = $vatUpdateTitleForm->createView();

                $this->gvars['pagetitle'] = $this->translate('pagetitle.vat.edit', array(
                    '%vat%' => $vat->getTitle()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.vat.edit.txt', array(
                    '%vat%' => $vat->getTitle()
                ));

                return $this->renderResponse('AcfAdminBundle:Vat:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }
}
