<?php
namespace Acf\SecurityBundle\Controller;

use Acf\SecurityBundle\Form\CancelOrderTForm as CancelOrderTForm;
use Acf\SecurityBundle\Form\OnlineOrderSetTypeCHECKTForm as OnlineOrderSetTypeCHECKTForm;
use Acf\SecurityBundle\Form\OnlineOrderSetTypeMONEYTForm as OnlineOrderSetTypeMONEYTForm;
use Acf\SecurityBundle\Form\OnlineOrderSetTypeVRTTForm as OnlineOrderSetTypeVRTTForm;
use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\OnlineOrder;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class MyOrderController extends BaseController
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

  public function indexAction()
  {
    $sc = $this->getSecurityTokenStorage();
    $user = $sc->getToken()->getUser();

    $em = $this->getEntityManager();

    $orders = $em->getRepository('AcfDataBundle:OnlineOrder')->getAllByUser($user);
    $this->gvars['orders'] = $orders;

    $this->gvars['pagetitle'] = $this->translate('pagetitle.myorder.list');
    $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.myorder.list.txt');

    return $this->renderResponse('AcfSecurityBundle:MyOrder:list.html.twig', $this->gvars);
  }

  public function editGetAction($uid)
  {
    $urlFrom = $this->getReferer();
    if (null == $urlFrom || trim($urlFrom) == '') {
      $urlFrom = $this->generateUrl('_security_myOrders');
    }
    $sc = $this->getSecurityTokenStorage();
    $user = $sc->getToken()->getUser();

    $em = $this->getEntityManager();
    try {
      $order = $em->getRepository('AcfDataBundle:OnlineOrder')->find($uid);

      if (null == $order || null == $order->getUser() || $order->getUser()->getId() != $user->getId()) {
        return $this->redirect($urlFrom);
      } else {

        $setTypeCHECKForm = $this->createForm(OnlineOrderSetTypeCHECKTForm::class, $order);
        $setTypeMONEYForm = $this->createForm(OnlineOrderSetTypeMONEYTForm::class, $order);
        $setTypeVRTForm = $this->createForm(OnlineOrderSetTypeVRTTForm::class, $order);
        $cancelOrderForm = $this->createForm(CancelOrderTForm::class, $order);

        $this->gvars['order'] = $order;
        $this->gvars['OnlineOrderSetTypeCHECKForm'] = $setTypeCHECKForm->createView();
        $this->gvars['OnlineOrderSetTypeMONEYForm'] = $setTypeMONEYForm->createView();
        $this->gvars['OnlineOrderSetTypeVRTForm'] = $setTypeVRTForm->createView();
        $this->gvars['CancelOrderForm'] = $cancelOrderForm->createView();

        $this->gvars['pagetitle'] = $this->translate('pagetitle.myorder.edit', array(
          '%order%' => $order->getRef()
        ));
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.myorder.edit.txt', array(
          '%order%' => $order->getRef()
        ));

        return $this->renderResponse('AcfSecurityBundle:MyOrder:edit.html.twig', $this->gvars);
      }
    } catch (\Exception $e) {
      $logger = $this->getLogger();
      $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
    }

    return $this->redirect($urlFrom);
  }

  public function editPostAction($uid)
  {
    $urlFrom = $this->getReferer();
    if (null == $urlFrom || trim($urlFrom) == '') {
      $urlFrom = $this->generateUrl('_security_myOrders');
    }
    $sc = $this->getSecurityTokenStorage();
    $user = $sc->getToken()->getUser();

    $em = $this->getEntityManager();
    try {
      $order = $em->getRepository('AcfDataBundle:OnlineOrder')->find($uid);

      if (null == $order || null == $order->getUser() || $order->getUser()->getId() != $user->getId()) {
        return $this->redirect($urlFrom);
      } else {

        $setTypeCHECKForm = $this->createForm(OnlineOrderSetTypeCHECKTForm::class, $order);
        $setTypeMONEYForm = $this->createForm(OnlineOrderSetTypeMONEYTForm::class, $order);
        $setTypeVRTForm = $this->createForm(OnlineOrderSetTypeVRTTForm::class, $order);
        $cancelOrderForm = $this->createForm(CancelOrderTForm::class, $order);

        $request = $this->getRequest();
        $reqData = $request->request->all();

        if (isset($reqData['CancelOrderForm'])) {
          $cancelOrderForm->handleRequest($request);
          if ($cancelOrderForm->isValid()) {
            if ($order->getStatus() == OnlineOrder::ST_NEW || $order->getStatus() == OnlineOrder::ST_WAITING) {
              $order->setStatus(OnlineOrder::ST_CANCELED);
              $em->persist($order);
              $em->flush();
              $this->flashMsgSession('success', $this->translate('Order.edit.success', array(
                '%order%' => $order->getId()
              )));
            } else {
              $this->flashMsgSession('error', $this->translate('Order.edit.failure', array(
                '%order%' => $order->getId()
              )));
            }

            return $this->redirect($urlFrom);
          } else {
            $em->refresh($order);

            $this->flashMsgSession('error', $this->translate('Order.edit.failure', array(
              '%order%' => $order->getId()
            )));
          }
        } elseif (isset($reqData['OnlineOrderSetTypeCHECKForm'])) {
          $setTypeCHECKForm->handleRequest($request);
          if ($setTypeCHECKForm->isValid()) {
            if ($order->getStatus() == OnlineOrder::ST_NEW || $order->getStatus() == OnlineOrder::ST_WAITING) {
              $order->setStatus(OnlineOrder::ST_WAITING);
              $order->setPaymentType(OnlineOrder::PTYPE_CHECK);
              $em->persist($order);
              $em->flush();
              $this->flashMsgSession('success', $this->translate('Order.edit.success', array(
                '%order%' => $order->getId()
              )));
            } else {
              $this->flashMsgSession('error', $this->translate('Order.edit.failure', array(
                '%order%' => $order->getId()
              )));
            }

            return $this->redirect($urlFrom);
          } else {
            $em->refresh($order);

            $this->flashMsgSession('error', $this->translate('Order.edit.failure', array(
              '%order%' => $order->getId()
            )));
          }
        } elseif (isset($reqData['OnlineOrderSetTypeMONEYForm'])) {
          $setTypeMONEYForm->handleRequest($request);
          if ($setTypeMONEYForm->isValid()) {
            if ($order->getStatus() == OnlineOrder::ST_NEW || $order->getStatus() == OnlineOrder::ST_WAITING) {
              $order->setStatus(OnlineOrder::ST_WAITING);
              $order->setPaymentType(OnlineOrder::PTYPE_MONEY);
              $em->persist($order);
              $em->flush();
              $this->flashMsgSession('success', $this->translate('Order.edit.success', array(
                '%order%' => $order->getId()
              )));
            } else {
              $this->flashMsgSession('error', $this->translate('Order.edit.failure', array(
                '%order%' => $order->getId()
              )));
            }

            return $this->redirect($urlFrom);
          } else {
            $em->refresh($order);

            $this->flashMsgSession('error', $this->translate('Order.edit.failure', array(
              '%order%' => $order->getId()
            )));
          }
        } elseif (isset($reqData['CancelOrderForm'])) {
          $setTypeVRTForm->handleRequest($request);
          if ($setTypeVRTForm->isValid()) {
            if ($order->getStatus() == OnlineOrder::ST_NEW || $order->getStatus() == OnlineOrder::ST_WAITING) {
              $order->setStatus(OnlineOrder::ST_WAITING);
              $order->setPaymentType(OnlineOrder::PTYPE_VRTY);
              $em->persist($order);
              $em->flush();
              $this->flashMsgSession('success', $this->translate('Order.edit.success', array(
                '%order%' => $order->getId()
              )));
            } else {
              $this->flashMsgSession('error', $this->translate('Order.edit.failure', array(
                '%order%' => $order->getId()
              )));
            }

            return $this->redirect($urlFrom);
          } else {
            $em->refresh($order);

            $this->flashMsgSession('error', $this->translate('Order.edit.failure', array(
              '%order%' => $order->getId()
            )));
          }
        }

        $this->gvars['order'] = $order;
        $this->gvars['OnlineOrderSetTypeCHECKForm'] = $setTypeCHECKForm->createView();
        $this->gvars['OnlineOrderSetTypeMONEYForm'] = $setTypeMONEYForm->createView();
        $this->gvars['OnlineOrderSetTypeVRTForm'] = $setTypeVRTForm->createView();
        $this->gvars['CancelOrderForm'] = $cancelOrderForm->createView();

        $this->gvars['pagetitle'] = $this->translate('pagetitle.myorder.edit', array(
          '%order%' => $order->getRef()
        ));
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.myorder.edit.txt', array(
          '%order%' => $order->getRef()
        ));

        return $this->renderResponse('AcfSecurityBundle:MyOrder:edit.html.twig', $this->gvars);
      }
    } catch (\Exception $e) {
      $logger = $this->getLogger();
      $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
    }

    return $this->redirect($urlFrom);
  }

  public function printAction($uid)
  {
    $urlFrom = $this->getReferer();
    if (null == $urlFrom || trim($urlFrom) == '') {
      $urlFrom = $this->generateUrl('_security_myOrders');
    }
    $sc = $this->getSecurityTokenStorage();
    $user = $sc->getToken()->getUser();

    $em = $this->getEntityManager();
    try {
      $order = $em->getRepository('AcfDataBundle:OnlineOrder')->find($uid);

      if (null == $order || null == $order->getUser() || $order->getUser()->getId() != $user->getId()) {
        return $this->redirect($urlFrom);
      } else {

        $this->gvars['order'] = $order;

        $this->gvars['pagetitle'] = $this->translate('pagetitle.myorder.edit', array(
          '%order%' => $order->getRef()
        ));
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.myorder.edit.txt', array(
          '%order%' => $order->getRef()
        ));

        return $this->renderResponse('AcfSecurityBundle:MyOrder:print.html.twig', $this->gvars);
      }
    } catch (\Exception $e) {
      $logger = $this->getLogger();
      $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
    }

    return $this->redirect($urlFrom);
  }
}