<?php
namespace Acf\AdminBundle\Controller;

use Acf\AdminBundle\Form\LiasseDoc\UpdateContentTForm as LiasseDocUpdateContentTForm;
use Acf\AdminBundle\Form\LiasseDoc\UpdateDescriptionTForm as LiasseDocUpdateDescriptionTForm;
use Acf\AdminBundle\Form\LiasseDoc\UpdateOriginalNameTForm as LiasseDocUpdateOriginalNameTForm;
use Acf\AdminBundle\Form\LiasseDoc\UpdateTitleTForm as LiasseDocUpdateTitleTForm;
use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class LiasseDocController extends BaseController
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
        $this->gvars['menu_active'] = 'liasseFolder';
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
            $urlFrom = $this->generateUrl('_admin_liasseDoc_list');
        }
        $em = $this->getEntityManager();
        try {
            $liasseDoc = $em->getRepository('AcfDataBundle:LiasseDoc')->find($uid);

            if (null == $liasseDoc) {
                $this->flashMsgSession('warning', $this->translate('LiasseDoc.delete.notfound'));
            } else {
                $em->remove($liasseDoc);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('LiasseDoc.delete.success', array(
                    '%liasseDoc%' => $liasseDoc->getTitle()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('LiasseDoc.delete.failure'));
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
            $urlFrom = $this->generateUrl('_admin_liasseDoc_list');
        }
        $em = $this->getEntityManager();
        try {
            $liasseDoc = $em->getRepository('AcfDataBundle:LiasseDoc')->find($uid);

            if (null == $liasseDoc) {
                $logger = $this->getLogger();
                $logger->addError('Document inconnu');
                $this->flashMsgSession('warning', $this->translate('LiasseDoc.download.notfound'));
            } else {
                $liasseDocDir = $this->getParameter('kernel.root_dir') . '/../web/res/liasseDocs';
                $fileName = $liasseDoc->getFileName();

                try {
                    $dlFile = new File($liasseDocDir . '/' . $fileName);
                    $response = new StreamedResponse(function () use ($dlFile) {
                        $handle = fopen($dlFile->getRealPath(), 'r');
                        while (!feof($handle)) {
                            $buffer = fread($handle, 1024);
                            echo $buffer;
                            flush();
                        }
                        fclose($handle);
                    });

                    $response->headers->set('Content-Type', $liasseDoc->getMimeType());
                    $response->headers->set('Cache-Control', '');
                    $response->headers->set('Content-Length', $liasseDoc->getSize());
                    $response->headers->set('Last-Modified', gmdate('D, d M Y H:i:s', $liasseDoc->getDtUpdate()
                        ->getTimestamp()));
                    $fallback = $this->normalizeString($this->normalize($liasseDoc->getTitle()));

                    $contentDisposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $liasseDoc->getOriginalName(), $fallback);
                    $response->headers->set('Content-Disposition', $contentDisposition);

                    return $response;
                } catch (FileNotFoundException $fnfex) {
                    $logger = $this->getLogger();
                    $logger->addError('Fichier introuvable ou autre erreur');
                    $logger->addError($fnfex->getMessage());
                    $this->flashMsgSession('error', $fnfex->getMessage());
                    $this->flashMsgSession('warning', $this->translate('LiasseDoc.download.notfound'));
                }
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
            $this->flashMsgSession('error', $e->getMessage());
            $this->flashMsgSession('warning', $this->translate('LiasseDoc.download.notfound'));
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
            $urlFrom = $this->generateUrl('_admin_liasseDoc_list');
        }

        $em = $this->getEntityManager();
        try {
            $liasseDoc = $em->getRepository('AcfDataBundle:LiasseDoc')->find($uid);

            if (null == $liasseDoc) {
                $this->flashMsgSession('warning', $this->translate('LiasseDoc.edit.notfound'));
            } else {
                $liasseDocUpdateDescriptionForm = $this->createForm(LiasseDocUpdateDescriptionTForm::class, $liasseDoc);
                $liasseDocUpdateContentForm = $this->createForm(LiasseDocUpdateContentTForm::class, $liasseDoc);
                $liasseDocUpdateOriginalNameForm = $this->createForm(LiasseDocUpdateOriginalNameTForm::class, $liasseDoc);
                $liasseDocUpdateTitleForm = $this->createForm(LiasseDocUpdateTitleTForm::class, $liasseDoc);

                $this->gvars['liasseDoc'] = $liasseDoc;
                $this->gvars['LiasseDocUpdateDescriptionForm'] = $liasseDocUpdateDescriptionForm->createView();
                $this->gvars['LiasseDocUpdateContentForm'] = $liasseDocUpdateContentForm->createView();
                $this->gvars['LiasseDocUpdateOriginalNameForm'] = $liasseDocUpdateOriginalNameForm->createView();
                $this->gvars['LiasseDocUpdateTitleForm'] = $liasseDocUpdateTitleForm->createView();

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');

                $this->gvars['pagetitle'] = $this->translate('pagetitle.liasseDoc.edit', array(
                    '%liasseDoc%' => $liasseDoc->getTitle()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.liasseDoc.edit.txt', array(
                    '%liasseDoc%' => $liasseDoc->getTitle()
                ));

                return $this->renderResponse('AcfAdminBundle:LiasseDoc:edit.html.twig', $this->gvars);
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
            return $this->redirect($this->generateUrl('_admin_liasseDoc_list'));
        }

        $em = $this->getEntityManager();
        try {
            $liasseDoc = $em->getRepository('AcfDataBundle:LiasseDoc')->find($uid);

            if (null == $liasseDoc) {
                $this->flashMsgSession('warning', $this->translate('LiasseDoc.edit.notfound'));
            } else {
                $liasseDocUpdateDescriptionForm = $this->createForm(LiasseDocUpdateDescriptionTForm::class, $liasseDoc);
                $liasseDocUpdateContentForm = $this->createForm(LiasseDocUpdateContentTForm::class, $liasseDoc);
                $liasseDocUpdateOriginalNameForm = $this->createForm(LiasseDocUpdateOriginalNameTForm::class, $liasseDoc);
                $liasseDocUpdateTitleForm = $this->createForm(LiasseDocUpdateTitleTForm::class, $liasseDoc);

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                if (isset($reqData['LiasseDocUpdateDescriptionForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $liasseDocUpdateDescriptionForm->handleRequest($request);
                    if ($liasseDocUpdateDescriptionForm->isValid()) {
                        $em->persist($liasseDoc);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('LiasseDoc.edit.success', array(
                            '%liasseDoc%' => $liasseDoc->getTitle()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($liasseDoc);

                        $this->flashMsgSession('error', $this->translate('LiasseDoc.edit.failure', array(
                            '%liasseDoc%' => $liasseDoc->getTitle()
                        )));
                    }
                } elseif (isset($reqData['LiasseDocUpdateContentForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $liasseDocUpdateContentForm->handleRequest($request);
                    if ($liasseDocUpdateContentForm->isValid()) {

                        $liasseDocFile = $liasseDocUpdateContentForm['liasseDoc']->getData();

                        $liasseDocDir = $this->getParameter('kernel.root_dir') . '/../web/res/liasseDocs';

                        $originalName = $liasseDocFile->getClientOriginalName();
                        $fileName = sha1(uniqid(mt_rand(), true)) . '.' . strtolower($liasseDocFile->getClientOriginalExtension());
                        $mimeType = $liasseDocFile->getMimeType();
                        $liasseDocFile->move($liasseDocDir, $fileName);

                        $size = filesize($liasseDocDir . '/' . $fileName);
                        $md5 = md5_file($liasseDocDir . '/' . $fileName);

                        $liasseDoc->setFileName($fileName);
                        $liasseDoc->setOriginalName($originalName);
                        $liasseDoc->setSize($size);
                        $liasseDoc->setMimeType($mimeType);
                        $liasseDoc->setMd5($md5);

                        $em->persist($liasseDoc);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('LiasseDoc.edit.success', array(
                            '%liasseDoc%' => $liasseDoc->getTitle()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($liasseDoc);

                        $this->flashMsgSession('error', $this->translate('LiasseDoc.add.failure'));
                    }
                } elseif (isset($reqData['LiasseDocUpdateOriginalNameForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $liasseDocUpdateOriginalNameForm->handleRequest($request);
                    if ($liasseDocUpdateOriginalNameForm->isValid()) {
                        $em->persist($liasseDoc);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('LiasseDoc.edit.success', array(
                            '%liasseDoc%' => $liasseDoc->getTitle()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($liasseDoc);

                        $this->flashMsgSession('error', $this->translate('LiasseDoc.edit.failure', array(
                            '%liasseDoc%' => $liasseDoc->getTitle()
                        )));
                    }
                } elseif (isset($reqData['LiasseDocUpdateTitleForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $liasseDocUpdateTitleForm->handleRequest($request);
                    if ($liasseDocUpdateTitleForm->isValid()) {
                        $em->persist($liasseDoc);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('LiasseDoc.edit.success', array(
                            '%liasseDoc%' => $liasseDoc->getTitle()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($liasseDoc);

                        $this->flashMsgSession('error', $this->translate('LiasseDoc.edit.failure', array(
                            '%liasseDoc%' => $liasseDoc->getTitle()
                        )));
                    }
                }

                $this->gvars['liasseDoc'] = $liasseDoc;
                $this->gvars['LiasseDocUpdateDescriptionForm'] = $liasseDocUpdateDescriptionForm->createView();
                $this->gvars['LiasseDocUpdateContentForm'] = $liasseDocUpdateContentForm->createView();
                $this->gvars['LiasseDocUpdateOriginalNameForm'] = $liasseDocUpdateOriginalNameForm->createView();
                $this->gvars['LiasseDocUpdateTitleForm'] = $liasseDocUpdateTitleForm->createView();

                $this->gvars['pagetitle'] = $this->translate('pagetitle.liasseDoc.edit', array(
                    '%liasseDoc%' => $liasseDoc->getTitle()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.liasseDoc.edit.txt', array(
                    '%liasseDoc%' => $liasseDoc->getTitle()
                ));

                return $this->renderResponse('AcfAdminBundle:LiasseDoc:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }

    private static function normalizeString($str = '')
    {
        $str = strip_tags($str);
        $str = preg_replace('/[\r\n\t ]+/', ' ', $str);
        $str = preg_replace('/[\"\*\/\:\<\>\?\'\|]+/', ' ', $str);
        $str = strtolower($str);
        $str = html_entity_decode($str, ENT_QUOTES, "utf-8");
        $str = htmlentities($str, ENT_QUOTES, "utf-8");
        $str = preg_replace("/(&)([a-z])([a-z]+;)/i", '$2', $str);
        $str = str_replace(' ', '-', $str);
        $str = rawurlencode($str);
        $str = str_replace('%', '-', $str);
        return $str;
    }
}
