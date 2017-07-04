<?php
namespace Acf\FrontBundle\Controller;

use Acf\FrontBundle\Form\Devis\NewTForm as DevisNewTForm;
use Sasedev\Commons\SharedBundle\Controller\BaseController;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class DevisController extends BaseController
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $devisNewForm = $this->createForm(DevisNewTForm::class);
        $this->gvars['DevisNewForm'] = $devisNewForm->createView();

        return $this->renderResponse('AcfFrontBundle:Default:devis.html.twig', $this->gvars);
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function postAction()
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_front_devis');
        }
        $devisNewForm = $this->createForm(DevisNewTForm::class);

        $request = $this->getRequest();
        $reqData = $request->request->all();

        if (isset($reqData['DevisNewForm'])) {
            $devisNewForm->handleRequest($request);
            if ($devisNewForm->isValid()) {

                $from = $this->getParameter('mail_from');
                $fromName = $this->getParameter('mail_from_name');
                $subject = $this->translate('_mail.devis.subject', array(), 'messages');

                $mvars = array();
                $mvars['firstname'] = $devisNewForm['firstname']->getData();
                $mvars['lastname'] = $devisNewForm['lastname']->getData();
                $mvars['email'] = $devisNewForm['email']->getData();
                $mvars['phone'] = $devisNewForm['phone']->getData();
                $mvars['entreprise'] = $devisNewForm['entreprise']->getData();
                $mvars['entrepriseForm'] = $devisNewForm['entrepriseForm']->getData();
                $mvars['activity'] = $devisNewForm['activity']->getData();
                $mvars['dtActivation'] = $devisNewForm['dtActivation']->getData();
                $mvars['address'] = $devisNewForm['address']->getData();
                $mvars['zipCode'] = $devisNewForm['zipCode']->getData();
                $mvars['town'] = $devisNewForm['town']->getData();
                $mvars['caYear'] = $devisNewForm['caYear']->getData();
                $mvars['nbrInvoicesBuy'] = $devisNewForm['nbrInvoicesBuy']->getData();
                $mvars['nbrInvoicesSale'] = $devisNewForm['nbrInvoicesSale']->getData();
                $mvars['nbrSalaries'] = $devisNewForm['nbrSalaries']->getData();
                $mvars['hasexpert'] = $devisNewForm['hasexpert']->getData();
                $mvars['otherInfos'] = $devisNewForm['otherInfos']->getData();

                try {
                    $admins = $this->getParameter('mailtos');

                    $message = \Swift_Message::newInstance()->setFrom($from, $fromName);
                    foreach ($admins as $admin) {
                        $message->addTo($admin['email'], $admin['name']);
                        $message->setReplyTo($mvars['email'], $mvars['lastname'] . ' ' . $mvars['firstname']);
                    }
                    $message->setSubject($subject);
                    $mvars['logo'] = $message->embed(\Swift_Image::fromPath($this->getParameter('kernel.root_dir') . '/../web/bundles/acfres/images/logo_acf.jpg'));
                    $message->setBody($this->renderView('AcfFrontBundle:Mail:devis.html.twig', $mvars), 'text/html');

                    $this->sendmail($message);
                } catch (\Exception $e) {
                    $logger = $this->getLogger();
                    $logger->addError($e->getMessage());
                }
                $this->flashMsgSession('success', $this->translate('_front.devis.success'));

                return $this->redirect($this->generateUrl('_front_devis'));
            } else {
                $this->gvars['DevisNewForm'] = $devisNewForm->createView();

                $this->flashMsgSession('error', $this->translate('_front.devis.error'));

                return $this->renderResponse('AcfFrontBundle:Default:devis.html.twig', $this->gvars);
            }
        }

        return $this->redirect($urlFrom);
    }
}