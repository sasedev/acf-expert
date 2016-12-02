<?php
namespace Acf\ClientBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\Customer;
use Acf\ClientBundle\Form\Customer\UpdateTForm as CustomerUpdateTForm;
use Acf\ClientBundle\Form\Customer\UpdateDocsTForm as CustomerUpdateDocsTForm;
use Acf\ClientBundle\Form\Doc\NewTForm as DocNewTForm;
use Acf\DataBundle\Entity\Doc;
use Acf\DataBundle\Entity\ConstantStr;
use Acf\DataBundle\Entity\CompanyUser;
use Acf\DataBundle\Entity\Trace;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class CustomerController extends BaseController
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

	/**
	 *
	 * @param string $uid
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteAction($uid)
	{
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_client_homepage');
		}
		$em = $this->getEntityManager();
		try {
			$customer = $em->getRepository('AcfDataBundle:Customer')->find($uid);

			if (null == $customer) {
				$this->flashMsgSession('warning', $this->translate('Customer.delete.notfound'));
			} else {

				$sc = $this->getSecurityTokenStorage();
				$user = $sc->getToken()->getUser();

				$company = $customer->getCompany();

				$companyUser = $em->getRepository('AcfDataBundle:CompanyUser')->findOneBy(array(
					'company' => $company,
					'user' => $user
				));
				if (null == $companyUser || $companyUser->getDeleteCustomers() == CompanyUser::CANT) {
					$this->flashMsgSession('error', $this->translate('CompanyUser.accessForbidden'));

					return $this->redirect($this->generateUrl('_client_homepage'));
				}
				$this->gvars['companyUser'] = $companyUser;
				$this->gvars['menu_active'] = 'client' . $company->getId();

				$em->remove($customer);
				$em->flush();

				$this->flashMsgSession('success', $this->translate('Customer.delete.success', array(
					'%customer%' => $customer->getLabel()
				)));
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

			$this->flashMsgSession('error', $this->translate('Customer.delete.failure'));
		}

		return $this->redirect($urlFrom);
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
			$urlFrom = $this->generateUrl('_client_homepage');
		}

		$em = $this->getEntityManager();
		try {
			$customer = $em->getRepository('AcfDataBundle:Customer')->find($uid);

			if (null == $customer) {
				$this->flashMsgSession('warning', $this->translate('Customer.edit.notfound'));
			} else {

				$sc = $this->getSecurityTokenStorage();
				$user = $sc->getToken()->getUser();

				$company = $customer->getCompany();

				$companyUser = $em->getRepository('AcfDataBundle:CompanyUser')->findOneBy(array(
					'company' => $company,
					'user' => $user
				));
				if (null == $companyUser) {
					$this->flashMsgSession('error', $this->translate('CompanyUser.accessForbidden'));

					return $this->redirect($this->generateUrl('_client_homepage'));
				}
				$this->gvars['companyUser'] = $companyUser;
				$this->gvars['menu_active'] = 'client' . $company->getId();

				$customerUpdateForm = $this->createForm(CustomerUpdateTForm::class, $customer);
				$customerUpdateDocsForm = $this->createForm(CustomerUpdateDocsTForm::class, $customer, array(
					'company' => $customer->getCompany()
				));

				$doc = new Doc();
				$doc->setCompany($customer->getCompany());
				$docNewForm = $this->createForm(DocNewTForm::class, $doc, array(
					'company' => $customer->getCompany()
				));

				$this->gvars['customer'] = $customer;
				$this->gvars['doc'] = $doc;
				$this->gvars['CustomerUpdateForm'] = $customerUpdateForm->createView();
				$this->gvars['CustomerUpdateDocsForm'] = $customerUpdateDocsForm->createView();
				$this->gvars['DocNewForm'] = $docNewForm->createView();

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
				$this->getSession()->remove('tabActive');

				$this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
				$this->getSession()->remove('stabActive');

				$customersConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
					'name' => 'customersPrefix'
				));
				if (null == $customersConstStr) {
					$customersConstStr = new ConstantStr();
					$customersConstStr->setName('customersPrefix');
					$customersConstStr->setValue('411');
					$em->persist($customersConstStr);
					$em->flush();
				}
				$customersPrefix = $customersConstStr->getValue();
				$this->gvars['customersPrefix'] = $customersPrefix;

				$this->gvars['pagetitle'] = $this->translate('pagetitle.customer.edit', array(
					'%customer%' => $customer->getLabel()
				));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.customer.edit.txt', array(
					'%customer%' => $customer->getLabel()
				));

				return $this->renderResponse('AcfClientBundle:Customer:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
		}

		return $this->redirect($urlFrom);
	}

	/**
	 *
	 * @param string $uid
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function editPostAction($uid)
	{
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_client_homepage');
		}

		$em = $this->getEntityManager();
		try {
			$customer = $em->getRepository('AcfDataBundle:Customer')->find($uid);

			if (null == $customer) {
				$this->flashMsgSession('warning', $this->translate('Customer.edit.notfound'));
			} else {

				$sc = $this->getSecurityTokenStorage();
				$user = $sc->getToken()->getUser();

				$company = $customer->getCompany();

				$companyUser = $em->getRepository('AcfDataBundle:CompanyUser')->findOneBy(array(
					'company' => $company,
					'user' => $user
				));
				if (null == $companyUser || $companyUser->getEditCustomers() == CompanyUser::CANT) {
					$this->flashMsgSession('error', $this->translate('CompanyUser.accessForbidden'));

					return $this->redirect($this->generateUrl('_client_homepage'));
				}
				$this->gvars['companyUser'] = $companyUser;
				$this->gvars['menu_active'] = 'client' . $company->getId();

				$customerUpdateForm = $this->createForm(CustomerUpdateTForm::class, $customer);
				$customerUpdateDocsForm = $this->createForm(CustomerUpdateDocsTForm::class, $customer, array(
					'company' => $customer->getCompany()
				));

				$doc = new Doc();
				$doc->setCompany($customer->getCompany());
				$docNewForm = $this->createForm(DocNewTForm::class, $doc, array(
					'company' => $customer->getCompany()
				));

				$this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
				$this->getSession()->remove('tabActive');

				$this->gvars['stabActive'] = $this->getSession()->get('stabActive', 1);
				$this->getSession()->remove('stabActive');

				$request = $this->getRequest();
				$reqData = $request->request->all();

				$cloneCustomer = clone $customer;

				if (isset($reqData['CustomerUpdateForm'])) {
					$this->gvars['tabActive'] = 2;
					$this->getSession()->set('tabActive', 2);
					$customerUpdateForm->handleRequest($request);
					if ($customerUpdateForm->isValid()) {
						$em->persist($customer);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Customer.edit.success', array(
							'%customer%' => $customer->getLabel()
						)));

						$this->traceEntity($cloneCustomer, $customer);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($customer);

						$this->flashMsgSession('error', $this->translate('Customer.edit.failure', array(
							'%customer%' => $customer->getLabel()
						)));
					}
				} elseif (isset($reqData['DocNewForm'])) {
					$this->gvars['tabActive'] = 3;
					$this->getSession()->set('tabActive', 3);
					$this->gvars['stabActive'] = 1;
					$this->getSession()->set('stabActive', 1);
					$docNewForm->handleRequest($request);
					if ($docNewForm->isValid()) {
						$docFiles = $docNewForm['fileName']->getData();
						$docs = array();

						$docDir = $this->getParameter('kernel.root_dir') . '/../web/res/docs';

						$docNames = '';

						foreach ($docFiles as $docFile) {

							$originalName = $docFile->getClientOriginalName();
							$fileName = sha1(uniqid(mt_rand(), true)) . '.' . strtolower($docFile->getClientOriginalExtension());
							$mimeType = $docFile->getMimeType();
							$docFile->move($docDir, $fileName);

							$size = filesize($docDir . '/' . $fileName);
							$md5 = md5_file($docDir . '/' . $fileName);

							$doc = new Doc();
							$doc->setCompany($customer->getCompany());

							$doc->setFileName($fileName);
							$doc->setOriginalName($originalName);
							$doc->setSize($size);
							$doc->setMimeType($mimeType);
							$doc->setMd5($md5);
							$doc->setDescription($docNewForm['description']->getData());
							$em->persist($doc);

							$customer->addDoc($doc);

							$docs[] = $doc;

							$docNames .= $doc->getOriginalName() . ' ';
						}

						$em->persist($customer);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Doc.add.success', array(
							'%doc%' => $docNames
						)));
						$this->newDocNotifyAdmin($customer, $docs);
						$this->gvars['stabActive'] = 3;
						$this->getSession()->set('stabActive', 3);

						$this->traceEntity($cloneCustomer, $customer);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($customer);

						$this->flashMsgSession('error', $this->translate('Doc.add.failure'));
					}
				} elseif (isset($reqData['CustomerUpdateDocsForm'])) {
					$this->gvars['tabActive'] = 3;
					$this->getSession()->set('tabActive', 3);
					$this->gvars['stabActive'] = 2;
					$this->getSession()->set('stabActive', 2);
					$customerUpdateDocsForm->handleRequest($request);
					if ($customerUpdateDocsForm->isValid()) {
						$em->persist($customer);
						$em->flush();
						$this->flashMsgSession('success', $this->translate('Customer.edit.success', array(
							'%customer%' => $customer->getLabel()
						)));
						$this->gvars['stabActive'] = 3;
						$this->getSession()->set('stabActive', 3);

						$this->traceEntity($cloneCustomer, $customer);

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($customer);

						$this->flashMsgSession('error', $this->translate('Customer.edit.failure', array(
							'%customer%' => $customer->getLabel()
						)));
					}
				}

				$this->gvars['customer'] = $customer;
				$this->gvars['doc'] = $doc;
				$this->gvars['CustomerUpdateForm'] = $customerUpdateForm->createView();
				$this->gvars['CustomerUpdateDocsForm'] = $customerUpdateDocsForm->createView();
				$this->gvars['DocNewForm'] = $docNewForm->createView();

				$customersConstStr = $em->getRepository('AcfDataBundle:ConstantStr')->findOneBy(array(
					'name' => 'customersPrefix'
				));
				if (null == $customersConstStr) {
					$customersConstStr = new ConstantStr();
					$customersConstStr->setName('customersPrefix');
					$customersConstStr->setValue('411');
					$em->persist($customersConstStr);
					$em->flush();
				}
				$customersPrefix = $customersConstStr->getValue();
				$this->gvars['customersPrefix'] = $customersPrefix;

				$this->gvars['pagetitle'] = $this->translate('pagetitle.customer.edit', array(
					'%customer%' => $customer->getLabel()
				));
				$this->gvars['pagetitle_txt'] = $this->translate('pagetitle.customer.edit.txt', array(
					'%customer%' => $customer->getLabel()
				));

				return $this->renderResponse('AcfClientBundle:Customer:edit.html.twig', $this->gvars);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
		}

		return $this->redirect($urlFrom);
	}

	protected function newDocNotifyAdmin(Customer $customer, $docs)
	{
		$from = $this->getParameter('mail_from');
		$fromName = $this->getParameter('mail_from_name');
		$subject = $this->translate('_mail.newdocs.subject', array(), 'messages');

		$user = $this->getSecurityTokenStorage()->getToken()->getUser();
		$company = $customer->getCompany();

		$admins = $company->getAdmins();
		if (\count($admins) != 0) {
			$mvars = array();
			$mvars['customer'] = $customer;
			$mvars['user'] = $user;
			$mvars['company'] = $company;
			$mvars['docs'] = $docs;
			$message = \Swift_Message::newInstance();
			$message->setFrom($from, $fromName);
			foreach ($admins as $admin) {
				$message->addTo($admin->getEmail(), $admin->getFullname());
			}
			$message->setSubject($subject);
			$mvars['logo'] = $message->embed(\Swift_Image::fromPath($this->getParameter('kernel.root_dir') . '/../web/bundles/acfres/images/logo_acf.jpg'));
			$message->setBody($this->renderView('AcfClientBundle:Mail:Customernewdoc.html.twig', $mvars), 'text/html');
			$this->sendmail($message);
		}
	}

	protected function traceEntity(Customer $cloneCustomer, Customer $customer)
	{
		$curUser = $this->getSecurityTokenStorage()->getToken()->getUser();
		$trace = new Trace();
		$trace->setActionId($customer->getId());
		$trace->setActionType(Trace::AT_UPDATE);
		$trace->setUserId($curUser->getId());
		$trace->setCompanyId($customer->getCompany()->getId());
		$trace->setUserFullname($curUser->getFullName());
		if (!$this->hasRole('ROLE_SUPERADMIN')) {
			if (!$this->hasRole('ROLE_ADMIN')) {
				$trace->setUserType(Trace::UT_CLIENT);
			} else {
				$trace->setUserType(Trace::UT_ADMIN);
			}
		} else {
			$trace->setUserType(Trace::UT_SUPERADMIN);
		}

		$tableBegin = ': <br><table class="table table-bordered table-condensed table-hover table-striped">';
		$tableBegin .= '<thead><tr><th class="text-left">' . $this->translate('Entity.field') . '</th>';
		$tableBegin .= '<th class="text-left">' . $this->translate('Entity.oldVal') . '</th>';
		$tableBegin .= '<th class="text-left">' . $this->translate('Entity.newVal') . '</th></tr></thead><tbody>';

		$tableEnd = '</tbody></table>';

		$trace->setActionEntity(Trace::AE_CUSTOMER);
		$trace->setActionId2($customer->getCompany()->getId());
		$trace->setActionEntity2(Trace::AE_COMPANY);

		$msg = '';

		if ($cloneCustomer->getLabel() != $customer->getLabel()) {
			$msg .= '<tr><td>' . $this->translate('Customer.label.label') . '</td><td>';
			if ($cloneCustomer->getLabel() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneCustomer->getLabel();
			}
			$msg .= '</td><td>';
			if ($customer->getLabel() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $customer->getLabel();
			}
			$msg .= '</td></tr>';
		}

		if ($cloneCustomer->getNumber() != $customer->getNumber()) {
			$msg .= '<tr><td>' . $this->translate('Customer.number.label') . '</td><td>';
			if ($cloneCustomer->getNumber() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneCustomer->getNumberFormated();
			}
			$msg .= '</td><td>';
			if ($customer->getNumber() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $customer->getNumberFormated();
			}
			$msg .= '</td></tr>';
		}

		if ($cloneCustomer->getFisc() != $customer->getFisc()) {
			$msg .= '<tr><td>' . $this->translate('Customer.fisc.label') . '</td><td>';
			if ($cloneCustomer->getFisc() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneCustomer->getFisc();
			}
			$msg .= '</td><td>';
			if ($customer->getFisc() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $customer->getFisc();
			}
			$msg .= '</td></tr>';
		}

		if ($cloneCustomer->getPhysicaltype() != $customer->getPhysicaltype()) {
			$msg .= '<tr><td>' . $this->translate('Customer.physicaltype.label') . '</td><td>';
			if ($cloneCustomer->getPhysicaltype() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $this->translate('Customer.physicaltype.' . $cloneCustomer->getPhysicaltype());
			}
			$msg .= '</td><td>';
			if ($customer->getSexe() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $this->translate('Customer.physicaltype.' . $customer->getPhysicaltype());
			}
			$msg .= '</td></tr>';
		}

		if ($cloneCustomer->getCin() != $customer->getCin()) {
			$msg .= '<tr><td>' . $this->translate('Customer.cin.label') . '</td><td>';
			if ($cloneCustomer->getCin() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneCustomer->getCin();
			}
			$msg .= '</td><td>';
			if ($customer->getCin() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $customer->getCin();
			}
			$msg .= '</td></tr>';
		}

		if ($cloneCustomer->getPassport() != $customer->getPassport()) {
			$msg .= '<tr><td>' . $this->translate('Customer.passport.label') . '</td><td>';
			if ($cloneCustomer->getPassport() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneCustomer->getPassport();
			}
			$msg .= '</td><td>';
			if ($customer->getPassport() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $customer->getPassport();
			}
			$msg .= '</td></tr>';
		}

		if ($cloneCustomer->getCommercialRegister() != $customer->getCommercialRegister()) {
			$msg .= '<tr><td>' . $this->translate('Customer.commercialRegister.label') . '</td><td>';
			if ($cloneCustomer->getCommercialRegister() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneCustomer->getCommercialRegister();
			}
			$msg .= '</td><td>';
			if ($customer->getCommercialRegister() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $customer->getCommercialRegister();
			}
			$msg .= '</td></tr>';
		}

		if ($cloneCustomer->getStreetNum() != $customer->getStreetNum()) {
			$msg .= '<tr><td>' . $this->translate('Customer.streetNum.label') . '</td><td>';
			if ($cloneCustomer->getStreetNum() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneCustomer->getStreetNum();
			}
			$msg .= '</td><td>';
			if ($customer->getStreetNum() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $customer->getStreetNum();
			}
			$msg .= '</td></tr>';
		}

		if ($cloneCustomer->getAddress() != $customer->getAddress()) {
			$msg .= '<tr><td>' . $this->translate('Customer.address.label') . '</td><td>';
			if ($cloneCustomer->getAddress() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneCustomer->getAddress();
			}
			$msg .= '</td><td>';
			if ($customer->getAddress() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $customer->getAddress();
			}
			$msg .= '</td></tr>';
		}

		if ($cloneCustomer->getAddress2() != $customer->getAddress2()) {
			$msg .= '<tr><td>' . $this->translate('Customer.address2.label') . '</td><td>';
			if ($cloneCustomer->getAddress2() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneCustomer->getAddress2();
			}
			$msg .= '</td><td>';
			if ($customer->getAddress2() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $customer->getAddress2();
			}
			$msg .= '</td></tr>';
		}

		if ($cloneCustomer->getTown() != $customer->getTown()) {
			$msg .= '<tr><td>' . $this->translate('Customer.town.label') . '</td><td>';
			if ($cloneCustomer->getTown() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneCustomer->getTown();
			}
			$msg .= '</td><td>';
			if ($customer->getTown() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $customer->getTown();
			}
			$msg .= '</td></tr>';
		}

		if ($cloneCustomer->getZipCode() != $customer->getZipCode()) {
			$msg .= '<tr><td>' . $this->translate('Customer.zipCode.label') . '</td><td>';
			if ($cloneCustomer->getZipCode() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneCustomer->getZipCode();
			}
			$msg .= '</td><td>';
			if ($customer->getZipCode() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $customer->getZipCode();
			}
			$msg .= '</td></tr>';
		}

		if ($cloneCustomer->getCountry() != $customer->getCountry()) {
			$msg .= '<tr><td>' . $this->translate('Customer.country.label') . '</td><td>';
			if ($cloneCustomer->getCountry() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneCustomer->getCountry();
			}
			$msg .= '</td><td>';
			if ($customer->getCountry() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $customer->getCountry();
			}
			$msg .= '</td></tr>';
		}

		if ($cloneCustomer->getPhone() != $customer->getPhone()) {
			$msg .= '<tr><td>' . $this->translate('Customer.phone.label') . '</td><td>';
			if ($cloneCustomer->getPhone() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneCustomer->getPhone();
			}
			$msg .= '</td><td>';
			if ($customer->getPhone() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $customer->getPhone();
			}
			$msg .= '</td></tr>';
		}

		if ($cloneCustomer->getMobile() != $customer->getMobile()) {
			$msg .= '<tr><td>' . $this->translate('Customer.mobile.label') . '</td><td>';
			if ($cloneCustomer->getMobile() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneCustomer->getMobile();
			}
			$msg .= '</td><td>';
			if ($customer->getMobile() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $customer->getMobile();
			}
			$msg .= '</td></tr>';
		}

		if ($cloneCustomer->getFax() != $customer->getFax()) {
			$msg .= '<tr><td>' . $this->translate('Customer.fax.label') . '</td><td>';
			if ($cloneCustomer->getFax() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneCustomer->getFax();
			}
			$msg .= '</td><td>';
			if ($customer->getFax() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $customer->getFax();
			}
			$msg .= '</td></tr>';
		}

		if ($cloneCustomer->getEmail() != $customer->getEmail()) {
			$msg .= '<tr><td>' . $this->translate('Customer.email.label') . '</td><td>';
			if ($cloneCustomer->getEmail() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneCustomer->getEmail();
			}
			$msg .= '</td><td>';
			if ($customer->getEmail() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $customer->getEmail();
			}
			$msg .= '</td></tr>';
		}

		if ($cloneCustomer->getOtherInfos() != $customer->getOtherInfos()) {
			$msg .= '<tr><td>' . $this->translate('Customer.otherInfos.label') . '</td><td>';
			if ($cloneCustomer->getOtherInfos() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $cloneCustomer->getOtherInfos();
			}
			$msg .= '</td><td>';
			if ($customer->getOtherInfos() == null) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= $customer->getOtherInfos();
			}
			$msg .= '</td></tr>';
		}

		if (\count(\array_diff($customer->getDocs()->toArray(), $cloneCustomer->getDocs()->toArray())) != 0 || \count(\array_diff($cloneCustomer->getDocs()->toArray(), $customer->getDocs()->toArray())) != 0) {
			$msg .= '<tr><td>' . $this->translate('Customer.docs.label') . '</td><td>';
			if (\count($cloneCustomer->getDocs()) == 0) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= '<ul>';
				foreach ($cloneCustomer->getDocs() as $doc) {
					$msg .= '<li><a href="' . $this->generateUrl('_admin_doc_editGet', array(
						'uid' => $doc->getId()
					)) . '">' . $doc->getOriginalName() . '</a></li>';
				}
				$msg .= '<ul>';
			}
			$msg .= '</td><td>';
			if (\count($customer->getDocs()) == 0) {
				$msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
			} else {
				$msg .= '<ul>';
				foreach ($customer->getDocs() as $doc) {
					$msg .= '<li><a href="' . $this->generateUrl('_admin_doc_editGet', array(
						'uid' => $doc->getId()
					)) . '">' . $doc->getOriginalName() . '</a></li>';
				}
				$msg .= '<ul>';
			}
			$msg .= '</td></tr>';
		}

		if ($msg != '') {

			$msg = $tableBegin . $msg . $tableEnd;

			$trace->setMsg($this->translate('Customer.traceEdit', array(
				'%customer%' => $customer->getLabel(),
				'%company%' => $customer->getCompany()->getCorporateName()
			)) . $msg);
			$trace->setDtCrea(new \DateTime('now'));
			$em = $this->getEntityManager();
			$em->persist($trace);
			$em->flush();
		}
	}
}
