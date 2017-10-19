<?php
namespace Acf\AdminBundle\Controller;

use Acf\AdminBundle\Form\Invoice\UpdateStatusTForm as InvoiceUpdateStatusTForm;
use Acf\AdminBundle\Form\Invoice\UpdatePaymentTypeTForm as InvoiceUpdatePaymentTypeTForm;
use Acf\AdminBundle\Form\Invoice\UpdateOrderToTForm as InvoiceUpdateOrderToTForm;
use Acf\AdminBundle\Form\Invoice\UpdateRefTForm as InvoiceUpdateRefTForm;
use Acf\AdminBundle\Form\InvoiceDocument\NewTForm as InvoiceDocumentNewTForm;
use Acf\DataBundle\Entity\OnlineInvoiceDocument;
use Sasedev\Commons\SharedBundle\Controller\BaseController;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class InvoiceController extends BaseController
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }
        $em = $this->getEntityManager();
        $invoices = $em->getRepository('AcfDataBundle:OnlineInvoice')->getAll();
        $this->gvars['invoices'] = $invoices;

        $this->gvars['smenu_active'] = 'list';
        $this->gvars['pagetitle'] = $this->translate('pagetitle.invoice.list');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.invoice.list.txt');

        return $this->renderResponse('AcfAdminBundle:Invoice:list.html.twig', $this->gvars);
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function excelAction()
    {
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_user_list');
        }

        try {
            $em = $this->getEntityManager();
            $invoices = $em->getRepository('AcfDataBundle:OnlineInvoice')->getAll();
            $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

            $phpExcelObject->getProperties()
                ->setCreator('Salah Abdelkader Seif Eddine')
                ->setLastModifiedBy($this->getSecurityTokenStorage()
                ->getToken()
                ->getUser()
                ->getFullname())
                ->setTitle($this->translate('pagetitle.user.list'))
                ->setSubject($this->translate('pagetitle.user.list'))
                ->setDescription($this->translate('pagetitle.user.list'))
                ->setKeywords($this->translate('pagetitle.user.list'))
                ->setCategory('ACEF Users');

            $phpExcelObject->setActiveSheetIndex(0);

            $workSheet = $phpExcelObject->getActiveSheet();
            $workSheet->setTitle($this->translate('pagetitle.invoice.list'));

            $workSheet->setCellValue('A1', $this->translate('Invoice.dtCrea.label'));
            $workSheet->getStyle('A1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('B1', $this->translate('Invoice.user.label'));
            $workSheet->getStyle('B1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('C1', $this->translate('Invoice.company.label'));
            $workSheet->getStyle('C1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('D1', $this->translate('Invoice.ref.label'));
            $workSheet->getStyle('D1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('E1', $this->translate('Invoice.status.label'));
            $workSheet->getStyle('E1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('F1', $this->translate('Invoice.orderTo.label'));
            $workSheet->getStyle('F1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('G1', $this->translate('Invoice.val.label'));
            $workSheet->getStyle('G1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('H1', $this->translate('Invoice.paymentType.label'));
            $workSheet->getStyle('H1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('I1', $this->translate('Invoice.renew.label'));
            $workSheet->getStyle('I1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('J1', $this->translate('Invoice.order.label'));
            $workSheet->getStyle('J1')
                ->getFont()
                ->setBold(true);

            $workSheet->getStyle('A1:J1')->applyFromArray(array(
                'fill' => array(
                    'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array(
                        'rgb' => '94ccdf'
                    )
                )
            ));

            $i = 1;

            foreach ($invoices as $invoice) {
                $i++;
                $workSheet->setCellValue('A' . $i, \PHPExcel_Shared_Date::PHPToExcel($invoice->getDtCrea()), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $workSheet->getStyle('A' . $i)
                    ->getNumberFormat()
                    ->setFormatCode('dd/mm/yyyy hh:MM:ss');
                $workSheet->setCellValue('B' . $i, $invoice->getUser()
                    ->getFullname(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                if (null != $invoice->getCompany()) {
                    $workSheet->setCellValue('C' . $i, $invoice->getCompany()
                        ->getCorporateName(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                }
                $workSheet->setCellValue('D' . $i, $invoice->getRef(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('E' . $i, $this->translate('Invoice.status.' . $invoice->getStatus()), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('F' . $i, $invoice->getOrderTo(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('G' . $i, $invoice->getVal(), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $workSheet->setCellValue('H' . $i, $this->translate('Invoice.paymentType.' . $invoice->getPaymentType()), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('I' . $i, $this->translate('Invoice.renew.' . $invoice->getRenew()), \PHPExcel_Cell_DataType::TYPE_STRING2);
                if (null != $invoice->getOrder()) {
                    $workSheet->setCellValue('J' . $i, $invoice->getOrder()
                        ->getRef(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                }

                if ($i % 2 == 1) {
                    $workSheet->getStyle('A' . $i . ':J' . $i)->applyFromArray(array(
                        'fill' => array(
                            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array(
                                'rgb' => 'd8f1f5'
                            )
                        )
                    ));
                } else {
                    $workSheet->getStyle('A' . $i . ':J' . $i)->applyFromArray(array(
                        'fill' => array(
                            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array(
                                'rgb' => 'bfbfbf'
                            )
                        )
                    ));
                }
            }
            // *
            $workSheet->getColumnDimension('A')->setAutoSize(true);
            $workSheet->getColumnDimension('B')->setAutoSize(true);
            $workSheet->getColumnDimension('C')->setAutoSize(true);
            $workSheet->getColumnDimension('D')->setAutoSize(true);
            $workSheet->getColumnDimension('E')->setAutoSize(true);
            $workSheet->getColumnDimension('F')->setAutoSize(true);
            $workSheet->getColumnDimension('G')->setAutoSize(true);
            $workSheet->getColumnDimension('H')->setAutoSize(true);
            $workSheet->getColumnDimension('I')->setAutoSize(true);
            $workSheet->getColumnDimension('J')->setAutoSize(true);

            $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
            $response = $this->get('phpexcel')->createStreamedResponse($writer);

            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8');

            $filename = $this->normalize($this->translate('pagetitle.invoice.list'));
            $filename = str_ireplace('"', '|', $filename);
            $filename = str_ireplace(' ', '_', $filename);

            $response->headers->set('Content-Disposition', 'attachment;filename=' . $filename . '.xlsx');
            $response->headers->set('Pragma', 'public');
            $response->headers->set('Cache-Control', 'maxage=1');

            return $response;
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);

        $this->gvars['smenu_active'] = 'list';
        $this->gvars['pagetitle'] = $this->translate('pagetitle.user.list');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.user.list.txt');

        return $this->renderResponse('AcfAdminBundle:User:list.html.twig', $this->gvars);
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
            $invoice = $em->getRepository('AcfDataBundle:OnlineInvoice')->find($uid);

            if (null == $invoice) {
                $this->flashMsgSession('warning', $this->translate('Invoice.delete.notfound'));
            } else {
                $em->remove($invoice);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('Invoice.delete.success', array(
                    '%invoice%' => $invoice->getRef()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('Invoice.delete.failure'));
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
            $invoice = $em->getRepository('AcfDataBundle:OnlineInvoice')->find($uid);

            if (null == $invoice) {
                $this->flashMsgSession('warning', $this->translate('.edit.notfound'));
            } else {
                $invoiceUpdateStatusForm = $this->createForm(InvoiceUpdateStatusTForm::class, $invoice);
                $invoiceUpdatePaymentTypeForm = $this->createForm(InvoiceUpdatePaymentTypeTForm::class, $invoice);
                $invoiceUpdateOrderToForm = $this->createForm(InvoiceUpdateOrderToTForm::class, $invoice);
                $invoiceUpdateRefForm = $this->createForm(InvoiceUpdateRefTForm::class, $invoice);

                $invoiceDoc = new OnlineInvoiceDocument();
                $invoiceDoc->setInvoice($invoice);
                $invoiceDocumentNewForm = $this->createForm(InvoiceDocumentNewTForm::class, $invoiceDoc, array(
                    'invoice' => $invoice
                ));

                $this->gvars['invoice'] = $invoice;
                $this->gvars['InvoiceUpdateStatusForm'] = $invoiceUpdateStatusForm->createView();
                $this->gvars['InvoiceUpdatePaymentTypeForm'] = $invoiceUpdatePaymentTypeForm->createView();
                $this->gvars['InvoiceUpdateOrderToForm'] = $invoiceUpdateOrderToForm->createView();
                $this->gvars['InvoiceUpdateRefForm'] = $invoiceUpdateRefForm->createView();
                $this->gvars['invoiceDocument'] = $invoiceDoc;
                $this->gvars['InvoiceDocumentNewForm'] = $invoiceDocumentNewForm->createView();

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');

                $this->gvars['pagetitle'] = $this->translate('pagetitle.invoice.edit', array(
                    '%invoice%' => $invoice->getRef()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.invoice.edit.txt', array(
                    '%invoice%' => $invoice->getRef()
                ));

                return $this->renderResponse('AcfAdminBundle:Invoice:edit.html.twig', $this->gvars);
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
            $invoice = $em->getRepository('AcfDataBundle:OnlineInvoice')->find($uid);

            if (null == $invoice) {
                $this->flashMsgSession('warning', $this->translate('Invoice.edit.notfound'));
            } else {
                $invoiceUpdateStatusForm = $this->createForm(InvoiceUpdateStatusTForm::class, $invoice);
                $invoiceUpdatePaymentTypeForm = $this->createForm(InvoiceUpdatePaymentTypeTForm::class, $invoice);
                $invoiceUpdateOrderToForm = $this->createForm(InvoiceUpdateOrderToTForm::class, $invoice);
                $invoiceUpdateRefForm = $this->createForm(InvoiceUpdateRefTForm::class, $invoice);

                $invoiceDoc = new OnlineInvoiceDocument();
                $invoiceDoc->setInvoice($invoice);
                $invoiceDocumentNewForm = $this->createForm(InvoiceDocumentNewTForm::class, $invoiceDoc, array(
                    'invoice' => $invoice
                ));

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                if (isset($reqData['InvoiceUpdateStatusForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $invoiceUpdateStatusForm->handleRequest($request);
                    if ($invoiceUpdateStatusForm->isValid()) {
                        $em->persist($invoice);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Invoice.edit.success', array(
                            '%invoice%' => $invoice->getRef()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($invoice);

                        $this->flashMsgSession('error', $this->translate('Invoice.edit.failure', array(
                            '%invoice%' => $invoice->getRef()
                        )));
                    }
                } elseif (isset($reqData['InvoiceUpdatePaymentTypeForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $invoiceUpdatePaymentTypeForm->handleRequest($request);
                    if ($invoiceUpdatePaymentTypeForm->isValid()) {
                        $em->persist($invoice);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Invoice.edit.success', array(
                            '%invoice%' => $invoice->getRef()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($invoice);

                        $this->flashMsgSession('error', $this->translate('Invoice.edit.failure', array(
                            '%invoice%' => $invoice->getRef()
                        )));
                    }
                } elseif (isset($reqData['InvoiceUpdateOrderToForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $invoiceUpdateOrderToForm->handleRequest($request);
                    if ($invoiceUpdateOrderToForm->isValid()) {
                        $em->persist($invoice);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Invoice.edit.success', array(
                            '%invoice%' => $invoice->getRef()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($invoice);

                        $this->flashMsgSession('error', $this->translate('Invoice.edit.failure', array(
                            '%invoice%' => $invoice->getRef()
                        )));
                    }
                } elseif (isset($reqData['InvoiceUpdateRefForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $invoiceUpdateRefForm->handleRequest($request);
                    if ($invoiceUpdateRefForm->isValid()) {
                        $em->persist($invoice);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Invoice.edit.success', array(
                            '%invoice%' => $invoice->getRef()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($invoice);

                        $this->flashMsgSession('error', $this->translate('Invoice.edit.failure', array(
                            '%invoice%' => $invoice->getRef()
                        )));
                    }
                } elseif (isset($reqData['InvoiceDocumentNewForm'])) {
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
                $this->gvars['InvoiceUpdateStatusForm'] = $invoiceUpdateStatusForm->createView();
                $this->gvars['InvoiceUpdatePaymentTypeForm'] = $invoiceUpdatePaymentTypeForm->createView();
                $this->gvars['InvoiceUpdateOrderToForm'] = $invoiceUpdateOrderToForm->createView();
                $this->gvars['InvoiceUpdateRefForm'] = $invoiceUpdateRefForm->createView();
                $this->gvars['invoiceDocument'] = $invoiceDoc;
                $this->gvars['InvoiceDocumentNewForm'] = $invoiceDocumentNewForm->createView();

                $this->gvars['pagetitle'] = $this->translate('pagetitle.invoice.edit', array(
                    '%invoice%' => $invoice->getRef()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.invoice.edit.txt', array(
                    '%invoice%' => $invoice->getRef()
                ));

                return $this->renderResponse('AcfAdminBundle:Invoice:edit.html.twig', $this->gvars);
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
    public function printAction($uid)
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
            $invoice = $em->getRepository('AcfDataBundle:OnlineInvoice')->find($uid);

            if (null == $invoice) {
                $this->flashMsgSession('warning', $this->translate('Invoice.edit.notfound'));
            } else {

                $this->gvars['invoice'] = $invoice;

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');

                $this->gvars['pagetitle'] = $this->translate('pagetitle.invoice.edit', array(
                    '%invoice%' => $invoice->getRef()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.invoice.edit.txt', array(
                    '%invoice%' => $invoice->getRef()
                ));

                return $this->renderResponse('AcfAdminBundle:Invoice:print.html.twig', $this->gvars);
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
            $invoice = $em->getRepository('AcfDataBundle:OnlineInvoice')->find($uid);

            if (null == $invoice) {
                $this->flashMsgSession('warning', $this->translate('Invoice.edit.notfound'));
            } else {

                $from = $this->getParameter('mail_from');
                $fromName = $this->getParameter('mail_from_name');
                $subject = $this->translate('_mail.invoice.subject', array(
                    '%invoice%' => $invoice->getRef()
                ), 'messages');
                $mvars = array();
                $mvars['invoice'] = $invoice;
                $message = \Swift_Message::newInstance();
                $message->setFrom($from, $fromName);
                $message->setTo($invoice->getUser()
                    ->getEmail(), $invoice->getUser()
                    ->getFullname());
                $message->setSubject($subject);
                $mvars['logo'] = $message->embed(\Swift_Image::fromPath($this->getParameter('kernel.root_dir') . '/../web/bundles/acfres/images/logo_acf.jpg'));
                $message->setBody($this->renderView('AcfAdminBundle:Invoice:mail.html.twig', $mvars), 'text/html');
                $this->sendmail($message);
                $this->flashMsgSession('success', $this->translate('Invoice.mail.success', array(
                    '%invoice%' => $invoice->getRef()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }
}
