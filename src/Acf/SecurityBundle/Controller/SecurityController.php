<?php

namespace Acf\SecurityBundle\Controller;

use Acf\DataBundle\Entity\User;
use Acf\SecurityBundle\Form\LoginTForm;
use Acf\SecurityBundle\Form\LostPasswordTForm;
use Acf\SecurityBundle\Form\NewUserTForm;
use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Acf\DataBundle\Entity\Role;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class SecurityController extends BaseController
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
	 * login Action
	 *
	 * @return RedirectResponse|Response
	 */
	public function loginAction()
	{
		// si l'utilisateur est déja connecté on le redirige vers sa page de
		// profile
		if ($this->hasRole('IS_AUTHENTICATED_FULLY')) {
			return $this->redirect($this->generateUrl('_security_profile'));
		}
		$session = $this->getSession();
		$request = $this->getRequest();
		if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
			$error = $request->attributes->get(Security::AUTHENTICATION_ERROR);
			$request->attributes->remove(Security::AUTHENTICATION_ERROR);
			$msg = $this->translate($error->getMessage());
			$this->flashMsgSession('error', $msg);
		} elseif ($session->has(Security::AUTHENTICATION_ERROR)) {
			$error = $session->get(Security::AUTHENTICATION_ERROR);
			$session->remove(Security::AUTHENTICATION_ERROR);
			$msg = $this->translate($error->getMessage());
			$this->flashMsgSession('error', $msg);
		}

		$lastUsername = $session->get('_security.last_username');
		$referer = $this->getReferer();

		$loginForm = $this->createForm(LoginTForm::class);

		$loginForm->get('username')->setData($lastUsername);
		$loginForm->get('target_path')->setData($referer);
		$loginForm->get('remember_me')->setData(true);

		$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.security.login.txt');
		$this->gvars['pagetitle'] = $this->translate('pagetitle.security.login');
		$this->gvars['LoginForm'] = $loginForm->createView();

		return $this->renderResponse('AcfSecurityBundle:Security:login.html.twig', $this->gvars);
	}

	public function registerAction()
	{
		if ($this->hasRole('IS_AUTHENTICATED_FULLY')) {
			return $this->redirect($this->generateUrl('_security_profile'));
		}
		$user = new User();
		$newUserForm = $this->createForm(NewUserTForm::class, $user);
		$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.security.register.txt');
		$this->gvars['pagetitle'] = $this->translate('pagetitle.security.register');
		$this->gvars['NewUserForm'] = $newUserForm->createView();

		return $this->renderResponse('AcfSecurityBundle:Security:register.html.twig', $this->gvars);

	}

	public function registerPostAction()
	{
		if ($this->hasRole('IS_AUTHENTICATED_FULLY')) {
			return $this->redirect($this->generateUrl('_security_profile'));
		}
		$user = new User();
		$newUserForm = $this->createForm(NewUserTForm::class, $user);
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$newUserForm->handleRequest($request);
			if ($newUserForm->isValid()) {
				$em = $this->getEntityManager();
				$user->setLockout(User::LOCKOUT_LOCKED);
				$user->setSalt(md5(uniqid(null, true)));
				$user->setClearPassword(User::generateRandomChar(12, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz&#@0123456789'));

				$role = $em->getRepository('AcfDataBundle:Role')->findOneBy(array('name' => 'ROLE_USER'));
				$user->addUserRole($role);

				$em->persist($user);
				$em->flush();

				$mvars = array();
				$mvars['user'] = $user;
				$mvars['corporateName'] = $newUserForm['corporateName']->getData();
				$mvars['type'] = $newUserForm['type']->getData();
				$mvars['fisc'] = $newUserForm['fisc']->getData();
				$mvars['commercialRegister'] = $newUserForm['commercialRegister']->getData();
				$mvars['oneYear'] = $newUserForm['oneYear']->getData();
				$mvars['autoRenew'] = $newUserForm['autoRenew']->getData();
				$mvars['paymentType'] = $newUserForm['paymentType']->getData();

				$from = $this->getParameter('mail_from');
				$fromName = $this->getParameter('mail_from_name');
				$subject = $this->translate('_mail.register.subject', array(), 'messages');

				try {
					$admins = $this->getParameter('mailtos');

					$message = \Swift_Message::newInstance()->setFrom($from, $fromName);
					foreach ($admins as $admin) {
						$message->addTo($admin['email'], $admin['name']);
					}
					$message->setSubject($subject);
					$message->setBody($this->renderView('AcfSecurityBundle:Mail:registeradmin.html.twig', $mvars), 'text/html');

					$this->sendmail($message);
				} catch (\Exception $e) {
					// ne rien faire
				}

				$message = \Swift_Message::newInstance()->setFrom($from, $fromName);
				$message->setTo($user->getEmail(), $user->getFullname());
				$message->setSubject($subject);
				$message->setBody($this->renderView('AcfSecurityBundle:Mail:register.html.twig', $mvars), 'text/html');

				$this->sendmail($message);

				$this->flashMsgSession('success', $this->translate('_security.register.success', array('%mail%' => $user->getEmail())));

				return $this->redirect($this->generateUrl('_security_login'));
			} else {
				$this->flashMsgSession('error', $this->translate('_security.register.error'));
			}
		}

		$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.security.register.txt');
		$this->gvars['pagetitle'] = $this->translate('pagetitle.security.register');
		$this->gvars['NewUserForm'] = $newUserForm->createView();

		return $this->renderResponse('AcfSecurityBundle:Security:register.html.twig', $this->gvars);

	}

	/**
	 * lostPassword Action
	 *
	 * @return RedirectResponse|Response
	 */
	public function lostPasswordAction()
	{
		if ($this->hasRole('IS_AUTHENTICATED_FULLY')) {
			return $this->redirect($this->generateUrl('_security_profile'));
		}
		$lostPasswordForm = $this->createForm(LostPasswordTForm::class);
		$request = $this->getRequest();
		if ($request->getMethod() == 'POST') {
			$lostPasswordForm->handleRequest($request);
			if ($lostPasswordForm->isValid()) {
				$username = $lostPasswordForm['username']->getData();
				$em = $this->getEntityManager();
				$user = null;
				$user = $em->getRepository('AcfDataBundle:User')->findOneBy(array('username' => $username));

				if (null != $user) {
					$now = new \DateTime('now');
					$nexthour = new \DateTime();
					$nexthour->setTimestamp(strtotime('+1 hour'));
					if (null == $user->getRecoveryExpiration() || $user->getRecoveryExpiration() < $now) {
						$user->setRecoveryExpiration($nexthour);
						$user->setRecoveryCode(User::generateRandomChar(20));
						$em->persist($user);
						$em->flush();

						$mvars = array();
						$mvars['user'] = $user;
						$mvars['url'] = $this->generateUrl('_security_lost_genpassword',
							array('id' => $user->getId(), 'code' => $user->getRecoveryCode()), true);

						$from = $this->getParameter('mail_from');
						$fromName = $this->getParameter('mail_from_name');
						$subject = $this->translate('_mail.lostPassword.subject', array(), 'messages');
						$message = \Swift_Message::newInstance()->setFrom($from, $fromName)
							->setTo($user->getEmail(), $user->getFullname())
							->setSubject($subject)
							->setBody($this->renderView('AcfSecurityBundle:Mail:getPasswordResetLink.html.twig', $mvars), 'text/html');

						$this->sendmail($message);

						$this->flashMsgSession('success',
							$this->translate('_security.lostPassword.mail.newpassSent', array('%mail%' => $user->getEmail())));

						return $this->redirect($this->generateUrl('_security_login'));
					} else {
						$this->flashMsgSession('error', $this->translate('_security.lostPassword.alreadySent'));
					}
				} else {
					$this->flashMsgSession('error', $this->translate('_security.lostPassword.notfound', array('%username%' => $username)));
				}
			}
		}
		$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.security.lostPassword.txt');
		$this->gvars['pagetitle'] = $this->translate('pagetitle.security.lostPassword');
		$this->gvars['LostPasswordForm'] = $lostPasswordForm->createView();

		return $this->renderResponse('AcfSecurityBundle:Security:lostPassword.html.twig', $this->gvars);
	}

	/**
	 * genNewPassword Action
	 *
	 * @param guid $id
	 * @param string $code
	 *
	 * @return RedirectResponse|Response
	 */
	public function genNewPasswordAction($id, $code)
	{
		if ($this->hasRole('IS_AUTHENTICATED_FULLY')) {
			return $this->redirect($this->generateUrl('_security_profile'));
		}
		$em = $this->getEntityManager();
		try {
			$user = null;
			$user = $em->getRepository('AcfDataBundle:User')->find($id);

			if (null != $user) {
				$now = new \DateTime('now');
				if (null == $user->getRecoveryExpiration() || $user->getRecoveryExpiration() < $now) {
					$this->flashMsgSession('error', $this->translate('_security.genNewPassword.errorparams2'));
				} elseif ($user->getRecoveryCode() != $code) {
					$this->flashMsgSession('error', $this->translate('_security.genNewPassword.errorparams3'));
				} else {
					$user->setSalt(md5(uniqid(null, true)));
					$user->setClearPassword(User::generateRandomChar(8, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'));
					$user->setRecoveryExpiration(null);
					$user->setRecoveryCode(null);
					$em->persist($user);
					$em->flush();
					$mvars = array();
					$mvars['user'] = $user;
					$from = $this->getParameter('mail_from');
					$fromName = $this->getParameter('mail_from_name');
					$subject = $this->translate('_mail.genNewPassword.subject', array(), 'messages');

					$message = \Swift_Message::newInstance()->setFrom($from, $fromName)
						->setTo($user->getEmail(), $user->getFullname())
						->setSubject($subject)
						->setBody($this->renderView('AcfSecurityBundle:Mail:genNewPassword.html.twig', $mvars), 'text/html');

					$this->sendmail($message);

					$this->flashMsgSession('success', $this->translate('_security.genNewPassword.ok'));
				}
			} else {
				$this->flashMsgSession('error', $this->translate('_security.genNewPassword.errorparams1'));
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->error($e->getMessage());
			$this->flashMsgSession('error', $this->translate('_security.genNewPassword.errorparams4'));
		}

		return $this->redirect($this->generateUrl('_security_login'));
	}

	/**
	 * whoamiAction
	 *
	 * @return Response
	 */
	public function whoamiAction()
	{
		$sc = $this->getSecurityTokenStorage();
		$user = $sc->getToken()->getUser();

		$this->gvars['user'] = $user;

		return $this->renderResponse('AcfSecurityBundle:Profile:whoami.html.twig', $this->gvars);
	}

	/**
	 * myNextEventsAction
	 *
	 * @return Response
	 */
	public function myNextEventsAction()
	{
		$sc = $this->getSecurityTokenStorage();
		$user = $sc->getToken()->getUser();
		$this->gvars['user'] = $user;

		$em = $this->getEntityManager();
		$events = $em->getRepository('AcfDataBundle:Agenda')->getNextByUser($user);
		$this->gvars['nextEvents'] = $events;

		return $this->renderResponse('AcfSecurityBundle:Agenda:next.html.twig', $this->gvars);
	}
}
