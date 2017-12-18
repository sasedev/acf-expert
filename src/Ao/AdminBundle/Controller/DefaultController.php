<?php
namespace Ao\AdminBundle\Controller;

use Acf\DataBundle\Entity\Autoinc;
use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Ao\AdminBundle\Form\AoAdvertisement\ImportTForm as AdvertisementImportTForm;
use Acf\DataBundle\Entity\AoAdvertisement;

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
        $this->gvars['menu_active'] = 'aoadminhome';
    }

    public function indexAction()
    {
        $em = $this->getEntityManager();

        $advertisementImportForm = $this->createForm(AdvertisementImportTForm::class);

        $this->gvars['AdvertisementImportForm'] = $advertisementImportForm->createView();

        $request = $this->getRequest();
        $reqData = $request->request->all();

        if (isset($reqData['AoAdvertisementImportForm'])) {

            $advertisementImportForm->handleRequest($request);
            if ($advertisementImportForm->isValid()) {
                // $logger = $this->getLogger();

                $imgDir = $this->getParameter('kernel.root_dir') . '/../web/res/AoVe';

                $autoinc = $em->getRepository('AcfDataBundle:Autoinc')->findOneBy(array(
                    'name' => 'AOVE'
                ));
                if (null == $autoinc) {
                    $autoinc = new Autoinc(1, 0);
                    $autoinc->setName('AOVE');
                } else {
                    $autoinc->setCount($autoinc->getCount() + 1);
                }
                $em->persist($autoinc);
                $em->flush();

                ini_set('memory_limit', '4096M');
                ini_set('max_execution_time', '0');
                $extension = $advertisementImportForm['excel']->getData()->guessExtension();
                if ($extension == 'zip') {
                    $extension = 'xlsx';
                }

                $filename = uniqid() . '.' . $extension;
                $advertisementImportForm['excel']->getData()->move($this->getParameter('adapter_files'), $filename);
                $fullfilename = $this->getParameter('adapter_files');
                $fullfilename .= '/' . $filename;

                $excelObj = $this->get('phpexcel')->createPHPExcelObject($fullfilename);

                $log = '';

                $activeSheetIndex = 0;

                $excelObj->setActiveSheetIndex($activeSheetIndex);

                $worksheet = $excelObj->getActiveSheet();
                $highestRow = $worksheet->getHighestRow();
                $lineRead = 0;
                $aoveNew = 0;
                $lineUnprocessed = 0;
                $lineError = 0;

                for ($row = 2; $row <= $highestRow; $row++) {
                    $lineRead++;

                    $dtPublication = \PHPExcel_Shared_Date::ExcelToPHPObject($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $country = \trim(\strval($worksheet->getCellByColumnAndRow(2, $row)->getValue()));

                    $description = \trim(\strval($worksheet->getCellByColumnAndRow(3, $row)->getValue()));
                    $company = \trim(\strval($worksheet->getCellByColumnAndRow(4, $row)->getValue()));
                    $nature = \trim(\strval($worksheet->getCellByColumnAndRow(5, $row)->getValue()));

                    $dtEnd = \PHPExcel_Shared_Date::ExcelToPHPObject($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $aoVe = \trim(\strval($worksheet->getCellByColumnAndRow(8, $row)->getValue()));
                    $categ = \trim(\strval($worksheet->getCellByColumnAndRow(9, $row)->getValue()));
                    $dtOpen = \PHPExcel_Shared_Date::ExcelToPHPObject($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $adress = \trim(\strval($worksheet->getCellByColumnAndRow(11, $row)->getValue()));
                    $price = \trim(\strval($worksheet->getCellByColumnAndRow(12, $row)->getValue()));
                    $typeAvis = \trim(\strval($worksheet->getCellByColumnAndRow(13, $row)->getValue()));
                    $addRef = \trim(\strval($worksheet->getCellByColumnAndRow(15, $row)->getValue()));
                    $source = \trim(\strval($worksheet->getCellByColumnAndRow(17, $row)->getValue()));
                    $status = AoAdvertisement::STATUS_SHOW;

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

                    if (null == $dtEnd) {
                        $haserror = true;
                        $log .= 'ligne ' . $lineRead . ", erreur : Date de Fin<br>";
                    }

                    if ($aoVe == '') {
                        $haserror = true;
                        $log .= 'ligne ' . $lineRead . ', erreur : AOVE<br>';
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

                    if ($adress == '') {
                        $haserror = true;
                        $log .= 'ligne ' . $lineRead . ', erreur : Adresse<br>';
                    }

                    if ($price == '') {
                        $haserror = true;
                        $log .= 'ligne ' . $lineRead . ', erreur : Prix<br>';
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

                        $advertisement = new AoAdvertisement();
                        $advertisement->setRef($autoinc->getValue());
                        $advertisement->setGrp($subCateg);
                        $advertisement->setImg($img);
                        $advertisement->setDtPublication($dtPublication);
                        $advertisement->setCountry($country);
                        $advertisement->setDescription($description);
                        $advertisement->setCompany($company);
                        $advertisement->setNature($nature);
                        $advertisement->setDtEnd($dtEnd);
                        $advertisement->setAoVe($aoVe);
                        $advertisement->setDtOpen($dtOpen);
                        $advertisement->setAdress($adress);
                        $advertisement->setPrice($price);
                        $advertisement->setTypeAvis($typeAvis);
                        $advertisement->setAddRef($addRef);
                        $advertisement->setSource($source);
                        $advertisement->setStatus($status);

                        $em->persist($advertisement);

                        $autoinc->setCount($autoinc->getCount() + 1);
                        $em->persist($autoinc);
                        $em->flush();
                        $aoveNew++;
                    } else {
                        $lineError++;
                        $log .= 'la ligne ' . $lineRead . ' contient des erreurs<br>';
                    }
                }
                $em->flush();

                $log .= $lineRead . ' lignes lues<br>';
                $log .= $aoveNew . ' nouveaux AO/VE<br>';
                $log .= $lineUnprocessed . ' AO/VE déjà dans la base<br>';
                $log .= $lineError . ' lignes contenant des erreurs<br>'; // */

                $this->flashMsgSession('log', $log);

                $this->flashMsgSession('success', $this->translate('AoAdvertisement.import.success'));

                return $this->redirect($this->generateUrl('ao_admin_homepage'));
            } else {
                $this->flashMsgSession('error', $this->translate('AoAdvertisement.import.failure'));
            }
        }
        $categs = $em->getRepository('AcfDataBundle:AoCateg')->getAll();
        $this->gvars['categs'] = $categs;

        $this->gvars['pagetitle'] = $this->translate('pagetitle.dasboard2');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.dasboard2');

        return $this->renderResponse('AoAdminBundle:Default:index.html.twig', $this->gvars);
    }
}
