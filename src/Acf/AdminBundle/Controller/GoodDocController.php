<?php
namespace Acf\AdminBundle\Controller;

use Acf\DataBundle\Entity\GoodDoc;
use Acf\AdminBundle\Form\GoodDoc\NewTForm as GoodDocNewTForm;
use Acf\AdminBundle\Form\GoodDoc\UpdateContentTForm as GoodDocUpdateContentTForm;
use Acf\AdminBundle\Form\GoodDoc\UpdateDescriptionTForm as GoodDocUpdateDescriptionTForm;
use Acf\AdminBundle\Form\GoodDoc\UpdateOriginalNameTForm as GoodDocUpdateOriginalNameTForm;
use Acf\AdminBundle\Form\GoodDoc\UpdateTitleTForm as GoodDocUpdateTitleTForm;
use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class GoodDocController extends BaseController
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
        $this->gvars['menu_active'] = 'goodDoc';
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }
        $em = $this->getEntityManager();
        $goodDocs = $em->getRepository('AcfDataBundle:GoodDoc')->getAll();
        $this->gvars['goodDocs'] = $goodDocs;

        $this->gvars['smenu_active'] = 'list';
        $this->gvars['pagetitle'] = $this->translate('pagetitle.goodDoc.list');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.goodDoc.list.txt');

        return $this->renderResponse('AcfAdminBundle:GoodDoc:list.html.twig', $this->gvars);
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addGetAction()
    {
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }
        $goodDoc = new GoodDoc();
        $goodDocNewForm = $this->createForm(GoodDocNewTForm::class, $goodDoc);
        $this->gvars['goodDoc'] = $goodDoc;
        $this->gvars['GoodDocNewForm'] = $goodDocNewForm->createView();

        $this->gvars['pagetitle'] = $this->translate('pagetitle.goodDoc.add');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.goodDoc.add.txt');
        $this->gvars['smenu_active'] = 'add';

        return $this->renderResponse('AcfAdminBundle:GoodDoc:add.html.twig', $this->gvars);
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addPostAction()
    {
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('_admin_homepage'));
        }
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            return $this->redirect($this->generateUrl('_admin_goodDoc_addGet'));
        }

        $goodDoc = new GoodDoc();
        $goodDocNewForm = $this->createForm(GoodDocNewTForm::class, $goodDoc);
        $this->gvars['goodDoc'] = $goodDoc;

        $request = $this->getRequest();
        $reqData = $request->request->all();

        if (isset($reqData['GoodDocNewForm'])) {
            $goodDocNewForm->handleRequest($request);
            if ($goodDocNewForm->isValid()) {
                $em = $this->getEntityManager();
                $goodDocFile = $goodDocNewForm['fileName']->getData();

                $goodDocDir = $this->getParameter('kernel.root_dir') . '/../web/res/goodDocs';

                $originalName = $goodDocFile->getClientOriginalName();
                $fileName = sha1(uniqid(mt_rand(), true)) . '.' . strtolower($goodDocFile->getClientOriginalExtension());
                $mimeType = $goodDocFile->getMimeType();
                $goodDocFile->move($goodDocDir, $fileName);

                $size = filesize($goodDocDir . '/' . $fileName);
                $md5 = md5_file($goodDocDir . '/' . $fileName);

                $goodDoc->setFileName($fileName);
                $goodDoc->setOriginalName($originalName);
                $goodDoc->setSize($size);
                $goodDoc->setMimeType($mimeType);
                $goodDoc->setMd5($md5);
                $em->persist($goodDoc);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('GoodDoc.add.success', array(
                    '%goodDoc%' => $goodDoc->getTitle()
                )));

                return $this->redirect($this->generateUrl('_admin_goodDoc_editGet', array(
                    'uid' => $goodDoc->getId()
                )));
            } else {
                $this->flashMsgSession('error', $this->translate('GoodDoc.add.failure'));
            }
        }
        $this->gvars['GoodDocNewForm'] = $goodDocNewForm->createView();

        $this->gvars['pagetitle'] = $this->translate('pagetitle.goodDoc.add');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.goodDoc.add.txt');
        $this->gvars['smenu_active'] = 'add';

        return $this->renderResponse('AcfAdminBundle:GoodDoc:add.html.twig', $this->gvars);
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
            $urlFrom = $this->generateUrl('_admin_goodDoc_list');
        }
        $em = $this->getEntityManager();
        try {
            $goodDoc = $em->getRepository('AcfDataBundle:GoodDoc')->find($uid);

            if (null == $goodDoc) {
                $this->flashMsgSession('warning', $this->translate('GoodDoc.delete.notfound'));
            } else {
                $em->remove($goodDoc);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('GoodDoc.delete.success', array(
                    '%goodDoc%' => $goodDoc->getTitle()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('GoodDoc.delete.failure'));
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
            $urlFrom = $this->generateUrl('_admin_goodDoc_list');
        }
        $em = $this->getEntityManager();
        try {
            $goodDoc = $em->getRepository('AcfDataBundle:GoodDoc')->find($uid);

            if (null == $goodDoc) {
                $logger = $this->getLogger();
                $logger->addError('Document inconnu');
                $this->flashMsgSession('warning', $this->translate('GoodDoc.download.notfound'));
            } else {
                $goodDocDir = $this->getParameter('kernel.root_dir') . '/../web/res/goodDocs';
                $fileName = $goodDoc->getFileName();

                try {
                    $dlFile = new File($goodDocDir . '/' . $fileName);
                    $response = new StreamedResponse(function () use ($dlFile) {
                        $handle = fopen($dlFile->getRealPath(), 'r');
                        while (!feof($handle)) {
                            $buffer = fread($handle, 1024);
                            echo $buffer;
                            flush();
                        }
                        fclose($handle);
                    });

                    $response->headers->set('Content-Type', $goodDoc->getMimeType());
                    $response->headers->set('Cache-Control', '');
                    $response->headers->set('Content-Length', $goodDoc->getSize());
                    $response->headers->set('Last-Modified', gmdate('D, d M Y H:i:s', $goodDoc->getDtUpdate()
                    ->getTimestamp()));
                    $fallback = $this->normalize($goodDoc->getTitle());

                    $contentDisposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $goodDoc->getOriginalName(), $fallback);
                    $response->headers->set('Content-Disposition', $contentDisposition);

                    return $response;
                } catch (FileNotFoundException $fnfex) {
                    $logger = $this->getLogger();
                    $logger->addError('Fichier introuvable ou autre erreur');
                    $logger->addError($fnfex->getMessage());
                    $this->flashMsgSession('error', $fnfex->getMessage());
                    $this->flashMsgSession('warning', $this->translate('GoodDoc.download.notfound'));
                }
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
            $this->flashMsgSession('error', $e->getMessage());
            $this->flashMsgSession('warning', $this->translate('GoodDoc.download.notfound'));
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
            $urlFrom = $this->generateUrl('_admin_goodDoc_list');
        }

        $em = $this->getEntityManager();
        try {
            $goodDoc = $em->getRepository('AcfDataBundle:GoodDoc')->find($uid);

            if (null == $goodDoc) {
                $this->flashMsgSession('warning', $this->translate('GoodDoc.edit.notfound'));
            } else {
                $goodDocUpdateDescriptionForm = $this->createForm(GoodDocUpdateDescriptionTForm::class, $goodDoc);
                $goodDocUpdateContentForm = $this->createForm(GoodDocUpdateContentTForm::class, $goodDoc);
                $goodDocUpdateOriginalNameForm = $this->createForm(GoodDocUpdateOriginalNameTForm::class, $goodDoc);
                $goodDocUpdateTitleForm = $this->createForm(GoodDocUpdateTitleTForm::class, $goodDoc);

                $this->gvars['goodDoc'] = $goodDoc;
                $this->gvars['GoodDocUpdateDescriptionForm'] = $goodDocUpdateDescriptionForm->createView();
                $this->gvars['GoodDocUpdateContentForm'] = $goodDocUpdateContentForm->createView();
                $this->gvars['GoodDocUpdateOriginalNameForm'] = $goodDocUpdateOriginalNameForm->createView();
                $this->gvars['GoodDocUpdateTitleForm'] = $goodDocUpdateTitleForm->createView();

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');

                $this->gvars['pagetitle'] = $this->translate('pagetitle.goodDoc.edit', array(
                    '%goodDoc%' => $goodDoc->getTitle()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.goodDoc.edit.txt', array(
                    '%goodDoc%' => $goodDoc->getTitle()
                ));

                return $this->renderResponse('AcfAdminBundle:GoodDoc:edit.html.twig', $this->gvars);
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
            return $this->redirect($this->generateUrl('_admin_goodDoc_list'));
        }

        $em = $this->getEntityManager();
        try {
            $goodDoc = $em->getRepository('AcfDataBundle:GoodDoc')->find($uid);

            if (null == $goodDoc) {
                $this->flashMsgSession('warning', $this->translate('GoodDoc.edit.notfound'));
            } else {
                $goodDocUpdateDescriptionForm = $this->createForm(GoodDocUpdateDescriptionTForm::class, $goodDoc);
                $goodDocUpdateContentForm = $this->createForm(GoodDocUpdateContentTForm::class, $goodDoc);
                $goodDocUpdateOriginalNameForm = $this->createForm(GoodDocUpdateOriginalNameTForm::class, $goodDoc);
                $goodDocUpdateTitleForm = $this->createForm(GoodDocUpdateTitleTForm::class, $goodDoc);

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                if (isset($reqData['GoodDocUpdateDescriptionForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $goodDocUpdateDescriptionForm->handleRequest($request);
                    if ($goodDocUpdateDescriptionForm->isValid()) {
                        $em->persist($goodDoc);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('GoodDoc.edit.success', array(
                            '%goodDoc%' => $goodDoc->getTitle()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($goodDoc);

                        $this->flashMsgSession('error', $this->translate('GoodDoc.edit.failure', array(
                            '%goodDoc%' => $goodDoc->getTitle()
                        )));
                    }
                } elseif (isset($reqData['GoodDocUpdateContentForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $goodDocUpdateContentForm->handleRequest($request);
                    if ($goodDocUpdateContentForm->isValid()) {

                        $goodDocFile = $goodDocUpdateContentForm['goodDoc']->getData();

                        $goodDocDir = $this->getParameter('kernel.root_dir') . '/../web/res/goodDocs';

                        $originalName = $goodDocFile->getClientOriginalName();
                        $fileName = sha1(uniqid(mt_rand(), true)) . '.' . strtolower($goodDocFile->getClientOriginalExtension());
                        $mimeType = $goodDocFile->getMimeType();
                        $goodDocFile->move($goodDocDir, $fileName);

                        $size = filesize($goodDocDir . '/' . $fileName);
                        $md5 = md5_file($goodDocDir . '/' . $fileName);

                        $goodDoc->setFileName($fileName);
                        $goodDoc->setOriginalName($originalName);
                        $goodDoc->setSize($size);
                        $goodDoc->setMimeType($mimeType);
                        $goodDoc->setMd5($md5);

                        $em->persist($goodDoc);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('GoodDoc.edit.success', array(
                            '%goodDoc%' => $goodDoc->getTitle()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($goodDoc);

                        $this->flashMsgSession('error', $this->translate('GoodDoc.add.failure'));
                    }
                } elseif (isset($reqData['GoodDocUpdateOriginalNameForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $goodDocUpdateOriginalNameForm->handleRequest($request);
                    if ($goodDocUpdateOriginalNameForm->isValid()) {
                        $em->persist($goodDoc);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('GoodDoc.edit.success', array(
                            '%goodDoc%' => $goodDoc->getTitle()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($goodDoc);

                        $this->flashMsgSession('error', $this->translate('GoodDoc.edit.failure', array(
                            '%goodDoc%' => $goodDoc->getTitle()
                        )));
                    }
                } elseif (isset($reqData['GoodDocUpdateTitleForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $goodDocUpdateTitleForm->handleRequest($request);
                    if ($goodDocUpdateTitleForm->isValid()) {
                        $em->persist($goodDoc);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('GoodDoc.edit.success', array(
                            '%goodDoc%' => $goodDoc->getTitle()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($goodDoc);

                        $this->flashMsgSession('error', $this->translate('GoodDoc.edit.failure', array(
                            '%goodDoc%' => $goodDoc->getTitle()
                        )));
                    }
                }

                $this->gvars['goodDoc'] = $goodDoc;
                $this->gvars['GoodDocUpdateDescriptionForm'] = $goodDocUpdateDescriptionForm->createView();
                $this->gvars['GoodDocUpdateContentForm'] = $goodDocUpdateContentForm->createView();
                $this->gvars['GoodDocUpdateOriginalNameForm'] = $goodDocUpdateOriginalNameForm->createView();
                $this->gvars['GoodDocUpdateTitleForm'] = $goodDocUpdateTitleForm->createView();

                $this->gvars['pagetitle'] = $this->translate('pagetitle.goodDoc.edit', array(
                    '%goodDoc%' => $goodDoc->getTitle()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.goodDoc.edit.txt', array(
                    '%goodDoc%' => $goodDoc->getTitle()
                ));

                return $this->renderResponse('AcfAdminBundle:GoodDoc:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }
}
