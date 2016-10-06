<?php
namespace Acf\AdminBundle\Controller;

use Acf\AdminBundle\Form\BulletinInfo\ImportTForm as BulletinInfoImportTForm;
use Acf\AdminBundle\Form\BulletinInfo\UpdateDtStartTForm as BulletinInfoUpdateDtStartTForm;
use Acf\AdminBundle\Form\BulletinInfo\UpdateNumTForm as BulletinInfoUpdateNumTForm;
use Acf\AdminBundle\Form\BulletinInfo\UpdateDescriptionTForm as BulletinInfoUpdateDescriptionTForm;
use Acf\AdminBundle\Form\BulletinInfoTitle\NewTForm as BulletinInfoTitleNewTForm;
use Acf\DataBundle\Entity\BulletinInfo;
use Acf\DataBundle\Entity\BulletinInfoContent;
use Acf\DataBundle\Entity\BulletinInfoTitle;
use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\Role;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 * @version $Id$
 * @license MIT
 */
class BulletinInfoController extends BaseController
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
        $this->gvars['menu_active'] = 'bulletinInfo';
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
        $bulletinInfos = $em->getRepository('AcfDataBundle:BulletinInfo')->getAll();
        $this->gvars['bulletinInfos'] = $bulletinInfos;

        $this->gvars['smenu_active'] = 'list';
        $this->gvars['pagetitle'] = $this->translate('pagetitle.bulletinInfo.list');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.bulletinInfo.list.txt');

        return $this->renderResponse('AcfAdminBundle:BulletinInfo:list.html.twig', $this->gvars);
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function importGetAction()
    {
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }
        $bulletinInfoImportForm = $this->createForm(BulletinInfoImportTForm::class);

        $this->gvars['BulletinInfoImportForm'] = $bulletinInfoImportForm->createView();

        $this->gvars['pagetitle'] = $this->translate('pagetitle.bulletinInfo.add');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.bulletinInfo.add.txt');
        $this->gvars['smenu_active'] = 'add';

        return $this->renderResponse('AcfAdminBundle:BulletinInfo:import.html.twig', $this->gvars);
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function importPostAction()
    {
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_bulletinInfo_list');
        }

        $bulletinInfoImportForm = $this->createForm(BulletinInfoImportTForm::class);

        $request = $this->getRequest();
        $reqData = $request->request->all();

        if (isset($reqData['BulletinInfoImportForm'])) {
            $bulletinInfoImportForm->handleRequest($request);
            if ($bulletinInfoImportForm->isValid()) {

                ini_set('memory_limit', '4096M');
                ini_set('max_execution_time', '0');
                $extension = $bulletinInfoImportForm['excel']->getData()->guessExtension();
                if ($extension == 'zip') {
                    $extension = 'xlsx';
                }

                $filename = uniqid() . '.' . $extension;
                $bulletinInfoImportForm['excel']->getData()->move($this->getParameter('adapter_files'), $filename);
                $fullfilename = $this->getParameter('adapter_files');
                $fullfilename .= '/' . $filename;

                $excelObj = $this->get('phpexcel')->createPHPExcelObject($fullfilename);

                $log = '';

                $iterator = $excelObj->getWorksheetIterator();

                $activeSheetIndex = -1;
                $i = 0;

                $bulletinInfo = new BulletinInfo();

                foreach ($iterator as $worksheet) {
                    $worksheetTitle = $worksheet->getTitle();
                    $highestRow = $worksheet->getHighestRow(); // e.g. 10
                    $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
                    $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);

                    $log .= "Feuille : '" . $worksheetTitle . "' trouvée contenant " . $highestRow . ' lignes et ' . $highestColumnIndex . ' colonnes avec comme plus grand index ' . $highestColumn . ' <br>';
                    if (\trim($worksheetTitle) == 'BulletinInfo') {
                        $activeSheetIndex = $i;
                    }
                    $i++;
                }
                if ($activeSheetIndex == -1) {
                    $log .= "Aucune Feuille de Titre 'BulletinInfo' trouvée tentative d'import depuis le première Feuille<br>";
                    $activeSheetIndex = 0;
                }

                $excelObj->setActiveSheetIndex($activeSheetIndex);
                $worksheet = $excelObj->getActiveSheet();
                $highestRow = $worksheet->getHighestRow();
                $lineRead = 0;
                $lineUnprocessed = 0;
                $bulletinInfoTitleNew = 0;
                $bulletinInfoContentNew = 0;
                $lineError = 0;
                $haserror = false;

                if ($highestRow < 3) {
                    $log .= 'Fichier Excel Invalide<br>';
                    $haserror = true;
                } else {
                    $lineRead++;
                    $num = \trim(\intval($worksheet->getCellByColumnAndRow(8, 3)->getValue()));
                    $dtStart = \PHPExcel_Shared_Date::ExcelToPHPObject($worksheet->getCellByColumnAndRow(10, 3)->getValue());
                    if ($num <= 0) {
                        $log .= 'Numéro de bulletin illisible (' . $num . ')<br>';
                        $haserror = true;
                        $lineError++;
                    }
                    if (!($dtStart instanceof \DateTime)) {
                        $log .= 'Date du bulletin illisible (' . $dtStart . ')<br>';
                        $haserror = true;
                        $lineError++;
                    }
                }

                if (!$haserror) {
                    $em = $this->getEntityManager();
                    $bulletinInfoTest = $em->getRepository('AcfDataBundle:BulletinInfo')->findOneBy(array(
                        'num' => $num
                    ));
                    if (null != $bulletinInfoTest) {
                        $log .= 'Numéro de bulletin déjà existant<br>';
                        $haserror = true;
                    }
                }

                if (!$haserror) {

                    $bulletinInfo->setNum($num);
                    $bulletinInfo->setDtStart($dtStart);
                    $em->persist($bulletinInfo);
                    $lineRead = 5;
                    $isTitle = false;
                    $isContent = false;
                    $titles = array();
                    // $logger = $this->getLogger();
                    for ($row = $lineRead - 1; $row <= $highestRow; $row++) {
                        $isTitle = false;
                        $isContent = false;
                        $canBeTitle = false;
                        $canBeContent = false;
                        try {
                            $testNum = \trim(\strval($worksheet->getCellByColumnAndRow(1, $row)->getValue()));
                            if ($testNum != '') {
                                if (\stripos($testNum, '-')) {
                                    $canBeContent = true;
                                } elseif (\intval($testNum) > 0) {
                                    $canBeTitle = true;
                                }
                            }
                            if ($canBeTitle) {
                                if (!\array_key_exists($testNum, $titles)) {
                                    $isTitle = true;
                                } else {
                                    $lineError++;
                                    $log .= 'Titre : Numéro de Titre ' . $testNum . ' à  la ligne  ' . $lineRead . ' existe déja<br>';
                                }
                            }
                            if ($canBeContent) {
                                $titleContent = \explode('-', $testNum);
                                if (\count($titleContent) == 2) {
                                    $titleNum = \intval($titleContent[0]);
                                    $contentNum = \intval($titleContent[1]);
                                    if ($titleNum > 0 && $titleContent > 0) {
                                        if (!\array_key_exists($titleNum, $titles)) {
                                            $lineError++;
                                            $log .= 'Contenu : Numéro de Titre ' . $titleNum . ' à  la ligne  ' . $lineRead . ' inexistant<br>';
                                        } elseif (!\array_key_exists($contentNum, $titles[$titleNum]['contents'])) {
                                            $isContent = true;
                                        } else {
                                            $lineError++;
                                            $log .= 'Contenu : Numéro de Contenu ' . $contentNum . ' à  la ligne  ' . $lineRead . ' existe déjà<br>';
                                        }
                                    }
                                }
                            }
                            if ($isTitle) {
                                $btTitle = \trim(\strval($worksheet->getCellByColumnAndRow(2, $row)->getValue()));
                                if ($btTitle != '') {
                                    $bulletinInfoTitle = new BulletinInfoTitle();
                                    $bulletinInfoTitle->setBulletinInfo($bulletinInfo);
                                    $bulletinInfoTitle->setTitle($btTitle);
                                    $em->persist($bulletinInfoTitle);
                                    $titles[$testNum] = array(
                                        'bulletinInfoTitle' => $bulletinInfoTitle,
                                        'contents' => array()
                                    );
                                    $bulletinInfoTitleNew++;
                                } else {
                                    $lineError++;
                                    $log .= 'Titre : Numéro de nouveau Titre ' . $testNum . ' trouvé à la ligne  ' . $lineRead . ' mais texte vide<br>';
                                }
                            }

                            if ($isContent) {
                                $bcTitle = \trim(\strval($worksheet->getCellByColumnAndRow(2, $row)->getValue()));
                                if ($bcTitle != '') {
                                    $bulletinInfoTitle = $titles[$titleNum]['bulletinInfoTitle'];
                                    $bulletinInfoContent = new BulletinInfoContent();
                                    $bulletinInfoContent->setBulletinInfoTitle($bulletinInfoTitle);
                                    $bulletinInfoContent->setTitle($bcTitle);
                                    $row++;
                                    $content = \trim(\strval($worksheet->getCellByColumnAndRow(3, $row)->getValue()));
                                    if ($content != '') {
                                        $bulletinInfoContent->setContent($content);
                                    }
                                    $theme = \trim(\strval($worksheet->getCellByColumnAndRow(8, $row)->getValue()));
                                    if ($theme != '') {
                                        $bulletinInfoContent->setTheme($theme);
                                    }
                                    $jort = \trim(\strval($worksheet->getCellByColumnAndRow(9, $row)->getValue()));
                                    if ($jort != '') {
                                        $bulletinInfoContent->setJort($jort);
                                    }
                                    $txtNum = \trim(\strval($worksheet->getCellByColumnAndRow(10, $row)->getValue()));
                                    if ($txtNum != '') {
                                        $bulletinInfoContent->setTxtNum($txtNum);
                                    }
                                    $artTxt = \trim(\strval($worksheet->getCellByColumnAndRow(11, $row)->getValue()));
                                    if ($artTxt != '') {
                                        $bulletinInfoContent->setArtTxt($artTxt);
                                    }
                                    $dtTxt = \trim(\strval($worksheet->getCellByColumnAndRow(12, $row)->getValue()));
                                    if ($dtTxt != '') {
                                        $bulletinInfoContent->setDtTxt($dtTxt);
                                    }
                                    $artCode = \trim(\strval($worksheet->getCellByColumnAndRow(13, $row)->getValue()));
                                    if ($artCode != '') {
                                        $bulletinInfoContent->setArtCode($artCode);
                                    }
                                    $companyType = \trim(\strval($worksheet->getCellByColumnAndRow(14, $row)->getValue()));
                                    if ($companyType != '') {
                                        $bulletinInfoContent->setCompanyType($companyType);
                                    }
                                    $dtApplication = \trim(\strval($worksheet->getCellByColumnAndRow(15, $row)->getValue()));
                                    if ($dtApplication != '') {
                                        $bulletinInfoContent->setDtApplication($dtApplication);
                                    }
                                    $em->persist($bulletinInfoContent);
                                    $titles[$titleNum]['contents'][$contentNum] = $bulletinInfoContent;
                                    $bulletinInfoContentNew++;
                                } else {
                                    $lineError++;
                                    $log .= 'Contenu : Numéro de Contenu ' . $testNum . ' trouvé à la ligne  ' . $lineRead . ' mais texte vide<br>';
                                }
                            } else {
                                $lineUnprocessed++;
                            }
                        } catch (\Exception $e) {
                            $lineError++;
                            $log .= 'Erreur de lecture à la ligne  ' . $lineRead . ' : ' . $e->getMessage() . '<br>';
                        }
                        $lineRead++;
                    }
                    $em->flush();
                }

                $log .= $lineRead . ' lignes lues<br>';
                $log .= $lineUnprocessed . ' lignes non traités<br>';
                $log .= $bulletinInfoTitleNew . " nouveaux Titres de Bulletin d'Informations<br>";
                $log .= $bulletinInfoContentNew . " nouveaux Contenus de Bulletin d'Informations<br>";
                $log .= $lineError . ' lignes contenant des erreurs<br>';

                $this->flashMsgSession('log', $log);

                if (!$haserror) {

                    $this->flashMsgSession('success', $this->translate('BulletinInfo.import.success', array(
                        '%bulletinInfo%' => $bulletinInfo->getId()
                    )));

                    return $this->redirect($this->generateUrl('_admin_bulletinInfo_editGet', array(
                        'uid' => $bulletinInfo->getId()
                    )));
                }
            }
        }
        $this->flashMsgSession('error', $this->translate('BulletinInfo.import.failure'));

        return $this->redirect($urlFrom);
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
            $urlFrom = $this->generateUrl('_admin_bulletinInfo_list');
        }
        $em = $this->getEntityManager();
        try {
            $bulletinInfo = $em->getRepository('AcfDataBundle:BulletinInfo')->find($uid);

            if (null == $bulletinInfo) {
                $this->flashMsgSession('warning', $this->translate('BulletinInfo.delete.notfound'));
            } else {
                $em->remove($bulletinInfo);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('BulletinInfo.delete.success', array(
                    '%bulletinInfo%' => $bulletinInfo->getNum()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('BulletinInfo.delete.failure'));
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
            $urlFrom = $this->generateUrl('_admin_bulletinInfo_list');
        }

        $em = $this->getEntityManager();
        try {
            $bulletinInfo = $em->getRepository('AcfDataBundle:BulletinInfo')->find($uid);

            if (null == $bulletinInfo) {
                $this->flashMsgSession('warning', $this->translate('BulletinInfo.edit.notfound'));
            } else {
                $bulletinInfoUpdateDtStartForm = $this->createForm(BulletinInfoUpdateDtStartTForm::class, $bulletinInfo);
                $bulletinInfoUpdateNumForm = $this->createForm(BulletinInfoUpdateNumTForm::class, $bulletinInfo);
                $bulletinInfoUpdateDescriptionForm = $this->createForm(BulletinInfoUpdateDescriptionTForm::class, $bulletinInfo);

                $bulletinInfoTitle = new BulletinInfoTitle();
                $bulletinInfoTitle->setBulletinInfo($bulletinInfo);
                $bulletinInfoTitleNewForm = $this->createForm(BulletinInfoTitleNewTForm::class, $bulletinInfoTitle, array(
                    'bulletinInfo' => $bulletinInfo
                ));

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');

                $this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
                $this->getSession()->remove('stabActive');

                $this->gvars['bulletinInfo'] = $bulletinInfo;

                $this->gvars['BulletinInfoUpdateDtStartForm'] = $bulletinInfoUpdateDtStartForm->createView();
                $this->gvars['BulletinInfoUpdateDescriptionForm'] = $bulletinInfoUpdateDescriptionForm->createView();
                $this->gvars['BulletinInfoUpdateNumForm'] = $bulletinInfoUpdateNumForm->createView();
                $this->gvars['BulletinInfoTitleNewForm'] = $bulletinInfoTitleNewForm->createView();

                $this->gvars['pagetitle'] = $this->translate('pagetitle.bulletinInfo.edit', array(
                    '%bulletinInfo%' => $bulletinInfo->getNum()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.bulletinInfo.edit.txt', array(
                    '%bulletinInfo%' => $bulletinInfo->getNum()
                ));

                return $this->renderResponse('AcfAdminBundle:BulletinInfo:edit.html.twig', $this->gvars);
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editPostAction($uid)
    {
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }

        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_bulletinInfo_list');
        }

        $em = $this->getEntityManager();
        try {
            $bulletinInfo = $em->getRepository('AcfDataBundle:BulletinInfo')->find($uid);

            if (null == $bulletinInfo) {
                $this->flashMsgSession('warning', $this->translate('BulletinInfo.edit.notfound'));
            } else {
                $bulletinInfoUpdateDtStartForm = $this->createForm(BulletinInfoUpdateDtStartTForm::class, $bulletinInfo);
                $bulletinInfoUpdateDescriptionForm = $this->createForm(BulletinInfoUpdateDescriptionTForm::class, $bulletinInfo);
                $bulletinInfoUpdateNumForm = $this->createForm(BulletinInfoUpdateNumTForm::class, $bulletinInfo);

                $bulletinInfoTitle = new BulletinInfoTitle();
                $bulletinInfoTitle->setBulletinInfo($bulletinInfo);
                $bulletinInfoTitleNewForm = $this->createForm(BulletinInfoTitleNewTForm::class, $bulletinInfoTitle, array(
                    'bulletinInfo' => $bulletinInfo
                ));

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
                $this->getSession()->remove('stabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                if (isset($reqData['BulletinInfoUpdateDtStartForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $bulletinInfoUpdateDtStartForm->handleRequest($request);
                    if ($bulletinInfoUpdateDtStartForm->isValid()) {
                        $em->persist($bulletinInfo);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('BulletinInfo.edit.success', array(
                            '%bulletinInfo%' => $bulletinInfo->getNum()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($bulletinInfo);

                        $this->flashMsgSession('error', $this->translate('BulletinInfo.edit.failure', array(
                            '%bulletinInfo%' => $bulletinInfo->getNum()
                        )));
                    }
                } elseif (isset($reqData['BulletinInfoUpdateNumForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $bulletinInfoUpdateNumForm->handleRequest($request);
                    if ($bulletinInfoUpdateNumForm->isValid()) {
                        $em->persist($bulletinInfo);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('BulletinInfo.edit.success', array(
                            '%bulletinInfo%' => $bulletinInfo->getNum()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($bulletinInfo);

                        $this->flashMsgSession('error', $this->translate('BulletinInfo.edit.failure', array(
                            '%bulletinInfo%' => $bulletinInfo->getNum()
                        )));
                    }
                } elseif (isset($reqData['BulletinInfoUpdateDescriptionForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $bulletinInfoUpdateDescriptionForm->handleRequest($request);
                    if ($bulletinInfoUpdateDescriptionForm->isValid()) {
                        $em->persist($bulletinInfo);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('BulletinInfo.edit.success', array(
                            '%bulletinInfo%' => $bulletinInfo->getNum()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($bulletinInfo);

                        $this->flashMsgSession('error', $this->translate('BulletinInfo.edit.failure', array(
                            '%bulletinInfo%' => $bulletinInfo->getNum()
                        )));
                    }
                } elseif (isset($reqData['BulletinInfoTitleNewForm'])) {
                    $this->gvars['tabActive'] = 3;
                    $this->getSession()->set('tabActive', 3);
                    $bulletinInfoTitleNewForm->handleRequest($request);
                    if ($bulletinInfoTitleNewForm->isValid()) {
                        $em->persist($bulletinInfoTitle);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('BulletinInfoTitle.add.success', array(
                            '%bulletinInfoTitle%' => $bulletinInfoTitle->getTitle()
                        )));

                        $this->gvars['stabActive'] = 2;
                        $this->getSession()->set('stabActive', 2);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($bulletinInfo);

                        $this->flashMsgSession('error', $this->translate('BulletinInfoTitle.add.failure'));
                    }
                }

                $this->gvars['bulletinInfo'] = $bulletinInfo;

                $this->gvars['BulletinInfoUpdateDtStartForm'] = $bulletinInfoUpdateDtStartForm->createView();
                $this->gvars['BulletinInfoUpdateDescriptionForm'] = $bulletinInfoUpdateDescriptionForm->createView();
                $this->gvars['BulletinInfoUpdateNumForm'] = $bulletinInfoUpdateNumForm->createView();
                $this->gvars['BulletinInfoTitleNewForm'] = $bulletinInfoTitleNewForm->createView();

                $this->gvars['pagetitle'] = $this->translate('pagetitle.bulletinInfo.edit', array(
                    '%bulletinInfo%' => $bulletinInfo->getNum()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.bulletinInfo.edit.txt', array(
                    '%bulletinInfo%' => $bulletinInfo->getNum()
                ));

                return $this->renderResponse('AcfAdminBundle:BulletinInfo:edit.html.twig', $this->gvars);
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
    public function mailAction($uid)
    {
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }

        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_bulletinInfo_list');
        }

        $em = $this->getEntityManager();
        try {
            $bulletinInfo = $em->getRepository('AcfDataBundle:BulletinInfo')->find($uid);

            if (null == $bulletinInfo) {
                $this->flashMsgSession('warning', $this->translate('BulletinInfo.edit.notfound'));
            } else {
                $acfInfoRole = $em->getRepository('AcfDataBundle:Role')->findOneBy(array(
                    'name' => 'ROLE_CLIENT2'
                ));
                $from = $this->getParameter('mail_from');
                $fromName = $this->getParameter('mail_from_name');
                $subject = $this->translate('_mail.newbi.subject', array(
                    '%bulletinInfo%' => $bulletinInfo->getNum()
                ), 'messages');
                $i = 0;
                foreach ($acfInfoRole->getUsers() as $user) {
                    $i++;
                    $mvars = array();
                    $mvars['bi'] = $bulletinInfo;
                    $mvars['user'] = $user;
                    $message = \Swift_Message::newInstance();
                    $message->setFrom($from, $fromName);
                    $message->setTo($user->getEmail(), $user->getFullname());
                    $message->setSubject($subject);
                    $message->setBody($this->renderView('AcfAdminBundle:BulletinInfo:mail.html.twig', $mvars), 'text/html');
                    $this->sendmail($message);
                }
                $this->flashMsgSession('success', $this->translate('BulletinInfo.mail.success', array(
                    '%sendmail%' => $i
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }
}
