<?php
namespace Ao\FrontBundle\Controller;

use Ao\FrontBundle\Form\SearchTForm;
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

        $searchForm = $this->createForm(SearchTForm::class, null, array());

        $this->gvars['SearchForm'] = $searchForm->createView();

        $this->gvars['pagetitle'] = $this->translate('pagetitle.dasboard2');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.dasboard2');

        return $this->renderResponse('AoFrontBundle:Default:index.html.twig', $this->gvars);
    }

    public function searchAction()
    {
        $em = $this->getEntityManager();

        $request = $this->getRequest();

        $country = $request->query->get('SearchForm[country]', null);
        $grp = $request->query->get('SearchForm[grp]', null);
        $typeAvis = $request->query->get('SearchForm[typeAvis]', null);
        $nature = $request->query->get('SearchForm[nature]', null);
        $dtPublicationBegin = $request->query->get('SearchForm[dtPublicationBegin]', null);
        $dtPublicationEnd = $request->query->get('SearchForm[dtPublicationEnd]', null);
        $dtEndBegin = $request->query->get('SearchForm[dtEndBegin]', null);
        $dtEndEnd = $request->query->get('SearchForm[dtEndEnd]', null);

        $searchFormParms = $request->query->get('SearchForm', null);
        if (null != $searchFormParms && \is_array($searchFormParms)) {
            if (isset($searchFormParms['country'])) {
                $country = $searchFormParms['country'];
            }
            if (isset($searchFormParms['grp'])) {
                $grp = $searchFormParms['grp'];
                $UUIDv4 = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';
                if (!preg_match($UUIDv4, $grp)) {
                    $grp = null;
                }
            }
            if (isset($searchFormParms['typeAvis'])) {
                $typeAvis = $searchFormParms['typeAvis'];
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

        $data = array(
            'country' => $country,
            'grp' => $grp,
            'typeAvis' => $typeAvis,
            'nature' => $nature,
            'dtPublicationBegin' => $dtPublicationBegin,
            'dtPublicationEnd' => $dtPublicationEnd,
            'dtEndBegin' => $dtEndBegin,
            'dtEndEnd' => $dtEndEnd
        );

        $callfortenders = $em->getRepository('AcfDataBundle:AoCallfortender')->getSearchFront($data);
        $this->gvars['callfortenders'] = $callfortenders;

        $searchForm = $this->createForm(SearchTForm::class, null, $data);

        $this->gvars['SearchForm'] = $searchForm->createView();

        $this->gvars['pagetitle'] = $this->translate('pagetitle.dasboard2');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.dasboard2');

        return $this->renderResponse('AoFrontBundle:Default:search.html.twig', $this->gvars);
    }
}
