<?php
namespace Ve\FrontBundle\Controller;

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
        $this->gvars['menu_active'] = 'vehome';
    }

    public function indexAction()
    {
        $em = $this->getEntityManager();

        $auctions = $em->getRepository('AcfDataBundle:AoAuction')->getAllFront();
        $this->gvars['auctions'] = $auctions;

        $this->gvars['pagetitle'] = $this->translate('pagetitle.dasboard2');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.dasboard2');

        return $this->renderResponse('VeFrontBundle:Default:index.html.twig', $this->gvars);
    }
}
