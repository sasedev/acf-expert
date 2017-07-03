<?php
namespace Acf\SecurityBundle\Controller;

use Acf\SecurityBundle\Form\CancelOrderTForm as CancelOrderTForm;
use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\Order;

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
        $this->gvars['menu_active'] = 'profile';
    }

    /**
     *
     * @param string $uid
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editGetAction($uid)
    {
        $sc = $this->getSecurityTokenStorage();
        $user = $sc->getToken()->getUser();
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_security_profile');
        }

        $em = $this->getEntityManager();
        $session = $this->getSession();
        $request = $this->getRequest();
        $logger = $this->getLogger();

        try {
            $order = $em->getRepository('AcfDataBundle:Order')->find($uid);
            if (null != $order) {
                if ($order->getUser()->getId() == $user->getId()) {
                    if ($order->getStatus() == Order::ST_NEW) {
                        $order->setSessId($session->getId());
                        $order->setIpAddr($request->getClientIp());
                        $em->persist($order);
                        $em->flush();
                    }

                    $cancelOrderForm = $this->createForm(CancelOrderTForm::class, $order);

                    $this->gvars['order'] = $order;
                    $this->gvars['CancelOrderForm'] = $cancelOrderForm->createView();

                    $this->gvars['pagetitle'] = $this->translate('pagetitle.myorder.edit', array(
                        '%order%' => $order->getId()
                    ));
                    $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.myorder.edit.txt', array(
                        '%order%' => $order->getId()
                    ));

                    return $this->renderResponse('AcfSecurityBundle:Order:edit.html.twig', $this->gvars);
                } else {
                    $this->flashMsgSession('error', $this->translate('Order.edit.failure'));
                }
            } else {

                $this->flashMsgSession('error', $this->translate('Order.edit.notfound'));
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
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editPostAction($uid)
    {
        $sc = $this->getSecurityTokenStorage();
        $user = $sc->getToken()->getUser();
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_security_profile');
        }

        $em = $this->getEntityManager();
        try {
            $order = $em->getRepository('AcfDataBundle:Order')->find($uid);
            if (null != $order) {
                if ($order->getUser()->getId() == $user->getId()) {
                    $cancelOrderForm = $this->createForm(CancelOrderTForm::class, $order);

                    $request = $this->getRequest();
                    $reqData = $request->request->all();

                    if (isset($reqData['CancelOrderForm'])) {
                        $cancelOrderForm->handleRequest($request);
                        if ($cancelOrderForm->isValid()) {
                            if ($order->getStatus() == Order::ST_NEW || $order->getStatus() == Order::ST_WAITING) {
                                $order->setStatus(Order::ST_CANCELED);
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
                    $this->gvars['CancelOrderForm'] = $cancelOrderForm->createView();

                    $this->gvars['pagetitle'] = $this->translate('pagetitle.myorder.edit', array(
                        '%order%' => $order->getId()
                    ));
                    $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.myorder.edit.txt', array(
                        '%order%' => $order->getId()
                    ));

                    return $this->renderResponse('AcfSecurityBundle:Order:edit.html.twig', $this->gvars);
                } else {
                    $this->flashMsgSession('error', $this->translate('Order.edit.failure'));
                }
            } else {

                $this->flashMsgSession('error', $this->translate('Order.edit.notfound'));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }
}