<?php
namespace Acf\AdminBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\Withholding;
use Acf\AdminBundle\Form\Withholding\UpdateLabelTForm as WithholdingUpdateLabelTForm;
use Acf\AdminBundle\Form\Withholding\UpdateNumberTForm as WithholdingUpdateNumberTForm;
use Acf\AdminBundle\Form\Withholding\UpdateValueTForm as WithholdingUpdateValueTForm;
use Acf\AdminBundle\Form\Withholding\UpdateOtherInfosTForm as WithholdingUpdateOtherInfosTForm;
use Acf\DataBundle\Entity\ConstantStr;
use Acf\DataBundle\Entity\Trace;

/**
 * @author sasedev
 *
 */
class WithholdingController extends BaseController
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

		$this->gvars['menu_active'] = 'withholding';

	}

	public function deleteAction($uid)
	{
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_admin_company_list');
		}
		$em = $this->getEntityManager();
		try {
			$withholding = $em->getRepository('AcfDataBundle:Withholding')->find($uid);

			if (null == $withholding) {
				$this->flashMsgSession('warning', $this->translate('Withholding.delete.notfound'));
			} else {
				$em->remove($withholding);
				$em->flush();

				$this->flashMsgSession(
					'success',
					$this->translate('Withholding.delete.success', array('%withholding%' => $withholding->getLabel()))
				);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());

			$this->flashMsgSession('error', $this->translate('Withholding.delete.failure'));
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
			$withholding = $em->getRepository('AcfDataBundle:Withholding')->find($uid);

			if (null == $withholding) {
				$this->flashMsgSession('warning', $this->translate('Withholding.edit.notfound'));
			} else {
				$traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($withholding->getId(), Trace::AE_WHITHHOLDING);
				$this->gvars['traces'] = array_reverse($traces);
				$withholdingUpdateLabelForm = $this->createForm(WithholdingUpdateLabelTForm::class, $withholding);
				$withholdingUpdateNumberForm = $this->createForm(WithholdingUpdateNumberTForm::class, $withholding);
				$withholdingUpdateValueForm = $this->createForm(WithholdingUpdateValueTForm::class, $withholding);
				$withholdingUpdateOtherInfosForm = $this->createForm(WithholdingUpdateOtherInfosTForm::class, $withholding);


				$this->gvars['withholding'] = $withholding;
				$this->gvars['WithholdingUpdateLabelForm'] = $withholdingUpdateLabelForm->createView();
				$this->gvars['WithholdingUpdateNumberForm'] = $withholdingUpdateNumberForm->createView();
				$this->gvars['WithholdingUpdateValueForm'] = $withholdingUpdateValueForm->createView();
				$this->gvars['WithholdingUpdateOtherInfosForm'] = $withholdingUpdateOtherInfosForm->createView();

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
				$this->getSession()->remove('tabActive');

				$withholdingsConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array('name' => 'withholdingsPrefix'));
				if (null == $withholdingsConstStr) {
					$withholdingsConstStr = new ConstantStr();
					$withholdingsConstStr->setName('withholdingsPrefix');
					$withholdingsConstStr->setValue('432');
					$em->persist($withholdingsConstStr);
					$em->flush();
				}
				$withholdingsPrefix = $withholdingsConstStr->getValue();
				$this->gvars['withholdingsPrefix'] = $withholdingsPrefix;



				$this->gvars['pagetitle'] = $this->translate('pagetitle.withholding.edit', array('%withholding%' => $withholding->getLabel()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.withholding.edit.txt', array('%withholding%' => $withholding->getLabel()));

				return $this->renderResponse('AcfAdminBundle:Withholding:edit.html.twig', $this->gvars);
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
			$withholding = $em->getRepository('AcfDataBundle:Withholding')->find($uid);

			if (null == $withholding) {
				$this->flashMsgSession('warning', $this->translate('Withholding.edit.notfound'));
			} else {
				$traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($withholding->getId(), Trace::AE_WHITHHOLDING);
				$this->gvars['traces'] = array_reverse($traces);
				$withholdingUpdateLabelForm = $this->createForm(WithholdingUpdateLabelTForm::class, $withholding);
				$withholdingUpdateNumberForm = $this->createForm(WithholdingUpdateNumberTForm::class, $withholding);
				$withholdingUpdateValueForm = $this->createForm(WithholdingUpdateValueTForm::class, $withholding);
				$withholdingUpdateOtherInfosForm = $this->createForm(WithholdingUpdateOtherInfosTForm::class, $withholding);



				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
				$this->getSession()->remove('tabActive');

				$request = $this->getRequest();
				$reqData = $request->request->all();

				$cloneWithholding = clone $withholding;

				if (isset($reqData['WithholdingUpdateLabelForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$withholdingUpdateLabelForm->handleRequest($request);
					if ($withholdingUpdateLabelForm->isValid()) {
						$em->persist($withholding);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('Withholding.edit.success', array('%withholding%' => $withholding->getLabel()))
						);

						$this->traceEntity($cloneWithholding, $withholding);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($withholding);

						$this->flashMsgSession('error', $this->translate('Withholding.edit.failure', array('%withholding%' => $withholding->getLabel())));
					}
				} elseif (isset($reqData['WithholdingUpdateNumberForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$withholdingUpdateNumberForm->handleRequest($request);
					if ($withholdingUpdateNumberForm->isValid()) {
						$em->persist($withholding);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('Withholding.edit.success', array('%withholding%' => $withholding->getLabel()))
						);

						$this->traceEntity($cloneWithholding, $withholding);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($withholding);

						$this->flashMsgSession('error', $this->translate('Withholding.edit.failure', array('%withholding%' => $withholding->getLabel())));
					}
				} elseif (isset($reqData['WithholdingUpdateValueForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$withholdingUpdateValueForm->handleRequest($request);
					if ($withholdingUpdateValueForm->isValid()) {
						$em->persist($withholding);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('Withholding.edit.success', array('%withholding%' => $withholding->getLabel()))
						);

						$this->traceEntity($cloneWithholding, $withholding);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($withholding);

						$this->flashMsgSession('error', $this->translate('Withholding.edit.failure', array('%withholding%' => $withholding->getLabel())));
					}
				} elseif (isset($reqData['WithholdingUpdateOtherInfosForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$withholdingUpdateOtherInfosForm->handleRequest($request);
					if ($withholdingUpdateOtherInfosForm->isValid()) {
						$em->persist($withholding);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('Withholding.edit.success', array('%withholding%' => $withholding->getLabel()))
						);

						$this->traceEntity($cloneWithholding, $withholding);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($withholding);

						$this->flashMsgSession('error', $this->translate('Withholding.edit.failure', array('%withholding%' => $withholding->getLabel())));
					}
				}

				$this->gvars['withholding'] = $withholding;
				$this->gvars['WithholdingUpdateLabelForm'] = $withholdingUpdateLabelForm->createView();
				$this->gvars['WithholdingUpdateNumberForm'] = $withholdingUpdateNumberForm->createView();
				$this->gvars['WithholdingUpdateValueForm'] = $withholdingUpdateValueForm->createView();
				$this->gvars['WithholdingUpdateOtherInfosForm'] = $withholdingUpdateOtherInfosForm->createView();

				$withholdingsConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array('name' => 'withholdingsPrefix'));
				if (null == $withholdingsConstStr) {
					$withholdingsConstStr = new ConstantStr();
					$withholdingsConstStr->setName('withholdingsPrefix');
					$withholdingsConstStr->setValue('432');
					$em->persist($withholdingsConstStr);
					$em->flush();
				}
				$withholdingsPrefix = $withholdingsConstStr->getValue();
				$this->gvars['withholdingsPrefix'] = $withholdingsPrefix;



				$this->gvars['pagetitle'] = $this->translate('pagetitle.withholding.edit', array('%withholding%' => $withholding->getLabel()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.withholding.edit.txt', array('%withholding%' => $withholding->getLabel()));

				return $this->renderResponse('AcfAdminBundle:Withholding:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());
		}

		return $this->redirect($urlFrom);
	}

	protected function traceEntity(Withholding $cloneWithholding, Withholding $withholding) {

		$curUser = $this->getSecurityTokenStorage()->getToken()->getUser();
		$trace = new Trace();
		$trace->setActionId($withholding->getId());
		$trace->setActionType(Trace::AT_UPDATE);
		$trace->setUserId($curUser->getId());
		$trace->setCompanyId($withholding->getCompany()->getId());
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

		$trace->setActionEntity(Trace::AE_WHITHHOLDING);
		$trace->setActionId2($withholding->getCompany()->getId());
		$trace->setActionEntity2(Trace::AE_COMPANY);

		$msg = "";

		if ($cloneWithholding->getLabel() != $withholding->getLabel()) {
			$msg .= "<tr><td>".$this->translate('Withholding.label.label').'</td><td>';
			if ($cloneWithholding->getLabel() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneWithholding->getLabel();
			}
			$msg .= "</td><td>";
			if ($withholding->getLabel() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $withholding->getLabel();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneWithholding->getNumber() != $withholding->getNumber()) {
			$msg .= "<tr><td>".$this->translate('Withholding.number.label').'</td><td>';
			if ($cloneWithholding->getNumber() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneWithholding->getNumberFormated();
			}
			$msg .= "</td><td>";
			if ($withholding->getNumber() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $withholding->getNumberFormated();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneWithholding->getValue() != $withholding->getValue()) {
			$msg .= "<tr><td>".$this->translate('Withholding.value.label').'</td><td>';
			if ($cloneWithholding->getValue() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneWithholding->getValue();
			}
			$msg .= "</td><td>";
			if ($withholding->getValue() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $withholding->getValue();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneWithholding->getOtherInfos() != $withholding->getOtherInfos()) {
			$msg .= "<tr><td>".$this->translate('Withholding.otherInfos.label').'</td><td>';
			if ($cloneWithholding->getOtherInfos() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneWithholding->getOtherInfos();
			}
			$msg .= "</td><td>";
			if ($withholding->getOtherInfos() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $withholding->getOtherInfos();
			}
			$msg .= "</td></tr>";
		}

		if ($msg != "") {

			$msg = $table_begin.$msg.$table_end;

			$trace->setMsg(
				$this->translate(
					'Withholding.traceEdit',
					array('%withholding%' => $withholding->getLabel(), '%company%' => $withholding->getCompany()->getCorporateName())
					).$msg
				);
			$trace->setDtCrea(new \DateTime('now'));
			$em = $this->getEntityManager();
			$em->persist($trace);
			$em->flush();
		}
	}

}