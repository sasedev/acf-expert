<?php
namespace Ao\FrontBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class CategController extends BaseController
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

    /**
     *
     * @param string $uid
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editGetAction($uid)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('ao_front_homepage');
        }
        $em = $this->getEntityManager();
        try {
            $categ = $em->getRepository('AcfDataBundle:AoCateg')->find($uid);

            if (null == $categ) {
                $this->flashMsgSession('warning', $this->translate('AoCateg.edit.notfound'));
            } else {

                $callfortenders = $em->getRepository('AcfDataBundle:AoCallfortender')->getAllFrontByCateg($categ);
                $this->gvars['callfortenders'] = $callfortenders;

                $this->gvars['categ'] = $categ;

                $this->gvars['pagetitle'] = $categ->getTitle();
                $this->gvars['pagetitle_txt'] = $categ->getTitle();

                return $this->renderResponse('AoFrontBundle:AoCateg:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }
}

