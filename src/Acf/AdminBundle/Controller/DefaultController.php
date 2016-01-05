<?php

namespace Acf\AdminBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\User;

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
		$this->gvars['menu_active'] = 'adminhome';
	}

	public function indexAction()
	{
		$em = $this->getEntityManager();
		$sc = $this->getSecurityTokenStorage();
		$ac = $this->getSecurityAuthorizationChecker();
		$user = $sc->getToken()
			->getUser();

		$minDate = new \DateTime('now');
		$minDate->modify('-7 days');

		if ($ac->isGranted('ROLE_SUPERADMIN', $user)) {
			$traces = $em->getRepository('AcfDataBundle:Trace')
				->getAll($minDate);
		} else {
			$traces = $em->getRepository('AcfDataBundle:Trace')
				->getAllByAdmin($user, $minDate);
		}
		$this->gvars['traces'] = array_reverse($traces);

		$activeUser = $em->getRepository('AcfDataBundle:User')->getAllActiveNow('30 minutes ago');
		$this->gvars['activeUsers'] = $activeUser;

		return $this->renderResponse('AcfAdminBundle:Default:index.html.twig', $this->gvars);
	}
}
