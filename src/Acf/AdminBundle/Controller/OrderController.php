<?php
namespace Acf\AdminBundle\Controller;

use Acf\AdminBundle\Form\Order\NewTForm as OrderNewTForm;
use Acf\AdminBundle\Form\Order\UpdateStatusTForm as OrderUpdateStatusTForm;
use Acf\AdminBundle\Form\Order\AddProductTForm as OrderAddProductTForm;
use Acf\AdminBundle\Form\Order\UpdatePaymentTypeTForm as OrderUpdatePaymentTypeTForm;
use Acf\AdminBundle\Form\Order\UpdateUserTForm as OrderUpdateUserTForm;
use Acf\AdminBundle\Form\Order\UpdateOrderToTForm as OrderUpdateOrderToTForm;
use Acf\AdminBundle\Form\Order\GenerateInvoiceTForm as OrderGenerateInvoiceTForm;
use Acf\DataBundle\Entity\OnlineInvoice;
use Acf\DataBundle\Entity\OnlineOrder;
use Acf\DataBundle\Entity\OnlineOrderProduct;
use Acf\DataBundle\Entity\OnlineOrderTaxe;
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
        $orders = $em->getRepository('AcfDataBundle:OnlineOrder')->getAll();
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
        $order = new OnlineOrder();
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

        $order = new OnlineOrder();
        $orderNewForm = $this->createForm(OrderNewTForm::class, $order);
        $this->gvars['order'] = $order;

        $request = $this->getRequest();
        $reqData = $request->request->all();

        if (isset($reqData['OrderNewForm'])) {
            $orderNewForm->handleRequest($request);
            if ($orderNewForm->isValid()) {
                $em = $this->getEntityManager();
                foreach ($orderNewForm['oproducts']->getData() as $product) {
                    $oproduct = new OnlineOrderProduct($product);
                    $order->addProduct($oproduct);
                }
                $taxes = $em->getRepository('AcfDataBundle:OnlineTaxe')->getAllVisible();
                foreach ($taxes as $taxe) {
                    $otaxe = new OnlineOrderTaxe($taxe);
                    $order->addTaxe($otaxe);
                }
                $order->updateVal();
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
            $order = $em->getRepository('AcfDataBundle:OnlineOrder')->find($uid);

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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeproductAction($uid)
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
            $orderProduct = $em->getRepository('AcfDataBundle:OnlineOrderProduct')->find($uid);

            if (null == $orderProduct) {
                $this->flashMsgSession('warning', $this->translate('OrderProduct.delete.notfound'));
            } else {
                $order = $orderProduct->getOrder();
                $em->remove($orderProduct);
                $em->flush();
                $order->updateVal();
                $em->persist($order);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('OrderProduct.delete.success', array(
                    '%orderProduct%' => $orderProduct->getLabel()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('OrderProduct.delete.failure'));
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
            $order = $em->getRepository('AcfDataBundle:OnlineOrder')->find($uid);

            if (null == $order) {
                $this->flashMsgSession('warning', $this->translate('Order.edit.notfound'));
            } else {
                $orderAddProductForm = $this->createForm(OrderAddProductTForm::class, $order, array(
                    'order' => $order
                ));
                $orderUpdateStatusForm = $this->createForm(OrderUpdateStatusTForm::class, $order);
                $orderUpdatePaymentTypeForm = $this->createForm(OrderUpdatePaymentTypeTForm::class, $order);
                $orderUpdateUserForm = $this->createForm(OrderUpdateUserTForm::class, $order);
                $orderUpdateOrderToForm = $this->createForm(OrderUpdateOrderToTForm::class, $order);
                if (null == $order->getInvoice()) {
                    $invoice = new OnlineInvoice($order);
                    $orderGenerateInvoiceForm = $this->createForm(OrderGenerateInvoiceTForm::class, $invoice);
                }

                $this->gvars['order'] = $order;
                $this->gvars['OrderUpdateStatusForm'] = $orderUpdateStatusForm->createView();
                $this->gvars['OrderAddProductForm'] = $orderAddProductForm->createView();
                $this->gvars['OrderUpdatePaymentTypeForm'] = $orderUpdatePaymentTypeForm->createView();
                $this->gvars['OrderUpdateUserForm'] = $orderUpdateUserForm->createView();
                $this->gvars['OrderUpdateOrderToForm'] = $orderUpdateOrderToForm->createView();
                if (null == $order->getInvoice()) {
                    $this->gvars['OrderGenerateInvoiceForm'] = $orderGenerateInvoiceForm->createView();
                }

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
            $order = $em->getRepository('AcfDataBundle:OnlineOrder')->find($uid);

            if (null == $order) {
                $this->flashMsgSession('warning', $this->translate('Order.edit.notfound'));
            } else {
                $orderAddProductForm = $this->createForm(OrderAddProductTForm::class, $order, array(
                    'order' => $order
                ));
                $orderUpdateStatusForm = $this->createForm(OrderUpdateStatusTForm::class, $order);
                $orderUpdatePaymentTypeForm = $this->createForm(OrderUpdatePaymentTypeTForm::class, $order);
                $orderUpdateUserForm = $this->createForm(OrderUpdateUserTForm::class, $order);
                $orderUpdateOrderToForm = $this->createForm(OrderUpdateOrderToTForm::class, $order);
                if (null == $order->getInvoice()) {
                    $invoice = new OnlineInvoice($order);
                    $orderGenerateInvoiceForm = $this->createForm(OrderGenerateInvoiceTForm::class, $invoice);
                }

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                if (isset($reqData['OrderAddProductForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $orderAddProductForm->handleRequest($request);
                    if ($orderAddProductForm->isValid()) {
                        $product = $orderAddProductForm['oproduct']->getData();
                        $oproduct = new OnlineOrderProduct($product);
                        $order->addProduct($oproduct);
                        $order->updateVal();
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
                } elseif (isset($reqData['OrderUpdatePaymentTypeForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $orderUpdatePaymentTypeForm->handleRequest($request);
                    if ($orderUpdatePaymentTypeForm->isValid()) {
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
                } elseif (isset($reqData['OrderUpdateUserForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $orderUpdateUserForm->handleRequest($request);
                    if ($orderUpdateUserForm->isValid()) {
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
                } elseif (isset($reqData['OrderUpdateOrderToForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $orderUpdateOrderToForm->handleRequest($request);
                    if ($orderUpdateOrderToForm->isValid()) {
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
                } elseif (isset($reqData['OrderGenerateInvoiceForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $orderGenerateInvoiceForm->handleRequest($request);
                    if ($orderGenerateInvoiceForm->isValid()) {
                        $em->persist($invoice);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Invoice.add.success', array(
                            '%invoice%' => $invoice->getRef()
                        )));

                        return $this->redirect($this->generateUrl('_admin_invoice_editGet', array(
                            'uid' => $invoice->getId()
                        )));
                    } else {
                        $em->refresh($order);

                        $this->flashMsgSession('error', $this->translate('Invoice.add.failure'));
                    }
                }

                $this->gvars['order'] = $order;
                $this->gvars['OrderAddProductForm'] = $orderAddProductForm->createView();
                $this->gvars['OrderUpdateStatusForm'] = $orderUpdateStatusForm->createView();
                $this->gvars['OrderUpdatePaymentTypeForm'] = $orderUpdatePaymentTypeForm->createView();
                $this->gvars['OrderUpdateUserForm'] = $orderUpdateUserForm->createView();
                $this->gvars['OrderUpdateOrderToForm'] = $orderUpdateOrderToForm->createView();
                if (null == $order->getInvoice()) {
                    $this->gvars['OrderGenerateInvoiceForm'] = $orderGenerateInvoiceForm->createView();
                }

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
    public function printAction($uid)
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
            $order = $em->getRepository('AcfDataBundle:OnlineOrder')->find($uid);

            if (null == $order) {
                $this->flashMsgSession('warning', $this->translate('Order.edit.notfound'));
            } else {

                $this->gvars['order'] = $order;

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');

                $this->gvars['pagetitle'] = $this->translate('pagetitle.order.edit', array(
                    '%order%' => $order->getRef()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.order.edit.txt', array(
                    '%order%' => $order->getRef()
                ));

                return $this->renderResponse('AcfAdminBundle:Order:print.html.twig', $this->gvars);
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
    public function sendmailAction($uid)
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
            $order = $em->getRepository('AcfDataBundle:OnlineOrder')->find($uid);

            if (null == $order) {
                $this->flashMsgSession('warning', $this->translate('Order.edit.notfound'));
            } else {

                $from = $this->getParameter('mail_from');
                $fromName = $this->getParameter('mail_from_name');
                $subject = $this->translate('_mail.order.subject', array(
                    '%order%' => $order->getRef()
                ), 'messages');
                $mvars = array();
                $mvars['order'] = $order;
                $message = \Swift_Message::newInstance();
                $message->setFrom($from, $fromName);
                $message->setTo($order->getUser()->getEmail(), $order->getUser()->getFullname());
                $message->setSubject($subject);
                $mvars['logo'] = $message->embed(\Swift_Image::fromPath($this->getParameter('kernel.root_dir') . '/../web/bundles/acfres/images/logo_acf.jpg'));
                $message->setBody($this->renderView('AcfAdminBundle:Order:mail.html.twig', $mvars), 'text/html');
                $this->sendmail($message);
                $this->flashMsgSession('success', $this->translate('Order.mail.success', array(
                    '%order%' => $order->getRef()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }
}
