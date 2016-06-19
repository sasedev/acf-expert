<?php
namespace Acf\AdminBundle\Controller;

use Acf\DataBundle\Entity\BiDoc;
use Acf\AdminBundle\Form\BiDoc\UpdateContentTForm as BiDocUpdateContentTForm;
use Acf\AdminBundle\Form\BiDoc\UpdateDescriptionTForm as BiDocUpdateDescriptionTForm;
use Acf\AdminBundle\Form\BiDoc\UpdateOriginalNameTForm as BiDocUpdateOriginalNameTForm;
use Acf\AdminBundle\Form\BiDoc\UpdateTitleTForm as BiDocUpdateTitleTForm;
use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class BiDocController extends BaseController
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
        $this->gvars['menu_active'] = 'biFolder';
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
            $urlFrom = $this->generateUrl('_admin_biDoc_list');
        }
        $em = $this->getEntityManager();
        try {
            $biDoc = $em->getRepository('AcfDataBundle:BiDoc')->find($uid);
            
            if (null == $biDoc) {
                $this->flashMsgSession('warning', $this->translate('BiDoc.delete.notfound'));
            } else {
                $em->remove($biDoc);
                $em->flush();
                
                $this->flashMsgSession('success', $this->translate('BiDoc.delete.success', array(
                    '%biDoc%' => $biDoc->getTitle()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
            
            $this->flashMsgSession('error', $this->translate('BiDoc.delete.failure'));
        }
        
        return $this->redirect($urlFrom);
    }

    /**
     *
     * @param string $uid
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadAction($uid)
    {
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_biDoc_list');
        }
        $em = $this->getEntityManager();
        try {
            $biDoc = $em->getRepository('AcfDataBundle:BiDoc')->find($uid);
            
            if (null == $biDoc) {
                $logger = $this->getLogger();
                $logger->addError('Document inconnu');
                $this->flashMsgSession('warning', $this->translate('BiDoc.download.notfound'));
            } else {
                $biDocDir = $this->getParameter('kernel.root_dir') . '/../web/res/biDocs';
                $fileName = $biDoc->getFileName();
                
                try {
                    $dlFile = new File($biDocDir . '/' . $fileName);
                    $response = new StreamedResponse(function () use ($dlFile) {
                        $handle = fopen($dlFile->getRealPath(), 'r');
                        while (!feof($handle)) {
                            $buffer = fread($handle, 1024);
                            echo $buffer;
                            flush();
                        }
                        fclose($handle);
                    });
                    
                    $response->headers->set('Content-Type', $biDoc->getMimeType());
                    $response->headers->set('Cache-Control', '');
                    $response->headers->set('Content-Length', $biDoc->getSize());
                    $response->headers->set('Last-Modified', gmdate('D, d M Y H:i:s', $biDoc->getDtUpdate()->getTimestamp()));
                    $fallback = $this->normalize($biDoc->getTitle());
                    
                    $contentDisposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $biDoc->getOriginalName(), $fallback);
                    $response->headers->set('Content-Disposition', $contentDisposition);
                    
                    return $response;
                } catch (FileNotFoundException $fnfex) {
                    $logger = $this->getLogger();
                    $logger->addError('Fichier introuvable ou autre erreur');
                    $logger->addError($fnfex->getMessage());
                    $this->flashMsgSession('error', $fnfex->getMessage());
                    $this->flashMsgSession('warning', $this->translate('BiDoc.download.notfound'));
                }
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
            $this->flashMsgSession('error', $e->getMessage());
            $this->flashMsgSession('warning', $this->translate('BiDoc.download.notfound'));
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
            $urlFrom = $this->generateUrl('_admin_biDoc_list');
        }
        
        $em = $this->getEntityManager();
        try {
            $biDoc = $em->getRepository('AcfDataBundle:BiDoc')->find($uid);
            
            if (null == $biDoc) {
                $this->flashMsgSession('warning', $this->translate('BiDoc.edit.notfound'));
            } else {
                $biDocUpdateDescriptionForm = $this->createForm(BiDocUpdateDescriptionTForm::class, $biDoc);
                $biDocUpdateContentForm = $this->createForm(BiDocUpdateContentTForm::class, $biDoc);
                $biDocUpdateOriginalNameForm = $this->createForm(BiDocUpdateOriginalNameTForm::class, $biDoc);
                $biDocUpdateTitleForm = $this->createForm(BiDocUpdateTitleTForm::class, $biDoc);
                
                $this->gvars['biDoc'] = $biDoc;
                $this->gvars['BiDocUpdateDescriptionForm'] = $biDocUpdateDescriptionForm->createView();
                $this->gvars['BiDocUpdateContentForm'] = $biDocUpdateContentForm->createView();
                $this->gvars['BiDocUpdateOriginalNameForm'] = $biDocUpdateOriginalNameForm->createView();
                $this->gvars['BiDocUpdateTitleForm'] = $biDocUpdateTitleForm->createView();
                
                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');
                
                $this->gvars['pagetitle'] = $this->translate('pagetitle.biDoc.edit', array(
                    '%biDoc%' => $biDoc->getTitle()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.biDoc.edit.txt', array(
                    '%biDoc%' => $biDoc->getTitle()
                ));
                
                return $this->renderResponse('AcfAdminBundle:BiDoc:edit.html.twig', $this->gvars);
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
            return $this->redirect($this->generateUrl('_admin_biDoc_list'));
        }
        
        $em = $this->getEntityManager();
        try {
            $biDoc = $em->getRepository('AcfDataBundle:BiDoc')->find($uid);
            
            if (null == $biDoc) {
                $this->flashMsgSession('warning', $this->translate('BiDoc.edit.notfound'));
            } else {
                $biDocUpdateDescriptionForm = $this->createForm(BiDocUpdateDescriptionTForm::class, $biDoc);
                $biDocUpdateContentForm = $this->createForm(BiDocUpdateContentTForm::class, $biDoc);
                $biDocUpdateOriginalNameForm = $this->createForm(BiDocUpdateOriginalNameTForm::class, $biDoc);
                $biDocUpdateTitleForm = $this->createForm(BiDocUpdateTitleTForm::class, $biDoc);
                
                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');
                
                $request = $this->getRequest();
                $reqData = $request->request->all();
                
                if (isset($reqData['BiDocUpdateDescriptionForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $biDocUpdateDescriptionForm->handleRequest($request);
                    if ($biDocUpdateDescriptionForm->isValid()) {
                        $em->persist($biDoc);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('BiDoc.edit.success', array(
                            '%biDoc%' => $biDoc->getTitle()
                        )));
                        
                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($biDoc);
                        
                        $this->flashMsgSession('error', $this->translate('BiDoc.edit.failure', array(
                            '%biDoc%' => $biDoc->getTitle()
                        )));
                    }
                } elseif (isset($reqData['BiDocUpdateContentForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $biDocUpdateContentForm->handleRequest($request);
                    if ($biDocUpdateContentForm->isValid()) {
                        
                        $biDocFile = $biDocUpdateContentForm['biDoc']->getData();
                        
                        $biDocDir = $this->getParameter('kernel.root_dir') . '/../web/res/biDocs';
                        
                        $originalName = $biDocFile->getClientOriginalName();
                        $fileName = sha1(uniqid(mt_rand(), true)) . '.' . strtolower($biDocFile->getClientOriginalExtension());
                        $mimeType = $biDocFile->getMimeType();
                        $biDocFile->move($biDocDir, $fileName);
                        
                        $size = filesize($biDocDir . '/' . $fileName);
                        $md5 = md5_file($biDocDir . '/' . $fileName);
                        
                        $biDoc->setFileName($fileName);
                        $biDoc->setOriginalName($originalName);
                        $biDoc->setSize($size);
                        $biDoc->setMimeType($mimeType);
                        $biDoc->setMd5($md5);
                        
                        $em->persist($biDoc);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('BiDoc.edit.success', array(
                            '%biDoc%' => $biDoc->getTitle()
                        )));
                        
                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($biDoc);
                        
                        $this->flashMsgSession('error', $this->translate('BiDoc.add.failure'));
                    }
                } elseif (isset($reqData['BiDocUpdateOriginalNameForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $biDocUpdateOriginalNameForm->handleRequest($request);
                    if ($biDocUpdateOriginalNameForm->isValid()) {
                        $em->persist($biDoc);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('BiDoc.edit.success', array(
                            '%biDoc%' => $biDoc->getTitle()
                        )));
                        
                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($biDoc);
                        
                        $this->flashMsgSession('error', $this->translate('BiDoc.edit.failure', array(
                            '%biDoc%' => $biDoc->getTitle()
                        )));
                    }
                } elseif (isset($reqData['BiDocUpdateTitleForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $biDocUpdateTitleForm->handleRequest($request);
                    if ($biDocUpdateTitleForm->isValid()) {
                        $em->persist($biDoc);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('BiDoc.edit.success', array(
                            '%biDoc%' => $biDoc->getTitle()
                        )));
                        
                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($biDoc);
                        
                        $this->flashMsgSession('error', $this->translate('BiDoc.edit.failure', array(
                            '%biDoc%' => $biDoc->getTitle()
                        )));
                    }
                }
                
                $this->gvars['biDoc'] = $biDoc;
                $this->gvars['BiDocUpdateDescriptionForm'] = $biDocUpdateDescriptionForm->createView();
                $this->gvars['BiDocUpdateContentForm'] = $biDocUpdateContentForm->createView();
                $this->gvars['BiDocUpdateOriginalNameForm'] = $biDocUpdateOriginalNameForm->createView();
                $this->gvars['BiDocUpdateTitleForm'] = $biDocUpdateTitleForm->createView();
                
                $this->gvars['pagetitle'] = $this->translate('pagetitle.biDoc.edit', array(
                    '%biDoc%' => $biDoc->getTitle()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.biDoc.edit.txt', array(
                    '%biDoc%' => $biDoc->getTitle()
                ));
                
                return $this->renderResponse('AcfAdminBundle:BiDoc:edit.html.twig', $this->gvars);
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
    public function mailAction($uid)
    {
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_admin_biDoc_list');
        }
        
        $em = $this->getEntityManager();
        try {
            $biDoc = $em->getRepository('AcfDataBundle:BiDoc')->find($uid);
            
            if (null == $biDoc) {
                $this->flashMsgSession('warning', $this->translate('BiDoc.edit.notfound'));
            } else {
                $acfInfoRole = $em->getRepository('AcfDataBundle:Role')->findOneBy(array(
                    'name' => 'ROLE_CLIENT2'
                ));
                $from = $this->getParameter('mail_from');
                $fromName = $this->getParameter('mail_from_name');
                $subject = $this->translate('_mail.newbiDoc.subject', array(
                    '%biDoc%' => $biDoc->getTitle()
                ), 'messages');
                $i = 0;
                foreach ($acfInfoRole->getUsers() as $user) {
                    $i++;
                    $mvars = array();
                    $mvars['biDoc'] = $biDoc;
                    $mvars['user'] = $user;
                    $message = \Swift_Message::newInstance();
                    $message->setFrom($from, $fromName);
                    $message->setTo($user->getEmail(), $user->getFullname());
                    $message->setSubject($subject);
                    $message->setBody($this->renderView('AcfAdminBundle:BiDoc:mail.html.twig', $mvars), 'text/html');
                    $this->sendmail($message);
                }
                $this->flashMsgSession('success', $this->translate('BiDoc.mail.success', array(
                    '%sendmail%' => $i
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }
        
        return $this->redirect($urlFrom);
    }
}
