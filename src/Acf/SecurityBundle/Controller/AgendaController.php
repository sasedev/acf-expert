<?php
namespace Acf\SecurityBundle\Controller;

use Acf\DataBundle\Entity\Agenda;
use Acf\SecurityBundle\Form\EventAddTForm;
use Acf\SecurityBundle\Form\EventEditTForm;
use Acf\SecurityBundle\Form\EventEditUsersTForm;
use Acf\SecurityBundle\Form\EventEditAdminsTForm;
use Acf\SecurityBundle\Form\ChooseDateTForm;
use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class AgendaController extends BaseController
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
        $this->gvars['menu_active'] = 'agenda';
    }

    /**
     *
     * @return Response
     */
    public function indexAction()
    {
        $sc = $this->getSecurityTokenStorage();
        $user = $sc->getToken()->getUser();

        $currentYear = intval(date('Y'));
        $currentWeek = intval(date('W'));
        $currentMonth = intval(date('n'));

        if ($currentMonth == 1 && ($currentWeek == 52 || $currentWeek == 53)) {
            $currentYear--;
        }
        if ($currentMonth == 12 && ($currentWeek == 1 || $currentWeek == 0)) {
            $currentYear++;
        }

        $prevWeek = $currentWeek - 1;
        $prevYear = $currentYear;
        $nextWeek = $currentWeek + 1;
        $nextYear = $currentYear;
        if ($prevWeek < 1) {
            $prevYear--;
            $prevWeek = $this->getIsoWeeksInYear($prevYear);
        }
        if ($nextWeek > $this->getIsoWeeksInYear($nextYear)) {
            $nextYear++;
            $nextWeek = 1;
        }

        $countNextYearWeeks = $this->getIsoWeeksInYear($nextYear);
        $countCurrentYearWeeks = $this->getIsoWeeksInYear($currentYear);
        $countPrevYearWeeks = $this->getIsoWeeksInYear($prevYear);

        $em = $this->getEntityManager();
        $events = $em->getRepository('AcfDataBundle:Agenda')->getAllByYearWeekUser($currentYear, $currentWeek, $user);

        $weekDays = $this->daysInWeek($currentWeek, $currentYear);
        $this->gvars['weekdays'] = $weekDays;

        $chooseDateForm = $this->createForm(ChooseDateTForm::class, null, array(
            'date' => new \DateTime('now')
        ));
        $this->gvars['ChooseDateForm'] = $chooseDateForm->createView();

        $this->gvars['nextYear'] = $nextYear;
        $this->gvars['nextWeek'] = $nextWeek;
        $this->gvars['currentYear'] = $currentYear;
        $this->gvars['currentWeek'] = $currentWeek;
        $this->gvars['prevYear'] = $prevYear;
        $this->gvars['prevWeek'] = $prevWeek;
        $this->gvars['agendaEvents'] = $events;

        $this->gvars['countPrevYearWeeks'] = $countPrevYearWeeks;
        $this->gvars['countCurrentYearWeeks'] = $countCurrentYearWeeks;
        $this->gvars['countNextYearWeeks'] = $countNextYearWeeks;

        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.agenda.txt');
        $this->gvars['pagetitle'] = $this->translate('pagetitle.agenda');

        $event = new Agenda();
        $eventAddForm = $this->createForm(EventAddTForm::class, $event);
        $this->gvars['event'] = $event;
        $this->gvars['EventAddForm'] = $eventAddForm->createView();

        return $this->renderResponse('AcfSecurityBundle:Agenda:index.html.twig', $this->gvars);
    }

    /**
     *
     * @param int $year
     * @param int $week
     * @return RedirectResponse|Response
     */
    public function planningAction($year, $week)
    {
        if (null == $year || $year < 1) {
            $currentYear = intval(date('Y'));
            $currentWeek = intval(date('W'));

            return $this->redirect($this->generateUrl('_security_agenda_planning', array(
                'year' => $currentYear,
                'week' => $currentWeek
            )));
        } elseif (null == $week) {
            $currentYear = $year;
            $currentWeek = date('W');

            return $this->redirect($this->generateUrl('_security_agenda_planning', array(
                'year' => $currentYear,
                'week' => $currentWeek
            )));
        } elseif ($week < 1) {
            $currentYear = $year;
            $currentWeek = 1;

            return $this->redirect($this->generateUrl('_security_agenda_planning', array(
                'year' => $currentYear,
                'week' => $currentWeek
            )));
        } elseif ($week > $this->getIsoWeeksInYear($year)) {
            $currentYear = $year;
            $currentWeek = $this->getIsoWeeksInYear($year);

            return $this->redirect($this->generateUrl('_security_agenda_planning', array(
                'year' => $currentYear,
                'week' => $currentWeek
            )));
        } else {
            $currentYear = $year;
            $currentWeek = $week;
        }

        $sc = $this->getSecurityTokenStorage();
        $user = $sc->getToken()->getUser();

        $prevWeek = $currentWeek - 1;
        $prevYear = $currentYear;
        $nextWeek = $currentWeek + 1;
        $nextYear = $currentYear;
        if ($prevWeek < 1) {
            $prevYear--;
            $prevWeek = $this->getIsoWeeksInYear($prevYear);
        }
        if ($nextWeek > $this->getIsoWeeksInYear($nextYear)) {
            $nextYear++;
            $nextWeek = 1;
        }

        $countNextYearWeeks = $this->getIsoWeeksInYear($nextYear);
        $countCurrentYearWeeks = $this->getIsoWeeksInYear($currentYear);
        $countPrevYearWeeks = $this->getIsoWeeksInYear($prevYear);

        $em = $this->getEntityManager();
        $events = $em->getRepository('AcfDataBundle:Agenda')->getAllByYearWeekUser($currentYear, $currentWeek, $user);

        $weekDays = $this->daysInWeek($currentWeek, $currentYear);
        $this->gvars['weekdays'] = $weekDays;

        $chooseDate = new \DateTime();
        $chooseDate->setISODate($currentYear, $currentWeek);
        $chooseDateForm = $this->createForm(ChooseDateTForm::class, null, array(
            'date' => new \DateTime($chooseDate->format('Y-M-d'))
        ));
        $this->gvars['ChooseDateForm'] = $chooseDateForm->createView();

        $this->gvars['nextYear'] = $nextYear;
        $this->gvars['nextWeek'] = $nextWeek;
        $this->gvars['currentYear'] = $currentYear;
        $this->gvars['currentWeek'] = $currentWeek;
        $this->gvars['prevYear'] = $prevYear;
        $this->gvars['prevWeek'] = $prevWeek;
        $this->gvars['agendaEvents'] = $events;

        $this->gvars['countPrevYearWeeks'] = $countPrevYearWeeks;
        $this->gvars['countCurrentYearWeeks'] = $countCurrentYearWeeks;
        $this->gvars['countNextYearWeeks'] = $countNextYearWeeks;

        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.agenda.txt');
        $this->gvars['pagetitle'] = $this->translate('pagetitle.agenda');

        $event = new Agenda();
        $eventAddForm = $this->createForm(EventAddTForm::class, $event);
        $this->gvars['event'] = $event;
        $this->gvars['EventAddForm'] = $eventAddForm->createView();

        return $this->renderResponse('AcfSecurityBundle:Agenda:index.html.twig', $this->gvars);
    }

    /**
     *
     * @return RedirectResponse
     */
    public function ajaxGotoDateAction()
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            return $this->redirect($this->generateUrl('_security_agenda_index'));
        }

        $chooseDateForm = $this->createForm(ChooseDateTForm::class, null, array(
            'date' => new \DateTime('now')
        ));
        $this->gvars['ChooseDateForm'] = $chooseDateForm->createView();

        $request = $this->getRequest();
        $reqData = $request->request->all();
        if (isset($reqData['ChooseDateForm'])) {
            $chooseDateForm->handleRequest($request);
            if ($chooseDateForm->isValid()) {
                $gotoDateData = $chooseDateForm['date']->getData();
                $currentYear = \intval($gotoDateData->format('Y'));
                $currentWeek = \intval($gotoDateData->format('W'));
                $currentMonth = \intval($gotoDateData->format('n'));

                if ($currentMonth == 1 && ($currentWeek == 52 || $currentWeek == 53)) {
                    $currentYear--;
                }
                if ($currentMonth == 12 && ($currentWeek == 1 || $currentWeek == 0)) {
                    $currentYear++;
                }

                return $this->redirect($this->generateUrl('_security_agenda_planning', array(
                    'year' => $currentYear,
                    'week' => $currentWeek
                )));
            }
        }

        return $this->redirect($urlFrom);
    }

    /**
     * Ajax Add Agenda
     *
     * @return JsonResponse|RedirectResponse|Response
     */
    public function ajaxAddAction()
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            return $this->redirect($this->generateUrl('_security_agenda_index'));
        }
        $sc = $this->getSecurityTokenStorage();
        $user = $sc->getToken()->getUser();

        $event = new Agenda();
        $event->setUser($user);
        $eventAddForm = $this->createForm(EventAddTForm::class, $event);

        $request = $this->getRequest();
        $reqData = $request->request->all();
        if (isset($reqData['EventAddForm']) && $request->isXmlHttpRequest()) { //
            $eventAddForm->handleRequest($request);
            if ($eventAddForm->isValid()) {

                $em = $this->getEntityManager();
                $response = new Response();
                try {
                    $em->persist($event);
                    $em->flush();

                    $jsonResponse = new JsonResponse();
                    $jsonResponse->setData(array(
                        'id' => $event->getId(),
                        'title' => $event->getTitle(),
                        'backgroundColor' => $event->getBackgroundColor(),
                        'borderColor' => $event->getBorderColor(),
                        'textColor' => $event->getTextColor(),
                        'dtStart' => $event->getEvStart(),
                        'dtEnd' => $event->getEvEnd(),
                        'msg' => $this->translate('Agenda.add.success.txt', array(
                            '%agenda%' => $event->getTitle()
                        ))
                    ));

                    return $jsonResponse;
                } catch (\Exception $e) {
                    $logger = $this->getLogger();
                    $logger->addError($e->getLine() . ' ' . $e->getFile() . ' ' . $e->getMessage());

                    $response->setStatusCode(415);
                    $response->setContent($this->translate('Agenda.add.failure'));

                    return $response;
                }
            } else {
                $response->setStatusCode(415);
                $response->setContent($this->translate('Agenda.add.failure'));
            }
        }

        return $this->redirect($urlFrom);
    }

    /**
     * Ajax Edit Agenda
     *
     * @return RedirectResponse|Response
     */
    public function ajaxEditAction()
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            return $this->redirect($this->generateUrl('_security_agenda_index'));
        }

        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {

            $id = $request->request->get('evId');
            $dtStart = new \DateTime();
            $dtStart->setTimestamp($request->request->get('start'));
            $dtEnd = new \DateTime();
            $dtEnd->setTimestamp($request->request->get('end'));

            $em = $this->getEntityManager();
            $response = new Response();
            try {
                $event = $em->getRepository('AcfDataBundle:Agenda')->findOneBy(array(
                    'id' => $id
                ));

                // verifier si le cours existe
                if (null == $event) {

                    $response->setStatusCode(404);
                    $response->setContent($this->translate('Agenda.edit.notfound'));

                    return $response;
                }

                $event->setDtStart($dtStart);
                $event->setDtEnd($dtEnd);

                $em->persist($event);
                $em->flush();

                $jsonResponse = new JsonResponse();
                $jsonResponse->setData(array(
                    'id' => $event->getId(),
                    'title' => $event->getTitle(),
                    'backgroundColor' => $event->getBackgroundColor(),
                    'borderColor' => $event->getBorderColor(),
                    'textColor' => $event->getTextColor(),
                    'dtStart' => $event->getEvStart(),
                    'dtEnd' => $event->getEvEnd(),
                    'msg' => $this->translate('Agenda.edit.success.txt', array(
                        '%agenda%' => $event->getTitle()
                    ))
                ));

                return $jsonResponse;
            } catch (\Exception $e) {
                $logger = $this->getLogger();
                $logger->addError($e->getLine() . ' ' . $e->getFile() . ' ' . $e->getMessage());

                $response->setStatusCode(415);
                $response->setContent($this->translate('Agenda.edit.failure'));

                return $response;
            }
        }

        return $this->redirect($urlFrom);
    }

    /**
     * Ajax Delete Agenda
     *
     * @return RedirectResponse|Response|JsonResponse
     */
    public function ajaxDeleteAction()
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            return $this->redirect($this->generateUrl('_security_agenda_index'));
        }

        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {

            $id = $request->request->get('evId');

            $em = $this->getEntityManager();
            $response = new Response();

            try {
                $event = $em->getRepository('AcfDataBundle:Agenda')->findOneBy(array(
                    'id' => $id
                ));

                if (null == $event) {

                    $response->setStatusCode(404);
                    $response->setContent($this->translate('Agenda.delete.notfound'));

                    return $response;
                }

                $em->remove($event);
                $em->flush();

                $jsonResponse = new JsonResponse();
                $jsonResponse->setData(array(
                    'id' => $event->getId(),
                    'title' => $event->getTitle(),
                    'backgroundColor' => $event->getBackgroundColor(),
                    'borderColor' => $event->getBorderColor(),
                    'textColor' => $event->getTextColor(),
                    'dtStart' => $event->getEvStart(),
                    'dtEnd' => $event->getEvEnd(),
                    'msg' => $this->translate('Agenda.delete.success.txt', array(
                        '%agenda%' => $event->getTitle()
                    ))
                ));

                return $jsonResponse;
            } catch (\Exception $e) {
                $logger = $this->getLogger();
                $logger->addError($e->getLine() . ' ' . $e->getFile() . ' ' . $e->getMessage());

                $response->setStatusCode(415);
                $response->setContent($this->translate('Agenda.delete.failure'));

                return $response;
            }
        }

        return $this->redirect($urlFrom);
    }

    /**
     *
     * @param string $uid
     * @return Response|RedirectResponse
     */
    public function eventAction($uid)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_security_agenda_index');
        }
        $em = $this->getEntityManager();

        $sc = $this->getSecurityTokenStorage();
        $user = $sc->getToken()->getUser();
        try {
            $event = $em->getRepository('AcfDataBundle:Agenda')->find($uid);

            if (null == $event) {
                $this->flashMsgSession('warning', $this->translate('Agenda.edit.notfound'));
            } elseif ($event->getUser()->getId() != $user->getId() && !$event->isUserInAgenda($user)) {
                $this->flashMsgSession('warning', $this->translate('Agenda.edit.notfound'));
            } else {
                $eventEditForm = $this->createForm(EventEditTForm::class, $event);
                if ($this->hasRole('ROLE_ADMIN')) {
                    $eventEditUsersForm = $this->createForm(EventEditUsersTForm::class, $event);
                }
                $eventEditAdminsForm = $this->createForm(EventEditAdminsTForm::class, $event);

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');

                $this->gvars['event'] = $event;
                $this->gvars['EventEditForm'] = $eventEditForm->createView();
                if ($this->hasRole('ROLE_ADMIN')) {
                    $this->gvars['EventEditUsersForm'] = $eventEditUsersForm->createView();
                }
                $this->gvars['EventEditAdminsForm'] = $eventEditAdminsForm->createView();

                $this->gvars['pagetitle'] = $this->translate('pagetitle.agenda.edit', array(
                    '%agenda%' => $event->getTitle()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.agenda.edit.txt', array(
                    '%agenda%' => $event->getTitle()
                ));

                return $this->renderResponse('AcfSecurityBundle:Agenda:edit.html.twig', $this->gvars);
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
     * @return RedirectResponse|Response
     */
    public function eventEditAction($uid)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_security_agenda_index');
        }
        $em = $this->getEntityManager();

        $sc = $this->getSecurityTokenStorage();
        $user = $sc->getToken()->getUser();
        try {
            $event = $em->getRepository('AcfDataBundle:Agenda')->find($uid);

            if (null == $event) {
                $this->flashMsgSession('warning', $this->translate('Agenda.edit.notfound'));
            } elseif ($event->getUser()->getId() != $user->getId()) {
                $this->flashMsgSession('warning', $this->translate('Agenda.edit.notfound'));
            } else {
                $eventEditForm = $this->createForm(EventEditTForm::class, $event);
                if ($this->hasRole('ROLE_ADMIN')) {
                    $eventEditUsersForm = $this->createForm(EventEditUsersTForm::class, $event);
                }
                $eventEditAdminsForm = $this->createForm(EventEditAdminsTForm::class, $event);

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();
                if (isset($reqData['EventEditForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $eventEditForm->handleRequest($request);
                    if ($eventEditForm->isValid()) {
                        $em->persist($event);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Agenda.edit.success.txt', array(
                            '%agenda%' => $event->getTitle()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($event);

                        $this->flashMsgSession('error', $this->translate('Agenda.edit.failure'));
                    }
                } elseif (isset($reqData['EventEditUsersForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $eventEditUsersForm->handleRequest($request);
                    if ($eventEditUsersForm->isValid()) {
                        $em->persist($event);
                        $em->flush();

                        foreach ($event->getUsers() as $sharedUser) {
                            $mvars = array();
                            $mvars['user'] = $sharedUser;
                            $mvars['iuser'] = $user;
                            $mvars['event'] = $event;
                            $from = $this->getParameter('mail_from');
                            $fromName = $this->getParameter('mail_from_name');
                            $subject = $this->translate('_mail.newEvent.subject', array(), 'messages');

                            $message = \Swift_Message::newInstance();
                            $message->setFrom($from, $fromName);
                            $message->setTo($sharedUser->getEmail(), $sharedUser->getFullname());
                            $message->setSubject($subject);
                            $mvars['logo'] = $message->embed(\Swift_Image::fromPath($this->getParameter('kernel.root_dir') . '/../web/bundles/acfres/images/logo_acf.jpg'));
                            $message->setBody($this->renderView('AcfSecurityBundle:Agenda:mail.html.twig', $mvars), 'text/html');

                            $this->sendmail($message);
                        }

                        $this->flashMsgSession('success', $this->translate('Agenda.edit.success.txt', array(
                            '%agenda%' => $event->getTitle()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($event);

                        $this->flashMsgSession('error', $this->translate('Agenda.edit.failure'));
                    }
                } elseif (isset($reqData['EventEditAdminsForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $eventEditAdminsForm->handleRequest($request);
                    if ($eventEditAdminsForm->isValid()) {
                        $em->persist($event);
                        $em->flush();

                        foreach ($event->getUsers() as $sharedUser) {
                            $mvars = array();
                            $mvars['user'] = $sharedUser;
                            $mvars['iuser'] = $user;
                            $mvars['event'] = $event;
                            $from = $this->getParameter('mail_from');
                            $fromName = $this->getParameter('mail_from_name');
                            $subject = $this->translate('_mail.newEvent.subject', array(), 'messages');

                            $message = \Swift_Message::newInstance();
                            $message->setFrom($from, $fromName);
                            $message->setTo($sharedUser->getEmail(), $sharedUser->getFullname());
                            $message->setSubject($subject);
                            $mvars['logo'] = $message->embed(\Swift_Image::fromPath($this->getParameter('kernel.root_dir') . '/../web/bundles/acfres/images/logo_acf.jpg'));
                            $message->setBody($this->renderView('AcfSecurityBundle:Agenda:mail.html.twig', $mvars), 'text/html');

                            $this->sendmail($message);
                        }

                        $this->flashMsgSession('success', $this->translate('Agenda.edit.success.txt', array(
                            '%agenda%' => $event->getTitle()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($event);

                        $this->flashMsgSession('error', $this->translate('Agenda.edit.failure'));
                    }
                }

                $this->gvars['event'] = $event;
                $this->gvars['EventEditForm'] = $eventEditForm->createView();
                if ($this->hasRole('ROLE_ADMIN')) {
                    $this->gvars['EventEditUsersForm'] = $eventEditUsersForm->createView();
                }
                $this->gvars['EventEditAdminsForm'] = $eventEditAdminsForm->createView();

                $this->gvars['pagetitle'] = $this->translate('pagetitle.agenda.edit', array(
                    '%agenda%' => $event->getTitle()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.agenda.edit.txt', array(
                    '%agenda%' => $event->getTitle()
                ));

                return $this->renderResponse('AcfSecurityBundle:Agenda:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }

    /**
     * Get count of week in a year
     *
     * @param integer $year
     *
     * @return integer
     */
    public function getIsoWeeksInYear($year)
    {
        $date = new \DateTime();
        $date->setISODate($year, 53);

        return ($date->format('W') === '53' ? 53 : 52);
    }

    /**
     * Get array of days in selected week and year
     *
     * @param integer $weekNum
     * @param integer $year
     *
     * @return multitype:\DateTime
     */
    public function daysInWeek($weekNum, $year = null)
    {
        $result = array();
        $datetime = new \DateTime();
        if (null == $year) {
            $year = (int) $datetime->format('Y');
        }
        $datetime->setISODate($year, $weekNum, 1);
        $interval = new \DateInterval('P1D');
        $week = new \DatePeriod($datetime, $interval, 6);

        foreach ($week as $day) {
            $curDay = new \DateTime($day->format('Y-m-d'));
            $result[] = $curDay;
        }

        return $result;
    }

    /**
     * Get array of days in selected month and year
     *
     * @param integer $monthNum
     * @param integer $year
     *
     * @return multitype:\DateTime
     */
    public function daysInMonth($monthNum, $year = null)
    {
        $datetime = new \DateTime();
        if (null == $year) {
            $year = (int) $datetime->format('Y');
        }
        $countDaysInmonth = cal_days_in_month(CAL_GREGORIAN, $monthNum, $year);

        $result = array();
        $datetime->setDate($year, $monthNum, 1);
        $interval = new \DateInterval('P1D');
        $month = new \DatePeriod($datetime, $interval, $countDaysInmonth);

        foreach ($month as $day) {
            $curDay = new \DateTime($day->format('Y-m-d'));
            $result[] = $curDay;
        }

        return $result;
    }
}
