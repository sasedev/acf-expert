<?php

namespace Acf\AdminBundle\Controller;

use Acf\DataBundle\Entity\Role;
use Acf\AdminBundle\Form\Role\NewTForm as RoleNewTForm;
use Acf\AdminBundle\Form\Role\UpdateDescriptionTForm as RoleUpdateDescriptionTForm;
use Acf\AdminBundle\Form\Role\UpdateParentsTForm as RoleUpdateParentsTForm;
use Sasedev\Commons\SharedBundle\Controller\BaseController;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class RoleController extends BaseController
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

		$this->gvars['menu_active'] = 'role';

	}

	public function listAction()
	{
		if (! $this->hasRole('ROLE_SUPERSUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$em = $this->getEntityManager();
		$roles = $em->getRepository('AcfDataBundle:Role')->getAll();
		$this->gvars['roles'] = $roles;

		$this->gvars['smenu_active'] = 'list';
		$this->gvars['pagetitle'] = $this->translate('pagetitle.role.list');
		$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.role.list.txt');
		return $this->renderResponse('AcfAdminBundle:Role:list.html.twig', $this->gvars);
	}

	public function addGetAction()
	{
		if (! $this->hasRole('ROLE_SUPERSUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$role = new Role();
		$roleNewForm = $this->createForm(RoleNewTForm::class, $role);
		$this->gvars['role'] = $role;
		$this->gvars['RoleNewForm'] = $roleNewForm->createView();

		$this->gvars['pagetitle'] = $this->translate('pagetitle.role.add');
		$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.role.add.txt');
		$this->gvars['smenu_active'] = 'add';

		return $this->renderResponse('AcfAdminBundle:Role:add.html.twig', $this->gvars);
	}

	public function addPostAction()
	{
		if (! $this->hasRole('ROLE_SUPERSUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('_admin_role_addGet'));
		}

		$role = new Role();
		$roleNewForm = $this->createForm(RoleNewTForm::class, $role);

		$request = $this->getRequest();
		$reqData = $request->request->all();

		if (isset($reqData['RoleNewForm'])) {
			$roleNewForm->handleRequest($request);
			if ($roleNewForm->isValid()) {
				$em = $this->getEntityManager();
				$em->persist($role);
				$em->flush();
				$this->flashMsgSession(
					'success',
					$this->translate('Role.add.success', array('%role%' => $role->getName()))
				);

				return $this->redirect(
					$this->generateUrl('_admin_role_editGet', array('id' => $role->getId()))
				);
			} else {
				$this->flashMsgSession(
					'error',
					$this->translate('Role.add.failure')
				);
			}
		}
		$this->gvars['RoleNewForm'] = $roleNewForm->createView();
		$this->gvars['role'] = $role;

		$this->gvars['pagetitle'] = $this->translate('pagetitle.role.add');
		$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.role.add.txt');
		$this->gvars['smenu_active'] = 'add';

		return $this->renderResponse('AcfAdminBundle:Role:add.html.twig', $this->gvars);
	}

	public function deleteAction($id)
	{
		if (! $this->hasRole('ROLE_SUPERSUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_admin_role_list');
		}
		$em = $this->getEntityManager();
		try {
			$role = $em->getRepository('AcfDataBundle:Role')->find($id);

			if (null == $role) {
				$this->flashMsgSession('warning', $this->translate('Role.delete.notfound'));
			} else {
				$em->remove($role);
				$em->flush();

				$this->flashMsgSession(
					'success',
					$this->translate('Role.delete.success', array('%role%' => $role->getName()))
				);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());

			$this->flashMsgSession('error', $this->translate('Role.delete.failure'));
		}

		return $this->redirect($urlFrom);

	}

	public function editGetAction($id)
	{
		if (! $this->hasRole('ROLE_SUPERSUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_admin_role_list');
		}

		$em = $this->getEntityManager();
		try {
			$role = $em->getRepository('AcfDataBundle:Role')->find($id);

			if (null == $role) {
				$this->flashMsgSession('warning', $this->translate('Role.edit.notfound'));
			} else {
				$roleUpdateDescriptionForm = $this->createForm(RoleUpdateDescriptionTForm::class, $role);
				$roleUpdateParentsForm = $this->createForm(RoleUpdateParentsTForm::class, $role);

				$this->gvars['role'] = $role;
				$this->gvars['RoleUpdateDescriptionForm'] = $roleUpdateDescriptionForm->createView();
				$this->gvars['RoleUpdateParentsForm'] = $roleUpdateParentsForm->createView();

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
				$this->getSession()->remove('tabActive');



				$this->gvars['pagetitle'] = $this->translate('pagetitle.role.edit', array('%role%' => $role->getName()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.role.edit.txt', array('%role%' => $role->getName()));

				return $this->renderResponse('AcfAdminBundle:Role:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());
		}

		return $this->redirect($urlFrom);
	}


	public function editPostAction($id)
	{
		if (! $this->hasRole('ROLE_SUPERSUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('_admin_role_list'));
		}

		$em = $this->getEntityManager();
		try {
			$role = $em->getRepository('AcfDataBundle:Role')->find($id);

			if (null == $role) {
				$this->flashMsgSession('warning', $this->translate('Role.edit.notfound'));
			} else {
				$roleUpdateDescriptionForm = $this->createForm(RoleUpdateDescriptionTForm::class, $role);
				$roleUpdateParentsForm = $this->createForm(RoleUpdateParentsTForm::class, $role);



				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
				$this->getSession()->remove('tabActive');

				$request = $this->getRequest();
				$reqData = $request->request->all();

				if (isset($reqData['RoleUpdateDescriptionForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$roleUpdateDescriptionForm->handleRequest($request);
					if ($roleUpdateDescriptionForm->isValid()) {
						$em->persist($role);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('Role.edit.success', array('%role%' => $role->getName()))
						);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($role);

						$this->flashMsgSession(
							'error',
							$this->translate('Role.edit.failure', array('%role%' => $role->getName()))
						);
					}
				} elseif (isset($reqData['RoleUpdateParentsForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$roleUpdateParentsForm->handleRequest($request);
					if ($roleUpdateParentsForm->isValid()) {
						$em->persist($role);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('Role.edit.success', array('%role%' => $role->getName()))
						);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($role);

						$this->flashMsgSession(
							'error',
							$this->translate('Role.edit.failure', array('%role%' => $role->getName()))
						);
					}
				}

				$this->gvars['role'] = $role;
				$this->gvars['RoleUpdateDescriptionForm'] = $roleUpdateDescriptionForm->createView();
				$this->gvars['RoleUpdateParentsForm'] = $roleUpdateParentsForm->createView();

				$this->gvars['pagetitle'] = $this->translate('pagetitle.role.edit', array('%role%' => $role->getName()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.role.edit.txt', array('%role%' => $role->getName()));

				return $this->renderResponse('AcfAdminBundle:Role:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());
		}

		return $this->redirect($urlFrom);

	}
}
