<?php

namespace Acf\InfoBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;

class DefaultController extends BaseController
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

		$this->gvars['menu_active'] = 'bihome';

	}

	public function indexAction()
	{
		$em = $this->getEntityManager();
		$bulletinInfos = $em->getRepository('AcfDataBundle:BulletinInfo')->getAll();
		$this->gvars['bulletinInfos'] = $bulletinInfos;
		$biDocs = $em->getRepository('AcfDataBundle:BiDoc')->getAll();
		$this->gvars['biDocs'] = $biDocs;

		$this->gvars['smenu_active'] = 'list';
		$this->gvars['pagetitle'] = $this->translate('pagetitle.info.bulletinInfo.list');
		$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.info.bulletinInfo.list.txt');

		return $this->renderResponse('AcfInfoBundle:Default:index.html.twig', $this->gvars);

	}

	public function editGetAction($uid)
	{

		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_info_homepage');
		}

		$em = $this->getEntityManager();
		try {
			$bulletinInfo = $em->getRepository('AcfDataBundle:BulletinInfo')->find($uid);

			if (null == $bulletinInfo) {
				$this->flashMsgSession('warning', $this->translate('BulletinInfo.edit.notfound'));
			} else {
				$bulletinInfo->setNbrClicks($bulletinInfo->getNbrClicks() + 1);
				$em->persist($bulletinInfo);
				$em->flush();

				$this->gvars['bulletinInfo'] = $bulletinInfo;

				$this->gvars['pagetitle'] = $this->translate('pagetitle.info.bulletinInfo.edit',
					array('%bulletinInfo%' => $bulletinInfo->getNum()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.info.bulletinInfo.edit.txt',
					array('%bulletinInfo%' => $bulletinInfo->getNum()));

				return $this->renderResponse('AcfInfoBundle:Default:show.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
		}

		return $this->redirect($urlFrom);



	}

}
