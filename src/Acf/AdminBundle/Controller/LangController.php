<?php
namespace Acf\AdminBundle\Controller;

use Acf\DataBundle\Entity\Lang;
use Acf\AdminBundle\Form\Lang\NewTForm as LangNewTForm;
use Acf\AdminBundle\Form\Lang\UpdateDirectionTForm as LangUpdateDirectionTForm;
use Acf\AdminBundle\Form\Lang\UpdateStatusTForm as LangUpdateStatusTForm;
use Sasedev\Commons\SharedBundle\Controller\BaseController;

/**
 * @author sasedev <seif.salah@gmail.com>
 *
 */
class LangController extends BaseController
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

		$this->gvars['menu_active'] = 'lang';

	}

	public function listAction()
	{
		if (! $this->hasRole('ROLE_SUPERSUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$em = $this->getEntityManager();
		$langs = $em->getRepository('AcfDataBundle:Lang')->getAll();
		$this->gvars['langs'] = $langs;

		$this->gvars['smenu_active'] = 'list';
		$this->gvars['pagetitle'] = $this->translate('pagetitle.lang.list');
		$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.lang.list.txt');
		return $this->renderResponse('AcfAdminBundle:Lang:list.html.twig', $this->gvars);
	}

	public function addGetAction()
	{
		if (! $this->hasRole('ROLE_SUPERSUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$lang = new Lang();
		$langNewForm = $this->createForm(LangNewTForm::class, $lang);
		$this->gvars['LangNewForm'] = $langNewForm->createView();

		$this->gvars['pagetitle'] = $this->translate('pagetitle.lang.add');
		$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.lang.add.txt');
		$this->gvars['smenu_active'] = 'add';

		return $this->renderResponse('AcfAdminBundle:Lang:add.html.twig', $this->gvars);
	}

	public function addPostAction()
	{
		if (! $this->hasRole('ROLE_SUPERSUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('_admin_lang_addGet'));
		}

		$lang = new Lang();
		$langNewForm = $this->createForm(LangNewTForm::class, $lang);

		$request = $this->getRequest();
		$reqData = $request->request->all();

		if (isset($reqData['LangNewForm'])) {
			$langNewForm->handleRequest($request);
			if ($langNewForm->isValid()) {
				$em = $this->getEntityManager();
				$em->persist($lang);
				$em->flush();
				$this->flashMsgSession(
					'success',
					$this->translate('Lang.add.success', array('%lang%' => $lang->getLocale()))
				);

				return $this->redirect(
					$this->generateUrl('_admin_lang_editGet', array('id' => $lang->getId()))
				);
			} else {
				$this->flashMsgSession(
					'error',
					$this->translate('Lang.add.failure')
				);
			}
		}
		$this->gvars['LangNewForm'] = $langNewForm->createView();

		$this->gvars['pagetitle'] = $this->translate('pagetitle.lang.add');
		$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.lang.add.txt');
		$this->gvars['smenu_active'] = 'add';

		return $this->renderResponse('AcfAdminBundle:Lang:add.html.twig', $this->gvars);
	}

	public function deleteAction($id)
	{
		if (! $this->hasRole('ROLE_SUPERSUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_admin_lang_list');
		}
		$em = $this->getEntityManager();
		try {
			$lang = $em->getRepository('AcfDataBundle:Lang')->find($id);

			if (null == $lang) {
				$this->flashMsgSession('warning', $this->translate('Lang.delete.notfound'));
			} else {
				$em->remove($lang);
				$em->flush();

				$this->flashMsgSession(
					'success',
					$this->translate('Lang.delete.success', array('%lang%' => $lang->getLocale()))
				);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());

			$this->flashMsgSession('error', $this->translate('Lang.delete.failure'));
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
			$urlFrom = $this->generateUrl('_admin_lang_list');
		}

		$em = $this->getEntityManager();
		try {
			$lang = $em->getRepository('AcfDataBundle:Lang')->find($id);

			if (null == $lang) {
				$this->flashMsgSession('warning', $this->translate('Lang.edit.notfound'));
			} else {
				$langUpdateDirectionForm = $this->createForm(LangUpdateDirectionTForm::class, $lang);
				$langUpdateStatusForm = $this->createForm(LangUpdateStatusTForm::class, $lang);

				$this->gvars['lang'] = $lang;
				$this->gvars['LangUpdateDirectionForm'] = $langUpdateDirectionForm->createView();
				$this->gvars['LangUpdateStatusForm'] = $langUpdateStatusForm->createView();

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
				$this->getSession()->remove('tabActive');



				$this->gvars['pagetitle'] = $this->translate('pagetitle.lang.edit', array('%lang%' => $lang->getLocale()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.lang.edit.txt', array('%lang%' => $lang->getLocale()));

				return $this->renderResponse('AcfAdminBundle:Lang:edit.html.twig', $this->gvars);
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
			return $this->redirect($this->generateUrl('_admin_lang_list'));
		}

		$em = $this->getEntityManager();
		try {
			$lang = $em->getRepository('AcfDataBundle:Lang')->find($id);

			if (null == $lang) {
				$this->flashMsgSession('warning', $this->translate('Lang.edit.notfound'));
			} else {
				$langUpdateDirectionForm = $this->createForm(LangUpdateDirectionTForm::class, $lang);
				$langUpdateStatusForm = $this->createForm(LangUpdateStatusTForm::class, $lang);



				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
				$this->getSession()->remove('tabActive');

				$request = $this->getRequest();
				$reqData = $request->request->all();

				if (isset($reqData['LangUpdateDirectionForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$langUpdateDirectionForm->handleRequest($request);
					if ($langUpdateDirectionForm->isValid()) {
						$em->persist($lang);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('Lang.edit.success', array('%lang%' => $lang->getLocale()))
						);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($lang);

						$this->flashMsgSession(
							'error',
							$this->translate('Lang.edit.failure', array('%lang%' => $lang->getLocale()))
						);
					}
				} elseif (isset($reqData['LangUpdateStatusForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$langUpdateStatusForm->handleRequest($request);
					if ($langUpdateStatusForm->isValid()) {
						$em->persist($lang);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('Lang.edit.success', array('%lang%' => $lang->getLocale()))
						);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($lang);

						$this->flashMsgSession(
							'error',
							$this->translate('Lang.edit.failure', array('%lang%' => $lang->getLocale()))
						);
					}
				}

				$this->gvars['lang'] = $lang;
				$this->gvars['LangUpdateDirectionForm'] = $langUpdateDirectionForm->createView();
				$this->gvars['LangUpdateStatusForm'] = $langUpdateStatusForm->createView();

				$this->gvars['pagetitle'] = $this->translate('pagetitle.lang.edit', array('%lang%' => $lang->getLocale()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.lang.edit.txt', array('%lang%' => $lang->getLocale()));

				return $this->renderResponse('AcfAdminBundle:Lang:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());
		}

		return $this->redirect($urlFrom);

	}
}
