<?php

namespace Acf\AdminBundle\Controller;

use Acf\AdminBundle\Form\BulletinInfoTitle\UpdateTForm as BulletinInfoTitleUpdateTForm;
use Acf\AdminBundle\Form\BulletinInfoContent\NewTForm as BulletinInfoContentNewTForm;
use Acf\DataBundle\Entity\BulletinInfo;
use Acf\DataBundle\Entity\BulletinInfoContent;
use Acf\DataBundle\Entity\BulletinInfoTitle;
use Sasedev\Commons\SharedBundle\Controller\BaseController;

/**
 *
 *
 * @author      sasedev <seif.salah@gmail.com>
 * @version     $Id$
 * @license     MIT
 *
 */
class BulletinInfoTitleController extends BaseController
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

	public function deleteAction($uid)
	{
		if (! $this->hasRole('ROLE_SUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}

		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_admin_bulletinInfo_list');
		}
		$em = $this->getEntityManager();
		try {
			$bulletinInfoTitle = $em->getRepository('AcfDataBundle:BulletinInfoTitle')->find($uid);

			if (null == $bulletinInfoTitle) {
				$this->flashMsgSession('warning', $this->translate('BulletinInfoTitle.delete.notfound'));
			} else {
				$em->remove($bulletinInfoTitle);
				$em->flush();

				$this->flashMsgSession('success',
					$this->translate('BulletinInfoTitle.delete.success', array('%bulletinInfoTitle%' => $bulletinInfoTitle->getTitle())));
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

			$this->flashMsgSession('error', $this->translate('BulletinInfoTitle.delete.failure'));
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
			$urlFrom = $this->generateUrl('_admin_bulletinInfoTitle_list');
		}

		$em = $this->getEntityManager();
		try {
			$bulletinInfoTitle = $em->getRepository('AcfDataBundle:BulletinInfoTitle')->find($uid);

			if (null == $bulletinInfoTitle) {
				$this->flashMsgSession('warning', $this->translate('BulletinInfoTitle.edit.notfound'));
			} else {
				$bulletinInfoTitleUpdateForm = $this->createForm(BulletinInfoTitleUpdateTForm::class, $bulletinInfoTitle);

				$bulletinInfoContent = new BulletinInfoContent();
				$bulletinInfoContent->setBulletinInfoTitle($bulletinInfoTitle);
				$bulletinInfoContentNewForm = $this->createForm(BulletinInfoContentNewTForm::class, $bulletinInfoContent, array('bulletinInfoTitle' => $bulletinInfoTitle));

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
				$this->getSession()->remove('tabActive');

				$this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
				$this->getSession()->remove('stabActive');

				$this->gvars['bulletinInfoTitle'] = $bulletinInfoTitle;

				$this->gvars['BulletinInfoTitleUpdateForm'] = $bulletinInfoTitleUpdateForm->createView();
				$this->gvars['BulletinInfoContentNewForm'] = $bulletinInfoContentNewForm->createView();

				$this->gvars['pagetitle'] = $this->translate('pagetitle.bulletinInfoTitle.edit',
					array('%bulletinInfoTitle%' => $bulletinInfoTitle->getTitle()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.bulletinInfoTitle.edit.txt',
					array('%bulletinInfoTitle%' => $bulletinInfoTitle->getTitle()));

				return $this->renderResponse('AcfAdminBundle:BulletinInfoTitle:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
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
			$urlFrom = $this->generateUrl('_admin_bulletinInfoTitle_list');
		}

		$em = $this->getEntityManager();
		try {
			$bulletinInfoTitle = $em->getRepository('AcfDataBundle:BulletinInfoTitle')->find($uid);

			if (null == $bulletinInfoTitle) {
				$this->flashMsgSession('warning', $this->translate('BulletinInfoTitle.edit.notfound'));
			} else {
				$bulletinInfoTitleUpdateForm = $this->createForm(BulletinInfoTitleUpdateTForm::class, $bulletinInfoTitle);

				$bulletinInfoContent = new BulletinInfoContent();
				$bulletinInfoContent->setBulletinInfoTitle($bulletinInfoTitle);
				$bulletinInfoContentNewForm = $this->createForm(BulletinInfoContentNewTForm::class, $bulletinInfoContent, array('bulletinInfoTitle' => $bulletinInfoTitle));

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
				$this->getSession()->remove('tabActive');

				$this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
				$this->getSession()->remove('stabActive');

				$request = $this->getRequest();
				$reqData = $request->request->all();

				if (isset($reqData['BulletinInfoTitleUpdateForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$bulletinInfoTitleUpdateForm->handleRequest($request);
					if ($bulletinInfoTitleUpdateForm->isValid()) {
						$em->persist($bulletinInfoTitle);
						$em->flush();
						$this->flashMsgSession('success',
							$this->translate('BulletinInfoTitle.edit.success', array('%bulletinInfoTitle%' => $bulletinInfoTitle->getTitle())));

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($bulletinInfoTitle);

						$this->flashMsgSession('error',
							$this->translate('BulletinInfoTitle.edit.failure', array('%bulletinInfoTitle%' => $bulletinInfoTitle->getTitle())));
					}
				} elseif (isset($reqData['BulletinInfoContentNewForm'])) {
					$this->gvars['tabActive'] = 3;
					$this->getSession()->set('tabActive', 3);
					$bulletinInfoContentNewForm->handleRequest($request);
					if ($bulletinInfoContentNewForm->isValid()) {
						$em->persist($bulletinInfoContent);
						$em->flush();
						$this->flashMsgSession('success',
							$this->translate('BulletinInfoContent.add.success', array('%bulletinInfoContent%' => $bulletinInfoContent->getTitle())));

						$this->gvars['stabActive'] = 2;
						$this->getSession()->set('stabActive', 2);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($bulletinInfoTitle);

						$this->flashMsgSession('error',
							$this->translate('BulletinInfoContent.add.failure'));
					}
				}

				$this->gvars['bulletinInfoTitle'] = $bulletinInfoTitle;

				$this->gvars['BulletinInfoTitleUpdateForm'] = $bulletinInfoTitleUpdateForm->createView();
				$this->gvars['BulletinInfoContentNewForm'] = $bulletinInfoContentNewForm->createView();

				$this->gvars['pagetitle'] = $this->translate('pagetitle.bulletinInfoTitle.edit',
					array('%bulletinInfoTitle%' => $bulletinInfoTitle->getTitle()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.bulletinInfoTitle.edit.txt',
					array('%bulletinInfoTitle%' => $bulletinInfoTitle->getTitle()));

				return $this->renderResponse('AcfAdminBundle:BulletinInfoTitle:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
		}

		return $this->redirect($urlFrom);
	}
}

?>
