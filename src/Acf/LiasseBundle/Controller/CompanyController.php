<?php
namespace Acf\LiasseBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class CompanyController extends BaseController
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
     * @param string $uid
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editGetAction($uid)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_liasse_homepage');
        }

        $em = $this->getEntityManager();
        try {
            $company = $em->getRepository('AcfDataBundle:Company')->find($uid);

            if (null == $company) {
                $this->flashMsgSession('warning', $this->translate('Company.edit.notfound'));
            } else {

                $sc = $this->getSecurityTokenStorage();
                $user = $sc->getToken()->getUser();

                $companyUser = $em->getRepository('AcfDataBundle:CompanyUser')->findOneBy(array(
                    'company' => $company,
                    'user' => $user
                ));
                if (null == $companyUser) {
                    return $this->redirect($this->generateUrl('_liasse_homepage'));
                }

                $liasseFolders = $em->getRepository('AcfDataBundle:LiasseFolder')->getRoots($company);
                $this->gvars['liasseFolders'] = $liasseFolders;

                $this->gvars['companyUser'] = $companyUser;
                $this->gvars['menu_active'] = 'liasse' . $company->getId();

                $this->gvars['company'] = $company;

                $this->gvars['pagetitle'] = $this->translate('pagetitle.company.edit', array(
                    '%company%' => $company->getCorporateName()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.company.edit.txt', array(
                    '%company%' => $company->getCorporateName()
                ));

                return $this->renderResponse('AcfLiasseBundle:LiasseFolder:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }
}
