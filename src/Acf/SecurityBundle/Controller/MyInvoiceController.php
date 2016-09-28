<?php
namespace Acf\SecurityBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\OnlineInvoice;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class MyInvoiceController extends BaseController
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

    $invoices = $em->getRepository('AcfDataBundle:OnlineInvoice')->getAllByUser($user);
    $this->gvars['invoices'] = $invoices;

    $this->gvars['pagetitle'] = $this->translate('pagetitle.myinvoice.list');
    $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.myinvoice.list.txt');

    return $this->renderResponse('AcfSecurityBundle:MyInvoice:list.html.twig', $this->gvars);
  }

  public function editGetAction($uid)
  {
    $urlFrom = $this->getReferer();
    if (null == $urlFrom || trim($urlFrom) == '') {
      $urlFrom = $this->generateUrl('_security_myInvoices');
    }
    $sc = $this->getSecurityTokenStorage();
    $user = $sc->getToken()->getUser();

    $em = $this->getEntityManager();
    try {
      $invoice = $em->getRepository('AcfDataBundle:OnlineInvoice')->find($uid);

      if (null == $invoice || null == $invoice->getUser() || $invoice->getUser()->getId() != $user->getId()) {
        return $this->redirect($urlFrom);
      } else {

        $this->gvars['invoice'] = $invoice;

        $this->gvars['pagetitle'] = $this->translate('pagetitle.myinvoice.edit', array(
          '%invoice%' => $invoice->getRef()
        ));
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.myinvoice.edit.txt', array(
          '%invoice%' => $invoice->getRef()
        ));

        return $this->renderResponse('AcfSecurityBundle:MyInvoice:edit.html.twig', $this->gvars);
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
      $urlFrom = $this->generateUrl('_security_myInvoices');
    }
    $sc = $this->getSecurityTokenStorage();
    $user = $sc->getToken()->getUser();

    $em = $this->getEntityManager();
    try {
      $invoice = $em->getRepository('AcfDataBundle:OnlineInvoice')->find($uid);

      if (null == $invoice || null == $invoice->getUser() || $invoice->getUser()->getId() != $user->getId()) {
        return $this->redirect($urlFrom);
      } else {

        $this->gvars['invoice'] = $invoice;

        $this->gvars['pagetitle'] = $this->translate('pagetitle.myinvoice.edit', array(
          '%invoice%' => $invoice->getRef()
        ));
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.myinvoice.edit.txt', array(
          '%invoice%' => $invoice->getRef()
        ));

        return $this->renderResponse('AcfSecurityBundle:MyInvoice:print.html.twig', $this->gvars);
      }
    } catch (\Exception $e) {
      $logger = $this->getLogger();
      $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
    }

    return $this->redirect($urlFrom);
  }
}