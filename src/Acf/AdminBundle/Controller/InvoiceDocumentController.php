<?php
namespace Acf\AdminBundle\Controller;

use Acf\DataBundle\Entity\OnlineInvoiceDocument;
use Acf\AdminBundle\Form\InvoiceDocument\UpdateContentTForm as InvoiceDocumentUpdateContentTForm;
use Acf\AdminBundle\Form\InvoiceDocument\UpdateVisibleTForm as InvoiceDocumentUpdateVisibleTForm;
use Acf\AdminBundle\Form\InvoiceDocument\UpdateOriginalNameTForm as InvoiceDocumentUpdateOriginalNameTForm;
use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class InvoiceDocumentController extends BaseController
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
        $this->gvars['menu_active'] = 'invoice';
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
            $urlFrom = $this->generateUrl('_admin_invoice_list');
        }
        $em = $this->getEntityManager();
        try {
            $invoiceDoc = $em->getRepository('AcfDataBundle:OnlineInvoiceDocument')->find($uid);

            if (null == $invoiceDoc) {
                $this->flashMsgSession('warning', $this->translate('InvoiceDocument.delete.notfound'));
            } else {
                $em->remove($invoiceDoc);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('InvoiceDocument.delete.success', array(
                    '%invoiceDocument%' => $invoiceDoc->getOriginalName()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('InvoiceDocument.delete.failure'));
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
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_invoice_list');
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
            $urlFrom = $this->generateUrl('_admin_invoice_list');
        }

        $em = $this->getEntityManager();
        try {
            $invoiceDocument = $em->getRepository('AcfDataBundle:OnlineInvoiceDocument')->find($uid);

            if (null == $invoiceDocument) {
                $this->flashMsgSession('warning', $this->translate('InvoiceDocument.edit.notfound'));
            } else {
                $invoiceDocumentUpdateVisibleForm = $this->createForm(InvoiceDocumentUpdateVisibleTForm::class, $invoiceDocument);
                $invoiceDocumentUpdateContentForm = $this->createForm(InvoiceDocumentUpdateContentTForm::class, $invoiceDocument);
                $invoiceDocumentUpdateOriginalNameForm = $this->createForm(InvoiceDocumentUpdateOriginalNameTForm::class, $invoiceDocument);

                $this->gvars['invoiceDocument'] = $invoiceDocument;
                $this->gvars['InvoiceDocumentUpdateVisibleForm'] = $invoiceDocumentUpdateVisibleForm->createView();
                $this->gvars['InvoiceDocumentUpdateContentForm'] = $invoiceDocumentUpdateContentForm->createView();
                $this->gvars['InvoiceDocumentUpdateOriginalNameForm'] = $invoiceDocumentUpdateOriginalNameForm->createView();

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');

                $this->gvars['pagetitle'] = $this->translate('pagetitle.invoiceDocument.edit', array(
                    '%invoiceDocument%' => $invoiceDocument->getOriginalName()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.invoiceDocument.edit.txt', array(
                    '%invoiceDocument%' => $invoiceDocument->getOriginalName()
                ));

                return $this->renderResponse('AcfAdminBundle:InvoiceDocument:edit.html.twig', $this->gvars);
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
            return $this->redirect($this->generateUrl('_admin_invoice_list'));
        }

        $em = $this->getEntityManager();
        try {
            $invoiceDocument = $em->getRepository('AcfDataBundle:OnlineInvoiceDocument')->find($uid);

            if (null == $invoiceDocument) {
                $this->flashMsgSession('warning', $this->translate('InvoiceDocument.edit.notfound'));
            } else {
                $invoiceDocumentUpdateVisibleForm = $this->createForm(InvoiceDocumentUpdateVisibleTForm::class, $invoiceDocument);
                $invoiceDocumentUpdateContentForm = $this->createForm(InvoiceDocumentUpdateContentTForm::class, $invoiceDocument);
                $invoiceDocumentUpdateOriginalNameForm = $this->createForm(InvoiceDocumentUpdateOriginalNameTForm::class, $invoiceDocument);

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                if (isset($reqData['InvoiceDocumentUpdateVisibleForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $invoiceDocumentUpdateVisibleForm->handleRequest($request);
                    if ($invoiceDocumentUpdateVisibleForm->isValid()) {
                        $em->persist($invoiceDocument);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('InvoiceDocument.edit.success', array(
                            '%invoiceDocument%' => $invoiceDocument->getOriginalName()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($invoiceDocument);

                        $this->flashMsgSession('error', $this->translate('InvoiceDocument.edit.failure', array(
                            '%invoiceDocument%' => $invoiceDocument->getOriginalName()
                        )));
                    }
                } elseif (isset($reqData['InvoiceDocumentUpdateContentForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $invoiceDocumentUpdateContentForm->handleRequest($request);
                    if ($invoiceDocumentUpdateContentForm->isValid()) {

                        $invoiceDocumentFile = $invoiceDocumentUpdateContentForm['doc']->getData();

                        $invoiceDocumentDir = $this->getParameter('kernel.root_dir') . '/../web/res/invoiceDocuments';

                        $originalName = $invoiceDocumentFile->getClientOriginalName();
                        $fileName = sha1(uniqid(mt_rand(), true)) . '.' . strtolower($invoiceDocumentFile->getClientOriginalExtension());
                        $mimeType = $invoiceDocumentFile->getMimeType();
                        $invoiceDocumentFile->move($invoiceDocumentDir, $fileName);

                        $size = filesize($invoiceDocumentDir . '/' . $fileName);
                        $md5 = md5_file($invoiceDocumentDir . '/' . $fileName);

                        $invoiceDocument->setFileName($fileName);
                        $invoiceDocument->setOriginalName($originalName);
                        $invoiceDocument->setSize($size);
                        $invoiceDocument->setMimeType($mimeType);
                        $invoiceDocument->setMd5($md5);

                        $em->persist($invoiceDocument);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('InvoiceDocument.edit.success', array(
                            '%invoiceDocument%' => $invoiceDocument->getOriginalName()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($invoiceDocument);

                        $this->flashMsgSession('error', $this->translate('InvoiceDocument.edit.failure'));
                    }
                } elseif (isset($reqData['InvoiceDocumentUpdateOriginalNameForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $invoiceDocumentUpdateOriginalNameForm->handleRequest($request);
                    if ($invoiceDocumentUpdateOriginalNameForm->isValid()) {
                        $em->persist($invoiceDocument);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('InvoiceDocument.edit.success', array(
                            '%invoiceDocument%' => $invoiceDocument->getOriginalName()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($invoiceDocument);

                        $this->flashMsgSession('error', $this->translate('InvoiceDocument.edit.failure', array(
                            '%invoiceDocument%' => $invoiceDocument->getOriginalName()
                        )));
                    }
                }

                $this->gvars['invoiceDocument'] = $invoiceDocument;
                $this->gvars['InvoiceDocumentUpdateVisibleForm'] = $invoiceDocumentUpdateVisibleForm->createView();
                $this->gvars['InvoiceDocumentUpdateContentForm'] = $invoiceDocumentUpdateContentForm->createView();
                $this->gvars['InvoiceDocumentUpdateOriginalNameForm'] = $invoiceDocumentUpdateOriginalNameForm->createView();

                $this->gvars['pagetitle'] = $this->translate('pagetitle.invoiceDocument.edit', array(
                    '%invoiceDocument%' => $invoiceDocument->getOriginalName()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.invoiceDocument.edit.txt', array(
                    '%invoiceDocument%' => $invoiceDocument->getOriginalName()
                ));

                return $this->renderResponse('AcfAdminBundle:InvoiceDocument:edit.html.twig', $this->gvars);
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
            $urlFrom = $this->generateUrl('_admin_invoice_list');
        }

        $em = $this->getEntityManager();
        try {
            $invoiceDocument = $em->getRepository('AcfDataBundle:OnlineInvoiceDocument')->find($uid);

            if (null == $invoiceDocument) {
                $this->flashMsgSession('warning', $this->translate('InvoiceDocument.edit.notfound'));
            } else {

                $from = $this->getParameter('mail_from');
                $fromName = $this->getParameter('mail_from_name');
                $subject = $this->translate('_mail.invoiceDocument.subject', array(
                    '%invoice%' => $invoiceDocument->getInvoice()
                        ->getRef(),
                    '%invoiceDocument%' => $invoiceDocument->getOriginalName()
                ), 'messages');
                $mvars = array();
                $mvars['invoiceDocument'] = $invoiceDocument;
                $message = \Swift_Message::newInstance();
                $message->setFrom($from, $fromName);
                $message->setTo($invoiceDocument->getInvoice()
                    ->getUser()
                    ->getEmail(), $invoiceDocument->getInvoice()
                    ->getUser()
                    ->getFullname());
                $message->setSubject($subject);
                $message->setBody($this->renderView('AcfAdminBundle:InvoiceDocument:mail.html.twig', $mvars), 'text/html');
                $this->sendmail($message);
                $this->flashMsgSession('success', $this->translate('InvoiceDocument.mail.success', array(
                    '%invoiceDocument%' => $invoiceDocument->getOriginalName()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }
}