<?php
namespace Ao\FrontBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class SubCategController extends BaseController
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
            $subCateg = $em->getRepository('AcfDataBundle:AoSubCateg')->find($uid);

            if (null == $subCateg) {
                $this->flashMsgSession('warning', $this->translate('AoSubCateg.edit.notfound'));
            } else {

                $advertisements = $em->getRepository('AcfDataBundle:AoAdvertisement')->getAllFrontByGrp($subCateg);
                $this->gvars['advertisements'] = $advertisements;

                $this->gvars['subCateg'] = $subCateg;

                $this->gvars['pagetitle'] = $subCateg->getRef() . ' : ' . $subCateg->getTitle();
                $this->gvars['pagetitle_txt'] = $subCateg->getRef() . ' : ' . $subCateg->getTitle();

                return $this->renderResponse('AoFrontBundle:AoSubCateg:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }
}

