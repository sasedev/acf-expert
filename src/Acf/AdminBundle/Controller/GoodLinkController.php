<?php

namespace Acf\AdminBundle\Controller;

use Acf\DataBundle\Entity\GoodLink;
use Acf\AdminBundle\Form\GoodLink\NewTForm as GoodLinkNewTForm;
use Acf\AdminBundle\Form\GoodLink\UpdateUrlTForm as GoodLinkUpdateUrlTForm;
use Acf\AdminBundle\Form\GoodLink\UpdateTitleTForm as GoodLinkUpdateTitleTForm;
use Sasedev\Commons\SharedBundle\Controller\BaseController;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class GoodLinkController extends BaseController
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

		$this->gvars['menu_active'] = 'goodLink';

	}

	public function listAction()
	{
		if (! $this->hasRole('ROLE_SUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$em = $this->getEntityManager();
		$goodLinks = $em->getRepository('AcfDataBundle:GoodLink')->getAll();
		$this->gvars['goodLinks'] = $goodLinks;

		$this->gvars['smenu_active'] = 'list';
		$this->gvars['pagetitle'] = $this->translate('pagetitle.goodLink.list');
		$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.goodLink.list.txt');
		return $this->renderResponse('AcfAdminBundle:GoodLink:list.html.twig', $this->gvars);
	}

	public function addGetAction()
	{
		if (! $this->hasRole('ROLE_SUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$goodLink = new GoodLink();
		$goodLinkNewForm = $this->createForm(GoodLinkNewTForm::class, $goodLink);
		$this->gvars['goodLink'] = $goodLink;
		$this->gvars['GoodLinkNewForm'] = $goodLinkNewForm->createView();

		$this->gvars['pagetitle'] = $this->translate('pagetitle.goodLink.add');
		$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.goodLink.add.txt');
		$this->gvars['smenu_active'] = 'add';

		return $this->renderResponse('AcfAdminBundle:GoodLink:add.html.twig', $this->gvars);
	}

	public function addPostAction()
	{
		if (! $this->hasRole('ROLE_SUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('_admin_goodLink_addGet'));
		}

		$goodLink = new GoodLink();
		$goodLinkNewForm = $this->createForm(GoodLinkNewTForm::class, $goodLink);
		$this->gvars['goodLink'] = $goodLink;

		$request = $this->getRequest();
		$reqData = $request->request->all();

		if (isset($reqData['GoodLinkNewForm'])) {
			$goodLinkNewForm->handleRequest($request);
			if ($goodLinkNewForm->isValid()) {
				$em = $this->getEntityManager();
				$em->persist($goodLink);
				$em->flush();
				$this->flashMsgSession(
					'success',
					$this->translate('GoodLink.add.success', array('%goodLink%' => $goodLink->getUrl()))
				);

				return $this->redirect(
					$this->generateUrl('_admin_goodLink_editGet', array('uid' => $goodLink->getId()))
				);
			} else {
				$this->flashMsgSession(
					'error',
					$this->translate('GoodLink.add.failure')
				);
			}
		}
		$this->gvars['GoodLinkNewForm'] = $goodLinkNewForm->createView();

		$this->gvars['pagetitle'] = $this->translate('pagetitle.goodLink.add');
		$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.goodLink.add.txt');
		$this->gvars['smenu_active'] = 'add';

		return $this->renderResponse('AcfAdminBundle:GoodLink:add.html.twig', $this->gvars);
	}

	public function deleteAction($uid)
	{
		if (! $this->hasRole('ROLE_SUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_admin_goodLink_list');
		}
		$em = $this->getEntityManager();
		try {
			$goodLink = $em->getRepository('AcfDataBundle:GoodLink')->find($uid);

			if (null == $goodLink) {
				$this->flashMsgSession('warning', $this->translate('GoodLink.delete.notfound'));
			} else {
				$em->remove($goodLink);
				$em->flush();

				$this->flashMsgSession(
					'success',
					$this->translate('GoodLink.delete.success', array('%goodLink%' => $goodLink->getUrl()))
				);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());

			$this->flashMsgSession('error', $this->translate('GoodLink.delete.failure'));
		}

		return $this->redirect($urlFrom);

	}

	public function editGetAction($uid)
	{
		if (! $this->hasRole('ROLE_SUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_admin_goodLink_list');
		}

		$em = $this->getEntityManager();
		try {
			$goodLink = $em->getRepository('AcfDataBundle:GoodLink')->find($uid);

			if (null == $goodLink) {
				$this->flashMsgSession('warning', $this->translate('GoodLink.edit.notfound'));
			} else {
				$goodLinkUpdateUrlForm = $this->createForm(GoodLinkUpdateUrlTForm::class, $goodLink);
				$goodLinkUpdateTitleForm = $this->createForm(GoodLinkUpdateTitleTForm::class, $goodLink);

				$this->gvars['goodLink'] = $goodLink;
				$this->gvars['GoodLinkUpdateUrlForm'] = $goodLinkUpdateUrlForm->createView();
				$this->gvars['GoodLinkUpdateTitleForm'] = $goodLinkUpdateTitleForm->createView();

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
				$this->getSession()->remove('tabActive');



				$this->gvars['pagetitle'] = $this->translate('pagetitle.goodLink.edit', array('%goodLink%' => $goodLink->getUrl()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.goodLink.edit.txt', array('%goodLink%' => $goodLink->getUrl()));

				return $this->renderResponse('AcfAdminBundle:GoodLink:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());
		}

		return $this->redirect($urlFrom);
	}


	public function editPostAction($uid)
	{
		if (! $this->hasRole('ROLE_SUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('_admin_goodLink_list'));
		}

		$em = $this->getEntityManager();
		try {
			$goodLink = $em->getRepository('AcfDataBundle:GoodLink')->find($uid);

			if (null == $goodLink) {
				$this->flashMsgSession('warning', $this->translate('GoodLink.edit.notfound'));
			} else {
				$goodLinkUpdateUrlForm = $this->createForm(GoodLinkUpdateUrlTForm::class, $goodLink);
				$goodLinkUpdateTitleForm = $this->createForm(GoodLinkUpdateTitleTForm::class, $goodLink);



				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
				$this->getSession()->remove('tabActive');

				$request = $this->getRequest();
				$reqData = $request->request->all();

				if (isset($reqData['GoodLinkUpdateUrlForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$goodLinkUpdateUrlForm->handleRequest($request);
					if ($goodLinkUpdateUrlForm->isValid()) {
						$em->persist($goodLink);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('GoodLink.edit.success', array('%goodLink%' => $goodLink->getUrl()))
						);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($goodLink);

						$this->flashMsgSession(
							'error',
							$this->translate('GoodLink.edit.failure', array('%goodLink%' => $goodLink->getUrl()))
						);
					}
				} elseif (isset($reqData['GoodLinkUpdateTitleForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$goodLinkUpdateTitleForm->handleRequest($request);
					if ($goodLinkUpdateTitleForm->isValid()) {
						$em->persist($goodLink);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('GoodLink.edit.success', array('%goodLink%' => $goodLink->getUrl()))
						);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($goodLink);

						$this->flashMsgSession(
							'error',
							$this->translate('GoodLink.edit.failure', array('%goodLink%' => $goodLink->getUrl()))
						);
					}
				}

				$this->gvars['goodLink'] = $goodLink;
				$this->gvars['GoodLinkUpdateUrlForm'] = $goodLinkUpdateUrlForm->createView();
				$this->gvars['GoodLinkUpdateTitleForm'] = $goodLinkUpdateTitleForm->createView();

				$this->gvars['pagetitle'] = $this->translate('pagetitle.goodLink.edit', array('%goodLink%' => $goodLink->getUrl()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.goodLink.edit.txt', array('%goodLink%' => $goodLink->getUrl()));

				return $this->renderResponse('AcfAdminBundle:GoodLink:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());
		}

		return $this->redirect($urlFrom);

	}
}
