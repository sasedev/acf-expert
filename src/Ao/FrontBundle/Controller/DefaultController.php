<?php
namespace Ao\FrontBundle\Controller;

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
        $this->gvars['menu_active'] = 'aohome';
    }

    public function indexAction()
    {
        $em = $this->getEntityManager();

        $callfortenders = $em->getRepository('AcfDataBundle:AoCallfortender')->getAllFront();
        $this->gvars['callfortenders'] = $callfortenders;

        $this->gvars['pagetitle'] = $this->translate('pagetitle.dasboard2');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.dasboard2');

        return $this->renderResponse('AoFrontBundle:Default:index.html.twig', $this->gvars);
    }
}
