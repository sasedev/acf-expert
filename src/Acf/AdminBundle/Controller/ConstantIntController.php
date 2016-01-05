<?php

namespace Acf\AdminBundle\Controller;

use Acf\DataBundle\Entity\ConstantInt;
use Acf\AdminBundle\Form\ConstantInt\NewTForm as ConstantIntNewTForm;
use Acf\AdminBundle\Form\ConstantInt\UpdateDescriptionTForm as ConstantIntUpdateDescriptionTForm;
use Acf\AdminBundle\Form\ConstantInt\UpdateValueTForm as ConstantIntUpdateValueTForm;
use Sasedev\Commons\SharedBundle\Controller\BaseController;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class ConstantIntController extends BaseController
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

		$this->gvars['menu_active'] = 'constantInt';

	}

	public function listAction()
	{
		if (! $this->hasRole('ROLE_SUPERSUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$em = $this->getEntityManager();
		$constantInts = $em->getRepository('AcfDataBundle:ConstantInt')->getAll();
		$this->gvars['constantInts'] = $constantInts;

		$this->gvars['smenu_active'] = 'list';
		$this->gvars['pagetitle'] = $this->translate('pagetitle.constantInt.list');
		$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.constantInt.list.txt');
		return $this->renderResponse('AcfAdminBundle:ConstantInt:list.html.twig', $this->gvars);
	}

	public function addGetAction()
	{
		if (! $this->hasRole('ROLE_SUPERSUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$constantInt = new ConstantInt();
		$constantIntNewForm = $this->createForm(ConstantIntNewTForm::class, $constantInt);
		$this->gvars['constantInt'] = $constantInt;
		$this->gvars['ConstantIntNewForm'] = $constantIntNewForm->createView();

		$this->gvars['pagetitle'] = $this->translate('pagetitle.constantInt.add');
		$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.constantInt.add.txt');
		$this->gvars['smenu_active'] = 'add';

		return $this->renderResponse('AcfAdminBundle:ConstantInt:add.html.twig', $this->gvars);
	}

	public function addPostAction()
	{
		if (! $this->hasRole('ROLE_SUPERSUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('_admin_constantInt_addGet'));
		}

		$constantInt = new ConstantInt();
		$constantIntNewForm = $this->createForm(ConstantIntNewTForm::class, $constantInt);
		$this->gvars['constantInt'] = $constantInt;

		$request = $this->getRequest();
		$reqData = $request->request->all();

		if (isset($reqData['ConstantIntNewForm'])) {
			$constantIntNewForm->handleRequest($request);
			if ($constantIntNewForm->isValid()) {
				$em = $this->getEntityManager();
				$em->persist($constantInt);
				$em->flush();
				$this->flashMsgSession(
					'success',
					$this->translate('ConstantInt.add.success', array('%constantInt%' => $constantInt->getName()))
				);

				return $this->redirect(
					$this->generateUrl('_admin_constantInt_editGet', array('uid' => $constantInt->getId()))
				);
			} else {
				$this->flashMsgSession(
					'error',
					$this->translate('ConstantInt.add.failure')
				);
			}
		}
		$this->gvars['ConstantIntNewForm'] = $constantIntNewForm->createView();

		$this->gvars['pagetitle'] = $this->translate('pagetitle.constantInt.add');
		$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.constantInt.add.txt');
		$this->gvars['smenu_active'] = 'add';

		return $this->renderResponse('AcfAdminBundle:ConstantInt:add.html.twig', $this->gvars);
	}

	public function deleteAction($uid)
	{
		if (! $this->hasRole('ROLE_SUPERSUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_admin_constantInt_list');
		}
		$em = $this->getEntityManager();
		try {
			$constantInt = $em->getRepository('AcfDataBundle:ConstantInt')->find($uid);

			if (null == $constantInt) {
				$this->flashMsgSession('warning', $this->translate('ConstantInt.delete.notfound'));
			} else {
				$em->remove($constantInt);
				$em->flush();

				$this->flashMsgSession(
					'success',
					$this->translate('ConstantInt.delete.success', array('%constantInt%' => $constantInt->getName()))
				);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());

			$this->flashMsgSession('error', $this->translate('ConstantInt.delete.failure'));
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
			$urlFrom = $this->generateUrl('_admin_constantInt_list');
		}

		$em = $this->getEntityManager();
		try {
			$constantInt = $em->getRepository('AcfDataBundle:ConstantInt')->find($uid);

			if (null == $constantInt) {
				$this->flashMsgSession('warning', $this->translate('ConstantInt.edit.notfound'));
			} else {
				$constantIntUpdateDescriptionForm = $this->createForm(ConstantIntUpdateDescriptionTForm::class, $constantInt);
				$constantIntUpdateValueForm = $this->createForm(ConstantIntUpdateValueTForm::class, $constantInt);

				$this->gvars['constantInt'] = $constantInt;
				$this->gvars['ConstantIntUpdateDescriptionForm'] = $constantIntUpdateDescriptionForm->createView();
				$this->gvars['ConstantIntUpdateValueForm'] = $constantIntUpdateValueForm->createView();

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
				$this->getSession()->remove('tabActive');



				$this->gvars['pagetitle'] = $this->translate('pagetitle.constantInt.edit', array('%constantInt%' => $constantInt->getName()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.constantInt.edit.txt', array('%constantInt%' => $constantInt->getName()));

				return $this->renderResponse('AcfAdminBundle:ConstantInt:edit.html.twig', $this->gvars);
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
			return $this->redirect($this->generateUrl('_admin_constantInt_list'));
		}

		$em = $this->getEntityManager();
		try {
			$constantInt = $em->getRepository('AcfDataBundle:ConstantInt')->find($uid);

			if (null == $constantInt) {
				$this->flashMsgSession('warning', $this->translate('ConstantInt.edit.notfound'));
			} else {
				$constantIntUpdateDescriptionForm = $this->createForm(ConstantIntUpdateDescriptionTForm::class, $constantInt);
				$constantIntUpdateValueForm = $this->createForm(ConstantIntUpdateValueTForm::class, $constantInt);



				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
				$this->getSession()->remove('tabActive');

				$request = $this->getRequest();
				$reqData = $request->request->all();

				if (isset($reqData['ConstantIntUpdateDescriptionForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$constantIntUpdateDescriptionForm->handleRequest($request);
					if ($constantIntUpdateDescriptionForm->isValid()) {
						$em->persist($constantInt);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('ConstantInt.edit.success', array('%constantInt%' => $constantInt->getName()))
						);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($constantInt);

						$this->flashMsgSession(
							'error',
							$this->translate('ConstantInt.edit.failure', array('%constantInt%' => $constantInt->getName()))
						);
					}
				} elseif (isset($reqData['ConstantIntUpdateValueForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$constantIntUpdateValueForm->handleRequest($request);
					if ($constantIntUpdateValueForm->isValid()) {
						$em->persist($constantInt);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('ConstantInt.edit.success', array('%constantInt%' => $constantInt->getName()))
						);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($constantInt);

						$this->flashMsgSession(
							'error',
							$this->translate('ConstantInt.edit.failure', array('%constantInt%' => $constantInt->getName()))
						);
					}
				}

				$this->gvars['constantInt'] = $constantInt;
				$this->gvars['ConstantIntUpdateDescriptionForm'] = $constantIntUpdateDescriptionForm->createView();
				$this->gvars['ConstantIntUpdateValueForm'] = $constantIntUpdateValueForm->createView();

				$this->gvars['pagetitle'] = $this->translate('pagetitle.constantInt.edit', array('%constantInt%' => $constantInt->getName()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.constantInt.edit.txt', array('%constantInt%' => $constantInt->getName()));

				return $this->renderResponse('AcfAdminBundle:ConstantInt:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());
		}

		return $this->redirect($urlFrom);

	}
}
