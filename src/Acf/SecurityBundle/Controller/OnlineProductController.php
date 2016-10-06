<?php
namespace Acf\SecurityBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\OnlineProduct;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class OnlineProductController extends BaseController
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
        $this->gvars['menu_active'] = 'shopping';
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction()
    {
        $session = $this->getSession();

        $prods = $session->get("OnlineProducts", array());
        $this->gvars['OnlineProducts'] = $prods;
        $session->set("OnlineProducts", $prods);

        $em = $this->getEntityManager();
        $products = $em->getRepository("AcfDataBundle:OnlineProduct")->getAllVisible();

        $this->gvars['products'] = $products;

        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.onlineProduct.txt');
        $this->gvars['pagetitle'] = $this->translate('pagetitle.onlineProduct');

        return $this->renderResponse('AcfSecurityBundle:Product:index.html.twig', $this->gvars);
    }
}