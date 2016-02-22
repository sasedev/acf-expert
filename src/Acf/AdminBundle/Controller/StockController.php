<?php
namespace Acf\AdminBundle\Controller;

use Acf\AdminBundle\Form\Stock\UpdateTForm as StockUpdateTForm;
use Acf\DataBundle\Entity\Stock;
use Acf\DataBundle\Entity\Trace;
use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author sasedev
 *
 */
class StockController extends BaseController
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
			$stock = $em->getRepository('AcfDataBundle:Stock')->find($uid);

			if (null == $stock) {
				$this->flashMsgSession('warning', $this->translate('Stock.delete.notfound'));
			} else {
				$em->remove($stock);
				$em->flush();

				$this->flashMsgSession(
					'success',
					$this->translate('Stock.delete.success', array('%stock%' => $stock->getYear()))
				);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());

			$this->flashMsgSession('error', $this->translate('Stock.delete.failure'));
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
			$stock = $em->getRepository('AcfDataBundle:Stock')->find($uid);

			if (null == $stock) {
				$this->flashMsgSession('warning', $this->translate('Stock.edit.notfound'));
			} else {
				$traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($stock->getId(), Trace::AE_STOCK);
				$this->gvars['traces'] = array_reverse($traces);
				$stockUpdateForm = $this->createForm(StockUpdateTForm::class, $stock);


				$this->gvars['stock'] = $stock;
				$this->gvars['StockUpdateForm'] = $stockUpdateForm->createView();

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
				$this->getSession()->remove('tabActive');



				$this->gvars['pagetitle'] = $this->translate('pagetitle.stock.edit', array('%stock%' => $stock->getYear()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.stock.edit.txt', array('%stock%' => $stock->getYear()));

				return $this->renderResponse('AcfAdminBundle:Stock:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());
		}

		return $this->redirect($urlFrom);
	}

	public function editPostAction(Request $request, $uid)
	{
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_admin_company_list');
		}

		$em = $this->getEntityManager();
		try {
			$stock = $em->getRepository('AcfDataBundle:Stock')->find($uid);

			if (null == $stock) {
				$this->flashMsgSession('warning', $this->translate('Stock.edit.notfound'));
			} else {
				$traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($stock->getId(), Trace::AE_STOCK);
				$this->gvars['traces'] = array_reverse($traces);
				$stockUpdateForm = $this->createForm(StockUpdateTForm::class, $stock);



				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
				$this->getSession()->remove('tabActive');

				$reqData = $request->request->all();

				$cloneStock = clone $stock;

				if (isset($reqData['StockUpdateForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$stockUpdateForm->handleRequest($request);
					if ($stockUpdateForm->isValid()) {
						$em->persist($stock);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('Stock.edit.success', array('%stock%' => $stock->getYear()))
						);

						$this->traceEntity($cloneStock, $stock);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($stock);

						$this->flashMsgSession('error', $this->translate('Stock.edit.failure', array('%stock%' => $stock->getYear())));
					}
				}

				$this->gvars['stock'] = $stock;
				$this->gvars['StockUpdateForm'] = $stockUpdateForm->createView();



				$this->gvars['pagetitle'] = $this->translate('pagetitle.stock.edit', array('%stock%' => $stock->getYear()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.stock.edit.txt', array('%stock%' => $stock->getYear()));

				return $this->renderResponse('AcfAdminBundle:Stock:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());
		}

		return $this->redirect($urlFrom);
	}

	protected function traceEntity(Stock $cloneStock, Stock $stock) {

		$curUser = $this->getSecurityTokenStorage()->getToken()->getUser();
		$trace = new Trace();
		$trace->setActionId($stock->getId());
		$trace->setActionType(Trace::AT_UPDATE);
		$trace->setUserId($curUser->getId());
		$trace->setCompanyId($stock->getCompany()->getId());
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

		$trace->setActionEntity(Trace::AE_STOCK);
		$trace->setActionId2($stock->getCompany()->getId());
		$trace->setActionEntity2(Trace::AE_COMPANY);

		$msg = "";

		if ($cloneStock->getYear() != $stock->getYear()) {
			$msg .= "<tr><td>".$this->translate('Stock.year.label').'</td><td>';
			if ($cloneStock->getYear() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneStock->getYear();
			}
			$msg .= "</td><td>";
			if ($stock->getYear() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $stock->getYear();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneStock->getValue() != $stock->getValue()) {
			$msg .= "<tr><td>".$this->translate('Stock.streetNum.label').'</td><td>';
			if ($cloneStock->getValue() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneStock->getValue();
			}
			$msg .= "</td><td>";
			if ($stock->getValue() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $stock->getValue();
			}
			$msg .= "</td></tr>";
		}

		if ($msg != "") {

			$msg = $table_begin.$msg.$table_end;

			$trace->setMsg(
				$this->translate(
					'Stock.traceEdit',
					array('%stock%' => $stock->getYear(), '%company%' => $stock->getCompany()->getCorporateName())
					).$msg
				);
			$trace->setDtCrea(new \DateTime('now'));
			$em = $this->getEntityManager();
			$em->persist($trace);
			$em->flush();
		}
	}

}
