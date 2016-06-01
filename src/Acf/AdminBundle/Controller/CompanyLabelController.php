<?php
namespace Acf\AdminBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\CompanyLabel;
use Acf\AdminBundle\Form\CompanyLabel\UpdateTForm as CompanyLabelUpdateTForm;
use Acf\DataBundle\Entity\Trace;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class CompanyLabelController extends BaseController
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
            $urlFrom = $this->generateUrl('_admin_companyLabel_list');
        }
        $em = $this->getEntityManager();
        try {
            $companyLabel = $em->getRepository('AcfDataBundle:CompanyLabel')->find($uid);

            if (null == $companyLabel) {
                $this->flashMsgSession('warning', $this->translate('CompanyLabel.delete.notfound'));
            } else {
                $em->remove($companyLabel);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('CompanyLabel.delete.success', array(
                    '%companyLabel%' => $companyLabel->getName()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('CompanyLabel.delete.failure'));
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
            $companyLabel = $em->getRepository('AcfDataBundle:CompanyLabel')->find($uid);

            if (null == $companyLabel) {
                $this->flashMsgSession('warning', $this->translate('CompanyLabel.edit.notfound'));
            } else {
                $traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($companyLabel->getId(), Trace::AE_LABEL);
                $this->gvars['traces'] = array_reverse($traces);
                $companyLabelUpdateForm = $this->createForm(CompanyLabelUpdateTForm::class, $companyLabel);

                $this->gvars['companyLabel'] = $companyLabel;
                $this->gvars['CompanyLabelUpdateForm'] = $companyLabelUpdateForm->createView();

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');

                $this->gvars['pagetitle'] = $this->translate('pagetitle.companyLabel.edit', array(
                    '%companyLabel%' => $companyLabel->getName()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.companyLabel.edit.txt', array(
                    '%companyLabel%' => $companyLabel->getName()
                ));

                return $this->renderResponse('AcfAdminBundle:CompanyLabel:edit.html.twig', $this->gvars);
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
            $companyLabel = $em->getRepository('AcfDataBundle:CompanyLabel')->find($uid);

            if (null == $companyLabel) {
                $this->flashMsgSession('warning', $this->translate('CompanyLabel.edit.notfound'));
            } else {
                $traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($companyLabel->getId(), Trace::AE_LABEL);
                $this->gvars['traces'] = array_reverse($traces);
                $companyLabelUpdateForm = $this->createForm(CompanyLabelUpdateTForm::class, $companyLabel);

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                $cloneCompanyLabel = clone $companyLabel;

                if (isset($reqData['CompanyLabelUpdateForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $companyLabelUpdateForm->handleRequest($request);
                    if ($companyLabelUpdateForm->isValid()) {
                        $em->persist($companyLabel);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('CompanyLabel.edit.success', array(
                            '%companyLabel%' => $companyLabel->getName()
                        )));

                        $this->traceEntity($cloneCompanyLabel, $companyLabel);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($companyLabel);

                        $this->flashMsgSession('error', $this->translate('CompanyLabel.edit.failure', array(
                            '%companyLabel%' => $companyLabel->getName()
                        )));
                    }
                }

                $this->gvars['companyLabel'] = $companyLabel;
                $this->gvars['CompanyLabelUpdateForm'] = $companyLabelUpdateForm->createView();

                $this->gvars['pagetitle'] = $this->translate('pagetitle.companyLabel.edit', array(
                    '%companyLabel%' => $companyLabel->getName()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.companyLabel.edit.txt', array(
                    '%companyLabel%' => $companyLabel->getName()
                ));

                return $this->renderResponse('AcfAdminBundle:CompanyLabel:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }

    protected function traceEntity(CompanyLabel $cloneCompanyLabel, CompanyLabel $companyLabel)
    {
        $curUser = $this->getSecurityTokenStorage()
            ->getToken()
            ->getUser();
        $trace = new Trace();
        $trace->setActionId($companyLabel->getId());
        $trace->setActionType(Trace::AT_UPDATE);
        $trace->setUserId($curUser->getId());
        $trace->setCompanyId($companyLabel->getCompany()
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

        $trace->setActionEntity(Trace::AE_LABEL);
        $trace->setActionId2($companyLabel->getCompany()
        ->getId());
        $trace->setActionEntity2(Trace::AE_COMPANY);

        $msg = '';

        if ($cloneCompanyLabel->getAbrev() != $companyLabel->getAbrev()) {
            $msg .= '<tr><td>' . $this->translate('CompanyLabel.abrev.label') . '</td><td>';
            if ($cloneCompanyLabel->getAbrev() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompanyLabel->getAbrev();
            }
            $msg .= '</td><td>';
            if ($companyLabel->getAbrev() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $companyLabel->getAbrev();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneCompanyLabel->getName() != $companyLabel->getName()) {
            $msg .= '<tr><td>' . $this->translate('CompanyLabel.name.label') . '</td><td>';
            if ($cloneCompanyLabel->getName() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneCompanyLabel->getName();
            }
            $msg .= '</td><td>';
            if ($companyLabel->getName() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $companyLabel->getName();
            }
            $msg .= '</td></tr>';
        }

        if ($msg != '') {

            $msg = $tableBegin . $msg . $tableEnd;

            $trace->setMsg($this->translate('CompanyLabel.traceEdit', array(
                '%companyLabel%' => $companyLabel->getName(),
                '%company%' => $companyLabel->getCompany()
                    ->getCorporateName()
            )) . $msg);
            $trace->setDtCrea(new \DateTime('now'));
            $em = $this->getEntityManager();
            $em->persist($trace);
            $em->flush();
        }
    }
}