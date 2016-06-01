<?php
namespace Acf\AdminBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\Phone;
use Acf\AdminBundle\Form\Phone\UpdateTForm as PhoneUpdateTForm;
use Acf\DataBundle\Entity\Trace;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class PhoneController extends BaseController
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
            $phone = $em->getRepository('AcfDataBundle:Phone')->find($uid);

            if (null == $phone) {
                $this->flashMsgSession('warning', $this->translate('Phone.delete.notfound'));
            } else {
                $em->remove($phone);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('Phone.delete.success', array(
                    '%phone%' => $phone->getLabel()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('Phone.delete.failure'));
        }

        return $this->redirect($urlFrom);
    }

    /**
     *
     * @param string $uid
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editGetAction($uid)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_company_list');
        }

        $em = $this->getEntityManager();
        try {
            $phone = $em->getRepository('AcfDataBundle:Phone')->find($uid);

            if (null == $phone) {
                $this->flashMsgSession('warning', $this->translate('Phone.edit.notfound'));
            } else {
                $traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($phone->getId(), Trace::AE_PHONE);
                $this->gvars['traces'] = array_reverse($traces);
                $phoneUpdateForm = $this->createForm(PhoneUpdateTForm::class, $phone);

                $this->gvars['phone'] = $phone;
                $this->gvars['PhoneUpdateForm'] = $phoneUpdateForm->createView();

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');

                $this->gvars['pagetitle'] = $this->translate('pagetitle.phone.edit', array(
                    '%phone%' => $phone->getLabel()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.phone.edit.txt', array(
                    '%phone%' => $phone->getLabel()
                ));

                return $this->renderResponse('AcfAdminBundle:Phone:edit.html.twig', $this->gvars);
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
            $urlFrom = $this->generateUrl('_admin_company_list');
        }

        $em = $this->getEntityManager();
        try {
            $phone = $em->getRepository('AcfDataBundle:Phone')->find($uid);

            if (null == $phone) {
                $this->flashMsgSession('warning', $this->translate('Phone.edit.notfound'));
            } else {
                $traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($phone->getId(), Trace::AE_PHONE);
                $this->gvars['traces'] = array_reverse($traces);
                $phoneUpdateForm = $this->createForm(PhoneUpdateTForm::class, $phone);

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                $clonePhone = clone $phone;

                if (isset($reqData['PhoneUpdateForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $phoneUpdateForm->handleRequest($request);
                    if ($phoneUpdateForm->isValid()) {
                        $em->persist($phone);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Phone.edit.success', array(
                            '%phone%' => $phone->getLabel()
                        )));

                        $this->traceEntity($clonePhone, $phone);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($phone);

                        $this->flashMsgSession('error', $this->translate('Phone.edit.failure', array(
                            '%phone%' => $phone->getLabel()
                        )));
                    }
                }

                $this->gvars['phone'] = $phone;
                $this->gvars['PhoneUpdateForm'] = $phoneUpdateForm->createView();

                $this->gvars['pagetitle'] = $this->translate('pagetitle.phone.edit', array(
                    '%phone%' => $phone->getLabel()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.phone.edit.txt', array(
                    '%phone%' => $phone->getLabel()
                ));

                return $this->renderResponse('AcfAdminBundle:Phone:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }

    protected function traceEntity(Phone $clonePhone, Phone $phone)
    {
        $curUser = $this->getSecurityTokenStorage()
            ->getToken()
            ->getUser();
        $trace = new Trace();
        $trace->setActionId($phone->getId());
        $trace->setActionType(Trace::AT_UPDATE);
        $trace->setUserId($curUser->getId());
        $trace->setCompanyId($phone->getCompany()
        ->getId());
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

        $trace->setActionEntity(Trace::AE_PHONE);
        $trace->setActionId2($phone->getCompany()
        ->getId());
        $trace->setActionEntity2(Trace::AE_COMPANY);

        $msg = '';

        if ($clonePhone->getLabel() != $phone->getLabel()) {
            $msg .= '<tr><td>' . $this->translate('Phone.label.label') . '</td><td>';
            if ($clonePhone->getLabel() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePhone->getLabel();
            }
            $msg .= '</td><td>';
            if ($phone->getLabel() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $phone->getLabel();
            }
            $msg .= '</td></tr>';
        }

        if ($clonePhone->getPhone() != $phone->getPhone()) {
            $msg .= '<tr><td>' . $this->translate('Phone.phone.label') . '</td><td>';
            if ($clonePhone->getPhone() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePhone->getPhone();
            }
            $msg .= '</td><td>';
            if ($phone->getPhone() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $phone->getPhone();
            }
            $msg .= '</td></tr>';
        }

        if ($clonePhone->getContact() != $phone->getContact()) {
            $msg .= '<tr><td>' . $this->translate('Phone.contact.label') . '</td><td>';
            if ($clonePhone->getContact() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePhone->getContact();
            }
            $msg .= '</td><td>';
            if ($phone->getContact() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $phone->getContact();
            }
            $msg .= '</td></tr>';
        }

        if ($msg != '') {

            $msg = $tableBegin . $msg . $tableEnd;

            $trace->setMsg($this->translate('Phone.traceEdit', array(
                '%phone%' => $phone->getPhone(),
                '%company%' => $phone->getCompany()
                    ->getCorporateName()
            )) . $msg);
            $trace->setDtCrea(new \DateTime('now'));
            $em = $this->getEntityManager();
            $em->persist($trace);
            $em->flush();
        }
    }
}