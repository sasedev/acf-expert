<?php
namespace Acf\AdminBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\Company;
use Acf\DataBundle\Entity\Sector;
use Acf\AdminBundle\Form\Sector\NewTForm as SectorNewTForm;
use Acf\AdminBundle\Form\Company\NewTForm as CompanyNewTForm;
use Acf\AdminBundle\Form\Company\UpdateTypeTForm as CompanyUpdateTypeTForm;
use Acf\AdminBundle\Form\Company\UpdateRefTForm as CompanyUpdateRefTForm;
use Acf\AdminBundle\Form\Company\UpdateCorporateNameTForm as CompanyUpdateCorporateNameTForm;
use Acf\AdminBundle\Form\Company\UpdateFiscTForm as CompanyUpdateFiscTForm;
use Acf\AdminBundle\Form\Company\UpdateTribunalTForm as CompanyUpdateTribunalTForm;
use Acf\AdminBundle\Form\Company\UpdatePhysicaltypeTForm as CompanyUpdatePhysicaltypeTForm;
use Acf\AdminBundle\Form\Company\UpdateCnssTForm as CompanyUpdateCnssTForm;
use Acf\AdminBundle\Form\Company\UpdateCnssBureauTForm as CompanyUpdateCnssBureauTForm;
use Acf\AdminBundle\Form\Company\UpdateSectorsTForm as CompanyUpdateSectorsTForm;
use Acf\AdminBundle\Form\Company\UpdatePhoneTForm as CompanyUpdatePhoneTForm;
use Acf\AdminBundle\Form\Company\UpdateMobileTForm as CompanyUpdateMobileTForm;
use Acf\AdminBundle\Form\Company\UpdateFaxTForm as CompanyUpdateFaxTForm;
use Acf\AdminBundle\Form\Company\UpdateEmailTForm as CompanyUpdateEmailTForm;
use Acf\AdminBundle\Form\Company\UpdateAdrTForm as CompanyUpdateAdrTForm;
use Acf\AdminBundle\Form\Company\UpdateOtherInfosTForm as CompanyUpdateOtherInfosTForm;
use Acf\AdminBundle\Form\Company\UpdateActionvnTForm as CompanyUpdateActionvnTForm;
use Acf\AdminBundle\Form\Stock\NewTForm as StockNewTForm;
use Acf\AdminBundle\Form\Address\NewTForm as AddressNewTForm;
use Acf\AdminBundle\Form\Phone\NewTForm as PhoneNewTForm;
use Acf\AdminBundle\Form\CompanyFrame\NewTForm as CompanyFrameNewTForm;
use Acf\AdminBundle\Form\CompanyNature\NewTForm as CompanyNatureNewTForm;
use Acf\AdminBundle\Form\CompanyNature\ImportTForm as CompanyNatureImportTForm;
use Acf\AdminBundle\Form\Doc\NewTForm as DocNewTForm;
use Acf\AdminBundle\Form\Pilot\NewTForm as PilotNewTForm;
use Acf\AdminBundle\Form\CompanyUser\NewTForm as CompanyUserNewTForm;
use Acf\AdminBundle\Form\CompanyAdmin\NewTForm as CompanyAdminNewTForm;
use Acf\AdminBundle\Form\CompanyLabel\NewTForm as CompanyLabelNewTForm;
use Acf\AdminBundle\Form\CompanyLabel\ImportTForm as CompanyLabelImportTForm;
use Acf\AdminBundle\Form\Shareholder\NewTForm as ShareholderNewTForm;
use Acf\AdminBundle\Form\Customer\NewTForm as CustomerNewTForm;
use Acf\AdminBundle\Form\Customer\ImportTForm as CustomerImportTForm;
use Acf\AdminBundle\Form\Supplier\NewTForm as SupplierNewTForm;
use Acf\AdminBundle\Form\Supplier\ImportTForm as SupplierImportTForm;
use Acf\AdminBundle\Form\Bank\NewTForm as BankNewTForm;
use Acf\AdminBundle\Form\Bank\ImportTForm as BankImportTForm;
use Acf\AdminBundle\Form\Fund\NewTForm as FundNewTForm;
use Acf\AdminBundle\Form\Fund\ImportTForm as FundImportTForm;
use Acf\AdminBundle\Form\Withholding\NewTForm as WithholdingNewTForm;
use Acf\AdminBundle\Form\Withholding\ImportTForm as WithholdingImportTForm;
use Acf\AdminBundle\Form\MBPurchase\NewTForm as MBPurchaseNewTForm;
use Acf\AdminBundle\Form\MBSale\NewTForm as MBSaleNewTForm;
use Acf\AdminBundle\Form\MPaye\NewTForm as MPayeNewTForm;
use Acf\AdminBundle\Form\MBPurchase\NewYearTForm as MBPurchaseNewYearTForm;
use Acf\AdminBundle\Form\MBSale\NewYearTForm as MBSaleNewYearTForm;
use Acf\AdminBundle\Form\MPaye\NewYearTForm as MPayeNewYearTForm;
use Acf\AdminBundle\Form\Docgroupcomptable\NewTForm as DocgroupcomptableNewTForm;
use Acf\AdminBundle\Form\Docgroup\NewTForm as DocgroupNewTForm;
use Acf\AdminBundle\Form\Docgroupfiscal\NewTForm as DocgroupfiscalNewTForm;
use Acf\AdminBundle\Form\Docgroupperso\NewTForm as DocgrouppersoNewTForm;
use Acf\AdminBundle\Form\Docgroupsyst\NewTForm as DocgroupsystNewTForm;
use Acf\AdminBundle\Form\Docgroupaudit\NewTForm as DocgroupauditNewTForm;
use Acf\AdminBundle\Form\Docgroupbank\NewTForm as DocgroupbankNewTForm;
use Acf\AdminBundle\Form\LiasseFolder\NewTForm as LiasseFolderNewTForm;
use Acf\DataBundle\Entity\CompanyFrame;
use Acf\DataBundle\Entity\CompanyLabel;
use Acf\DataBundle\Entity\Customer;
use Acf\DataBundle\Entity\Supplier;
use Acf\DataBundle\Entity\Bank;
use Acf\DataBundle\Entity\Fund;
use Acf\DataBundle\Entity\Withholding;
use Acf\DataBundle\Entity\Stock;
use Acf\DataBundle\Entity\Address;
use Acf\DataBundle\Entity\Phone;
use Acf\DataBundle\Entity\ConstantStr;
use Acf\DataBundle\Entity\MBSale;
use Acf\DataBundle\Entity\MBPurchase;
use Acf\DataBundle\Entity\MPaye;
use Acf\DataBundle\Entity\Docgroupcomptable;
use Acf\DataBundle\Entity\Docgroup;
use Acf\DataBundle\Entity\Docgroupfiscal;
use Acf\DataBundle\Entity\Docgroupperso;
use Acf\DataBundle\Entity\Docgroupsyst;
use Acf\DataBundle\Entity\Docgroupbank;
use Acf\DataBundle\Entity\Docgroupaudit;
use Acf\DataBundle\Entity\Doc;
use Acf\DataBundle\Entity\CompanyNature;
use Acf\DataBundle\Entity\Shareholder;
use Acf\DataBundle\Entity\Pilot;
use Acf\DataBundle\Entity\CompanyUser;
use Acf\DataBundle\Entity\CompanyAdmin;
use Acf\DataBundle\Entity\Role;
use Acf\DataBundle\Entity\Trace;
use Acf\DataBundle\Entity\LiasseFolder;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class CompanyController extends BaseController
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }

        $em = $this->getEntityManager();
        $companies = $em->getRepository('AcfDataBundle:Company')->getAll();
        $this->gvars['companies'] = $companies;

        $this->gvars['smenu_active'] = 'list';
        $this->gvars['pagetitle'] = $this->translate('pagetitle.company.list');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.company.list.txt');

        return $this->renderResponse('AcfAdminBundle:Company:list.html.twig', $this->gvars);
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function excelAction()
    {
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_company_list');
        }

        try {
            $em = $this->getEntityManager();
            $companies = $em->getRepository('AcfDataBundle:Company')->getAll();

            $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

            $phpExcelObject->getProperties()
                ->setCreator('Salah Abdelkader Seif Eddine')
                ->setLastModifiedBy($this->getSecurityTokenStorage()
                ->getToken()
                ->getUser()
                ->getFullname())
                ->setTitle($this->translate('pagetitle.company.list'))
                ->setSubject($this->translate('pagetitle.company.list'))
                ->setDescription($this->translate('pagetitle.company.list'))
                ->setKeywords($this->translate('pagetitle.company.list'))
                ->setCategory('ACEF Companies');

            $phpExcelObject->setActiveSheetIndex(0);

            $workSheet = $phpExcelObject->getActiveSheet();
            $workSheet->setTitle($this->translate('pagetitle.company.list'));

            $workSheet->setCellValue('A1', $this->translate('Company.ref.label'));
            $workSheet->getStyle('A1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('B1', $this->translate('Company.corporateName.label'));
            $workSheet->getStyle('B1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('C1', $this->translate('Company.type.label'));
            $workSheet->getStyle('C1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('D1', $this->translate('Company.sectors.label'));
            $workSheet->getStyle('D1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('E1', $this->translate('Company.fisc.label'));
            $workSheet->getStyle('E1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('F1', $this->translate('Company.docs.label'));
            $workSheet->getStyle('F1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('G1', $this->translate('Company.buys.label'));
            $workSheet->getStyle('G1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('H1', $this->translate('Company.sales.label'));
            $workSheet->getStyle('H1')
                ->getFont()
                ->setBold(true);

            $workSheet->getStyle('A1:H1')->applyFromArray(array(
                'fill' => array(
                    'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array(
                        'rgb' => '94ccdf'
                    )
                )
            ));

            $i = 1;

            foreach ($companies as $company) {
                $i++;
                $workSheet->setCellValue('A' . $i, $company->getRef(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('B' . $i, $company->getCorporateName(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('C' . $i, $company->getType()
                    ->getLabel(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $sectors = '';
                $ln = 0;
                foreach ($company->getSectors() as $sector) {
                    if ($ln != 0) {
                        $sectors .= "\n";
                    }
                    $sectors .= $sector->getLabel();
                    $ln++;
                }
                $workSheet->setCellValue('D' . $i, $sectors, \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->getStyle('D' . $i)
                    ->getAlignment()
                    ->setWrapText(true);
                $workSheet->setCellValue('E' . $i, $company->getFisc(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('F' . $i, \count($company->getDocs()), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $workSheet->setCellValue('G' . $i, \count($company->getPurchases()), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $workSheet->setCellValue('H' . $i, \count($company->getSales()), \PHPExcel_Cell_DataType::TYPE_NUMERIC);

                if ($i % 2 == 1) {
                    $workSheet->getStyle('A' . $i . ':H' . $i)->applyFromArray(array(
                        'fill' => array(
                            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array(
                                'rgb' => 'd8f1f5'
                            )
                        )
                    ));
                } else {
                    $workSheet->getStyle('A' . $i . ':H' . $i)->applyFromArray(array(
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

            $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
            $response = $this->get('phpexcel')->createStreamedResponse($writer);

            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8');

            $filename = $this->normalizeString($this->normalize($this->translate('pagetitle.company.list')));
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addGetAction()
    {
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }

        $company = new Company();
        $companyNewForm = $this->createForm(CompanyNewTForm::class, $company);
        $this->gvars['company'] = $company;
        $this->gvars['CompanyNewForm'] = $companyNewForm->createView();
        $sector = new Sector();

        $sectorNewForm = $this->createForm(SectorNewTForm::class, $sector);
        $this->gvars['sector'] = $sector;
        $this->gvars['SectorNewForm'] = $sectorNewForm->createView();

        $this->gvars['pagetitle'] = $this->translate('pagetitle.company.add');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.company.add.txt');
        $this->gvars['smenu_active'] = 'add';

        return $this->renderResponse('AcfAdminBundle:Company:add.html.twig', $this->gvars);
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addPostAction()
    {
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }

        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            return $this->redirect($this->generateUrl('_admin_company_addGet'));
        }

        $company = new Company();
        $companyNewForm = $this->createForm(CompanyNewTForm::class, $company);

        $request = $this->getRequest();
        $reqData = $request->request->all();

        if (isset($reqData['CompanyNewForm'])) {
            $companyNewForm->handleRequest($request);
            if ($companyNewForm->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($company);
                $em->flush();

                if (null != $companyNewForm['clone']->getData()) {
                    $companyClone = $companyNewForm['clone']->getData();

                    $oldDocgroupaudits = $em->getRepository('AcfDataBundle:Docgroupaudit')->getAll($companyClone);
                    $docgroupauditsCloneArray = array();
                    foreach ($oldDocgroupaudits as $oldDocgroupaudit) {
                        $docgroupaudit = new Docgroupaudit();
                        $docgroupaudit->setCompany($company);
                        $docgroupaudit->setLabel($oldDocgroupaudit->getLabel());
                        $docgroupaudit->setOtherInfos($oldDocgroupaudit->getOtherInfos());
                        if (null != $oldDocgroupaudit->getParent()) {
                            $parentId = $oldDocgroupaudit->getParent()->getId();
                            $docgroupaudit->setParent($docgroupauditsCloneArray[$parentId]);
                        }
                        $docgroupauditsCloneArray[$oldDocgroupaudit->getId()] = $docgroupaudit;
                        $em->persist($docgroupaudit);
                    }

                    $oldDocgroupbanks = $em->getRepository('AcfDataBundle:Docgroupbank')->getAll($companyClone);
                    $docgroupbanksCloneArray = array();
                    foreach ($oldDocgroupbanks as $oldDocgroupbank) {
                        $docgroupbank = new Docgroupbank();
                        $docgroupbank->setCompany($company);
                        $docgroupbank->setLabel($oldDocgroupbank->getLabel());
                        $docgroupbank->setOtherInfos($oldDocgroupbank->getOtherInfos());
                        if (null != $oldDocgroupbank->getParent()) {
                            $parentId = $oldDocgroupbank->getParent()->getId();
                            $docgroupbank->setParent($docgroupbanksCloneArray[$parentId]);
                        }
                        $docgroupbanksCloneArray[$oldDocgroupbank->getId()] = $docgroupbank;
                        $em->persist($docgroupbank);
                    }

                    $oldDocgroupcomptables = $em->getRepository('AcfDataBundle:Docgroupcomptable')->getAll($companyClone);
                    $docgroupcomptablesCloneArray = array();
                    foreach ($oldDocgroupcomptables as $oldDocgroupcomptable) {
                        $docgroupcomptable = new Docgroupcomptable();
                        $docgroupcomptable->setCompany($company);
                        $docgroupcomptable->setLabel($oldDocgroupcomptable->getLabel());
                        $docgroupcomptable->setOtherInfos($oldDocgroupcomptable->getOtherInfos());
                        if (null != $oldDocgroupcomptable->getParent()) {
                            $parentId = $oldDocgroupcomptable->getParent()->getId();
                            $docgroupcomptable->setParent($docgroupcomptablesCloneArray[$parentId]);
                        }
                        $docgroupcomptablesCloneArray[$oldDocgroupcomptable->getId()] = $docgroupcomptable;
                        $em->persist($docgroupcomptable);
                    }

                    $oldDocgroupfiscals = $em->getRepository('AcfDataBundle:Docgroupfiscal')->getAll($companyClone);
                    $docgroupfiscalsCloneArray = array();
                    foreach ($oldDocgroupfiscals as $oldDocgroupfiscal) {
                        $docgroupfiscal = new Docgroupfiscal();
                        $docgroupfiscal->setCompany($company);
                        $docgroupfiscal->setLabel($oldDocgroupfiscal->getLabel());
                        $docgroupfiscal->setOtherInfos($oldDocgroupfiscal->getOtherInfos());
                        if (null != $oldDocgroupfiscal->getParent()) {
                            $parentId = $oldDocgroupfiscal->getParent()->getId();
                            $docgroupfiscal->setParent($docgroupfiscalsCloneArray[$parentId]);
                        }
                        $docgroupfiscalsCloneArray[$oldDocgroupfiscal->getId()] = $docgroupfiscal;
                        $em->persist($docgroupfiscal);
                    }

                    $oldDocgrouppersos = $em->getRepository('AcfDataBundle:Docgroupperso')->getAll($companyClone);
                    $docgrouppersosCloneArray = array();
                    foreach ($oldDocgrouppersos as $oldDocgroupperso) {
                        $docgroupperso = new Docgroupperso();
                        $docgroupperso->setCompany($company);
                        $docgroupperso->setLabel($oldDocgroupperso->getLabel());
                        $docgroupperso->setOtherInfos($oldDocgroupperso->getOtherInfos());
                        if (null != $oldDocgroupperso->getParent()) {
                            $parentId = $oldDocgroupperso->getParent()->getId();
                            $docgroupperso->setParent($docgrouppersosCloneArray[$parentId]);
                        }
                        $docgrouppersosCloneArray[$oldDocgroupperso->getId()] = $docgroupperso;
                        $em->persist($docgroupperso);
                    }

                    $oldDocgroups = $em->getRepository('AcfDataBundle:Docgroup')->getAll($companyClone);
                    $docgroupsCloneArray = array();
                    foreach ($oldDocgroups as $oldDocgroup) {
                        $docgroup = new Docgroup();
                        $docgroup->setCompany($company);
                        $docgroup->setLabel($oldDocgroup->getLabel());
                        $docgroup->setOtherInfos($oldDocgroup->getOtherInfos());
                        if (null != $oldDocgroup->getParent()) {
                            $parentId = $oldDocgroup->getParent()->getId();
                            $docgroup->setParent($docgroupsCloneArray[$parentId]);
                        }
                        $docgroupsCloneArray[$oldDocgroup->getId()] = $docgroup;
                        $em->persist($docgroup);
                    }

                    $oldDocgroupsysts = $em->getRepository('AcfDataBundle:Docgroupsyst')->getAll($companyClone);
                    $docgroupsystsCloneArray = array();
                    foreach ($oldDocgroupsysts as $oldDocgroupsyst) {
                        $docgroupsyst = new Docgroupsyst();
                        $docgroupsyst->setCompany($company);
                        $docgroupsyst->setLabel($oldDocgroupsyst->getLabel());
                        $docgroupsyst->setOtherInfos($oldDocgroupsyst->getOtherInfos());
                        if (null != $oldDocgroupsyst->getParent()) {
                            $parentId = $oldDocgroupsyst->getParent()->getId();
                            $docgroupsyst->setParent($docgroupsystsCloneArray[$parentId]);
                        }
                        $docgroupsystsCloneArray[$oldDocgroupsyst->getId()] = $docgroupsyst;
                        $em->persist($docgroupsyst);
                    }
                    $em->flush();

                    $docDir = $this->getParameter('kernel.root_dir') . '/../web/res/docs';
                    $oldDocs = $em->getRepository('AcfDataBundle:Doc')->getAllByCompany($companyClone);
                    foreach ($oldDocs as $oldDoc) {
                        $oldFileName = $oldDoc->getFileName();
                        $fileName = sha1(uniqid(mt_rand(), true)) . '.' . $oldFileName;

                        $doc = new Doc();
                        $doc->setCompany($company);
                        $doc->setDescription($oldDoc->getDescription());
                        $doc->setNbrDownloads(0);
                        $doc->setSize($oldDoc->getSize());
                        $doc->setFileName($fileName);
                        $doc->setMimeType($oldDoc->getMimeType());
                        $doc->setMd5($oldDoc->getMd5());
                        $doc->setOriginalName($oldDoc->getOriginalName());

                        $doc->setFileName($fileName);

                        copy($docDir . '/' . $oldFileName, $docDir . '/' . $fileName);

                        foreach ($oldDoc->getGroupaudits() as $oldDocgroupaudit) {
                            $doc->addGroupaudit($docgroupauditsCloneArray[$oldDocgroupaudit->getId()]);
                        }

                        foreach ($oldDoc->getGroupbanks() as $oldDocgroupbank) {
                            $doc->addGroupbank($docgroupbanksCloneArray[$oldDocgroupbank->getId()]);
                        }

                        foreach ($oldDoc->getGroupcomptables() as $oldDocgroupcomptable) {
                            $doc->addGroupcomptable($docgroupcomptablesCloneArray[$oldDocgroupcomptable->getId()]);
                        }

                        foreach ($oldDoc->getGroupfiscals() as $oldDocgroupfiscal) {
                            $doc->addGroupfiscal($docgroupfiscalsCloneArray[$oldDocgroupfiscal->getId()]);
                        }

                        foreach ($oldDoc->getGrouppersos() as $oldDocgroupperso) {
                            $doc->addGroupperso($docgrouppersosCloneArray[$oldDocgroupperso->getId()]);
                        }

                        foreach ($oldDoc->getGroups() as $oldDocgroup) {
                            $doc->addGroup($docgroupsCloneArray[$oldDocgroup->getId()]);
                        }

                        foreach ($oldDoc->getGroupsysts() as $oldDocgroupsyst) {
                            $doc->addGroupsyst($docgroupsystsCloneArray[$oldDocgroupsyst->getId()]);
                        }
                        $em->persist($doc);
                    }
                    $em->flush();
                }

                $this->flashMsgSession('success', $this->translate('Company.add.success', array(
                    '%company%' => $company->getCorporateName()
                )));

                return $this->redirect($this->generateUrl('_admin_company_editGet', array(
                    'uid' => $company->getId()
                )));
            } else {
                $this->flashMsgSession('error', $this->translate('Company.add.failure'));
            }
        }
        $this->gvars['company'] = $company;
        $this->gvars['CompanyNewForm'] = $companyNewForm->createView();

        $sector = new Sector();
        $sectorNewForm = $this->createForm(SectorNewTForm::class, $sector);
        $this->gvars['sector'] = $sector;
        $this->gvars['SectorNewForm'] = $sectorNewForm->createView();

        $this->gvars['pagetitle'] = $this->translate('pagetitle.company.add');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.company.add.txt');
        $this->gvars['smenu_active'] = 'add';

        return $this->renderResponse('AcfAdminBundle:Company:add.html.twig', $this->gvars);
    }

    /**
     *
     * @param string $uid
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($uid)
    {
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }

        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_company_list');
        }
        $em = $this->getEntityManager();
        try {
            $company = $em->getRepository('AcfDataBundle:Company')->find($uid);

            if (null == $company) {
                $this->flashMsgSession('warning', $this->translate('Company.delete.notfound'));
            } else {
                $em->remove($company);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('Company.delete.success', array(
                    '%company%' => $company->getCorporateName()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('Company.delete.failure'));
        }

        return $this->redirect($urlFrom);
    }

    public function backtoStockAction($uid)
    {
        $this->getSession()->set('tabActive', 3);
        $this->getSession()->set('stabActive', 2);

        return $this->redirect($this->generateUrl('_admin_company_editGet', array(
            'uid' => $uid
        )));
    }

    public function backtoAddressAction($uid)
    {
        $this->getSession()->set('tabActive', 4);
        $this->getSession()->set('stabActive', 2);

        return $this->redirect($this->generateUrl('_admin_company_editGet', array(
            'uid' => $uid
        )));
    }

    public function backtoPhoneAction($uid)
    {
        $this->getSession()->set('tabActive', 5);
        $this->getSession()->set('stabActive', 2);

        return $this->redirect($this->generateUrl('_admin_company_editGet', array(
            'uid' => $uid
        )));
    }

    public function backtoCompanyFrameAction($uid)
    {
        $this->getSession()->set('tabActive', 6);
        $this->getSession()->set('stabActive', 2);

        return $this->redirect($this->generateUrl('_admin_company_editGet', array(
            'uid' => $uid
        )));
    }

    public function backtoCompanyNatureAction($uid)
    {
        $this->getSession()->set('tabActive', 7);
        $this->getSession()->set('stabActive', 3);

        return $this->redirect($this->generateUrl('_admin_company_editGet', array(
            'uid' => $uid
        )));
    }

    public function backtoDocAction($uid)
    {
        $this->getSession()->set('tabActive', 8);
        $this->getSession()->set('stabActive', 2);

        return $this->redirect($this->generateUrl('_admin_company_editGet', array(
            'uid' => $uid
        )));
    }

    public function backtoPilotAction($uid)
    {
        $this->getSession()->set('tabActive', 9);
        $this->getSession()->set('stabActive', 2);

        return $this->redirect($this->generateUrl('_admin_company_editGet', array(
            'uid' => $uid
        )));
    }

    public function backtoCompanyUserAction($uid)
    {
        $this->getSession()->set('tabActive', 10);
        $this->getSession()->set('stabActive', 2);

        return $this->redirect($this->generateUrl('_admin_company_editGet', array(
            'uid' => $uid
        )));
    }

    public function backtoCompanyAdminAction($uid)
    {
        $this->getSession()->set('tabActive', 11);
        $this->getSession()->set('stabActive', 2);

        return $this->redirect($this->generateUrl('_admin_company_editGet', array(
            'uid' => $uid
        )));
    }

    public function backtoCompanyLabelAction($uid)
    {
        $this->getSession()->set('tabActive', 21);
        $this->getSession()->set('stabActive', 3);

        return $this->redirect($this->generateUrl('_admin_company_editGet', array(
            'uid' => $uid
        )));
    }

    public function backtoCustomerAction($uid)
    {
        $this->getSession()->set('tabActive', 22);
        $this->getSession()->set('stabActive', 3);

        return $this->redirect($this->generateUrl('_admin_company_editGet', array(
            'uid' => $uid
        )));
    }

    public function backtoSupplierAction($uid)
    {
        $this->getSession()->set('tabActive', 23);
        $this->getSession()->set('stabActive', 3);

        return $this->redirect($this->generateUrl('_admin_company_editGet', array(
            'uid' => $uid
        )));
    }

    public function backtoBankAction($uid)
    {
        $this->getSession()->set('tabActive', 24);
        $this->getSession()->set('stabActive', 3);

        return $this->redirect($this->generateUrl('_admin_company_editGet', array(
            'uid' => $uid
        )));
    }

    public function backtoFundAction($uid)
    {
        $this->getSession()->set('tabActive', 25);
        $this->getSession()->set('stabActive', 3);

        return $this->redirect($this->generateUrl('_admin_company_editGet', array(
            'uid' => $uid
        )));
    }

    public function backtoWithholdingAction($uid)
    {
        $this->getSession()->set('tabActive', 26);
        $this->getSession()->set('stabActive', 3);

        return $this->redirect($this->generateUrl('_admin_company_editGet', array(
            'uid' => $uid
        )));
    }

    public function backtoMBSaleAction($uid)
    {
        $this->getSession()->set('tabActive', 27);
        $this->getSession()->set('stabActive', 3);

        return $this->redirect($this->generateUrl('_admin_company_editGet', array(
            'uid' => $uid
        )));
    }

    public function backtoMBPurchaseAction($uid)
    {
        $this->getSession()->set('tabActive', 28);
        $this->getSession()->set('stabActive', 3);

        return $this->redirect($this->generateUrl('_admin_company_editGet', array(
            'uid' => $uid
        )));
    }

    public function backtoDocgroupcomptableAction($uid)
    {
        $this->getSession()->set('tabActive', 29);
        $this->getSession()->set('stabActive', 2);

        return $this->redirect($this->generateUrl('_admin_company_editGet', array(
            'uid' => $uid
        )));
    }

    public function backtoDocgroupbankAction($uid)
    {
        $this->getSession()->set('tabActive', 30);
        $this->getSession()->set('stabActive', 2);

        return $this->redirect($this->generateUrl('_admin_company_editGet', array(
            'uid' => $uid
        )));
    }

    public function backtoDocgroupAction($uid)
    {
        $this->getSession()->set('tabActive', 41);
        $this->getSession()->set('stabActive', 2);

        return $this->redirect($this->generateUrl('_admin_company_editGet', array(
            'uid' => $uid
        )));
    }

    public function backtoShareholderAction($uid)
    {
        $this->getSession()->set('tabActive', 42);
        $this->getSession()->set('stabActive', 2);

        return $this->redirect($this->generateUrl('_admin_company_editGet', array(
            'uid' => $uid
        )));
    }

    public function backtoDocgroupfiscalAction($uid)
    {
        $this->getSession()->set('tabActive', 51);
        $this->getSession()->set('stabActive', 2);

        return $this->redirect($this->generateUrl('_admin_company_editGet', array(
            'uid' => $uid
        )));
    }

    public function backtoDocgrouppersoAction($uid)
    {
        $this->getSession()->set('tabActive', 61);
        $this->getSession()->set('stabActive', 2);

        return $this->redirect($this->generateUrl('_admin_company_editGet', array(
            'uid' => $uid
        )));
    }

    public function backtoDocgroupsystAction($uid)
    {
        $this->getSession()->set('tabActive', 71);
        $this->getSession()->set('stabActive', 2);

        return $this->redirect($this->generateUrl('_admin_company_editGet', array(
            'uid' => $uid
        )));
    }

    public function backtoDocgroupauditAction($uid)
    {
        $this->getSession()->set('tabActive', 81);
        $this->getSession()->set('stabActive', 2);

        return $this->redirect($this->generateUrl('_admin_company_editGet', array(
            'uid' => $uid
        )));
    }

    public function backtoMPayeAction($uid)
    {
        $this->getSession()->set('tabActive', 101);
        $this->getSession()->set('stabActive', 2);

        return $this->redirect($this->generateUrl('_admin_company_editGet', array(
            'uid' => $uid
        )));
    }

    /**
     *
     * @param string $uid
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editGetAction($uid)
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
                $sc = $this->getSecurityTokenStorage();
                $ac = $this->getSecurityAuthorizationChecker();
                $user = $sc->getToken()->getUser();
                if ($ac->isGranted('ROLE_SUPERADMIN', $user)) {
                    $traces = $em->getRepository('AcfDataBundle:Trace')->getAllByCompany($company, true);
                    $this->gvars['traces'] = array_reverse($traces);
                } else {
                    $traces = $em->getRepository('AcfDataBundle:Trace')->getAllByCompany($company, false);
                    $this->gvars['traces'] = array_reverse($traces);
                }
                $achatMarchNature = $em->getRepository('AcfDataBundle:CompanyNature')->findOneBy(array(
                    'company' => $company,
                    'label' => 'ACHATS DE MARCHANDISES'
                ));
                if (null == $achatMarchNature) {
                    $achatMarchNature = new CompanyNature();
                    $achatMarchNature->setCompany($company);
                    $achatMarchNature->setLabel('ACHATS DE MARCHANDISES');
                    $em->persist($achatMarchNature);
                    $em->flush();
                }
                $em->getRepository('AcfDataBundle:Buy')->updateCompanyNatureNullByCompany($company, $achatMarchNature);

                $companyUpdateTypeForm = $this->createForm(CompanyUpdateTypeTForm::class, $company);
                $companyUpdateCorporateNameForm = $this->createForm(CompanyUpdateCorporateNameTForm::class, $company);
                $companyUpdateRefForm = $this->createForm(CompanyUpdateRefTForm::class, $company);
                $companyUpdateFiscForm = $this->createForm(CompanyUpdateFiscTForm::class, $company);
                $companyUpdateTribunalForm = $this->createForm(CompanyUpdateTribunalTForm::class, $company);
                $companyUpdatePhysicaltypeForm = $this->createForm(CompanyUpdatePhysicaltypeTForm::class, $company);
                $companyUpdateCnssForm = $this->createForm(CompanyUpdateCnssTForm::class, $company);
                $companyUpdateCnssBureauForm = $this->createForm(CompanyUpdateCnssBureauTForm::class, $company);
                $companyUpdateSectorsForm = $this->createForm(CompanyUpdateSectorsTForm::class, $company);
                $companyUpdatePhoneForm = $this->createForm(CompanyUpdatePhoneTForm::class, $company);
                $companyUpdateMobileForm = $this->createForm(CompanyUpdateMobileTForm::class, $company);
                $companyUpdateFaxForm = $this->createForm(CompanyUpdateFaxTForm::class, $company);
                $companyUpdateEmailForm = $this->createForm(CompanyUpdateEmailTForm::class, $company);
                $companyUpdateAdrForm = $this->createForm(CompanyUpdateAdrTForm::class, $company);
                $companyUpdateOtherInfosForm = $this->createForm(CompanyUpdateOtherInfosTForm::class, $company);
                $companyUpdateActionvnForm = $this->createForm(CompanyUpdateActionvnTForm::class, $company);

                $stock = new Stock();
                $stock->setCompany($company);
                $stockNewForm = $this->createForm(StockNewTForm::class, $stock, array(
                    'company' => $company
                ));

                $address = new Address();
                $address->setCompany($company);
                $addressNewForm = $this->createForm(AddressNewTForm::class, $address, array(
                    'company' => $company
                ));

                $phone = new Phone();
                $phone->setCompany($company);
                $phoneNewForm = $this->createForm(PhoneNewTForm::class, $phone, array(
                    'company' => $company
                ));

                $companyFrame = new CompanyFrame();
                $companyFrame->setCompany($company);
                $companyFrameNewForm = $this->createForm(CompanyFrameNewTForm::class, $companyFrame, array(
                    'company' => $company
                ));

                $companyNature = new CompanyNature();
                $companyNature->setCompany($company);
                $companyNatureNewForm = $this->createForm(CompanyNatureNewTForm::class, $companyNature, array(
                    'company' => $company
                ));
                $companyNatureImportForm = $this->createForm(CompanyNatureImportTForm::class);

                $doc = new Doc();
                $doc->setCompany($company);
                $docNewForm = $this->createForm(DocNewTForm::class, $doc, array(
                    'company' => $company
                ));

                $pilot = new Pilot();
                $pilot->setCompany($company);
                $pilotNewForm = $this->createForm(PilotNewTForm::class, $pilot, array(
                    'company' => $company
                ));

                $companyUser = new CompanyUser();
                $companyUser->setCompany($company);
                $companyUserNewForm = $this->createForm(CompanyUserNewTForm::class, $companyUser, array(
                    'company' => $company
                ));

                $companyAdmin = new CompanyAdmin();
                $companyAdmin->setCompany($company);
                $companyAdminNewForm = $this->createForm(CompanyAdminNewTForm::class, $companyAdmin, array(
                    'company' => $company
                ));

                $companyLabel = new CompanyLabel();
                $companyLabel->setCompany($company);
                $companyLabelNewForm = $this->createForm(CompanyLabelNewTForm::class, $companyLabel, array(
                    'company' => $company
                ));
                $companyLabelImportForm = $this->createForm(CompanyLabelImportTForm::class);

                $customer = new Customer();
                $customer->setCompany($company);
                $customerNewForm = $this->createForm(CustomerNewTForm::class, $customer, array(
                    'company' => $company
                ));
                $customerImportForm = $this->createForm(CustomerImportTForm::class);

                $supplier = new Supplier();
                $supplier->setCompany($company);
                $supplierNewForm = $this->createForm(SupplierNewTForm::class, $supplier, array(
                    'company' => $company
                ));
                $supplierImportForm = $this->createForm(SupplierImportTForm::class);

                $bank = new Bank();
                $bank->setCompany($company);
                $bankNewForm = $this->createForm(BankNewTForm::class, $bank, array(
                    'company' => $company
                ));
                $bankImportForm = $this->createForm(BankImportTForm::class);

                $fund = new Fund();
                $fund->setCompany($company);
                $fundNewForm = $this->createForm(FundNewTForm::class, $fund, array(
                    'company' => $company
                ));
                $fundImportForm = $this->createForm(FundImportTForm::class);

                $withholding = new Withholding();
                $withholding->setCompany($company);
                $withholdingNewForm = $this->createForm(WithholdingNewTForm::class, $withholding, array(
                    'company' => $company
                ));
                $withholdingImportForm = $this->createForm(WithholdingImportTForm::class);

                $mbsale = new MBSale();
                $mbsale->setCompany($company);
                $mbsaleNewForm = $this->createForm(MBSaleNewTForm::class, $mbsale, array(
                    'company' => $company
                ));
                $mbsaleNewYearForm = $this->createForm(MBSaleNewYearTForm::class, $mbsale, array(
                    'company' => $company
                ));

                $mbpurchase = new MBPurchase();
                $mbpurchase->setCompany($company);
                $mbpurchaseNewForm = $this->createForm(MBPurchaseNewTForm::class, $mbpurchase, array(
                    'company' => $company
                ));
                $mbpurchaseNewYearForm = $this->createForm(MBPurchaseNewYearTForm::class, $mbpurchase, array(
                    'company' => $company
                ));

                $mpaye = new MPaye();
                $mpaye->setCompany($company);
                $mpayeNewForm = $this->createForm(MPayeNewTForm::class, $mpaye, array(
                    'company' => $company
                ));
                $mpayeNewYearForm = $this->createForm(MPayeNewYearTForm::class, $mpaye, array(
                    'company' => $company
                ));

                $docgroupcomptable = new Docgroupcomptable();
                $docgroupcomptable->setCompany($company);
                $docgroupcomptableNewForm = $this->createForm(DocgroupcomptableNewTForm::class, $docgroupcomptable, array(
                    'company' => $company
                ));

                $docgroup = new Docgroup();
                $docgroup->setCompany($company);
                $docgroupNewForm = $this->createForm(DocgroupNewTForm::class, $docgroup, array(
                    'company' => $company
                ));

                $shareholder = new Shareholder();
                $shareholder->setCompany($company);
                $shareholderNewForm = $this->createForm(ShareholderNewTForm::class, $shareholder, array(
                    'company' => $company
                ));

                $docgroupfiscal = new Docgroupfiscal();
                $docgroupfiscal->setCompany($company);
                $docgroupfiscalNewForm = $this->createForm(DocgroupfiscalNewTForm::class, $docgroupfiscal, array(
                    'company' => $company
                ));

                $docgroupperso = new Docgroupperso();
                $docgroupperso->setCompany($company);
                $docgrouppersoNewForm = $this->createForm(DocgrouppersoNewTForm::class, $docgroupperso, array(
                    'company' => $company
                ));

                $docgroupsyst = new Docgroupsyst();
                $docgroupsyst->setCompany($company);
                $docgroupsystNewForm = $this->createForm(DocgroupsystNewTForm::class, $docgroupsyst, array(
                    'company' => $company
                ));

                $docgroupaudit = new Docgroupaudit();
                $docgroupaudit->setCompany($company);
                $docgroupauditNewForm = $this->createForm(DocgroupauditNewTForm::class, $docgroupaudit, array(
                    'company' => $company
                ));

                $docgroupbank = new Docgroupbank();
                $docgroupbank->setCompany($company);
                $docgroupbankNewForm = $this->createForm(DocgroupbankNewTForm::class, $docgroupbank, array(
                    'company' => $company
                ));

                $sector = new Sector();
                $sectorNewForm = $this->createForm(SectorNewTForm::class, $sector);

                $liasseFolder = new LiasseFolder();
                $liasseFolder->setCompany($company);
                $liasseFolderNewForm = $this->createForm(LiasseFolderNewTForm::class, $liasseFolder, array(
                    'company' => $company
                ));

                $this->gvars['liasseFolder'] = $liasseFolder;

                $this->gvars['sector'] = $sector;
                $this->gvars['SectorNewForm'] = $sectorNewForm->createView();

                $this->gvars['company'] = $company;
                $this->gvars['stock'] = $stock;
                $this->gvars['address'] = $address;
                $this->gvars['phone'] = $phone;
                $this->gvars['companyFrame'] = $companyFrame;
                $this->gvars['companyNature'] = $companyNature;
                $this->gvars['doc'] = $doc;
                $this->gvars['pilot'] = $pilot;
                $this->gvars['companyUser'] = $companyUser;
                $this->gvars['companyAdmin'] = $companyAdmin;
                $this->gvars['companyLabel'] = $companyLabel;
                $this->gvars['customer'] = $customer;
                $this->gvars['supplier'] = $supplier;
                $this->gvars['bank'] = $bank;
                $this->gvars['fund'] = $fund;
                $this->gvars['withholding'] = $withholding;
                $this->gvars['docgroupcomptable'] = $docgroupcomptable;
                $this->gvars['shareholder'] = $withholding;
                $this->gvars['docgroup'] = $docgroup;
                $this->gvars['docgroupfiscal'] = $docgroupfiscal;
                $this->gvars['docgroupperso'] = $docgroupperso;
                $this->gvars['docgroupsyst'] = $docgroupsyst;
                $this->gvars['docgroupaudit'] = $docgroupaudit;
                $this->gvars['docgroupbank'] = $docgroupbank;
                $this->gvars['docgroupcomptables'] = $em->getRepository('AcfDataBundle:Docgroupcomptable')->getRoots($company);
                $this->gvars['docgroups'] = $em->getRepository('AcfDataBundle:Docgroup')->getRoots($company);
                $this->gvars['docgroupfiscals'] = $em->getRepository('AcfDataBundle:Docgroupfiscal')->getRoots($company);
                $this->gvars['docgrouppersos'] = $em->getRepository('AcfDataBundle:Docgroupperso')->getRoots($company);
                $this->gvars['docgroupsysts'] = $em->getRepository('AcfDataBundle:Docgroupsyst')->getRoots($company);
                $this->gvars['docgroupbanks'] = $em->getRepository('AcfDataBundle:Docgroupbank')->getRoots($company);
                $this->gvars['docgroupaudits'] = $em->getRepository('AcfDataBundle:Docgroupaudit')->getRoots($company);
                $this->gvars['liasseFolders'] = $em->getRepository('AcfDataBundle:LiasseFolder')->getRoots($company);

                $this->gvars['CompanyUpdateTypeForm'] = $companyUpdateTypeForm->createView();
                $this->gvars['CompanyUpdateRefForm'] = $companyUpdateRefForm->createView();
                $this->gvars['CompanyUpdateCorporateNameForm'] = $companyUpdateCorporateNameForm->createView();
                $this->gvars['CompanyUpdateFiscForm'] = $companyUpdateFiscForm->createView();
                $this->gvars['CompanyUpdateTribunalForm'] = $companyUpdateTribunalForm->createView();
                $this->gvars['CompanyUpdatePhysicaltypeForm'] = $companyUpdatePhysicaltypeForm->createView();
                $this->gvars['CompanyUpdateCnssForm'] = $companyUpdateCnssForm->createView();
                $this->gvars['CompanyUpdateCnssBureauForm'] = $companyUpdateCnssBureauForm->createView();
                $this->gvars['CompanyUpdateSectorsForm'] = $companyUpdateSectorsForm->createView();
                $this->gvars['CompanyUpdatePhoneForm'] = $companyUpdatePhoneForm->createView();
                $this->gvars['CompanyUpdateMobileForm'] = $companyUpdateMobileForm->createView();
                $this->gvars['CompanyUpdateFaxForm'] = $companyUpdateFaxForm->createView();
                $this->gvars['CompanyUpdateEmailForm'] = $companyUpdateEmailForm->createView();
                $this->gvars['CompanyUpdateAdrForm'] = $companyUpdateAdrForm->createView();
                $this->gvars['CompanyUpdateOtherInfosForm'] = $companyUpdateOtherInfosForm->createView();
                $this->gvars['CompanyUpdateActionvnForm'] = $companyUpdateActionvnForm->createView();
                $this->gvars['StockNewForm'] = $stockNewForm->createView();
                $this->gvars['AddressNewForm'] = $addressNewForm->createView();
                $this->gvars['PhoneNewForm'] = $phoneNewForm->createView();
                $this->gvars['CompanyFrameNewForm'] = $companyFrameNewForm->createView();
                $this->gvars['CompanyNatureNewForm'] = $companyNatureNewForm->createView();
                $this->gvars['CompanyNatureImportForm'] = $companyNatureImportForm->createView();
                $this->gvars['DocNewForm'] = $docNewForm->createView();
                $this->gvars['PilotNewForm'] = $pilotNewForm->createView();
                $this->gvars['CompanyUserNewForm'] = $companyUserNewForm->createView();
                $this->gvars['CompanyAdminNewForm'] = $companyAdminNewForm->createView();
                $this->gvars['CompanyLabelNewForm'] = $companyLabelNewForm->createView();
                $this->gvars['CompanyLabelImportForm'] = $companyLabelImportForm->createView();
                $this->gvars['CustomerNewForm'] = $customerNewForm->createView();
                $this->gvars['CustomerImportForm'] = $customerImportForm->createView();
                $this->gvars['SupplierNewForm'] = $supplierNewForm->createView();
                $this->gvars['SupplierImportForm'] = $supplierImportForm->createView();
                $this->gvars['BankNewForm'] = $bankNewForm->createView();
                $this->gvars['BankImportForm'] = $bankImportForm->createView();
                $this->gvars['FundNewForm'] = $fundNewForm->createView();
                $this->gvars['FundImportForm'] = $fundImportForm->createView();
                $this->gvars['WithholdingNewForm'] = $withholdingNewForm->createView();
                $this->gvars['WithholdingImportForm'] = $withholdingImportForm->createView();
                $this->gvars['MBSaleNewForm'] = $mbsaleNewForm->createView();
                $this->gvars['MBSaleNewYearForm'] = $mbsaleNewYearForm->createView();
                $this->gvars['MBPurchaseNewForm'] = $mbpurchaseNewForm->createView();
                $this->gvars['MBPurchaseNewYearForm'] = $mbpurchaseNewYearForm->createView();
                $this->gvars['MPayeNewForm'] = $mpayeNewForm->createView();
                $this->gvars['MPayeNewYearForm'] = $mpayeNewYearForm->createView();
                $this->gvars['DocgroupcomptableNewForm'] = $docgroupcomptableNewForm->createView();
                $this->gvars['DocgroupNewForm'] = $docgroupNewForm->createView();
                $this->gvars['ShareholderNewForm'] = $shareholderNewForm->createView();
                $this->gvars['DocgroupfiscalNewForm'] = $docgroupfiscalNewForm->createView();
                $this->gvars['DocgrouppersoNewForm'] = $docgrouppersoNewForm->createView();
                $this->gvars['DocgroupsystNewForm'] = $docgroupsystNewForm->createView();
                $this->gvars['DocgroupauditNewForm'] = $docgroupauditNewForm->createView();
                $this->gvars['DocgroupbankNewForm'] = $docgroupbankNewForm->createView();
                $this->gvars['LiasseFolderNewForm'] = $liasseFolderNewForm->createView();

                $mbsaleYears = $em->getRepository('AcfDataBundle:MBSale')->getAllYearByCompany($company);
                $mbpurchaseYears = $em->getRepository('AcfDataBundle:MBPurchase')->getAllYearByCompany($company);
                $mpayeYears = $em->getRepository('AcfDataBundle:MPaye')->getAllYearByCompany($company);

                $this->gvars['mbsaleYears'] = $mbsaleYears;
                $this->gvars['mbpurchaseYears'] = $mbpurchaseYears;
                $this->gvars['mpayeYears'] = $mpayeYears;

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 29);
                $this->getSession()->remove('tabActive');

                $this->gvars['stabActive'] = $this->getSession()->get('stabActive', 2);
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

                $banksConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
                    'name' => 'banksPrefix'
                ));
                if (null == $banksConstStr) {
                    $banksConstStr = new ConstantStr();
                    $banksConstStr->setName('banksPrefix');
                    $banksConstStr->setValue('532');
                    $em->persist($banksConstStr);
                    $em->flush();
                }
                $banksPrefix = $banksConstStr->getValue();
                $this->gvars['banksPrefix'] = $banksPrefix;

                $fundsConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
                    'name' => 'fundsPrefix'
                ));
                if (null == $fundsConstStr) {
                    $fundsConstStr = new ConstantStr();
                    $fundsConstStr->setName('fundsPrefix');
                    $fundsConstStr->setValue('540');
                    $em->persist($fundsConstStr);
                    $em->flush();
                }
                $fundsPrefix = $fundsConstStr->getValue();
                $this->gvars['fundsPrefix'] = $fundsPrefix;

                $withholdingsConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
                    'name' => 'withholdingsPrefix'
                ));
                if (null == $withholdingsConstStr) {
                    $withholdingsConstStr = new ConstantStr();
                    $withholdingsConstStr->setName('withholdingsPrefix');
                    $withholdingsConstStr->setValue('432');
                    $em->persist($withholdingsConstStr);
                    $em->flush();
                }
                $withholdingsPrefix = $withholdingsConstStr->getValue();
                $this->gvars['withholdingsPrefix'] = $withholdingsPrefix;

                $this->gvars['pagetitle'] = $this->translate('pagetitle.company.edit', array(
                    '%company%' => $company->getCorporateName()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.company.edit.txt', array(
                    '%company%' => $company->getCorporateName()
                ));

                return $this->renderResponse('AcfAdminBundle:Company:edit.html.twig', $this->gvars);
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editPostAction($uid)
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
                $sc = $this->getSecurityTokenStorage();
                $ac = $this->getSecurityAuthorizationChecker();
                $user = $sc->getToken()->getUser();
                if ($ac->isGranted('ROLE_SUPERADMIN', $user)) {
                    $traces = $em->getRepository('AcfDataBundle:Trace')->getAllByCompany($company, true);
                    $this->gvars['traces'] = array_reverse($traces);
                } else {
                    $traces = $em->getRepository('AcfDataBundle:Trace')->getAllByCompany($company, false);
                    $this->gvars['traces'] = array_reverse($traces);
                }
                $achatMarchNature = $em->getRepository('AcfDataBundle:CompanyNature')->findOneBy(array(
                    'company' => $company,
                    'label' => 'ACHATS DE MARCHANDISES'
                ));
                if (null == $achatMarchNature) {
                    $achatMarchNature = new CompanyNature();
                    $achatMarchNature->setCompany($company);
                    $achatMarchNature->setLabel('ACHATS DE MARCHANDISES');
                    $em->persist($achatMarchNature);
                    $em->flush();
                }
                $em->getRepository('AcfDataBundle:Buy')->updateCompanyNatureNullByCompany($company, $achatMarchNature);
                $em->flush();
                $companyUpdateTypeForm = $this->createForm(CompanyUpdateTypeTForm::class, $company);
                $companyUpdateCorporateNameForm = $this->createForm(CompanyUpdateCorporateNameTForm::class, $company);
                $companyUpdateRefForm = $this->createForm(CompanyUpdateRefTForm::class, $company);
                $companyUpdateFiscForm = $this->createForm(CompanyUpdateFiscTForm::class, $company);
                $companyUpdateTribunalForm = $this->createForm(CompanyUpdateTribunalTForm::class, $company);
                $companyUpdatePhysicaltypeForm = $this->createForm(CompanyUpdatePhysicaltypeTForm::class, $company);
                $companyUpdateCnssForm = $this->createForm(CompanyUpdateCnssTForm::class, $company);
                $companyUpdateCnssBureauForm = $this->createForm(CompanyUpdateCnssBureauTForm::class, $company);
                $companyUpdateSectorsForm = $this->createForm(CompanyUpdateSectorsTForm::class, $company);
                $companyUpdatePhoneForm = $this->createForm(CompanyUpdatePhoneTForm::class, $company);
                $companyUpdateMobileForm = $this->createForm(CompanyUpdateMobileTForm::class, $company);
                $companyUpdateFaxForm = $this->createForm(CompanyUpdateFaxTForm::class, $company);
                $companyUpdateEmailForm = $this->createForm(CompanyUpdateEmailTForm::class, $company);
                $companyUpdateAdrForm = $this->createForm(CompanyUpdateAdrTForm::class, $company);
                $companyUpdateOtherInfosForm = $this->createForm(CompanyUpdateOtherInfosTForm::class, $company);
                $companyUpdateActionvnForm = $this->createForm(CompanyUpdateActionvnTForm::class, $company);

                $stock = new Stock();
                $stock->setCompany($company);
                $stockNewForm = $this->createForm(StockNewTForm::class, $stock, array(
                    'company' => $company
                ));

                $address = new Address();
                $address->setCompany($company);
                $addressNewForm = $this->createForm(AddressNewTForm::class, $address, array(
                    'company' => $company
                ));

                $phone = new Phone();
                $phone->setCompany($company);
                $phoneNewForm = $this->createForm(PhoneNewTForm::class, $phone, array(
                    'company' => $company
                ));

                $companyFrame = new CompanyFrame();
                $companyFrame->setCompany($company);
                $companyFrameNewForm = $this->createForm(CompanyFrameNewTForm::class, $companyFrame, array(
                    'company' => $company
                ));

                $companyNature = new CompanyNature();
                $companyNature->setCompany($company);
                $companyNatureNewForm = $this->createForm(CompanyNatureNewTForm::class, $companyNature, array(
                    'company' => $company
                ));
                $companyNatureImportForm = $this->createForm(CompanyNatureImportTForm::class);

                $doc = new Doc();
                $doc->setCompany($company);
                $docNewForm = $this->createForm(DocNewTForm::class, $doc, array(
                    'company' => $company
                ));

                $pilot = new Pilot();
                $pilot->setCompany($company);
                $pilotNewForm = $this->createForm(PilotNewTForm::class, $pilot, array(
                    'company' => $company
                ));

                $companyUser = new CompanyUser();
                $companyUser->setCompany($company);
                $companyUserNewForm = $this->createForm(CompanyUserNewTForm::class, $companyUser, array(
                    'company' => $company
                ));

                $companyAdmin = new CompanyAdmin();
                $companyAdmin->setCompany($company);
                $companyAdminNewForm = $this->createForm(CompanyAdminNewTForm::class, $companyAdmin, array(
                    'company' => $company
                ));

                $companyLabel = new CompanyLabel();
                $companyLabel->setCompany($company);
                $companyLabelNewForm = $this->createForm(CompanyLabelNewTForm::class, $companyLabel, array(
                    'company' => $company
                ));
                $companyLabelImportForm = $this->createForm(CompanyLabelImportTForm::class);

                $customer = new Customer();
                $customer->setCompany($company);
                $customerNewForm = $this->createForm(CustomerNewTForm::class, $customer, array(
                    'company' => $company
                ));
                $customerImportForm = $this->createForm(CustomerImportTForm::class);

                $supplier = new Supplier();
                $supplier->setCompany($company);
                $supplierNewForm = $this->createForm(SupplierNewTForm::class, $supplier, array(
                    'company' => $company
                ));
                $supplierImportForm = $this->createForm(SupplierImportTForm::class);

                $bank = new Bank();
                $bank->setCompany($company);
                $bankNewForm = $this->createForm(BankNewTForm::class, $bank, array(
                    'company' => $company
                ));
                $bankImportForm = $this->createForm(BankImportTForm::class);

                $fund = new Fund();
                $fund->setCompany($company);
                $fundNewForm = $this->createForm(FundNewTForm::class, $fund, array(
                    'company' => $company
                ));
                $fundImportForm = $this->createForm(FundImportTForm::class);

                $withholding = new Withholding();
                $withholding->setCompany($company);
                $withholdingNewForm = $this->createForm(WithholdingNewTForm::class, $withholding, array(
                    'company' => $company
                ));
                $withholdingImportForm = $this->createForm(WithholdingImportTForm::class);

                $mbsale = new MBSale();
                $mbsale->setCompany($company);
                $mbsaleNewForm = $this->createForm(MBSaleNewTForm::class, $mbsale, array(
                    'company' => $company
                ));
                $mbsaleNewYearForm = $this->createForm(MBSaleNewYearTForm::class, $mbsale, array(
                    'company' => $company
                ));

                $mbpurchase = new MBPurchase();
                $mbpurchase->setCompany($company);
                $mbpurchaseNewForm = $this->createForm(MBPurchaseNewTForm::class, $mbpurchase, array(
                    'company' => $company
                ));
                $mbpurchaseNewYearForm = $this->createForm(MBPurchaseNewYearTForm::class, $mbpurchase, array(
                    'company' => $company
                ));

                $mpaye = new MPaye();
                $mpaye->setCompany($company);
                $mpayeNewForm = $this->createForm(MPayeNewTForm::class, $mpaye, array(
                    'company' => $company
                ));
                $mpayeNewYearForm = $this->createForm(MPayeNewYearTForm::class, $mpaye, array(
                    'company' => $company
                ));

                $docgroupcomptable = new Docgroupcomptable();
                $docgroupcomptable->setCompany($company);
                $docgroupcomptableNewForm = $this->createForm(DocgroupcomptableNewTForm::class, $docgroupcomptable, array(
                    'company' => $company
                ));

                $docgroup = new Docgroup();
                $docgroup->setCompany($company);
                $docgroupNewForm = $this->createForm(DocgroupNewTForm::class, $docgroup, array(
                    'company' => $company
                ));

                $shareholder = new Shareholder();
                $shareholder->setCompany($company);
                $shareholderNewForm = $this->createForm(ShareholderNewTForm::class, $shareholder, array(
                    'company' => $company
                ));

                $docgroupfiscal = new Docgroupfiscal();
                $docgroupfiscal->setCompany($company);
                $docgroupfiscalNewForm = $this->createForm(DocgroupfiscalNewTForm::class, $docgroupfiscal, array(
                    'company' => $company
                ));

                $docgroupperso = new Docgroupperso();
                $docgroupperso->setCompany($company);
                $docgrouppersoNewForm = $this->createForm(DocgrouppersoNewTForm::class, $docgroupperso, array(
                    'company' => $company
                ));

                $docgroupsyst = new Docgroupsyst();
                $docgroupsyst->setCompany($company);
                $docgroupsystNewForm = $this->createForm(DocgroupsystNewTForm::class, $docgroupsyst, array(
                    'company' => $company
                ));

                $docgroupaudit = new Docgroupaudit();
                $docgroupaudit->setCompany($company);
                $docgroupauditNewForm = $this->createForm(DocgroupauditNewTForm::class, $docgroupaudit, array(
                    'company' => $company
                ));

                $docgroupbank = new Docgroupbank();
                $docgroupbank->setCompany($company);
                $docgroupbankNewForm = $this->createForm(DocgroupbankNewTForm::class, $docgroupbank, array(
                    'company' => $company
                ));

                $liasseFolder = new LiasseFolder();
                $liasseFolder->setCompany($company);
                $liasseFolderNewForm = $this->createForm(LiasseFolderNewTForm::class, $liasseFolder, array(
                    'company' => $company
                ));

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 29);
                $this->getSession()->remove('tabActive');

                $this->gvars['stabActive'] = $this->getSession()->get('stabActive', 2);
                $this->getSession()->remove('stabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                $cloneCompany = clone $company;

                if (isset($reqData['CompanyUpdateTypeForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyUpdateTypeForm->handleRequest($request);
                    if ($companyUpdateTypeForm->isValid()) {
                        $em->persist($company);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Company.edit.success', array(
                            '%company%' => $company->getCorporateName()
                        )));

                        $this->traceEntity($cloneCompany, $company);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Company.edit.failure', array(
                            '%company%' => $company->getCorporateName()
                        )));
                    }
                } elseif (isset($reqData['CompanyUpdateCorporateNameForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyUpdateCorporateNameForm->handleRequest($request);
                    if ($companyUpdateCorporateNameForm->isValid()) {
                        $em->persist($company);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Company.edit.success', array(
                            '%company%' => $company->getCorporateName()
                        )));

                        $this->traceEntity($cloneCompany, $company);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Company.edit.failure', array(
                            '%company%' => $company->getCorporateName()
                        )));
                    }
                } elseif (isset($reqData['CompanyUpdateRefForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyUpdateRefForm->handleRequest($request);
                    if ($companyUpdateRefForm->isValid()) {
                        $em->persist($company);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Company.edit.success', array(
                            '%company%' => $company->getCorporateName()
                        )));

                        $this->traceEntity($cloneCompany, $company);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Company.edit.failure', array(
                            '%company%' => $company->getCorporateName()
                        )));
                    }
                } elseif (isset($reqData['CompanyUpdateFiscForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyUpdateFiscForm->handleRequest($request);
                    if ($companyUpdateFiscForm->isValid()) {
                        $em->persist($company);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Company.edit.success', array(
                            '%company%' => $company->getCorporateName()
                        )));

                        $this->traceEntity($cloneCompany, $company);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Company.edit.failure', array(
                            '%company%' => $company->getCorporateName()
                        )));
                    }
                } elseif (isset($reqData['CompanyUpdateTribunalForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyUpdateTribunalForm->handleRequest($request);
                    if ($companyUpdateTribunalForm->isValid()) {
                        $em->persist($company);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Company.edit.success', array(
                            '%company%' => $company->getCorporateName()
                        )));

                        $this->traceEntity($cloneCompany, $company);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Company.edit.failure', array(
                            '%company%' => $company->getCorporateName()
                        )));
                    }
                } elseif (isset($reqData['CompanyUpdatePhysicaltypeForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyUpdatePhysicaltypeForm->handleRequest($request);
                    if ($companyUpdatePhysicaltypeForm->isValid()) {
                        $em->persist($company);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Company.edit.success', array(
                            '%company%' => $company->getCorporateName()
                        )));

                        $this->traceEntity($cloneCompany, $company);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Company.edit.failure', array(
                            '%company%' => $company->getCorporateName()
                        )));
                    }
                } elseif (isset($reqData['CompanyUpdateCnssForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyUpdateCnssForm->handleRequest($request);
                    if ($companyUpdateCnssForm->isValid()) {
                        $em->persist($company);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Company.edit.success', array(
                            '%company%' => $company->getCorporateName()
                        )));

                        $this->traceEntity($cloneCompany, $company);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Company.edit.failure', array(
                            '%company%' => $company->getCorporateName()
                        )));
                    }
                } elseif (isset($reqData['CompanyUpdateCnssBureauForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyUpdateCnssBureauForm->handleRequest($request);
                    if ($companyUpdateCnssBureauForm->isValid()) {
                        $em->persist($company);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Company.edit.success', array(
                            '%company%' => $company->getCorporateName()
                        )));

                        $this->traceEntity($cloneCompany, $company);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Company.edit.failure', array(
                            '%company%' => $company->getCorporateName()
                        )));
                    }
                } elseif (isset($reqData['CompanyUpdateSectorsForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyUpdateSectorsForm->handleRequest($request);
                    if ($companyUpdateSectorsForm->isValid()) {
                        $em->persist($company);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Company.edit.success', array(
                            '%company%' => $company->getCorporateName()
                        )));

                        $this->traceEntity($cloneCompany, $company);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Company.edit.failure', array(
                            '%company%' => $company->getCorporateName()
                        )));
                    }
                } elseif (isset($reqData['CompanyUpdatePhoneForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyUpdatePhoneForm->handleRequest($request);
                    if ($companyUpdatePhoneForm->isValid()) {
                        $em->persist($company);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Company.edit.success', array(
                            '%company%' => $company->getCorporateName()
                        )));

                        $this->traceEntity($cloneCompany, $company);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Company.edit.failure', array(
                            '%company%' => $company->getCorporateName()
                        )));
                    }
                } elseif (isset($reqData['CompanyUpdateMobileForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyUpdateMobileForm->handleRequest($request);
                    if ($companyUpdateMobileForm->isValid()) {
                        $em->persist($company);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Company.edit.success', array(
                            '%company%' => $company->getCorporateName()
                        )));

                        $this->traceEntity($cloneCompany, $company);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Company.edit.failure', array(
                            '%company%' => $company->getCorporateName()
                        )));
                    }
                } elseif (isset($reqData['CompanyUpdateFaxForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyUpdateFaxForm->handleRequest($request);
                    if ($companyUpdateFaxForm->isValid()) {
                        $em->persist($company);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Company.edit.success', array(
                            '%company%' => $company->getCorporateName()
                        )));

                        $this->traceEntity($cloneCompany, $company);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Company.edit.failure', array(
                            '%company%' => $company->getCorporateName()
                        )));
                    }
                } elseif (isset($reqData['CompanyUpdateEmailForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyUpdateEmailForm->handleRequest($request);
                    if ($companyUpdateEmailForm->isValid()) {
                        $em->persist($company);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Company.edit.success', array(
                            '%company%' => $company->getCorporateName()
                        )));

                        $this->traceEntity($cloneCompany, $company);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Company.edit.failure', array(
                            '%company%' => $company->getCorporateName()
                        )));
                    }
                } elseif (isset($reqData['CompanyUpdateAdrForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyUpdateAdrForm->handleRequest($request);
                    if ($companyUpdateAdrForm->isValid()) {
                        $em->persist($company);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Company.edit.success', array(
                            '%company%' => $company->getCorporateName()
                        )));

                        $this->traceEntity($cloneCompany, $company);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Company.edit.failure', array(
                            '%company%' => $company->getCorporateName()
                        )));
                    }
                } elseif (isset($reqData['CompanyUpdateOtherInfosForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyUpdateOtherInfosForm->handleRequest($request);
                    if ($companyUpdateOtherInfosForm->isValid()) {
                        $em->persist($company);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Company.edit.success', array(
                            '%company%' => $company->getCorporateName()
                        )));

                        $this->traceEntity($cloneCompany, $company);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Company.edit.failure', array(
                            '%company%' => $company->getCorporateName()
                        )));
                    }
                } elseif (isset($reqData['CompanyUpdateActionvnForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyUpdateActionvnForm->handleRequest($request);
                    if ($companyUpdateActionvnForm->isValid()) {
                        $em->persist($company);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Company.edit.success', array(
                            '%company%' => $company->getCorporateName()
                        )));

                        $this->traceEntity($cloneCompany, $company);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Company.edit.failure', array(
                            '%company%' => $company->getCorporateName()
                        )));
                    }
                } elseif (isset($reqData['StockNewForm'])) {
                    $this->gvars['tabActive'] = 3;
                    $this->getSession()->set('tabActive', 3);
                    $stockNewForm->handleRequest($request);
                    if ($stockNewForm->isValid()) {
                        $em->persist($stock);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Stock.add.success', array(
                            '%stock%' => $stock->getYear()
                        )));

                        $this->gvars['stabActive'] = 2;
                        $this->getSession()->set('stabActive', 2);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Address.add.failure'));
                    }
                } elseif (isset($reqData['AddressNewForm'])) {
                    $this->gvars['tabActive'] = 4;
                    $this->getSession()->set('tabActive', 4);
                    $addressNewForm->handleRequest($request);
                    if ($addressNewForm->isValid()) {
                        $em->persist($address);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Address.add.success', array(
                            '%address%' => $address->getLabel()
                        )));

                        $this->gvars['stabActive'] = 2;
                        $this->getSession()->set('stabActive', 2);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Address.add.failure'));
                    }
                } elseif (isset($reqData['PhoneNewForm'])) {
                    $this->gvars['tabActive'] = 5;
                    $this->getSession()->set('tabActive', 5);
                    $phoneNewForm->handleRequest($request);
                    if ($phoneNewForm->isValid()) {
                        $em->persist($phone);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Phone.add.success', array(
                            '%phone%' => $phone->getLabel()
                        )));

                        $this->gvars['stabActive'] = 2;
                        $this->getSession()->set('stabActive', 2);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Phone.add.failure'));
                    }
                } elseif (isset($reqData['CompanyFrameNewForm'])) {
                    $this->gvars['tabActive'] = 6;
                    $this->getSession()->set('tabActive', 6);
                    $companyFrameNewForm->handleRequest($request);
                    if ($companyFrameNewForm->isValid()) {
                        $em->persist($companyFrame);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('CompanyFrame.add.success', array(
                            '%companyFrame%' => $companyFrame->getFullName()
                        )));

                        $this->gvars['stabActive'] = 2;
                        $this->getSession()->set('stabActive', 2);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('CompanyFrame.add.failure'));
                    }
                } elseif (isset($reqData['CompanyNatureNewForm'])) {
                    $this->gvars['tabActive'] = 7;
                    $this->getSession()->set('tabActive', 7);
                    $this->gvars['stabActive'] = 1;
                    $this->getSession()->set('stabActive', 1);
                    $companyNatureNewForm->handleRequest($request);
                    if ($companyNatureNewForm->isValid()) {
                        $em->persist($companyNature);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('CompanyNature.add.success', array(
                            '%companyNature%' => $companyNature->getLabel()
                        )));

                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('CompanyNature.add.failure'));
                    }
                } elseif (isset($reqData['CompanyNatureImportForm'])) {
                    $this->gvars['tabActive'] = 8;
                    $this->getSession()->set('tabActive', 8);
                    $this->gvars['stabActive'] = 2;
                    $this->getSession()->set('stabActive', 2);
                    $companyNatureImportForm->handleRequest($request);
                    if ($companyNatureImportForm->isValid()) {

                        ini_set('memory_limit', '4096M');
                        ini_set('max_execution_time', '0');
                        $extension = $companyNatureImportForm['excel']->getData()->guessExtension();
                        if ($extension == 'zip') {
                            $extension = 'xlsx';
                        }

                        $filename = uniqid() . '.' . $extension;
                        $companyNatureImportForm['excel']->getData()->move($this->getParameter('adapter_files'), $filename);
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
                        $companyNaturesNew = 0;
                        $lineUnprocessed = 0;
                        $lineError = 0;
                        for ($row = 1; $row <= $highestRow; $row++) {
                            $lineRead++;

                            $label = \trim(\strval($worksheet->getCellByColumnAndRow(0, $row)->getValue()));
                            $color = \trim(\strval($worksheet->getCellByColumnAndRow(1, $row)->getValue()));

                            if ($label != '') {
                                $companyNature = $em->getRepository('AcfDataBundle:CompanyNature')->findOneBy(array(
                                    'company' => $company,
                                    'label' => $label
                                ));
                                if (null == $companyNature) {
                                    $companyNaturesNew++;

                                    $companyNature = new CompanyNature();
                                    $companyNature->setCompany($company);
                                    $companyNature->setLabel($label);
                                    $companyNature->setColor($color);

                                    $em->persist($companyNature);
                                } else {
                                    $lineUnprocessed++;
                                    $log .= "la Nature d'Achat " . $lineRead . ' existe déjà<br>';
                                }
                            } else {
                                $lineError++;
                                $log .= 'la ligne ' . $lineRead . ' contient des erreurs<br>';
                            }
                        }
                        $em->flush();

                        $log .= $lineRead . ' lignes lues<br>';
                        $log .= $companyNaturesNew . " nouvelles Natures d'Achat<br>";
                        $log .= $lineUnprocessed . " Natures d'Achat déjà dans la base<br>";
                        $log .= $lineError . ' lignes contenant des erreurs<br>';

                        $this->flashMsgSession('log', $log);

                        $this->flashMsgSession('success', $this->translate('CompanyNature.import.success'));

                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('CompanyNature.import.failure'));
                    }
                } elseif (isset($reqData['DocNewForm'])) {
                    $this->gvars['tabActive'] = 8;
                    $this->getSession()->set('tabActive', 8);
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
                            $doc->setCompany($company);
                            $doc->setFileName($fileName);
                            $doc->setOriginalName($originalName);
                            $doc->setSize($size);
                            $doc->setMimeType($mimeType);
                            $doc->setMd5($md5);
                            $doc->setDescription($docNewForm['description']->getData());
                            $em->persist($doc);

                            $docNames .= $doc->getOriginalName() . ' ';

                            $docs[] = $doc;
                        }
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Doc.add.success', array(
                            '%doc%' => $docNames
                        )));

                        $from = $this->getParameter('mail_from');
                        $fromName = $this->getParameter('mail_from_name');
                        $subject = $this->translate('_mail.newdocsCloud.subject', array(), 'messages');

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
                                $mvars['logo'] = $message->embed(\Swift_Image::fromPath($this->getParameter('kernel.root_dir') . '/../web/bundles/acfres/images/logo_acf.jpg'));
                                $message->setBody($this->renderView('AcfAdminBundle:Doc:sendmail.html.twig', $mvars), 'text/html');
                                $this->sendmail($message);
                            }
                        }

                        $this->gvars['stabActive'] = 2;
                        $this->getSession()->set('stabActive', 2);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Doc.add.failure'));
                    }
                } elseif (isset($reqData['PilotNewForm'])) {
                    $this->gvars['tabActive'] = 9;
                    $this->getSession()->set('tabActive', 9);
                    $pilotNewForm->handleRequest($request);
                    if ($pilotNewForm->isValid()) {

                        $em->persist($pilot);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Pilot.add.success', array(
                            '%pilot%' => $pilot->getMission()
                        )));

                        $this->gvars['stabActive'] = 2;
                        $this->getSession()->set('stabActive', 2);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Pilot.add.failure'));
                    }
                } elseif (isset($reqData['CompanyUserNewForm'])) {
                    $this->gvars['tabActive'] = 10;
                    $this->getSession()->set('tabActive', 10);
                    $companyUserNewForm->handleRequest($request);
                    if ($companyUserNewForm->isValid()) {
                        $user = $companyUser->getUser();
                        $hasClientRole = false;
                        foreach ($user->getUserRoles() as $role) {
                            if ($role->getName() == 'ROLE_CLIENT1') {
                                $hasClientRole = true;
                            }
                        }
                        if (!$hasClientRole) {

                            $roleClient = $em->getRepository('AcfDataBundle:Role')->findOneBy(array(
                                'name' => 'ROLE_CLIENT1'
                            ));
                            if (null == $roleClient) {
                                $roleClient = new Role();
                                $roleClient->setName('ROLE_CLIENT1');
                            }

                            $user->addUserRole($roleClient);
                            $em->persist($user);
                        }

                        $em->persist($companyUser);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('CompanyUser.add.success', array(
                            '%company%' => $companyUser->getCompany()
                                ->getCorporateName(),
                            '%user%' => $companyUser->getUser()
                                ->getFullName()
                        )));

                        $this->gvars['stabActive'] = 2;
                        $this->getSession()->set('stabActive', 2);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('CompanyUser.add.failure'));
                    }
                } elseif (isset($reqData['CompanyAdminNewForm'])) {
                    $this->gvars['tabActive'] = 11;
                    $this->getSession()->set('tabActive', 11);
                    $companyAdminNewForm->handleRequest($request);
                    if ($companyAdminNewForm->isValid()) {
                        $user = $companyAdmin->getUser();
                        $hasClientRole = false;
                        foreach ($user->getUserRoles() as $role) {
                            if ($role->getName() == 'ROLE_ADMIN') {
                                $hasClientRole = true;
                            }
                        }
                        if (!$hasClientRole) {

                            $roleAdmin = $em->getRepository('AcfDataBundle:Role')->findOneBy(array(
                                'name' => 'ROLE_ADMIN'
                            ));
                            if (null == $roleAdmin) {
                                $roleAdmin = new Role();
                                $roleAdmin->setName('ROLE_ADMIN');
                            }

                            $user->addUserRole($roleAdmin);
                            $em->persist($user);
                        }

                        $em->persist($companyAdmin);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('CompanyAdmin.add.success', array(
                            '%company%' => $companyAdmin->getCompany()
                                ->getCorporateName(),
                            '%user%' => $companyAdmin->getUser()
                                ->getFullName()
                        )));

                        $this->gvars['stabActive'] = 2;
                        $this->getSession()->set('stabActive', 2);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('CompanyAdmin.add.failure'));
                    }
                } elseif (isset($reqData['CompanyLabelNewForm'])) {
                    $this->gvars['tabActive'] = 21;
                    $this->getSession()->set('tabActive', 21);
                    $this->gvars['stabActive'] = 1;
                    $this->getSession()->set('stabActive', 1);
                    $companyLabelNewForm->handleRequest($request);
                    if ($companyLabelNewForm->isValid()) {
                        $em->persist($companyLabel);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('CompanyLabel.add.success', array(
                            '%companyLabel%' => $companyLabel->getName()
                        )));

                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('CompanyLabel.add.failure'));
                    }
                } elseif (isset($reqData['CompanyLabelImportForm'])) {
                    $this->gvars['tabActive'] = 21;
                    $this->getSession()->set('tabActive', 21);
                    $this->gvars['stabActive'] = 2;
                    $this->getSession()->set('stabActive', 2);
                    $companyLabelImportForm->handleRequest($request);
                    if ($companyLabelImportForm->isValid()) {

                        ini_set('memory_limit', '4096M');
                        ini_set('max_execution_time', '0');
                        $extension = $companyLabelImportForm['excel']->getData()->guessExtension();
                        if ($extension == 'zip') {
                            $extension = 'xlsx';
                        }

                        $filename = uniqid() . '.' . $extension;
                        $companyLabelImportForm['excel']->getData()->move($this->getParameter('adapter_files'), $filename);
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
                        $lineRead = 1;
                        $companyLabelsNew = 0;
                        $lineUnprocessed = 0;
                        $lineError = 0;
                        for ($row = 2; $row <= $highestRow; $row++) {
                            $lineRead++;

                            $name = \trim(\strval($worksheet->getCellByColumnAndRow(0, $row)->getValue()));
                            $abrev = \trim(\strval($worksheet->getCellByColumnAndRow(1, $row)->getValue()));

                            if ($abrev != '' && $name != '') {
                                $companyLabel = $em->getRepository('AcfDataBundle:CompanyLabel')->findOneBy(array(
                                    'company' => $company,
                                    'abrev' => $abrev
                                ));
                                if (null == $companyLabel) {
                                    $companyLabelsNew++;

                                    $companyLabel = new CompanyLabel();
                                    $companyLabel->setCompany($company);
                                    $companyLabel->setAbrev($abrev);
                                    $companyLabel->setName($name);

                                    $em->persist($companyLabel);
                                } else {
                                    $lineUnprocessed++;
                                    $log .= 'le Code Journal à la ligne ' . $lineRead . ' existe déjà<br>';
                                }
                            } else {
                                $lineError++;
                                $log .= 'la ligne ' . $lineRead . ' contient des erreurs<br>';
                            }
                        }
                        $em->flush();

                        $log .= $lineRead . ' lignes lues<br>';
                        $log .= $companyLabelsNew . ' nouveaux Journaux<br>';
                        $log .= $lineUnprocessed . ' Journaux déjà dans la base<br>';
                        $log .= $lineError . ' lignes contenant des erreurs<br>';

                        $this->flashMsgSession('log', $log);

                        $this->flashMsgSession('success', $this->translate('CompanyLabel.import.success'));

                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('CompanyLabel.import.failure'));
                    }
                } elseif (isset($reqData['CustomerNewForm'])) {
                    $this->gvars['tabActive'] = 22;
                    $this->getSession()->set('tabActive', 22);
                    $this->gvars['stabActive'] = 1;
                    $this->getSession()->set('stabActive', 1);
                    $customerNewForm->handleRequest($request);
                    if ($customerNewForm->isValid()) {
                        $em->persist($customer);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Customer.add.success', array(
                            '%customer%' => $customer->getLabel()
                        )));

                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Customer.add.failure'));
                    }
                } elseif (isset($reqData['CustomerImportForm'])) {
                    $this->gvars['tabActive'] = 22;
                    $this->getSession()->set('tabActive', 22);
                    $this->gvars['stabActive'] = 2;
                    $this->getSession()->set('stabActive', 2);
                    $customerImportForm->handleRequest($request);
                    if ($customerImportForm->isValid()) {

                        ini_set('memory_limit', '4096M');
                        ini_set('max_execution_time', '0');
                        $extension = $customerImportForm['excel']->getData()->guessExtension();
                        if ($extension == 'zip') {
                            $extension = 'xlsx';
                        }

                        $filename = uniqid() . '.' . $extension;
                        $customerImportForm['excel']->getData()->move($this->getParameter('adapter_files'), $filename);
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
                            $log .= "Aucune Feuille de Titre 'Sage' trouvée tentative d'import depuis la première Feuille<br>";
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
                        $lineRead = 1;
                        $customersNew = 0;
                        $lineUnprocessed = 0;
                        $lineError = 0;
                        for ($row = 2; $row <= $highestRow; $row++) {
                            $lineRead++;

                            $xlsLabel = \trim(\strval($worksheet->getCellByColumnAndRow(0, $row)->getValue()));
                            $xlsNumber = \trim(\strval($worksheet->getCellByColumnAndRow(1, $row)->getValue()));
                            $xlsMatricule = \trim(\strval($worksheet->getCellByColumnAndRow(2, $row)->getValue()));
                            $xlsAddress = \trim(\strval($worksheet->getCellByColumnAndRow(4, $row)->getValue()));
                            $xlsRc = \trim(\strval($worksheet->getCellByColumnAndRow(5, $row)->getValue()));

                            if ($xlsNumber != '' && \is_numeric($xlsNumber)) {
                                $xlsNumber = \intval($xlsNumber) - $customersPrefixNum;
                            }

                            if ($xlsNumber != '' && $xlsNumber > 0 && $xlsLabel != '') {

                                $customer1 = $em->getRepository('AcfDataBundle:Customer')->findOneBy(array(
                                    'company' => $company,
                                    'number' => $xlsNumber
                                ));
                                $customer2 = $em->getRepository('AcfDataBundle:Customer')->findOneBy(array(
                                    'company' => $company,
                                    'label' => $xlsLabel
                                ));
                                if (null == $customer1 && null == $customer2) {
                                    $customersNew++;

                                    $customer = new Customer();
                                    $customer->setCompany($company);
                                    $customer->setLabel($xlsLabel);
                                    $customer->setNumber($xlsNumber);
                                    $customer->setFisc($xlsMatricule);
                                    $customer->setCin($xlsMatricule);
                                    $customer->setAddress($xlsAddress);
                                    $customer->setCommercialRegister($xlsRc);

                                    $em->persist($customer);
                                } else {
                                    $lineUnprocessed++;
                                    $log .= 'le Client à la ligne ' . $lineRead . ' existe déjà<br>';
                                }
                            } else {
                                $lineError++;
                                $log .= 'la ligne ' . $lineRead . ' contient des erreurs (COL A : ' . $xlsLabel . ', COL B: ' . $xlsNumber . ')<br>';
                            }
                        }
                        $em->flush();

                        $log .= $lineRead . ' lignes lues<br>';
                        $log .= $customersNew . ' nouveaux Clients<br>';
                        $log .= $lineUnprocessed . ' Clients déjà dans la base<br>';
                        $log .= $lineError . ' lignes contenant des erreurs<br>';

                        $this->flashMsgSession('log', $log);

                        $this->flashMsgSession('success', $this->translate('Customer.import.success'));

                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Customer.import.failure'));
                    }
                } elseif (isset($reqData['SupplierNewForm'])) {
                    $this->gvars['tabActive'] = 23;
                    $this->getSession()->set('tabActive', 23);
                    $this->gvars['stabActive'] = 1;
                    $this->getSession()->set('stabActive', 1);
                    $supplierNewForm->handleRequest($request);
                    if ($supplierNewForm->isValid()) {
                        $em->persist($supplier);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Supplier.add.success', array(
                            '%supplier%' => $supplier->getLabel()
                        )));

                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Supplier.add.failure'));
                    }
                } elseif (isset($reqData['SupplierImportForm'])) {
                    $this->gvars['tabActive'] = 23;
                    $this->getSession()->set('tabActive', 23);
                    $this->gvars['stabActive'] = 2;
                    $this->getSession()->set('stabActive', 2);
                    $supplierImportForm->handleRequest($request);
                    if ($supplierImportForm->isValid()) {

                        ini_set('memory_limit', '4096M');
                        ini_set('max_execution_time', '0');
                        $extension = $supplierImportForm['excel']->getData()->guessExtension();
                        if ($extension == 'zip') {
                            $extension = 'xlsx';
                        }

                        $filename = uniqid() . '.' . $extension;
                        $supplierImportForm['excel']->getData()->move($this->getParameter('adapter_files'), $filename);
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
                        $lineRead = 1;
                        $suppliersNew = 0;
                        $lineUnprocessed = 0;
                        $lineError = 0;
                        for ($row = 2; $row <= $highestRow; $row++) {
                            $lineRead++;

                            $xlsLabel = \trim(\strval($worksheet->getCellByColumnAndRow(0, $row)->getValue()));
                            $xlsNumber = \trim(\strval($worksheet->getCellByColumnAndRow(1, $row)->getValue()));
                            $xlsMatricule = \trim(\strval($worksheet->getCellByColumnAndRow(2, $row)->getValue()));
                            $xlsAddress = \trim(\strval($worksheet->getCellByColumnAndRow(3, $row)->getValue()));
                            $xlsRc = \trim(\strval($worksheet->getCellByColumnAndRow(4, $row)->getValue()));

                            if ($xlsNumber != '' && \is_numeric($xlsNumber)) {
                                $xlsNumber = \intval($xlsNumber) - $suppliersPrefixNum;
                            }

                            if ($xlsNumber != '' && $xlsNumber > 0 && $xlsLabel != '') {

                                $supplier1 = $em->getRepository('AcfDataBundle:Supplier')->findOneBy(array(
                                    'company' => $company,
                                    'number' => $xlsNumber
                                ));
                                $supplier2 = $em->getRepository('AcfDataBundle:Supplier')->findOneBy(array(
                                    'company' => $company,
                                    'label' => $xlsLabel
                                ));
                                if (null == $supplier1 && null == $supplier2) {
                                    $suppliersNew++;

                                    $supplier = new Supplier();
                                    $supplier->setCompany($company);
                                    $supplier->setLabel($xlsLabel);
                                    $supplier->setNumber($xlsNumber);
                                    $supplier->setFisc($xlsMatricule);
                                    $supplier->setCin($xlsMatricule);
                                    $supplier->setAddress($xlsAddress);
                                    $supplier->setCommercialRegister($xlsRc);

                                    $em->persist($supplier);
                                } else {
                                    $lineUnprocessed++;
                                    $log .= 'le Fournisseur à la ligne ' . $lineRead . ' existe déjà<br>';
                                }
                            } else {
                                $lineError++;
                                $log .= 'la ligne ' . $lineRead . ' contient des erreurs (COL A : ' . $xlsLabel . ', COL B: ' . $xlsNumber . ')<br>';
                            }
                        }
                        $em->flush();

                        $log .= $lineRead . ' lignes lues<br>';
                        $log .= $suppliersNew . ' nouveaux Fournisseurs<br>';
                        $log .= $lineUnprocessed . ' Fournisseurs déjà dans la base<br>';
                        $log .= $lineError . ' lignes contenant des erreurs<br>';

                        $this->flashMsgSession('log', $log);

                        $this->flashMsgSession('success', $this->translate('Supplier.import.success'));

                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Supplier.import.failure'));
                    }
                } elseif (isset($reqData['BankNewForm'])) {
                    $this->gvars['tabActive'] = 24;
                    $this->getSession()->set('tabActive', 24);
                    $this->gvars['stabActive'] = 1;
                    $this->getSession()->set('stabActive', 1);
                    $bankNewForm->handleRequest($request);
                    if ($bankNewForm->isValid()) {
                        $em->persist($bank);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Bank.add.success', array(
                            '%bank%' => $bank->getLabel()
                        )));

                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Bank.add.failure'));
                    }
                } elseif (isset($reqData['BankImportForm'])) {
                    $this->gvars['tabActive'] = 24;
                    $this->getSession()->set('tabActive', 24);
                    $this->gvars['stabActive'] = 2;
                    $this->getSession()->set('stabActive', 2);
                    $bankImportForm->handleRequest($request);
                    if ($bankImportForm->isValid()) {

                        ini_set('memory_limit', '4096M');
                        ini_set('max_execution_time', '0');
                        $extension = $bankImportForm['excel']->getData()->guessExtension();
                        if ($extension == 'zip') {
                            $extension = 'xlsx';
                        }

                        $filename = uniqid() . '.' . $extension;
                        $bankImportForm['excel']->getData()->move($this->getParameter('adapter_files'), $filename);
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

                        $banksConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
                            'name' => 'banksPrefix'
                        ));
                        if (null == $banksConstStr) {
                            $banksConstStr = new ConstantStr();
                            $banksConstStr->setName('banksPrefix');
                            $banksConstStr->setValue('532');
                            $em->persist($banksConstStr);
                            $em->flush();
                        }
                        $banksPrefix = $banksConstStr->getValue();
                        $banksPrefixNum = \intval($banksPrefix) * 1000000000;

                        $worksheet = $excelObj->getActiveSheet();
                        $highestRow = $worksheet->getHighestRow();
                        $lineRead = 1;
                        $banksNew = 0;
                        $lineUnprocessed = 0;
                        $lineError = 0;
                        for ($row = 2; $row <= $highestRow; $row++) {
                            $lineRead++;

                            $label = \trim(\strval($worksheet->getCellByColumnAndRow(0, $row)->getValue()));
                            $number = \trim(\strval($worksheet->getCellByColumnAndRow(1, $row)->getValue()));
                            $agence = \trim(\strval($worksheet->getCellByColumnAndRow(2, $row)->getValue()));
                            $rib = \trim(\strval($worksheet->getCellByColumnAndRow(3, $row)->getValue()));
                            $contact = \trim(\strval($worksheet->getCellByColumnAndRow(4, $row)->getValue()));
                            $tel = \trim(\strval($worksheet->getCellByColumnAndRow(5, $row)->getValue()));
                            $fax = \trim(\strval($worksheet->getCellByColumnAndRow(6, $row)->getValue()));
                            $email = \trim(\strval($worksheet->getCellByColumnAndRow(7, $row)->getValue()));

                            if ($number != '' && \is_numeric($number)) {
                                $number = \intval($number) - $banksPrefixNum;
                            }

                            if ($number != '' && $number > 0 && $label != '') {

                                $bank = $em->getRepository('AcfDataBundle:Bank')->findOneBy(array(
                                    'company' => $company,
                                    'number' => $number
                                ));
                                $bank1 = $em->getRepository('AcfDataBundle:Bank')->findOneBy(array(
                                    'company' => $company,
                                    'label' => $label
                                ));
                                if (null == $bank && null == $bank1) {
                                    $banksNew++;

                                    $bank = new Bank();
                                    $bank->setCompany($company);
                                    $bank->setLabel($label);
                                    $bank->setNumber($number);
                                    $bank->setAgency($agence);
                                    $bank->setRib($rib);
                                    $bank->setContact($contact);
                                    $bank->setTel($tel);
                                    $bank->setFax($fax);
                                    $bank->setEmail($email);

                                    $em->persist($bank);
                                } else {
                                    $lineUnprocessed++;
                                    $log .= 'la Banque à la ligne ' . $lineRead . ' existe déjà<br>';
                                }
                            } else {
                                $lineError++;
                                $log .= 'la ligne ' . $lineRead . ' contient des erreurs<br>';
                            }
                        }
                        $em->flush();

                        $log .= $lineRead . ' lignes lues<br>';
                        $log .= $banksNew . ' nouveaux Banques<br>';
                        $log .= $lineUnprocessed . ' Banques déjà dans la base<br>';
                        $log .= $lineError . ' lignes contenant des erreurs<br>';

                        $this->flashMsgSession('log', $log);

                        $this->flashMsgSession('success', $this->translate('Bank.import.success'));

                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Bank.import.failure'));
                    }
                } elseif (isset($reqData['FundNewForm'])) {
                    $this->gvars['tabActive'] = 25;
                    $this->getSession()->set('tabActive', 25);
                    $this->gvars['stabActive'] = 1;
                    $this->getSession()->set('stabActive', 1);
                    $fundNewForm->handleRequest($request);
                    if ($fundNewForm->isValid()) {
                        $em->persist($fund);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Fund.add.success', array(
                            '%fund%' => $fund->getLabel()
                        )));

                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Fund.add.failure'));
                    }
                } elseif (isset($reqData['FundImportForm'])) {
                    $this->gvars['tabActive'] = 25;
                    $this->getSession()->set('tabActive', 25);
                    $this->gvars['stabActive'] = 2;
                    $this->getSession()->set('stabActive', 2);
                    $fundImportForm->handleRequest($request);
                    if ($fundImportForm->isValid()) {

                        ini_set('memory_limit', '4096M');
                        ini_set('max_execution_time', '0');
                        $extension = $fundImportForm['excel']->getData()->guessExtension();
                        if ($extension == 'zip') {
                            $extension = 'xlsx';
                        }

                        $filename = uniqid() . '.' . $extension;
                        $fundImportForm['excel']->getData()->move($this->getParameter('adapter_files'), $filename);
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

                        $fundsConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
                            'name' => 'fundsPrefix'
                        ));
                        if (null == $fundsConstStr) {
                            $fundsConstStr = new ConstantStr();
                            $fundsConstStr->setName('fundsPrefix');
                            $fundsConstStr->setValue('540');
                            $em->persist($fundsConstStr);
                            $em->flush();
                        }
                        $fundsPrefix = $fundsConstStr->getValue();
                        $fundsPrefixNum = \intval($fundsPrefix) * 1000000000;

                        $worksheet = $excelObj->getActiveSheet();
                        $highestRow = $worksheet->getHighestRow();
                        $lineRead = 1;
                        $fundsNew = 0;
                        $lineUnprocessed = 0;
                        $lineError = 0;
                        for ($row = 2; $row <= $highestRow; $row++) {
                            $lineRead++;

                            $label = \trim(\strval($worksheet->getCellByColumnAndRow(0, $row)->getValue()));
                            $number = \trim(\strval($worksheet->getCellByColumnAndRow(1, $row)->getValue()));

                            if ($number != '' && \is_numeric($number)) {
                                $number = \intval($number) - $fundsPrefixNum;
                            }

                            if ($number != '' && $number > 0 && $label != '') {

                                $fund = $em->getRepository('AcfDataBundle:Fund')->findOneBy(array(
                                    'company' => $company,
                                    'number' => $number
                                ));
                                $fund1 = $em->getRepository('AcfDataBundle:Fund')->findOneBy(array(
                                    'company' => $company,
                                    'label' => $label
                                ));
                                if (null == $fund && null == $fund1) {
                                    $fundsNew++;

                                    $fund = new Fund();
                                    $fund->setCompany($company);
                                    $fund->setLabel($label);
                                    $fund->setNumber($number);

                                    $em->persist($fund);
                                } else {
                                    $lineUnprocessed++;
                                    $log .= 'la Caisse à la ligne ' . $lineRead . ' existe déjà<br>';
                                }
                            } else {
                                $lineError++;
                                $log .= 'la ligne ' . $lineRead . ' contient des erreurs<br>';
                            }
                        }
                        $em->flush();

                        $log .= $lineRead . ' lignes lues<br>';
                        $log .= $fundsNew . ' nouvelles Caisses<br>';
                        $log .= $lineUnprocessed . ' Caisses déjà dans la base<br>';
                        $log .= $lineError . ' lignes contenant des erreurs<br>';

                        $this->flashMsgSession('log', $log);

                        $this->flashMsgSession('success', $this->translate('Fund.import.success'));

                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Fund.import.failure'));
                    }
                } elseif (isset($reqData['WithholdingNewForm'])) {
                    $this->gvars['tabActive'] = 26;
                    $this->getSession()->set('tabActive', 26);
                    $this->gvars['stabActive'] = 1;
                    $this->getSession()->set('stabActive', 1);
                    $withholdingNewForm->handleRequest($request);
                    if ($withholdingNewForm->isValid()) {
                        $em->persist($withholding);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Withholding.add.success', array(
                            '%withholding%' => $withholding->getLabel()
                        )));

                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Withholding.add.failure'));
                    }
                } elseif (isset($reqData['WithholdingImportForm'])) {
                    $this->gvars['tabActive'] = 26;
                    $this->getSession()->set('tabActive', 26);
                    $this->gvars['stabActive'] = 2;
                    $this->getSession()->set('stabActive', 2);
                    $withholdingImportForm->handleRequest($request);
                    if ($withholdingImportForm->isValid()) {

                        ini_set('memory_limit', '4096M');
                        ini_set('max_execution_time', '0');
                        $extension = $withholdingImportForm['excel']->getData()->guessExtension();
                        if ($extension == 'zip') {
                            $extension = 'xlsx';
                        }

                        $filename = uniqid() . '.' . $extension;
                        $withholdingImportForm['excel']->getData()->move($this->getParameter('adapter_files'), $filename);
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

                        $withholdingsConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
                            'name' => 'withholdingsPrefix'
                        ));
                        if (null == $withholdingsConstStr) {
                            $withholdingsConstStr = new ConstantStr();
                            $withholdingsConstStr->setName('withholdingsPrefix');
                            $withholdingsConstStr->setValue('432');
                            $em->persist($withholdingsConstStr);
                            $em->flush();
                        }
                        $withholdingsPrefix = $withholdingsConstStr->getValue();
                        $withholdingsPrefixNum = \intval($withholdingsPrefix) * 1000000000;

                        $worksheet = $excelObj->getActiveSheet();
                        $highestRow = $worksheet->getHighestRow();
                        $lineRead = 1;
                        $withholdingsNew = 0;
                        $lineUnprocessed = 0;
                        $lineError = 0;
                        for ($row = 2; $row <= $highestRow; $row++) {
                            $lineRead++;

                            $label = \trim(\strval($worksheet->getCellByColumnAndRow(0, $row)->getValue()));
                            $number = \trim(\strval($worksheet->getCellByColumnAndRow(1, $row)->getValue()));
                            $xlsValue = \trim(\strval($worksheet->getCellByColumnAndRow(2, $row)->getValue()));

                            if ($number != '' && \is_numeric($number)) {
                                $number = \intval($number) - $withholdingsPrefixNum;
                            }

                            if ($number != '' && $number > 0 && $label != '') {

                                $withholding1 = $em->getRepository('AcfDataBundle:Withholding')->findOneBy(array(
                                    'company' => $company,
                                    'number' => $number
                                ));
                                $withholding2 = $em->getRepository('AcfDataBundle:Withholding')->findOneBy(array(
                                    'company' => $company,
                                    'label' => $label
                                ));
                                if (null == $withholding1 && null == $withholding2) {
                                    $withholdingsNew++;

                                    $withholding = new Withholding();
                                    $withholding->setCompany($company);
                                    $withholding->setNumber($number);
                                    $withholding->setLabel($label);

                                    if ($this->endswith($xlsValue, '%')) {
                                        $value = \intval(\substr($xlsValue, 0, -1));
                                    } else {
                                        $value = \intval($xlsValue);
                                    }

                                    $withholding->setValue($value);

                                    $em->persist($withholding);
                                } else {
                                    $lineUnprocessed++;
                                    $log .= 'la Retenue à la ligne ' . $lineRead . ' existe déjà<br>';
                                }
                            } else {
                                $lineError++;
                                $log .= 'la ligne ' . $lineRead . ' contient des erreurs<br>';
                            }
                        }
                        $em->flush();

                        $log .= $lineRead . ' lignes lues<br>';
                        $log .= $withholdingsNew . ' nouvelles Retenues<br>';
                        $log .= $lineUnprocessed . ' Retenues déjà dans la base<br>';
                        $log .= $lineError . ' lignes contenant des erreurs<br>';

                        $this->flashMsgSession('log', $log);

                        $this->flashMsgSession('success', $this->translate('Withholding.import.success'));

                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Withholding.import.failure'));
                    }
                } elseif (isset($reqData['MBSaleNewForm'])) {
                    $this->gvars['tabActive'] = 27;
                    $this->getSession()->set('tabActive', 27);
                    $mbsaleNewForm->handleRequest($request);
                    if ($mbsaleNewForm->isValid()) {
                        $mbsale->generateRef();
                        $em->persist($mbsale);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('MBSale.add.success', array(
                            '%mbsale%' => $mbsale->getRef()
                        )));

                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        return $this->redirect($urlFrom);
                    } else {
                        $this->gvars['stabActive'] = 1;
                        $this->getSession()->set('stabActive', 1);
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('MBSale.add.failure'));
                    }
                } elseif (isset($reqData['MBSaleNewYearForm'])) {
                    $this->gvars['tabActive'] = 27;
                    $this->getSession()->set('tabActive', 27);
                    $mbsaleNewYearForm->handleRequest($request);
                    if ($mbsaleNewYearForm->isValid()) {
                        $year = $mbsaleNewYearForm['year']->getData();
                        foreach (MBSale::choiceMonthCallback() as $month) {

                            $mbsale = $em->getRepository('AcfDataBundle:MBSale')->findOneBy(array(
                                'company' => $company,
                                'year' => $year,
                                'month' => $month
                            ));
                            if (null == $mbsale) {
                                $mbsale = new MBSale();
                                $mbsale->setCompany($company);
                                $mbsale->setYear($year);
                                $mbsale->setMonth($month);
                                $mbsale->generateRef();
                                $em->persist($mbsale);
                                $this->flashMsgSession('success', $this->translate('MBSale.add.success', array(
                                    '%mbsale%' => $mbsale->getRef()
                                )));
                            }
                        }

                        $em->flush();

                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        return $this->redirect($urlFrom);
                    } else {
                        $this->gvars['stabActive'] = 2;
                        $this->getSession()->set('stabActive', 2);
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('MBSale.add.failure'));
                    }
                } elseif (isset($reqData['MBPurchaseNewForm'])) {
                    $this->gvars['tabActive'] = 28;
                    $this->getSession()->set('tabActive', 28);
                    $mbpurchaseNewForm->handleRequest($request);
                    if ($mbpurchaseNewForm->isValid()) {
                        $mbpurchase->generateRef();
                        $em->persist($mbpurchase);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('MBPurchase.add.success', array(
                            '%mbpurchase%' => $mbpurchase->getRef()
                        )));

                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        return $this->redirect($urlFrom);
                    } else {
                        $this->gvars['stabActive'] = 2;
                        $this->getSession()->set('stabActive', 2);
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('MBPurchase.add.failure'));
                    }
                } elseif (isset($reqData['MBPurchaseNewYearForm'])) {
                    $this->gvars['tabActive'] = 28;
                    $this->getSession()->set('tabActive', 28);
                    $mbpurchaseNewYearForm->handleRequest($request);
                    if ($mbpurchaseNewYearForm->isValid()) {
                        $year = $mbpurchaseNewYearForm['year']->getData();
                        foreach (MBPurchase::choiceMonthCallback() as $month) {

                            $mbpurchase = $em->getRepository('AcfDataBundle:MBPurchase')->findOneBy(array(
                                'company' => $company,
                                'year' => $year,
                                'month' => $month
                            ));
                            if (null == $mbpurchase) {
                                $mbpurchase = new MBPurchase();
                                $mbpurchase->setCompany($company);
                                $mbpurchase->setYear($year);
                                $mbpurchase->setMonth($month);
                                $mbpurchase->generateRef();
                                $em->persist($mbpurchase);
                                $this->flashMsgSession('success', $this->translate('MBPurchase.add.success', array(
                                    '%mbpurchase%' => $mbpurchase->getRef()
                                )));
                            }
                        }

                        $em->flush();

                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        return $this->redirect($urlFrom);
                    } else {
                        $this->gvars['stabActive'] = 2;
                        $this->getSession()->set('stabActive', 2);
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('MBPurchase.add.failure'));
                    }
                } elseif (isset($reqData['DocgroupcomptableNewForm'])) {
                    $this->gvars['tabActive'] = 29;
                    $this->getSession()->set('tabActive', 29);
                    $docgroupcomptableNewForm->handleRequest($request);
                    if ($docgroupcomptableNewForm->isValid()) {

                        $dgtoclone = null;
                        $oldDocgroupcomptables = null;

                        if (null != $docgroupcomptableNewForm['clone']->getData()) {
                            $dgtoclone = $docgroupcomptableNewForm['clone']->getData();

                            $oldDocgroupcomptables = $em->getRepository('AcfDataBundle:Docgroupcomptable')->getAllChilds($dgtoclone);
                        }

                        $em->persist($docgroupcomptable);
                        $em->flush();

                        if (null != $dgtoclone && null != $oldDocgroupcomptables && \count($oldDocgroupcomptables) != 0) {

                            $docgroupcomptablesCloneArray = array();
                            foreach ($oldDocgroupcomptables as $oldDocgroupcomptable) {
                                $ndocgroupcomptable = new Docgroupcomptable();
                                $ndocgroupcomptable->setCompany($company);
                                $ndocgroupcomptable->setLabel($oldDocgroupcomptable->getLabel());
                                $ndocgroupcomptable->setOtherInfos($oldDocgroupcomptable->getOtherInfos());
                                if (null != $oldDocgroupcomptable->getParent() && $oldDocgroupcomptable->getParent()->getId() != $dgtoclone->getId()) {
                                    $parentId = $oldDocgroupcomptable->getParent()->getId();
                                    $ndocgroupcomptable->setParent($docgroupcomptablesCloneArray[$parentId]);
                                } else {
                                    $ndocgroupcomptable->setParent($docgroupcomptable);
                                }
                                $docgroupcomptablesCloneArray[$oldDocgroupcomptable->getId()] = $ndocgroupcomptable;
                                $em->persist($ndocgroupcomptable);
                            }
                        }
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('Docgroupcomptable.add.success', array(
                            '%docgroupcomptable%' => $docgroupcomptable->getLabel()
                        )));

                        $this->gvars['stabActive'] = 2;
                        $this->getSession()->set('stabActive', 2);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Docgroupcomptable.add.failure'));
                    }
                } elseif (isset($reqData['DocgroupbankNewForm'])) {
                    $this->gvars['tabActive'] = 30;
                    $this->getSession()->set('tabActive', 30);
                    $docgroupbankNewForm->handleRequest($request);
                    if ($docgroupbankNewForm->isValid()) {

                        $dgtoclone = null;
                        $oldDocgroupbanks = null;

                        if (null != $docgroupbankNewForm['clone']->getData()) {
                            $dgtoclone = $docgroupbankNewForm['clone']->getData();

                            $oldDocgroupbanks = $em->getRepository('AcfDataBundle:Docgroupbank')->getAllChilds($dgtoclone);
                        }

                        $em->persist($docgroupbank);
                        $em->flush();

                        if (null != $dgtoclone && null != $oldDocgroupbanks && \count($oldDocgroupbanks) != 0) {

                            $docgroupbanksCloneArray = array();
                            foreach ($oldDocgroupbanks as $oldDocgroupbank) {
                                $ndocgroupbank = new Docgroupbank();
                                $ndocgroupbank->setCompany($company);
                                $ndocgroupbank->setLabel($oldDocgroupbank->getLabel());
                                $ndocgroupbank->setOtherInfos($oldDocgroupbank->getOtherInfos());
                                if (null != $oldDocgroupbank->getParent() && $oldDocgroupbank->getParent()->getId() != $dgtoclone->getId()) {
                                    $parentId = $oldDocgroupbank->getParent()->getId();
                                    $ndocgroupbank->setParent($docgroupbanksCloneArray[$parentId]);
                                } else {
                                    $ndocgroupbank->setParent($docgroupbank);
                                }
                                $docgroupbanksCloneArray[$oldDocgroupbank->getId()] = $ndocgroupbank;
                                $em->persist($ndocgroupbank);
                            }
                        }
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Docgroupbank.add.success', array(
                            '%docgroupbank%' => $docgroupbank->getLabel()
                        )));

                        $this->gvars['stabActive'] = 2;
                        $this->getSession()->set('stabActive', 2);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Docgroupbank.add.failure'));
                    }
                } elseif (isset($reqData['DocgroupNewForm'])) {
                    $this->gvars['tabActive'] = 41;
                    $this->getSession()->set('tabActive', 41);
                    $docgroupNewForm->handleRequest($request);
                    if ($docgroupNewForm->isValid()) {

                        $dgtoclone = null;
                        $oldDocgroups = null;

                        if (null != $docgroupNewForm['clone']->getData()) {
                            $dgtoclone = $docgroupNewForm['clone']->getData();

                            $oldDocgroups = $em->getRepository('AcfDataBundle:Docgroup')->getAllChilds($dgtoclone);
                        }

                        $em->persist($docgroup);
                        $em->flush();

                        if (null != $dgtoclone && null != $oldDocgroups && \count($oldDocgroups) != 0) {

                            $docgroupsCloneArray = array();
                            foreach ($oldDocgroups as $oldDocgroup) {
                                $ndocgroup = new Docgroup();
                                $ndocgroup->setCompany($company);
                                $ndocgroup->setLabel($oldDocgroup->getLabel());
                                $ndocgroup->setOtherInfos($oldDocgroup->getOtherInfos());
                                if (null != $oldDocgroup->getParent() && $oldDocgroup->getParent()->getId() != $dgtoclone->getId()) {
                                    $parentId = $oldDocgroup->getParent()->getId();
                                    $ndocgroup->setParent($docgroupsCloneArray[$parentId]);
                                } else {
                                    $ndocgroup->setParent($docgroup);
                                }
                                $docgroupsCloneArray[$oldDocgroup->getId()] = $ndocgroup;
                                $em->persist($ndocgroup);
                            }
                        }
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Docgroup.add.success', array(
                            '%docgroup%' => $docgroup->getLabel()
                        )));

                        $this->gvars['stabActive'] = 2;
                        $this->getSession()->set('stabActive', 2);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Docgroup.add.failure'));
                    }
                } elseif (isset($reqData['ShareholderNewForm'])) {
                    $this->gvars['tabActive'] = 42;
                    $this->getSession()->set('tabActive', 42);
                    $shareholderNewForm->handleRequest($request);
                    if ($shareholderNewForm->isValid()) {
                        $em->persist($shareholder);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Shareholder.add.success', array(
                            '%shareholder%' => $shareholder->getName()
                        )));

                        $this->gvars['stabActive'] = 2;
                        $this->getSession()->set('stabActive', 2);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Shareholder.add.failure'));
                    }
                } elseif (isset($reqData['DocgroupfiscalNewForm'])) {
                    $this->gvars['tabActive'] = 51;
                    $this->getSession()->set('tabActive', 51);
                    $docgroupfiscalNewForm->handleRequest($request);
                    if ($docgroupfiscalNewForm->isValid()) {

                        $dgtoclone = null;
                        $oldDocgroupfiscals = null;

                        if (null != $docgroupfiscalNewForm['clone']->getData()) {
                            $dgtoclone = $docgroupfiscalNewForm['clone']->getData();

                            $oldDocgroupfiscals = $em->getRepository('AcfDataBundle:Docgroupfiscal')->getAllChilds($dgtoclone);
                        }

                        $em->persist($docgroupfiscal);
                        $em->flush();

                        if (null != $dgtoclone && null != $oldDocgroupfiscals && \count($oldDocgroupfiscals) != 0) {

                            $docgroupfiscalsCloneArray = array();
                            foreach ($oldDocgroupfiscals as $oldDocgroupfiscal) {
                                $ndocgroupfiscal = new Docgroupfiscal();
                                $ndocgroupfiscal->setCompany($company);
                                $ndocgroupfiscal->setLabel($oldDocgroupfiscal->getLabel());
                                $ndocgroupfiscal->setOtherInfos($oldDocgroupfiscal->getOtherInfos());
                                if (null != $oldDocgroupfiscal->getParent() && $oldDocgroupfiscal->getParent()->getId() != $dgtoclone->getId()) {
                                    $parentId = $oldDocgroupfiscal->getParent()->getId();
                                    $ndocgroupfiscal->setParent($docgroupfiscalsCloneArray[$parentId]);
                                } else {
                                    $ndocgroupfiscal->setParent($docgroupfiscal);
                                }
                                $docgroupfiscalsCloneArray[$oldDocgroupfiscal->getId()] = $ndocgroupfiscal;
                                $em->persist($ndocgroupfiscal);
                            }
                        }
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Docgroupfiscal.add.success', array(
                            '%docgroupfiscal%' => $docgroupfiscal->getLabel()
                        )));

                        $this->gvars['stabActive'] = 2;
                        $this->getSession()->set('stabActive', 2);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Docgroupfiscal.add.failure'));
                    }
                } elseif (isset($reqData['DocgrouppersoNewForm'])) {
                    $this->gvars['tabActive'] = 61;
                    $this->getSession()->set('tabActive', 61);
                    $docgrouppersoNewForm->handleRequest($request);
                    if ($docgrouppersoNewForm->isValid()) {

                        $dgtoclone = null;
                        $oldDocgrouppersos = null;

                        if (null != $docgrouppersoNewForm['clone']->getData()) {
                            $dgtoclone = $docgrouppersoNewForm['clone']->getData();

                            $oldDocgrouppersos = $em->getRepository('AcfDataBundle:Docgroupperso')->getAllChilds($dgtoclone);
                        }

                        $em->persist($docgroupperso);
                        $em->flush();

                        if (null != $dgtoclone && null != $oldDocgrouppersos && \count($oldDocgrouppersos) != 0) {

                            $docgrouppersosCloneArray = array();
                            foreach ($oldDocgrouppersos as $oldDocgroupperso) {
                                $ndocgroupperso = new Docgroupperso();
                                $ndocgroupperso->setCompany($company);
                                $ndocgroupperso->setLabel($oldDocgroupperso->getLabel());
                                $ndocgroupperso->setOtherInfos($oldDocgroupperso->getOtherInfos());
                                if (null != $oldDocgroupperso->getParent() && $oldDocgroupperso->getParent()->getId() != $dgtoclone->getId()) {
                                    $parentId = $oldDocgroupperso->getParent()->getId();
                                    $ndocgroupperso->setParent($docgrouppersosCloneArray[$parentId]);
                                } else {
                                    $ndocgroupperso->setParent($docgroupperso);
                                }
                                $docgrouppersosCloneArray[$oldDocgroupperso->getId()] = $ndocgroupperso;
                                $em->persist($ndocgroupperso);
                            }
                        }
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Docgroupperso.add.success', array(
                            '%docgroupperso%' => $docgroupperso->getLabel()
                        )));

                        $this->gvars['stabActive'] = 2;
                        $this->getSession()->set('stabActive', 2);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Docgroupperso.add.failure'));
                    }
                } elseif (isset($reqData['DocgroupsystNewForm'])) {
                    $this->gvars['tabActive'] = 71;
                    $this->getSession()->set('tabActive', 71);
                    $docgroupsystNewForm->handleRequest($request);
                    if ($docgroupsystNewForm->isValid()) {

                        $dgtoclone = null;
                        $oldDocgroupsysts = null;

                        if (null != $docgroupsystNewForm['clone']->getData()) {
                            $dgtoclone = $docgroupsystNewForm['clone']->getData();

                            $oldDocgroupsysts = $em->getRepository('AcfDataBundle:Docgroupsyst')->getAllChilds($dgtoclone);
                        }

                        $em->persist($docgroupsyst);
                        $em->flush();

                        if (null != $dgtoclone && null != $oldDocgroupsysts && \count($oldDocgroupsysts) != 0) {

                            $docgroupsystsCloneArray = array();
                            foreach ($oldDocgroupsysts as $oldDocgroupsyst) {
                                $ndocgroupsyst = new Docgroupsyst();
                                $ndocgroupsyst->setCompany($company);
                                $ndocgroupsyst->setLabel($oldDocgroupsyst->getLabel());
                                $ndocgroupsyst->setOtherInfos($oldDocgroupsyst->getOtherInfos());
                                if (null != $oldDocgroupsyst->getParent() && $oldDocgroupsyst->getParent()->getId() != $dgtoclone->getId()) {
                                    $parentId = $oldDocgroupsyst->getParent()->getId();
                                    $ndocgroupsyst->setParent($docgroupsystsCloneArray[$parentId]);
                                } else {
                                    $ndocgroupsyst->setParent($docgroupsyst);
                                }
                                $docgroupsystsCloneArray[$oldDocgroupsyst->getId()] = $ndocgroupsyst;
                                $em->persist($ndocgroupsyst);
                            }
                        }
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Docgroupsyst.add.success', array(
                            '%docgroupsyst%' => $docgroupsyst->getLabel()
                        )));

                        $this->gvars['stabActive'] = 2;
                        $this->getSession()->set('stabActive', 2);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Docgroupsyst.add.failure'));
                    }
                } elseif (isset($reqData['DocgroupauditNewForm'])) {
                    $this->gvars['tabActive'] = 81;
                    $this->getSession()->set('tabActive', 81);
                    $docgroupauditNewForm->handleRequest($request);
                    if ($docgroupauditNewForm->isValid()) {

                        $dgtoclone = null;
                        $oldDocgroupaudits = null;

                        if (null != $docgroupauditNewForm['clone']->getData()) {
                            $dgtoclone = $docgroupauditNewForm['clone']->getData();

                            $oldDocgroupaudits = $em->getRepository('AcfDataBundle:Docgroupaudit')->getAllChilds($dgtoclone);
                        }

                        $em->persist($docgroupaudit);
                        $em->flush();

                        if (null != $dgtoclone && null != $oldDocgroupaudits && \count($oldDocgroupaudits) != 0) {

                            $docgroupauditsCloneArray = array();
                            foreach ($oldDocgroupaudits as $oldDocgroupaudit) {
                                $ndocgroupaudit = new Docgroupaudit();
                                $ndocgroupaudit->setCompany($company);
                                $ndocgroupaudit->setLabel($oldDocgroupaudit->getLabel());
                                $ndocgroupaudit->setOtherInfos($oldDocgroupaudit->getOtherInfos());
                                if (null != $oldDocgroupaudit->getParent() && $oldDocgroupaudit->getParent()->getId() != $dgtoclone->getId()) {
                                    $parentId = $oldDocgroupaudit->getParent()->getId();
                                    $ndocgroupaudit->setParent($docgroupauditsCloneArray[$parentId]);
                                } else {
                                    $ndocgroupaudit->setParent($docgroupaudit);
                                }
                                $docgroupauditsCloneArray[$oldDocgroupaudit->getId()] = $ndocgroupaudit;
                                $em->persist($ndocgroupaudit);
                            }
                        }
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Docgroupaudit.add.success', array(
                            '%docgroupaudit%' => $docgroupaudit->getLabel()
                        )));

                        $this->gvars['stabActive'] = 2;
                        $this->getSession()->set('stabActive', 2);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('Docgroupaudit.add.failure'));
                    }
                } elseif (isset($reqData['MPayeNewForm'])) {
                    $this->gvars['tabActive'] = 101;
                    $this->getSession()->set('tabActive', 101);
                    $mpayeNewForm->handleRequest($request);
                    if ($mpayeNewForm->isValid()) {
                        $mpaye->generateRef();
                        $em->persist($mpaye);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('MPaye.add.success', array(
                            '%mpaye%' => $mpaye->getRef()
                        )));

                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        return $this->redirect($urlFrom);
                    } else {
                        $this->gvars['stabActive'] = 2;
                        $this->getSession()->set('stabActive', 2);
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('MPaye.add.failure'));
                    }
                } elseif (isset($reqData['MPayeNewYearForm'])) {
                    $this->gvars['tabActive'] = 101;
                    $this->getSession()->set('tabActive', 101);
                    $mpayeNewYearForm->handleRequest($request);
                    if ($mpayeNewYearForm->isValid()) {
                        $year = $mpayeNewYearForm['year']->getData();
                        foreach (MPaye::choiceMonthCallback() as $month) {

                            $mpaye = $em->getRepository('AcfDataBundle:MPaye')->findOneBy(array(
                                'company' => $company,
                                'year' => $year,
                                'month' => $month
                            ));
                            if (null == $mpaye) {
                                $mpaye = new MPaye();
                                $mpaye->setCompany($company);
                                $mpaye->setYear($year);
                                $mpaye->setMonth($month);
                                $mpaye->generateRef();
                                $em->persist($mpaye);
                                $this->flashMsgSession('success', $this->translate('MPaye.add.success', array(
                                    '%mpaye%' => $mpaye->getRef()
                                )));
                            }
                        }

                        $em->flush();

                        $this->gvars['stabActive'] = 3;
                        $this->getSession()->set('stabActive', 3);

                        return $this->redirect($urlFrom);
                    } else {
                        $this->gvars['stabActive'] = 2;
                        $this->getSession()->set('stabActive', 2);
                        $em->refresh($company);

                        $this->flashMsgSession('error', $this->translate('MPaye.add.failure'));
                    }
                } elseif (isset($reqData['LiasseFolderNewForm'])) {
                    $this->gvars['tabActive'] = 201;
                    $this->getSession()->set('tabActive', 201);
                    $liasseFolderNewForm->handleRequest($request);
                    if ($liasseFolderNewForm->isValid()) {
                        $em = $this->getEntityManager();
                        $em->persist($liasseFolder);
                        $em->flush();

                        $this->gvars['stabActive'] = 2;
                        $this->getSession()->set('stabActive', 2);

                        $this->flashMsgSession('success', $this->translate('LiasseFolder.add.success', array(
                            '%liasseFolder%' => $liasseFolder->getTitle()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $this->flashMsgSession('error', $this->translate('LiasseFolder.add.failure'));
                    }
                }

                $sector = new Sector();
                $sectorNewForm = $this->createForm(SectorNewTForm::class, $sector);
                $this->gvars['sector'] = $sector;
                $this->gvars['SectorNewForm'] = $sectorNewForm->createView();

                $this->gvars['company'] = $company;
                $this->gvars['stock'] = $stock;
                $this->gvars['address'] = $address;
                $this->gvars['phone'] = $phone;
                $this->gvars['companyFrame'] = $companyFrame;
                $this->gvars['companyNature'] = $companyNature;
                $this->gvars['doc'] = $doc;
                $this->gvars['pilot'] = $pilot;
                $this->gvars['companyUser'] = $companyUser;
                $this->gvars['companyAdmin'] = $companyAdmin;
                $this->gvars['companyLabel'] = $companyLabel;
                $this->gvars['customer'] = $customer;
                $this->gvars['supplier'] = $supplier;
                $this->gvars['bank'] = $bank;
                $this->gvars['fund'] = $fund;
                $this->gvars['withholding'] = $withholding;
                $this->gvars['docgroupcomptable'] = $docgroupcomptable;
                $this->gvars['shareholder'] = $withholding;
                $this->gvars['docgroup'] = $docgroup;
                $this->gvars['docgroupfiscal'] = $docgroupfiscal;
                $this->gvars['docgroupperso'] = $docgroupperso;
                $this->gvars['docgroupsyst'] = $docgroupsyst;
                $this->gvars['docgroupaudit'] = $docgroupaudit;
                $this->gvars['docgroupbank'] = $docgroupbank;
                $this->gvars['docgroupcomptables'] = $em->getRepository('AcfDataBundle:Docgroupcomptable')->getRoots($company);
                $this->gvars['docgroups'] = $em->getRepository('AcfDataBundle:Docgroup')->getRoots($company);
                $this->gvars['docgroupfiscals'] = $em->getRepository('AcfDataBundle:Docgroupfiscal')->getRoots($company);
                $this->gvars['docgrouppersos'] = $em->getRepository('AcfDataBundle:Docgroupperso')->getRoots($company);
                $this->gvars['docgroupsysts'] = $em->getRepository('AcfDataBundle:Docgroupsyst')->getRoots($company);
                $this->gvars['docgroupbanks'] = $em->getRepository('AcfDataBundle:Docgroupbank')->getRoots($company);
                $this->gvars['docgroupaudits'] = $em->getRepository('AcfDataBundle:Docgroupaudit')->getRoots($company);
                $this->gvars['liasseFolders'] = $em->getRepository('AcfDataBundle:LiasseFolder')->getRoots($company);

                $this->gvars['CompanyUpdateTypeForm'] = $companyUpdateTypeForm->createView();
                $this->gvars['CompanyUpdateCorporateNameForm'] = $companyUpdateCorporateNameForm->createView();
                $this->gvars['CompanyUpdateFiscForm'] = $companyUpdateFiscForm->createView();
                $this->gvars['CompanyUpdateTribunalForm'] = $companyUpdateTribunalForm->createView();
                $this->gvars['CompanyUpdatePhysicaltypeForm'] = $companyUpdatePhysicaltypeForm->createView();
                $this->gvars['CompanyUpdateCnssForm'] = $companyUpdateCnssForm->createView();
                $this->gvars['CompanyUpdateCnssBureauForm'] = $companyUpdateCnssBureauForm->createView();
                $this->gvars['CompanyUpdateSectorsForm'] = $companyUpdateSectorsForm->createView();
                $this->gvars['CompanyUpdatePhoneForm'] = $companyUpdatePhoneForm->createView();
                $this->gvars['CompanyUpdateMobileForm'] = $companyUpdateMobileForm->createView();
                $this->gvars['CompanyUpdateFaxForm'] = $companyUpdateFaxForm->createView();
                $this->gvars['CompanyUpdateEmailForm'] = $companyUpdateEmailForm->createView();
                $this->gvars['CompanyUpdateAdrForm'] = $companyUpdateAdrForm->createView();
                $this->gvars['CompanyUpdateOtherInfosForm'] = $companyUpdateOtherInfosForm->createView();
                $this->gvars['CompanyUpdateActionvnForm'] = $companyUpdateActionvnForm->createView();
                $this->gvars['CompanyUpdateRefForm'] = $companyUpdateRefForm->createView();
                $this->gvars['StockNewForm'] = $stockNewForm->createView();
                $this->gvars['AddressNewForm'] = $addressNewForm->createView();
                $this->gvars['PhoneNewForm'] = $phoneNewForm->createView();
                $this->gvars['CompanyFrameNewForm'] = $companyFrameNewForm->createView();
                $this->gvars['CompanyNatureNewForm'] = $companyNatureNewForm->createView();
                $this->gvars['CompanyNatureImportForm'] = $companyNatureImportForm->createView();
                $this->gvars['DocNewForm'] = $docNewForm->createView();
                $this->gvars['PilotNewForm'] = $pilotNewForm->createView();
                $this->gvars['CompanyUserNewForm'] = $companyUserNewForm->createView();
                $this->gvars['CompanyAdminNewForm'] = $companyAdminNewForm->createView();
                $this->gvars['CompanyLabelNewForm'] = $companyLabelNewForm->createView();
                $this->gvars['CompanyLabelImportForm'] = $companyLabelImportForm->createView();
                $this->gvars['CustomerNewForm'] = $customerNewForm->createView();
                $this->gvars['CustomerImportForm'] = $customerImportForm->createView();
                $this->gvars['SupplierNewForm'] = $supplierNewForm->createView();
                $this->gvars['SupplierImportForm'] = $supplierImportForm->createView();
                $this->gvars['BankNewForm'] = $bankNewForm->createView();
                $this->gvars['BankImportForm'] = $bankImportForm->createView();
                $this->gvars['FundNewForm'] = $fundNewForm->createView();
                $this->gvars['FundImportForm'] = $fundImportForm->createView();
                $this->gvars['WithholdingNewForm'] = $withholdingNewForm->createView();
                $this->gvars['WithholdingImportForm'] = $withholdingImportForm->createView();
                $this->gvars['MBSaleNewForm'] = $mbsaleNewForm->createView();
                $this->gvars['MBPurchaseNewForm'] = $mbpurchaseNewForm->createView();
                $this->gvars['MBSaleNewYearForm'] = $mbsaleNewYearForm->createView();
                $this->gvars['MBPurchaseNewYearForm'] = $mbpurchaseNewYearForm->createView();
                $this->gvars['MPayeNewForm'] = $mpayeNewForm->createView();
                $this->gvars['MPayeNewYearForm'] = $mpayeNewYearForm->createView();
                $this->gvars['DocgroupcomptableNewForm'] = $docgroupcomptableNewForm->createView();
                $this->gvars['DocgroupNewForm'] = $docgroupNewForm->createView();
                $this->gvars['ShareholderNewForm'] = $shareholderNewForm->createView();
                $this->gvars['DocgroupfiscalNewForm'] = $docgroupfiscalNewForm->createView();
                $this->gvars['DocgrouppersoNewForm'] = $docgrouppersoNewForm->createView();
                $this->gvars['DocgroupsystNewForm'] = $docgroupsystNewForm->createView();
                $this->gvars['DocgroupauditNewForm'] = $docgroupauditNewForm->createView();
                $this->gvars['DocgroupbankNewForm'] = $docgroupbankNewForm->createView();
                $this->gvars['liasseFolder'] = $liasseFolder;
                $this->gvars['LiasseFolderNewForm'] = $liasseFolderNewForm->createView();

                $mbsaleYears = $em->getRepository('AcfDataBundle:MBSale')->getAllYearByCompany($company);
                $mbpurchaseYears = $em->getRepository('AcfDataBundle:MBPurchase')->getAllYearByCompany($company);
                $mpayeYears = $em->getRepository('AcfDataBundle:MPaye')->getAllYearByCompany($company);

                $this->gvars['mbsaleYears'] = $mbsaleYears;
                $this->gvars['mbpurchaseYears'] = $mbpurchaseYears;
                $this->gvars['mpayeYears'] = $mpayeYears;

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

                $banksConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
                    'name' => 'banksPrefix'
                ));
                if (null == $banksConstStr) {
                    $banksConstStr = new ConstantStr();
                    $banksConstStr->setName('banksPrefix');
                    $banksConstStr->setValue('532');
                    $em->persist($banksConstStr);
                    $em->flush();
                }
                $banksPrefix = $banksConstStr->getValue();
                $this->gvars['banksPrefix'] = $banksPrefix;

                $fundsConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
                    'name' => 'fundsPrefix'
                ));
                if (null == $fundsConstStr) {
                    $fundsConstStr = new ConstantStr();
                    $fundsConstStr->setName('fundsPrefix');
                    $fundsConstStr->setValue('540');
                    $em->persist($fundsConstStr);
                    $em->flush();
                }
                $fundsPrefix = $fundsConstStr->getValue();
                $this->gvars['fundsPrefix'] = $fundsPrefix;

                $withholdingsConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
                    'name' => 'withholdingsPrefix'
                ));
                if (null == $withholdingsConstStr) {
                    $withholdingsConstStr = new ConstantStr();
                    $withholdingsConstStr->setName('withholdingsPrefix');
                    $withholdingsConstStr->setValue('432');
                    $em->persist($withholdingsConstStr);
                    $em->flush();
                }
                $withholdingsPrefix = $withholdingsConstStr->getValue();
                $this->gvars['withholdingsPrefix'] = $withholdingsPrefix;

                $this->gvars['pagetitle'] = $this->translate('pagetitle.company.edit', array(
                    '%company%' => $company->getCorporateName()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.company.edit.txt', array(
                    '%company%' => $company->getCorporateName()
                ));

                return $this->renderResponse('AcfAdminBundle:Company:edit.html.twig', $this->gvars);
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function shareholderExcelAction($uid)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_company_list');
        }

        $em = $this->getEntityManager();
        try {
            $company = $em->getRepository('AcfDataBundle:Company')->find($uid);
            $shareholders = $company->getShareholders();

            $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

            $phpExcelObject->getProperties()
                ->setCreator('Salah Abdelkader Seif Eddine')
                ->setLastModifiedBy($this->getSecurityTokenStorage()
                ->getToken()
                ->getUser()
                ->getFullname())
                ->setTitle($this->translate('pagetitle.shareholder.list'))
                ->setSubject($this->translate('pagetitle.shareholder.list'))
                ->setDescription($this->translate('pagetitle.shareholder.list'))
                ->setKeywords($this->translate('pagetitle.shareholder.list'))
                ->setCategory('ACEF companyLabel');

            $phpExcelObject->setActiveSheetIndex(0);

            $workSheet = $phpExcelObject->getActiveSheet();
            $workSheet->setTitle($this->translate('pagetitle.shareholder.list'));

            $workSheet->setCellValue('A1', $this->translate('Company.capital.label'));
            $workSheet->getStyle('A1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('B1', $company->getCapital());
            $workSheet->getStyle('B1')
                ->getNumberFormat()
                ->setFormatCode('#,##0.000');

            $workSheet->setCellValue('A2', $this->translate('Company.actionvn.label'));
            $workSheet->getStyle('A2')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('B2', $company->getActionvn());
            $workSheet->getStyle('B2')
                ->getNumberFormat()
                ->setFormatCode('#,##0.000');

            $workSheet->setCellValue('A3', $this->translate('Company.actioncount.label'));
            $workSheet->getStyle('A3')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('B3', $company->getActioncount());
            $workSheet->getStyle('B3')
                ->getNumberFormat()
                ->setFormatCode('#,##0');

            $workSheet->setCellValue('A5', $this->translate('Shareholder.name.label'));
            $workSheet->getStyle('A5')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('B5', $this->translate('Shareholder.cin.label'));
            $workSheet->getStyle('B5')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('C5', $this->translate('Shareholder.quality.label'));
            $workSheet->getStyle('C5')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('D5', $this->translate('Shareholder.address.label'));
            $workSheet->getStyle('D5')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('E5', $this->translate('Shareholder.trades.label'));
            $workSheet->getStyle('E5')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('F5', $this->translate('Shareholder.tradesv.label'));
            $workSheet->getStyle('F5')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('G5', $this->translate('Shareholder.tradesp.label'));
            $workSheet->getStyle('G5')
                ->getFont()
                ->setBold(true);

            $workSheet->getStyle('A5:G5')->applyFromArray(array(
                'fill' => array(
                    'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array(
                        'rgb' => '94ccdf'
                    )
                )
            ));

            $i = 5;

            foreach ($shareholders as $shareholder) {
                $i++;
                $workSheet->setCellValue('A' . $i, $shareholder->getName(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('B' . $i, $shareholder->getCin(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('C' . $i, $shareholder->getQuality(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('D' . $i, $shareholder->getAddress(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('E' . $i, $shareholder->getTrades());
                $workSheet->getStyle('E' . $i)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');
                $workSheet->setCellValue('F' . $i, $shareholder->getTradesv());
                $workSheet->getStyle('F' . $i)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.000');
                $workSheet->setCellValue('G' . $i, $shareholder->getTradesp() / 100);
                $workSheet->getStyle('G' . $i)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.00%');

                if ($i % 2 == 1) {
                    $workSheet->getStyle('A' . $i . ':G' . $i)->applyFromArray(array(
                        'fill' => array(
                            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array(
                                'rgb' => 'd8f1f5'
                            )
                        )
                    ));
                } else {
                    $workSheet->getStyle('A' . $i . ':G' . $i)->applyFromArray(array(
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

            $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
            $response = $this->get('phpexcel')->createStreamedResponse($writer);

            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8');

            $filename = $this->normalizeString($this->normalize($this->translate('pagetitle.shareholder.listByCompany', array(
                '%company%' => $company->getCorporateName()
            ))));
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
     * @param string $uid
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function companyLabelExcelAction($uid)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_company_list');
        }

        $em = $this->getEntityManager();
        try {
            $company = $em->getRepository('AcfDataBundle:Company')->find($uid);
            $companyLabels = $company->getCompanyLabels();

            $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

            $phpExcelObject->getProperties()
                ->setCreator('Salah Abdelkader Seif Eddine')
                ->setLastModifiedBy($this->getSecurityTokenStorage()
                ->getToken()
                ->getUser()
                ->getFullname())
                ->setTitle($this->translate('pagetitle.companyLabel.list'))
                ->setSubject($this->translate('pagetitle.companyLabel.list'))
                ->setDescription($this->translate('pagetitle.companyLabel.list'))
                ->setKeywords($this->translate('pagetitle.companyLabel.list'))
                ->setCategory('ACEF companyLabel');

            $phpExcelObject->setActiveSheetIndex(0);

            $workSheet = $phpExcelObject->getActiveSheet();
            $workSheet->setTitle($this->translate('pagetitle.companyLabel.list'));

            $workSheet->setCellValue('A1', $this->translate('CompanyLabel.name.label'));
            $workSheet->getStyle('A1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('B1', $this->translate('CompanyLabel.abrev.label'));
            $workSheet->getStyle('B1')
                ->getFont()
                ->setBold(true);

            $workSheet->getStyle('A1:B1')->applyFromArray(array(
                'fill' => array(
                    'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array(
                        'rgb' => '94ccdf'
                    )
                )
            ));

            $i = 1;

            foreach ($companyLabels as $companyLabel) {
                $i++;
                $workSheet->setCellValue('A' . $i, $companyLabel->getName(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('B' . $i, $companyLabel->getAbrev(), \PHPExcel_Cell_DataType::TYPE_STRING2);

                if ($i % 2 == 1) {
                    $workSheet->getStyle('A' . $i . ':B' . $i)->applyFromArray(array(
                        'fill' => array(
                            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array(
                                'rgb' => 'd8f1f5'
                            )
                        )
                    ));
                } else {
                    $workSheet->getStyle('A' . $i . ':B' . $i)->applyFromArray(array(
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

            $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
            $response = $this->get('phpexcel')->createStreamedResponse($writer);

            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8');

            $filename = $this->normalizeString($this->normalize($this->translate('pagetitle.companyLabel.listByCompany', array(
                '%company%' => $company->getCorporateName()
            ))));
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
     * @param string $uid
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function companyNatureExcelAction($uid)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_company_list');
        }

        $em = $this->getEntityManager();
        try {
            $company = $em->getRepository('AcfDataBundle:Company')->find($uid);
            $companyNatures = $company->getCompanyNatures();

            $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

            $phpExcelObject->getProperties()
                ->setCreator('Salah Abdelkader Seif Eddine')
                ->setLastModifiedBy($this->getSecurityTokenStorage()
                ->getToken()
                ->getUser()
                ->getFullname())
                ->setTitle($this->translate('pagetitle.companyNature.list'))
                ->setSubject($this->translate('pagetitle.companyNature.list'))
                ->setDescription($this->translate('pagetitle.companyNature.list'))
                ->setKeywords($this->translate('pagetitle.companyNature.list'))
                ->setCategory('ACEF companyNature');

            $phpExcelObject->setActiveSheetIndex(0);

            $workSheet = $phpExcelObject->getActiveSheet();
            $workSheet->setTitle($this->translate('pagetitle.companyNature.list'));

            $workSheet->setCellValue('A1', $this->translate('CompanyNature.label.label'));
            $workSheet->getStyle('A1')
                ->getFont()
                ->setBold(true);

            $workSheet->setCellValue('B1', $this->translate('CompanyNature.color.label'));
            $workSheet->getStyle('B1')
                ->getFont()
                ->setBold(true);

            $workSheet->getStyle('A1:B1')->applyFromArray(array(
                'fill' => array(
                    'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array(
                        'rgb' => '94ccdf'
                    )
                )
            ));

            $i = 1;

            foreach ($companyNatures as $companyNature) {
                $i++;
                $workSheet->setCellValue('A' . $i, $companyNature->getLabel(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('B' . $i, $companyNature->getColor(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                if ($i % 2 == 1) {
                    $workSheet->getStyle('A' . $i . ':B' . $i)->applyFromArray(array(
                        'fill' => array(
                            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array(
                                'rgb' => 'd8f1f5'
                            )
                        )
                    ));
                } else {
                    $workSheet->getStyle('A' . $i . ':B' . $i)->applyFromArray(array(
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

            $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
            $response = $this->get('phpexcel')->createStreamedResponse($writer);

            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8');

            $filename = $this->normalizeString($this->normalize($this->translate('pagetitle.companyNature.listByCompany', array(
                '%company%' => $company->getCorporateName()
            ))));
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
     * @param string $uid
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function pilotExcelAction($uid)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_company_list');
        }

        $em = $this->getEntityManager();
        try {
            $company = $em->getRepository('AcfDataBundle:Company')->find($uid);
            $pilots = $company->getPilots();

            $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

            $phpExcelObject->getProperties()
                ->setCreator('Salah Abdelkader Seif Eddine')
                ->setLastModifiedBy($this->getSecurityTokenStorage()
                ->getToken()
                ->getUser()
                ->getFullname())
                ->setTitle($this->translate('pagetitle.pilot.list'))
                ->setSubject($this->translate('pagetitle.pilot.list'))
                ->setDescription($this->translate('pagetitle.pilot.list'))
                ->setKeywords($this->translate('pagetitle.pilot.list'))
                ->setCategory('ACEF pilot');

            $phpExcelObject->setActiveSheetIndex(0);

            $workSheet = $phpExcelObject->getActiveSheet();
            $workSheet->setTitle($this->translate('pagetitle.pilot.list'));

            $workSheet->setCellValue('A1', $this->translate('Pilot.ref.label'));
            $workSheet->getStyle('A1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('B1', $this->translate('Pilot.mission.label'));
            $workSheet->getStyle('B1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('C1', $this->translate('Pilot.natureMission.label'));
            $workSheet->getStyle('C1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('D1', $this->translate('Pilot.prestataire.label'));
            $workSheet->getStyle('D1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('E1', $this->translate('Pilot.recetteFinance.label'));
            $workSheet->getStyle('E1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('F1', $this->translate('Pilot.pinAnce.label'));
            $workSheet->getStyle('F1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('G1', $this->translate('Pilot.expirationAnce.label'));
            $workSheet->getStyle('G1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('H1', $this->translate('Pilot.mpImpots.label'));
            $workSheet->getStyle('H1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('I1', $this->translate('Pilot.idCnss.label'));
            $workSheet->getStyle('I1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('J1', $this->translate('Pilot.mpCnss.label'));
            $workSheet->getStyle('J1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('K1', $this->translate('Pilot.nomCac.label'));
            $workSheet->getStyle('K1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('L1', $this->translate('Pilot.dureeMandat.label'));
            $workSheet->getStyle('L1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('M1', $this->translate('Pilot.numMandat.label'));
            $workSheet->getStyle('M1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('N1', $this->translate('Pilot.rapportCac.label'));
            $workSheet->getStyle('N1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('O1', $this->translate('Pilot.declEmpl.label'));
            $workSheet->getStyle('O1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('P1', $this->translate('Pilot.isDur.label'));
            $workSheet->getStyle('P1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('Q1', $this->translate('Pilot.pvCa.label'));
            $workSheet->getStyle('Q1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('R1', $this->translate('Pilot.rapportGerance.label'));
            $workSheet->getStyle('R1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('S1', $this->translate('Pilot.pvAgo.label'));
            $workSheet->getStyle('S1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('T1', $this->translate('Pilot.pvAge.label'));
            $workSheet->getStyle('T1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('U1', $this->translate('Pilot.livresCotes.label'));
            $workSheet->getStyle('U1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('V1', $this->translate('Pilot.honTeorAnn.label'));
            $workSheet->getStyle('V1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('W1', $this->translate('Pilot.modeFact.label'));
            $workSheet->getStyle('W1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('X1', $this->translate('Pilot.nonFactMont.label'));
            $workSheet->getStyle('X1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('Y1', $this->translate('Pilot.nonFactDesc.label'));
            $workSheet->getStyle('Y1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('Z1', $this->translate('Pilot.nonEncMont.label'));
            $workSheet->getStyle('Z1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('AA1', $this->translate('Pilot.nonEncDesc.label'));
            $workSheet->getStyle('AA1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('AB1', $this->translate('Pilot.commentQuit.label'));
            $workSheet->getStyle('AB1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('AC1', $this->translate('Pilot.mqQuitImpots.label'));
            $workSheet->getStyle('AC1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('AD1', $this->translate('Pilot.mqQuitCnss.label'));
            $workSheet->getStyle('AD1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('AE1', $this->translate('Pilot.comments.label'));
            $workSheet->getStyle('AE1')
                ->getFont()
                ->setBold(true);

            $workSheet->getStyle('A1:AE1')->applyFromArray(array(
                'fill' => array(
                    'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array(
                        'rgb' => '94ccdf'
                    )
                )
            ));

            $i = 1;

            foreach ($pilots as $pilot) {
                $i++;
                $workSheet->setCellValue('A' . $i, $pilot->getRef(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('B' . $i, $pilot->getMission(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('C' . $i, $pilot->getNatureMission(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('D' . $i, $pilot->getPrestataire(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('E' . $i, $pilot->getRecetteFinance(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('F' . $i, $pilot->getPinAnce(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('G' . $i, $pilot->getExpirationAnce(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('H' . $i, $pilot->getMpImpots(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('I' . $i, $pilot->getIdCnss(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('J' . $i, $pilot->getMpCnss(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('K' . $i, $pilot->getNomCac(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('L' . $i, $pilot->getDureeMandat(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('M' . $i, $pilot->getNumMandat(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('N' . $i, $pilot->getRapportCac(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('O' . $i, $pilot->getDeclEmpl(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('P' . $i, $pilot->getIsDur(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('Q' . $i, $pilot->getPvCa(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('R' . $i, $pilot->getRapportGerance(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('S' . $i, $pilot->getPvAgo(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('T' . $i, $pilot->getPvAge(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('U' . $i, $pilot->getLivresCotes(), \PHPExcel_Cell_DataType::TYPE_STRING2);

                $workSheet->setCellValue('V' . $i, $pilot->getHonTeorAnn());
                $workSheet->getStyle('V' . $i)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.000');

                $workSheet->setCellValue('W' . $i, $pilot->getModeFact(), \PHPExcel_Cell_DataType::TYPE_STRING2);

                $workSheet->setCellValue('X' . $i, $pilot->getNonFactMont());
                $workSheet->getStyle('G' . $i)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.000');

                $workSheet->setCellValue('Y' . $i, $pilot->getNonFactDesc(), \PHPExcel_Cell_DataType::TYPE_STRING2);

                $workSheet->setCellValue('Z' . $i, $pilot->getNonEncMont());
                $workSheet->getStyle('G' . $i)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.000');

                $workSheet->setCellValue('AA' . $i, $pilot->getNonEncDesc(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('AB' . $i, $pilot->getCommentQuit(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('AC' . $i, $pilot->getMqQuitImpots(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('AD' . $i, $pilot->getMqQuitCnss(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('AE' . $i, $pilot->getComments(), \PHPExcel_Cell_DataType::TYPE_STRING2);

                if ($i % 2 == 1) {
                    $workSheet->getStyle('A' . $i . ':AE' . $i)->applyFromArray(array(
                        'fill' => array(
                            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array(
                                'rgb' => 'd8f1f5'
                            )
                        )
                    ));
                } else {
                    $workSheet->getStyle('A' . $i . ':AE' . $i)->applyFromArray(array(
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

            $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
            $response = $this->get('phpexcel')->createStreamedResponse($writer);

            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8');

            $filename = $this->normalizeString($this->normalize($this->translate('pagetitle.pilot.listByCompany', array(
                '%company%' => $company->getCorporateName()
            ))));
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
     * @param string $uid
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function customerExcelAction($uid)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_company_list');
        }

        $em = $this->getEntityManager();
        try {
            $company = $em->getRepository('AcfDataBundle:Company')->find($uid);
            $customers = $company->getCustomers();

            $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

            $phpExcelObject->getProperties()
                ->setCreator('Salah Abdelkader Seif Eddine')
                ->setLastModifiedBy($this->getSecurityTokenStorage()
                ->getToken()
                ->getUser()
                ->getFullname())
                ->setTitle($this->translate('pagetitle.customer.list'))
                ->setSubject($this->translate('pagetitle.customer.list'))
                ->setDescription($this->translate('pagetitle.customer.list'))
                ->setKeywords($this->translate('pagetitle.customer.list'))
                ->setCategory('ACEF customer');

            $phpExcelObject->setActiveSheetIndex(0);

            $workSheet = $phpExcelObject->getActiveSheet();
            $workSheet->setTitle($this->translate('pagetitle.customer.list'));

            $workSheet->setCellValue('A1', $this->translate('Customer.label.label'));
            $workSheet->getStyle('A1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('B1', $this->translate('Customer.number.label'));
            $workSheet->getStyle('B1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('C1', $this->translate('Customer.fisc.label'));
            $workSheet->getStyle('C1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('D1', $this->translate('Customer.sectors.label'));
            $workSheet->getStyle('D1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('E1', $this->translate('Customer.address.label'));
            $workSheet->getStyle('E1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('F1', $this->translate('Customer.commercialRegister.label'));
            $workSheet->getStyle('F1')
                ->getFont()
                ->setBold(true);

            $workSheet->getStyle('A1:F1')->applyFromArray(array(
                'fill' => array(
                    'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array(
                        'rgb' => '94ccdf'
                    )
                )
            ));

            $i = 1;

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

            foreach ($customers as $customer) {
                $i++;
                $workSheet->setCellValue('A' . $i, $customer->getLabel(), \PHPExcel_Cell_DataType::TYPE_STRING2);

                $numb = $customersPrefix . $customer->getNumberFormated();

                $workSheet->setCellValueExplicit('B' . $i, $numb, \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('C' . $i, $customer->getFisc(), \PHPExcel_Cell_DataType::TYPE_STRING2);

                $sectors = '';
                foreach ($customer->getSectors() as $sector) {
                    $sectors .= ' ' . $sector->getLabel();
                }
                $workSheet->setCellValue('D' . $i, $sectors, \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('E' . $i, $customer->getFullAddress(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('F' . $i, $customer->getCommercialRegister(), \PHPExcel_Cell_DataType::TYPE_STRING2);

                if ($i % 2 == 1) {
                    $workSheet->getStyle('A' . $i . ':F' . $i)->applyFromArray(array(
                        'fill' => array(
                            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array(
                                'rgb' => 'd8f1f5'
                            )
                        )
                    ));
                } else {
                    $workSheet->getStyle('A' . $i . ':F' . $i)->applyFromArray(array(
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

            $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
            $response = $this->get('phpexcel')->createStreamedResponse($writer);

            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8');

            $filename = $this->normalizeString($this->normalize($this->translate('pagetitle.customer.listByCompany', array(
                '%company%' => $company->getCorporateName()
            ))));
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
     * @param string $uid
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function supplierExcelAction($uid)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_company_list');
        }

        $em = $this->getEntityManager();
        try {
            $company = $em->getRepository('AcfDataBundle:Company')->find($uid);
            $suppliers = $company->getSuppliers();

            $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

            $phpExcelObject->getProperties()
                ->setCreator('Salah Abdelkader Seif Eddine')
                ->setLastModifiedBy($this->getSecurityTokenStorage()
                ->getToken()
                ->getUser()
                ->getFullname())
                ->setTitle($this->translate('pagetitle.supplier.list'))
                ->setSubject($this->translate('pagetitle.supplier.list'))
                ->setDescription($this->translate('pagetitle.supplier.list'))
                ->setKeywords($this->translate('pagetitle.supplier.list'))
                ->setCategory('ACEF supplier');

            $phpExcelObject->setActiveSheetIndex(0);

            $workSheet = $phpExcelObject->getActiveSheet();
            $workSheet->setTitle($this->translate('pagetitle.supplier.list'));

            $workSheet->setCellValue('A1', $this->translate('Supplier.label.label'));
            $workSheet->getStyle('A1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('B1', $this->translate('Supplier.number.label'));
            $workSheet->getStyle('B1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('C1', $this->translate('Supplier.fisc.label'));
            $workSheet->getStyle('C1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('D1', $this->translate('Supplier.address.label'));
            $workSheet->getStyle('D1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('E1', $this->translate('Supplier.commercialRegister.label'));
            $workSheet->getStyle('E1')
                ->getFont()
                ->setBold(true);

            $workSheet->getStyle('A1:E1')->applyFromArray(array(
                'fill' => array(
                    'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array(
                        'rgb' => '94ccdf'
                    )
                )
            ));

            $i = 1;

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

            foreach ($suppliers as $supplier) {
                $i++;
                $workSheet->setCellValue('A' . $i, $supplier->getLabel(), \PHPExcel_Cell_DataType::TYPE_STRING2);

                $numb = $suppliersPrefix . $supplier->getNumberFormated();

                $workSheet->setCellValueExplicit('B' . $i, $numb, \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('C' . $i, $supplier->getFisc(), \PHPExcel_Cell_DataType::TYPE_STRING2);

                $workSheet->setCellValue('D' . $i, $supplier->getFullAddress(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('E' . $i, $supplier->getCommercialRegister(), \PHPExcel_Cell_DataType::TYPE_STRING2);

                if ($i % 2 == 1) {
                    $workSheet->getStyle('A' . $i . ':E' . $i)->applyFromArray(array(
                        'fill' => array(
                            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array(
                                'rgb' => 'd8f1f5'
                            )
                        )
                    ));
                } else {
                    $workSheet->getStyle('A' . $i . ':E' . $i)->applyFromArray(array(
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

            $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
            $response = $this->get('phpexcel')->createStreamedResponse($writer);

            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8');

            $filename = $this->normalizeString($this->normalize($this->translate('pagetitle.supplier.listByCompany', array(
                '%company%' => $company->getCorporateName()
            ))));
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
     * @param string $uid
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function bankExcelAction($uid)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_company_list');
        }

        $em = $this->getEntityManager();
        try {
            $company = $em->getRepository('AcfDataBundle:Company')->find($uid);
            $banks = $company->getBanks();

            $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

            $phpExcelObject->getProperties()
                ->setCreator('Salah Abdelkader Seif Eddine')
                ->setLastModifiedBy($this->getSecurityTokenStorage()
                ->getToken()
                ->getUser()
                ->getFullname())
                ->setTitle($this->translate('pagetitle.bank.list'))
                ->setSubject($this->translate('pagetitle.bank.list'))
                ->setDescription($this->translate('pagetitle.bank.list'))
                ->setKeywords($this->translate('pagetitle.bank.list'))
                ->setCategory('ACEF bank');

            $phpExcelObject->setActiveSheetIndex(0);

            $workSheet = $phpExcelObject->getActiveSheet();
            $workSheet->setTitle($this->translate('pagetitle.bank.list'));

            $workSheet->setCellValue('A1', $this->translate('Bank.label.label'));
            $workSheet->getStyle('A1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('B1', $this->translate('Bank.number.label'));
            $workSheet->getStyle('B1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('C1', $this->translate('Bank.agency.label'));
            $workSheet->getStyle('C1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('D1', $this->translate('Bank.rib.label'));
            $workSheet->getStyle('D1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('E1', $this->translate('Bank.contact.label'));
            $workSheet->getStyle('E1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('F1', $this->translate('Bank.tel.label'));
            $workSheet->getStyle('F1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('G1', $this->translate('Bank.fax.label'));
            $workSheet->getStyle('G1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('H1', $this->translate('Bank.email.label'));
            $workSheet->getStyle('H1')
                ->getFont()
                ->setBold(true);

            $workSheet->getStyle('A1:H1')->applyFromArray(array(
                'fill' => array(
                    'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array(
                        'rgb' => '94ccdf'
                    )
                )
            ));

            $banksConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
                'name' => 'banksPrefix'
            ));
            if (null == $banksConstStr) {
                $banksConstStr = new ConstantStr();
                $banksConstStr->setName('banksPrefix');
                $banksConstStr->setValue('532');
                $em->persist($banksConstStr);
                $em->flush();
            }
            $banksPrefix = $banksConstStr->getValue();
            $this->gvars['banksPrefix'] = $banksPrefix;

            $i = 1;

            foreach ($banks as $bank) {
                $i++;
                $workSheet->setCellValue('A' . $i, $bank->getLabel(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $numb = $banksPrefix . $bank->getNumberFormated();

                $workSheet->setCellValueExplicit('B' . $i, $numb, \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('C' . $i, $bank->getAgency(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('D' . $i, $bank->getRib(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('E' . $i, $bank->getContact(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('F' . $i, $bank->getTel(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('G' . $i, $bank->getFax(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('H' . $i, $bank->getEmail(), \PHPExcel_Cell_DataType::TYPE_STRING2);

                if ($i % 2 == 1) {
                    $workSheet->getStyle('A' . $i . ':H' . $i)->applyFromArray(array(
                        'fill' => array(
                            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array(
                                'rgb' => 'd8f1f5'
                            )
                        )
                    ));
                } else {
                    $workSheet->getStyle('A' . $i . ':H' . $i)->applyFromArray(array(
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

            $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
            $response = $this->get('phpexcel')->createStreamedResponse($writer);

            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8');

            $filename = $this->normalizeString($this->normalize($this->translate('pagetitle.bank.listByCompany', array(
                '%company%' => $company->getCorporateName()
            ))));
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
     * @param string $uid
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function fundExcelAction($uid)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_company_list');
        }

        $em = $this->getEntityManager();
        try {
            $company = $em->getRepository('AcfDataBundle:Company')->find($uid);
            $funds = $company->getFunds();

            $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

            $phpExcelObject->getProperties()
                ->setCreator('Salah Abdelkader Seif Eddine')
                ->setLastModifiedBy($this->getSecurityTokenStorage()
                ->getToken()
                ->getUser()
                ->getFullname())
                ->setTitle($this->translate('pagetitle.fund.list'))
                ->setSubject($this->translate('pagetitle.fund.list'))
                ->setDescription($this->translate('pagetitle.fund.list'))
                ->setKeywords($this->translate('pagetitle.fund.list'))
                ->setCategory('ACEF fund');

            $phpExcelObject->setActiveSheetIndex(0);

            $workSheet = $phpExcelObject->getActiveSheet();
            $workSheet->setTitle($this->translate('pagetitle.fund.list'));

            $workSheet->setCellValue('A1', $this->translate('Fund.label.label'));
            $workSheet->getStyle('A1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('B1', $this->translate('Fund.number.label'));
            $workSheet->getStyle('B1')
                ->getFont()
                ->setBold(true);

            $workSheet->getStyle('A1:B1')->applyFromArray(array(
                'fill' => array(
                    'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array(
                        'rgb' => '94ccdf'
                    )
                )
            ));

            $fundsConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
                'name' => 'fundsPrefix'
            ));
            if (null == $fundsConstStr) {
                $fundsConstStr = new ConstantStr();
                $fundsConstStr->setName('fundsPrefix');
                $fundsConstStr->setValue('540');
                $em->persist($fundsConstStr);
                $em->flush();
            }
            $fundsPrefix = $fundsConstStr->getValue();
            $this->gvars['fundsPrefix'] = $fundsPrefix;

            $i = 1;

            foreach ($funds as $fund) {
                $i++;
                $workSheet->setCellValue('A' . $i, $fund->getLabel(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $numb = $fundsPrefix . $fund->getNumberFormated();

                $workSheet->setCellValueExplicit('B' . $i, $numb, \PHPExcel_Cell_DataType::TYPE_STRING2);

                if ($i % 2 == 1) {
                    $workSheet->getStyle('A' . $i . ':B' . $i)->applyFromArray(array(
                        'fill' => array(
                            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array(
                                'rgb' => 'd8f1f5'
                            )
                        )
                    ));
                } else {
                    $workSheet->getStyle('A' . $i . ':B' . $i)->applyFromArray(array(
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

            $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
            $response = $this->get('phpexcel')->createStreamedResponse($writer);

            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8');

            $filename = $this->normalizeString($this->normalize($this->translate('pagetitle.fund.listByCompany', array(
                '%company%' => $company->getCorporateName()
            ))));
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
     * @param string $uid
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function withholdingExcelAction($uid)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_company_list');
        }

        $em = $this->getEntityManager();
        try {
            $company = $em->getRepository('AcfDataBundle:Company')->find($uid);
            $withholdings = $company->getWithholdings();

            $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

            $phpExcelObject->getProperties()
                ->setCreator('Salah Abdelkader Seif Eddine')
                ->setLastModifiedBy($this->getSecurityTokenStorage()
                ->getToken()
                ->getUser()
                ->getFullname())
                ->setTitle($this->translate('pagetitle.withholding.list'))
                ->setSubject($this->translate('pagetitle.withholding.list'))
                ->setDescription($this->translate('pagetitle.withholding.list'))
                ->setKeywords($this->translate('pagetitle.withholding.list'))
                ->setCategory('ACEF withholding');

            $phpExcelObject->setActiveSheetIndex(0);

            $workSheet = $phpExcelObject->getActiveSheet();
            $workSheet->setTitle($this->translate('pagetitle.withholding.list'));

            $workSheet->setCellValue('A1', $this->translate('Withholding.label.label'));
            $workSheet->getStyle('A1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('B1', $this->translate('Withholding.number.label'));
            $workSheet->getStyle('B1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('C1', $this->translate('Withholding.value.label'));
            $workSheet->getStyle('C1')
                ->getFont()
                ->setBold(true);

            $withholdingsConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
                'name' => 'withholdingsPrefix'
            ));
            if (null == $withholdingsConstStr) {
                $withholdingsConstStr = new ConstantStr();
                $withholdingsConstStr->setName('withholdingsPrefix');
                $withholdingsConstStr->setValue('432');
                $em->persist($withholdingsConstStr);
                $em->flush();
            }
            $withholdingsPrefix = $withholdingsConstStr->getValue();
            $this->gvars['withholdingsPrefix'] = $withholdingsPrefix;

            $workSheet->getStyle('A1:C1')->applyFromArray(array(
                'fill' => array(
                    'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array(
                        'rgb' => '94ccdf'
                    )
                )
            ));

            $i = 1;

            foreach ($withholdings as $withholding) {
                $i++;
                $workSheet->setCellValue('A' . $i, $withholding->getLabel(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $numb = $withholdingsPrefix . $withholding->getNumberFormated();

                $workSheet->setCellValueExplicit('B' . $i, $numb, \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('C' . $i, $withholding->getValue() . '%', \PHPExcel_Cell_DataType::TYPE_STRING2);

                if ($i % 2 == 1) {
                    $workSheet->getStyle('A' . $i . ':C' . $i)->applyFromArray(array(
                        'fill' => array(
                            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array(
                                'rgb' => 'd8f1f5'
                            )
                        )
                    ));
                } else {
                    $workSheet->getStyle('A' . $i . ':C' . $i)->applyFromArray(array(
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

            $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
            $response = $this->get('phpexcel')->createStreamedResponse($writer);

            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8');

            $filename = $this->normalizeString($this->normalize($this->translate('pagetitle.withholding.listByCompany', array(
                '%company%' => $company->getCorporateName()
            ))));
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

    protected function traceEntity(Company $cloneCompany, Company $company)
    {
        $curUser = $this->getSecurityTokenStorage()
            ->getToken()
            ->getUser();
        $trace = new Trace();
        $trace->setActionId($company->getId());
        $trace->setActionType(Trace::AT_UPDATE);
        $trace->setUserId($curUser->getId());
        $trace->setCompanyId($company->getId());
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

        $trace->setActionEntity(Trace::AE_COMPANY);

        $msg = '';

        if ($cloneCompany->getRef() != $company->getRef()) {
            $msg .= '<tr><td>' . $this->translate('Company.ref.label') . '</td><td>';
            if ($cloneCompany->getRef() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getRef();
            }
            $msg .= '</td><td>';
            if ($company->getRef() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getRef();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getCorporateName() != $company->getCorporateName()) {
            $msg .= '<tr><td>' . $this->translate('Company.corporateName.label') . '</td><td>';
            if ($cloneCompany->getCorporateName() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getCorporateName();
            }
            $msg .= '</td><td>';
            if ($company->getCorporateName() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getCorporateName();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getType() != $company->getType()) {
            $msg .= '<tr><td>' . $this->translate('Company.type.label') . '</td><td>';
            if ($cloneCompany->getType() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getType()->getLabel();
            }
            $msg .= '</td><td>';
            if ($company->getType() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getType()->getLabel();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getTribunal() != $company->getTribunal()) {
            $msg .= '<tr><td>' . $this->translate('Company.tribunal.label') . '</td><td>';
            if ($cloneCompany->getTribunal() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getTribunal();
            }
            $msg .= '</td><td>';
            if ($company->getTribunal() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getTribunal();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getFisc() != $company->getFisc()) {
            $msg .= '<tr><td>' . $this->translate('Company.fisc.label') . '</td><td>';
            if ($cloneCompany->getFisc() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getFisc();
            }
            $msg .= '</td><td>';
            if ($company->getFisc() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getFisc();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getCommercialRegister() != $company->getCommercialRegister()) {
            $msg .= '<tr><td>' . $this->translate('Company.commercialRegister.label') . '</td><td>';
            if ($cloneCompany->getCommercialRegister() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getCommercialRegister();
            }
            $msg .= '</td><td>';
            if ($company->getCommercialRegister() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getCommercialRegister();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getCommercialRegisterBureau() != $company->getCommercialRegisterBureau()) {
            $msg .= '<tr><td>' . $this->translate('Company.commercialRegisterBureau.label') . '</td><td>';
            if ($cloneCompany->getCommercialRegisterBureau() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getCommercialRegisterBureau();
            }
            $msg .= '</td><td>';
            if ($company->getCommercialRegisterBureau() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getCommercialRegisterBureau();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getCnss() != $company->getCnss()) {
            $msg .= '<tr><td>' . $this->translate('Company.cnss.label') . '</td><td>';
            if ($cloneCompany->getCnss() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getCnss();
            }
            $msg .= '</td><td>';
            if ($company->getCnss() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getCnss();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getCnssBureau() != $company->getCnssBureau()) {
            $msg .= '<tr><td>' . $this->translate('Company.cnssBureau.label') . '</td><td>';
            if ($cloneCompany->getCnssBureau() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getCnssBureau();
            }
            $msg .= '</td><td>';
            if ($company->getCnssBureau() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getCnssBureau();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getPhysicalType() != $company->getPhysicalType()) {
            $msg .= '<tr><td>' . $this->translate('Company.physicalType.label') . '</td><td>';
            if ($cloneCompany->getPhysicalType() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('Company.physicalType.' . $cloneCompany->getPhysicalType());
            }
            $msg .= '</td><td>';
            if ($company->getPhysicalType() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('Company.physicalType.' . $company->getPhysicalType());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getCin() != $company->getCin()) {
            $msg .= '<tr><td>' . $this->translate('Company.cin.label') . '</td><td>';
            if ($cloneCompany->getCin() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getCin();
            }
            $msg .= '</td><td>';
            if ($company->getCin() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getCin();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getPassport() != $company->getPassport()) {
            $msg .= '<tr><td>' . $this->translate('Company.passport.label') . '</td><td>';
            if ($cloneCompany->getPassport() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getPassport();
            }
            $msg .= '</td><td>';
            if ($company->getPassport() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getPassport();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getCustomsCode() != $company->getCustomsCode()) {
            $msg .= '<tr><td>' . $this->translate('Company.customsCode.label') . '</td><td>';
            if ($cloneCompany->getCustomsCode() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getCustomsCode();
            }
            $msg .= '</td><td>';
            if ($company->getCustomsCode() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getCustomsCode();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getActionvn() != $company->getActionvn()) {
            $msg .= '<tr><td>' . $this->translate('Company.actionvn.label') . '</td><td>';
            if ($cloneCompany->getActionvn() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getActionvn();
            }
            $msg .= '</td><td>';
            if ($company->getActionvn() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getActionvn();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getStreetNum() != $company->getStreetNum()) {
            $msg .= '<tr><td>' . $this->translate('Company.streetNum.label') . '</td><td>';
            if ($cloneCompany->getStreetNum() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getStreetNum();
            }
            $msg .= '</td><td>';
            if ($company->getStreetNum() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getStreetNum();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getAddress() != $company->getAddress()) {
            $msg .= '<tr><td>' . $this->translate('Company.address.label') . '</td><td>';
            if ($cloneCompany->getAddress() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getAddress();
            }
            $msg .= '</td><td>';
            if ($company->getAddress() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getAddress();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getAddress2() != $company->getAddress2()) {
            $msg .= '<tr><td>' . $this->translate('Company.address2.label') . '</td><td>';
            if ($cloneCompany->getAddress2() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getAddress2();
            }
            $msg .= '</td><td>';
            if ($company->getAddress2() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getAddress2();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getTown() != $company->getTown()) {
            $msg .= '<tr><td>' . $this->translate('Company.town.label') . '</td><td>';
            if ($cloneCompany->getTown() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getTown();
            }
            $msg .= '</td><td>';
            if ($company->getTown() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getTown();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getZipCode() != $company->getZipCode()) {
            $msg .= '<tr><td>' . $this->translate('Company.zipCode.label') . '</td><td>';
            if ($cloneCompany->getZipCode() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getZipCode();
            }
            $msg .= '</td><td>';
            if ($company->getZipCode() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getZipCode();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getCountry() != $company->getCountry()) {
            $msg .= '<tr><td>' . $this->translate('Company.country.label') . '</td><td>';
            if ($cloneCompany->getCountry() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getCountry();
            }
            $msg .= '</td><td>';
            if ($company->getCountry() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getCountry();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getPhone() != $company->getPhone()) {
            $msg .= '<tr><td>' . $this->translate('Company.phone.label') . '</td><td>';
            if ($cloneCompany->getPhone() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getPhone();
            }
            $msg .= '</td><td>';
            if ($company->getPhone() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getPhone();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getMobile() != $company->getMobile()) {
            $msg .= '<tr><td>' . $this->translate('Company.mobile.label') . '</td><td>';
            if ($cloneCompany->getMobile() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getMobile();
            }
            $msg .= '</td><td>';
            if ($company->getMobile() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getMobile();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getFax() != $company->getFax()) {
            $msg .= '<tr><td>' . $this->translate('Company.fax.label') . '</td><td>';
            if ($cloneCompany->getFax() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getFax();
            }
            $msg .= '</td><td>';
            if ($company->getFax() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getFax();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getEmail() != $company->getEmail()) {
            $msg .= '<tr><td>' . $this->translate('Company.email.label') . '</td><td>';
            if ($cloneCompany->getEmail() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getEmail();
            }
            $msg .= '</td><td>';
            if ($company->getEmail() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getEmail();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompany->getOtherInfos() != $company->getOtherInfos()) {
            $msg .= '<tr><td>' . $this->translate('Company.otherInfos.label') . '</td><td>';
            if ($cloneCompany->getOtherInfos() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompany->getOtherInfos();
            }
            $msg .= '</td><td>';
            if ($company->getOtherInfos() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $company->getOtherInfos();
            }
            $msg .= '</td></tr>';
        }

        if (\count(\array_diff($company->getSectors()->toArray(), $cloneCompany->getSectors()->toArray())) != 0 || \count(\array_diff($cloneCompany->getSectors()->toArray(), $company->getSectors()->toArray())) != 0) {
            $msg .= '<tr><td>' . $this->translate('Company.sectors.label') . '</td><td>';
            if (\count($cloneCompany->getSectors()) == 0) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= '<ul>';
                foreach ($cloneCompany->getSectors() as $sector) {
                    $msg .= '<li>' . $sector->getLabel() . '</li>';
                }
                $msg .= '<ul>';
            }
            $msg .= '</td><td>';
            if (\count($company->getSectors()) == 0) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= '<ul>';
                foreach ($company->getSectors() as $sector) {
                    $msg .= '<li>' . $sector->getLabel() . '</li>';
                }
                $msg .= '<ul>';
            }
            $msg .= '</td></tr>';
        }

        if ($msg != '') {

            $msg = $tableBegin . $msg . $tableEnd;

            $trace->setMsg($this->translate('Company.traceEdit', array(
                '%company%' => $company->getCorporateName()
            )) . $msg);
            $trace->setDtCrea(new \DateTime('now'));
            $em = $this->getEntityManager();
            $em->persist($trace);
            $em->flush();
        }
    }

    private static function normalizeString($str = '')
    {
        $str = strip_tags($str);
        $str = preg_replace('/[\r\n\t ]+/', ' ', $str);
        $str = preg_replace('/[\"\*\/\:\<\>\?\'\|]+/', ' ', $str);
        $str = strtolower($str);
        $str = html_entity_decode($str, ENT_QUOTES, "utf-8");
        $str = htmlentities($str, ENT_QUOTES, "utf-8");
        $str = preg_replace("/(&)([a-z])([a-z]+;)/i", '$2', $str);
        $str = str_replace(' ', '-', $str);
        $str = rawurlencode($str);
        $str = str_replace('%', '-', $str);
        return $str;
    }
}
