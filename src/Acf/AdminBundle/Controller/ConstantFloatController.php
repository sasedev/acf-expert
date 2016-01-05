<?php

namespace Acf\AdminBundle\Controller;

use Acf\DataBundle\Entity\ConstantFloat;
use Acf\AdminBundle\Form\ConstantFloat\NewTForm as ConstantFloatNewTForm;
use Acf\AdminBundle\Form\ConstantFloat\UpdateDescriptionTForm as ConstantFloatUpdateDescriptionTForm;
use Acf\AdminBundle\Form\ConstantFloat\UpdateValueTForm as ConstantFloatUpdateValueTForm;
use Sasedev\Commons\SharedBundle\Controller\BaseController;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class ConstantFloatController extends BaseController
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

		$this->gvars['menu_active'] = 'constantFloat';

	}

	public function listAction()
	{
		if (! $this->hasRole('ROLE_SUPERSUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$em = $this->getEntityManager();
		$constantFloats = $em->getRepository('AcfDataBundle:ConstantFloat')->getAll();
		$this->gvars['constantFloats'] = $constantFloats;

		$this->gvars['smenu_active'] = 'list';
		$this->gvars['pagetitle'] = $this->translate('pagetitle.constantFloat.list');
		$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.constantFloat.list.txt');
		return $this->renderResponse('AcfAdminBundle:ConstantFloat:list.html.twig', $this->gvars);
	}

	public function addGetAction()
	{
		if (! $this->hasRole('ROLE_SUPERSUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$constantFloat = new ConstantFloat();
		$constantFloatNewForm = $this->createForm(ConstantFloatNewTForm::class, $constantFloat);
		$this->gvars['constantFloat'] = $constantFloat;
		$this->gvars['ConstantFloatNewForm'] = $constantFloatNewForm->createView();

		$this->gvars['pagetitle'] = $this->translate('pagetitle.constantFloat.add');
		$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.constantFloat.add.txt');
		$this->gvars['smenu_active'] = 'add';

		return $this->renderResponse('AcfAdminBundle:ConstantFloat:add.html.twig', $this->gvars);
	}

	public function addPostAction()
	{
		if (! $this->hasRole('ROLE_SUPERSUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('_admin_constantFloat_addGet'));
		}

		$constantFloat = new ConstantFloat();
		$constantFloatNewForm = $this->createForm(ConstantFloatNewTForm::class, $constantFloat);
		$this->gvars['constantFloat'] = $constantFloat;

		$request = $this->getRequest();
		$reqData = $request->request->all();

		if (isset($reqData['ConstantFloatNewForm'])) {
			$constantFloatNewForm->handleRequest($request);
			if ($constantFloatNewForm->isValid()) {
				$em = $this->getEntityManager();
				$em->persist($constantFloat);
				$em->flush();
				$this->flashMsgSession(
					'success',
					$this->translate('ConstantFloat.add.success', array('%constantFloat%' => $constantFloat->getName()))
				);

				return $this->redirect(
					$this->generateUrl('_admin_constantFloat_editGet', array('uid' => $constantFloat->getId()))
				);
			} else {
				$this->flashMsgSession(
					'error',
					$this->translate('ConstantFloat.add.failure')
				);
			}
		}
		$this->gvars['ConstantFloatNewForm'] = $constantFloatNewForm->createView();

		$this->gvars['pagetitle'] = $this->translate('pagetitle.constantFloat.add');
		$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.constantFloat.add.txt');
		$this->gvars['smenu_active'] = 'add';

		return $this->renderResponse('AcfAdminBundle:ConstantFloat:add.html.twig', $this->gvars);
	}

	public function deleteAction($uid)
	{
		if (! $this->hasRole('ROLE_SUPERSUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_admin_constantFloat_list');
		}
		$em = $this->getEntityManager();
		try {
			$constantFloat = $em->getRepository('AcfDataBundle:ConstantFloat')->find($uid);

			if (null == $constantFloat) {
				$this->flashMsgSession('warning', $this->translate('ConstantFloat.delete.notfound'));
			} else {
				$em->remove($constantFloat);
				$em->flush();

				$this->flashMsgSession(
					'success',
					$this->translate('ConstantFloat.delete.success', array('%constantFloat%' => $constantFloat->getName()))
				);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());

			$this->flashMsgSession('error', $this->translate('ConstantFloat.delete.failure'));
		}

		return $this->redirect($urlFrom);

	}

	public function editGetAction($uid)
	{
		if (! $this->hasRole('ROLE_SUPERSUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_admin_constantFloat_list');
		}

		$em = $this->getEntityManager();
		try {
			$constantFloat = $em->getRepository('AcfDataBundle:ConstantFloat')->find($uid);

			if (null == $constantFloat) {
				$this->flashMsgSession('warning', $this->translate('ConstantFloat.edit.notfound'));
			} else {
				$constantFloatUpdateDescriptionForm = $this->createForm(ConstantFloatUpdateDescriptionTForm::class, $constantFloat);
				$constantFloatUpdateValueForm = $this->createForm(ConstantFloatUpdateValueTForm::class, $constantFloat);

				$this->gvars['constantFloat'] = $constantFloat;
				$this->gvars['ConstantFloatUpdateDescriptionForm'] = $constantFloatUpdateDescriptionForm->createView();
				$this->gvars['ConstantFloatUpdateValueForm'] = $constantFloatUpdateValueForm->createView();

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
				$this->getSession()->remove('tabActive');



				$this->gvars['pagetitle'] = $this->translate('pagetitle.constantFloat.edit', array('%constantFloat%' => $constantFloat->getName()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.constantFloat.edit.txt', array('%constantFloat%' => $constantFloat->getName()));

				return $this->renderResponse('AcfAdminBundle:ConstantFloat:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());
		}

		return $this->redirect($urlFrom);
	}


	public function editPostAction($uid)
	{
		if (! $this->hasRole('ROLE_SUPERSUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('_admin_constantFloat_list'));
		}

		$em = $this->getEntityManager();
		try {
			$constantFloat = $em->getRepository('AcfDataBundle:ConstantFloat')->find($uid);

			if (null == $constantFloat) {
				$this->flashMsgSession('warning', $this->translate('ConstantFloat.edit.notfound'));
			} else {
				$constantFloatUpdateDescriptionForm = $this->createForm(ConstantFloatUpdateDescriptionTForm::class, $constantFloat);
				$constantFloatUpdateValueForm = $this->createForm(ConstantFloatUpdateValueTForm::class, $constantFloat);



				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
				$this->getSession()->remove('tabActive');

				$request = $this->getRequest();
				$reqData = $request->request->all();

				if (isset($reqData['ConstantFloatUpdateDescriptionForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$constantFloatUpdateDescriptionForm->handleRequest($request);
					if ($constantFloatUpdateDescriptionForm->isValid()) {
						$em->persist($constantFloat);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('ConstantFloat.edit.success', array('%constantFloat%' => $constantFloat->getName()))
						);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($constantFloat);

						$this->flashMsgSession(
							'error',
							$this->translate('ConstantFloat.edit.failure', array('%constantFloat%' => $constantFloat->getName()))
						);
					}
				} elseif (isset($reqData['ConstantFloatUpdateValueForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$constantFloatUpdateValueForm->handleRequest($request);
					if ($constantFloatUpdateValueForm->isValid()) {
						$em->persist($constantFloat);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('ConstantFloat.edit.success', array('%constantFloat%' => $constantFloat->getName()))
						);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($constantFloat);

						$this->flashMsgSession(
							'error',
							$this->translate('ConstantFloat.edit.failure', array('%constantFloat%' => $constantFloat->getName()))
						);
					}
				}

				$this->gvars['constantFloat'] = $constantFloat;
				$this->gvars['ConstantFloatUpdateDescriptionForm'] = $constantFloatUpdateDescriptionForm->createView();
				$this->gvars['ConstantFloatUpdateValueForm'] = $constantFloatUpdateValueForm->createView();

				$this->gvars['pagetitle'] = $this->translate('pagetitle.constantFloat.edit', array('%constantFloat%' => $constantFloat->getName()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.constantFloat.edit.txt', array('%constantFloat%' => $constantFloat->getName()));

				return $this->renderResponse('AcfAdminBundle:ConstantFloat:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());
		}

		return $this->redirect($urlFrom);

	}
}
