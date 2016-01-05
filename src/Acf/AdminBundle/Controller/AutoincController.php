<?php

namespace Acf\AdminBundle\Controller;

use Acf\DataBundle\Entity\Autoinc;
use Acf\AdminBundle\Form\Autoinc\NewTForm as AutoincNewTForm;
use Acf\AdminBundle\Form\Autoinc\UpdateTForm as AutoincUpdateTForm;
use Sasedev\Commons\SharedBundle\Controller\BaseController;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class AutoincController extends BaseController
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

		$this->gvars['menu_active'] = 'autoinc';

	}

	public function listAction()
	{
		if (! $this->hasRole('ROLE_SUPERSUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$em = $this->getEntityManager();
		$autoincs = $em->getRepository('AcfDataBundle:Autoinc')->getAll();
		$this->gvars['autoincs'] = $autoincs;

		$this->gvars['smenu_active'] = 'list';
		$this->gvars['pagetitle'] = $this->translate('pagetitle.autoinc.list');
		$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.autoinc.list.txt');
		return $this->renderResponse('AcfAdminBundle:Autoinc:list.html.twig', $this->gvars);
	}

	public function addGetAction()
	{
		if (! $this->hasRole('ROLE_SUPERSUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$autoinc = new Autoinc();
		$autoincNewForm = $this->createForm(AutoincNewTForm::class, $autoinc);
		$this->gvars['autoinc'] = $autoinc;
		$this->gvars['AutoincNewForm'] = $autoincNewForm->createView();

		$this->gvars['pagetitle'] = $this->translate('pagetitle.autoinc.add');
		$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.autoinc.add.txt');
		$this->gvars['smenu_active'] = 'add';

		return $this->renderResponse('AcfAdminBundle:Autoinc:add.html.twig', $this->gvars);
	}

	public function addPostAction()
	{
		if (! $this->hasRole('ROLE_SUPERSUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('_admin_autoinc_addGet'));
		}

		$autoinc = new Autoinc();
		$autoincNewForm = $this->createForm(AutoincNewTForm::class, $autoinc);
		$this->gvars['autoinc'] = $autoinc;

		$request = $this->getRequest();
		$reqData = $request->request->all();

		if (isset($reqData['AutoincNewForm'])) {
			$autoincNewForm->handleRequest($request);
			if ($autoincNewForm->isValid()) {
				$em = $this->getEntityManager();
				$em->persist($autoinc);
				$em->flush();
				$this->flashMsgSession(
					'success',
					$this->translate('Autoinc.add.success', array('%autoinc%' => $autoinc->getName()))
				);

				return $this->redirect(
					$this->generateUrl('_admin_autoinc_editGet', array('uid' => $autoinc->getId()))
				);
			} else {
				$this->flashMsgSession(
					'error',
					$this->translate('Autoinc.add.failure')
				);
			}
		}
		$this->gvars['AutoincNewForm'] = $autoincNewForm->createView();

		$this->gvars['pagetitle'] = $this->translate('pagetitle.autoinc.add');
		$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.autoinc.add.txt');
		$this->gvars['smenu_active'] = 'add';

		return $this->renderResponse('AcfAdminBundle:Autoinc:add.html.twig', $this->gvars);
	}

	public function deleteAction($uid)
	{
		if (! $this->hasRole('ROLE_SUPERSUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_admin_autoinc_list');
		}
		$em = $this->getEntityManager();
		try {
			$autoinc = $em->getRepository('AcfDataBundle:Autoinc')->find($uid);

			if (null == $autoinc) {
				$this->flashMsgSession('warning', $this->translate('Autoinc.delete.notfound'));
			} else {
				$em->remove($autoinc);
				$em->flush();

				$this->flashMsgSession(
					'success',
					$this->translate('Autoinc.delete.success', array('%autoinc%' => $autoinc->getName()))
				);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());

			$this->flashMsgSession('error', $this->translate('Autoinc.delete.failure'));
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
			$urlFrom = $this->generateUrl('_admin_autoinc_list');
		}

		$em = $this->getEntityManager();
		try {
			$autoinc = $em->getRepository('AcfDataBundle:Autoinc')->find($uid);

			if (null == $autoinc) {
				$this->flashMsgSession('warning', $this->translate('Autoinc.edit.notfound'));
			} else {
				$autoincUpdateForm = $this->createForm(AutoincUpdateTForm::class, $autoinc);

				$this->gvars['autoinc'] = $autoinc;
				$this->gvars['AutoincUpdateForm'] = $autoincUpdateForm->createView();

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
				$this->getSession()->remove('tabActive');



				$this->gvars['pagetitle'] = $this->translate('pagetitle.autoinc.edit', array('%autoinc%' => $autoinc->getName()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.autoinc.edit.txt', array('%autoinc%' => $autoinc->getName()));

				return $this->renderResponse('AcfAdminBundle:Autoinc:edit.html.twig', $this->gvars);
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
			return $this->redirect($this->generateUrl('_admin_autoinc_list'));
		}

		$em = $this->getEntityManager();
		try {
			$autoinc = $em->getRepository('AcfDataBundle:Autoinc')->find($uid);

			if (null == $autoinc) {
				$this->flashMsgSession('warning', $this->translate('Autoinc.edit.notfound'));
			} else {
				$autoincUpdateForm = $this->createForm(AutoincUpdateTForm::class, $autoinc);



				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
				$this->getSession()->remove('tabActive');

				$request = $this->getRequest();
				$reqData = $request->request->all();

				if (isset($reqData['AutoincUpdateForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$autoincUpdateForm->handleRequest($request);
					if ($autoincUpdateForm->isValid()) {
						$em->persist($autoinc);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('Autoinc.edit.success', array('%autoinc%' => $autoinc->getName()))
						);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($autoinc);

						$this->flashMsgSession(
							'error',
							$this->translate('Autoinc.edit.failure', array('%autoinc%' => $autoinc->getName()))
						);
					}
				}

				$this->gvars['autoinc'] = $autoinc;
				$this->gvars['AutoincUpdateForm'] = $autoincUpdateForm->createView();

				$this->gvars['pagetitle'] = $this->translate('pagetitle.autoinc.edit', array('%autoinc%' => $autoinc->getName()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.autoinc.edit.txt', array('%autoinc%' => $autoinc->getName()));

				return $this->renderResponse('AcfAdminBundle:Autoinc:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());
		}

		return $this->redirect($urlFrom);

	}
}
