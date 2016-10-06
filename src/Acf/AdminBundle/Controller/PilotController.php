<?php
namespace Acf\AdminBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\Pilot;
use Acf\AdminBundle\Form\Pilot\UpdateTForm as PilotUpdateTForm;
use Acf\DataBundle\Entity\Trace;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class PilotController extends BaseController
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
            $urlFrom = $this->generateUrl('_admin_pilot_list');
        }
        $em = $this->getEntityManager();
        try {
            $pilot = $em->getRepository('AcfDataBundle:Pilot')->find($uid);

            if (null == $pilot) {
                $this->flashMsgSession('warning', $this->translate('Pilot.delete.notfound'));
            } else {
                $em->remove($pilot);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('Pilot.delete.success', array(
                    '%pilot%' => $pilot->getMission()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('Pilot.delete.failure'));
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
            $pilot = $em->getRepository('AcfDataBundle:Pilot')->find($uid);

            if (null == $pilot) {
                $this->flashMsgSession('warning', $this->translate('Pilot.edit.notfound'));
            } else {
                $traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($pilot->getId(), Trace::AE_PILOT);
                $this->gvars['traces'] = array_reverse($traces);
                $pilotUpdateForm = $this->createForm(PilotUpdateTForm::class, $pilot);

                $this->gvars['pilot'] = $pilot;
                $this->gvars['PilotUpdateForm'] = $pilotUpdateForm->createView();

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');

                $this->gvars['pagetitle'] = $this->translate('pagetitle.pilot.edit', array(
                    '%pilot%' => $pilot->getMission()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.pilot.edit.txt', array(
                    '%pilot%' => $pilot->getMission()
                ));

                return $this->renderResponse('AcfAdminBundle:Pilot:edit.html.twig', $this->gvars);
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
            $pilot = $em->getRepository('AcfDataBundle:Pilot')->find($uid);

            if (null == $pilot) {
                $this->flashMsgSession('warning', $this->translate('Pilot.edit.notfound'));
            } else {
                $traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($pilot->getId(), Trace::AE_PILOT);
                $this->gvars['traces'] = array_reverse($traces);
                $pilotUpdateForm = $this->createForm(PilotUpdateTForm::class, $pilot);

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                $clonePilot = clone $pilot;

                if (isset($reqData['PilotUpdateForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $pilotUpdateForm->handleRequest($request);
                    if ($pilotUpdateForm->isValid()) {
                        $em->persist($pilot);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Pilot.edit.success', array(
                            '%pilot%' => $pilot->getMission()
                        )));

                        $this->traceEntity($clonePilot, $pilot);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($pilot);

                        $this->flashMsgSession('error', $this->translate('Pilot.edit.failure', array(
                            '%pilot%' => $pilot->getMission()
                        )));
                    }
                }

                $this->gvars['pilot'] = $pilot;
                $this->gvars['PilotUpdateForm'] = $pilotUpdateForm->createView();

                $this->gvars['pagetitle'] = $this->translate('pagetitle.pilot.edit', array(
                    '%pilot%' => $pilot->getMission()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.pilot.edit.txt', array(
                    '%pilot%' => $pilot->getMission()
                ));

                return $this->renderResponse('AcfAdminBundle:Pilot:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }

    protected function traceEntity(Pilot $clonePilot, Pilot $pilot)
    {
        $curUser = $this->getSecurityTokenStorage()
            ->getToken()
            ->getUser();
        $trace = new Trace();
        $trace->setActionId($pilot->getId());
        $trace->setActionType(Trace::AT_UPDATE);
        $trace->setUserId($curUser->getId());
        $trace->setCompanyId($pilot->getCompany()
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

        $trace->setActionEntity(Trace::AE_PILOT);
        $trace->setActionId2($pilot->getCompany()
            ->getId());
        $trace->setActionEntity2(Trace::AE_COMPANY);

        $msg = '';

        if ($clonePilot->getRef() != $pilot->getRef()) {
            $msg .= '<tr><td>' . $this->translate('Pilot.ref.label') . '</td><td>';
            if ($clonePilot->getRef() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePilot->getRef();
            }
            $msg .= '</td><td>';
            if ($pilot->getRef() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $pilot->getRef();
            }
            $msg .= '</td></tr>';
        }

        if ($clonePilot->getMission() != $pilot->getMission()) {
            $msg .= '<tr><td>' . $this->translate('Pilot.mission.label') . '</td><td>';
            if ($clonePilot->getMission() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePilot->getMission();
            }
            $msg .= '</td><td>';
            if ($pilot->getMission() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $pilot->getMission();
            }
            $msg .= '</td></tr>';
        }

        if ($clonePilot->getNatureMission() != $pilot->getNatureMission()) {
            $msg .= '<tr><td>' . $this->translate('Pilot.natureMission.label') . '</td><td>';
            if ($clonePilot->getNatureMission() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePilot->getNatureMission();
            }
            $msg .= '</td><td>';
            if ($pilot->getNatureMission() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $pilot->getNatureMission();
            }
            $msg .= '</td></tr>';
        }

        if ($clonePilot->getPrestataire() != $pilot->getPrestataire()) {
            $msg .= '<tr><td>' . $this->translate('Pilot.prestataire.label') . '</td><td>';
            if ($clonePilot->getPrestataire() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePilot->getPrestataire();
            }
            $msg .= '</td><td>';
            if ($pilot->getPrestataire() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $pilot->getPrestataire();
            }
            $msg .= '</td></tr>';
        }

        if ($clonePilot->getPinAnce() != $pilot->getPinAnce()) {
            $msg .= '<tr><td>' . $this->translate('Pilot.pinAnce.label') . '</td><td>';
            if ($clonePilot->getPinAnce() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePilot->getPinAnce();
            }
            $msg .= '</td><td>';
            if ($pilot->getPinAnce() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $pilot->getPinAnce();
            }
            $msg .= '</td></tr>';
        }

        if ($clonePilot->getExpirationAnce() != $pilot->getExpirationAnce()) {
            $msg .= '<tr><td>' . $this->translate('Pilot.expirationAnce.label') . '</td><td>';
            if ($clonePilot->getExpirationAnce() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePilot->getExpirationAnce();
            }
            $msg .= '</td><td>';
            if ($pilot->getExpirationAnce() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $pilot->getExpirationAnce();
            }
            $msg .= '</td></tr>';
        }

        if ($clonePilot->getMpImpots() != $pilot->getMpImpots()) {
            $msg .= '<tr><td>' . $this->translate('Pilot.mpImpots.label') . '</td><td>';
            if ($clonePilot->getMpImpots() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePilot->getMpImpots();
            }
            $msg .= '</td><td>';
            if ($pilot->getMpImpots() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $pilot->getMpImpots();
            }
            $msg .= '</td></tr>';
        }

        if ($clonePilot->getIdCnss() != $pilot->getIdCnss()) {
            $msg .= '<tr><td>' . $this->translate('Pilot.idCnss.label') . '</td><td>';
            if ($clonePilot->getIdCnss() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePilot->getIdCnss();
            }
            $msg .= '</td><td>';
            if ($pilot->getIdCnss() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $pilot->getIdCnss();
            }
            $msg .= '</td></tr>';
        }

        if ($clonePilot->getMpCnss() != $pilot->getMpCnss()) {
            $msg .= '<tr><td>' . $this->translate('Pilot.mpCnss.label') . '</td><td>';
            if ($clonePilot->getMpCnss() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePilot->getMpCnss();
            }
            $msg .= '</td><td>';
            if ($pilot->getMpCnss() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $pilot->getMpCnss();
            }
            $msg .= '</td></tr>';
        }

        if ($clonePilot->getRecetteFinance() != $pilot->getRecetteFinance()) {
            $msg .= '<tr><td>' . $this->translate('Pilot.recetteFinance.label') . '</td><td>';
            if ($clonePilot->getRecetteFinance() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePilot->getRecetteFinance();
            }
            $msg .= '</td><td>';
            if ($pilot->getRecetteFinance() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $pilot->getRecetteFinance();
            }
            $msg .= '</td></tr>';
        }

        if ($clonePilot->getNomCac() != $pilot->getNomCac()) {
            $msg .= '<tr><td>' . $this->translate('Pilot.nomCac.label') . '</td><td>';
            if ($clonePilot->getNomCac() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePilot->getNomCac();
            }
            $msg .= '</td><td>';
            if ($pilot->getNomCac() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $pilot->getNomCac();
            }
            $msg .= '</td></tr>';
        }

        if ($clonePilot->getDureeMandat() != $pilot->getDureeMandat()) {
            $msg .= '<tr><td>' . $this->translate('Pilot.dureeMandat.label') . '</td><td>';
            if ($clonePilot->getDureeMandat() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePilot->getDureeMandat();
            }
            $msg .= '</td><td>';
            if ($pilot->getDureeMandat() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $pilot->getDureeMandat();
            }
            $msg .= '</td></tr>';
        }

        if ($clonePilot->getNumMandat() != $pilot->getNumMandat()) {
            $msg .= '<tr><td>' . $this->translate('Pilot.numMandat.label') . '</td><td>';
            if ($clonePilot->getNumMandat() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePilot->getNumMandat();
            }
            $msg .= '</td><td>';
            if ($pilot->getNumMandat() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $pilot->getNumMandat();
            }
            $msg .= '</td></tr>';
        }

        if ($clonePilot->getRapportCac() != $pilot->getRapportCac()) {
            $msg .= '<tr><td>' . $this->translate('Pilot.rapportCac.label') . '</td><td>';
            if ($clonePilot->getRapportCac() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePilot->getRapportCac();
            }
            $msg .= '</td><td>';
            if ($pilot->getRapportCac() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $pilot->getRapportCac();
            }
            $msg .= '</td></tr>';
        }

        if ($clonePilot->getDeclEmpl() != $pilot->getDeclEmpl()) {
            $msg .= '<tr><td>' . $this->translate('Pilot.declEmpl.label') . '</td><td>';
            if ($clonePilot->getDeclEmpl() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePilot->getDeclEmpl();
            }
            $msg .= '</td><td>';
            if ($pilot->getDeclEmpl() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $pilot->getDeclEmpl();
            }
            $msg .= '</td></tr>';
        }

        if ($clonePilot->getIsDur() != $pilot->getIsDur()) {
            $msg .= '<tr><td>' . $this->translate('Pilot.isDur.label') . '</td><td>';
            if ($clonePilot->getIsDur() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePilot->getIsDur();
            }
            $msg .= '</td><td>';
            if ($pilot->getIsDur() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $pilot->getIsDur();
            }
            $msg .= '</td></tr>';
        }

        if ($clonePilot->getPvCa() != $pilot->getPvCa()) {
            $msg .= '<tr><td>' . $this->translate('Pilot.pvCa.label') . '</td><td>';
            if ($clonePilot->getPvCa() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePilot->getPvCa();
            }
            $msg .= '</td><td>';
            if ($pilot->getPvCa() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $pilot->getPvCa();
            }
            $msg .= '</td></tr>';
        }

        if ($clonePilot->getRapportGerance() != $pilot->getRapportGerance()) {
            $msg .= '<tr><td>' . $this->translate('Pilot.rapportGerance.label') . '</td><td>';
            if ($clonePilot->getRapportGerance() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePilot->getRapportGerance();
            }
            $msg .= '</td><td>';
            if ($pilot->getRapportGerance() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $pilot->getRapportGerance();
            }
            $msg .= '</td></tr>';
        }

        if ($clonePilot->getPvAgo() != $pilot->getPvAgo()) {
            $msg .= '<tr><td>' . $this->translate('Pilot.pvAgo.label') . '</td><td>';
            if ($clonePilot->getPvAgo() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePilot->getPvAgo();
            }
            $msg .= '</td><td>';
            if ($pilot->getPvAgo() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $pilot->getPvAgo();
            }
            $msg .= '</td></tr>';
        }

        if ($clonePilot->getPvAge() != $pilot->getPvAge()) {
            $msg .= '<tr><td>' . $this->translate('Pilot.pvAge.label') . '</td><td>';
            if ($clonePilot->getPvAge() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePilot->getPvAge();
            }
            $msg .= '</td><td>';
            if ($pilot->getPvAge() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $pilot->getPvAge();
            }
            $msg .= '</td></tr>';
        }

        if ($clonePilot->getLivresCotes() != $pilot->getLivresCotes()) {
            $msg .= '<tr><td>' . $this->translate('Pilot.livresCotes.label') . '</td><td>';
            if ($clonePilot->getLivresCotes() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePilot->getLivresCotes();
            }
            $msg .= '</td><td>';
            if ($pilot->getLivresCotes() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $pilot->getLivresCotes();
            }
            $msg .= '</td></tr>';
        }

        if ($clonePilot->getHonTeorAnn() != $pilot->getHonTeorAnn()) {
            $msg .= '<tr><td>' . $this->translate('Pilot.honTeorAnn.label') . '</td><td>';
            if ($clonePilot->getHonTeorAnn() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePilot->getHonTeorAnn();
            }
            $msg .= '</td><td>';
            if ($pilot->getHonTeorAnn() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $pilot->getHonTeorAnn();
            }
            $msg .= '</td></tr>';
        }

        if ($clonePilot->getModeFact() != $pilot->getModeFact()) {
            $msg .= '<tr><td>' . $this->translate('Pilot.modeFact.label') . '</td><td>';
            if ($clonePilot->getModeFact() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePilot->getModeFact();
            }
            $msg .= '</td><td>';
            if ($pilot->getModeFact() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $pilot->getModeFact();
            }
            $msg .= '</td></tr>';
        }

        if ($clonePilot->getNonFactMont() != $pilot->getNonFactMont()) {
            $msg .= '<tr><td>' . $this->translate('Pilot.nonFactMont.label') . '</td><td>';
            if ($clonePilot->getNonFactMont() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePilot->getNonFactMont();
            }
            $msg .= '</td><td>';
            if ($pilot->getNonFactMont() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $pilot->getNonFactMont();
            }
            $msg .= '</td></tr>';
        }

        if ($clonePilot->getNonFactDesc() != $pilot->getNonFactDesc()) {
            $msg .= '<tr><td>' . $this->translate('Pilot.nonFactDesc.label') . '</td><td>';
            if ($clonePilot->getNonFactDesc() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePilot->getNonFactDesc();
            }
            $msg .= '</td><td>';
            if ($pilot->getNonFactDesc() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $pilot->getNonFactDesc();
            }
            $msg .= '</td></tr>';
        }

        if ($clonePilot->getNonEncMont() != $pilot->getNonEncMont()) {
            $msg .= '<tr><td>' . $this->translate('Pilot.nonEncMont.label') . '</td><td>';
            if ($clonePilot->getNonEncMont() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePilot->getNonEncMont();
            }
            $msg .= '</td><td>';
            if ($pilot->getNonEncMont() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $pilot->getNonEncMont();
            }
            $msg .= '</td></tr>';
        }

        if ($clonePilot->getNonEncDesc() != $pilot->getNonEncDesc()) {
            $msg .= '<tr><td>' . $this->translate('Pilot.nonEncDesc.label') . '</td><td>';
            if ($clonePilot->getNonEncDesc() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePilot->getNonEncDesc();
            }
            $msg .= '</td><td>';
            if ($pilot->getNonEncDesc() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $pilot->getNonEncDesc();
            }
            $msg .= '</td></tr>';
        }

        if ($clonePilot->getCommentQuit() != $pilot->getCommentQuit()) {
            $msg .= '<tr><td>' . $this->translate('Pilot.commentQuit.label') . '</td><td>';
            if ($clonePilot->getCommentQuit() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePilot->getCommentQuit();
            }
            $msg .= '</td><td>';
            if ($pilot->getCommentQuit() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $pilot->getCommentQuit();
            }
            $msg .= '</td></tr>';
        }

        if ($clonePilot->getMqQuitImpots() != $pilot->getMqQuitImpots()) {
            $msg .= '<tr><td>' . $this->translate('Pilot.mqQuitImpots.label') . '</td><td>';
            if ($clonePilot->getMqQuitImpots() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePilot->getMqQuitImpots();
            }
            $msg .= '</td><td>';
            if ($pilot->getMqQuitImpots() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $pilot->getMqQuitImpots();
            }
            $msg .= '</td></tr>';
        }

        if ($clonePilot->getMqQuitCnss() != $pilot->getMqQuitCnss()) {
            $msg .= '<tr><td>' . $this->translate('Pilot.mqQuitCnss.label') . '</td><td>';
            if ($clonePilot->getMqQuitCnss() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePilot->getMqQuitCnss();
            }
            $msg .= '</td><td>';
            if ($pilot->getMqQuitCnss() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $pilot->getMqQuitCnss();
            }
            $msg .= '</td></tr>';
        }

        if ($clonePilot->getComments() != $pilot->getComments()) {
            $msg .= '<tr><td>' . $this->translate('Pilot.comments.label') . '</td><td>';
            if ($clonePilot->getComments() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $clonePilot->getComments();
            }
            $msg .= '</td><td>';
            if ($pilot->getComments() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $pilot->getComments();
            }
            $msg .= '</td></tr>';
        }

        if ($msg != '') {

            $msg = $tableBegin . $msg . $tableEnd;

            $trace->setMsg($this->translate('Pilot.traceEdit', array(
                '%pilot%' => $pilot->getRef(),
                '%company%' => $pilot->getCompany()
                    ->getCorporateName()
            )) . $msg);
            $trace->setDtCrea(new \DateTime('now'));
            $em = $this->getEntityManager();
            $em->persist($trace);
            $em->flush();
        }
    }
}
