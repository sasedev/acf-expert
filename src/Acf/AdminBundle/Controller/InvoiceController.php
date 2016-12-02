<?php
namespace Acf\AdminBundle\Controller;

use Acf\AdminBundle\Form\Invoice\UpdateStatusTForm as InvoiceUpdateStatusTForm;
use Acf\AdminBundle\Form\Invoice\UpdatePaymentTypeTForm as InvoiceUpdatePaymentTypeTForm;
use Acf\AdminBundle\Form\Invoice\UpdateOrderToTForm as InvoiceUpdateOrderToTForm;
use Acf\AdminBundle\Form\Invoice\UpdateRefTForm as InvoiceUpdateRefTForm;
use Acf\AdminBundle\Form\InvoiceDocument\NewTForm as InvoiceDocumentNewTForm;
use Acf\DataBundle\Entity\OnlineInvoice;
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
				$message->setTo($invoice->getUser()->getEmail(), $invoice->getUser()->getFullname());
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
