<?php
namespace Acf\SecurityBundle\Controller;

use Acf\DataBundle\Entity\Trace;
use Acf\DataBundle\Entity\User;
use Acf\SecurityBundle\Form\CropAvatarTForm as UserCropAvatarTForm;
use Acf\SecurityBundle\Form\UploadAvatarTForm as UserUploadAvatarTForm;
use Acf\SecurityBundle\Form\UpdateEmailTForm as UserUpdateEmailTForm;
use Acf\SecurityBundle\Form\UpdatePasswordTForm as UserUpdatePasswordTForm;
// use Acf\SecurityBundle\Form\UpdatePreferedLangTForm as UserUpdatePreferedLangTForm;
use Acf\SecurityBundle\Form\UpdateProfileTForm as UserUpdateProfileTForm;
use Imagine\Imagick\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Image\ImageInterface;
use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\Encoder\Pbkdf2PasswordEncoder;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class ProfileController extends BaseController
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
        $this->gvars['menu_active'] = 'profile';
    }

    /**
     *
     * @return Response
     */
    public function myProfileAction()
    {
        $sc = $this->getSecurityTokenStorage();
        $user = $sc->getToken()->getUser();

        $userUpdateProfileForm = $this->createForm(UserUpdateProfileTForm::class, $user);
        $userUpdateEmailForm = $this->createForm(UserUpdateEmailTForm::class, $user);
        $userUpdatePasswordForm = $this->createForm(UserUpdatePasswordTForm::class, $user);
        $userUploadAvatarForm = $this->createForm(UserUploadAvatarTForm::class, $user);
        $userCropAvatarForm = $this->createForm(UserCropAvatarTForm::class);

        $this->gvars['user'] = $user;
        $this->gvars['UserUpdateProfileForm'] = $userUpdateProfileForm->createView();
        $this->gvars['UserUpdateEmailForm'] = $userUpdateEmailForm->createView();
        $this->gvars['UserUpdatePasswordForm'] = $userUpdatePasswordForm->createView();
        $this->gvars['UserUploadAvatarForm'] = $userUploadAvatarForm->createView();
        $this->gvars['UserCropAvatarForm'] = $userCropAvatarForm->createView();

        $this->gvars['pagetitle'] = $this->translate('pagetitle.profile');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.profile.txt');
        $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
        $this->getSession()->remove('tabActive');

        return $this->renderResponse('AcfSecurityBundle:Profile:profile.default.html.twig', $this->gvars);
    }

    /**
     *
     * @return RedirectResponse|Response
     */
    public function myProfilePostAction()
    {
        $sc = $this->getSecurityTokenStorage();
        $user = $sc->getToken()->getUser();
        $oldDbpass = $user->getPassword();

        $em = $this->getEntityManager();

        $userUpdateProfileForm = $this->createForm(UserUpdateProfileTForm::class, $user);
        // $userUpdatePreferedLangForm = $this->createForm(UserUpdatePreferedLangTForm::class, $user);
        $userUpdateEmailForm = $this->createForm(UserUpdateEmailTForm::class, $user);
        $userUpdatePasswordForm = $this->createForm(UserUpdatePasswordTForm::class, $user);
        $userUploadAvatarForm = $this->createForm(UserUploadAvatarTForm::class, $user);
        $userCropAvatarForm = $this->createForm(UserCropAvatarTForm::class);

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
                $this->flashMsgSession('success', $this->translate('Profile.edit.success'));

                $this->traceEntity($cloneUser, $user);

                return $this->redirect($this->generateUrl('_security_profile'));
            } else {
                $em->refresh($user);

                $this->flashMsgSession('error', $this->translate('Profile.edit.failure'));
            }
        } elseif (isset($reqData['UserUpdatePasswordForm'])) {
            $this->gvars['tabActive'] = 2;
            $this->getSession()->set('tabActive', 2);
            $userUpdatePasswordForm->handleRequest($request);
            if ($userUpdatePasswordForm->isValid()) {
                $oldPassword = $userUpdatePasswordForm['oldPassword']->getData();
                $encoder = new Pbkdf2PasswordEncoder('sha512', true, 1000);
                $oldpassEncoded = $encoder->encodePassword($oldPassword, $user->getSalt());
                if ($oldpassEncoded != $oldDbpass) {
                    $formError = new FormError($this->translate('User.oldPassword.incorrect', array(), 'validators'));

                    $userUpdatePasswordForm['oldPassword']->addError($formError);
                    $this->flashMsgSession('error', $this->translate('Profile.edit.failure'));
                } else {
                    $em->persist($user);
                    $em->flush();
                    $this->flashMsgSession('success', $this->translate('Profile.edit.success'));

                    $this->traceEntity($cloneUser, $user);

                    return $this->redirect($this->generateUrl('_security_profile'));
                }
            } else {
                $em->refresh($user);

                $this->flashMsgSession('error', $this->translate('Profile.edit.failure'));
            }
        } elseif (isset($reqData['UserUpdateProfileForm'])) {
            $this->gvars['tabActive'] = 2;
            $this->getSession()->set('tabActive', 2);
            $userUpdateProfileForm->handleRequest($request);
            if ($userUpdateProfileForm->isValid()) {
                $em->persist($user);
                $em->flush();
                $this->flashMsgSession('success', $this->translate('Profile.edit.success'));

                $this->traceEntity($cloneUser, $user);

                return $this->redirect($this->generateUrl('_security_profile'));
            } else {
                $em->refresh($user);

                $this->flashMsgSession('error', $this->translate('Profile.edit.failure'));
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

                return $this->renderResponse('AcfSecurityBundle:Profile:resize_avatar.html.twig', $this->gvars);
            } else {
                $this->gvars['UserUploadAvatarForm'] = $userUploadAvatarForm->createView();

                return $this->renderResponse('AcfSecurityBundle:Profile:resize_avatar_error.html.twig', $this->gvars);
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

                $this->traceEntity($cloneUser, $user);

                $em->persist($user);
                $em->flush();
                $this->flashMsgSession('success', $this->translate('Profile.edit.success'));

                $this->getSession()->set('tabActive', 1);

                return $this->redirect($this->generateUrl('_security_profile'));
            } else {
                $em->refresh($user);

                $this->flashMsgSession('error', $this->translate('Profile.edit.failure'));
            }
        }

        $this->gvars['user'] = $user;
        $this->gvars['UserUpdateProfileForm'] = $userUpdateProfileForm->createView();
        $this->gvars['UserUpdateEmailForm'] = $userUpdateEmailForm->createView();
        $this->gvars['UserUpdatePasswordForm'] = $userUpdatePasswordForm->createView();
        $this->gvars['UserUploadAvatarForm'] = $userUploadAvatarForm->createView();
        $this->gvars['UserCropAvatarForm'] = $userCropAvatarForm->createView();

        $this->gvars['pagetitle'] = $this->translate('pagetitle.profile');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.profile.txt');

        return $this->renderResponse('AcfSecurityBundle:Profile:profile.default.html.twig', $this->gvars);
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

        if ($cloneUser->getLockout() != $user->getLockout()) {
            $msg .= '<tr><td>' . $this->translate('User.sexe.label') . '</td><td>';
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
}
