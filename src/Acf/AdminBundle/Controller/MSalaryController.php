<?php
namespace Acf\AdminBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class MSalaryController extends BaseController
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
        $this->gvars['menu_active'] = 'company';
    }

    /**
     *
     * @param string $uid
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($uid)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_company_list');
        }
        $em = $this->getEntityManager();
        try {
            $msalary = $em->getRepository('AcfDataBundle:MSalary')->find($uid);

            if (null == $msalary) {
                $this->flashMsgSession('warning', $this->translate('MSalary.delete.notfound'));
            } else {
                $em->remove($msalary);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('MSalary.delete.success', array(
                    '%msalary%' => $msalary->getMatricule()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('MSalary.delete.failure'));
        }

        return $this->redirect($urlFrom);
    }
}
