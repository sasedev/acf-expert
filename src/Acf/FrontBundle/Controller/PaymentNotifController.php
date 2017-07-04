<?php
namespace Acf\FrontBundle\Controller;

use Acf\DataBundle\Entity\OnlineOrder;
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function paymentnotifAction()
    {
        $request = $this->getRequest();

        $ipFrom = $request->getClientIp();
        $logger = $this->getLogger();
        $logger->addError('IP : ' . $ipFrom);

        if ($ipFrom != '196.203.11.74' && $ipFrom != '196.203.11.69' && $ipFrom != '196.203.11.72') {
            throw new HttpException('403', 'Access Forbiden');
        }

        $logger->addError($request->getQueryString());

        $ref = $request->get('Reference', null);
        $actionId = $request->get('Action', 'DETAIL');
        $paramId = $request->get('Param', null);

        $logger->addError('$ref: ' . $ref);
        $logger->addError('$actionId: ' . $actionId);
        $logger->addError('$paramId: ' . $paramId);

        if (null != $ref && \trim($ref) != '') {
            $em = $this->getEntityManager();
            $order = $em->getRepository('AcfDataBundle:OnlineOrder')->findOneBy(array(
                'ref' => $ref
            ));
            if (null == $order) {
                throw new HttpException('404', 'Unknown Order');
            } else {
                $order->setPaymentType(OnlineOrder::PTYPE_ONLINE);
                switch ($actionId) {
                    case 'DETAIL':
                        if ($order->getStatus() == OnlineOrder::ST_NEW) {
                            $order->setStatus(OnlineOrder::ST_WAITING);
                            $em->persist($order);
                            $em->flush();
                        }
                        $this->gvars['order'] = $order;
                        $this->gvars['action'] = OnlineOrder::ST_WAITING;
                        break;
                    case 'ACCORD':
                        // if ($order->getStatus() == Order::ST_NEW || $order->getStatus() == Order::ST_WAITING) {
                        $order->setStatus(OnlineOrder::ST_OK);
                        // paramId à tester ?
                        $order->setAuth($paramId);
                        $em->persist($order);
                        $em->flush();
                        /*
                         * } elseif ($order->getStatus() != Order::ST_OK) {
                         * // que faire ici ?
                         * throw new HttpException('500', 'Already set Order');
                         * }//
                         */
                        $this->gvars['order'] = $order;
                        $this->gvars['action'] = OnlineOrder::ST_OK;
                        break;
                    case 'REFUS':
                        // if ($order->getStatus() == Order::ST_NEW || $order->getStatus() == Order::ST_WAITING) {
                        $order->setStatus(OnlineOrder::ST_REFUSAL);
                        // paramId à tester ?
                        $order->setAuth($paramId);
                        $em->persist($order);
                        $em->flush();
                        /*
                         * } elseif ($order->getStatus() != Order::ST_REFUSAL) {
                         * // que faire ici ?
                         * throw new HttpException('500', 'Already set Order');
                         * }//
                         */
                        $this->gvars['order'] = $order;
                        $this->gvars['action'] = OnlineOrder::ST_REFUSAL;
                        break;
                    case 'ANNULATION':
                        // if ($order->getStatus() == Order::ST_NEW || $order->getStatus() == Order::ST_WAITING) {
                        $order->setStatus(OnlineOrder::ST_CANCELED);
                        // paramId à tester ?
                        $order->setAuth($paramId);
                        $em->persist($order);
                        $em->flush();
                        /*
                         * } elseif ($order->getStatus() != Order::ST_CANCELED) {
                         * // que faire ici ?
                         * throw new HttpException('500', 'Already set Order');
                         * }//
                         */
                        $this->gvars['order'] = $order;
                        $this->gvars['action'] = OnlineOrder::ST_CANCELED;
                        break;
                    case 'ERREUR':
                        // if ($order->getStatus() == Order::ST_NEW || $order->getStatus() == Order::ST_WAITING) {
                        $order->setStatus(OnlineOrder::ST_ERROR);
                        // paramId à tester ?
                        $order->setAuth($paramId);
                        $em->persist($order);
                        $em->flush();
                        /*
                         * } elseif ($order->getStatus() != Order::ST_ERROR) {
                         * // que faire ici ?
                         * throw new HttpException('500', 'Already set Order');
                         * }//
                         */
                        $this->gvars['order'] = $order;
                        $this->gvars['action'] = OnlineOrder::ST_ERROR;
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