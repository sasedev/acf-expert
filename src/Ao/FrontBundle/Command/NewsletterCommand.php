<?php
namespace Ao\FrontBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * MailNotifCommand
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class NewsletterCommand extends ContainerAwareCommand
{

    /**
     *
     * {@inheritdoc}
     * @see Command::configure()
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('ao:newsletter')->setDescription('Sends Email NewsLetter To Users.');
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
        $now = new \DateTime();
        $subject = $trans->trans('_mail.aonewletter.subject', array(), 'messages') . ' ';
        $subject .= $now->format('Y-m-d');

        $newsletterUsers = $em->getRepository('AcfDataBundle:User')->getAllAoNewsletter();

        $aoSubCategs = $em->getRepository('AcfDataBundle:AoSubCateg')->getAll();
        $subcategs = array();
        foreach ($aoSubCategs as $aoSubcateg) {
            $newAoCallfortenders = $em->getRepository('AcfDataBundle:AoCallfortender')->getAllNewsletter($aoSubcateg);
            if (\count($newAoCallfortenders) != 0) {
                $subcategs[$aoSubcateg->getId()] = $newAoCallfortenders;
            }

            $logger->addNotice(\count($newAoCallfortenders) . ' Nouveaux AO trouvés pour ' . $aoSubcateg->getRef());
        }

        $logger->addNotice('---------------------------------------------------------------------------------');
        $logger->addNotice('Début Envoie email newsletter AO');
        $logger->addNotice('---------------------------------------------------------------------------------');

        $newsletterUsersMailSent = 0;

        foreach ($newsletterUsers as $user) {
            try {
                $mvars = array();
                $message = \Swift_Message::newInstance();
                $message->setFrom($from, $fromName);
                $message->addTo($user->getEmail(), $user->getFullname());
                $message->setSubject($subject);
                $mvars['logo'] = $message->embed(\Swift_Image::fromPath($container->getParameter('kernel.root_dir') . '/../web/bundles/acfres/images/logo_ao.jpg'));
                $mvars['user'] = $user;

                $subcategsToSend = array();
                $userSendmail = false;
                foreach ($user->getSubcategs() as $userSubcateg) {
                    foreach ($subcategs as $subcategkey => $subcategval) {
                        if ($subcategkey == $userSubcateg->getId() && \count($subcategval) != 0) {
                            $userSendmail = true;
                            $subcategsToSendVal = array();
                            $subcategsToSendVal['title'] = $userSubcateg->getTitle();
                            $subcategsToSendVal['content'] = $subcategval;
                            $subcategsToSend[] = $subcategsToSendVal;
                        }
                    }
                }

                if ($userSendmail) {
                    $mvars['subcategs'] = $subcategsToSend;
                    $message->setBody($templating->render('AoFrontBundle:Newsletter:sendmail.html.twig', $mvars), 'text/html');
                    $mailer->send($message);
                    echo "Envoie notif fin de newsletter AO pour : " . $user->getFullname() . ' ' . $user->getEmail() . "\r\n";
                    $newsletterUsersMailSent++;
                }
            } catch (\Exception $e) {
                $logger->addNotice("Impossible d'envoyer un email de newsletter AO pour " . $user->getFullname() . ' ' . $user->getEmail() . ' ' . $e->getMessage());
            }
        }

        $logger->addNotice('---------------------------------------------------------------------------------');
        $logger->addNotice($newsletterUsersMailSent . ' / ' . count($newsletterUsers) . ' newsletter AO');
        $logger->addNotice('---------------------------------------------------------------------------------');

        echo $newsletterUsersMailSent . ' / ' . count($newsletterUsers) . ' newsletter AO' . "\r\n";
    }
}

