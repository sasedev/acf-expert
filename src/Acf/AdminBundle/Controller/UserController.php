<?php
namespace Acf\AdminBundle\Controller;

use Acf\DataBundle\Entity\User;
use Acf\DataBundle\Entity\Trace;
use Acf\AdminBundle\Form\User\CropAvatarTForm as UserCropAvatarTForm;
use Acf\AdminBundle\Form\User\NewTForm as UserNewTForm;
use Acf\AdminBundle\Form\User\UpdateEmailTForm as UserUpdateEmailTForm;
use Acf\AdminBundle\Form\User\UpdateLastValidityTForm as UserUpdateLastValidityTForm;
use Acf\AdminBundle\Form\User\UpdateLockoutTForm as UserUpdateLockoutTForm;
use Acf\AdminBundle\Form\User\UpdatePasswordTForm as UserUpdatePasswordTForm;
use Acf\AdminBundle\Form\User\UpdatePreferedLangTForm as UserUpdatePreferedLangTForm;
use Acf\AdminBundle\Form\User\UpdateProfileTForm as UserUpdateProfileTForm;
use Acf\AdminBundle\Form\User\UpdateSubcategsTForm as UserUpdateSubcategsTForm;
use Acf\AdminBundle\Form\User\UpdateUserRolesTForm as UserUpdateUserRolesTForm;
use Acf\AdminBundle\Form\User\UploadAvatarTForm as UserUploadAvatarTForm;
use Imagine\Imagick\Imagine;
use Imagine\Image\Point;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Intl\Intl;
use Acf\DataBundle\Entity\AoSubCateg;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class UserController extends BaseController
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
        $this->gvars['menu_active'] = 'user';
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
        $users = $em->getRepository('AcfDataBundle:User')->getAll();
        $this->gvars['users'] = $users;

        $this->gvars['smenu_active'] = 'list';
        $this->gvars['pagetitle'] = $this->translate('pagetitle.user.list');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.user.list.txt');

        return $this->renderResponse('AcfAdminBundle:User:list.html.twig', $this->gvars);
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
            $users = $em->getRepository('AcfDataBundle:User')->getAll();
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
            $workSheet->setTitle($this->translate('pagetitle.user.list'));

            $workSheet->setCellValue('A1', $this->translate('User.lastName.label'));
            $workSheet->getStyle('A1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('B1', $this->translate('User.firstName.label'));
            $workSheet->getStyle('B1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('C1', $this->translate('User.sexe.label'));
            $workSheet->getStyle('C1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('D1', $this->translate('User.username.label'));
            $workSheet->getStyle('D1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('E1', $this->translate('User.email.label'));
            $workSheet->getStyle('E1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('F1', $this->translate('User.lockout.label'));
            $workSheet->getStyle('F1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('G1', $this->translate('User.birthday.label'));
            $workSheet->getStyle('G1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('H1', $this->translate('User.streetNum.label'));
            $workSheet->getStyle('H1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('I1', $this->translate('User.address.label'));
            $workSheet->getStyle('I1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('J1', $this->translate('User.address2.label'));
            $workSheet->getStyle('J1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('K1', $this->translate('User.town.label'));
            $workSheet->getStyle('K1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('L1', $this->translate('User.zipCode.label'));
            $workSheet->getStyle('L1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('M1', $this->translate('User.country.label'));
            $workSheet->getStyle('M1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('N1', $this->translate('User.phone.label'));
            $workSheet->getStyle('N1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('O1', $this->translate('User.mobile.label'));
            $workSheet->getStyle('O1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('P1', $this->translate('User.dtCrea.label'));
            $workSheet->getStyle('P1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('Q1', $this->translate('User.userRoles.label'));
            $workSheet->getStyle('Q1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('R1', $this->translate('User.companies.label'));
            $workSheet->getStyle('R1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('S1', $this->translate('User.admCompanies.label'));
            $workSheet->getStyle('S1')
                ->getFont()
                ->setBold(true);
            $workSheet->setCellValue('T1', $this->translate('User.lastValidity.label'));
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

            $i = 1;

            foreach ($users as $user) {
                $i++;
                $workSheet->setCellValue('A' . $i, $user->getLastName(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('B' . $i, $user->getFirstName(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('C' . $i, '(' . $this->translate('User.sexe.' . $user->getSexe()) . ')', \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('D' . $i, $user->getUsername(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('E' . $i, $user->getEmail(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('F' . $i, $this->translate('User.lockout.' . $user->getLockout()), \PHPExcel_Cell_DataType::TYPE_STRING2);
                if (null != $user->getBirthday()) {
                    $workSheet->setCellValue('G' . $i, \PHPExcel_Shared_Date::PHPToExcel($user->getBirthday()), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                } else {
                    $workSheet->setCellValue('G' . $i, null, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                }
                $workSheet->getStyle('G' . $i)
                    ->getNumberFormat()
                    ->setFormatCode('dd/mm/yyyy');
                $workSheet->setCellValue('H' . $i, $user->getStreetNum(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('I' . $i, \html_entity_decode(\strip_tags($user->getAddress())), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('J' . $i, \html_entity_decode(\strip_tags($user->getAddress2())), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('K' . $i, $user->getTown(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('L' . $i, $user->getZipCode(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                if (null != $user->getCountry()) {
                    $workSheet->setCellValue('M' . $i, Intl::getRegionBundle()->getCountryName($user->getCountry()), \PHPExcel_Cell_DataType::TYPE_STRING2);
                }
                $workSheet->setCellValue('N' . $i, ' ' . $user->getPhone(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->setCellValue('O' . $i, ' ' . $user->getMobile(), \PHPExcel_Cell_DataType::TYPE_STRING2);
                if (null != $user->getDtCrea()) {
                    $workSheet->setCellValue('P' . $i, \PHPExcel_Shared_Date::PHPToExcel($user->getDtCrea()), \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                } else {
                    $workSheet->setCellValue('P' . $i, null, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                }
                $workSheet->getStyle('P' . $i)
                    ->getNumberFormat()
                    ->setFormatCode('dd/mm/yyyy hh:MM:ss');

                $roles = '';
                $ln = 0;
                foreach ($user->getUserRoles() as $role) {
                    if ($ln != 0) {
                        $roles .= "\n";
                    }
                    $roles .= $this->translate($role->getName());
                    $ln++;
                }
                $workSheet->setCellValue('Q' . $i, $roles, \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->getStyle('Q' . $i)
                    ->getAlignment()
                    ->setWrapText(true);

                $companies = '';
                $ln = 0;
                foreach ($user->getCompanies() as $company) {
                    if ($ln != 0) {
                        $companies .= "\n";
                    }
                    $companies .= $company->getCorporateName();
                    $ln++;
                }
                $workSheet->setCellValue('R' . $i, $companies, \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->getStyle('R' . $i)
                    ->getAlignment()
                    ->setWrapText(true);

                $admCompanies = '';
                $ln = 0;
                foreach ($user->getAdmCompanies() as $company) {
                    if ($ln != 0) {
                        $admCompanies .= "\n";
                    }
                    $admCompanies .= $company->getCorporateName();
                    $ln++;
                }
                $workSheet->setCellValue('S' . $i, $admCompanies, \PHPExcel_Cell_DataType::TYPE_STRING2);
                $workSheet->getStyle('S' . $i)
                    ->getAlignment()
                    ->setWrapText(true);
                if (null != $user->getLastValidity()) {
                    $workSheet->setCellValue('T' . $i, \PHPExcel_Shared_Date::PHPToExcel($user->getLastValidity()));
                    $format = 'dd/mm/yyyy';

                    $workSheet->getStyle('T' . $i)
                        ->getNumberFormat()
                        ->setFormatCode($format);
                } else {
                    $workSheet->setCellValue('T' . $i, null, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                }

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

            $filename = $this->normalizeString($this->normalize($this->translate('pagetitle.user.list')));
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addGetAction()
    {
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }
        $user = new User();
        $userNewForm = $this->createForm(UserNewTForm::class, $user);
        $this->gvars['user'] = $user;
        $this->gvars['UserNewForm'] = $userNewForm->createView();

        $this->gvars['pagetitle'] = $this->translate('pagetitle.user.add');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.user.add.txt');
        $this->gvars['smenu_active'] = 'add';

        return $this->renderResponse('AcfAdminBundle:User:add.html.twig', $this->gvars);
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
            return $this->redirect($this->generateUrl('_admin_user_addGet'));
        }

        $user = new User();
        $userNewForm = $this->createForm(UserNewTForm::class, $user);
        $this->gvars['user'] = $user;

        $request = $this->getRequest();
        $reqData = $request->request->all();

        if (isset($reqData['UserNewForm'])) {
            $userNewForm->handleRequest($request);
            if ($userNewForm->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($user);
                $em->flush();
                $this->flashMsgSession('success', $this->translate('User.add.success', array(
                    '%user%' => $user->getUsername()
                )));

                return $this->redirect($this->generateUrl('_admin_user_editGet', array(
                    'id' => $user->getId()
                )));
            } else {
                $this->flashMsgSession('error', $this->translate('User.add.failure'));
            }
        }
        $this->gvars['UserNewForm'] = $userNewForm->createView();

        $this->gvars['pagetitle'] = $this->translate('pagetitle.user.add');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.user.add.txt');
        $this->gvars['smenu_active'] = 'add';

        return $this->renderResponse('AcfAdminBundle:User:add.html.twig', $this->gvars);
    }

    /**
     *
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id)
    {
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_user_list');
        }
        $em = $this->getEntityManager();
        try {
            $user = $em->getRepository('AcfDataBundle:User')->find($id);

            if (null == $user) {
                $this->flashMsgSession('warning', $this->translate('User.delete.notfound'));
            } else {
                $em->remove($user);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('User.delete.success', array(
                    '%user%' => $user->getUsername()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('User.delete.failure'));
        }

        return $this->redirect($urlFrom);
    }

    /**
     *
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editGetAction($id)
    {
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_user_list');
        }

        $em = $this->getEntityManager();
        try {
            $user = $em->getRepository('AcfDataBundle:User')->find($id);

            if (null == $user) {
                $this->flashMsgSession('warning', $this->translate('User.edit.notfound'));
            } else {
                $traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($user->getId(), Trace::AE_USER);
                $this->gvars['traces'] = array_reverse($traces);
                $userUpdateEmailForm = $this->createForm(UserUpdateEmailTForm::class, $user);
                $userUpdateLockoutForm = $this->createForm(UserUpdateLockoutTForm::class, $user);
                $userUpdateLastValidityForm = $this->createForm(UserUpdateLastValidityTForm::class, $user);
                $userUpdatePasswordForm = $this->createForm(UserUpdatePasswordTForm::class, $user);
                $userUpdatePreferedLangForm = $this->createForm(UserUpdatePreferedLangTForm::class, $user);
                $userUpdateProfileForm = $this->createForm(UserUpdateProfileTForm::class, $user);
                $userUpdateUserRolesForm = $this->createForm(UserUpdateUserRolesTForm::class, $user);
                $userUploadAvatarForm = $this->createForm(UserUploadAvatarTForm::class, $user);
                $userCropAvatarForm = $this->createForm(UserCropAvatarTForm::class);
                $userUpdateSubcategsForm = $this->createForm(UserUpdateSubcategsTForm::class, $user);

                $this->gvars['user'] = $user;
                $this->gvars['UserUpdateEmailForm'] = $userUpdateEmailForm->createView();
                $this->gvars['UserUpdateLockoutForm'] = $userUpdateLockoutForm->createView();
                $this->gvars['UserUpdateLastValidityForm'] = $userUpdateLastValidityForm->createView();
                $this->gvars['UserUpdatePasswordForm'] = $userUpdatePasswordForm->createView();
                $this->gvars['UserUpdatePreferedLangForm'] = $userUpdatePreferedLangForm->createView();
                $this->gvars['UserUpdateProfileForm'] = $userUpdateProfileForm->createView();
                $this->gvars['UserUpdateSubcategsForm'] = $userUpdateSubcategsForm->createView();
                $this->gvars['UserUpdateUserRolesForm'] = $userUpdateUserRolesForm->createView();
                $this->gvars['UserUploadAvatarForm'] = $userUploadAvatarForm->createView();
                $this->gvars['UserCropAvatarForm'] = $userCropAvatarForm->createView();

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');

                $this->gvars['pagetitle'] = $this->translate('pagetitle.user.edit', array(
                    '%user%' => $user->getUsername()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.user.edit.txt', array(
                    '%user%' => $user->getUsername()
                ));

                return $this->renderResponse('AcfAdminBundle:User:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }

    /**
     *
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editPostAction($id)
    {
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            return $this->redirect($this->generateUrl('_admin_user_list'));
        }

        $em = $this->getEntityManager();
        try {
            $user = $em->getRepository('AcfDataBundle:User')->find($id);

            if (null == $user) {
                $this->flashMsgSession('warning', 'User.edit.notfound');
            } else {
                $traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($user->getId(), Trace::AE_USER);
                $this->gvars['traces'] = array_reverse($traces);
                $userUpdateEmailForm = $this->createForm(UserUpdateEmailTForm::class, $user);
                $userUpdateLastValidityForm = $this->createForm(UserUpdateLastValidityTForm::class, $user);
                $userUpdateLockoutForm = $this->createForm(UserUpdateLockoutTForm::class, $user);
                $userUpdatePasswordForm = $this->createForm(UserUpdatePasswordTForm::class, $user);
                $userUpdatePreferedLangForm = $this->createForm(UserUpdatePreferedLangTForm::class, $user);
                $userUpdateProfileForm = $this->createForm(UserUpdateProfileTForm::class, $user);
                $userUpdateUserRolesForm = $this->createForm(UserUpdateUserRolesTForm::class, $user);
                $userUploadAvatarForm = $this->createForm(UserUploadAvatarTForm::class, $user);
                $userCropAvatarForm = $this->createForm(UserCropAvatarTForm::class);
                $userUpdateSubcategsForm = $this->createForm(UserUpdateSubcategsTForm::class, $user);

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                $cloneUser = clone $user;

                if (isset($reqData['UserUpdateEmailForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $userUpdateEmailForm->handleRequest($request);
                    if ($userUpdateEmailForm->isValid()) {
                        $em->persist($user);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('User.edit.success', array(
                            '%user%' => $user->getUsername()
                        )));

                        $this->traceEntity($cloneUser, $user);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($user);

                        $this->flashMsgSession('error', $this->translate('User.edit.failure', array(
                            '%user%' => $user->getUsername()
                        )));
                    }
                } elseif (isset($reqData['UserUpdateLastValidityForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $userUpdateLastValidityForm->handleRequest($request);
                    if ($userUpdateLastValidityForm->isValid()) {
                        $em->persist($user);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('User.edit.success', array(
                            '%user%' => $user->getUsername()
                        )));

                        $this->traceEntity($cloneUser, $user);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($user);

                        $this->flashMsgSession('error', $this->translate('User.edit.failure', array(
                            '%user%' => $user->getUsername()
                        )));
                    }
                } elseif (isset($reqData['UserUpdateLockoutForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $userUpdateLockoutForm->handleRequest($request);
                    if ($userUpdateLockoutForm->isValid()) {
                        $em->persist($user);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('User.edit.success', array(
                            '%user%' => $user->getUsername()
                        )));

                        $this->traceEntity($cloneUser, $user);

                        $from = $this->getParameter('mail_from');
                        $fromName = $this->getParameter('mail_from_name');
                        $message = \Swift_Message::newInstance()->setFrom($from, $fromName);
                        $message->setTo($user->getEmail(), $user->getFullname());
                        $mvars = array();
                        $mvars['user'] = $user;
                        $mvars['logo'] = $message->embed(\Swift_Image::fromPath($this->getParameter('kernel.root_dir') . '/../web/bundles/acfres/images/logo_acf.jpg'));

                        if ($user->getLockout() == User::LOCKOUT_LOCKED) {
                            $subject = $this->translate('_mail.register.lock.subject', array(), 'messages');
                            $message->setSubject($subject);
                            $message->setBody($this->renderView('AcfSecurityBundle:Mail:user.lock.html.twig', $mvars), 'text/html');
                        } else {
                            $subject = $this->translate('_mail.register.unlock.subject', array(), 'messages');
                            $message->setSubject($subject);
                            $message->setBody($this->renderView('AcfSecurityBundle:Mail:user.unlock.html.twig', $mvars), 'text/html');
                        }
                        $this->sendmail($message);

                        $this->flashMsgSession('success', $this->translate('User.lockout.success', array(
                            '%mail%' => $user->getEmail()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($user);

                        $this->flashMsgSession('error', $this->translate('User.edit.failure', array(
                            '%user%' => $user->getUsername()
                        )));
                    }
                } elseif (isset($reqData['UserUpdatePasswordForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $userUpdatePasswordForm->handleRequest($request);
                    if ($userUpdatePasswordForm->isValid()) {
                        $em->persist($user);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('User.edit.success', array(
                            '%user%' => $user->getUsername()
                        )));

                        $this->traceEntity($cloneUser, $user);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($user);

                        $this->flashMsgSession('error', $this->translate('User.edit.failure', array(
                            '%user%' => $user->getUsername()
                        )));
                    }
                } elseif (isset($reqData['UserUpdatePreferedLangForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $userUpdatePreferedLangForm->handleRequest($request);
                    if ($userUpdatePreferedLangForm->isValid()) {
                        $em->persist($user);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('User.edit.success', array(
                            '%user%' => $user->getUsername()
                        )));

                        $this->traceEntity($cloneUser, $user);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($user);

                        $this->flashMsgSession('error', $this->translate('User.edit.failure', array(
                            '%user%' => $user->getUsername()
                        )));
                    }
                } elseif (isset($reqData['UserUpdateProfileForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $userUpdateProfileForm->handleRequest($request);
                    if ($userUpdateProfileForm->isValid()) {
                        $em->persist($user);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('User.edit.success', array(
                            '%user%' => $user->getUsername()
                        )));

                        $this->traceEntity($cloneUser, $user);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($user);

                        $this->flashMsgSession('error', $this->translate('User.edit.failure', array(
                            '%user%' => $user->getUsername()
                        )));
                    }
                } elseif (isset($reqData['UserUpdateSubcategsForm'])) {
                    $this->gvars['tabActive'] = 3;
                    $this->getSession()->set('tabActive', 3);
                    $userUpdateSubcategsForm->handleRequest($request);
                    if ($userUpdateSubcategsForm->isValid()) {
                        $user->setSubcategs($userUpdateSubcategsForm['subcategs']->getData());
                        $em->persist($user);

                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('User.edit.success', array(
                            '%user%' => $user->getUsername()
                        )));

                        // $this->traceEntity($cloneUser, $user);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($user);

                        $this->flashMsgSession('error', $this->translate('User.edit.failure', array(
                            '%user%' => $user->getUsername()
                        )));
                    }
                } elseif (isset($reqData['UserUpdateUserRolesForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $userUpdateUserRolesForm->handleRequest($request);
                    if ($userUpdateUserRolesForm->isValid()) {
                        $em->persist($user);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('User.edit.success', array(
                            '%user%' => $user->getUsername()
                        )));

                        $this->traceEntity($cloneUser, $user);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($user);

                        $this->flashMsgSession('error', $this->translate('User.edit.failure', array(
                            '%user%' => $user->getUsername()
                        )));
                    }
                } elseif (isset($reqData['UserUploadAvatarForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $userUploadAvatarForm->handleRequest($request);
                    if ($userUploadAvatarForm->isValid()) {
                        $filename = $user->getUsername() . '_' . uniqid() . '.' . $userUploadAvatarForm['avatar']->getData()->guessExtension();
                        $userUploadAvatarForm['avatar']->getData()->move($this->getParameter('adapter_tmp_files'), $filename);
                        $this->gvars['tmp_avatar'] = $filename;
                        $userCropAvatarForm = $this->createForm(UserCropAvatarTForm::class, null, array(
                            'filename' => $filename
                        ));
                        $this->gvars['UserCropAvatarForm'] = $userCropAvatarForm->createView();
                        $this->gvars['user'] = $user;

                        return $this->renderResponse('AcfAdminBundle:User:resize_avatar.html.twig', $this->gvars);
                    } else {
                        $this->gvars['UserUploadAvatarForm'] = $userUploadAvatarForm->createView();

                        return $this->renderResponse('AcfAdminBundle:User:resize_avatar_error.html.twig', $this->gvars);
                    }
                } elseif (isset($reqData['UserCropAvatarForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $userCropAvatarForm->handleRequest($request);
                    if ($userCropAvatarForm->isValid()) {
                        $filename = $userCropAvatarForm['avatar_tmp']->getData();
                        $path = $this->getParameter('adapter_tmp_files') . '/' . $filename;
                        $x1 = $userCropAvatarForm['x1']->getData();
                        $y1 = $userCropAvatarForm['y1']->getData();
                        $w = $userCropAvatarForm['w']->getData();
                        $h = $userCropAvatarForm['h']->getData();

                        $imagine = new Imagine();
                        $image = $imagine->open($path);
                        /*
                         * $widh = $image->getSize()->getWidth();
                         * $mult = 1;
                         * if ($widh > 400) {
                         * $mult = $widh / 400;
                         * }
                         * if ($widh < 130) {
                         * $mult = $widh / 130;
                         * }
                         * $x1 = round($x1 * $mult);
                         * $y1 = round($y1 * $mult);
                         * $w = round($w * $mult);
                         * $h = round($h * $mult);
                         */

                        $firstpoint = new Point($x1, $y1);
                        $selbox = new Box($w, $h);
                        $lastbox = new Box(130, 130);
                        $mode = ImageInterface::THUMBNAIL_OUTBOUND;

                        $image->crop($firstpoint, $selbox)
                            ->thumbnail($lastbox, $mode)
                            ->save($path);

                        $file = new File($path);
                        $avatarDir = $this->getParameter('kernel.root_dir') . '/../web/res/avatars';
                        $file->move($avatarDir, $filename);

                        $user->setAvatar($filename);

                        $em->persist($user);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('User.edit.success', array(
                            '%user%' => $user->getUsername()
                        )));

                        $this->traceEntity($cloneUser, $user);

                        $this->getSession()->set('tabActive', 1);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($user);

                        $this->flashMsgSession('error', $this->translate('User.edit.failure', array(
                            '%user%' => $user->getUsername()
                        )));
                    }
                }

                $this->gvars['user'] = $user;
                $this->gvars['UserUpdateEmailForm'] = $userUpdateEmailForm->createView();
                $this->gvars['UserUpdateLastValidityForm'] = $userUpdateLastValidityForm->createView();
                $this->gvars['UserUpdateLockoutForm'] = $userUpdateLockoutForm->createView();
                $this->gvars['UserUpdatePasswordForm'] = $userUpdatePasswordForm->createView();
                $this->gvars['UserUpdatePreferedLangForm'] = $userUpdatePreferedLangForm->createView();
                $this->gvars['UserUpdateProfileForm'] = $userUpdateProfileForm->createView();
                $this->gvars['UserUpdateUserRolesForm'] = $userUpdateUserRolesForm->createView();
                $this->gvars['UserUploadAvatarForm'] = $userUploadAvatarForm->createView();
                $this->gvars['UserCropAvatarForm'] = $userCropAvatarForm->createView();
                $this->gvars['UserUpdateSubcategsForm'] = $userUpdateSubcategsForm->createView();

                $this->gvars['pagetitle'] = $this->translate('pagetitle.user.edit', array(
                    '%user%' => $user->getUsername()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.user.edit.txt', array(
                    '%user%' => $user->getUsername()
                ));

                return $this->renderResponse('AcfAdminBundle:User:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }

    protected function traceEntity(User $cloneUser, User $user)
    {
        $curUser = $this->getSecurityTokenStorage()
            ->getToken()
            ->getUser();
        $trace = new Trace();
        $trace->setActionId($user->getId());
        $trace->setActionType(Trace::AT_UPDATE);
        $trace->setUserId($curUser->getId());
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

        $trace->setActionEntity(Trace::AE_USER);

        $msg = '';

        if ($cloneUser->getSexe() != $user->getSexe()) {
            $msg .= '<tr><td>' . $this->translate('User.sexe.label') . '</td><td>';
            if ($cloneUser->getSexe() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('User.sexe.' . $cloneUser->getSexe());
            }
            $msg .= '</td><td>';
            if ($user->getSexe() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('User.sexe.' . $user->getSexe());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneUser->getFirstName() != $user->getFirstName()) {
            $msg .= '<tr><td>' . $this->translate('User.firstName.label') . '</td><td>';
            if ($cloneUser->getFirstName() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneUser->getFirstName();
            }
            $msg .= '</td><td>';
            if ($user->getFirstName() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $user->getFirstName();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneUser->getLastName() != $user->getLastName()) {
            $msg .= '<tr><td>' . $this->translate('User.lastName.label') . '</td><td>';
            if ($cloneUser->getLastName() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneUser->getLastName();
            }
            $msg .= '</td><td>';
            if ($user->getUsername() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $user->getLastName();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneUser->getBirthday() != $user->getBirthday()) {
            $msg .= '<tr><td>' . $this->translate('User.birthday.label') . '</td><td>';
            if ($cloneUser->getBirthday() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneUser->getBirthday()->format('Y-m-d');
            }
            $msg .= '</td><td>';
            if ($user->getBirthday() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $user->getBirthday()->format('Y-m-d');
            }
            $msg .= '</td></tr>';
        }

        if ($cloneUser->getStreetNum() != $user->getStreetNum()) {
            $msg .= '<tr><td>' . $this->translate('User.streetNum.label') . '</td><td>';
            if ($cloneUser->getStreetNum() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneUser->getStreetNum();
            }
            $msg .= '</td><td>';
            if ($user->getStreetNum() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $user->getStreetNum();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneUser->getAddress() != $user->getAddress()) {
            $msg .= '<tr><td>' . $this->translate('User.address.label') . '</td><td>';
            if ($cloneUser->getAddress() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneUser->getAddress();
            }
            $msg .= '</td><td>';
            if ($user->getAddress() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $user->getAddress();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneUser->getAddress2() != $user->getAddress2()) {
            $msg .= '<tr><td>' . $this->translate('User.address2.label') . '</td><td>';
            if ($cloneUser->getAddress2() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneUser->getAddress2();
            }
            $msg .= '</td><td>';
            if ($user->getAddress2() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $user->getAddress2();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneUser->getTown() != $user->getTown()) {
            $msg .= '<tr><td>' . $this->translate('User.town.label') . '</td><td>';
            if ($cloneUser->getTown() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneUser->getTown();
            }
            $msg .= '</td><td>';
            if ($user->getTown() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $user->getTown();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneUser->getZipCode() != $user->getZipCode()) {
            $msg .= '<tr><td>' . $this->translate('User.zipCode.label') . '</td><td>';
            if ($cloneUser->getZipCode() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneUser->getZipCode();
            }
            $msg .= '</td><td>';
            if ($user->getZipCode() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $user->getZipCode();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneUser->getCountry() != $user->getCountry()) {
            $msg .= '<tr><td>' . $this->translate('User.country.label') . '</td><td>';
            if ($cloneUser->getCountry() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneUser->getCountry();
            }
            $msg .= '</td><td>';
            if ($user->getCountry() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $user->getCountry();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneUser->getPhone() != $user->getPhone()) {
            $msg .= '<tr><td>' . $this->translate('User.phone.label') . '</td><td>';
            if ($cloneUser->getPhone() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneUser->getPhone();
            }
            $msg .= '</td><td>';
            if ($user->getPhone() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $user->getPhone();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneUser->getMobile() != $user->getMobile()) {
            $msg .= '<tr><td>' . $this->translate('User.mobile.label') . '</td><td>';
            if ($cloneUser->getMobile() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneUser->getMobile();
            }
            $msg .= '</td><td>';
            if ($user->getMobile() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $user->getMobile();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneUser->getPreferedLang() != $user->getPreferedLang()) {
            $msg .= '<tr><td>' . $this->translate('User.preferedLang.label') . '</td><td>';
            if ($cloneUser->getPreferedLang() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneUser->getPreferedLang()->getName();
            }
            $msg .= '</td><td>';
            if ($user->getPreferedLang() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $user->getPreferedLang()->getName();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneUser->getEmail() != $user->getEmail()) {
            $msg .= '<tr><td>' . $this->translate('User.email.label') . '</td><td>';
            if ($cloneUser->getEmail() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneUser->getEmail();
            }
            $msg .= '</td><td>';
            if ($user->getEmail() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $user->getEmail();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneUser->getLastValidity() != $user->getLastValidity()) {
            $msg .= '<tr><td>' . $this->translate('User.lastValidity.label') . '</td><td>';
            if ($cloneUser->getLastValidity() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneUser->getLastValidity()->format('Y-m-d');
            }
            $msg .= '</td><td>';
            if ($user->getLastValidity() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $user->getLastValidity()->format('Y-m-d');
            }
            $msg .= '</td></tr>';
        }

        if ($cloneUser->getLockout() != $user->getLockout()) {
            $msg .= '<tr><td>' . $this->translate('User.lockout.label') . '</td><td>';
            if ($cloneUser->getLockout() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('User.lockout.' . $cloneUser->getLockout());
            }
            $msg .= '</td><td>';
            if ($user->getLockout() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $this->translate('User.lockout.' . $user->getLockout());
            }
            $msg .= '</td></tr>';
        }

        if ($cloneUser->getClearPassword() != $user->getClearPassword()) {
            $msg .= '<tr><td>' . $this->translate('User.password.label') . '</td><td>';
            for ($i = 0; $i < \strlen($cloneUser->getClearPassword()); $i++) {
                $msg .= '*';
            }
            $msg .= '</td><td>';
            for ($i = 0; $i < \strlen($user->getClearPassword()); $i++) {
                $msg .= '*';
            }
            $msg .= '</td></tr>';
        }

        if ($cloneUser->getAvatar() != $user->getAvatar()) {
            $msg .= '<tr><td>' . $this->translate('User.avatar.label') . '</td><td>';
            if ($cloneUser->getAvatar() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= '<img class="img-responsive img-thumbnail" alt="" src="/res/avatars/' . $cloneUser->getAvatar() . '">';
            }
            $msg .= '</td><td>';
            if ($user->getAvatar() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= '<img class="img-responsive img-thumbnail" alt="" src="/res/avatars/' . $user->getAvatar() . '">';
            }
            $msg .= '</td></tr>';
        }

        if (\count(\array_diff($user->getUserRoles()->toArray(), $cloneUser->getUserRoles()->toArray())) != 0 || \count(\array_diff($cloneUser->getUserRoles()->toArray(), $user->getUserRoles()->toArray())) != 0) {
            $msg .= '<tr><td>' . $this->translate('User.userRoles.label') . '</td><td>';
            if (\count($cloneUser->getUserRoles()) == 0) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= '<ul>';
                foreach ($cloneUser->getUserRoles() as $role) {
                    $msg .= '<li>' . $role->getName() . '</li>';
                }
                $msg .= '<ul>';
            }
            $msg .= '</td><td>';
            if (\count($user->getUserRoles()) == 0) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= '<ul>';
                foreach ($user->getUserRoles() as $role) {
                    $msg .= '<li>' . $role->getName() . '</li>';
                }
                $msg .= '<ul>';
            }
            $msg .= '</td></tr>';
        }

        if ($msg != '') {

            $msg = $tableBegin . $msg . $tableEnd;

            $trace->setMsg($this->translate('User.traceEdit', array(
                '%user%' => $user->getFullName()
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
