<?php
namespace Acf\FrontBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class DefaultController extends BaseController
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
        $this->gvars['menu_active'] = 'home';
    }

    /**
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->renderResponse('AcfFrontBundle:Default:index.html.twig', $this->gvars);
    }

    /**
     *
     * @return Response
     */
    public function appelsdoffresAction()
    {
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.ao.index.txt');
        $this->gvars['pagetitle'] = $this->translate('pagetitle.ao.index');
        return $this->renderResponse('AcfFrontBundle:Appelsdoffres:index.html.twig', $this->gvars);
    }

    /**
     *
     * @return Response
     */
    public function contactAction()
    {
        $request = $this->getRequest();
        $email = $request->request->get('email');
        $name = $request->request->get('name');
        $subject = $request->request->get('subject');
        $message = $request->request->get('message');
        $mvars = array();
        $mvars['msg'] = $message;
        $from = $this->getParameter('mail_from');
        $fromName = $this->getParameter('mail_from_name');
        $subject = $this->translate('_mail.contact.subject', array(), 'messages') . $subject;

        try {
            $admins = $this->getParameter('mailtos');

            $message = \Swift_Message::newInstance()->setFrom($from, $fromName);
            foreach ($admins as $admin) {
                $message->addTo($admin['email'], $admin['name']);
            }
            $message->setReplyTo($email, $name);
            $message->setSubject($subject);
            $mvars['logo'] = $message->embed(\Swift_Image::fromPath($this->getParameter('kernel.root_dir') . '/../web/bundles/acfres/images/logo_acf.jpg'));
            $message->setBody($this->renderView('AcfFrontBundle:Mail:contact.html.twig', $mvars), 'text/html');

            $this->sendmail($message);
        } catch (\Exception $e) {
            // ne rien faire
        }
        $response = new Response();
        $response->setContent('Votre Message a été envoyé et sera traité dans les plus bref délais');
        return $response;
    }

    /**
     *
     * @return Response
     */
    public function joinAction()
    {
        $request = $this->getRequest();
        $civ = $request->request->get('civ');
        $fname = $request->request->get('fname');
        $lname = $request->request->get('lname');
        $email = $request->request->get('email');
        $subject = $request->request->get('subject');
        $message = $request->request->get('message');
        $cv = $request->files->get('cv');
        $motiv = $request->files->get('motiv');
        $mvars = array();
        $mvars['msg'] = $message;
        $from = $this->getParameter('mail_from');
        $fromName = $this->getParameter('mail_from_name');
        $subject = $this->translate('_mail.join.subject', array(), 'messages') . $subject;

        try {
            $admins = $this->getParameter('mailtos');

            $message = \Swift_Message::newInstance()->setFrom($from, $fromName);
            foreach ($admins as $admin) {
                $message->addTo($admin['email'], $admin['name']);
            }
            $message->setReplyTo($email, $civ . ' ' . $fname . ' ' . $lname);
            $message->setSubject($subject);
            $mvars['logo'] = $message->embed(\Swift_Image::fromPath($this->getParameter('kernel.root_dir') . '/../web/bundles/acfres/images/logo_acf.jpg'));
            $message->setBody($this->renderView('AcfFrontBundle:Mail:contact.html.twig', $mvars), 'text/html');
            if (null != $cv) {
                $message->attach(\Swift_Attachment::fromPath($cv)->setFilename($cv->getClientOriginalName()));
            }

            if (null != $motiv) {
                $message->attach(\Swift_Attachment::fromPath($motiv)->setFilename($motiv->getClientOriginalName()));
            }

            $this->sendmail($message);
        } catch (\Exception $e) {
            // ne rien faire
        }
        $response = new Response();
        $response->setContent('Votre Message a été envoyé et sera traité dans les plus bref délais');
        return $response;
    }
}
