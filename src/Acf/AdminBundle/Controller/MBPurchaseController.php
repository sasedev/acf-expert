<?php
namespace Acf\AdminBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\AdminBundle\Form\MBPurchase\UpdateCountTForm as MBPurchaseUpdateCountTForm;
use Acf\AdminBundle\Form\Buy\NewTForm as BuyNewTForm;
use Acf\AdminBundle\Form\Buy\ImportTForm as BuyImportTForm;
use Acf\AdminBundle\Form\MBPurchase\UpdateDocsTForm as MBPurchaseUpdateDocsTForm;
use Acf\AdminBundle\Form\Doc\NewTForm as DocNewTForm;
use Acf\DataBundle\Entity\Doc;
use Acf\DataBundle\Entity\Buy;
use Acf\DataBundle\Entity\MBPurchase;
use Acf\DataBundle\Entity\ConstantStr;
use Acf\DataBundle\Entity\Account;
use Acf\DataBundle\Entity\Trace;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class MBPurchaseController extends BaseController
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
		$this->gvars['menu_active'] = 'company';
	}

	/**
	 *
	 * @param string $uid
	 *
	 * @return Response
	 */
	public function editGetAction($uid)
	{
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_admin_company_list');
		}

		$em = $this->getEntityManager();
		try {
			$mbpurchase = $em->getRepository('AcfDataBundle:MBPurchase')->find($uid);

			if (null == $mbpurchase) {
				$this->flashMsgSession('warning', $this->translate('MBPurchase.edit.notfound'));
			} else {
				$traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($mbpurchase->getId(), Trace::AE_MBPURCHASE);
				$this->gvars['traces'] = array_reverse($traces);
				$mbpurchaseUpdateCountForm = $this->createForm(MBPurchaseUpdateCountTForm::class, $mbpurchase);
				$buy = new Buy();
				$buy->setMonthlyBalance($mbpurchase);
				$buyNewForm = $this->createForm(BuyNewTForm::class, $buy, array(
					'monthlybalance' => $mbpurchase
				));
				$buyImportForm = $this->createForm(BuyImportTForm::class);
				$mbpurchaseUpdateDocsForm = $this->createForm(MBPurchaseUpdateDocsTForm::class, $mbpurchase, array(
					'company' => $mbpurchase->getCompany()
				));

				$doc = new Doc();
				$doc->setCompany($mbpurchase->getCompany());
				$docNewForm = $this->createForm(DocNewTForm::class, $doc, array(
					'company' => $mbpurchase->getCompany()
				));

				$this->gvars['mbpurchase'] = $mbpurchase;
				$this->gvars['buy'] = $buy;
				$this->gvars['doc'] = $doc;
				$this->gvars['BuyNewForm'] = $buyNewForm->createView();
				$this->gvars['BuyImportForm'] = $buyImportForm->createView();
				$this->gvars['MBPurchaseUpdateCountForm'] = $mbpurchaseUpdateCountForm->createView();
				$this->gvars['MBPurchaseUpdateDocsForm'] = $mbpurchaseUpdateDocsForm->createView();
				$this->gvars['DocNewForm'] = $docNewForm->createView();

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
				$this->getSession()->remove('tabActive');

				$this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
				$this->getSession()->remove('stabActive');

				$suppliersConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
					'name' => 'suppliersPrefix'
				));
				if (null == $suppliersConstStr) {
					$suppliersConstStr = new ConstantStr();
					$suppliersConstStr->setName('suppliersPrefix');
					$suppliersConstStr->setValue('401');
					$em->persist($suppliersConstStr);
					$em->flush();
				}
				$suppliersPrefix = $suppliersConstStr->getValue();
				$this->gvars['suppliersPrefix'] = $suppliersPrefix;

				$this->gvars['pagetitle'] = $this->translate('pagetitle.mbpurchase.edit', array(
					'%mbpurchase%' => $mbpurchase->getRef()
				));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.mbpurchase.edit.txt', array(
					'%mbpurchase%' => $mbpurchase->getRef()
				));

				return $this->renderResponse('AcfAdminBundle:MBPurchase:edit.html.twig', $this->gvars);
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
	 *
	 * @return Response
	 */
	public function editPostAction($uid)
	{
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_admin_company_list');
		}

		$em = $this->getEntityManager();
		try {
			$mbpurchase = $em->getRepository('AcfDataBundle:MBPurchase')->find($uid);

			if (null == $mbpurchase) {
				$this->flashMsgSession('warning', $this->translate('MBPurchase.edit.notfound'));
			} else {
				$traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($mbpurchase->getId(), Trace::AE_MBPURCHASE);
				$this->gvars['traces'] = array_reverse($traces);
				$mbpurchaseUpdateCountForm = $this->createForm(MBPurchaseUpdateCountTForm::class, $mbpurchase);
				$buy = new Buy();
				$buy->setMonthlyBalance($mbpurchase);
				$buyNewForm = $this->createForm(BuyNewTForm::class, $buy, array(
					'monthlybalance' => $mbpurchase
				));
				$buyImportForm = $this->createForm(BuyImportTForm::class);
				$mbpurchaseUpdateDocsForm = $this->createForm(MBPurchaseUpdateDocsTForm::class, $mbpurchase, array(
					'company' => $mbpurchase->getCompany()
				));

				$doc = new Doc();
				$doc->setCompany($mbpurchase->getCompany());
				$docNewForm = $this->createForm(DocNewTForm::class, $doc, array(
					'company' => $mbpurchase->getCompany()
				));

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
				$this->getSession()->remove('tabActive');

				$this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
				$this->getSession()->remove('stabActive');

				$request = $this->getRequest();
				$reqData = $request->request->all();

				$cloneMBPurchase = clone $mbpurchase;

				if (isset($reqData['BuyImportForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$this->gvars['stabActive'] = 1;
					$this->getSession()->set('stabActive', 1);
					$buyImportForm->handleRequest($request);
					if ($buyImportForm->isValid()) {

						ini_set('memory_limit', '4096M');
						ini_set('max_execution_time', '0');
						$extension = $buyImportForm['excel']->getData()->guessExtension();
						if ($extension == 'zip') {
							$extension = 'xlsx';
						}

						$filename = uniqid() . '.' . $extension;
						$buyImportForm['excel']->getData()->move($this->getParameter('adapter_files'), $filename);
						$fullfilename = $this->getParameter('adapter_files');
						$fullfilename .= '/' . $filename;

						$excelObj = $this->get('phpexcel')->createPHPExcelObject($fullfilename);

						$log = '';

						$iterator = $excelObj->getWorksheetIterator();

						$activeSheetIndex = -1;
						$i = 0;

						foreach ($iterator as $worksheet) {
							$worksheetTitle = $worksheet->getTitle();
							$highestRow = $worksheet->getHighestRow(); // e.g. 10
							$highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
							$highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);

							$log .= "Feuille : '" . $worksheetTitle . "' trouvée contenant " . $highestRow . ' lignes et ' . $highestColumnIndex . ' colonnes avec comme plus grand index ' . $highestColumn . ' <br>';
							if (\trim($worksheetTitle) == 'Sage') {
								$activeSheetIndex = $i;
							}
							$i++;
						}
						if ($activeSheetIndex == -1) {
							$log .= "Aucune Feuille de Titre 'Sage' trouvée tentative d'import depuis le première Feuille<br>";
							$activeSheetIndex = 0;
						}

						$excelObj->setActiveSheetIndex($activeSheetIndex);

						$suppliersConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
							'name' => 'suppliersPrefix'
						));
						if (null == $suppliersConstStr) {
							$suppliersConstStr = new ConstantStr();
							$suppliersConstStr->setName('suppliersPrefix');
							$suppliersConstStr->setValue('401');
							$em->persist($suppliersConstStr);
							$em->flush();
						}
						$suppliersPrefix = $suppliersConstStr->getValue();
						$suppliersPrefixNum = \intval($suppliersPrefix) * 1000000000;

						$worksheet = $excelObj->getActiveSheet();
						$highestRow = $worksheet->getHighestRow();
						$lineRead = 0;
						$buysNew = 0;
						$lineUnprocessed = 0;
						$lineError = 0;

						$company = $mbpurchase->getCompany();

						$accounts = $em->getRepository('AcfDataBundle:Account')->getAllByCompany($company);
						$suppliers = $em->getRepository('AcfDataBundle:Supplier')->getAllByCompany($company);
						$companyNatures = $em->getRepository('AcfDataBundle:CompanyNature')->getAllByCompany($company);
						$withholdings = $em->getRepository('AcfDataBundle:Withholding')->getAllByCompany($company);

						for ($row = 1; $row <= $highestRow; $row++) {
							$lineRead++;

							$dtActivation = \PHPExcel_Shared_Date::ExcelToPHPObject($worksheet->getCellByColumnAndRow(1, $row)->getValue());
							$bill = \trim(\strval($worksheet->getCellByColumnAndRow(2, $row)->getValue()));
							$supplierNum = \intval($worksheet->getCellByColumnAndRow(4, $row)->getValue());
							$label = \trim(\strval($worksheet->getCellByColumnAndRow(5, $row)->getValue()));
							$vat = \floatval($worksheet->getCellByColumnAndRow(7, $row)->getValue());
							$stamp = \floatval($worksheet->getCellByColumnAndRow(8, $row)->getValue());
							$balanceTtc = \floatval($worksheet->getCellByColumnAndRow(9, $row)->getValue());
							$regime = \trim(\strval($worksheet->getCellByColumnAndRow(10, $row)->getValue()));
							$withholdingValue = \trim(\strval($worksheet->getCellByColumnAndRow(12, $row)->getValue() * 100));
							$balanceNet = \floatval($worksheet->getCellByColumnAndRow(13, $row)->getValue());
							$paymentType = \trim(\strval($worksheet->getCellByColumnAndRow(14, $row)->getValue()));
							$dtPayment = \PHPExcel_Shared_Date::ExcelToPHPObject($worksheet->getCellByColumnAndRow(15, $row)->getValue());
							$accountLabel = \trim(\strval($worksheet->getCellByColumnAndRow(16, $row)->getValue()));
							$natureLabel = \trim(\strval($worksheet->getCellByColumnAndRow(17, $row)->getValue()));
							$status = \trim(\strval($worksheet->getCellByColumnAndRow(18, $row)->getValue()));
							$otherInfos = \trim(\strval($worksheet->getCellByColumnAndRow(19, $row)->getValue()));

							if ($supplierNum != '' && \is_numeric($supplierNum)) {
								$supplierNum = \intval($supplierNum) - $suppliersPrefixNum;
							}

							$haserror = false;

							if (null == $dtActivation) {
								$haserror = true;
								$log .= 'ligne ' . $lineRead . ", erreur : Date d'activation<br>";
							}

							if ($bill == '') {
								$haserror = true;
								$log .= 'ligne ' . $lineRead . ', erreur : Numéro Facture<br>';
							}

							if ($supplierNum == '' || $supplierNum <= 0) {
								$haserror = true;
								$oldsuppnum = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
								$log .= 'ligne ' . $lineRead . ', erreur : Numéro Fournisseur (' . $oldsuppnum . ')<br>';
							} else {
								$supplier = null;
								$knownSupplier = false;
								foreach ($suppliers as $s) {
									if ($s->getNumber() == $supplierNum) {
										$knownSupplier = true;
										$supplier = $s;
									}
								}

								if ($knownSupplier == false) {
									$haserror = true;
									$log .= 'ligne ' . $lineRead . ', erreur : Fournisseur Inconnu<br>';
								}
							}

							if ($label == '') {
								$haserror = true;
								$log .= 'ligne ' . $lineRead . ', erreur : Libélé<br>';
							}

							if ($vat < 0) {
								$haserror = true;
								$log .= 'ligne ' . $lineRead . ', erreur : TVA<br>';
							}

							if ($stamp < 0) {
								$haserror = true;
								$log .= 'ligne ' . $lineRead . ', erreur : Timbre<br>';
							}

							if ($balanceTtc < 0) {
								$haserror = true;
								$log .= 'ligne ' . $lineRead . ', erreur : TTC<br>';
							}

							if ($regime == $this->translate('Buy.regime.0')) {
								$regime = 0;
							} elseif ($regime == $this->translate('Buy.regime.1')) {
								$regime = 1;
							} elseif ($regime == $this->translate('Buy.regime.2')) {
								$regime = 2;
							} elseif ($regime == $this->translate('Buy.regime.3')) {
								$regime = 3;
							} elseif ($regime == $this->translate('Buy.regime.4')) {
								$regime = 4;
							} elseif ($regime == $this->translate('Buy.regime.5')) {
								$regime = 5;
							} else {
								$regime = 0;
								$log .= 'ligne ' . $lineRead . ', erreur (ignorée) : Régime inconnu => ' . $this->translate('Buy.regime.0') . '<br>';
							}

							$withholding = null;
							$knownWithholding = false;
							foreach ($withholdings as $w) {
								if ($w->getValue() == $withholdingValue || $w->getLabel() == $withholdingValue) {
									$knownWithholding = true;
									$withholding = $w;
								}
							}

							if ($knownWithholding == false) {
								$haserror = true;
								$log .= 'ligne ' . $lineRead . ', erreur : Retenue Inconnue ' . $withholdingValue . '<br>';
							}

							if ($balanceNet < 0) {
								$haserror = true;
								$log .= 'ligne ' . $lineRead . ', erreur : Net à Payer<br>';
							}

							if ($paymentType == $this->translate('Transaction.paymentType.0')) {
								$paymentType = 0;
							} elseif ($paymentType == $this->translate('Transaction.paymentType.1')) {
								$paymentType = 1;
							} elseif ($paymentType == $this->translate('Transaction.paymentType.2')) {
								$paymentType = 2;
							} elseif ($paymentType == $this->translate('Transaction.paymentType.3')) {
								$paymentType = 3;
							} elseif ($paymentType == $this->translate('Transaction.paymentType.4')) {
								$paymentType = 4;
							} else {
								$paymentType = 0;
								$log .= 'ligne ' . $lineRead . ', erreur (ignorée) : Type de Paiement inconnu => ' . $this->translate('Transaction.paymentType.0') . '<br>';
							}

							if (null == $dtPayment) {
								$haserror = true;
								$log .= 'ligne ' . $lineRead . ', erreur : Date de paiement<br>';
							}

							$account = null;
							$knownAccount = false;
							foreach ($accounts as $a) {
								if ($a->getLabel() == $accountLabel) {
									$knownAccount = true;
									$account = $a;
								}
							}

							if ($knownAccount == false) {
								$haserror = true;
								$log .= 'ligne ' . $lineRead . ', erreur : Banque/Caisse Inconnue<br>';
							}

							$nature = null;
							foreach ($companyNatures as $n) {
								if ($n->getLabel() == $natureLabel) {
									$nature = $n;
								}
							}

							if ($status == $this->translate('Transaction.transactionStatus.0')) {
								$status = 0;
							} elseif ($status == $this->translate('Transaction.transactionStatus.1')) {
								$status = 1;
							} elseif ($status == $this->translate('Transaction.transactionStatus.10')) {
								$status = 10;
							} else {
								$status = 0;
								$log .= 'ligne ' . $lineRead . ', erreur (ignorée) : Etat inconnu => ' . $this->translate('Transaction.transactionStatus.0') . '<br>';
							}

							if ($haserror == false) {

								$buy = $em->getRepository('AcfDataBundle:Buy')->findOneBy(array(
									'monthlyBalance' => $mbpurchase,
									'bill' => $bill
								));
								$exist = false;
								if (null == $buy) {
									$buysNew++;

									$buy = new Buy();
								} else {
									$lineUnprocessed++;
									$log .= "l'Achat à la ligne " . $lineRead . ' existe déjà et sera donc remplacé<br>';
									$exist = true;
								}

								$buy->setMonthlyBalance($mbpurchase);

								if (!$exist) {
									$buy->setNumber($mbpurchase->getCount());
								}
								$buy->setDtActivation($dtActivation);
								$buy->setBill($bill);
								$buy->setRelation($supplier);
								$buy->setLabel($label);
								$buy->setVat($vat);
								$buy->setVatDevise($vat);
								$buy->setStamp($stamp);
								$buy->setStampDevise($stamp);
								$buy->setBalanceTtc($balanceTtc);
								$buy->setBalanceTtcDevise($balanceTtc);
								$buy->setRegime($regime);
								$buy->setWithholding($withholding);
								$buy->setBalanceNet($balanceNet);
								$buy->setBalanceNetDevise($balanceNet);
								$buy->setPaymentType($paymentType);
								$buy->setDtPayment($dtPayment);
								$buy->setAccount($account);
								$buy->setNature($nature);
								$buy->setTransactionStatus($status);
								$buy->setOtherInfos($otherInfos);

								$em->persist($buy);
								if (!$exist) {
									$mbpurchase->updateCount();
								}
								$em->persist($mbpurchase);
							} else {
								$lineError++;
								$log .= 'la ligne ' . $lineRead . ' contient des erreurs<br>';
							}
						}
						$em->flush();

						$log .= $lineRead . ' lignes lues<br>';
						$log .= $buysNew . ' nouveaux Achat<br>';
						$log .= $lineUnprocessed . ' Achats déjà dans la base<br>';
						$log .= $lineError . ' lignes contenant des erreurs<br>'; // */

						$this->flashMsgSession('log', $log);

						$this->flashMsgSession('success', $this->translate('Buy.import.success'));

						$this->gvars['tabActive'] = 1;
						$this->getSession()->set('tabActive', 1);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($mbpurchase);

						$this->flashMsgSession('error', $this->translate('Buy.import.failure'));
					}
				} elseif (isset($reqData['BuyNewForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$this->gvars['stabActive'] = 2;
					$this->getSession()->set('stabActive', 2);
					$buyNewForm->handleRequest($request);
					if ($buyNewForm->isValid()) {
						$buy->setNumber($mbpurchase->getCount());
						if ($buy->getDevise() == 'TND') {
							$buy->setConversionRate(1);
							$buy->setVatDevise($buy->getVat());
							$buy->setStampDevise($buy->getStamp());
							$buy->setBalanceTtcDevise($buy->getBalanceTtc());
							$buy->setBalanceNetDevise($buy->getBalanceNet());
						} else {
							$buy->setVat($buy->getVatDevise() * $buy->getConversionRate());
							$buy->setStamp($buy->getStampDevise() * $buy->getConversionRate());
							$buy->setBalanceTtc($buy->getBalanceTtcDevise() * $buy->getConversionRate());
							$buy->setBalanceNet($buy->getBalanceNetDevise() * $buy->getConversionRate());
						}
						foreach ($buyNewForm->get('docs') as $docNewForm) {
							$docFile = $docNewForm['fileName']->getData();
							$docDir = $this->getParameter('kernel.root_dir') . '/../web/res/docs';
							$originalName = $docFile->getClientOriginalName();
							$fileName = sha1(uniqid(mt_rand(), true)) . '.' . strtolower($docFile->getClientOriginalExtension());
							$mimeType = $docFile->getMimeType();
							$docFile->move($docDir, $fileName);
							$size = filesize($docDir . '/' . $fileName);
							$md5 = md5_file($docDir . '/' . $fileName);

							$doc = $docNewForm->getData();
							$doc->setCompany($mbpurchase->getCompany());
							$doc->setFileName($fileName);
							$doc->setOriginalName($originalName);
							$doc->setSize($size);
							$doc->setMimeType($mimeType);
							$doc->setMd5($md5);
							$doc->setDescription($docNewForm['description']->getData());
							$em->persist($doc);

							$buy->addDoc($doc);
						}
						$em->persist($buy);
						$em->flush();
						$mbpurchase->updateCount();
						$em->persist($mbpurchase);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Buy.add.success', array(
							'%buy%' => $buy->getNumber()
						)));
						$this->gvars['tabActive'] = 1;
						$this->getSession()->set('tabActive', 1);
						$this->gvars['stabActive'] = 1;
						$this->getSession()->set('stabActive', 1);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($mbpurchase);

						$this->flashMsgSession('error', $this->translate('Buy.add.failure'));
					}
				} elseif (isset($reqData['MBPurchaseUpdateCountForm'])) {
					$this->gvars['tabActive'] = 3;
					$this->getSession()->set('tabActive', 3);
					$mbpurchaseUpdateCountForm->handleRequest($request);
					if ($mbpurchaseUpdateCountForm->isValid()) {
						$em->persist($mbpurchase);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('MBPurchase.edit.success', array(
							'%mbpurchase%' => $mbpurchase->getRef()
						)));

						$this->traceEntity($cloneMBPurchase, $mbpurchase);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($mbpurchase);

						$this->flashMsgSession('error', $this->translate('MBPurchase.edit.failure', array(
							'%mbpurchase%' => $mbpurchase->getRef()
						)));
					}
				} elseif (isset($reqData['DocNewForm'])) {
					$this->gvars['tabActive'] = 4;
					$this->getSession()->set('tabActive', 4);
					$this->gvars['stabActive'] = 1;
					$this->getSession()->set('stabActive', 1);
					$docNewForm->handleRequest($request);
					if ($docNewForm->isValid()) {
						$docFiles = $docNewForm['fileName']->getData();
						$docs = array();

						$docDir = $this->getParameter('kernel.root_dir') . '/../web/res/docs';

						$docNames = '';

						foreach ($docFiles as $docFile) {

							$originalName = $docFile->getClientOriginalName();
							$fileName = sha1(uniqid(mt_rand(), true)) . '.' . strtolower($docFile->getClientOriginalExtension());
							$mimeType = $docFile->getMimeType();
							$docFile->move($docDir, $fileName);

							$size = filesize($docDir . '/' . $fileName);
							$md5 = md5_file($docDir . '/' . $fileName);

							$doc = new Doc();
							$doc->setCompany($mbpurchase->getCompany());

							$doc->setFileName($fileName);
							$doc->setOriginalName($originalName);
							$doc->setSize($size);
							$doc->setMimeType($mimeType);
							$doc->setMd5($md5);
							$doc->setDescription($docNewForm['description']->getData());
							$em->persist($doc);

							$mbpurchase->addDoc($doc);

							$docNames .= $doc->getOriginalName() . ' ';

							$docs[] = $doc;
						}

						$em->persist($mbpurchase);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Doc.add.success', array(
							'%doc%' => $docNames
						)));

						$from = $this->getParameter('mail_from');
						$fromName = $this->getParameter('mail_from_name');
						$subject = $this->translate('_mail.newdocsCloud.subject', array(), 'messages');

						$company = $mbpurchase->getCompany();
						$acfCloudRole = $em->getRepository('AcfDataBundle:Role')->findOneBy(array(
							'name' => 'ROLE_CLIENT1'
						));

						$users = array();
						foreach ($company->getUsers() as $user) {
							if ($user->hasRole($acfCloudRole)) {
								$users[] = $user;
							}
						}

						if (\count($users) != 0) {
							foreach ($users as $user) {
								$mvars = array();
								$mvars['company'] = $company;
								$mvars['docs'] = $docs;
								$message = \Swift_Message::newInstance();
								$message->setFrom($from, $fromName);
								$message->addTo($user->getEmail(), $user->getFullname());
								$message->setSubject($subject);
								$message->setBody($this->renderView('AcfAdminBundle:Doc:sendmail.html.twig', $mvars), 'text/html');
								$this->sendmail($message);
							}
						}
						$this->gvars['stabActive'] = 3;
						$this->getSession()->set('stabActive', 3);

						$this->traceEntity($cloneMBPurchase, $mbpurchase);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($mbpurchase);

						$this->flashMsgSession('error', $this->translate('Doc.add.failure'));
					}
				} elseif (isset($reqData['MBPurchaseUpdateDocsForm'])) {
					$this->gvars['tabActive'] = 4;
					$this->getSession()->set('tabActive', 4);
					$this->gvars['stabActive'] = 2;
					$this->getSession()->set('stabActive', 2);
					$mbpurchaseUpdateDocsForm->handleRequest($request);
					if ($mbpurchaseUpdateDocsForm->isValid()) {
						$em->persist($mbpurchase);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('MBPurchase.edit.success', array(
							'%mbpurchase%' => $mbpurchase->getRef()
						)));
						$this->gvars['stabActive'] = 3;
						$this->getSession()->set('stabActive', 3);

						$this->traceEntity($cloneMBPurchase, $mbpurchase);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($mbpurchase);

						$this->flashMsgSession('error', $this->translate('MBPurchase.edit.failure', array(
							'%mbpurchase%' => $mbpurchase->getRef()
						)));
					}
				}

				$this->gvars['mbpurchase'] = $mbpurchase;
				$this->gvars['buy'] = $buy;
				$this->gvars['doc'] = $doc;
				$this->gvars['BuyNewForm'] = $buyNewForm->createView();
				$this->gvars['BuyImportForm'] = $buyImportForm->createView();
				$this->gvars['MBPurchaseUpdateCountForm'] = $mbpurchaseUpdateCountForm->createView();
				$this->gvars['MBPurchaseUpdateDocsForm'] = $mbpurchaseUpdateDocsForm->createView();
				$this->gvars['DocNewForm'] = $docNewForm->createView();

				$suppliersConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
					'name' => 'suppliersPrefix'
				));
				if (null == $suppliersConstStr) {
					$suppliersConstStr = new ConstantStr();
					$suppliersConstStr->setName('suppliersPrefix');
					$suppliersConstStr->setValue('401');
					$em->persist($suppliersConstStr);
					$em->flush();
				}
				$suppliersPrefix = $suppliersConstStr->getValue();
				$this->gvars['suppliersPrefix'] = $suppliersPrefix;

				$this->gvars['pagetitle'] = $this->translate('pagetitle.mbpurchase.edit', array(
					'%mbpurchase%' => $mbpurchase->getRef()
				));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.mbpurchase.edit.txt', array(
					'%mbpurchase%' => $mbpurchase->getRef()
				));

				return $this->renderResponse('AcfAdminBundle:MBPurchase:edit.html.twig', $this->gvars);
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
	 *
	 * @return unknown|\Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function excelAction($uid)
	{
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_admin_company_list');
		}

		$em = $this->getEntityManager();
		try {
			$mbpurchase = $em->getRepository('AcfDataBundle:MBPurchase')->find($uid);
			$buys = $mbpurchase->getTransactions();

			$phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

			$phpExcelObject->getProperties()->setCreator('Salah Abdelkader Seif Eddine')->setLastModifiedBy($this->getSecurityTokenStorage()->getToken()->getUser()->getFullname())->setTitle($this->translate('pagetitle.buy.list'))->setSubject($this->translate('pagetitle.buy.list'))->setDescription($this->translate('pagetitle.buy.list'))->setKeywords($this->translate('pagetitle.buy.list'))->setCategory('ACEF buy');

			$phpExcelObject->setActiveSheetIndex(0);

			$workSheet = $phpExcelObject->getActiveSheet();
			$workSheet->setTitle($this->translate('pagetitle.buy.listExcel', array(
				'%mbpurchase%' => $mbpurchase->getRef()
			)));

			$workSheet->setCellValue('A1', $this->translate('Buy.number.label'));
			$workSheet->getStyle('A1')->getFont()->setBold(true);
			$workSheet->setCellValue('B1', $this->translate('Buy.dtActivation.label'));
			$workSheet->getStyle('B1')->getFont()->setBold(true);
			$workSheet->setCellValue('C1', $this->translate('Buy.bill.label'));
			$workSheet->getStyle('C1')->getFont()->setBold(true);
			$workSheet->setCellValue('D1', $this->translate('Buy.relation.label'));
			$workSheet->getStyle('D1')->getFont()->setBold(true);
			$workSheet->setCellValue('E1', $this->translate('Buy.relation.number'));
			$workSheet->getStyle('E1')->getFont()->setBold(true);
			$workSheet->setCellValue('F1', $this->translate('Buy.label.label'));
			$workSheet->getStyle('F1')->getFont()->setBold(true);
			$workSheet->setCellValue('G1', $this->translate('Buy.balanceHt.label'));
			$workSheet->getStyle('G1')->getFont()->setBold(true);
			$workSheet->setCellValue('H1', $this->translate('Buy.vat.label'));
			$workSheet->getStyle('H1')->getFont()->setBold(true);
			$workSheet->setCellValue('I1', $this->translate('Buy.stamp.label'));
			$workSheet->getStyle('I1')->getFont()->setBold(true);
			$workSheet->setCellValue('J1', $this->translate('Buy.balanceTtc.label'));
			$workSheet->getStyle('J1')->getFont()->setBold(true);
			$workSheet->setCellValue('K1', $this->translate('Buy.regime.label'));
			$workSheet->getStyle('K1')->getFont()->setBold(true);
			$workSheet->setCellValue('L1', $this->translate('Buy.withholding.label'));
			$workSheet->getStyle('L1')->getFont()->setBold(true);
			$workSheet->setCellValue('M1', $this->translate('Buy.withholding.value.label'));
			$workSheet->getStyle('M1')->getFont()->setBold(true);
			$workSheet->setCellValue('N1', $this->translate('Buy.balanceNet.label'));
			$workSheet->getStyle('N1')->getFont()->setBold(true);
			$workSheet->setCellValue('O1', $this->translate('Buy.paymentType.label'));
			$workSheet->getStyle('O1')->getFont()->setBold(true);
			$workSheet->setCellValue('P1', $this->translate('Buy.dtPayment.label'));
			$workSheet->getStyle('P1')->getFont()->setBold(true);
			$workSheet->setCellValue('Q1', $this->translate('Buy.account.label'));
			$workSheet->getStyle('Q1')->getFont()->setBold(true);
			$workSheet->setCellValue('R1', $this->translate('Buy.nature.label'));
			$workSheet->getStyle('R1')->getFont()->setBold(true);
			$workSheet->setCellValue('S1', $this->translate('Buy.transactionStatus.label'));
			$workSheet->getStyle('S1')->getFont()->setBold(true);
			$workSheet->setCellValue('T1', $this->translate('Buy.otherInfos.label'));
			$workSheet->getStyle('T1')->getFont()->setBold(true);

			$workSheet->getStyle('A1:T1')->applyFromArray(array(
				'fill' => array(
					'type' => \PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array(
						'rgb' => '94ccdf'
					)
				)
			));

			$suppliersConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
				'name' => 'suppliersPrefix'
			));
			if (null == $suppliersConstStr) {
				$suppliersConstStr = new ConstantStr();
				$suppliersConstStr->setName('suppliersPrefix');
				$suppliersConstStr->setValue('401');
				$em->persist($suppliersConstStr);
				$em->flush();
			}
			$suppliersPrefix = $suppliersConstStr->getValue();
			$this->gvars['suppliersPrefix'] = $suppliersPrefix;

			$i = 1;

			// $currencyFormatter = new \NumberFormatter($this->getRequest()->getLocale(), \NumberFormatter::CURRENCY);
			// $balance = $currencyFormatter->formatCurrency($balance, 'TND');

			foreach ($buys as $buy) {
				$i++;

				$workSheet->setCellValue('A' . $i, $buy->getNumber(), \PHPExcel_Cell_DataType::TYPE_STRING2);
				$workSheet->setCellValue('B' . $i, \PHPExcel_Shared_Date::PHPToExcel($buy->getDtActivation()), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$workSheet->getStyle('B' . $i)->getNumberFormat()->setFormatCode('dd/mm/yyyy');
				$workSheet->setCellValue('C' . $i, $buy->getBill(), \PHPExcel_Cell_DataType::TYPE_STRING2);
				$workSheet->setCellValue('D' . $i, $buy->getRelation()->getLabel(), \PHPExcel_Cell_DataType::TYPE_STRING2);
				$numb = $suppliersPrefix . $buy->getRelation()->getNumberFormated();
				$workSheet->setCellValueExplicit('E' . $i, $numb, \PHPExcel_Cell_DataType::TYPE_STRING2);
				$workSheet->setCellValue('F' . $i, $buy->getLabel(), \PHPExcel_Cell_DataType::TYPE_STRING2);

				$balanceHt = $buy->getBalanceTtc() - $buy->getStamp() - $buy->getVat();
				// $balanceHt = $currencyFormatter->formatCurrency($balanceHt, 'TND');
				$workSheet->setCellValue('G' . $i, $balanceHt);
				$workSheet->getStyle('G' . $i)->getNumberFormat()->setFormatCode('#,##0.000');
				$workSheet->setCellValue('H' . $i, $buy->getVat());
				$workSheet->getStyle('H' . $i)->getNumberFormat()->setFormatCode('#,##0.000');
				$workSheet->setCellValue('I' . $i, $buy->getStamp());
				$workSheet->getStyle('I' . $i)->getNumberFormat()->setFormatCode('#,##0.000');
				$workSheet->setCellValue('J' . $i, $buy->getBalanceTtc());
				$workSheet->getStyle('J' . $i)->getNumberFormat()->setFormatCode('#,##0.000');
				$workSheet->setCellValue('K' . $i, $this->translate('Buy.regime.' . $buy->getRegime()), \PHPExcel_Cell_DataType::TYPE_STRING2);
				$withholding = $buy->getBalanceTtc() - $buy->getBalanceNet();
				$workSheet->setCellValue('L' . $i, $withholding);
				$workSheet->getStyle('L' . $i)->getNumberFormat()->setFormatCode('#,##0.000');
				$workSheet->setCellValue('M' . $i, $buy->getWithholding()->getValue() / 100);
				$workSheet->getStyle('M' . $i)->getNumberFormat()->setFormatCode('#,##0.00%');
				$workSheet->setCellValue('N' . $i, $buy->getBalanceNet());
				$workSheet->getStyle('N' . $i)->getNumberFormat()->setFormatCode('#,##0.000');
				$workSheet->setCellValue('O' . $i, $this->translate('Transaction.paymentType.' . $buy->getPaymentType()), \PHPExcel_Cell_DataType::TYPE_STRING2);
				$workSheet->setCellValue('P' . $i, \PHPExcel_Shared_Date::PHPToExcel($buy->getDtPayment()), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$workSheet->getStyle('P' . $i)->getNumberFormat()->setFormatCode('dd/mm/yyyy');
				$workSheet->setCellValue('Q' . $i, $buy->getAccount()->getLabel(), \PHPExcel_Cell_DataType::TYPE_STRING2);
				if (null == $buy->getNature()) {
					$workSheet->setCellValue('R' . $i, 'ACHATS DE MARCHANDISES', \PHPExcel_Cell_DataType::TYPE_STRING2);
				} else {
					$workSheet->setCellValue('R' . $i, $buy->getNature()->getLabel(), \PHPExcel_Cell_DataType::TYPE_STRING2);
				}
				$workSheet->setCellValue('S' . $i, $this->translate('Transaction.transactionStatus.' . $buy->getTransactionStatus()), \PHPExcel_Cell_DataType::TYPE_STRING2);
				$workSheet->setCellValue('T' . $i, $buy->getOtherInfos(), \PHPExcel_Cell_DataType::TYPE_STRING2);

				if ($i % 2 == 1) {
					$workSheet->getStyle('A' . $i . ':T' . $i)->applyFromArray(array(
						'fill' => array(
							'type' => \PHPExcel_Style_Fill::FILL_SOLID,
							'color' => array(
								'rgb' => 'd8f1f5'
							)
						)
					));
				} else {
					$workSheet->getStyle('A' . $i . ':T' . $i)->applyFromArray(array(
						'fill' => array(
							'type' => \PHPExcel_Style_Fill::FILL_SOLID,
							'color' => array(
								'rgb' => 'bfbfbf'
							)
						)
					));
				}
			}

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
			$workSheet->getColumnDimension('K')->setAutoSize(true);
			$workSheet->getColumnDimension('L')->setAutoSize(true);
			$workSheet->getColumnDimension('M')->setAutoSize(true);
			$workSheet->getColumnDimension('N')->setAutoSize(true);
			$workSheet->getColumnDimension('O')->setAutoSize(true);
			$workSheet->getColumnDimension('P')->setAutoSize(true);
			$workSheet->getColumnDimension('Q')->setAutoSize(true);
			$workSheet->getColumnDimension('R')->setAutoSize(true);
			$workSheet->getColumnDimension('S')->setAutoSize(true);
			$workSheet->getColumnDimension('T')->setAutoSize(true);

			$writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
			$response = $this->get('phpexcel')->createStreamedResponse($writer);

			$response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8');

			$filename = $this->normalize($this->translate('pagetitle.buy.listByMBPurchase', array(
				'%mbpurchase%' => $mbpurchase->getRef(),
				'%company%' => $mbpurchase->getCompany()->getCorporateName()
			)));
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
	}

	/**
	 *
	 * @param integer $year
	 * @param string $uid
	 *
	 * @return unknown|\Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function excelYearAction($year, $uid)
	{
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_admin_company_list');
		}

		$em = $this->getEntityManager();
		try {
			$company = $em->getRepository('AcfDataBundle:Company')->find($uid);

			if (null == $company) {
				$this->flashMsgSession('warning', $this->translate('Company.edit.notfound'));
			} else {

				$mbpurchases = $em->getRepository('AcfDataBundle:MBPurchase')->getAllByYearCompany($year, $company);
				$buys = array();
				foreach ($mbpurchases as $mbpurchase) {
					$buys = array_merge($buys, $mbpurchase->getTransactions()->toArray());
				}

				$phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

				$phpExcelObject->getProperties()->setCreator('Salah Abdelkader Seif Eddine')->setLastModifiedBy($this->getSecurityTokenStorage()->getToken()->getUser()->getFullname())->setTitle($this->translate('pagetitle.buy.list'))->setSubject($this->translate('pagetitle.buy.list'))->setDescription($this->translate('pagetitle.buy.list'))->setKeywords($this->translate('pagetitle.buy.list'))->setCategory('ACEF buy');

				$phpExcelObject->setActiveSheetIndex(0);

				$workSheet = $phpExcelObject->getActiveSheet();
				$workSheet->setTitle($this->translate('pagetitle.buy.listExcel', array(
					'%mbpurchase%' => $year
				)));

				$workSheet->setCellValue('A1', $this->translate('Buy.number.label'));
				$workSheet->getStyle('A1')->getFont()->setBold(true);
				$workSheet->setCellValue('B1', $this->translate('Buy.dtActivation.label'));
				$workSheet->getStyle('B1')->getFont()->setBold(true);
				$workSheet->setCellValue('C1', $this->translate('Buy.bill.label'));
				$workSheet->getStyle('C1')->getFont()->setBold(true);
				$workSheet->setCellValue('D1', $this->translate('Buy.relation.label'));
				$workSheet->getStyle('D1')->getFont()->setBold(true);
				$workSheet->setCellValue('E1', $this->translate('Buy.relation.number'));
				$workSheet->getStyle('E1')->getFont()->setBold(true);
				$workSheet->setCellValue('F1', $this->translate('Buy.label.label'));
				$workSheet->getStyle('F1')->getFont()->setBold(true);
				$workSheet->setCellValue('G1', $this->translate('Buy.balanceHt.label'));
				$workSheet->getStyle('G1')->getFont()->setBold(true);
				$workSheet->setCellValue('H1', $this->translate('Buy.vat.label'));
				$workSheet->getStyle('H1')->getFont()->setBold(true);
				$workSheet->setCellValue('I1', $this->translate('Buy.stamp.label'));
				$workSheet->getStyle('I1')->getFont()->setBold(true);
				$workSheet->setCellValue('J1', $this->translate('Buy.balanceTtc.label'));
				$workSheet->getStyle('J1')->getFont()->setBold(true);
				$workSheet->setCellValue('K1', $this->translate('Buy.regime.label'));
				$workSheet->getStyle('K1')->getFont()->setBold(true);
				$workSheet->setCellValue('L1', $this->translate('Buy.withholding.label'));
				$workSheet->getStyle('L1')->getFont()->setBold(true);
				$workSheet->setCellValue('M1', $this->translate('Buy.withholding.value.label'));
				$workSheet->getStyle('M1')->getFont()->setBold(true);
				$workSheet->setCellValue('N1', $this->translate('Buy.balanceNet.label'));
				$workSheet->getStyle('N1')->getFont()->setBold(true);
				$workSheet->setCellValue('O1', $this->translate('Buy.paymentType.label'));
				$workSheet->getStyle('O1')->getFont()->setBold(true);
				$workSheet->setCellValue('P1', $this->translate('Buy.dtPayment.label'));
				$workSheet->getStyle('P1')->getFont()->setBold(true);
				$workSheet->setCellValue('Q1', $this->translate('Buy.account.label'));
				$workSheet->getStyle('Q1')->getFont()->setBold(true);
				$workSheet->setCellValue('R1', $this->translate('Buy.nature.label'));
				$workSheet->getStyle('R1')->getFont()->setBold(true);
				$workSheet->setCellValue('S1', $this->translate('Buy.transactionStatus.label'));
				$workSheet->getStyle('S1')->getFont()->setBold(true);
				$workSheet->setCellValue('T1', $this->translate('Buy.otherInfos.label'));
				$workSheet->getStyle('T1')->getFont()->setBold(true);

				$workSheet->getStyle('A1:T1')->applyFromArray(array(
					'fill' => array(
						'type' => \PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array(
							'rgb' => '94ccdf'
						)
					)
				));

				$suppliersConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
					'name' => 'suppliersPrefix'
				));
				if (null == $suppliersConstStr) {
					$suppliersConstStr = new ConstantStr();
					$suppliersConstStr->setName('suppliersPrefix');
					$suppliersConstStr->setValue('401');
					$em->persist($suppliersConstStr);
					$em->flush();
				}
				$suppliersPrefix = $suppliersConstStr->getValue();
				$this->gvars['suppliersPrefix'] = $suppliersPrefix;

				$i = 1;

				// $currencyFormatter = new \NumberFormatter($this->getRequest()->getLocale(), \NumberFormatter::CURRENCY);
				// $balance = $currencyFormatter->formatCurrency($balance, 'TND');

				foreach ($buys as $buy) {
					$i++;

					$workSheet->setCellValue('A' . $i, $buy->getNumber(), \PHPExcel_Cell_DataType::TYPE_STRING2);
					$workSheet->setCellValue('B' . $i, \PHPExcel_Shared_Date::PHPToExcel($buy->getDtActivation()), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
					$workSheet->getStyle('B' . $i)->getNumberFormat()->setFormatCode('dd/mm/yyyy');
					$workSheet->setCellValue('C' . $i, $buy->getBill(), \PHPExcel_Cell_DataType::TYPE_STRING2);
					$workSheet->setCellValue('D' . $i, $buy->getRelation()->getLabel(), \PHPExcel_Cell_DataType::TYPE_STRING2);
					$numb = $suppliersPrefix . $buy->getRelation()->getNumberFormated();
					$workSheet->setCellValueExplicit('E' . $i, $numb, \PHPExcel_Cell_DataType::TYPE_STRING2);
					$workSheet->setCellValue('F' . $i, $buy->getLabel(), \PHPExcel_Cell_DataType::TYPE_STRING2);

					$balanceHt = $buy->getBalanceTtc() - $buy->getStamp() - $buy->getVat();
					// $balanceHt = $currencyFormatter->formatCurrency($balanceHt, 'TND');
					$workSheet->setCellValue('G' . $i, $balanceHt);
					$workSheet->getStyle('G' . $i)->getNumberFormat()->setFormatCode('#,##0.000');
					$workSheet->setCellValue('H' . $i, $buy->getVat());
					$workSheet->getStyle('H' . $i)->getNumberFormat()->setFormatCode('#,##0.000');
					$workSheet->setCellValue('I' . $i, $buy->getStamp());
					$workSheet->getStyle('I' . $i)->getNumberFormat()->setFormatCode('#,##0.000');
					$workSheet->setCellValue('J' . $i, $buy->getBalanceTtc());
					$workSheet->getStyle('J' . $i)->getNumberFormat()->setFormatCode('#,##0.000');
					$workSheet->setCellValue('K' . $i, $this->translate('Buy.regime.' . $buy->getRegime()), \PHPExcel_Cell_DataType::TYPE_STRING2);
					$withholding = $buy->getBalanceTtc() - $buy->getBalanceNet();
					$workSheet->setCellValue('L' . $i, $withholding);
					$workSheet->getStyle('L' . $i)->getNumberFormat()->setFormatCode('#,##0.000');
					$workSheet->setCellValue('M' . $i, $buy->getWithholding()->getValue() / 100);
					$workSheet->getStyle('M' . $i)->getNumberFormat()->setFormatCode('#,##0.00%');
					$workSheet->setCellValue('N' . $i, $buy->getBalanceNet());
					$workSheet->getStyle('N' . $i)->getNumberFormat()->setFormatCode('#,##0.000');
					$workSheet->setCellValue('O' . $i, $this->translate('Transaction.paymentType.' . $buy->getPaymentType()), \PHPExcel_Cell_DataType::TYPE_STRING2);
					$workSheet->setCellValue('P' . $i, \PHPExcel_Shared_Date::PHPToExcel($buy->getDtPayment()), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
					$workSheet->getStyle('P' . $i)->getNumberFormat()->setFormatCode('dd/mm/yyyy');
					$workSheet->setCellValue('Q' . $i, $buy->getAccount()->getLabel(), \PHPExcel_Cell_DataType::TYPE_STRING2);
					if (null == $buy->getNature()) {
						$workSheet->setCellValue('R' . $i, 'ACHATS DE MARCHANDISES', \PHPExcel_Cell_DataType::TYPE_STRING2);
					} else {
						$workSheet->setCellValue('R' . $i, $buy->getNature()->getLabel(), \PHPExcel_Cell_DataType::TYPE_STRING2);
					}
					$workSheet->setCellValue('S' . $i, $this->translate('Transaction.transactionStatus.' . $buy->getTransactionStatus()), \PHPExcel_Cell_DataType::TYPE_STRING2);
					$workSheet->setCellValue('T' . $i, $buy->getOtherInfos(), \PHPExcel_Cell_DataType::TYPE_STRING2);

					if ($i % 2 == 1) {
						$workSheet->getStyle('A' . $i . ':T' . $i)->applyFromArray(array(
							'fill' => array(
								'type' => \PHPExcel_Style_Fill::FILL_SOLID,
								'color' => array(
									'rgb' => 'd8f1f5'
								)
							)
						));
					} else {
						$workSheet->getStyle('A' . $i . ':T' . $i)->applyFromArray(array(
							'fill' => array(
								'type' => \PHPExcel_Style_Fill::FILL_SOLID,
								'color' => array(
									'rgb' => 'bfbfbf'
								)
							)
						));
					}
				}

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
				$workSheet->getColumnDimension('K')->setAutoSize(true);
				$workSheet->getColumnDimension('L')->setAutoSize(true);
				$workSheet->getColumnDimension('M')->setAutoSize(true);
				$workSheet->getColumnDimension('N')->setAutoSize(true);
				$workSheet->getColumnDimension('O')->setAutoSize(true);
				$workSheet->getColumnDimension('P')->setAutoSize(true);
				$workSheet->getColumnDimension('Q')->setAutoSize(true);
				$workSheet->getColumnDimension('R')->setAutoSize(true);
				$workSheet->getColumnDimension('S')->setAutoSize(true);
				$workSheet->getColumnDimension('T')->setAutoSize(true);

				$writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
				$response = $this->get('phpexcel')->createStreamedResponse($writer);

				$response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8');

				$filename = $this->normalize($this->translate('pagetitle.buy.listByYear', array(
					'%year%' => $year,
					'%company%' => $mbpurchase->getCompany()->getCorporateName()
				)));
				$filename = str_ireplace('"', '|', $filename);
				$filename = str_ireplace(' ', '_', $filename);

				$response->headers->set('Content-Disposition', 'attachment;filename=' . $filename . '.xlsx');
				$response->headers->set('Pragma', 'public');
				$response->headers->set('Cache-Control', 'maxage=1');

				return $response;
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
		}

		return $this->redirect($urlFrom);
	}

	protected function traceEntity(MBPurchase $cloneMBPurchase, MBPurchase $mbpurchase)
	{
		$curUser = $this->getSecurityTokenStorage()->getToken()->getUser();
		$trace = new Trace();
		$trace->setActionId($mbpurchase->getId());
		$trace->setActionType(Trace::AT_UPDATE);
		$trace->setUserId($curUser->getId());
		$trace->setCompanyId($mbpurchase->getCompany()->getId());
		$trace->setUserFullname($curUser->getFullName());
		if (!$this->hasRole('ROLE_SUPERADMIN')) {
			if (!$this->hasRole('ROLE_ADMIN')) {
				$trace->setUserType(Trace::UT_CLIENT);
			} else {
				$trace->setUserType(Trace::UT_ADMIN);
			}
		} else {
			$trace->setUserType(Trace::UT_SUPERADMIN);
		}

		$tableBegin = ': <br><table class="table table-bordered table-condensed table-hover table-striped">';
		$tableBegin .= '<thead><tr><th class="text-left">' . $this->translate('Entity.field') . '</th>';
		$tableBegin .= '<th class="text-left">' . $this->translate('Entity.oldVal') . '</th>';
		$tableBegin .= '<th class="text-left">' . $this->translate('Entity.newVal') . '</th></tr></thead><tbody>';

		$tableEnd = '</tbody></table>';

		$trace->setActionEntity(Trace::AE_MBPURCHASE);
		$trace->setActionId2($mbpurchase->getCompany()->getId());
		$trace->setActionEntity2(Trace::AE_COMPANY);

		$msg = '';

		if ($cloneMBPurchase->getCount() != $mbpurchase->getCount()) {
			$msg .= '<tr><td>' . $this->translate('MBPurchase.count.label') . '</td><td>';
			if ($cloneMBPurchase->getCount() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneMBPurchase->getCount();
			}
			$msg .= '</td><td>';
			if ($mbpurchase->getCount() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $mbpurchase->getCount();
			}
			$msg .= '</td></tr>';
		}

		if (\count(\array_diff($mbpurchase->getDocs()->toArray(), $cloneMBPurchase->getDocs()->toArray())) != 0 || \count(\array_diff($cloneMBPurchase->getDocs()->toArray(), $mbpurchase->getDocs()->toArray())) != 0) {
			$msg .= '<tr><td>' . $this->translate('MBPurchase.docs.label') . '</td><td>';
			if (\count($cloneMBPurchase->getDocs()) == 0) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= '<ul>';
				foreach ($cloneMBPurchase->getDocs() as $doc) {
					$msg .= '<li><a href="' . $this->generateUrl('_admin_doc_editGet', array(
						'uid' => $doc->getId()
					)) . '">' . $doc->getOriginalName() . '</a></li>';
				}
				$msg .= '<ul>';
			}
			$msg .= '</td><td>';
			if (\count($mbpurchase->getDocs()) == 0) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= '<ul>';
				foreach ($mbpurchase->getDocs() as $doc) {
					$msg .= '<li><a href="' . $this->generateUrl('_admin_doc_editGet', array(
						'uid' => $doc->getId()
					)) . '">' . $doc->getOriginalName() . '</a></li>';
				}
				$msg .= '<ul>';
			}
			$msg .= '</td></tr>';
		}

		if ($msg != '') {

			$msg = $tableBegin . $msg . $tableEnd;

			$trace->setMsg($this->translate('MBPurchase.traceEdit', array(
				'%mbpurchase%' => $mbpurchase->getRef(),
				'%company%' => $mbpurchase->getCompany()->getCorporateName()
			)) . $msg);
			$trace->setDtCrea(new \DateTime('now'));
			$em = $this->getEntityManager();
			$em->persist($trace);
			$em->flush();
		}
	}
}
