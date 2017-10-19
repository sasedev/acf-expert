<?php
namespace Acf\ToolsBundle\Controller;

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
        $this->gvars['menu_active'] = 'toolshome';
    }

    public function indexAction()
    {
        $this->gvars['smenu_active'] = 'list';
        $this->gvars['pagetitle'] = $this->translate('pagetitle.tools.index');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.tools.index.txt');

        return $this->renderResponse('AcfToolsBundle:Default:index.html.twig', $this->gvars);
    }
}
