<?php
namespace Acf\SecurityBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\OnlineProduct;
use Acf\DataBundle\Entity\OnlineOrder;
use Acf\SecurityBundle\Form\NewOnlineOrderTForm as NewOnlineOrderTForm;
use Acf\DataBundle\Entity\OnlineTaxe;
use Acf\DataBundle\Entity\OnlineOrderTaxe;
use Acf\DataBundle\Entity\OnlineOrderProduct;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class CartController extends BaseController
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
        $this->gvars['menu_active'] = 'shopping';
    }

    /**
     *
     * @param string $uid
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addGetAction($uid)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_security_myCart');
        }

        $em = $this->getEntityManager();
        $onlineProduct = $em->getRepository("AcfDataBundle:OnlineProduct")->find($uid);
        if (null == $onlineProduct || $onlineProduct->getLockout() == OnlineProduct::LOCKOUT_LOCKED) {
            return $this->redirect($urlFrom);
        }
        $session = $this->getSession();

        $products = $session->get("OnlineProducts", array());

        $productInCart = false;

        foreach ($products as $product) {
            if ($product == $onlineProduct->getId()) {
                $productInCart = true;
            }
        }

        if (!$productInCart) {
            $products[$uid] = $uid;
        }
        $session->set("OnlineProducts", $products);

        return $this->redirect($urlFrom);
    }

    /**
     *
     * @param string $uid
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeGetAction($uid)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_security_myCart');
        }

        $session = $this->getSession();

        $products = $session->get("OnlineProducts", array());
        unset($products[$uid]);

        $session->set("OnlineProducts", $products);

        return $this->redirect($urlFrom);
    }

    /**
     * whoamiAction
     *
     * @return Response
     */
    public function elementsAction()
    {
        $session = $this->getSession();
        $products = $session->get("OnlineProducts", array());

        $this->gvars['elements'] = \count($products);

        return $this->renderResponse('AcfSecurityBundle:Cart:elements.html.twig', $this->gvars);
    }

    /**
     * indexAction
     *
     * @return Response
     */
    public function indexAction()
    {
        $urlFrom = $this->generateUrl('_security_onlineProduct');

        $em = $this->getEntityManager();

        $session = $this->getSession();
        $request = $this->getRequest();

        $sc = $this->getSecurityTokenStorage();
        $user = $sc->getToken()->getUser();

        $products = $session->get("OnlineProducts", array());

        if (\count($products) == 0) {
            $this->flashMsgSession("warning", "Panier vide, vous devez d'abord ajouter des produits au panier");
            return $this->redirect($urlFrom);
        }

        $myProducts = array();
        foreach ($products as $prdId) {
            $myProduct = $em->getRepository("AcfDataBundle:OnlineProduct")->find($prdId);
            if (null != $myProduct && $myProduct->getLockout() == OnlineProduct::LOCKOUT_UNLOCKED) {
                $myProducts[] = $myProduct;
            }
        }

        if (\count($myProducts) == 0) {
            $this->flashMsgSession("warning", "Panier vide, vous devez d'abord ajouter des produits au panier");
            return $this->redirect($urlFrom);
        }

        $this->gvars['products'] = $myProducts;

        $order = new OnlineOrder();

        $order->setSessId($session->getId());
        $order->setIpAddr($request->getClientIp());
        $order->setStatus(OnlineOrder::ST_NEW);
        $order->setUser($user);
        $order->setOrderTo($user->getFullName());

        $orderNewForm = $this->createForm(NewOnlineOrderTForm::class, $order);
        $this->gvars['OrderNewForm'] = $orderNewForm->createView();

        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.myCart.txt');
        $this->gvars['pagetitle'] = $this->translate('pagetitle.myCart');

        return $this->renderResponse('AcfSecurityBundle:Cart:index.html.twig', $this->gvars);
    }

    /**
     * indexAction
     *
     * @return Response
     */
    public function validateAction()
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            return $this->redirect($this->generateUrl('_admin_order_addGet'));
        }

        $em = $this->getEntityManager();

        $session = $this->getSession();
        $request = $this->getRequest();

        $sc = $this->getSecurityTokenStorage();
        $user = $sc->getToken()->getUser();

        $products = $session->get("OnlineProducts", array());

        if (\count($products) == 0) {
            $this->flashMsgSession("warning", "Panier vide, vous devez d'abord ajouter des produits au panier");
            return $this->redirect($this->generateUrl('_security_onlineProduct'));
        }

        $myProducts = array();
        foreach ($products as $prdId) {
            $myProduct = $em->getRepository("AcfDataBundle:OnlineProduct")->find($prdId);
            if (null != $myProduct && $myProduct->getLockout() == OnlineProduct::LOCKOUT_UNLOCKED) {
                $myProducts[] = $myProduct;
            }
        }

        if (\count($myProducts) == 0) {
            $this->flashMsgSession("warning", "Panier vide, vous devez d'abord ajouter des produits au panier");
            return $this->redirect($this->generateUrl('_security_onlineProduct'));
        }

        $this->gvars['products'] = $myProducts;

        $order = new OnlineOrder();

        $order->setSessId($session->getId());
        $order->setIpAddr($request->getClientIp());
        $order->setStatus(OnlineOrder::ST_NEW);
        $order->setUser($user);

        $orderNewForm = $this->createForm(NewOnlineOrderTForm::class, $order);

        $reqData = $request->request->all();

        if (isset($reqData['NewOnlineOrderForm'])) {
            $orderNewForm->handleRequest($request);
            if ($orderNewForm->isValid()) {

                foreach ($myProducts as $myProduct) {
                    $oproduct = new OnlineOrderProduct($myProduct);
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

                $session->set("OnlineProducts", array());

                $from = $this->getParameter('mail_from');
                $fromName = $this->getParameter('mail_from_name');
                $subject = $this->translate('_mail.order.subject', array(
                    '%order%' => $order->getRef()
                ), 'messages');

                $mvars = array();
                $mvars['order'] = $order;

                $admins = $this->getParameter('mailtos');

                $message = \Swift_Message::newInstance();
                $message->setFrom($from, $fromName);
                foreach ($admins as $admin) {
                    $message->addTo($admin['email'], $admin['name']);
                }
                $message->setSubject($subject);
                $message->setBody($this->renderView('AcfSecurityBundle:Mail:neworder.html.twig', $mvars), 'text/html');
                $this->sendmail($message);

                return $this->redirect($this->generateUrl('_security_myOrder_editGet', array(
                    'uid' => $order->getId()
                )));
            } else {
                $this->flashMsgSession('error', $this->translate('Order.add.failure'));
            }
        } else {
            $this->flashMsgSession('error', $this->translate('Order.add.failure') . " 2");
        }
        $this->gvars['OrderNewForm'] = $orderNewForm->createView();

        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.myCart.txt');
        $this->gvars['pagetitle'] = $this->translate('pagetitle.myCart');

        return $this->renderResponse('AcfSecurityBundle:Cart:index.html.twig', $this->gvars);
    }
}