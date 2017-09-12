<?php
namespace Acf\LiasseBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\LiasseFolder;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class LiasseFolderController extends BaseController
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
        $this->gvars['menu_active'] = 'liassehome';
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getEntityManager();
        $liasseFolders = $em->getRepository('AcfDataBundle:LiasseFolder')->getRoots();
        $this->gvars['liasseFolders'] = $liasseFolders;

        $this->gvars['smenu_active'] = 'list';
        $this->gvars['pagetitle'] = $this->translate('pagetitle.liasse.list');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.liasse.list.txt');

        return $this->renderResponse('AcfLiasseBundle:Default:index.html.twig', $this->gvars);
    }

    /**
     *
     * @param LiasseFolder $parent
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function childsAction(LiasseFolder $parent)
    {
        $em = $this->getEntityManager();
        $dg = $em->getRepository('AcfDataBundle:LiasseFolder')->find($parent);
        $this->gvars['parent'] = $dg;

        return $this->renderResponse('AcfLiasseBundle:LiasseFolder:childs.html.twig', $this->gvars);
    }
}
