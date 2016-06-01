<?php
namespace Acf\AdminBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\AdminBundle\Form\MBSale\UpdateCountTForm as MBSaleUpdateCountTForm;
use Acf\AdminBundle\Form\Sale\NewTForm as SaleNewTForm;
use Acf\AdminBundle\Form\Sale\ImportTForm as SaleImportTForm;
use Acf\AdminBundle\Form\MBSale\UpdateDocsTForm as MBSaleUpdateDocsTForm;
use Acf\AdminBundle\Form\Doc\NewTForm as DocNewTForm;
use Acf\DataBundle\Entity\Doc;
use Acf\DataBundle\Entity\Sale;
use Acf\DataBundle\Entity\MBSale;
use Acf\DataBundle\Entity\ConstantStr;
use Acf\DataBundle\Entity\Trace;
use Acf\DataBundle\Entity\Customer;
use Acf\DataBundle\Entity\SecondaryVat;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class MBSaleController extends BaseController
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
     * @param string $uid
     *
     * @return Response
     **/
    public function editGetAction($uid)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_company_list');
        }

        $em = $this->getEntityManager();
        try {
            $mbsale = $em->getRepository('AcfDataBundle:MBSale')->find($uid);

            if (null == $mbsale) {
                $this->flashMsgSession('warning', $this->translate('MBSale.edit.notfound'));
            } else {
                $traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($mbsale->getId(), Trace::AE_MBSALE);
                $this->gvars['traces'] = array_reverse($traces);
                $mbsaleUpdateCountForm = $this->createForm(MBSaleUpdateCountTForm::class, $mbsale);
                $sale = new Sale();
                $sale->setMonthlyBalance($mbsale);
                $saleNewForm = $this->createForm(SaleNewTForm::class, $sale, array(
                    'monthlybalance' => $mbsale
                ));
                $saleImportForm = $this->createForm(SaleImportTForm::class);
                $mbsaleUpdateDocsForm = $this->createForm(MBSaleUpdateDocsTForm::class, $mbsale, array(
                    'company' => $mbsale->getCompany()
                ));

                $doc = new Doc();
                $doc->setCompany($mbsale->getCompany());
                $docNewForm = $this->createForm(DocNewTForm::class, $doc, array(
                    'company' => $mbsale->getCompany()
                ));

                $this->gvars['mbsale'] = $mbsale;
                $this->gvars['sale'] = $sale;
                $this->gvars['doc'] = $doc;
                $this->gvars['SaleNewForm'] = $saleNewForm->createView();
                $this->gvars['SaleImportForm'] = $saleImportForm->createView();
                $this->gvars['MBSaleUpdateCountForm'] = $mbsaleUpdateCountForm->createView();
                $this->gvars['MBSaleUpdateDocsForm'] = $mbsaleUpdateDocsForm->createView();
                $this->gvars['DocNewForm'] = $docNewForm->createView();

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');

                $this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
                $this->getSession()->remove('stabActive');

                $customersConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
                    'name' => 'customersPrefix'
                ));
                if (null == $customersConstStr) {
                    $customersConstStr = new ConstantStr();
                    $customersConstStr->setName('customersPrefix');
                    $customersConstStr->setValue('411');
                    $em->persist($customersConstStr);
                    $em->flush();
                }
                $customersPrefix = $customersConstStr->getValue();
                $this->gvars['customersPrefix'] = $customersPrefix;

                $this->gvars['pagetitle'] = $this->translate('pagetitle.mbsale.edit', array(
                    '%mbsale%' => $mbsale->getRef()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.mbsale.edit.txt', array(
                    '%mbsale%' => $mbsale->getRef()
                ));

                return $this->renderResponse('AcfAdminBundle:MBSale:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }

    /**
     * @param string $uid
     *
     * @return Response
     **/
    public function editPostAction($uid)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_company_list');
        }

        $em = $this->getEntityManager();
        try {
            $mbsale = $em->getRepository('AcfDataBundle:MBSale')->find($uid);

            if (null == $mbsale) {
                $this->flashMsgSession('warning', $this->translate('MBSale.edit.notfound'));
            } else {
                $traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($mbsale->getId(), Trace::AE_MBSALE);
                $this->gvars['traces'] = array_reverse($traces);
                $mbsaleUpdateCountForm = $this->createForm(MBSaleUpdateCountTForm::class, $mbsale);
                $sale = new Sale();
                $sale->setMonthlyBalance($mbsale);
                $saleNewForm = $this->createForm(SaleNewTForm::class, $sale, array(
                    'monthlybalance' => $mbsale
                ));
                $saleImportForm = $this->createForm(SaleImportTForm::class);
                $mbsaleUpdateDocsForm = $this->createForm(MBSaleUpdateDocsTForm::class, $mbsale, array(
                    'company' => $mbsale->getCompany()
                ));

                $doc = new Doc();
                $doc->setCompany($mbsale->getCompany());
                $docNewForm = $this->createForm(DocNewTForm::class, $doc, array(
                    'company' => $mbsale->getCompany()
                ));

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
                $this->getSession()->remove('stabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                $cloneMBSale = clone $mbsale;

                if (isset($reqData['SaleImportForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $this->gvars['stabActive'] = 1;
                    $this->getSession()->set('stabActive', 1);
                    $saleImportForm->handleRequest($request);
                    if ($saleImportForm->isValid()) {

                        ini_set('memory_limit', '4096M');
                        ini_set('max_execution_time', '0');
                        $extension = $saleImportForm['excel']->getData()->guessExtension();
                        if ($extension == 'zip') {
                            $extension = 'xlsx';
                        }

                        $filename = uniqid() . '.' . $extension;
                        $saleImportForm['excel']->getData()->move($this->getParameter('adapter_files'), $filename);
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

                        $customersConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
                            'name' => 'customersPrefix'
                        ));
                        if (null == $customersConstStr) {
                            $customersConstStr = new ConstantStr();
                            $customersConstStr->setName('customersPrefix');
                            $customersConstStr->setValue('411');
                            $em->persist($customersConstStr);
                            $em->flush();
                        }
                        $customersPrefix = $customersConstStr->getValue();
                        $customersPrefixNum = \intval($customersPrefix) * 1000000000;

                        $worksheet = $excelObj->getActiveSheet();
                        $highestRow = $worksheet->getHighestRow();
                        $lineRead = 0;
                        $salesNew = 0;
                        $lineUnprocessed = 0;
                        $lineError = 0;

                        $company = $mbsale->getCompany();

                        $accounts = $em->getRepository('AcfDataBundle:Account')->getAllByCompany($company);
                        $customers = $em->getRepository('AcfDataBundle:Customer')->getAllByCompany($company);
                        $withholdings = $em->getRepository('AcfDataBundle:Withholding')->getAllByCompany($company);

                        $prevbill = null;
                        $haspreverror = false;

                        for ($row = 1; $row <= $highestRow; $row++) {
                            $lineRead++;

                            $dtActivation = \PHPExcel_Shared_Date::ExcelToPHPObject($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                            $bill = \trim(\strval($worksheet->getCellByColumnAndRow(2, $row)->getValue()));
                            $customerNum = \intval($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                            $label = \trim(\strval($worksheet->getCellByColumnAndRow(5, $row)->getValue()));
                            $vat = \floatval($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                            $stamp = \floatval($worksheet->getCellByColumnAndRow(8, $row)->getValue());
                            $balanceTtc = \floatval($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                            $vatInfo = \trim(\strval($worksheet->getCellByColumnAndRow(10, $row)->getValue()));
                            $regime = \trim(\strval($worksheet->getCellByColumnAndRow(11, $row)->getValue()));
                            $withholdingValue = \trim(\strval($worksheet->getCellByColumnAndRow(13, $row)->getValue() * 100));
                            $balanceNet = \floatval($worksheet->getCellByColumnAndRow(14, $row)->getValue());
                            $paymentType = \trim(\strval($worksheet->getCellByColumnAndRow(15, $row)->getValue()));
                            $dtPayment = \PHPExcel_Shared_Date::ExcelToPHPObject($worksheet->getCellByColumnAndRow(16, $row)->getValue());
                            $accountLabel = \trim(\strval($worksheet->getCellByColumnAndRow(17, $row)->getValue()));
                            $status = \trim(\strval($worksheet->getCellByColumnAndRow(18, $row)->getValue()));
                            $otherInfos = \trim(\strval($worksheet->getCellByColumnAndRow(19, $row)->getValue()));

                            if ($customerNum != '' && \is_numeric($customerNum)) {
                                $customerNum = \intval($customerNum) - $customersPrefixNum;
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

                            if ($customerNum == '' || $customerNum <= 0) {
                                $haserror = true;
                                $oldcustomnum = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                                $log .= 'ligne ' . $lineRead . ', erreur : Numéro Client ('.$oldcustomnum.')<br>';
                            } else {
                                $customer = null;
                                $knownCustomer = false;
                                foreach ($customers as $s) {
                                    if ($s->getNumber() == $customerNum) {
                                        $knownCustomer = true;
                                        $customer = $s;
                                    }
                                }

                                if ($knownCustomer == false) {
                                    $haserror = true;
                                    $log .= 'ligne ' . $lineRead . ', erreur : Client Inconnu<br>';
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

                            if ($vatInfo == $this->translate('Transaction.vatInfo.0')) {
                                $vatInfo = 0;
                            } elseif ($vatInfo == $this->translate('Transaction.vatInfo.6')) {
                                $vatInfo = 6;
                            } elseif ($vatInfo == $this->translate('Transaction.vatInfo.12')) {
                                $vatInfo = 12;
                            } elseif ($vatInfo == $this->translate('Transaction.vatInfo.18')) {
                                $vatInfo = 18;
                            } else {
                                $vatInfo = 0;
                                $log .= 'ligne ' . $lineRead . ', erreur (ignorée) : TVA PR INFO inconnu => ' . $this->translate('Transaction.vatInfo.0') . '<br>';
                            }

                            if ($regime == $this->translate('Sale.regime.0')) {
                                $regime = 0;
                            } elseif ($regime == $this->translate('Sale.regime.1')) {
                                $regime = 1;
                            } elseif ($regime == $this->translate('Sale.regime.2')) {
                                $regime = 2;
                            } elseif ($regime == $this->translate('Sale.regime.3')) {
                                $regime = 3;
                            } else {
                                $regime = 0;
                                $log .= 'ligne ' . $lineRead . ', erreur (ignorée) : Régime inconnu => ' . $this->translate('Sale.regime.0') . '<br>';
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
                                $log .= 'ligne ' . $lineRead . ', erreur : Retenue Inconnue '.$withholdingValue.'<br>';
                            }

                            if ($balanceNet < 0) {
                                $haserror = true;
                                $log .= 'ligne ' . $lineRead . ', erreur : Net à Encaisser<br>';
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
                                $log .= 'ligne ' . $lineRead . ", erreur (ignorée) : Type d'Encaissement inconnu => " . $this->translate('Transaction.paymentType.0') . '<br>';
                            }

                            if (null == $dtPayment) {
                                $haserror = true;
                                $log .= 'ligne ' . $lineRead . ", erreur : Date d'Encaissement<br>";
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

                                if ($bill != $prevbill) {
                                    $sale = $em->getRepository('AcfDataBundle:Sale')->findOneBy(array(
                                        'monthlyBalance' => $mbsale,
                                        'bill' => $bill
                                    ));
                                    if (null == $sale) {
                                        $salesNew++;

                                        $sale = new Sale();
                                        $sale->setMonthlyBalance($mbsale);

                                        $sale->setNumber($mbsale->getCount());
                                        $sale->setDtActivation($dtActivation);
                                        $sale->setBill($bill);
                                        $sale->setRelation($customer);
                                        $sale->setLabel($label);
                                        $sale->setVat($vat);
                                        $sale->setVatDevise($vat);
                                        $sale->setStamp($stamp);
                                        $sale->setStampDevise($stamp);
                                        $sale->setBalanceTtc($balanceTtc);
                                        $sale->setBalanceTtcDevise($balanceTtc);
                                        $sale->setVatInfo($vatInfo);
                                        $sale->setRegime($regime);
                                        $sale->setWithholding($withholding);
                                        $sale->setBalanceNet($balanceNet);
                                        $sale->setBalanceNetDevise($balanceNet);
                                        $sale->setPaymentType($paymentType);
                                        $sale->setDtPayment($dtPayment);
                                        $sale->setAccount($account);
                                        $sale->setTransactionStatus($status);
                                        $sale->setOtherInfos($otherInfos);

                                        $em->persist($sale);
                                        $mbsale->updateCount();
                                        $em->persist($mbsale);
                                        $haspreverror = false;
                                    } else {
                                        $lineUnprocessed++;
                                        $log .= "l'Achat à la ligne " . $lineRead . ' existe déjà<br>';
                                        $haspreverror = true;
                                    }
                                } else {
                                    if ($haspreverror == false) {
                                        $secondaryVat = new SecondaryVat();
                                        $secondaryVat->setSale($sale);
                                        $secondaryVat->setBalanceNet($balanceNet);
                                        $secondaryVat->setBalanceTtc($balanceTtc);
                                        $secondaryVat->setVat($vat);
                                        $secondaryVat->setVatInfo($vatInfo);
                                        $em->persist($secondaryVat);
                                    } else {
                                        $lineUnprocessed++;
                                        $log .= "l'Achat à la ligne " . $lineRead . ' existe déjà<br>';
                                        $haspreverror = true;
                                    }
                                }
                            } else {
                                $lineError++;
                                $log .= 'la ligne ' . $lineRead . ' contient des erreurs<br>';
                            }

                            $prevbill = $bill;
                        }
                        $em->flush();

                        $log .= $lineRead . ' lignes lues<br>';
                        $log .= $salesNew . ' nouvelles Ventes<br>';
                        $log .= $lineUnprocessed . ' Ventes déjà dans la base<br>';
                        $log .= $lineError . ' lignes contenant des erreurs<br>'; // */

                        $this->flashMsgSession('log', $log);

                        $this->flashMsgSession('success', $this->translate('Sale.import.success'));

                        $this->gvars['tabActive'] = 1;
                        $this->getSession()->set('tabActive', 1);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($mbsale);

                        $this->flashMsgSession('error', $this->translate('Sale.import.failure'));
                    }
                } elseif (isset($reqData['SaleNewForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $this->gvars['stabActive'] = 2;
                    $this->getSession()->set('stabActive', 2);
                    $saleNewForm->handleRequest($request);
                    if ($saleNewForm->isValid()) {
                        $sale->setNumber($mbsale->getCount());
                        if ($sale->getDevise() == 'TND') {
                            $sale->setConversionRate(1);
                            $sale->setVatDevise($sale->getVat());
                            $sale->setStampDevise($sale->getStamp());
                            $sale->setBalanceTtcDevise($sale->getBalanceTtc());
                            $sale->setBalanceNetDevise($sale->getBalanceNet());
                        } else {
                            $sale->setVat($sale->getVatDevise() * $sale->getConversionRate());
                            $sale->setStamp($sale->getStampDevise() * $sale->getConversionRate());
                            $sale->setBalanceTtc($sale->getBalanceTtcDevise() * $sale->getConversionRate());
                            $sale->setBalanceNet($sale->getBalanceNetDevise() * $sale->getConversionRate());
                        }
                        foreach ($saleNewForm->get('docs') as $docNewForm) {
                            $docFile = $docNewForm['fileName']->getData();
                            $docDir = $this->getParameter('kernel.root_dir') . '/../web/res/docs';
                            $originalName = $docFile->getClientOriginalName();
                            $fileName = sha1(uniqid(mt_rand(), true)) . '.' . strtolower($docFile->getClientOriginalExtension());
                            $mimeType = $docFile->getMimeType();
                            $docFile->move($docDir, $fileName);
                            $size = filesize($docDir . '/' . $fileName);
                            $md5 = md5_file($docDir . '/' . $fileName);

                            $doc = $docNewForm->getData();
                            $doc->setCompany($mbsale->getCompany());
                            $doc->setFileName($fileName);
                            $doc->setOriginalName($originalName);
                            $doc->setSize($size);
                            $doc->setMimeType($mimeType);
                            $doc->setMd5($md5);
                            $doc->setDescription($docNewForm['description']->getData());
                            $em->persist($doc);

                            $sale->addDoc($doc);
                        }
                        $em->persist($sale);
                        foreach ($saleNewForm->get('secondaryVats') as $secondaryVatNewForm) {
                            $secondaryVat = $secondaryVatNewForm->getData();
                            $secondaryVat->setSale($sale);
                            $sale->addSecondaryVat($secondaryVat);
                            $em->persist($secondaryVat);
                            $em->persist($sale);
                        }
                        $em->flush();
                        $mbsale->updateCount();
                        $em->persist($mbsale);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Sale.add.success', array(
                            '%sale%' => $sale->getNumber()
                        )));
                        $this->gvars['tabActive'] = 1;
                        $this->getSession()->set('tabActive', 1);
                        $this->gvars['stabActive'] = 1;
                        $this->getSession()->set('stabActive', 1);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($mbsale);

                        $this->flashMsgSession('error', $this->translate('Sale.add.failure'));
                    }
                } elseif (isset($reqData['MBSaleUpdateCountForm'])) {
                    $this->gvars['tabActive'] = 3;
                    $this->getSession()->set('tabActive', 3);
                    $mbsaleUpdateCountForm->handleRequest($request);
                    if ($mbsaleUpdateCountForm->isValid()) {
                        $em->persist($mbsale);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('MBSale.edit.success', array(
                            '%mbsale%' => $mbsale->getRef()
                        )));

                        $this->traceEntity($cloneMBSale, $mbsale);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($mbsale);

                        $this->flashMsgSession('error', $this->translate('MBSale.edit.failure', array(
                            '%mbsale%' => $mbsale->getRef()
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
                            $doc->setCompany($mbsale->getCompany());

                            $doc->setFileName($fileName);
                            $doc->setOriginalName($originalName);
                            $doc->setSize($size);
                            $doc->setMimeType($mimeType);
                            $doc->setMd5($md5);
                            $doc->setDescription($docNewForm['description']->getData());
                            $em->persist($doc);

                            $mbsale->addDoc($doc);

                            $docNames .= $doc->getOriginalName() . ' ';
                        }

                        $em->persist($mbsale);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Doc.add.success', array(
                            '%doc%' => $docNames
                        )));
                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        $this->traceEntity($cloneMBSale, $mbsale);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($mbsale);

                        $this->flashMsgSession('error', $this->translate('Doc.add.failure'));
                    }
                } elseif (isset($reqData['MBSaleUpdateDocsForm'])) {
                    $this->gvars['tabActive'] = 4;
                    $this->getSession()->set('tabActive', 4);
                    $this->gvars['stabActive'] = 2;
                    $this->getSession()->set('stabActive', 2);
                    $mbsaleUpdateDocsForm->handleRequest($request);
                    if ($mbsaleUpdateDocsForm->isValid()) {
                        $em->persist($mbsale);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('MBSale.edit.success', array(
                            '%mbsale%' => $mbsale->getRef()
                        )));
                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        $this->traceEntity($cloneMBSale, $mbsale);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($mbsale);

                        $this->flashMsgSession('error', $this->translate('MBSale.edit.failure', array(
                            '%mbsale%' => $mbsale->getRef()
                        )));
                    }
                }

                $this->gvars['mbsale'] = $mbsale;
                $this->gvars['sale'] = $sale;
                $this->gvars['doc'] = $doc;
                $this->gvars['SaleNewForm'] = $saleNewForm->createView();
                $this->gvars['SaleImportForm'] = $saleImportForm->createView();
                $this->gvars['MBSaleUpdateCountForm'] = $mbsaleUpdateCountForm->createView();
                $this->gvars['MBSaleUpdateDocsForm'] = $mbsaleUpdateDocsForm->createView();
                $this->gvars['DocNewForm'] = $docNewForm->createView();

                $customersConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
                    'name' => 'customersPrefix'
                ));
                if (null == $customersConstStr) {
                    $customersConstStr = new ConstantStr();
                    $customersConstStr->setName('customersPrefix');
                    $customersConstStr->setValue('411');
                    $em->persist($customersConstStr);
                    $em->flush();
                }
                $customersPrefix = $customersConstStr->getValue();
                $this->gvars['customersPrefix'] = $customersPrefix;

                $this->gvars['pagetitle'] = $this->translate('pagetitle.mbsale.edit', array(
                    '%mbsale%' => $mbsale->getRef()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.mbsale.edit.txt', array(
                    '%mbsale%' => $mbsale->getRef()
                ));

                return $this->renderResponse('AcfAdminBundle:MBSale:edit.html.twig', $this->gvars);
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
            $mbsale = $em->getRepository('AcfDataBundle:MBSale')->find($uid);
            $sales = $mbsale->getTransactions();

            $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

            $phpExcelObject->getProperties()
                ->setCreator('Salah Abdelkader Seif Eddine')
                ->setLastModifiedBy($this->getSecurityTokenStorage()
                    ->getToken()
                    ->getUser()
                ->getFullname())
                ->setTitle($this->translate('pagetitle.sale.list'))
                ->setSubject($this->translate('pagetitle.sale.list'))
                ->setDescription($this->translate('pagetitle.sale.list'))
                ->setKeywords($this->translate('pagetitle.sale.list'))
                ->setCategory('ACF sale');

            $phpExcelObject->setActiveSheetIndex(0);

            $workSheet = $phpExcelObject->getActiveSheet();
            $workSheet->setTitle($this->translate('pagetitle.sale.listExcel', array(
                '%mbsale%' => $mbsale->getRef()
            )));

            $workSheet->setCellValue('A1', $this->translate('Sale.number.label'));
            $workSheet->getStyle('A1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('B1', $this->translate('Sale.dtActivation.label'));
            $workSheet->getStyle('B1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('C1', $this->translate('Sale.bill.label'));
            $workSheet->getStyle('C1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('D1', $this->translate('Sale.relation.label'));
            $workSheet->getStyle('D1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('E1', $this->translate('Sale.relation.number'));
            $workSheet->getStyle('E1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('F1', $this->translate('Sale.label.label'));
            $workSheet->getStyle('F1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('G1', $this->translate('Sale.balanceHt.label'));
            $workSheet->getStyle('G1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('H1', $this->translate('Sale.vat.label'));
            $workSheet->getStyle('H1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('I1', $this->translate('Sale.stamp.label'));
            $workSheet->getStyle('I1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('J1', $this->translate('Sale.balanceTtc.label'));
            $workSheet->getStyle('J1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('K1', $this->translate('Sale.vatInfo.label'));
            $workSheet->getStyle('K1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('L1', $this->translate('Sale.regime.label'));
            $workSheet->getStyle('L1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('M1', $this->translate('Sale.withholding.label'));
            $workSheet->getStyle('M1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('N1', $this->translate('Sale.withholding.value.label'));
            $workSheet->getStyle('N1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('O1', $this->translate('Sale.balanceNet.label'));
            $workSheet->getStyle('O1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('P1', $this->translate('Sale.paymentType.label'));
            $workSheet->getStyle('P1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('Q1', $this->translate('Sale.dtPayment.label'));
            $workSheet->getStyle('Q1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('R1', $this->translate('Sale.account.label'));
            $workSheet->getStyle('R1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('S1', $this->translate('Sale.transactionStatus.label'));
            $workSheet->getStyle('S1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('T1', $this->translate('Sale.otherInfos.label'));
            $workSheet->getStyle('T1')
                ->getFont()
                ->setBold(true);

            $workSheet->getStyle('A1:T1')->applyFromArray(array(
                'fill' => array(
                    'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array(
                        'rgb' => '94ccdf'
                    )
                )
            ));

            $customersConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
                'name' => 'customersPrefix'
            ));
            if (null == $customersConstStr) {
                $customersConstStr = new ConstantStr();
                $customersConstStr->setName('customersPrefix');
                $customersConstStr->setValue('411');
                $em->persist($customersConstStr);
                $em->flush();
            }
            $customersPrefix = $customersConstStr->getValue();
            $this->gvars['customersPrefix'] = $customersPrefix;

            $i = 1;

            // $currencyFormatter = new \NumberFormatter($this->getRequest()->getLocale(), \NumberFormatter::CURRENCY);
            // $balance = $currencyFormatter->formatCurrency($balance, 'TND');

            foreach ($sales as $sale) {
                $i++;

                $workSheet->setCellValue('A' . $i, $sale->getNumber(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('B' . $i, \PHPExcel_Shared_Date::PHPToExcel($sale->getDtActivation()), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $workSheet->getStyle('B' . $i)
                    ->getNumberFormat()
                    ->setFormatCode('dd/mm/yyyy');
                $workSheet->setCellValue('C' . $i, $sale->getBill(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('D' . $i, $sale->getRelation()
                ->getLabel(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $numb = $customersPrefix . $sale->getRelation()->getNumberFormated();
                $workSheet->setCellValueExplicit('E' . $i, $numb, \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('F' . $i, $sale->getLabel(), \PHPExcel_Cell_DataType::TYPE_STRING2);

                $balanceHt = $sale->getBalanceTtc() - $sale->getStamp() - $sale->getVat();
                // $balanceHt = $currencyFormatter->formatCurrency($balanceHt, 'TND');
                $workSheet->setCellValue('G' . $i, $balanceHt);
                $workSheet->getStyle('G' . $i)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.000');
                $workSheet->setCellValue('H' . $i, $sale->getVat());
                $workSheet->getStyle('H' . $i)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.000');
                $workSheet->setCellValue('I' . $i, $sale->getStamp());
                $workSheet->getStyle('I' . $i)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.000');
                $workSheet->setCellValue('J' . $i, $sale->getBalanceTtc());
                $workSheet->getStyle('J' . $i)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.000');
                $workSheet->setCellValue('K' . $i, $this->translate('Transaction.vatInfo.' . $sale->getVatInfo()), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('L' . $i, $this->translate('Sale.regime.' . $sale->getRegime()), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $withholding = $sale->getBalanceTtc() - $sale->getBalanceNet();
                $workSheet->setCellValue('M' . $i, $withholding);
                $workSheet->getStyle('M' . $i)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.000');
                $workSheet->setCellValue('N' . $i, $sale->getWithholding()
                ->getValue() / 100);
                $workSheet->getStyle('N' . $i)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.00%');
                $workSheet->setCellValue('O' . $i, $sale->getBalanceNet());
                $workSheet->getStyle('O' . $i)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.000');
                $workSheet->setCellValue('P' . $i, $this->translate('Transaction.paymentType.' . $sale->getPaymentType()), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('Q' . $i, \PHPExcel_Shared_Date::PHPToExcel($sale->getDtPayment()), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $workSheet->getStyle('Q' . $i)
                    ->getNumberFormat()
                    ->setFormatCode('dd/mm/yyyy');
                $workSheet->setCellValue('R' . $i, $sale->getAccount()
                ->getLabel(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('S' . $i, $this->translate('Transaction.transactionStatus.' . $sale->getTransactionStatus()), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('T' . $i, $sale->getOtherInfos(), \PHPExcel_Cell_DataType::TYPE_STRING2);

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

                foreach ($sale->getSecondaryVats() as $secondaryVat) {
                    $i++;

                    $workSheet->setCellValue('A' . $i, $sale->getNumber(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                    $workSheet->setCellValue('B' . $i, \PHPExcel_Shared_Date::PHPToExcel($sale->getDtActivation()), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    $workSheet->getStyle('B' . $i)
                        ->getNumberFormat()
                        ->setFormatCode('dd/mm/yyyy');
                    $workSheet->setCellValue('C' . $i, $sale->getBill(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                    $workSheet->setCellValue('D' . $i, $sale->getRelation()
                    ->getLabel(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                    // $numb = $customersPrefix . $sale->getRelation()->getNumberFormated();
                    $workSheet->setCellValueExplicit('E' . $i, $numb, \PHPExcel_Cell_DataType::TYPE_STRING2);
                    $workSheet->setCellValue('F' . $i, $sale->getLabel(), \PHPExcel_Cell_DataType::TYPE_STRING2);

                    $balanceHt = $secondaryVat->getBalanceTtc() - $secondaryVat->getVat();
                    // $balanceHt = $currencyFormatter->formatCurrency($balanceHt, 'TND');
                    $workSheet->setCellValue('G' . $i, $balanceHt);
                    $workSheet->getStyle('G' . $i)
                        ->getNumberFormat()
                        ->setFormatCode('#,##0.000');
                    $workSheet->setCellValue('H' . $i, $secondaryVat->getVat());
                    $workSheet->getStyle('H' . $i)
                        ->getNumberFormat()
                        ->setFormatCode('#,##0.000');
                    $workSheet->setCellValue('J' . $i, $secondaryVat->getBalanceTtc());
                    $workSheet->getStyle('J' . $i)
                        ->getNumberFormat()
                        ->setFormatCode('#,##0.000');
                    $workSheet->setCellValue('K' . $i, $this->translate('SecondaryVat.vatInfo.' . $secondaryVat->getVatInfo()), \PHPExcel_Cell_DataType::TYPE_STRING2);
                    $workSheet->setCellValue('L' . $i, $this->translate('Sale.regime.' . $sale->getRegime()), \PHPExcel_Cell_DataType::TYPE_STRING2);
                    $withholding = $secondaryVat->getBalanceTtc() - $secondaryVat->getBalanceNet();
                    $workSheet->setCellValue('M' . $i, $withholding);
                    $workSheet->getStyle('M' . $i)
                        ->getNumberFormat()
                        ->setFormatCode('#,##0.000');
                    $workSheet->setCellValue('N' . $i, $sale->getWithholding()
                    ->getValue() / 100);
                    $workSheet->getStyle('N' . $i)
                        ->getNumberFormat()
                        ->setFormatCode('#,##0.00%');
                    $workSheet->setCellValue('O' . $i, $secondaryVat->getBalanceNet());
                    $workSheet->getStyle('O' . $i)
                        ->getNumberFormat()
                        ->setFormatCode('#,##0.000');
                    $workSheet->setCellValue('P' . $i, $this->translate('Transaction.paymentType.' . $sale->getPaymentType()), \PHPExcel_Cell_DataType::TYPE_STRING2);
                    $workSheet->setCellValue('Q' . $i, \PHPExcel_Shared_Date::PHPToExcel($sale->getDtPayment()), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    $workSheet->getStyle('Q' . $i)
                        ->getNumberFormat()
                        ->setFormatCode('dd/mm/yyyy');
                    $workSheet->setCellValue('R' . $i, $sale->getAccount()
                    ->getLabel(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                    $workSheet->setCellValue('S' . $i, $this->translate('Transaction.transactionStatus.' . $sale->getTransactionStatus()), \PHPExcel_Cell_DataType::TYPE_STRING2);
                    $workSheet->setCellValue('T' . $i, $sale->getOtherInfos(), \PHPExcel_Cell_DataType::TYPE_STRING2);

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

            $filename = $this->normalize($this->translate('pagetitle.sale.listByMBSale', array(
                '%mbsale%' => $mbsale->getRef(),
                '%company%' => $mbsale->getCompany()
                    ->getCorporateName()
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
     * @param string  $uid
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

                $mbsales = $em->getRepository('AcfDataBundle:MBSale')->getAllByYearCompany($year, $company);
                $sales = array();
                foreach ($mbsales as $mbsale) {
                    $sales = array_merge($sales, $mbsale->getTransactions()->toArray());
                }

                $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

                $phpExcelObject->getProperties()
                    ->setCreator('Salah Abdelkader Seif Eddine')
                    ->setLastModifiedBy($this->getSecurityTokenStorage()
                        ->getToken()
                        ->getUser()
                    ->getFullname())
                    ->setTitle($this->translate('pagetitle.sale.list'))
                    ->setSubject($this->translate('pagetitle.sale.list'))
                    ->setDescription($this->translate('pagetitle.sale.list'))
                    ->setKeywords($this->translate('pagetitle.sale.list'))
                    ->setCategory('ACF sale');

                $phpExcelObject->setActiveSheetIndex(0);

                $workSheet = $phpExcelObject->getActiveSheet();
                $workSheet->setTitle($this->translate('pagetitle.sale.listExcel', array(
                    '%mbsale%' => $year
                )));

                $workSheet->setCellValue('A1', $this->translate('Sale.number.label'));
                $workSheet->getStyle('A1')
                    ->getFont()
                    ->setBold(true);
                $workSheet->setCellValue('B1', $this->translate('Sale.dtActivation.label'));
                $workSheet->getStyle('B1')
                    ->getFont()
                    ->setBold(true);
                $workSheet->setCellValue('C1', $this->translate('Sale.bill.label'));
                $workSheet->getStyle('C1')
                    ->getFont()
                    ->setBold(true);
                $workSheet->setCellValue('D1', $this->translate('Sale.relation.label'));
                $workSheet->getStyle('D1')
                    ->getFont()
                    ->setBold(true);
                $workSheet->setCellValue('E1', $this->translate('Sale.relation.number'));
                $workSheet->getStyle('E1')
                    ->getFont()
                    ->setBold(true);
                $workSheet->setCellValue('F1', $this->translate('Sale.label.label'));
                $workSheet->getStyle('F1')
                    ->getFont()
                    ->setBold(true);
                $workSheet->setCellValue('G1', $this->translate('Sale.balanceHt.label'));
                $workSheet->getStyle('G1')
                    ->getFont()
                    ->setBold(true);
                $workSheet->setCellValue('H1', $this->translate('Sale.vat.label'));
                $workSheet->getStyle('H1')
                    ->getFont()
                    ->setBold(true);
                $workSheet->setCellValue('I1', $this->translate('Sale.stamp.label'));
                $workSheet->getStyle('I1')
                    ->getFont()
                    ->setBold(true);
                $workSheet->setCellValue('J1', $this->translate('Sale.balanceTtc.label'));
                $workSheet->getStyle('J1')
                    ->getFont()
                    ->setBold(true);
                $workSheet->setCellValue('K1', $this->translate('Sale.vatInfo.label'));
                $workSheet->getStyle('K1')
                    ->getFont()
                    ->setBold(true);
                $workSheet->setCellValue('L1', $this->translate('Sale.regime.label'));
                $workSheet->getStyle('L1')
                    ->getFont()
                    ->setBold(true);
                $workSheet->setCellValue('M1', $this->translate('Sale.withholding.label'));
                $workSheet->getStyle('M1')
                    ->getFont()
                    ->setBold(true);
                $workSheet->setCellValue('N1', $this->translate('Sale.withholding.value.label'));
                $workSheet->getStyle('N1')
                    ->getFont()
                    ->setBold(true);
                $workSheet->setCellValue('O1', $this->translate('Sale.balanceNet.label'));
                $workSheet->getStyle('O1')
                    ->getFont()
                    ->setBold(true);
                $workSheet->setCellValue('P1', $this->translate('Sale.paymentType.label'));
                $workSheet->getStyle('P1')
                    ->getFont()
                    ->setBold(true);
                $workSheet->setCellValue('Q1', $this->translate('Sale.dtPayment.label'));
                $workSheet->getStyle('Q1')
                    ->getFont()
                    ->setBold(true);
                $workSheet->setCellValue('R1', $this->translate('Sale.account.label'));
                $workSheet->getStyle('R1')
                    ->getFont()
                    ->setBold(true);
                $workSheet->setCellValue('S1', $this->translate('Sale.transactionStatus.label'));
                $workSheet->getStyle('S1')
                    ->getFont()
                    ->setBold(true);
                $workSheet->setCellValue('T1', $this->translate('Sale.otherInfos.label'));
                $workSheet->getStyle('T1')
                    ->getFont()
                    ->setBold(true);

                $workSheet->getStyle('A1:T1')->applyFromArray(array(
                    'fill' => array(
                        'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array(
                            'rgb' => '94ccdf'
                        )
                    )
                ));

                $customersConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
                    'name' => 'customersPrefix'
                ));
                if (null == $customersConstStr) {
                    $customersConstStr = new ConstantStr();
                    $customersConstStr->setName('customersPrefix');
                    $customersConstStr->setValue('411');
                    $em->persist($customersConstStr);
                    $em->flush();
                }
                $customersPrefix = $customersConstStr->getValue();
                $this->gvars['customersPrefix'] = $customersPrefix;

                $i = 1;

                // $currencyFormatter = new \NumberFormatter($this->getRequest()->getLocale(), \NumberFormatter::CURRENCY);
                // $balance = $currencyFormatter->formatCurrency($balance, 'TND');

                foreach ($sales as $sale) {
                    $i++;

                    $workSheet->setCellValue('A' . $i, $sale->getNumber(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                    $workSheet->setCellValue('B' . $i, \PHPExcel_Shared_Date::PHPToExcel($sale->getDtActivation()), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    $workSheet->getStyle('B' . $i)
                        ->getNumberFormat()
                        ->setFormatCode('dd/mm/yyyy');
                    $workSheet->setCellValue('C' . $i, $sale->getBill(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                    $workSheet->setCellValue('D' . $i, $sale->getRelation()
                    ->getLabel(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                    $numb = $customersPrefix . $sale->getRelation()->getNumberFormated();
                    $workSheet->setCellValueExplicit('E' . $i, $numb, \PHPExcel_Cell_DataType::TYPE_STRING2);
                    $workSheet->setCellValue('F' . $i, $sale->getLabel(), \PHPExcel_Cell_DataType::TYPE_STRING2);

                    $balanceHt = $sale->getBalanceTtc() - $sale->getStamp() - $sale->getVat();
                    // $balanceHt = $currencyFormatter->formatCurrency($balanceHt, 'TND');
                    $workSheet->setCellValue('G' . $i, $balanceHt);
                    $workSheet->getStyle('G' . $i)
                        ->getNumberFormat()
                        ->setFormatCode('#,##0.000');
                    $workSheet->setCellValue('H' . $i, $sale->getVat());
                    $workSheet->getStyle('H' . $i)
                        ->getNumberFormat()
                        ->setFormatCode('#,##0.000');
                    $workSheet->setCellValue('I' . $i, $sale->getStamp());
                    $workSheet->getStyle('I' . $i)
                        ->getNumberFormat()
                        ->setFormatCode('#,##0.000');
                    $workSheet->setCellValue('J' . $i, $sale->getBalanceTtc());
                    $workSheet->getStyle('J' . $i)
                        ->getNumberFormat()
                        ->setFormatCode('#,##0.000');
                    $workSheet->setCellValue('K' . $i, $this->translate('Transaction.vatInfo.' . $sale->getVatInfo()), \PHPExcel_Cell_DataType::TYPE_STRING2);
                    $workSheet->setCellValue('L' . $i, $this->translate('Sale.regime.' . $sale->getRegime()), \PHPExcel_Cell_DataType::TYPE_STRING2);
                    $withholding = $sale->getBalanceTtc() - $sale->getBalanceNet();
                    $workSheet->setCellValue('M' . $i, $withholding);
                    $workSheet->getStyle('M' . $i)
                        ->getNumberFormat()
                        ->setFormatCode('#,##0.000');
                    $workSheet->setCellValue('N' . $i, $sale->getWithholding()
                    ->getValue() / 100);
                    $workSheet->getStyle('N' . $i)
                        ->getNumberFormat()
                        ->setFormatCode('#,##0.00%');
                    $workSheet->setCellValue('O' . $i, $sale->getBalanceNet());
                    $workSheet->getStyle('O' . $i)
                        ->getNumberFormat()
                        ->setFormatCode('#,##0.000');
                    $workSheet->setCellValue('P' . $i, $this->translate('Transaction.paymentType.' . $sale->getPaymentType()), \PHPExcel_Cell_DataType::TYPE_STRING2);
                    $workSheet->setCellValue('Q' . $i, \PHPExcel_Shared_Date::PHPToExcel($sale->getDtPayment()), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    $workSheet->getStyle('Q' . $i)
                        ->getNumberFormat()
                        ->setFormatCode('dd/mm/yyyy');
                    $workSheet->setCellValue('R' . $i, $sale->getAccount()
                    ->getLabel(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                    $workSheet->setCellValue('S' . $i, $this->translate('Transaction.transactionStatus.' . $sale->getTransactionStatus()), \PHPExcel_Cell_DataType::TYPE_STRING2);
                    $workSheet->setCellValue('T' . $i, $sale->getOtherInfos(), \PHPExcel_Cell_DataType::TYPE_STRING2);

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

                    foreach ($sale->getSecondaryVats() as $secondaryVat) {
                        $i++;

                        $workSheet->setCellValue('A' . $i, $sale->getNumber(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                        $workSheet->setCellValue('B' . $i, \PHPExcel_Shared_Date::PHPToExcel($sale->getDtActivation()), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $workSheet->getStyle('B' . $i)
                            ->getNumberFormat()
                            ->setFormatCode('dd/mm/yyyy');
                        $workSheet->setCellValue('C' . $i, $sale->getBill(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                        $workSheet->setCellValue('D' . $i, $sale->getRelation()
                        ->getLabel(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                        // $numb = $customersPrefix . $sale->getRelation()->getNumberFormated();
                        $workSheet->setCellValueExplicit('E' . $i, $numb, \PHPExcel_Cell_DataType::TYPE_STRING2);
                        $workSheet->setCellValue('F' . $i, $sale->getLabel(), \PHPExcel_Cell_DataType::TYPE_STRING2);

                        $balanceHt = $secondaryVat->getBalanceTtc() - $secondaryVat->getVat();
                        // $balanceHt = $currencyFormatter->formatCurrency($balanceHt, 'TND');
                        $workSheet->setCellValue('G' . $i, $balanceHt);
                        $workSheet->getStyle('G' . $i)
                            ->getNumberFormat()
                            ->setFormatCode('#,##0.000');
                        $workSheet->setCellValue('H' . $i, $secondaryVat->getVat());
                        $workSheet->getStyle('H' . $i)
                            ->getNumberFormat()
                            ->setFormatCode('#,##0.000');
                        $workSheet->setCellValue('J' . $i, $secondaryVat->getBalanceTtc());
                        $workSheet->getStyle('J' . $i)
                            ->getNumberFormat()
                            ->setFormatCode('#,##0.000');
                        $workSheet->setCellValue('K' . $i, $this->translate('SecondaryVat.vatInfo.' . $secondaryVat->getVatInfo()), \PHPExcel_Cell_DataType::TYPE_STRING2);
                        $workSheet->setCellValue('L' . $i, $this->translate('Sale.regime.' . $sale->getRegime()), \PHPExcel_Cell_DataType::TYPE_STRING2);
                        $withholding = $secondaryVat->getBalanceTtc() - $secondaryVat->getBalanceNet();
                        $workSheet->setCellValue('M' . $i, $withholding);
                        $workSheet->getStyle('M' . $i)
                            ->getNumberFormat()
                            ->setFormatCode('#,##0.000');
                        $workSheet->setCellValue('N' . $i, $sale->getWithholding()
                        ->getValue() / 100);
                        $workSheet->getStyle('N' . $i)
                            ->getNumberFormat()
                            ->setFormatCode('#,##0.00%');
                        $workSheet->setCellValue('O' . $i, $secondaryVat->getBalanceNet());
                        $workSheet->getStyle('O' . $i)
                            ->getNumberFormat()
                            ->setFormatCode('#,##0.000');
                        $workSheet->setCellValue('P' . $i, $this->translate('Transaction.paymentType.' . $sale->getPaymentType()), \PHPExcel_Cell_DataType::TYPE_STRING2);
                        $workSheet->setCellValue('Q' . $i, \PHPExcel_Shared_Date::PHPToExcel($sale->getDtPayment()), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $workSheet->getStyle('Q' . $i)
                            ->getNumberFormat()
                            ->setFormatCode('dd/mm/yyyy');
                        $workSheet->setCellValue('R' . $i, $sale->getAccount()
                        ->getLabel(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                        $workSheet->setCellValue('S' . $i, $this->translate('Transaction.transactionStatus.' . $sale->getTransactionStatus()), \PHPExcel_Cell_DataType::TYPE_STRING2);
                        $workSheet->setCellValue('T' . $i, $sale->getOtherInfos(), \PHPExcel_Cell_DataType::TYPE_STRING2);

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

                $filename = $this->normalize($this->translate('pagetitle.sale.listByYear', array(
                    '%year%' => $year,
                    '%company%' => $mbsale->getCompany()
                        ->getCorporateName()
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

    protected function traceEntity(MBSale $cloneMBSale, MBSale $mbsale)
    {
        $curUser = $this->getSecurityTokenStorage()
            ->getToken()
            ->getUser();
        $trace = new Trace();
        $trace->setActionId($mbsale->getId());
        $trace->setActionType(Trace::AT_UPDATE);
        $trace->setUserId($curUser->getId());
        $trace->setCompanyId($mbsale->getCompany()
        ->getId());
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

        $trace->setActionEntity(Trace::AE_MBSALE);
        $trace->setActionId2($mbsale->getCompany()
        ->getId());
        $trace->setActionEntity2(Trace::AE_COMPANY);

        $msg = '';

        if ($cloneMBSale->getCount() != $mbsale->getCount()) {
            $msg .= '<tr><td>' . $this->translate('MBSale.count.label') . '</td><td>';
            if ($cloneMBSale->getCount() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneMBSale->getCount();
            }
            $msg .= '</td><td>';
            if ($mbsale->getCount() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $mbsale->getCount();
            }
            $msg .= '</td></tr>';
        }

        if (\count(\array_diff($mbsale->getDocs()->toArray(), $cloneMBSale->getDocs()->toArray())) != 0 || \count(\array_diff($cloneMBSale->getDocs()->toArray(), $mbsale->getDocs()->toArray())) != 0) {
            $msg .= '<tr><td>' . $this->translate('MBSale.docs.label') . '</td><td>';
            if (\count($cloneMBSale->getDocs()) == 0) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= '<ul>';
                foreach ($cloneMBSale->getDocs() as $doc) {
                    $msg .= '<li><a href="' . $this->generateUrl('_admin_doc_editGet', array(
                        'uid' => $doc->getId()
                    )) . '">' . $doc->getOriginalName() . '</a></li>';
                }
                $msg .= '<ul>';
            }
            $msg .= '</td><td>';
            if (\count($mbsale->getDocs()) == 0) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= '<ul>';
                foreach ($mbsale->getDocs() as $doc) {
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

            $trace->setMsg($this->translate('MBSale.traceEdit', array(
                '%mbsale%' => $mbsale->getRef(),
                '%company%' => $mbsale->getCompany()
                    ->getCorporateName()
            )) . $msg);
            $trace->setDtCrea(new \DateTime('now'));
            $em = $this->getEntityManager();
            $em->persist($trace);
            $em->flush();
        }
    }
}
