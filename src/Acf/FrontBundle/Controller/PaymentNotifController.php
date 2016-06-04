<?php
namespace Acf\FrontBundle\Controller;


use Acf\DataBundle\Entity\Order;
use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class PaymentNotifController extends BaseController
{

    /**
     *
     * @var array
     */
    protected $gvars = array();

    /**
     *
     * @return Response
     */
    public function paymentnotifAction()
    {
        $request = $this->getRequest();

        $ipFrom = $request->getClientIp();
        $logger = $this->getLogger();
        $logger->addDebug('IP : '.$ipFrom);

        if ($ipFrom != '196.203.11.74' && $ipFrom != '196.203.11.69') {
            throw new HttpException('403', 'Access Forbiden');
        }

        $ref = $request->request->get('Reference', null);
        $actionId = $request->request->get('Action', 'DETAIL');
        $paramId = $request->request->get('Param', null);

        if (null != $ref && \trim($ref) != '') {
            $em = $this->getEntityManager();
            $order = $em->getRepository('AcfDataBundle:Order')->findOneBy(array('ref' => $ref));
            if (null == $order) {
                throw new HttpException('404', 'Unknown Order');
            } else {
                switch ($actionId) {
                    case 'DETAIL':
                        if ($order->getStatus() == Order::ST_NEW) {
                            $order->setStatus(Order::ST_WAITING);
                            $em->persist($order);
                            $em->flush();
                        }
                        $this->gvars['order'] = $order;
                        $this->gvars['action'] = Order::ST_WAITING;
                        break;
                    case 'ACCORD':
                        if ($order->getStatus() == Order::ST_NEW || $order->getStatus() == Order::ST_WAITING) {
                            $order->setStatus(Order::ST_OK);
                            // paramId à tester ?
                            $order->setAuth($paramId);
                            $em->persist($order);
                            $em->flush();
                        } elseif ($order->getStatus() != Order::ST_OK) {
                            // que faire ici ?
                            throw new HttpException('500', 'Already set Order');
                        }
                        $this->gvars['order'] = $order;
                        $this->gvars['action'] = Order::ST_OK;
                        break;
                    case 'REFUS':
                        if ($order->getStatus() == Order::ST_NEW || $order->getStatus() == Order::ST_WAITING) {
                            $order->setStatus(Order::ST_REFUSAL);
                            // paramId à tester ?
                            $order->setAuth($paramId);
                            $em->persist($order);
                            $em->flush();
                        } elseif ($order->getStatus() != Order::ST_REFUSAL) {
                            // que faire ici ?
                            throw new HttpException('500', 'Already set Order');
                        }
                        $this->gvars['order'] = $order;
                        $this->gvars['action'] = Order::ST_REFUSAL;
                        break;
                    case 'ANNULATION':
                        if ($order->getStatus() == Order::ST_NEW || $order->getStatus() == Order::ST_WAITING) {
                            $order->setStatus(Order::ST_CANCELED);
                            // paramId à tester ?
                            $order->setAuth($paramId);
                            $em->persist($order);
                            $em->flush();
                        } elseif ($order->getStatus() != Order::ST_CANCELED) {
                            // que faire ici ?
                            throw new HttpException('500', 'Already set Order');
                        }
                        $this->gvars['order'] = $order;
                        $this->gvars['action'] = Order::ST_CANCELED;
                        break;
                    case 'ERREUR':
                        if ($order->getStatus() == Order::ST_NEW || $order->getStatus() == Order::ST_WAITING) {
                            $order->setStatus(Order::ST_ERROR);
                            // paramId à tester ?
                            $order->setAuth($paramId);
                            $em->persist($order);
                            $em->flush();
                        } elseif ($order->getStatus() != Order::ST_ERROR) {
                            // que faire ici ?
                            throw new HttpException('500', 'Already set Order');
                        }
                        $this->gvars['order'] = $order;
                        $this->gvars['action'] = Order::ST_ERROR;
                        break;
                    default:
                        throw new HttpException('500', 'Wrong request');
                        break;
                }
            }
        } else {
            throw new HttpException('500', 'Wrong request');
        }

        return $this->renderResponse('AcfFrontBundle:Payment:paymentnotif.html.twig', $this->gvars);
    }
}