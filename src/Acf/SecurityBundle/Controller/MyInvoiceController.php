<?php
namespace Acf\SecurityBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\OnlineInvoice;
use Acf\DataBundle\Entity\OnlineInvoiceDocument;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Acf\SecurityBundle\Form\NewInvoiceDocumentTForm as NewInvoiceDocumentTForm;

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

                $invoiceDoc = new OnlineInvoiceDocument();
                $invoiceDoc->setInvoice($invoice);
                $invoiceDoc->setVisible(OnlineInvoiceDocument::ST_OK);
                $invoiceDocumentNewForm = $this->createForm(NewInvoiceDocumentTForm::class, $invoiceDoc, array(
                    'invoice' => $invoice
                ));

                $this->gvars['invoice'] = $invoice;
                $this->gvars['InvoiceDocumentNewForm'] = $invoiceDocumentNewForm->createView();

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

    public function editPostAction($uid)
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

                $invoiceDoc = new OnlineInvoiceDocument();
                $invoiceDoc->setInvoice($invoice);
                $invoiceDoc->setVisible(OnlineInvoiceDocument::ST_OK);
                $invoiceDocumentNewForm = $this->createForm(NewInvoiceDocumentTForm::class, $invoiceDoc, array(
                    'invoice' => $invoice
                ));

                $request = $this->getRequest();
                $reqData = $request->request->all();
                if (isset($reqData['NewInvoiceDocumentForm'])) {
                    $this->gvars['tabActive'] = 3;
                    $this->getSession()->set('tabActive', 3);
                    $invoiceDocumentNewForm->handleRequest($request);
                    if ($invoiceDocumentNewForm->isValid()) {
                        $invoiceDocumentFile = $invoiceDocumentNewForm['fileName']->getData();

                        $invoiceDocumentDir = $this->getParameter('kernel.root_dir') . '/../web/res/invoiceDocuments';

                        $originalName = $invoiceDocumentFile->getClientOriginalName();
                        $fileName = sha1(uniqid(mt_rand(), true)) . '.' . strtolower($invoiceDocumentFile->getClientOriginalExtension());
                        $mimeType = $invoiceDocumentFile->getMimeType();
                        $invoiceDocumentFile->move($invoiceDocumentDir, $fileName);

                        $size = filesize($invoiceDocumentDir . '/' . $fileName);
                        $md5 = md5_file($invoiceDocumentDir . '/' . $fileName);

                        $invoiceDoc->setFileName($fileName);
                        $invoiceDoc->setOriginalName($originalName);
                        $invoiceDoc->setSize($size);
                        $invoiceDoc->setMimeType($mimeType);
                        $invoiceDoc->setMd5($md5);
                        $em->persist($invoiceDoc);
                        $em->flush();

                        $from = $this->getParameter('mail_from');
                        $fromName = $this->getParameter('mail_from_name');
                        $subject = $this->translate('_mail.invoicedoc.subject', array(
                            '%invoice%' => $invoiceDoc->getInvoice()
                                ->getRef(),
                            '%invoiceDocument%' => $invoiceDoc->getOriginalName()
                        ), 'messages');

                        $mvars = array();
                        $mvars['invoiceDoc'] = $invoiceDoc;

                        $admins = $this->getParameter('mailtos');

                        $message = \Swift_Message::newInstance();
                        $message->setFrom($from, $fromName);
                        foreach ($admins as $admin) {
                            $message->addTo($admin['email'], $admin['name']);
                        }
                        $message->setSubject($subject);
                        $message->setBody($this->renderView('AcfSecurityBundle:Mail:newdoc.html.twig', $mvars), 'text/html');
                        $this->sendmail($message);

                        $this->flashMsgSession('success', $this->translate('InvoiceDocument.add.success', array(
                            '%invoiceDocument%' => $invoiceDoc->getOriginalName()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($invoice);

                        $this->flashMsgSession('error', $this->translate('InvoiceDocument.add.failure'));
                    }
                }

                $this->gvars['invoice'] = $invoice;
                $this->gvars['InvoiceDocumentNewForm'] = $invoiceDocumentNewForm->createView();

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