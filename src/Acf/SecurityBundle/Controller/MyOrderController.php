<?php
namespace Acf\SecurityBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;

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

        $this->gvars['order'] = $order;

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