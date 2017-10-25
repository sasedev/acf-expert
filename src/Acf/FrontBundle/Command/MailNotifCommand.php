<?php
namespace Acf\FrontBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * MailNotifCommand
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class MailNotifCommand extends ContainerAwareCommand
{

    /**
     *
     * {@inheritdoc}
     * @see Command::configure()
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('acf:mailNotif')->setDescription('Sends Email Notifications To Userss.');
    }

    /**
     *
     * {@inheritdoc}
     * @see Command::execute()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $em = $container->get('doctrine.orm.default_entity_manager');
        $trans = $container->get('translator.default');
        $mailer = $container->get('mailer');
        $logger = $container->get('logger');
        // $transport = $container->get('swiftmailer.transport.real');
        $templating = $container->get('templating');

        $from = $container->getParameter('mail_from');
        $fromName = $container->getParameter('mail_from_name');
        $subject = $trans->trans('_mail.uservalidity.subject', array(), 'messages');

        $logger->addNotice('---------------------------------------------------------------------------------');
        $logger->addNotice('Début Envoie email notifications');
        $logger->addNotice('---------------------------------------------------------------------------------');

        $inSevenDays = new \DateTime();
        $inSevenDays->setTimestamp(strtotime("+7 day"));

        $sevenDaysUsers = $em->getRepository('AcfDataBundle:User')->getAllByLastValidity($inSevenDays);
        $sevenDaysUsersMailSent = 0;

        foreach ($sevenDaysUsers as $user) {
            try {
                $mvars = array();
                $message = \Swift_Message::newInstance();
                $message->setFrom($from, $fromName);
                $message->addTo($user->getEmail(), $user->getFullname());
                $message->setSubject($subject);
                $mvars['logo'] = $message->embed(\Swift_Image::fromPath($container->getParameter('kernel.root_dir') . '/../web/bundles/acfres/images/logo_acf.jpg'));
                $mvars['pendingDays'] = '7';
                $mvars['user'] = $user;
                $message->setBody($templating->render('AcfFrontBundle:User:sendmail.html.twig', $mvars), 'text/html');
                $mailer->send($message);
                echo "Envoie notif fin de validité de compte 7j pour : " . $user->getFullname() . ' ' . $user->getEmail() . "\r\n";
                $sevenDaysUsersMailSent++;
            } catch (\Exception $e) {
                $logger->addNotice("Impossible d'envoyer un email de notification fin de validité de compte 7j pour " . $user->getFullname() . ' ' . $user->getEmail() . ' ' . $e->getMessage());
            }
        }

        $inThreeDays = new \DateTime();
        $inThreeDays->setTimestamp(strtotime("+3 day"));

        $threeDaysUsers = $em->getRepository('AcfDataBundle:User')->getAllByLastValidity($inThreeDays);
        $threeDaysUsersMailSent = 0;

        foreach ($threeDaysUsers as $user) {
            try {
                $mvars = array();
                $message = \Swift_Message::newInstance();
                $message->setFrom($from, $fromName);
                $message->addTo($user->getEmail(), $user->getFullname());
                $message->setSubject($subject);
                $mvars['logo'] = $message->embed(\Swift_Image::fromPath($container->getParameter('kernel.root_dir') . '/../web/bundles/acfres/images/logo_acf.jpg'));
                $mvars['pendingDays'] = '3';
                $mvars['user'] = $user;
                $message->setBody($templating->render('AcfFrontBundle:User:sendmail.html.twig', $mvars), 'text/html');
                $mailer->send($message);
                echo "Envoie notif fin de validité de compte 3j pour : " . $user->getFullname() . ' ' . $user->getEmail() . "\r\n";
                $threeDaysUsersMailSent++;
            } catch (\Exception $e) {
                $logger->addNotice("Impossible d'envoyer un email de notification fin de validité de compte 3j pour " . $user->getFullname() . ' ' . $user->getEmail() . ' ' . $e->getMessage());
            }
        }

        $logger->addNotice('---------------------------------------------------------------------------------');
        $logger->addNotice($sevenDaysUsersMailSent . ' / ' . count($sevenDaysUsers) . ' Notifications 7j');
        $logger->addNotice('---------------------------------------------------------------------------------');
        $logger->addNotice($threeDaysUsersMailSent . ' / ' . count($threeDaysUsers) . ' Notifications 3j');
        $logger->addNotice('---------------------------------------------------------------------------------');

        echo $sevenDaysUsersMailSent . ' / ' . count($sevenDaysUsers) . ' Notifications 7j' . "\r\n";
        echo $threeDaysUsersMailSent . ' / ' . count($threeDaysUsers) . ' Notifications 3j' . "\r\n";
    }
}

