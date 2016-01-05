<?php
namespace Acf\AdminBundle\Controller;


use Acf\DataBundle\Entity\Sector;
use Acf\AdminBundle\Form\Sector\NewTForm as SectorNewTForm;
use Acf\AdminBundle\Form\Sector\UpdateTForm as SectorUpdateTForm;
use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\Trace;

/**
 * @author sasedev
 *
 */
class SectorController extends BaseController
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

		$this->gvars['menu_active'] = 'sector';

	}

	public function listAction()
	{
		if (! $this->hasRole('ROLE_SUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$em = $this->getEntityManager();
		$sectors = $em->getRepository('AcfDataBundle:Sector')->getAll();
		$this->gvars['sectors'] = $sectors;

		$this->gvars['smenu_active'] = 'list';
		$this->gvars['pagetitle'] = $this->translate('pagetitle.sector.list');
		$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.sector.list.txt');
		return $this->renderResponse('AcfAdminBundle:Sector:list.html.twig', $this->gvars);
	}

	public function addGetAction()
	{
		if (! $this->hasRole('ROLE_SUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$sector = new Sector();
		$sectorNewForm = $this->createForm(SectorNewTForm::class, $sector);
		$this->gvars['sector'] = $sector;
		$this->gvars['SectorNewForm'] = $sectorNewForm->createView();

		$this->gvars['pagetitle'] = $this->translate('pagetitle.sector.add');
		$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.sector.add.txt');
		$this->gvars['smenu_active'] = 'add';

		return $this->renderResponse('AcfAdminBundle:Sector:add.html.twig', $this->gvars);
	}

	public function addPostAction()
	{
		if (! $this->hasRole('ROLE_SUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('_admin_sector_addGet'));
		}

		$sector = new Sector();
		$sectorNewForm = $this->createForm(SectorNewTForm::class, $sector);

		$request = $this->getRequest();
		$reqData = $request->request->all();

		if (isset($reqData['SectorNewForm'])) {
			$sectorNewForm->handleRequest($request);
			if ($sectorNewForm->isValid()) {
				$em = $this->getEntityManager();
				$em->persist($sector);
				$em->flush();

				if ($request->isXmlHttpRequest()) {
					$this->gvars['SectorNewForm'] = $sectorNewForm->createView();
					$this->gvars['sector'] = $sector;
					return $this->renderResponse(
						'AcfAdminBundle:Sector:add.ajax.html.twig',
						$this->gvars
					);
				} else {
					$this->flashMsgSession(
						'success',
						$this->translate('Sector.add.success', array('%sector%' => $sector->getLabel()))
					);
				}

				return $this->redirect(
					$this->generateUrl('_admin_sector_editGet', array('id' => $sector->getId()))
				);
			} else {

				if ($request->isXmlHttpRequest()) {
					$this->gvars['SectorNewForm'] = $sectorNewForm->createView();
					return $this->renderResponse(
						'AcfAdminBundle:Sector:add_error.ajax.html.twig',
						$this->gvars
					);

				} else {
					$this->flashMsgSession(
						'error',
						$this->translate('Sector.add.failure')
					);
				}

			}
		}
		$this->gvars['sector'] = $sector;
		$this->gvars['SectorNewForm'] = $sectorNewForm->createView();

		$this->gvars['pagetitle'] = $this->translate('pagetitle.sector.add');
		$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.sector.add.txt');
		$this->gvars['smenu_active'] = 'add';

		return $this->renderResponse('AcfAdminBundle:Sector:add.html.twig', $this->gvars);
	}

	public function deleteAction($id)
	{
		if (! $this->hasRole('ROLE_SUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_admin_sector_list');
		}
		$em = $this->getEntityManager();
		try {
			$sector = $em->getRepository('AcfDataBundle:Sector')->find($id);

			if (null == $sector) {
				$this->flashMsgSession('warning', $this->translate('Sector.delete.notfound'));
			} else {
				$em->remove($sector);
				$em->flush();

				$this->flashMsgSession(
					'success',
					$this->translate('Sector.delete.success', array('%sector%' => $sector->getLabel()))
				);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());

			$this->flashMsgSession('error', $this->translate('Sector.delete.failure'));
		}

		return $this->redirect($urlFrom);

	}

	public function editGetAction($id)
	{
		if (! $this->hasRole('ROLE_SUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_admin_sector_list');
		}

		$em = $this->getEntityManager();
		try {
			$sector = $em->getRepository('AcfDataBundle:Sector')->find($id);

			if (null == $sector) {
				$this->flashMsgSession('warning', $this->translate('Sector.edit.notfound'));
			} else {
				$traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($sector->getId(), Trace::AE_SECTOR);
				$this->gvars['traces'] = array_reverse($traces);
				$sectorUpdateForm = $this->createForm(SectorUpdateTForm::class, $sector);

				$this->gvars['sector'] = $sector;
				$this->gvars['SectorUpdateForm'] = $sectorUpdateForm->createView();

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
				$this->getSession()->remove('tabActive');



				$this->gvars['pagetitle'] = $this->translate('pagetitle.sector.edit', array('%sector%' => $sector->getLabel()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.sector.edit.txt', array('%sector%' => $sector->getLabel()));

				return $this->renderResponse('AcfAdminBundle:Sector:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());
		}

		return $this->redirect($urlFrom);
	}


	public function editPostAction($id)
	{
		if (! $this->hasRole('ROLE_SUPERADMIN')) {
			return $this->redirect($this->generateUrl('_admin_homepage'));
		}
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('_admin_sector_list'));
		}

		$em = $this->getEntityManager();
		try {
			$sector = $em->getRepository('AcfDataBundle:Sector')->find($id);

			if (null == $sector) {
				$this->flashMsgSession('warning', $this->translate('Sector.edit.notfound'));
			} else {
				$traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($sector->getId(), Trace::AE_SECTOR);
				$this->gvars['traces'] = array_reverse($traces);
				$sectorUpdateForm = $this->createForm(SectorUpdateTForm::class, $sector);



				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
				$this->getSession()->remove('tabActive');

				$request = $this->getRequest();
				$reqData = $request->request->all();

				$cloneSector = clone $sector;

				if (isset($reqData['SectorUpdateForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$sectorUpdateForm->handleRequest($request);
					if ($sectorUpdateForm->isValid()) {
						$em->persist($sector);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('Sector.edit.success', array('%sector%' => $sector->getLabel()))
						);

						$this->traceEntity($cloneSector, $sector);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($sector);

						$this->flashMsgSession(
							'error',
							$this->translate('Sector.edit.failure', array('%sector%' => $sector->getLabel()))
						);
					}
				}

				$this->gvars['sector'] = $sector;
				$this->gvars['SectorUpdateForm'] = $sectorUpdateForm->createView();

				$this->gvars['pagetitle'] = $this->translate('pagetitle.sector.edit', array('%sector%' => $sector->getLabel()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.sector.edit.txt', array('%sector%' => $sector->getLabel()));

				return $this->renderResponse('AcfAdminBundle:Sector:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());
		}

		return $this->redirect($urlFrom);

	}

	protected function traceEntity(Sector $cloneSector, Sector $sector) {

		$curUser = $this->getSecurityTokenStorage()->getToken()->getUser();
		$trace = new Trace();
		$trace->setActionId($sector->getId());
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

		$trace->setActionEntity(Trace::AE_SECTOR);

		$msg = "";

		if ($cloneSector->getLabel() != $sector->getLabel()) {
			$msg .= "<tr><td>".$this->translate('Sector.label.label').'</td><td>';
			if ($cloneSector->getLabel() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneSector->getLabel();
			}
			$msg .= "</td><td>";
			if ($sector->getLabel() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $sector->getLabel();
			}
			$msg .= "</td></tr>";
		}

		if ($msg != "") {

			$msg = $table_begin.$msg.$table_end;

			$trace->setMsg(
				$this->translate(
					'Sector.traceEdit',
					array('%sector%' => $sector->getLabel())
					).$msg
				);
			$trace->setDtCrea(new \DateTime('now'));
			$em = $this->getEntityManager();
			$em->persist($trace);
			$em->flush();
		}
	}

}