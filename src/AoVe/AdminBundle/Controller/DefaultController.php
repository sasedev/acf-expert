<?php
namespace AoVe\AdminBundle\Controller;

use Acf\DataBundle\Entity\Autoinc;
use Sasedev\Commons\SharedBundle\Controller\BaseController;
use AoVe\AdminBundle\Form\AoAuction\ImportTForm as AuctionImportTForm;
use AoVe\AdminBundle\Form\AoCallfortender\ImportTForm as CallfortenderImportTForm;
use Acf\DataBundle\Entity\AoAuction;
use Acf\DataBundle\Entity\AoCallfortender;

class DefaultController extends BaseController
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
        $this->gvars['menu_active'] = 'aoveadminhome';
    }

    public function indexAction()
    {
        $em = $this->getEntityManager();

        $auctionImportForm = $this->createForm(AuctionImportTForm::class);

        $callfortenderImportForm = $this->createForm(CallfortenderImportTForm::class);

        $request = $this->getRequest();
        $reqData = $request->request->all();

        if (isset($reqData['AoAuctionImportForm'])) {

            $auctionImportForm->handleRequest($request);
            if ($auctionImportForm->isValid()) {
                // $logger = $this->getLogger();

                $imgDir = $this->getParameter('kernel.root_dir') . '/../web/res/Ve';

                $autoinc = $em->getRepository('AcfDataBundle:Autoinc')->findOneBy(array(
                    'name' => 'VE'
                ));
                if (null == $autoinc) {
                    $autoinc = new Autoinc(1, 0);
                    $autoinc->setName('VE');
                } else {
                    $autoinc->setCount($autoinc->getCount() + 1);
                }
                $em->persist($autoinc);
                $em->flush();
                $nextref = $autoinc->getValue();

                ini_set('memory_limit', '4096M');
                ini_set('max_execution_time', '0');
                $extension = $auctionImportForm['excel']->getData()->guessExtension();
                if ($extension == 'zip') {
                    $extension = 'xlsx';
                }

                $filename = uniqid() . '.' . $extension;
                $auctionImportForm['excel']->getData()->move($this->getParameter('adapter_files'), $filename);
                $fullfilename = $this->getParameter('adapter_files');
                $fullfilename .= '/' . $filename;

                $excelObj = $this->get('phpexcel')->createPHPExcelObject($fullfilename);

                $log2 = '';

                $activeSheetIndex = 0;

                $excelObj->setActiveSheetIndex($activeSheetIndex);

                $worksheet = $excelObj->getActiveSheet();
                $highestRow = $worksheet->getHighestRow();
                $highestCol = $worksheet->getHighestColumn(); // 14 =

                if ($highestCol != 'O') {
                    $log2 .= 'La plus grande colonne n\'est pas "O" mais "' . $highestCol . '" ! Format incorrect<br>';

                    $this->flashMsgSession('log2', $log2);

                    $this->flashMsgSession('error', $this->translate('AoAuction.import.failure'));

                    return $this->redirect($this->generateUrl('aove_admin_homepage'));
                } else {
                    $lineRead = 0;
                    $veNew = 0;
                    $lineUnprocessed = 0;
                    $lineError = 0;

                    for ($row = 2; $row <= $highestRow; $row++) {
                        $lineRead++;

                        $dtPublication = \PHPExcel_Shared_Date::ExcelToPHPObject($worksheet->getCellByColumnAndRow(1, $row)->getValue()); // B
                        $country = \trim(\strval($worksheet->getCellByColumnAndRow(2, $row)->getValue())); // C

                        $description = \trim(\strval($worksheet->getCellByColumnAndRow(3, $row)->getValue())); // D
                        $company = \trim(\strval($worksheet->getCellByColumnAndRow(4, $row)->getValue())); // E
                        $nature = \trim(\strval($worksheet->getCellByColumnAndRow(5, $row)->getValue())); // F

                        $dtEnd = \PHPExcel_Shared_Date::ExcelToPHPObject($worksheet->getCellByColumnAndRow(6, $row)->getValue()); // G
                        $dtOpen = \PHPExcel_Shared_Date::ExcelToPHPObject($worksheet->getCellByColumnAndRow(8, $row)->getValue()); // I
                        $adress = \trim(\strval($worksheet->getCellByColumnAndRow(9, $row)->getValue())); // J
                        $price = \trim(\strval($worksheet->getCellByColumnAndRow(10, $row)->getValue())); // K
                        $addRef = \trim(\strval($worksheet->getCellByColumnAndRow(12, $row)->getValue())); // M
                        $source = \trim(\strval($worksheet->getCellByColumnAndRow(14, $row)->getValue())); // O
                        $status = AoAuction::STATUS_SHOW;

                        $haserror = false;

                        if (null == $dtPublication) {
                            $haserror = true;
                            $log2 .= 'ligne ' . $lineRead . ", erreur : Date de Publication<br>";
                        }

                        if ($country == '') {
                            $haserror = true;
                            $log2 .= 'ligne ' . $lineRead . ', erreur : Pays<br>';
                        }

                        if ($description == '') {
                            $haserror = true;
                            $log2 .= 'ligne ' . $lineRead . ', erreur : Description<br>';
                        }

                        if ($company == '') {
                            $haserror = true;
                            $log2 .= 'ligne ' . $lineRead . ', erreur : Société<br>';
                        }

                        if ($nature == '') {
                            $haserror = true;
                            $log2 .= 'ligne ' . $lineRead . ', erreur : Nature<br>';
                        }

                        if ($haserror == false) {

                            // Check if there is a corresponding image with this row
                            $new_file = "";

                            foreach ($worksheet->getDrawingCollection() as $drawing) {
                                if ($drawing instanceof \PHPExcel_Worksheet_Drawing) {
                                    $cellID = $drawing->getCoordinates();
                                    if ($cellID == \PHPExcel_Cell::stringFromColumnIndex(0) . $row) {
                                        // $logger->addError('CellID1 : ' . $cellID . ' ' . $drawing->getPath() . ' ' . $drawing->getExtension());
                                        $fileType = $drawing->getExtension();
                                        $fileName = sha1(uniqid(mt_rand(), true));
                                        switch ($fileType) {

                                            case 'gif':
                                                // $image = \imagecreatefromstring($imageContents);
                                                $new_file = $fileName . ".gif";
                                                // \imagegif($image, $imgDir . "/" . $new_file, 100);
                                                break;

                                            case 'jpeg':
                                                // $image = \imagecreatefromstring($imageContents);
                                                $new_file = $fileName . ".jpeg";
                                                // \imagejpeg($image, $imgDir . "/" . $new_file, 100);
                                                break;

                                            case 'png':
                                                // $image = \imagecreatefromstring($imageContents);
                                                $new_file = $fileName . ".png";
                                                // \imagepng($image, $imgDir . "/" . $new_file, 100);
                                                break;

                                            default:
                                                // $logger->addError($fileType);
                                                $new_file = "";
                                        }
                                        if ($new_file != "") {
                                            copy($drawing->getPath(), $imgDir . "/" . $new_file);
                                        }
                                    }
                                } elseif ($drawing instanceof \PHPExcel_Worksheet_MemoryDrawing) {
                                    $cellID = $drawing->getCoordinates();
                                    // $logger->addError('CellID2 : ' . $cellID);
                                    if ($cellID == \PHPExcel_Cell::stringFromColumnIndex(0) . $row) {
                                        ob_start();
                                        call_user_func($drawing->getRenderingFunction(), $drawing->getImageResource());
                                        $imageContents = ob_get_contents();
                                        ob_end_clean();

                                        $fileType = $drawing->getMimeType();

                                        $fileName = sha1(uniqid(mt_rand(), true));

                                        switch ($fileType) {

                                            case 'image/gif':
                                                $image = \imagecreatefromstring($imageContents);
                                                $new_file = $fileName . ".gif";
                                                \imagegif($image, $imgDir . "/" . $new_file, 100);
                                                break;

                                            case 'image/jpeg':
                                                $image = \imagecreatefromstring($imageContents);
                                                $new_file = $fileName . ".jpeg";
                                                \imagejpeg($image, $imgDir . "/" . $new_file, 100);
                                                break;

                                            case 'image/png':
                                                $image = \imagecreatefromstring($imageContents);
                                                $new_file = $fileName . ".png";
                                                \imagepng($image, $imgDir . "/" . $new_file, 100);
                                                break;

                                            default:
                                                // $logger->addError($fileType);
                                                $new_file = "";
                                        }
                                    }
                                }
                            }

                            $img = null;
                            if ($new_file != "") {
                                $img = $new_file;
                            }

                            $auction = new AoAuction();
                            $auction->setRef($nextref);
                            $auction->setImg($img);
                            $auction->setDtPublication($dtPublication);
                            $auction->setCountry($country);
                            $auction->setDescription($description);
                            $auction->setCompany($company);
                            $auction->setNature($nature);
                            $auction->setDtEnd($dtEnd);
                            $auction->setDtOpen($dtOpen);
                            $auction->setAdress($adress);
                            $auction->setPrice($price);
                            $auction->setAddRef($addRef);
                            $auction->setSource($source);
                            $auction->setStatus($status);

                            $em->persist($auction);

                            $autoinc->setCount($autoinc->getCount() + 1);
                            $em->persist($autoinc);
                            $em->flush();
                            $nextref++;
                            $veNew++;
                        } else {
                            $lineError++;
                            $log2 .= 'la ligne ' . $lineRead . ' contient des erreurs<br>';
                        }
                    }
                    $em->flush();

                    $log2 .= $lineRead . ' lignes lues<br>';
                    $log2 .= $veNew . ' nouvelles Ventes aux enchères<br>';
                    $log2 .= $lineUnprocessed . ' Ventes aux enchères déjà dans la base<br>';
                    $log2 .= $lineError . ' lignes contenant des erreurs<br>'; // */

                    $this->flashMsgSession('log2', $log2);

                    $this->flashMsgSession('success', $this->translate('AoAuction.import.success'));

                    return $this->redirect($this->generateUrl('aove_admin_homepage'));
                }
            } else {
                $this->flashMsgSession('error', $this->translate('AoAuction.import.failure'));
            }
        } elseif (isset($reqData['AoCallfortenderImportForm'])) {

            $callfortenderImportForm->handleRequest($request);
            if ($callfortenderImportForm->isValid()) {
                // $logger = $this->getLogger();

                $imgDir = $this->getParameter('kernel.root_dir') . '/../web/res/Ao';

                $autoinc = $em->getRepository('AcfDataBundle:Autoinc')->findOneBy(array(
                    'name' => 'AO'
                ));
                if (null == $autoinc) {
                    $autoinc = new Autoinc(1, 0);
                    $autoinc->setName('AO');
                } else {
                    $autoinc->setCount($autoinc->getCount() + 1);
                }
                $em->persist($autoinc);
                $em->flush();
                $nextref = $autoinc->getValue();

                ini_set('memory_limit', '4096M');
                ini_set('max_execution_time', '0');
                $extension = $callfortenderImportForm['excel']->getData()->guessExtension();
                if ($extension == 'zip') {
                    $extension = 'xlsx';
                }

                $filename = uniqid() . '.' . $extension;
                $callfortenderImportForm['excel']->getData()->move($this->getParameter('adapter_files'), $filename);
                $fullfilename = $this->getParameter('adapter_files');
                $fullfilename .= '/' . $filename;

                $excelObj = $this->get('phpexcel')->createPHPExcelObject($fullfilename);

                $log = '';

                $activeSheetIndex = 0;

                $excelObj->setActiveSheetIndex($activeSheetIndex);

                $worksheet = $excelObj->getActiveSheet();
                $highestRow = $worksheet->getHighestRow();
                $highestCol = $worksheet->getHighestColumn(); // 17

                if ($highestCol != 'Q') {
                    $log .= 'La plus grande colonne n\'est pas "Q" mais "' . $highestCol . '" ! Format incorrect<br>';
                    ;

                    $this->flashMsgSession('log', $log);

                    $this->flashMsgSession('error', $this->translate('AoCallfortender.import.failure'));

                    return $this->redirect($this->generateUrl('aove_admin_homepage'));
                } else {}

                $lineRead = 0;
                $aoNew = 0;
                $lineUnprocessed = 0;
                $lineError = 0;

                for ($row = 2; $row <= $highestRow; $row++) {
                    $lineRead++;

                    $dtPublication = \PHPExcel_Shared_Date::ExcelToPHPObject($worksheet->getCellByColumnAndRow(1, $row)->getValue()); // B
                    $country = \trim(\strval($worksheet->getCellByColumnAndRow(2, $row)->getValue())); // C

                    $description = \trim(\strval($worksheet->getCellByColumnAndRow(3, $row)->getValue())); // D
                    $company = \trim(\strval($worksheet->getCellByColumnAndRow(4, $row)->getValue())); // E
                    $nature = \trim(\strval($worksheet->getCellByColumnAndRow(5, $row)->getValue())); // F

                    $dtEnd = \PHPExcel_Shared_Date::ExcelToPHPObject($worksheet->getCellByColumnAndRow(6, $row)->getValue()); // G
                    $categ = \trim(\strval($worksheet->getCellByColumnAndRow(8, $row)->getValue())); // I
                    $dtOpen = \PHPExcel_Shared_Date::ExcelToPHPObject($worksheet->getCellByColumnAndRow(9, $row)->getValue()); // J
                    $adress = \trim(\strval($worksheet->getCellByColumnAndRow(10, $row)->getValue())); // K
                    $price = \trim(\strval($worksheet->getCellByColumnAndRow(11, $row)->getValue())); // L
                    $typeAvis = \trim(\strval($worksheet->getCellByColumnAndRow(12, $row)->getValue())); // M
                    $addRef = \trim(\strval($worksheet->getCellByColumnAndRow(14, $row)->getValue())); // O
                    $source = \trim(\strval($worksheet->getCellByColumnAndRow(16, $row)->getValue())); // Q
                    $status = AoCallfortender::STATUS_SHOW;

                    $haserror = false;

                    if (null == $dtPublication) {
                        $haserror = true;
                        $log .= 'ligne ' . $lineRead . ", erreur : Date de Publication<br>";
                    }

                    if ($country == '') {
                        $haserror = true;
                        $log .= 'ligne ' . $lineRead . ', erreur : Pays<br>';
                    }

                    if ($description == '') {
                        $haserror = true;
                        $log .= 'ligne ' . $lineRead . ', erreur : Description<br>';
                    }

                    if ($company == '') {
                        $haserror = true;
                        $log .= 'ligne ' . $lineRead . ', erreur : Société<br>';
                    }

                    if ($nature == '') {
                        $haserror = true;
                        $log .= 'ligne ' . $lineRead . ', erreur : Nature<br>';
                    }

                    $subCateg = null;

                    if ($categ == '') {
                        $haserror = true;
                        $log .= 'ligne ' . $lineRead . ', erreur : Ref<br>';
                    } else {
                        $subCateg = $em->getRepository('AcfDataBundle:AoSubCateg')->findOneBy(array(
                            'ref' => $categ
                        ));
                        if (null == $subCateg) {
                            $haserror = true;
                            $log .= 'ligne ' . $lineRead . ', erreur : Ref inconnue<br>';
                        }
                    }

                    if ($typeAvis == '') {
                        $haserror = true;
                        $log .= 'ligne ' . $lineRead . ', erreur : Type Avis<br>';
                    }

                    if ($haserror == false) {

                        // Check if there is a corresponding image with this row
                        $new_file = "";

                        foreach ($worksheet->getDrawingCollection() as $drawing) {
                            if ($drawing instanceof \PHPExcel_Worksheet_Drawing) {
                                $cellID = $drawing->getCoordinates();
                                if ($cellID == \PHPExcel_Cell::stringFromColumnIndex(0) . $row) {
                                    // $logger->addError('CellID1 : ' . $cellID . ' ' . $drawing->getPath() . ' ' . $drawing->getExtension());
                                    $fileType = $drawing->getExtension();
                                    $fileName = sha1(uniqid(mt_rand(), true));
                                    switch ($fileType) {

                                        case 'gif':
                                            // $image = \imagecreatefromstring($imageContents);
                                            $new_file = $fileName . ".gif";
                                            // \imagegif($image, $imgDir . "/" . $new_file, 100);
                                            break;

                                        case 'jpeg':
                                            // $image = \imagecreatefromstring($imageContents);
                                            $new_file = $fileName . ".jpeg";
                                            // \imagejpeg($image, $imgDir . "/" . $new_file, 100);
                                            break;

                                        case 'png':
                                            // $image = \imagecreatefromstring($imageContents);
                                            $new_file = $fileName . ".png";
                                            // \imagepng($image, $imgDir . "/" . $new_file, 100);
                                            break;

                                        default:
                                            // $logger->addError($fileType);
                                            $new_file = "";
                                    }
                                    if ($new_file != "") {
                                        copy($drawing->getPath(), $imgDir . "/" . $new_file);
                                    }
                                }
                            } elseif ($drawing instanceof \PHPExcel_Worksheet_MemoryDrawing) {
                                $cellID = $drawing->getCoordinates();
                                // $logger->addError('CellID2 : ' . $cellID);
                                if ($cellID == \PHPExcel_Cell::stringFromColumnIndex(0) . $row) {
                                    ob_start();
                                    call_user_func($drawing->getRenderingFunction(), $drawing->getImageResource());
                                    $imageContents = ob_get_contents();
                                    ob_end_clean();

                                    $fileType = $drawing->getMimeType();

                                    $fileName = sha1(uniqid(mt_rand(), true));

                                    switch ($fileType) {

                                        case 'image/gif':
                                            $image = \imagecreatefromstring($imageContents);
                                            $new_file = $fileName . ".gif";
                                            \imagegif($image, $imgDir . "/" . $new_file, 100);
                                            break;

                                        case 'image/jpeg':
                                            $image = \imagecreatefromstring($imageContents);
                                            $new_file = $fileName . ".jpeg";
                                            \imagejpeg($image, $imgDir . "/" . $new_file, 100);
                                            break;

                                        case 'image/png':
                                            $image = \imagecreatefromstring($imageContents);
                                            $new_file = $fileName . ".png";
                                            \imagepng($image, $imgDir . "/" . $new_file, 100);
                                            break;

                                        default:
                                            // $logger->addError($fileType);
                                            $new_file = "";
                                    }
                                }
                            }
                        }

                        $img = null;
                        if ($new_file != "") {
                            $img = $new_file;
                        }

                        $callfortender = new AoCallfortender();
                        $callfortender->setRef($nextref);
                        $callfortender->setGrp($subCateg);
                        $callfortender->setImg($img);
                        $callfortender->setDtPublication($dtPublication);
                        $callfortender->setCountry($country);
                        $callfortender->setDescription($description);
                        $callfortender->setCompany($company);
                        $callfortender->setNature($nature);
                        $callfortender->setDtEnd($dtEnd);
                        $callfortender->setDtOpen($dtOpen);
                        $callfortender->setAdress($adress);
                        $callfortender->setPrice($price);
                        $callfortender->setTypeAvis($typeAvis);
                        $callfortender->setAddRef($addRef);
                        $callfortender->setSource($source);
                        $callfortender->setStatus($status);

                        $em->persist($callfortender);

                        $autoinc->setCount($autoinc->getCount() + 1);
                        $em->persist($autoinc);
                        $em->flush();
                        $nextref++;
                        $aoNew++;
                    } else {
                        $lineError++;
                        $log .= 'la ligne ' . $lineRead . ' contient des erreurs<br>';
                    }
                }
                $em->flush();

                $log .= $lineRead . ' lignes lues<br>';
                $log .= $aoNew . ' nouveaux Appels d\'offres<br>';
                $log .= $lineUnprocessed . ' Appels d\'offres déjà dans la base<br>';
                $log .= $lineError . ' lignes contenant des erreurs<br>'; // */

                $this->flashMsgSession('log', $log);

                $this->flashMsgSession('success', $this->translate('AoCallfortender.import.success'));

                return $this->redirect($this->generateUrl('aove_admin_homepage'));
            } else {
                $this->flashMsgSession('error', $this->translate('AoCallfortender.import.failure'));
            }
        }

        $this->gvars['CallfortenderImportForm'] = $callfortenderImportForm->createView();

        $this->gvars['AuctionImportForm'] = $auctionImportForm->createView();

        $categs = $em->getRepository('AcfDataBundle:AoCateg')->getAll();
        $this->gvars['categs'] = $categs;

        $callfortenders = $em->getRepository('AcfDataBundle:AoCallfortender')->getAll();
        $this->gvars['callfortenders'] = $callfortenders;

        $auctions = $em->getRepository('AcfDataBundle:AoAuction')->getAll();
        $this->gvars['auctions'] = $auctions;

        $this->gvars['pagetitle'] = $this->translate('pagetitle.dasboard2');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.dasboard2');

        return $this->renderResponse('AoVeAdminBundle:Default:index.html.twig', $this->gvars);
    }
}
