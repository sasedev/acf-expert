<?php
namespace Acf\AdminBundle\Controller;

use Acf\DataBundle\Entity\Order;
use Acf\AdminBundle\Form\Order\NewTForm as OrderNewTForm;
use Acf\AdminBundle\Form\Order\UpdateValTForm as OrderUpdateValTForm;
use Acf\AdminBundle\Form\Order\UpdateStatusTForm as OrderUpdateStatusTForm;
use Acf\AdminBundle\Form\Order\UpdateDescriptionTForm as OrderUpdateDescriptionTForm;
use Sasedev\Commons\SharedBundle\Controller\BaseController;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class OrderController extends BaseController
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
        $this->gvars['menu_active'] = 'order';
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
        $orders = $em->getRepository('AcfDataBundle:Order')->getAll();
        $this->gvars['orders'] = $orders;
        
        $this->gvars['smenu_active'] = 'list';
        $this->gvars['pagetitle'] = $this->translate('pagetitle.order.list');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.order.list.txt');
        
        return $this->renderResponse('AcfAdminBundle:Order:list.html.twig', $this->gvars);
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
        $order = new Order();
        $orderNewForm = $this->createForm(OrderNewTForm::class, $order);
        $this->gvars['order'] = $order;
        $this->gvars['OrderNewForm'] = $orderNewForm->createView();
        
        $this->gvars['pagetitle'] = $this->translate('pagetitle.order.add');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.order.add.txt');
        $this->gvars['smenu_active'] = 'add';
        
        return $this->renderResponse('AcfAdminBundle:Order:add.html.twig', $this->gvars);
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
            return $this->redirect($this->generateUrl('_admin_order_addGet'));
        }
        
        $order = new Order();
        $orderNewForm = $this->createForm(OrderNewTForm::class, $order);
        $this->gvars['order'] = $order;
        
        $request = $this->getRequest();
        $reqData = $request->request->all();
        
        if (isset($reqData['OrderNewForm'])) {
            $orderNewForm->handleRequest($request);
            if ($orderNewForm->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($order);
                $em->flush();
                $this->flashMsgSession('success', $this->translate('Order.add.success', array(
                    '%order%' => $order->getRef()
                )));
                
                return $this->redirect($this->generateUrl('_admin_order_editGet', array(
                    'uid' => $order->getId()
                )));
            } else {
                $this->flashMsgSession('error', $this->translate('Order.add.failure'));
            }
        }
        $this->gvars['OrderNewForm'] = $orderNewForm->createView();
        
        $this->gvars['pagetitle'] = $this->translate('pagetitle.order.add');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.order.add.txt');
        $this->gvars['smenu_active'] = 'add';
        
        return $this->renderResponse('AcfAdminBundle:Order:add.html.twig', $this->gvars);
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
            $urlFrom = $this->generateUrl('_admin_order_list');
        }
        $em = $this->getEntityManager();
        try {
            $order = $em->getRepository('AcfDataBundle:Order')->find($uid);
            
            if (null == $order) {
                $this->flashMsgSession('warning', $this->translate('Order.delete.notfound'));
            } else {
                $em->remove($order);
                $em->flush();
                
                $this->flashMsgSession('success', $this->translate('Order.delete.success', array(
                    '%order%' => $order->getRef()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
            
            $this->flashMsgSession('error', $this->translate('Order.delete.failure'));
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
            $urlFrom = $this->generateUrl('_admin_order_list');
        }
        
        $em = $this->getEntityManager();
        try {
            $order = $em->getRepository('AcfDataBundle:Order')->find($uid);
            
            if (null == $order) {
                $this->flashMsgSession('warning', $this->translate('Order.edit.notfound'));
            } else {
                $orderUpdateValForm = $this->createForm(OrderUpdateValTForm::class, $order);
                $orderUpdateStatusForm = $this->createForm(OrderUpdateStatusTForm::class, $order);
                $orderUpdateDescriptionForm = $this->createForm(OrderUpdateDescriptionTForm::class, $order);
                
                $this->gvars['order'] = $order;
                $this->gvars['OrderUpdateValForm'] = $orderUpdateValForm->createView();
                $this->gvars['OrderUpdateStatusForm'] = $orderUpdateStatusForm->createView();
                $this->gvars['OrderUpdateDescriptionForm'] = $orderUpdateDescriptionForm->createView();
                
                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');
                
                $this->gvars['pagetitle'] = $this->translate('pagetitle.order.edit', array(
                    '%order%' => $order->getRef()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.order.edit.txt', array(
                    '%order%' => $order->getRef()
                ));
                
                return $this->renderResponse('AcfAdminBundle:Order:edit.html.twig', $this->gvars);
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
            return $this->redirect($this->generateUrl('_admin_order_list'));
        }
        
        $em = $this->getEntityManager();
        try {
            $order = $em->getRepository('AcfDataBundle:Order')->find($uid);
            
            if (null == $order) {
                $this->flashMsgSession('warning', $this->translate('Order.edit.notfound'));
            } else {
                $orderUpdateValForm = $this->createForm(OrderUpdateValTForm::class, $order);
                $orderUpdateStatusForm = $this->createForm(OrderUpdateStatusTForm::class, $order);
                $orderUpdateDescriptionForm = $this->createForm(OrderUpdateDescriptionTForm::class, $order);
                
                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');
                
                $request = $this->getRequest();
                $reqData = $request->request->all();
                
                if (isset($reqData['OrderUpdateValForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $orderUpdateValForm->handleRequest($request);
                    if ($orderUpdateValForm->isValid()) {
                        $em->persist($order);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Order.edit.success', array(
                            '%order%' => $order->getRef()
                        )));
                        
                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($order);
                        
                        $this->flashMsgSession('error', $this->translate('Order.edit.failure', array(
                            '%order%' => $order->getRef()
                        )));
                    }
                } elseif (isset($reqData['OrderUpdateStatusForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $orderUpdateStatusForm->handleRequest($request);
                    if ($orderUpdateStatusForm->isValid()) {
                        $em->persist($order);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Order.edit.success', array(
                            '%order%' => $order->getRef()
                        )));
                        
                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($order);
                        
                        $this->flashMsgSession('error', $this->translate('Order.edit.failure', array(
                            '%order%' => $order->getRef()
                        )));
                    }
                } elseif (isset($reqData['OrderUpdateDescriptionForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $orderUpdateDescriptionForm->handleRequest($request);
                    if ($orderUpdateDescriptionForm->isValid()) {
                        $em->persist($order);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Order.edit.success', array(
                            '%order%' => $order->getRef()
                        )));
                        
                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($order);
                        
                        $this->flashMsgSession('error', $this->translate('Order.edit.failure', array(
                            '%order%' => $order->getRef()
                        )));
                    }
                }
                
                $this->gvars['order'] = $order;
                $this->gvars['OrderUpdateValForm'] = $orderUpdateValForm->createView();
                $this->gvars['OrderUpdateStatusForm'] = $orderUpdateStatusForm->createView();
                $this->gvars['OrderUpdateDescriptionForm'] = $orderUpdateDescriptionForm->createView();
                
                $this->gvars['pagetitle'] = $this->translate('pagetitle.order.edit', array(
                    '%order%' => $order->getRef()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.order.edit.txt', array(
                    '%order%' => $order->getRef()
                ));
                
                return $this->renderResponse('AcfAdminBundle:Order:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }
        
        return $this->redirect($urlFrom);
    }
}
