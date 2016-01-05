<?php
namespace Acf\AdminBundle\Controller;

use Acf\DataBundle\Entity\User;
use Acf\DataBundle\Entity\Trace;
use Acf\AdminBundle\Form\User\CropAvatarTForm as UserCropAvatarTForm;
use Acf\AdminBundle\Form\User\NewTForm as UserNewTForm;
use Acf\AdminBundle\Form\User\UpdateEmailTForm as UserUpdateEmailTForm;
use Acf\AdminBundle\Form\User\UpdateLockoutTForm as UserUpdateLockoutTForm;
use Acf\AdminBundle\Form\User\UpdatePasswordTForm as UserUpdatePasswordTForm;
use Acf\AdminBundle\Form\User\UpdatePreferedLangTForm as UserUpdatePreferedLangTForm;
use Acf\AdminBundle\Form\User\UpdateProfileTForm as UserUpdateProfileTForm;
use Acf\AdminBundle\Form\User\UpdateUserRolesTForm as UserUpdateUserRolesTForm;
use Acf\AdminBundle\Form\User\UploadAvatarTForm as UserUploadAvatarTForm;
use Imagine\Imagick\Imagine;
use Imagine\Image\Point;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @author sasedev <seif.salah@gmail.com>
 *
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

	public function listAction()
	{
		if (! $this->hasRole('ROLE_SUPERADMIN')) {
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

	public function addGetAction()
	{
		if (! $this->hasRole('ROLE_SUPERADMIN')) {
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

	public function addPostAction()
	{
		if (! $this->hasRole('ROLE_SUPERADMIN')) {
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
				$this->flashMsgSession(
					'success',
					$this->translate('User.add.success', array('%user%' => $user->getUsername()))
				);

				return $this->redirect(
					$this->generateUrl('_admin_user_editGet', array('id' => $user->getId()))
				);
			} else {
				$this->flashMsgSession(
					'error',
					$this->translate('User.add.failure')
				);
			}
		}
		$this->gvars['UserNewForm'] = $userNewForm->createView();

		$this->gvars['pagetitle'] = $this->translate('pagetitle.user.add');
		$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.user.add.txt');
		$this->gvars['smenu_active'] = 'add';

		return $this->renderResponse('AcfAdminBundle:User:add.html.twig', $this->gvars);
	}

	public function deleteAction($id)
	{
		if (! $this->hasRole('ROLE_SUPERADMIN')) {
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

				$this->flashMsgSession(
					'success',
					$this->translate('User.delete.success', array('%user%' => $user->getUsername()))
				);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());

			$this->flashMsgSession('error', $this->translate('User.delete.failure'));
		}

		return $this->redirect($urlFrom);

	}

	public function editGetAction($id)
	{
		if (! $this->hasRole('ROLE_SUPERADMIN')) {
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
				$userUpdatePasswordForm = $this->createForm(UserUpdatePasswordTForm::class, $user);
				$userUpdatePreferedLangForm = $this->createForm(UserUpdatePreferedLangTForm::class, $user);
				$userUpdateProfileForm = $this->createForm(UserUpdateProfileTForm::class, $user);
				$userUpdateUserRolesForm = $this->createForm(UserUpdateUserRolesTForm::class, $user);
				$userUploadAvatarForm = $this->createForm(UserUploadAvatarTForm::class, $user);
				$userCropAvatarForm = $this->createForm(UserCropAvatarTForm::class);

				$this->gvars['user'] = $user;
				$this->gvars['UserUpdateEmailForm'] = $userUpdateEmailForm->createView();
				$this->gvars['UserUpdateLockoutForm'] = $userUpdateLockoutForm->createView();
				$this->gvars['UserUpdatePasswordForm'] = $userUpdatePasswordForm->createView();
				$this->gvars['UserUpdatePreferedLangForm'] = $userUpdatePreferedLangForm->createView();
				$this->gvars['UserUpdateProfileForm'] = $userUpdateProfileForm->createView();
				$this->gvars['UserUpdateUserRolesForm'] = $userUpdateUserRolesForm->createView();
				$this->gvars['UserUploadAvatarForm'] = $userUploadAvatarForm->createView();
				$this->gvars['UserCropAvatarForm'] = $userCropAvatarForm->createView();

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
				$this->getSession()->remove('tabActive');



				$this->gvars['pagetitle'] = $this->translate('pagetitle.user.edit', array('%user%' => $user->getUsername()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.user.edit.txt', array('%user%' => $user->getUsername()));

				return $this->renderResponse('AcfAdminBundle:User:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());
		}

		return $this->redirect($urlFrom);
	}


	public function editPostAction($id)
	{
		if (! $this->hasRole('ROLE_SUPERADMIN')) {
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
				$userUpdateLockoutForm = $this->createForm(UserUpdateLockoutTForm::class, $user);
				$userUpdatePasswordForm = $this->createForm(UserUpdatePasswordTForm::class, $user);
				$userUpdatePreferedLangForm = $this->createForm(UserUpdatePreferedLangTForm::class, $user);
				$userUpdateProfileForm = $this->createForm(UserUpdateProfileTForm::class, $user);
				$userUpdateUserRolesForm = $this->createForm(UserUpdateUserRolesTForm::class, $user);
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
						$this->flashMsgSession(
							'success',
							$this->translate('User.edit.success', array('%user%' => $user->getUsername()))
						);

						$this->traceEntity($cloneUser, $user);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($user);

						$this->flashMsgSession(
							'error',
							$this->translate('User.edit.failure', array('%user%' => $user->getUsername()))
						);
					}
				} elseif (isset($reqData['UserUpdateLockoutForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$userUpdateLockoutForm->handleRequest($request);
					if ($userUpdateLockoutForm->isValid()) {
						$em->persist($user);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('User.edit.success', array('%user%' => $user->getUsername()))
						);

						$this->traceEntity($cloneUser, $user);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($user);

						$this->flashMsgSession(
							'error',
							$this->translate('User.edit.failure', array('%user%' => $user->getUsername()))
						);
					}
				} elseif (isset($reqData['UserUpdatePasswordForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$userUpdatePasswordForm->handleRequest($request);
					if ($userUpdatePasswordForm->isValid()) {
						$em->persist($user);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('User.edit.success', array('%user%' => $user->getUsername()))
						);

						$this->traceEntity($cloneUser, $user);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($user);

						$this->flashMsgSession(
							'error',
							$this->translate('User.edit.failure', array('%user%' => $user->getUsername()))
						);
					}
				} elseif (isset($reqData['UserUpdatePreferedLangForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$userUpdatePreferedLangForm->handleRequest($request);
					if ($userUpdatePreferedLangForm->isValid()) {
						$em->persist($user);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('User.edit.success', array('%user%' => $user->getUsername()))
						);

						$this->traceEntity($cloneUser, $user);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($user);

						$this->flashMsgSession(
							'error',
							$this->translate('User.edit.failure', array('%user%' => $user->getUsername()))
						);
					}
				} elseif (isset($reqData['UserUpdateProfileForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$userUpdateProfileForm->handleRequest($request);
					if ($userUpdateProfileForm->isValid()) {
						$em->persist($user);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('User.edit.success', array('%user%' => $user->getUsername()))
						);

						$this->traceEntity($cloneUser, $user);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($user);

						$this->flashMsgSession(
							'error',
							$this->translate('User.edit.failure', array('%user%' => $user->getUsername()))
						);
					}
				} elseif (isset($reqData['UserUpdateUserRolesForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$userUpdateUserRolesForm->handleRequest($request);
					if ($userUpdateUserRolesForm->isValid()) {
						$em->persist($user);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('User.edit.success', array('%user%' => $user->getUsername()))
						);

						$this->traceEntity($cloneUser, $user);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($user);

						$this->flashMsgSession(
							'error',
							$this->translate('User.edit.failure', array('%user%' => $user->getUsername()))
						);
					}
				} elseif (isset($reqData['UserUploadAvatarForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$userUploadAvatarForm->handleRequest($request);
					if ($userUploadAvatarForm->isValid()) {
						$filename = $user->getUsername() . "_" . uniqid() . '.' .$userUploadAvatarForm['avatar']->getData()
						->guessExtension();
						$userUploadAvatarForm['avatar']->getData()->move($this->getParameter('adapter_tmp_files'), $filename);
						$this->gvars['tmp_avatar'] = $filename;
						$userCropAvatarForm = $this->createForm(UserCropAvatarTForm::class, null, array('filename' => $filename));
						$this->gvars['UserCropAvatarForm'] = $userCropAvatarForm->createView();
						$this->gvars['user'] = $user;

						return $this->renderResponse('AcfAdminBundle:User:resize_avatar.html.twig', $this->gvars);
					} else {
						$this->gvars['UserUploadAvatarForm'] = $userUploadAvatarForm->createView();

						return $this
						->renderResponse('AcfAdminBundle:User:resize_avatar_error.html.twig', $this->gvars);
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
						$widh = $image->getSize()->getWidth();
						$mult = 1;
						if ($widh > 400) {
							$mult = $widh / 400;
						}
						if ($widh < 130) {
							$mult = $widh / 130;
						}
						$x1 = round($x1 * $mult);
						$y1 = round($y1 * $mult);
						$w = round($w * $mult);
						$h = round($h * $mult);*/

						$firstpoint = new Point($x1, $y1);
						$selbox = new Box($w, $h);
						$lastbox = new Box(130, 130);
						$mode = ImageInterface::THUMBNAIL_OUTBOUND;

						$image->crop($firstpoint, $selbox)->thumbnail($lastbox, $mode)->save($path);

						$file = new File($path);
						$avatarDir = $this->getParameter('kernel.root_dir').'/../web/res/avatars';
						$file->move($avatarDir, $filename);

						$user->setAvatar($filename);

						$em->persist($user);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('User.edit.success', array('%user%' => $user->getUsername()))
						);

						$this->traceEntity($cloneUser, $user);

						$this->getSession()->set('tabActive', 1);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($user);

						$this->flashMsgSession(
							'error',
							$this->translate('User.edit.failure', array('%user%' => $user->getUsername()))
						);
					}
				}

				$this->gvars['user'] = $user;
				$this->gvars['UserUpdateEmailForm'] = $userUpdateEmailForm->createView();
				$this->gvars['UserUpdateLockoutForm'] = $userUpdateLockoutForm->createView();
				$this->gvars['UserUpdatePasswordForm'] = $userUpdatePasswordForm->createView();
				$this->gvars['UserUpdatePreferedLangForm'] = $userUpdatePreferedLangForm->createView();
				$this->gvars['UserUpdateProfileForm'] = $userUpdateProfileForm->createView();
				$this->gvars['UserUpdateUserRolesForm'] = $userUpdateUserRolesForm->createView();
				$this->gvars['UserUploadAvatarForm'] = $userUploadAvatarForm->createView();
				$this->gvars['UserCropAvatarForm'] = $userCropAvatarForm->createView();

				$this->gvars['pagetitle'] = $this->translate('pagetitle.user.edit', array('%user%' => $user->getUsername()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.user.edit.txt', array('%user%' => $user->getUsername()));

				return $this->renderResponse('AcfAdminBundle:User:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());
		}

		return $this->redirect($urlFrom);

	}

	protected function traceEntity(User $cloneUser, User $user) {

		$curUser = $this->getSecurityTokenStorage()->getToken()->getUser();
		$trace = new Trace();
		$trace->setActionId($user->getId());
		$trace->setActionType(Trace::AT_UPDATE);
		$trace->setUserId($curUser->getId());
		$trace->setUserFullname($curUser->getFullName());
		if (! $this->hasRole('ROLE_SUPERADMIN')) {
			if (! $this->hasRole('ROLE_ADMIN')) {
				$trace->setUserType(Trace::UT_CLIENT);
			} else {
				$trace->setUserType(Trace::UT_ADMIN);
			}

		} else {
			$trace->setUserType(Trace::UT_SUPERADMIN);
		}



		$table_begin = ': <br><table class="table table-bordered table-condensed table-hover table-striped">';
		$table_begin .= '<thead><tr><th class="text-left">'.$this->translate('Entity.field').'</th>';
		$table_begin .= '<th class="text-left">'.$this->translate('Entity.oldVal').'</th>';
		$table_begin .= '<th class="text-left">'.$this->translate('Entity.newVal').'</th></tr></thead><tbody>';

		$table_end = '</tbody></table>';

		$trace->setActionEntity(Trace::AE_USER);

		$msg = "";

		if ($cloneUser->getSexe() != $user->getSexe()) {
			$msg .= "<tr><td>".$this->translate('User.sexe.label').'</td><td>';
			if ($cloneUser->getSexe() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $this->translate('User.sexe.'.$cloneUser->getSexe());
			}
			$msg .= "</td><td>";
			if ($user->getSexe() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $this->translate('User.sexe.'.$user->getSexe());
			}
			$msg .= "</td></tr>";
		}

		if ($cloneUser->getFirstName() != $user->getFirstName()) {
			$msg .= "<tr><td>".$this->translate('User.firstName.label').'</td><td>';
			if ($cloneUser->getFirstName() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneUser->getFirstName();
			}
			$msg .= "</td><td>";
			if ($user->getFirstName() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $user->getFirstName();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneUser->getLastName() != $user->getLastName()) {
			$msg .= "<tr><td>".$this->translate('User.lastName.label').'</td><td>';
			if ($cloneUser->getLastName() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneUser->getLastName();
			}
			$msg .= "</td><td>";
			if ($user->getUsername() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $user->getLastName();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneUser->getBirthday() != $user->getBirthday()) {
			$msg .= "<tr><td>".$this->translate('User.birthday.label').'</td><td>';
			if ($cloneUser->getBirthday() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneUser->getBirthday()->format('Y-m-d');
			}
			$msg .= "</td><td>";
			if ($user->getBirthday() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $user->getBirthday()->format('Y-m-d');
			}
			$msg .= "</td></tr>";
		}

		if ($cloneUser->getStreetNum() != $user->getStreetNum()) {
			$msg .= "<tr><td>".$this->translate('User.streetNum.label').'</td><td>';
			if ($cloneUser->getStreetNum() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneUser->getStreetNum();
			}
			$msg .= "</td><td>";
			if ($user->getStreetNum() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $user->getStreetNum();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneUser->getAddress() != $user->getAddress()) {
			$msg .= "<tr><td>".$this->translate('User.address.label').'</td><td>';
			if ($cloneUser->getAddress() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneUser->getAddress();
			}
			$msg .= "</td><td>";
			if ($user->getAddress() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $user->getAddress();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneUser->getAddress2() != $user->getAddress2()) {
			$msg .= "<tr><td>".$this->translate('User.address2.label').'</td><td>';
			if ($cloneUser->getAddress2() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneUser->getAddress2();
			}
			$msg .= "</td><td>";
			if ($user->getAddress2() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $user->getAddress2();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneUser->getTown() != $user->getTown()) {
			$msg .= "<tr><td>".$this->translate('User.town.label').'</td><td>';
			if ($cloneUser->getTown() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneUser->getTown();
			}
			$msg .= "</td><td>";
			if ($user->getTown() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $user->getTown();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneUser->getZipCode() != $user->getZipCode()) {
			$msg .= "<tr><td>".$this->translate('User.zipCode.label').'</td><td>';
			if ($cloneUser->getZipCode() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneUser->getZipCode();
			}
			$msg .= "</td><td>";
			if ($user->getZipCode() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $user->getZipCode();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneUser->getCountry() != $user->getCountry()) {
			$msg .= "<tr><td>".$this->translate('User.country.label').'</td><td>';
			if ($cloneUser->getCountry() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneUser->getCountry();
			}
			$msg .= "</td><td>";
			if ($user->getCountry() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $user->getCountry();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneUser->getPhone() != $user->getPhone()) {
			$msg .= "<tr><td>".$this->translate('User.phone.label').'</td><td>';
			if ($cloneUser->getPhone() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneUser->getPhone();
			}
			$msg .= "</td><td>";
			if ($user->getPhone() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $user->getPhone();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneUser->getMobile() != $user->getMobile()) {
			$msg .= "<tr><td>".$this->translate('User.mobile.label').'</td><td>';
			if ($cloneUser->getMobile() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneUser->getMobile();
			}
			$msg .= "</td><td>";
			if ($user->getMobile() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $user->getMobile();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneUser->getPreferedLang() != $user->getPreferedLang()) {
			$msg .= "<tr><td>".$this->translate('User.preferedLang.label').'</td><td>';
			if ($cloneUser->getPreferedLang() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneUser->getPreferedLang()->getName();
			}
			$msg .= "</td><td>";
			if ($user->getPreferedLang() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $user->getPreferedLang()->getName();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneUser->getEmail() != $user->getEmail()) {
			$msg .= "<tr><td>".$this->translate('User.email.label').'</td><td>';
			if ($cloneUser->getEmail() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneUser->getEmail();
			}
			$msg .= "</td><td>";
			if ($user->getEmail() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $user->getEmail();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneUser->getLockout() != $user->getLockout()) {
			$msg .= "<tr><td>".$this->translate('User.sexe.label').'</td><td>';
			if ($cloneUser->getLockout() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $this->translate('User.lockout.'.$cloneUser->getLockout());
			}
			$msg .= "</td><td>";
			if ($user->getLockout() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $this->translate('User.lockout.'.$user->getLockout());
			}
			$msg .= "</td></tr>";
		}

		if ($cloneUser->getClearPassword() != $user->getClearPassword()) {
			$msg .= "<tr><td>".$this->translate('User.password.label').'</td><td>';
			for ($i = 0; $i < \strlen($cloneUser->getClearPassword()); $i++) {
				$msg .= "*";
			}
			$msg .= "</td><td>";
			for ($i = 0; $i < \strlen($user->getClearPassword()); $i++) {
				$msg .= "*";
			}
			$msg .= "</td></tr>";
		}

		if ($cloneUser->getAvatar() != $user->getAvatar()) {
			$msg .= "<tr><td>".$this->translate('User.avatar.label').'</td><td>';
			if ($cloneUser->getAvatar() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= '<img class="img-responsive img-thumbnail" alt="" src="/res/avatars/'.$cloneUser->getAvatar().'">';
			}
			$msg .= "</td><td>";
			if ($user->getAvatar() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= '<img class="img-responsive img-thumbnail" alt="" src="/res/avatars/'.$user->getAvatar().'">';
			}
			$msg .= "</td></tr>";
		}

		if (\count(\array_diff($user->getUserRoles()->toArray(), $cloneUser->getUserRoles()->toArray())) != 0 || \count(\array_diff($cloneUser->getUserRoles()->toArray(), $user->getUserRoles()->toArray())) != 0) {
			$msg .= "<tr><td>".$this->translate('User.userRoles.label').'</td><td>';
			if (\count($cloneUser->getUserRoles()) == 0) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= "<ul>";
				foreach ($cloneUser->getUserRoles() as $role) {
					$msg .= '<li>'.$role->getName().'</li>';
				}
				$msg .= "<ul>";
			}
			$msg .= "</td><td>";
			if (\count($user->getUserRoles()) == 0) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= "<ul>";
				foreach ($user->getUserRoles() as $role) {
					$msg .= '<li>'.$role->getName().'</li>';
				}
				$msg .= "<ul>";


			}
			$msg .= "</td></tr>";
		}

		if ($msg != "") {

			$msg = $table_begin.$msg.$table_end;

			$trace->setMsg(
				$this->translate(
					'User.traceEdit',
					array('%user%' => $user->getFullName())
					).$msg
				);
			$trace->setDtCrea(new \DateTime('now'));
			$em = $this->getEntityManager();
			$em->persist($trace);
			$em->flush();
		}
	}
}
