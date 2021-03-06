<?php
namespace Acf\LiasseBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
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
        $this->gvars['menu_active'] = 'liassehome';
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $sc = $this->getSecurityTokenStorage();
        $user = $sc->getToken()->getUser();
        $em = $this->getEntityManager();

        $companies = $user->getCompanies();
        $renderCompanies = array();

        foreach ($companies as $company) {
            $renderCompany = array();
            $renderCompany['company'] = $company;

            $liasseFolders = $em->getRepository('AcfDataBundle:LiasseFolder')->getRoots($company);
            $renderCompany['liasseFolders'] = $liasseFolders;

            $renderCompanies[] = $renderCompany;
        }

        $this->gvars['companies'] = $renderCompanies;

        $this->gvars['smenu_active'] = 'list';
        $this->gvars['pagetitle'] = $this->translate('pagetitle.liasse.list');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.liasse.list.txt');

        return $this->renderResponse('AcfLiasseBundle:Default:index.html.twig', $this->gvars);
    }
}

