<?php

namespace Acf\FrontBundle\Controller;

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

		$this->gvars['menu_active'] = 'home';

	}

	public function indexAction()
	{
		return $this->renderResponse('AcfFrontBundle:Default:index.html.twig', $this->gvars);
	}
}
