<?php
namespace Acf\AdminBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\CompanyNature;
use Acf\AdminBundle\Form\CompanyNature\UpdateTForm as CompanyNatureUpdateTForm;
use Acf\DataBundle\Entity\Trace;

/**
 *
 * @author sasedev
 */
class CompanyNatureController extends BaseController
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
			$urlFrom = $this->generateUrl('_admin_companyNature_list');
		}
		$em = $this->getEntityManager();
		try {
			$companyNature = $em->getRepository('AcfDataBundle:CompanyNature')->find($uid);

			if (null == $companyNature) {
				$this->flashMsgSession('warning', $this->translate('CompanyNature.delete.notfound'));
			} else {
				$em->remove($companyNature);
				$em->flush();

				$this->flashMsgSession(
					'success',
					$this->translate('CompanyNature.delete.success', array('%companyNature%' => $companyNature->getLabel()))
				);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());

			$this->flashMsgSession('error', $this->translate('CompanyNature.delete.failure'));
		}

		return $this->redirect($urlFrom);

	}

	public function editGetAction($uid)
	{
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_admin_company_list');
		}

		$em = $this->getEntityManager();
		try {
			$companyNature = $em->getRepository('AcfDataBundle:CompanyNature')->find($uid);

			if (null == $companyNature) {
				$this->flashMsgSession('warning', $this->translate('CompanyNature.edit.notfound'));
			} else {
				$traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($companyNature->getId(), Trace::AE_NATURE);
				$this->gvars['traces'] = array_reverse($traces);
				$companyNatureUpdateForm = $this->createForm(CompanyNatureUpdateTForm::class, $companyNature);


				$this->gvars['companyNature'] = $companyNature;
				$this->gvars['CompanyNatureUpdateForm'] = $companyNatureUpdateForm->createView();

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
				$this->getSession()->remove('tabActive');



				$this->gvars['pagetitle'] = $this->translate('pagetitle.companyNature.edit', array('%companyNature%' => $companyNature->getLabel()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.companyNature.edit.txt', array('%companyNature%' => $companyNature->getLabel()));

				return $this->renderResponse('AcfAdminBundle:CompanyNature:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());
		}

		return $this->redirect($urlFrom);
	}

	public function editPostAction($uid)
	{
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_admin_company_list');
		}

		$em = $this->getEntityManager();
		try {
			$companyNature = $em->getRepository('AcfDataBundle:CompanyNature')->find($uid);

			if (null == $companyNature) {
				$this->flashMsgSession('warning', $this->translate('CompanyNature.edit.notfound'));
			} else {
				$traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($companyNature->getId(), Trace::AE_NATURE);
				$this->gvars['traces'] = array_reverse($traces);
				$companyNatureUpdateForm = $this->createForm(CompanyNatureUpdateTForm::class, $companyNature);



				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
				$this->getSession()->remove('tabActive');

				$request = $this->getRequest();
				$reqData = $request->request->all();

				$cloneCompanyNature = clone $companyNature;

				if (isset($reqData['CompanyNatureUpdateForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$companyNatureUpdateForm->handleRequest($request);
					if ($companyNatureUpdateForm->isValid()) {
						$em->persist($companyNature);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('CompanyNature.edit.success', array('%companyNature%' => $companyNature->getLabel()))
						);

						$this->traceEntity($cloneCompanyNature, $companyNature);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($companyNature);

						$this->flashMsgSession('error', $this->translate('CompanyNature.edit.failure', array('%companyNature%' => $companyNature->getLabel())));
					}
				}

				$this->gvars['companyNature'] = $companyNature;
				$this->gvars['CompanyNatureUpdateForm'] = $companyNatureUpdateForm->createView();



				$this->gvars['pagetitle'] = $this->translate('pagetitle.companyNature.edit', array('%companyNature%' => $companyNature->getLabel()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.companyNature.edit.txt', array('%companyNature%' => $companyNature->getLabel()));

				return $this->renderResponse('AcfAdminBundle:CompanyNature:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());
		}

		return $this->redirect($urlFrom);
	}

	protected function traceEntity(CompanyNature $cloneCompanyNature, CompanyNature $companyNature) {

		$curUser = $this->getSecurityTokenStorage()->getToken()->getUser();
		$trace = new Trace();
		$trace->setActionId($companyNature->getId());
		$trace->setActionType(Trace::AT_UPDATE);
		$trace->setUserId($curUser->getId());
		$trace->setCompanyId($companyNature->getCompany()->getId());
		$trace->setUserFullname($curUser->getFullName());
		if (! $this->hasRole('ROLE_SUPERADMIN')) {
			if (! $this->hasRole('ROLE_ADMIN')) {
				$trace->setUserType(Trace::UT_CLIENT);
			} else {
				$trace->setUserType(Trace::UT_ADMIN);
			}

		} else {
			$trace->setUserType(Trace::UT_SUPERADMIN);
		}



		$table_begin = ': <br><table class="table table-bordered table-condensed table-hover table-striped">';
		$table_begin .= '<thead><tr><th class="text-left">'.$this->translate('Entity.field').'</th>';
		$table_begin .= '<th class="text-left">'.$this->translate('Entity.oldVal').'</th>';
		$table_begin .= '<th class="text-left">'.$this->translate('Entity.newVal').'</th></tr></thead><tbody>';

		$table_end = '</tbody></table>';

		$trace->setActionEntity(Trace::AE_NATURE);
		$trace->setActionId2($companyNature->getCompany()->getId());
		$trace->setActionEntity2(Trace::AE_COMPANY);

		$msg = "";

		if ($cloneCompanyNature->getLabel() != $companyNature->getLabel()) {
			$msg .= "<tr><td>".$this->translate('CompanyNature.label.label').'</td><td>';
			if ($cloneCompanyNature->getLabel() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneCompanyNature->getLabel();
			}
			$msg .= "</td><td>";
			if ($companyNature->getLabel() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $companyNature->getLabel();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneCompanyNature->getColor() != $companyNature->getColor()) {
			$msg .= "<tr><td>".$this->translate('CompanyNature.color.label').'</td><td>';
			if ($cloneCompanyNature->getColor() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneCompanyNature->getColor(). ' &nbsp; <span style="display:inline-block; background-color: '.$cloneCompanyNature->getColor().';  border: 1px solid '.$cloneCompanyNature->getColor().'; width: 60px; height: 20px;"></span>&nbsp;';
			}
			$msg .= "</td><td>";
			if ($companyNature->getColor() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $companyNature->getColor(). ' &nbsp; <span style="display:inline-block; background-color: '.$companyNature->getColor().';  border: 1px solid '.$companyNature->getColor().'; width: 60px; height: 20px;"></span>&nbsp;';
			}
			$msg .= "</td></tr>";
		}

		if ($msg != "") {

			$msg = $table_begin.$msg.$table_end;

			$trace->setMsg(
				$this->translate(
					'CompanyNature.traceEdit',
					array('%companyNature%' => $companyNature->getLabel(), '%company%' => $companyNature->getCompany()->getCorporateName())
					).$msg
				);
			$trace->setDtCrea(new \DateTime('now'));
			$em = $this->getEntityManager();
			$em->persist($trace);
			$em->flush();
		}
	}
}