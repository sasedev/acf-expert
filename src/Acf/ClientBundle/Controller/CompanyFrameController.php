<?php
namespace Acf\ClientBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\CompanyFrame;
use Acf\ClientBundle\Form\CompanyFrame\UpdateTForm as CompanyFrameUpdateTForm;
use Acf\DataBundle\Entity\CompanyUser;
use Acf\DataBundle\Entity\Trace;

/**
 *
 * @author sasedev
 */
class CompanyFrameController extends BaseController
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

		$this->gvars['menu_active'] = 'clienthome';

	}

	public function deleteAction($uid)
	{
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_client_companyFrame_list');
		}
		$em = $this->getEntityManager();
		try {
			$companyFrame = $em->getRepository('AcfDataBundle:CompanyFrame')->find($uid);

			if (null == $companyFrame) {
				$this->flashMsgSession('warning', $this->translate('CompanyFrame.delete.notfound'));
			} else {

				$sc = $this->getSecurityTokenStorage();
				$user = $sc->getToken()->getUser();

				$company = $companyFrame->getCompany();

				$companyUser = $em->getRepository('AcfDataBundle:CompanyUser')->findOneBy(array('company' => $company, 'user' => $user));
				if (null == $companyUser || $companyUser->getDeleteFrames() == CompanyUser::CANT) {
					$this->flashMsgSession('error', $this->translate('CompanyUser.accessForbidden'));
					return $this->redirect($this->generateUrl('_client_homepage'));
				}
				$this->gvars['companyUser'] = $companyUser;
				$this->gvars['menu_active'] = 'client'.$company->getId();

				$em->remove($companyFrame);
				$em->flush();

				$this->flashMsgSession(
					'success',
					$this->translate('CompanyFrame.delete.success', array('%companyFrame%' => $companyFrame->getFullName()))
				);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());

			$this->flashMsgSession('error', $this->translate('CompanyFrame.delete.failure'));
		}

		return $this->redirect($urlFrom);

	}

	public function editGetAction($uid)
	{
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_client_homepage');
		}

		$em = $this->getEntityManager();
		try {
			$companyFrame = $em->getRepository('AcfDataBundle:CompanyFrame')->find($uid);

			if (null == $companyFrame) {
				$this->flashMsgSession('warning', $this->translate('CompanyFrame.edit.notfound'));
			} else {

				$sc = $this->getSecurityTokenStorage();
				$user = $sc->getToken()->getUser();

				$company = $companyFrame->getCompany();

				$companyUser = $em->getRepository('AcfDataBundle:CompanyUser')->findOneBy(array('company' => $company, 'user' => $user));
				if (null == $companyUser) {
					$this->flashMsgSession('error', $this->translate('CompanyUser.accessForbidden'));
					return $this->redirect($this->generateUrl('_client_homepage'));
				}
				$this->gvars['companyUser'] = $companyUser;
				$this->gvars['menu_active'] = 'client'.$company->getId();

				$companyFrameUpdateForm = $this->createForm(CompanyFrameUpdateTForm::class, $companyFrame);


				$this->gvars['companyFrame'] = $companyFrame;
				$this->gvars['CompanyFrameUpdateForm'] = $companyFrameUpdateForm->createView();

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
				$this->getSession()->remove('tabActive');



				$this->gvars['pagetitle'] = $this->translate('pagetitle.companyFrame.edit', array('%companyFrame%' => $companyFrame->getFullName()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.companyFrame.edit.txt', array('%companyFrame%' => $companyFrame->getFullName()));

				return $this->renderResponse('AcfClientBundle:CompanyFrame:edit.html.twig', $this->gvars);
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
			$urlFrom = $this->generateUrl('_client_homepage');
		}

		$em = $this->getEntityManager();
		try {
			$companyFrame = $em->getRepository('AcfDataBundle:CompanyFrame')->find($uid);

			if (null == $companyFrame) {
				$this->flashMsgSession('warning', $this->translate('CompanyFrame.edit.notfound'));
			} else {

				$sc = $this->getSecurityTokenStorage();
				$user = $sc->getToken()->getUser();

				$company = $companyFrame->getCompany();

				$companyUser = $em->getRepository('AcfDataBundle:CompanyUser')->findOneBy(array('company' => $company, 'user' => $user));
				if (null == $companyUser || $companyUser->getEditFrames() == CompanyUser::CANT) {
					$this->flashMsgSession('error', $this->translate('CompanyUser.accessForbidden'));
					return $this->redirect($this->generateUrl('_client_homepage'));
				}
				$this->gvars['companyUser'] = $companyUser;
				$this->gvars['menu_active'] = 'client'.$company->getId();

				$companyFrameUpdateForm = $this->createForm(CompanyFrameUpdateTForm::class, $companyFrame);



				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
				$this->getSession()->remove('tabActive');

				$request = $this->getRequest();
				$reqData = $request->request->all();

				$cloneCompanyFrame = clone $companyFrame;

				if (isset($reqData['CompanyFrameUpdateForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$companyFrameUpdateForm->handleRequest($request);
					if ($companyFrameUpdateForm->isValid()) {
						$em->persist($companyFrame);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('CompanyFrame.edit.success', array('%companyFrame%' => $companyFrame->getFullName()))
						);

						$this->traceEntity($cloneCompanyFrame, $companyFrame);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($companyFrame);

						$this->flashMsgSession('error', $this->translate('CompanyFrame.edit.failure', array('%companyFrame%' => $companyFrame->getFullName())));
					}
				}

				$this->gvars['companyFrame'] = $companyFrame;
				$this->gvars['CompanyFrameUpdateForm'] = $companyFrameUpdateForm->createView();



				$this->gvars['pagetitle'] = $this->translate('pagetitle.companyFrame.edit', array('%companyFrame%' => $companyFrame->getFullName()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.companyFrame.edit.txt', array('%companyFrame%' => $companyFrame->getFullName()));

				return $this->renderResponse('AcfClientBundle:CompanyFrame:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());
		}

		return $this->redirect($urlFrom);
	}

	protected function traceEntity(CompanyFrame $cloneCompanyFrame, CompanyFrame $companyFrame) {

		$curUser = $this->getSecurityTokenStorage()->getToken()->getUser();
		$trace = new Trace();
		$trace->setActionId($companyFrame->getId());
		$trace->setActionType(Trace::AT_UPDATE);
		$trace->setUserId($curUser->getId());
		$trace->setCompanyId($companyFrame->getCompany()->getId());
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

		$trace->setActionEntity(Trace::AE_FRAME);
		$trace->setActionId2($companyFrame->getCompany()->getId());
		$trace->setActionEntity2(Trace::AE_COMPANY);

		$msg = "";

		if ($cloneCompanyFrame->getSexe() != $companyFrame->getSexe()) {
			$msg .= "<tr><td>".$this->translate('CompanyFrame.sexe.label').'</td><td>';
			if ($cloneCompanyFrame->getSexe() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $this->translate('CompanyFrame.sexe.'.$cloneCompanyFrame->getSexe());
			}
			$msg .= "</td><td>";
			if ($companyFrame->getSexe() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $this->translate('CompanyFrame.sexe.'.$companyFrame->getSexe());
			}
			$msg .= "</td></tr>";
		}

		if ($cloneCompanyFrame->getLastName() != $companyFrame->getLastName()) {
			$msg .= "<tr><td>".$this->translate('CompanyFrame.lastName.label').'</td><td>';
			if ($cloneCompanyFrame->getLastName() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneCompanyFrame->getLastName();
			}
			$msg .= "</td><td>";
			if ($companyFrame->getLastName() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $companyFrame->getLastName();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneCompanyFrame->getFirstName() != $companyFrame->getFirstName()) {
			$msg .= "<tr><td>".$this->translate('CompanyFrame.firstName.label').'</td><td>';
			if ($cloneCompanyFrame->getFirstName() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneCompanyFrame->getFirstName();
			}
			$msg .= "</td><td>";
			if ($companyFrame->getFirstName() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $companyFrame->getFirstName();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneCompanyFrame->getCin() != $companyFrame->getCin()) {
			$msg .= "<tr><td>".$this->translate('CompanyFrame.cin.label').'</td><td>';
			if ($cloneCompanyFrame->getCin() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneCompanyFrame->getCin();
			}
			$msg .= "</td><td>";
			if ($companyFrame->getCin() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $companyFrame->getCin();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneCompanyFrame->getPassport() != $companyFrame->getPassport()) {
			$msg .= "<tr><td>".$this->translate('CompanyFrame.passport.label').'</td><td>';
			if ($cloneCompanyFrame->getPassport() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneCompanyFrame->getPassport();
			}
			$msg .= "</td><td>";
			if ($companyFrame->getPassport() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $companyFrame->getPassport();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneCompanyFrame->getStreetNum() != $companyFrame->getStreetNum()) {
			$msg .= "<tr><td>".$this->translate('CompanyFrame.streetNum.label').'</td><td>';
			if ($cloneCompanyFrame->getStreetNum() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneCompanyFrame->getStreetNum();
			}
			$msg .= "</td><td>";
			if ($companyFrame->getStreetNum() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $companyFrame->getStreetNum();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneCompanyFrame->getAddress() != $companyFrame->getAddress()) {
			$msg .= "<tr><td>".$this->translate('CompanyFrame.address.label').'</td><td>';
			if ($cloneCompanyFrame->getAddress() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneCompanyFrame->getAddress();
			}
			$msg .= "</td><td>";
			if ($companyFrame->getAddress() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $companyFrame->getAddress();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneCompanyFrame->getAddress2() != $companyFrame->getAddress2()) {
			$msg .= "<tr><td>".$this->translate('CompanyFrame.address2.label').'</td><td>';
			if ($cloneCompanyFrame->getAddress2() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneCompanyFrame->getAddress2();
			}
			$msg .= "</td><td>";
			if ($companyFrame->getAddress2() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $companyFrame->getAddress2();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneCompanyFrame->getTown() != $companyFrame->getTown()) {
			$msg .= "<tr><td>".$this->translate('CompanyFrame.town.label').'</td><td>';
			if ($cloneCompanyFrame->getTown() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneCompanyFrame->getTown();
			}
			$msg .= "</td><td>";
			if ($companyFrame->getTown() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $companyFrame->getTown();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneCompanyFrame->getZipCode() != $companyFrame->getZipCode()) {
			$msg .= "<tr><td>".$this->translate('CompanyFrame.zipCode.label').'</td><td>';
			if ($cloneCompanyFrame->getZipCode() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneCompanyFrame->getZipCode();
			}
			$msg .= "</td><td>";
			if ($companyFrame->getZipCode() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $companyFrame->getZipCode();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneCompanyFrame->getCountry() != $companyFrame->getCountry()) {
			$msg .= "<tr><td>".$this->translate('CompanyFrame.country.label').'</td><td>';
			if ($cloneCompanyFrame->getCountry() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneCompanyFrame->getCountry();
			}
			$msg .= "</td><td>";
			if ($companyFrame->getCountry() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $companyFrame->getCountry();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneCompanyFrame->getPhone() != $companyFrame->getPhone()) {
			$msg .= "<tr><td>".$this->translate('CompanyFrame.phone.label').'</td><td>';
			if ($cloneCompanyFrame->getPhone() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneCompanyFrame->getPhone();
			}
			$msg .= "</td><td>";
			if ($companyFrame->getPhone() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $companyFrame->getPhone();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneCompanyFrame->getMobile() != $companyFrame->getMobile()) {
			$msg .= "<tr><td>".$this->translate('CompanyFrame.mobile.label').'</td><td>';
			if ($cloneCompanyFrame->getMobile() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneCompanyFrame->getMobile();
			}
			$msg .= "</td><td>";
			if ($companyFrame->getMobile() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $companyFrame->getMobile();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneCompanyFrame->getEmail() != $companyFrame->getEmail()) {
			$msg .= "<tr><td>".$this->translate('CompanyFrame.email.label').'</td><td>';
			if ($cloneCompanyFrame->getEmail() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneCompanyFrame->getEmail();
			}
			$msg .= "</td><td>";
			if ($companyFrame->getEmail() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $companyFrame->getEmail();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneCompanyFrame->getOtherInfos() != $companyFrame->getOtherInfos()) {
			$msg .= "<tr><td>".$this->translate('CompanyFrame.otherInfos.label').'</td><td>';
			if ($cloneCompanyFrame->getOtherInfos() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneCompanyFrame->getOtherInfos();
			}
			$msg .= "</td><td>";
			if ($companyFrame->getOtherInfos() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $companyFrame->getOtherInfos();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneCompanyFrame->getJob() != $companyFrame->getJob()) {
			$msg .= "<tr><td>".$this->translate('CompanyFrame.job.label').'</td><td>';
			if ($cloneCompanyFrame->getJob() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneCompanyFrame->getJob()->getLabel();
			}
			$msg .= "</td><td>";
			if ($companyFrame->getJob() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $companyFrame->getJob()->getLabel();
			}
			$msg .= "</td></tr>";
		}

		if ($msg != "") {

			$msg = $table_begin.$msg.$table_end;

			$trace->setMsg(
				$this->translate(
					'CompanyFrame.traceEdit',
					array('%companyFrame%' => $companyFrame->getFullName(), '%company%' => $companyFrame->getCompany()->getCorporateName())
					).$msg
				);
			$trace->setDtCrea(new \DateTime('now'));
			$em = $this->getEntityManager();
			$em->persist($trace);
			$em->flush();
		}
	}
}