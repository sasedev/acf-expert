<?php
namespace Acf\AdminBundle\Controller;


use Acf\DataBundle\Entity\CompanyType;
use Acf\AdminBundle\Form\CompanyType\NewTForm as CompanyTypeNewTForm;
use Acf\AdminBundle\Form\CompanyType\UpdateTForm as CompanyTypeUpdateTForm;
use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\Trace;

/**
 * @author sasedev
 *
 */
class CompanyTypeController extends BaseController
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

		$this->gvars['menu_active'] = 'companyType';

	}

	public function listAction()
	{
		$em = $this->getEntityManager();
		$companyTypes = $em->getRepository('AcfDataBundle:CompanyType')->getAll();
		$this->gvars['companyTypes'] = $companyTypes;

		$this->gvars['smenu_active'] = 'list';
		$this->gvars['pagetitle'] = $this->translate('pagetitle.companyType.list');
		$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.companyType.list.txt');
		return $this->renderResponse('AcfAdminBundle:CompanyType:list.html.twig', $this->gvars);
	}

	public function addGetAction()
	{
		$companyType = new CompanyType();
		$companyTypeNewForm = $this->createForm(CompanyTypeNewTForm::class, $companyType);
		$this->gvars['companyType'] = $companyType;
		$this->gvars['CompanyTypeNewForm'] = $companyTypeNewForm->createView();

		$this->gvars['pagetitle'] = $this->translate('pagetitle.companyType.add');
		$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.companyType.add.txt');
		$this->gvars['smenu_active'] = 'add';

		return $this->renderResponse('AcfAdminBundle:CompanyType:add.html.twig', $this->gvars);
	}

	public function addPostAction()
	{
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('_admin_companyType_addGet'));
		}

		$companyType = new CompanyType();
		$companyTypeNewForm = $this->createForm(CompanyTypeNewTForm::class, $companyType);

		$request = $this->getRequest();
		$reqData = $request->request->all();

		if (isset($reqData['CompanyTypeNewForm'])) {
			$companyTypeNewForm->handleRequest($request);
			if ($companyTypeNewForm->isValid()) {
				$em = $this->getEntityManager();
				$em->persist($companyType);
				$em->flush();
				$this->flashMsgSession(
					'success',
					$this->translate('CompanyType.add.success', array('%companyType%' => $companyType->getLabel()))
				);

				return $this->redirect(
					$this->generateUrl('_admin_companyType_editGet', array('id' => $companyType->getId()))
				);
			} else {
				$this->flashMsgSession(
					'error',
					$this->translate('CompanyType.add.failure')
				);
			}
		}
		$this->gvars['companyType'] = $companyType;
		$this->gvars['CompanyTypeNewForm'] = $companyTypeNewForm->createView();

		$this->gvars['pagetitle'] = $this->translate('pagetitle.companyType.add');
		$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.companyType.add.txt');
		$this->gvars['smenu_active'] = 'add';

		return $this->renderResponse('AcfAdminBundle:CompanyType:add.html.twig', $this->gvars);
	}

	public function deleteAction($id)
	{
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_admin_companyType_list');
		}
		$em = $this->getEntityManager();
		try {
			$companyType = $em->getRepository('AcfDataBundle:CompanyType')->find($id);

			if (null == $companyType) {
				$this->flashMsgSession('warning', $this->translate('CompanyType.delete.notfound'));
			} else {
				$em->remove($companyType);
				$em->flush();

				$this->flashMsgSession(
					'success',
					$this->translate('CompanyType.delete.success', array('%companyType%' => $companyType->getLabel()))
				);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());

			$this->flashMsgSession('error', $this->translate('CompanyType.delete.failure'));
		}

		return $this->redirect($urlFrom);

	}

	public function editGetAction($id)
	{
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_admin_companyType_list');
		}

		$em = $this->getEntityManager();
		try {
			$companyType = $em->getRepository('AcfDataBundle:CompanyType')->find($id);

			if (null == $companyType) {
				$this->flashMsgSession('warning', $this->translate('CompanyType.edit.notfound'));
			} else {
				$traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($companyType->getId(), Trace::AE_TYPE);
				$this->gvars['traces'] = array_reverse($traces);
				$companyTypeUpdateForm = $this->createForm(CompanyTypeUpdateTForm::class, $companyType);

				$this->gvars['companyType'] = $companyType;
				$this->gvars['CompanyTypeUpdateForm'] = $companyTypeUpdateForm->createView();

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
				$this->getSession()->remove('tabActive');



				$this->gvars['pagetitle'] = $this->translate('pagetitle.companyType.edit', array('%companyType%' => $companyType->getLabel()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.companyType.edit.txt', array('%companyType%' => $companyType->getLabel()));

				return $this->renderResponse('AcfAdminBundle:CompanyType:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());
		}

		return $this->redirect($urlFrom);
	}


	public function editPostAction($id)
	{
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('_admin_companyType_list'));
		}

		$em = $this->getEntityManager();
		try {
			$companyType = $em->getRepository('AcfDataBundle:CompanyType')->find($id);

			if (null == $companyType) {
				$this->flashMsgSession('warning', $this->translate('CompanyType.edit.notfound'));
			} else {
				$traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($companyType->getId(), Trace::AE_TYPE);
				$this->gvars['traces'] = array_reverse($traces);
				$companyTypeUpdateForm = $this->createForm(CompanyTypeUpdateTForm::class, $companyType);



				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
				$this->getSession()->remove('tabActive');

				$request = $this->getRequest();
				$reqData = $request->request->all();

				$cloneCompanyType = clone $companyType;

				if (isset($reqData['CompanyTypeUpdateForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$companyTypeUpdateForm->handleRequest($request);
					if ($companyTypeUpdateForm->isValid()) {
						$em->persist($companyType);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('CompanyType.edit.success', array('%companyType%' => $companyType->getLabel()))
						);

						$this->traceEntity($cloneCompanyType, $companyType);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($companyType);

						$this->flashMsgSession(
							'error',
							$this->translate('CompanyType.edit.failure', array('%companyType%' => $companyType->getLabel()))
						);
					}
				}

				$this->gvars['companyType'] = $companyType;
				$this->gvars['CompanyTypeUpdateForm'] = $companyTypeUpdateForm->createView();

				$this->gvars['pagetitle'] = $this->translate('pagetitle.companyType.edit', array('%companyType%' => $companyType->getLabel()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.companyType.edit.txt', array('%companyType%' => $companyType->getLabel()));

				return $this->renderResponse('AcfAdminBundle:CompanyType:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());
		}

		return $this->redirect($urlFrom);

	}

	protected function traceEntity(CompanyType $cloneCompanyType, CompanyType $companyType) {

		$curUser = $this->getSecurityTokenStorage()->getToken()->getUser();
		$trace = new Trace();
		$trace->setActionId($companyType->getId());
		$trace->setActionType(Trace::AT_UPDATE);
		$trace->setUserId($curUser->getId());
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

		$trace->setActionEntity(Trace::AE_TYPE);

		$msg = "";

		if ($cloneCompanyType->getLabel() != $companyType->getLabel()) {
			$msg .= "<tr><td>".$this->translate('CompanyType.label.label').'</td><td>';
			if ($cloneCompanyType->getLabel() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneCompanyType->getLabel();
			}
			$msg .= "</td><td>";
			if ($companyType->getLabel() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $companyType->getLabel();
			}
			$msg .= "</td></tr>";
		}

		if ($msg != "") {

			$msg = $table_begin.$msg.$table_end;

			$trace->setMsg(
				$this->translate(
					'CompanyType.traceEdit',
					array('%companyType%' => $companyType->getLabel())
					).$msg
				);
			$trace->setDtCrea(new \DateTime('now'));
			$em = $this->getEntityManager();
			$em->persist($trace);
			$em->flush();
		}
	}

}