<?php
namespace Acf\InfoBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\BiFolder;

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
        $this->gvars['menu_active'] = 'bihome';
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getEntityManager();
        $bulletinInfos = $em->getRepository('AcfDataBundle:BulletinInfo')->getAll();
        $this->gvars['bulletinInfos'] = $bulletinInfos;
        $biFolders = $em->getRepository('AcfDataBundle:BiFolder')->getRoots();
        $this->gvars['biFolders'] = $biFolders;

        $this->gvars['smenu_active'] = 'list';
        $this->gvars['pagetitle'] = $this->translate('pagetitle.info.bulletinInfo.list');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.info.bulletinInfo.list.txt');

        return $this->renderResponse('AcfInfoBundle:Default:index.html.twig', $this->gvars);
    }

    /**
     *
     * @param string $uid
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editGetAction($uid)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_info_homepage');
        }

        $em = $this->getEntityManager();
        try {
            $bulletinInfo = $em->getRepository('AcfDataBundle:BulletinInfo')->find($uid);

            if (null == $bulletinInfo) {
                $this->flashMsgSession('warning', $this->translate('BulletinInfo.edit.notfound'));
            } else {
                $bulletinInfo->setNbrClicks($bulletinInfo->getNbrClicks() + 1);
                $em->persist($bulletinInfo);
                $em->flush();

                $this->gvars['bulletinInfo'] = $bulletinInfo;

                $this->gvars['pagetitle'] = $this->translate('pagetitle.info.bulletinInfo.edit', array(
                    '%bulletinInfo%' => $bulletinInfo->getNum()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.info.bulletinInfo.edit.txt', array(
                    '%bulletinInfo%' => $bulletinInfo->getNum()
                ));

                return $this->renderResponse('AcfInfoBundle:Default:show.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }

    /**
     *
     * @param BiFolder $parent
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function childsAction(BiFolder $parent)
    {
        $em = $this->getEntityManager();
        $dg = $em->getRepository('AcfDataBundle:BiFolder')->find($parent);
        $this->gvars['parent'] = $dg;

        return $this->renderResponse('AcfInfoBundle:BiFolder:childs.html.twig', $this->gvars);
    }
}
