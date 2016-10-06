<?php
namespace Acf\AdminBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\Shareholder;
use Acf\AdminBundle\Form\Shareholder\UpdateTForm as ShareholderUpdateTForm;
use Acf\DataBundle\Entity\Trace;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class ShareholderController extends BaseController
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
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_shareholder_list');
        }
        $em = $this->getEntityManager();
        try {
            $shareholder = $em->getRepository('AcfDataBundle:Shareholder')->find($uid);

            if (null == $shareholder) {
                $this->flashMsgSession('warning', $this->translate('Shareholder.delete.notfound'));
            } else {
                $em->remove($shareholder);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('Shareholder.delete.success', array(
                    '%shareholder%' => $shareholder->getName()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('Shareholder.delete.failure'));
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
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_company_list');
        }

        $em = $this->getEntityManager();
        try {
            $shareholder = $em->getRepository('AcfDataBundle:Shareholder')->find($uid);

            if (null == $shareholder) {
                $this->flashMsgSession('warning', $this->translate('Shareholder.edit.notfound'));
            } else {
                $traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($shareholder->getId(), Trace::AE_SHAREHOLDER);
                $this->gvars['traces'] = array_reverse($traces);
                $shareholderUpdateForm = $this->createForm(ShareholderUpdateTForm::class, $shareholder);

                $this->gvars['shareholder'] = $shareholder;
                $this->gvars['ShareholderUpdateForm'] = $shareholderUpdateForm->createView();

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');

                $this->gvars['pagetitle'] = $this->translate('pagetitle.shareholder.edit', array(
                    '%shareholder%' => $shareholder->getName()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.shareholder.edit.txt', array(
                    '%shareholder%' => $shareholder->getName()
                ));

                return $this->renderResponse('AcfAdminBundle:Shareholder:edit.html.twig', $this->gvars);
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
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_company_list');
        }

        $em = $this->getEntityManager();
        try {
            $shareholder = $em->getRepository('AcfDataBundle:Shareholder')->find($uid);

            if (null == $shareholder) {
                $this->flashMsgSession('warning', $this->translate('Shareholder.edit.notfound'));
            } else {
                $traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($shareholder->getId(), Trace::AE_SHAREHOLDER);
                $this->gvars['traces'] = array_reverse($traces);
                $shareholderUpdateForm = $this->createForm(ShareholderUpdateTForm::class, $shareholder);

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                $cloneShareholder = clone $shareholder;

                if (isset($reqData['ShareholderUpdateForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $shareholderUpdateForm->handleRequest($request);
                    if ($shareholderUpdateForm->isValid()) {
                        $em->persist($shareholder);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Shareholder.edit.success', array(
                            '%shareholder%' => $shareholder->getName()
                        )));

                        $this->traceEntity($cloneShareholder, $shareholder);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($shareholder);

                        $this->flashMsgSession('error', $this->translate('Shareholder.edit.failure', array(
                            '%shareholder%' => $shareholder->getName()
                        )));
                    }
                }

                $this->gvars['shareholder'] = $shareholder;
                $this->gvars['ShareholderUpdateForm'] = $shareholderUpdateForm->createView();

                $this->gvars['pagetitle'] = $this->translate('pagetitle.shareholder.edit', array(
                    '%shareholder%' => $shareholder->getName()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.shareholder.edit.txt', array(
                    '%shareholder%' => $shareholder->getName()
                ));

                return $this->renderResponse('AcfAdminBundle:Shareholder:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }

    protected function traceEntity(Shareholder $cloneShareholder, Shareholder $shareholder)
    {
        $curUser = $this->getSecurityTokenStorage()
            ->getToken()
            ->getUser();
        $trace = new Trace();
        $trace->setActionId($shareholder->getId());
        $trace->setActionType(Trace::AT_UPDATE);
        $trace->setUserId($curUser->getId());
        $trace->setCompanyId($shareholder->getCompany()
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

        $trace->setActionEntity(Trace::AE_WHITHHOLDING);
        $trace->setActionId2($shareholder->getCompany()
            ->getId());
        $trace->setActionEntity2(Trace::AE_COMPANY);

        $msg = '';

        if ($cloneShareholder->getName() != $shareholder->getName()) {
            $msg .= '<tr><td>' . $this->translate('Shareholder.name.label') . '</td><td>';
            if ($cloneShareholder->getName() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneShareholder->getName();
            }
            $msg .= '</td><td>';
            if ($shareholder->getName() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $shareholder->getName();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneShareholder->getCin() != $shareholder->getCin()) {
            $msg .= '<tr><td>' . $this->translate('Shareholder.cin.label') . '</td><td>';
            if ($cloneShareholder->getCin() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneShareholder->getCin();
            }
            $msg .= '</td><td>';
            if ($shareholder->getCin() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $shareholder->getCin();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneShareholder->getQuality() != $shareholder->getQuality()) {
            $msg .= '<tr><td>' . $this->translate('Shareholder.quality.label') . '</td><td>';
            if ($cloneShareholder->getQuality() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneShareholder->getQuality();
            }
            $msg .= '</td><td>';
            if ($shareholder->getQuality() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $shareholder->getQuality();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneShareholder->getAddress() != $shareholder->getAddress()) {
            $msg .= '<tr><td>' . $this->translate('Shareholder.address.label') . '</td><td>';
            if ($cloneShareholder->getAddress() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneShareholder->getAddress();
            }
            $msg .= '</td><td>';
            if ($shareholder->getAddress() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $shareholder->getAddress();
            }
            $msg .= '</td></tr>';
        }

        if ($cloneShareholder->getTrades() != $shareholder->getTrades()) {
            $msg .= '<tr><td>' . $this->translate('Shareholder.trades.label') . '</td><td>';
            if ($cloneShareholder->getTrades() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneShareholder->getTrades();
            }
            $msg .= '</td><td>';
            if ($shareholder->getTrades() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $shareholder->getTrades();
            }
            $msg .= '</td></tr>';
        }

        if ($msg != '') {

            $msg = $tableBegin . $msg . $tableEnd;

            $trace->setMsg($this->translate('Shareholder.traceEdit', array(
                '%shareholder%' => $shareholder->getName(),
                '%company%' => $shareholder->getCompany()
                    ->getCorporateName()
            )) . $msg);
            $trace->setDtCrea(new \DateTime('now'));
            $em = $this->getEntityManager();
            $em->persist($trace);
            $em->flush();
        }
    }
}