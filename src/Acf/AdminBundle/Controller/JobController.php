<?php
namespace Acf\AdminBundle\Controller;

use Acf\DataBundle\Entity\Job;
use Acf\AdminBundle\Form\Job\NewTForm as JobNewTForm;
use Acf\AdminBundle\Form\Job\UpdateTForm as JobUpdateTForm;
use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\Trace;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class JobController extends BaseController
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
        $this->gvars['menu_active'] = 'job';
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $em = $this->getEntityManager();
        $jobs = $em->getRepository('AcfDataBundle:Job')->getAll();
        $this->gvars['jobs'] = $jobs;

        $this->gvars['smenu_active'] = 'list';
        $this->gvars['pagetitle'] = $this->translate('pagetitle.job.list');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.job.list.txt');

        return $this->renderResponse('AcfAdminBundle:Job:list.html.twig', $this->gvars);
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addGetAction()
    {
        $job = new Job();
        $jobNewForm = $this->createForm(JobNewTForm::class, $job);
        $this->gvars['job'] = $job;
        $this->gvars['JobNewForm'] = $jobNewForm->createView();

        $this->gvars['pagetitle'] = $this->translate('pagetitle.job.add');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.job.add.txt');
        $this->gvars['smenu_active'] = 'add';

        return $this->renderResponse('AcfAdminBundle:Job:add.html.twig', $this->gvars);
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addPostAction()
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            return $this->redirect($this->generateUrl('_admin_job_addGet'));
        }

        $job = new Job();
        $jobNewForm = $this->createForm(JobNewTForm::class, $job);

        $request = $this->getRequest();
        $reqData = $request->request->all();

        if (isset($reqData['JobNewForm'])) {
            $jobNewForm->handleRequest($request);
            if ($jobNewForm->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($job);
                $em->flush();
                $this->flashMsgSession('success', $this->translate('Job.add.success', array(
                    '%job%' => $job->getLabel()
                )));

                return $this->redirect($this->generateUrl('_admin_job_editGet', array(
                    'id' => $job->getId()
                )));
            } else {
                $this->flashMsgSession('error', $this->translate('Job.add.failure'));
            }
        }
        $this->gvars['job'] = $job;
        $this->gvars['JobNewForm'] = $jobNewForm->createView();

        $this->gvars['pagetitle'] = $this->translate('pagetitle.job.add');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.job.add.txt');
        $this->gvars['smenu_active'] = 'add';

        return $this->renderResponse('AcfAdminBundle:Job:add.html.twig', $this->gvars);
    }

    /**
     *
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($id)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_job_list');
        }
        $em = $this->getEntityManager();
        try {
            $job = $em->getRepository('AcfDataBundle:Job')->find($id);

            if (null == $job) {
                $this->flashMsgSession('warning', $this->translate('Job.delete.notfound'));
            } else {
                $em->remove($job);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('Job.delete.success', array(
                    '%job%' => $job->getLabel()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('Job.delete.failure'));
        }

        return $this->redirect($urlFrom);
    }

    /**
     *
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editGetAction($id)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_job_list');
        }

        $em = $this->getEntityManager();
        try {
            $job = $em->getRepository('AcfDataBundle:Job')->find($id);

            if (null == $job) {
                $this->flashMsgSession('warning', $this->translate('Job.edit.notfound'));
            } else {
                $traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($job->getId(), Trace::AE_JOB);
                $this->gvars['traces'] = array_reverse($traces);
                $jobUpdateForm = $this->createForm(JobUpdateTForm::class, $job);

                $this->gvars['job'] = $job;
                $this->gvars['JobUpdateForm'] = $jobUpdateForm->createView();

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');

                $this->gvars['pagetitle'] = $this->translate('pagetitle.job.edit', array(
                    '%job%' => $job->getLabel()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.job.edit.txt', array(
                    '%job%' => $job->getLabel()
                ));

                return $this->renderResponse('AcfAdminBundle:Job:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }

    /**
     *
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editPostAction($id)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            return $this->redirect($this->generateUrl('_admin_job_list'));
        }

        $em = $this->getEntityManager();
        try {
            $job = $em->getRepository('AcfDataBundle:Job')->find($id);

            if (null == $job) {
                $this->flashMsgSession('warning', $this->translate('Job.edit.notfound'));
            } else {
                $traces = $em->getRepository('AcfDataBundle:Trace')->getAllByEntityId($job->getId(), Trace::AE_JOB);
                $this->gvars['traces'] = array_reverse($traces);
                $jobUpdateForm = $this->createForm(JobUpdateTForm::class, $job);

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                $cloneJob = clone $job;

                if (isset($reqData['JobUpdateForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $jobUpdateForm->handleRequest($request);
                    if ($jobUpdateForm->isValid()) {
                        $em->persist($job);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Job.edit.success', array(
                            '%job%' => $job->getLabel()
                        )));

                        $this->traceEntity($cloneJob, $job);

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($job);

                        $this->flashMsgSession('error', $this->translate('Job.edit.failure', array(
                            '%job%' => $job->getLabel()
                        )));
                    }
                }

                $this->gvars['job'] = $job;
                $this->gvars['JobUpdateForm'] = $jobUpdateForm->createView();

                $this->gvars['pagetitle'] = $this->translate('pagetitle.job.edit', array(
                    '%job%' => $job->getLabel()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.job.edit.txt', array(
                    '%job%' => $job->getLabel()
                ));

                return $this->renderResponse('AcfAdminBundle:Job:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }

    protected function traceEntity(Job $cloneJob, Job $job)
    {
        $curUser = $this->getSecurityTokenStorage()
            ->getToken()
            ->getUser();
        $trace = new Trace();
        $trace->setActionId($job->getId());
        $trace->setActionType(Trace::AT_UPDATE);
        $trace->setUserId($curUser->getId());
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

        $trace->setActionEntity(Trace::AE_JOB);

        $msg = '';

        if ($cloneJob->getLabel() != $job->getLabel()) {
            $msg .= '<tr><td>' . $this->translate('Job.label.label') . '</td><td>';
            if ($cloneJob->getLabel() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $cloneJob->getLabel();
            }
            $msg .= '</td><td>';
            if ($job->getLabel() == null) {
                $msg .= '<span class="label label-warning">' . $this->translate('_NA') . '</span>';
            } else {
                $msg .= $job->getLabel();
            }
            $msg .= '</td></tr>';
        }

        if ($msg != '') {

            $msg = $tableBegin . $msg . $tableEnd;

            $trace->setMsg($this->translate('Job.traceEdit', array(
                '%job%' => $job->getLabel()
            )) . $msg);
            $trace->setDtCrea(new \DateTime('now'));
            $em = $this->getEntityManager();
            $em->persist($trace);
            $em->flush();
        }
    }
}