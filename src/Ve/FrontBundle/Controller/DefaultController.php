<?php
namespace Ve\FrontBundle\Controller;

use Ve\FrontBundle\Form\SearchTForm;
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

        $searchForm = $this->createForm(SearchTForm::class, null, array());

        $this->gvars['SearchForm'] = $searchForm->createView();

        $this->gvars['pagetitle'] = $this->translate('pagetitle.dasboard2');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.dasboard2');

        return $this->renderResponse('VeFrontBundle:Default:index.html.twig', $this->gvars);
    }

    public function searchAction()
    {
        $em = $this->getEntityManager();

        $request = $this->getRequest();

        $country = $request->query->get('SearchForm[country]', null);
        $nature = $request->query->get('SearchForm[nature]', null);
        $dtPublicationBegin = $request->query->get('SearchForm[dtPublicationBegin]', null);
        $dtPublicationEnd = $request->query->get('SearchForm[dtPublicationEnd]', null);
        $dtEndBegin = $request->query->get('SearchForm[dtEndBegin]', null);
        $dtEndEnd = $request->query->get('SearchForm[dtEndEnd]', null);
        $dtOpenBegin = $request->query->get('SearchForm[dtOpenBegin]', null);
        $dtOpenEnd = $request->query->get('SearchForm[dtOpenEnd]', null);

        $searchFormParms = $request->query->get('SearchForm', null);
        if (null != $searchFormParms && \is_array($searchFormParms)) {
            if (isset($searchFormParms['country'])) {
                $country = $searchFormParms['country'];
            }
            if (isset($searchFormParms['nature'])) {
                $nature = $searchFormParms['nature'];
            }
            if (isset($searchFormParms['dtPublicationBegin'])) {
                $dtPublicationBegin = $searchFormParms['dtPublicationBegin'];
                if (\DateTime::createFromFormat('Y-m-d', $dtPublicationBegin) !== FALSE) {
                    $dtPublicationBegin = new \DateTime($dtPublicationBegin);
                } else {
                    $dtPublicationBegin = null;
                }
            }
            if (isset($searchFormParms['dtPublicationEnd'])) {
                $dtPublicationEnd = $searchFormParms['dtPublicationEnd'];
                if (\DateTime::createFromFormat('Y-m-d', $dtPublicationEnd) !== FALSE) {
                    $dtPublicationEnd = new \DateTime($dtPublicationEnd);
                } else {
                    $dtPublicationEnd = null;
                }
            }
            if (isset($searchFormParms['dtEndBegin'])) {
                $dtEndBegin = $searchFormParms['dtEndBegin'];
                if (\DateTime::createFromFormat('Y-m-d', $dtEndBegin) !== FALSE) {
                    $dtEndBegin = new \DateTime($dtEndBegin);
                } else {
                    $dtEndBegin = null;
                }
            }
            if (isset($searchFormParms['dtEndEnd'])) {
                $dtEndEnd = $searchFormParms['dtEndEnd'];
                if (\DateTime::createFromFormat('Y-m-d', $dtEndEnd) !== FALSE) {
                    $dtEndEnd = new \DateTime($dtEndEnd);
                } else {
                    $dtEndEnd = null;
                }
            }
            if (isset($searchFormParms['dtOpenBegin'])) {
                $dtOpenBegin = $searchFormParms['dtOpenBegin'];
                if (\DateTime::createFromFormat('Y-m-d', $dtOpenBegin) !== FALSE) {
                    $dtOpenBegin = new \DateTime($dtOpenBegin);
                } else {
                    $dtOpenBegin = null;
                }
            }
            if (isset($searchFormParms['dtOpenEnd'])) {
                $dtOpenEnd = $searchFormParms['dtOpenEnd'];
                if (\DateTime::createFromFormat('Y-m-d', $dtOpenEnd) !== FALSE) {
                    $dtOpenEnd = new \DateTime($dtOpenEnd);
                } else {
                    $dtOpenEnd = null;
                }
            }
        }

        if (null != $dtPublicationBegin && null != $dtPublicationEnd) {
            if ($dtPublicationBegin > $dtPublicationEnd) {
                $tmpdate = $dtPublicationBegin;
                $dtPublicationBegin = $dtPublicationEnd;
                $dtPublicationEnd = $tmpdate;
            }
        }

        if (null != $dtEndBegin && null != $dtEndEnd) {
            if ($dtEndBegin > $dtEndEnd) {
                $tmpdate = $dtEndBegin;
                $dtEndBegin = $dtEndEnd;
                $dtEndEnd = $tmpdate;
            }
        }

        if (null != $dtOpenBegin && null != $dtOpenEnd) {
            if ($dtOpenBegin > $dtOpenEnd) {
                $tmpdate = $dtOpenBegin;
                $dtOpenBegin = $dtOpenEnd;
                $dtOpenEnd = $tmpdate;
            }
        }

        $data = array(
            'country' => $country,
            'nature' => $nature,
            'dtPublicationBegin' => $dtPublicationBegin,
            'dtPublicationEnd' => $dtPublicationEnd,
            'dtEndBegin' => $dtEndBegin,
            'dtEndEnd' => $dtEndEnd,
            'dtOpenBegin' => $dtOpenBegin,
            'dtOpenEnd' => $dtOpenEnd
        );

        $auctions = $em->getRepository('AcfDataBundle:AoAuction')->getSearchFront($data);
        $this->gvars['auctions'] = $auctions;

        $searchForm = $this->createForm(SearchTForm::class, null, $data);

        $this->gvars['SearchForm'] = $searchForm->createView();

        $this->gvars['pagetitle'] = $this->translate('pagetitle.dasboard2');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.dasboard2');

        return $this->renderResponse('VeFrontBundle:Default:search.html.twig', $this->gvars);
    }
}
