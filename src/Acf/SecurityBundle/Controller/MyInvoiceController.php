<?php
namespace Acf\SecurityBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\OnlineInvoice;
use Acf\DataBundle\Entity\OnlineInvoiceDocument;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

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

                $this->gvars['docs'] = $em->getRepository('AcfDataBundle:OnlineInvoiceDocument')->getAllByInvoice($invoice);

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

    /**
     *
     * @param string $uid
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadAction($uid)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_security_myInvoices');
        }
        $em = $this->getEntityManager();
        try {
            $invoiceDoc = $em->getRepository('AcfDataBundle:OnlineInvoiceDocument')->find($uid);

            if (null == $invoiceDoc) {
                $logger = $this->getLogger();
                $logger->addError('Document inconnu');
                $this->flashMsgSession('warning', $this->translate('InvoiceDocument.download.notfound'));
            } else {
                $invoiceDocDir = $this->getParameter('kernel.root_dir') . '/../web/res/invoiceDocuments';
                $fileName = $invoiceDoc->getFileName();

                try {
                    $dlFile = new File($invoiceDocDir . '/' . $fileName);
                    $response = new StreamedResponse(function () use ($dlFile) {
                        $handle = fopen($dlFile->getRealPath(), 'r');
                        while (!feof($handle)) {
                            $buffer = fread($handle, 1024);
                            echo $buffer;
                            flush();
                        }
                        fclose($handle);
                    });

                    $response->headers->set('Content-Type', $invoiceDoc->getMimeType());
                    $response->headers->set('Cache-Control', '');
                    $response->headers->set('Content-Length', $invoiceDoc->getSize());
                    $response->headers->set('Last-Modified', gmdate('D, d M Y H:i:s', $invoiceDoc->getDtUpdate()
                        ->getTimestamp()));
                    $fallback = $this->normalize($invoiceDoc->getOriginalName());

                    $contentDisposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $invoiceDoc->getOriginalName(), $fallback);
                    $response->headers->set('Content-Disposition', $contentDisposition);

                    return $response;
                } catch (FileNotFoundException $fnfex) {
                    $logger = $this->getLogger();
                    $logger->addError('Fichier introuvable ou autre erreur');
                    $logger->addError($fnfex->getMessage());
                    $this->flashMsgSession('error', $fnfex->getMessage());
                    $this->flashMsgSession('warning', $this->translate('InvoiceDocument.download.notfound'));
                }
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
            $this->flashMsgSession('error', $e->getMessage());
            $this->flashMsgSession('warning', $this->translate('InvoiceDocument.download.notfound'));
        }

        return $this->redirect($urlFrom);
    }
}