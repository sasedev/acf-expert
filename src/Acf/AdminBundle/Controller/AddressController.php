<?php
namespace Acf\AdminBundle\Controller;

use Acf\AdminBundle\Form\Address\UpdateTForm as AddressUpdateTForm;
use Acf\DataBundle\Entity\Address;
use Acf\DataBundle\Entity\Trace;
use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author sasedev
 *
 */
class AddressController extends BaseController
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
			$address = $em->getRepository('AcfDataBundle:Address')->find($uid);

			if (null == $address) {
				$this->flashMsgSession('warning', $this->translate('Address.delete.notfound'));
			} else {
				$em->remove($address);
				$em->flush();

				$this->flashMsgSession(
					'success',
					$this->translate('Address.delete.success', array('%address%' => $address->getLabel()))
				);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());

			$this->flashMsgSession('error', $this->translate('Address.delete.failure'));
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
			$address = $em->getRepository('AcfDataBundle:Address')->find($uid);

			if (null == $address) {
				$this->flashMsgSession('warning', $this->translate('Address.edit.notfound'));
			} else {
				$traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($address->getId(), Trace::AE_ADDRESS);
				$this->gvars['traces'] = array_reverse($traces);
				$addressUpdateForm = $this->createForm(AddressUpdateTForm::class, $address);


				$this->gvars['address'] = $address;
				$this->gvars['AddressUpdateForm'] = $addressUpdateForm->createView();

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
				$this->getSession()->remove('tabActive');



				$this->gvars['pagetitle'] = $this->translate('pagetitle.address.edit', array('%address%' => $address->getLabel()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.address.edit.txt', array('%address%' => $address->getLabel()));

				return $this->renderResponse('AcfAdminBundle:Address:edit.html.twig', $this->gvars);
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
			$address = $em->getRepository('AcfDataBundle:Address')->find($uid);

			if (null == $address) {
				$this->flashMsgSession('warning', $this->translate('Address.edit.notfound'));
			} else {
				$traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($address->getId(), Trace::AE_ADDRESS);
				$this->gvars['traces'] = array_reverse($traces);
				$addressUpdateForm = $this->createForm(AddressUpdateTForm::class, $address);



				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
				$this->getSession()->remove('tabActive');

				$reqData = $request->request->all();

				$cloneAddress = clone $address;

				if (isset($reqData['AddressUpdateForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$addressUpdateForm->handleRequest($request);
					if ($addressUpdateForm->isValid()) {
						$em->persist($address);
						$em->flush();
						$this->flashMsgSession(
							'success',
							$this->translate('Address.edit.success', array('%address%' => $address->getLabel()))
						);

						$this->traceEntity($cloneAddress, $address);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($address);

						$this->flashMsgSession('error', $this->translate('Address.edit.failure', array('%address%' => $address->getLabel())));
					}
				}

				$this->gvars['address'] = $address;
				$this->gvars['AddressUpdateForm'] = $addressUpdateForm->createView();



				$this->gvars['pagetitle'] = $this->translate('pagetitle.address.edit', array('%address%' => $address->getLabel()));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.address.edit.txt', array('%address%' => $address->getLabel()));

				return $this->renderResponse('AcfAdminBundle:Address:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());
		}

		return $this->redirect($urlFrom);
	}

	protected function traceEntity(Address $cloneAddress, Address $address) {

		$curUser = $this->getSecurityTokenStorage()->getToken()->getUser();
		$trace = new Trace();
		$trace->setActionId($address->getId());
		$trace->setActionType(Trace::AT_UPDATE);
		$trace->setUserId($curUser->getId());
		$trace->setCompanyId($address->getCompany()->getId());
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

		$trace->setActionEntity(Trace::AE_ADDRESS);
		$trace->setActionId2($address->getCompany()->getId());
		$trace->setActionEntity2(Trace::AE_COMPANY);

		$msg = "";

		if ($cloneAddress->getLabel() != $address->getLabel()) {
			$msg .= "<tr><td>".$this->translate('Address.label.label').'</td><td>';
			if ($cloneAddress->getLabel() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneAddress->getLabel();
			}
			$msg .= "</td><td>";
			if ($address->getLabel() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $address->getLabel();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneAddress->getStreetNum() != $address->getStreetNum()) {
			$msg .= "<tr><td>".$this->translate('Address.streetNum.label').'</td><td>';
			if ($cloneAddress->getStreetNum() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneAddress->getStreetNum();
			}
			$msg .= "</td><td>";
			if ($address->getStreetNum() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $address->getStreetNum();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneAddress->getAddress() != $address->getAddress()) {
			$msg .= "<tr><td>".$this->translate('Address.address.label').'</td><td>';
			if ($cloneAddress->getAddress() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneAddress->getAddress();
			}
			$msg .= "</td><td>";
			if ($address->getAddress() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $address->getAddress();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneAddress->getAddress2() != $address->getAddress2()) {
			$msg .= "<tr><td>".$this->translate('Address.address2.label').'</td><td>';
			if ($cloneAddress->getAddress2() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneAddress->getAddress2();
			}
			$msg .= "</td><td>";
			if ($address->getAddress2() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $address->getAddress2();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneAddress->getTown() != $address->getTown()) {
			$msg .= "<tr><td>".$this->translate('Address.town.label').'</td><td>';
			if ($cloneAddress->getTown() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneAddress->getTown();
			}
			$msg .= "</td><td>";
			if ($address->getTown() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $address->getTown();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneAddress->getZipCode() != $address->getZipCode()) {
			$msg .= "<tr><td>".$this->translate('Address.zipCode.label').'</td><td>';
			if ($cloneAddress->getZipCode() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneAddress->getZipCode();
			}
			$msg .= "</td><td>";
			if ($address->getZipCode() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $address->getZipCode();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneAddress->getCountry() != $address->getCountry()) {
			$msg .= "<tr><td>".$this->translate('Address.country.label').'</td><td>';
			if ($cloneAddress->getCountry() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneAddress->getCountry();
			}
			$msg .= "</td><td>";
			if ($address->getCountry() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $address->getCountry();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneAddress->getPhone() != $address->getPhone()) {
			$msg .= "<tr><td>".$this->translate('Address.phone.label').'</td><td>';
			if ($cloneAddress->getPhone() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneAddress->getPhone();
			}
			$msg .= "</td><td>";
			if ($address->getPhone() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $address->getPhone();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneAddress->getMobile() != $address->getMobile()) {
			$msg .= "<tr><td>".$this->translate('Address.mobile.label').'</td><td>';
			if ($cloneAddress->getMobile() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneAddress->getMobile();
			}
			$msg .= "</td><td>";
			if ($address->getMobile() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $address->getMobile();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneAddress->getFax() != $address->getFax()) {
			$msg .= "<tr><td>".$this->translate('Address.fax.label').'</td><td>';
			if ($cloneAddress->getFax() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneAddress->getFax();
			}
			$msg .= "</td><td>";
			if ($address->getFax() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $address->getFax();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneAddress->getEmail() != $address->getEmail()) {
			$msg .= "<tr><td>".$this->translate('Address.email.label').'</td><td>';
			if ($cloneAddress->getEmail() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneAddress->getEmail();
			}
			$msg .= "</td><td>";
			if ($address->getEmail() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $address->getEmail();
			}
			$msg .= "</td></tr>";
		}

		if ($cloneAddress->getOtherInfos() != $address->getOtherInfos()) {
			$msg .= "<tr><td>".$this->translate('Address.otherInfos.label').'</td><td>';
			if ($cloneAddress->getOtherInfos() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $cloneAddress->getOtherInfos();
			}
			$msg .= "</td><td>";
			if ($address->getOtherInfos() == null) {
				$msg .= '<span class="label label-warning">'.$this->translate('_NA').'</span>';
			} else {
				$msg .= $address->getOtherInfos();
			}
			$msg .= "</td></tr>";
		}

		if ($msg != "") {

			$msg = $table_begin.$msg.$table_end;

			$trace->setMsg(
				$this->translate(
					'Address.traceEdit',
					array('%address%' => $address->getFullAddress(), '%company%' => $address->getCompany()->getCorporateName())
					).$msg
				);
			$trace->setDtCrea(new \DateTime('now'));
			$em = $this->getEntityManager();
			$em->persist($trace);
			$em->flush();
		}
	}

}
