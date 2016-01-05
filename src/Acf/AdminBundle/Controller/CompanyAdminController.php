<?php
namespace Acf\AdminBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;

/**
 *
 * @author sasedev
 */
class CompanyAdminController extends BaseController
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

	public function deleteAction($uid)
	{
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_admin_company_list');
		}
		$em = $this->getEntityManager();
		try {
			$companyAdmin = $em->getRepository('AcfDataBundle:CompanyAdmin')->find($uid);

			if (null == $companyAdmin) {
				$this->flashMsgSession('warning', $this->translate('CompanyAdmin.delete.notfound'));
			} else {


				$em->remove($companyAdmin);
				$em->flush();

				$this->flashMsgSession(
					'success',
					$this->translate('CompanyAdmin.delete.success', array('%company%' => $companyAdmin->getCompany()->getCorporateName(), '%user%' => $companyAdmin->getUser()->getFullName()))
				);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());

			$this->flashMsgSession('error', $this->translate('CompanyAdmin.delete.failure'));
		}

		return $this->redirect($urlFrom);

	}
}