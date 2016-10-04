<?php
namespace Acf\PayrollBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\PayrollBundle\Form\MSalary\ImportTForm as MSalaryImportTForm;
use Acf\PayrollBundle\Form\Doc\NewTForm as DocNewTForm;
use Acf\DataBundle\Entity\Doc;
use Acf\DataBundle\Entity\MSalary;
use Acf\DataBundle\Entity\MPaye;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class MPayeController extends BaseController
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
      $mpaye = $em->getRepository('AcfDataBundle:MPaye')->find($uid);

      if (null == $mpaye) {
        $this->flashMsgSession('warning', $this->translate('MPaye.edit.notfound'));
      } else {
        $msalaryImportForm = $this->createForm(MSalaryImportTForm::class);

        $doc = new Doc();
        $doc->setCompany($mpaye->getCompany());
        $docNewForm = $this->createForm(DocNewTForm::class, $doc, array(
          'company' => $mpaye->getCompany()
        ));

        $this->gvars['mpaye'] = $mpaye;
        $this->gvars['doc'] = $doc;
        $this->gvars['MSalaryImportForm'] = $msalaryImportForm->createView();
        $this->gvars['DocNewForm'] = $docNewForm->createView();

        $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
        $this->getSession()->remove('tabActive');

        $this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
        $this->getSession()->remove('stabActive');

        $this->gvars['pagetitle'] = $this->translate('pagetitle.mpaye.edit', array(
          '%mpaye%' => $mpaye->getRef()
        ));
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.mpaye.edit.txt', array(
          '%mpaye%' => $mpaye->getRef()
        ));

        return $this->renderResponse('AcfPayrollBundle:MPaye:edit.html.twig', $this->gvars);
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
      $mpaye = $em->getRepository('AcfDataBundle:MPaye')->find($uid);

      if (null == $mpaye) {
        $this->flashMsgSession('warning', $this->translate('MPaye.edit.notfound'));
      } else {
        $msalaryImportForm = $this->createForm(MSalaryImportTForm::class);

        $doc = new Doc();
        $doc->setCompany($mpaye->getCompany());
        $docNewForm = $this->createForm(DocNewTForm::class, $doc, array(
          'company' => $mpaye->getCompany()
        ));

        $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
        $this->getSession()->remove('tabActive');

        $this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
        $this->getSession()->remove('stabActive');

        $request = $this->getRequest();
        $reqData = $request->request->all();

        if (isset($reqData['MSalaryImportForm'])) {
          $this->gvars['tabActive'] = 2;
          $this->getSession()->set('tabActive', 2);
          $this->gvars['stabActive'] = 1;
          $this->getSession()->set('stabActive', 1);
          $msalaryImportForm->handleRequest($request);
          if ($msalaryImportForm->isValid()) {
            $lineDel = 0;
            foreach ($mpaye->getSalaries() as $oldSalary) {
              $em->remove($oldSalary);
              $lineDel++;
            }
            $em->flush();

            ini_set('memory_limit', '1024M');
            ini_set('max_execution_time', '0');
            $extension = $msalaryImportForm['excel']->getData()->guessExtension();
            if ($extension == 'zip') {
              $extension = 'xlsx';
            }

            $filename = uniqid() . '.' . $extension;
            $msalaryImportForm['excel']->getData()->move($this->getParameter('adapter_files'), $filename);
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

            $worksheet = $excelObj->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();
            $lineRead = 0;
            $msalaryNew = 0;
            $lineUnprocessed = 0;
            $lineError = 0;

            for ($row = 2; $row <= $highestRow; $row++) {
              $lineRead++;
              $haserror = false;

              $col = 0; // A
              $matricule = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // B
              $nom = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // C
              $prenom = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // D
              $actif = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // E
              $fonction = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // F
              $regime = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // G
              $dtStartContrat = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // H
              $dtEndContrat = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // I
              $departement = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // J
              $categorie = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // K
              $echelon = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // L
              $cin = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // M
              $cnss = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // N
              $birthday = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // O
              $adresse = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // P
              $tel = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // Q
              $email = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // R
              $banque = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // S
              $rib = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // T
              $familyChef = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // U
              $familySituation = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // V
              $handicap = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // W
              $childWoBourse = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // X
              $nbrDaysWork = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // Y
              $nbrDaysAbs = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // Z
              $nbrDaysFerry = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // AA
              $nbrH075Sup = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // AB
              $nbrH100Sup = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // AC
              $nbrDSup = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // AD
              $remboursement = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // AE
              $buysFromCompany = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // AF
              $salaryAdvance = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // AG
              $salaryBrut = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // AH
              $salaryNet = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // AI
              $advantageNature = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // AJ
              $ticketResto = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // AK
              $ticketCadeau = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // AL
              $lifeAssurance = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // AM
              $ceaAccount = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
              $col++; // AN
              $others = $worksheet->getCellByColumnAndRow($col, $row)->getValue();

              if (null == $matricule || $matricule == '') {
                $haserror = true;
                $log .= 'ligne ' . $lineRead . ", erreur : Matricule<br>";
              }

              if (null == $nom || $nom == '') {
                $haserror = true;
                $log .= 'ligne ' . $lineRead . ", erreur : Nom<br>";
              }

              if (null == $prenom || $prenom == '') {
                $haserror = true;
                $log .= 'ligne ' . $lineRead . ", erreur : Prenom<br>";
              }

              if ($haserror == false) {

                $msalaryNew++;

                $msalary = new MSalary();

                $msalary->setPaye($mpaye);

                $msalary->setMatricule($matricule);
                $msalary->setNom($nom);
                $msalary->setPrenom($prenom);
                $msalary->setActif($actif);
                $msalary->setFonction($fonction);
                $msalary->setRegime($regime);
                $msalary->setDtStartContrat($dtStartContrat);
                $msalary->setDtEndContrat($dtEndContrat);
                $msalary->setDepartement($departement);
                $msalary->setCategorie($categorie);
                $msalary->setEchelon($echelon);
                $msalary->setCin($cin);
                $msalary->setCnss($cnss);
                $msalary->setBirthday($birthday);
                $msalary->setAdresse($adresse);
                $msalary->setTel($tel);
                $msalary->setEmail($email);
                $msalary->setBanque($banque);
                $msalary->setRib($rib);
                $msalary->setFamilyChef($familyChef);
                $msalary->setFamilySituation($familySituation);
                $msalary->setHandicap($handicap);
                $msalary->setChildWoBourse($childWoBourse);
                $msalary->setNbrDaysWork($nbrDaysWork);
                $msalary->setNbrDaysAbs($nbrDaysAbs);
                $msalary->setNbrDaysFerry($nbrDaysFerry);
                $msalary->setNbrH075Sup($nbrH075Sup);
                $msalary->setNbrH100Sup($nbrH100Sup);
                $msalary->setNbrDSup($nbrDSup);
                $msalary->setRemboursement($remboursement);
                $msalary->setBuysFromCompany($buysFromCompany);
                $msalary->setSalaryAdvance($salaryAdvance);
                $msalary->setSalaryBrut($salaryBrut);
                $msalary->setSalaryNet($salaryNet);
                $msalary->setAdvantageNature($advantageNature);
                $msalary->setTicketResto($ticketResto);
                $msalary->setTicketCadeau($ticketCadeau);
                $msalary->setLifeAssurance($lifeAssurance);
                $msalary->setCeaAccount($ceaAccount);
                $msalary->setOthers($others);

                $em->persist($msalary);
                $em->persist($mpaye);
              } else {
                $lineError++;
                $log .= 'la ligne ' . $lineRead . ' contient des erreurs<br>';
              }
            }
            $em->flush();

            $log .= '<br>';
            $log .= $lineDel . ' anciennes fiches supprimées<br>';
            $log .= $lineRead . ' lignes lues<br>';
            $log .= $msalaryNew . ' nouvelles fiches de paye<br>';
            $log .= $lineUnprocessed . ' Fiche déjà dans la base<br>';
            $log .= $lineError . ' lignes contenant des erreurs<br>'; // */

            $from = $this->getParameter('mail_from');
            $fromName = $this->getParameter('mail_from_name');
            $subject = $this->translate('_mail.newmpaye.subject', array(), 'messages');

            $user = $this->getSecurityTokenStorage()
              ->getToken()
              ->getUser();
            $company = $mpaye->getCompany();

            $admins = $company->getAdmins();
            if (\count($admins) != 0) {
              $mvars = array();
              $mvars['mpaye'] = $mpaye;
              $mvars['user'] = $user;
              $mvars['company'] = $company;
              $message = \Swift_Message::newInstance();
              $message->setFrom($from, $fromName);
              foreach ($admins as $admin) {
                $message->addTo($admin->getEmail(), $admin->getFullname());
              }
              $message->setSubject($subject);
              $message->setBody($this->renderView('AcfPayrollBundle:Mail:MPayenew.html.twig', $mvars), 'text/html');
              $this->sendmail($message);
            }

            $this->flashMsgSession('log', $log);

            $this->flashMsgSession('success', $this->translate('MSalary.import.success'));

            $this->gvars['tabActive'] = 1;
            $this->getSession()->set('tabActive', 1);

            return $this->redirect($urlFrom);
          } else {
            $em->refresh($mpaye);

            $this->flashMsgSession('error', $this->translate('MSalary.import.failure'));
          }
        } elseif (isset($reqData['DocNewForm'])) {
          $this->gvars['tabActive'] = 3;
          $this->getSession()->set('tabActive', 3);
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
              $doc->setCompany($mpaye->getCompany());

              $doc->setFileName($fileName);
              $doc->setOriginalName($originalName);
              $doc->setSize($size);
              $doc->setMimeType($mimeType);
              $doc->setMd5($md5);
              $doc->setDescription($docNewForm['description']->getData());
              $em->persist($doc);

              $mpaye->addDoc($doc);

              $docNames .= $doc->getOriginalName() . ' ';

              $docs[] = $doc;
            }

            $em->persist($mpaye);
            $em->flush();
            $this->flashMsgSession('success', $this->translate('Doc.add.success', array(
              '%doc%' => $docNames
            )));
            $this->gvars['stabActive'] = 3;
            $this->getSession()->set('stabActive', 3);

            $from = $this->getParameter('mail_from');
            $fromName = $this->getParameter('mail_from_name');
            $subject = $this->translate('_mail.newdocsMP.subject', array(), 'messages');

            $user = $this->getSecurityTokenStorage()
              ->getToken()
              ->getUser();
            $company = $mpaye->getCompany();

            $admins = $company->getAdmins();
            if (\count($admins) != 0) {
              $mvars = array();
              $mvars['mpaye'] = $mpaye;
              $mvars['user'] = $user;
              $mvars['company'] = $company;
              $mvars['docs'] = $docs;
              $message = \Swift_Message::newInstance();
              $message->setFrom($from, $fromName);
              foreach ($admins as $admin) {
                $message->addTo($admin->getEmail(), $admin->getFullname());
              }
              $message->setSubject($subject);
              $message->setBody($this->renderView('AcfPayrollBundle:Mail:MPayenewdoc.html.twig', $mvars), 'text/html');
              $this->sendmail($message);
            }

            return $this->redirect($urlFrom);
          } else {
            $em->refresh($mpaye);

            $this->flashMsgSession('error', $this->translate('Doc.add.failure'));
          }
        }

        $this->gvars['mpaye'] = $mpaye;
        $this->gvars['doc'] = $doc;
        $this->gvars['MSalaryImportForm'] = $msalaryImportForm->createView();
        $this->gvars['DocNewForm'] = $docNewForm->createView();

        $this->gvars['pagetitle'] = $this->translate('pagetitle.mpaye.edit', array(
          '%mpaye%' => $mpaye->getRef()
        ));
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.mpaye.edit.txt', array(
          '%mpaye%' => $mpaye->getRef()
        ));

        return $this->renderResponse('AcfPayrollBundle:MPaye:edit.html.twig', $this->gvars);
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
      $mpaye = $em->getRepository('AcfDataBundle:MPaye')->find($uid);
      $salaries = $mpaye->getSalaries();

      $modelExist = true;

      $mPayeModel = $this->getParameter('kernel.root_dir');
      $mPayeModel .= '/../web/res/fpmodel.xlsx';

      try {
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($mPayeModel);
      } catch (\Exception $e) {
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $modelExist = false;
      }

      $phpExcelObject->getProperties()
        ->setCreator('Salah Abdelkader Seif Eddine')
        ->setLastModifiedBy($this->getSecurityTokenStorage()
        ->getToken()
        ->getUser()
        ->getFullname())
        ->setTitle($this->translate('pagetitle.mpaye.list'))
        ->setSubject($this->translate('pagetitle.mpaye.list'))
        ->setDescription($this->translate('pagetitle.mpaye.list'))
        ->setKeywords($this->translate('pagetitle.mpaye.list'))
        ->setCategory('ACF mpaye');

      $phpExcelObject->setActiveSheetIndex(0);

      $workSheet = $phpExcelObject->getActiveSheet();
      $workSheet->setTitle($this->translate('pagetitle.msalary.listExcel', array(
        '%mpaye%' => $mpaye->getRef()
      )));

      if (!$modelExist) {
        $workSheet->setCellValue('A1', $this->translate('MSalary.matricule.label'));
        $workSheet->getStyle('A1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('B1', $this->translate('MSalary.nom.label'));
        $workSheet->getStyle('B1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('C1', $this->translate('MSalary.prenom.label'));
        $workSheet->getStyle('C1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('D1', $this->translate('MSalary.actif.label'));
        $workSheet->getStyle('D1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('E1', $this->translate('MSalary.fonction.label'));
        $workSheet->getStyle('E1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('F1', $this->translate('MSalary.regime.label'));
        $workSheet->getStyle('F1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('G1', $this->translate('MSalary.dtStartContrat.label'));
        $workSheet->getStyle('G1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('H1', $this->translate('MSalary.dtEndContrat.label'));
        $workSheet->getStyle('H1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('I1', $this->translate('MSalary.departement.label'));
        $workSheet->getStyle('I1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('J1', $this->translate('MSalary.categorie.label'));
        $workSheet->getStyle('J1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('K1', $this->translate('MSalary.echelon.label'));
        $workSheet->getStyle('K1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('L1', $this->translate('MSalary.cin.label'));
        $workSheet->getStyle('L1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('M1', $this->translate('MSalary.cnss.label'));
        $workSheet->getStyle('M1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('N1', $this->translate('MSalary.birthday.label'));
        $workSheet->getStyle('N1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('O1', $this->translate('MSalary.adresse.label'));
        $workSheet->getStyle('O1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('P1', $this->translate('MSalary.tel.label'));
        $workSheet->getStyle('P1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('Q1', $this->translate('MSalary.email.label'));
        $workSheet->getStyle('Q1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('R1', $this->translate('MSalary.banque.label'));
        $workSheet->getStyle('R1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('S1', $this->translate('MSalary.rib.label'));
        $workSheet->getStyle('S1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('T1', $this->translate('MSalary.familyChef.label'));
        $workSheet->getStyle('T1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('U1', $this->translate('MSalary.familySituation.label'));
        $workSheet->getStyle('U1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('V1', $this->translate('MSalary.handicap.label'));
        $workSheet->getStyle('V1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('W1', $this->translate('MSalary.childWoBourse.label'));
        $workSheet->getStyle('W1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('X1', $this->translate('MSalary.nbrDaysWork.label'));
        $workSheet->getStyle('X1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('Y1', $this->translate('MSalary.nbrDaysAbs.label'));
        $workSheet->getStyle('Y1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('Z1', $this->translate('MSalary.nbrDaysFerry.label'));
        $workSheet->getStyle('Z1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('AA1', $this->translate('MSalary.nbrH075Sup.label'));
        $workSheet->getStyle('AA1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('AB1', $this->translate('MSalary.nbrH100Sup.label'));
        $workSheet->getStyle('AB1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('AC1', $this->translate('MSalary.nbrDSup.label'));
        $workSheet->getStyle('AC1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('AD1', $this->translate('MSalary.remboursement.label'));
        $workSheet->getStyle('AD1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('AE1', $this->translate('MSalary.buysFromCompany.label'));
        $workSheet->getStyle('AE1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('AF1', $this->translate('MSalary.salaryAdvance.label'));
        $workSheet->getStyle('AF1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('AG1', $this->translate('MSalary.salaryBrut.label'));
        $workSheet->getStyle('AG1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('AH1', $this->translate('MSalary.salaryNet.label'));
        $workSheet->getStyle('AH1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('AI1', $this->translate('MSalary.advantageNature.label'));
        $workSheet->getStyle('AI1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('AJ1', $this->translate('MSalary.ticketResto.label'));
        $workSheet->getStyle('AJ1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('AK1', $this->translate('MSalary.ticketCadeau.label'));
        $workSheet->getStyle('AK1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('AL1', $this->translate('MSalary.lifeAssurance.label'));
        $workSheet->getStyle('AL1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('AM1', $this->translate('MSalary.ceaAccount.label'));
        $workSheet->getStyle('AM1')
          ->getFont()
          ->setBold(true);
        $workSheet->setCellValue('AN1', $this->translate('MSalary.others.label'));
        $workSheet->getStyle('AN1')
          ->getFont()
          ->setBold(true);

        $workSheet->getStyle('A1:AN1')->applyFromArray(array(
          'fill' => array(
            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array(
              'rgb' => '94ccdf'
            )
          )
        ));
      }

      $i = 1;

      foreach ($salaries as $salary) {
        $i++;

        $workSheet->setCellValue('A' . $i, $salary->getMatricule(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('B' . $i, $salary->getNom(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('C' . $i, $salary->getPrenom(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('D' . $i, $salary->getActif(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('E' . $i, $salary->getFonction(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('F' . $i, $salary->getRegime(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('G' . $i, $salary->getDtStartContrat(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('H' . $i, $salary->getDtEndContrat(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('I' . $i, $salary->getDepartement(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('J' . $i, $salary->getCategorie(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('K' . $i, $salary->getEchelon(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('L' . $i, $salary->getCin(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('M' . $i, $salary->getCnss(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('N' . $i, $salary->getBirthday(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('O' . $i, $salary->getAdresse(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('P' . $i, $salary->getTel(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('Q' . $i, $salary->getEmail(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('R' . $i, $salary->getBanque(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('S' . $i, $salary->getRib(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('T' . $i, $salary->getFamilyChef(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('U' . $i, $salary->getFamilySituation(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('V' . $i, $salary->getHandicap(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('W' . $i, $salary->getChildWoBourse(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('X' . $i, $salary->getNbrDaysWork(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('Y' . $i, $salary->getNbrDaysAbs(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('Z' . $i, $salary->getNbrDaysFerry(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('AA' . $i, $salary->getNbrH075Sup(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('AB' . $i, $salary->getNbrH100Sup(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('AC' . $i, $salary->getNbrDSup(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('AD' . $i, $salary->getRemboursement(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('AE' . $i, $salary->getBuysFromCompany(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('AF' . $i, $salary->getSalaryAdvance(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('AG' . $i, $salary->getSalaryBrut(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('AH' . $i, $salary->getSalaryNet(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('AI' . $i, $salary->getAdvantageNature(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('AJ' . $i, $salary->getTicketResto(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('AK' . $i, $salary->getTicketCadeau(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('AL' . $i, $salary->getLifeAssurance(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('AM' . $i, $salary->getCeaAccount(), \PHPExcel_Cell_DataType::TYPE_STRING2);
        $workSheet->setCellValue('AN' . $i, $salary->getOthers(), \PHPExcel_Cell_DataType::TYPE_STRING2);

        if ($i % 2 == 1) {
          $workSheet->getStyle('A' . $i . ':AN' . $i)->applyFromArray(array(
            'fill' => array(
              'type' => \PHPExcel_Style_Fill::FILL_SOLID,
              'color' => array(
                'rgb' => 'd8f1f5'
              )
            )
          ));
        } else {
          $workSheet->getStyle('A' . $i . ':AN' . $i)->applyFromArray(array(
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
      $workSheet->getColumnDimension('U')->setAutoSize(true);
      $workSheet->getColumnDimension('V')->setAutoSize(true);
      $workSheet->getColumnDimension('W')->setAutoSize(true);
      $workSheet->getColumnDimension('X')->setAutoSize(true);
      $workSheet->getColumnDimension('Y')->setAutoSize(true);
      $workSheet->getColumnDimension('Z')->setAutoSize(true);
      $workSheet->getColumnDimension('AA')->setAutoSize(true);
      $workSheet->getColumnDimension('AB')->setAutoSize(true);
      $workSheet->getColumnDimension('AC')->setAutoSize(true);
      $workSheet->getColumnDimension('AD')->setAutoSize(true);
      $workSheet->getColumnDimension('AE')->setAutoSize(true);
      $workSheet->getColumnDimension('AF')->setAutoSize(true);
      $workSheet->getColumnDimension('AG')->setAutoSize(true);
      $workSheet->getColumnDimension('AH')->setAutoSize(true);
      $workSheet->getColumnDimension('AI')->setAutoSize(true);
      $workSheet->getColumnDimension('AJ')->setAutoSize(true);
      $workSheet->getColumnDimension('AK')->setAutoSize(true);
      $workSheet->getColumnDimension('AL')->setAutoSize(true);
      $workSheet->getColumnDimension('AM')->setAutoSize(true);
      $workSheet->getColumnDimension('AN')->setAutoSize(true);

      $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
      $response = $this->get('phpexcel')->createStreamedResponse($writer);

      $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8');

      $filename = $this->normalize($this->translate('pagetitle.msalary.listByMPaye', array(
        '%mpaye%' => $mpaye->getRef(),
        '%company%' => $mpaye->getCompany()
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
}
