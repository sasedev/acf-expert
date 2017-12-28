<?php
namespace AoVe\AdminBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use AoVe\AdminBundle\Form\AoSubCateg\UpdateTitleTForm as AoSubCategUpdateTitleTForm;
use AoVe\AdminBundle\Form\AoSubCateg\UpdateRefTForm as AoSubCategUpdateRefTForm;
use AoVe\AdminBundle\Form\AoSubCateg\UpdateCategTForm as AoSubCategUpdateCategTForm;
use AoVe\AdminBundle\Form\AoSubCateg\UpdatePriorityTForm as AoSubCategUpdatePriorityTForm;
use AoVe\AdminBundle\Form\AoCallfortender\NewTForm as AoCallfortenderNewTForm;
use Acf\DataBundle\Entity\AoCallfortender;
use Acf\DataBundle\Entity\Autoinc;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class SubCategController extends BaseController
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
        $this->gvars['menu_active'] = 'aocateg';
    }

    /**
     *
     * @param string $uid
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($uid)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('aove_admin_categ_list');
        }
        $em = $this->getEntityManager();
        try {
            $subCateg = $em->getRepository('AcfDataBundle:AoSubCateg')->find($uid);

            if (null == $subCateg) {
                $this->flashMsgSession('warning', $this->translate('AoSubCateg.delete.notfound'));
            } else {
                $em->remove($subCateg);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('AoSubCateg.delete.success', array(
                    '%subCateg%' => $subCateg->getRef()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('AoSubCateg.delete.failure'));
        }

        return $this->redirect($urlFrom);
    }

    /**
     *
     * @param string $uid
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editGetAction($uid)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('aove_admin_categ_list');
        }
        $em = $this->getEntityManager();
        try {
            $subCateg = $em->getRepository('AcfDataBundle:AoSubCateg')->find($uid);

            if (null == $subCateg) {
                $this->flashMsgSession('warning', $this->translate('AoSubCateg.edit.notfound'));
            } else {
                $subCategUpdateTitleForm = $this->createForm(AoSubCategUpdateTitleTForm::class, $subCateg);
                $subCategUpdateRefForm = $this->createForm(AoSubCategUpdateRefTForm::class, $subCateg);
                $subCategUpdatePriorityForm = $this->createForm(AoSubCategUpdatePriorityTForm::class, $subCateg);
                $subCategUpdateCategForm = $this->createForm(AoSubCategUpdateCategTForm::class, $subCateg);

                $callfortender = new AoCallfortender();
                $callfortender->setGrp($subCateg);
                $callfortenderNewForm = $this->createForm(AoCallfortenderNewTForm::class, $callfortender, array(
                    'grp' => $subCateg
                ));
                $this->gvars['callfortender'] = $callfortender;

                $this->gvars['subCateg'] = $subCateg;

                $this->gvars['SubCategUpdateTitleForm'] = $subCategUpdateTitleForm->createView();
                $this->gvars['SubCategUpdateRefForm'] = $subCategUpdateRefForm->createView();
                $this->gvars['SubCategUpdatePriorityForm'] = $subCategUpdatePriorityForm->createView();
                $this->gvars['SubCategUpdateCategForm'] = $subCategUpdateCategForm->createView();
                $this->gvars['CallfortenderNewForm'] = $callfortenderNewForm->createView();

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');

                $this->gvars['pagetitle'] = $this->translate('pagetitle.aoSubCateg.edit', array(
                    '%subCateg%' => $subCateg->getRef()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.aoSubCateg.edit.txt', array(
                    '%subCateg%' => $subCateg->getRef()
                ));

                return $this->renderResponse('AoVeAdminBundle:AoSubCateg:edit.html.twig', $this->gvars);
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
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editPostAction($uid)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('aove_admin_categ_list');
        }
        $em = $this->getEntityManager();
        try {
            $subCateg = $em->getRepository('AcfDataBundle:AoSubCateg')->find($uid);

            if (null == $subCateg) {
                $this->flashMsgSession('warning', $this->translate('AoSubCateg.edit.notfound'));
            } else {
                $subCategUpdateTitleForm = $this->createForm(AoSubCategUpdateTitleTForm::class, $subCateg);
                $subCategUpdateRefForm = $this->createForm(AoSubCategUpdateRefTForm::class, $subCateg);
                $subCategUpdatePriorityForm = $this->createForm(AoSubCategUpdatePriorityTForm::class, $subCateg);
                $subCategUpdateCategForm = $this->createForm(AoSubCategUpdateCategTForm::class, $subCateg);

                $callfortender = new AoCallfortender();
                $callfortender->setGrp($subCateg);
                $callfortenderNewForm = $this->createForm(AoCallfortenderNewTForm::class, $callfortender, array(
                    'grp' => $subCateg
                ));

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                if (isset($reqData['AoSubCategUpdateTitleForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $subCategUpdateTitleForm->handleRequest($request);
                    if ($subCategUpdateTitleForm->isValid()) {
                        $em->persist($subCateg);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoSubCateg.edit.success', array(
                            '%subCateg%' => $subCateg->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_subcateg_editGet', array(
                            'uid' => $subCateg->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoSubCateg.edit.failure'));
                    }
                } elseif (isset($reqData['AoSubCategUpdateRefForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $subCategUpdateRefForm->handleRequest($request);
                    if ($subCategUpdateRefForm->isValid()) {
                        $em->persist($subCateg);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoSubCateg.edit.success', array(
                            '%subCateg%' => $subCateg->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_subcateg_editGet', array(
                            'uid' => $subCateg->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoSubCateg.edit.failure'));
                    }
                } elseif (isset($reqData['AoSubCategUpdatePriorityForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $subCategUpdatePriorityForm->handleRequest($request);
                    if ($subCategUpdatePriorityForm->isValid()) {
                        $em->persist($subCateg);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoSubCateg.edit.success', array(
                            '%subCateg%' => $subCateg->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_subcateg_editGet', array(
                            'uid' => $subCateg->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoSubCateg.edit.failure'));
                    }
                } elseif (isset($reqData['AoSubCategUpdateCategForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $subCategUpdateCategForm->handleRequest($request);
                    if ($subCategUpdateCategForm->isValid()) {
                        $em->persist($subCateg);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoSubCateg.edit.success', array(
                            '%subCateg%' => $subCateg->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_subcateg_editGet', array(
                            'uid' => $subCateg->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoSubCateg.edit.failure'));
                    }
                } elseif (isset($reqData['AoCallfortenderNewForm'])) {
                    $this->gvars['tabActive'] = 3;
                    $this->getSession()->set('tabActive', 3);
                    $callfortenderNewForm->handleRequest($request);
                    if ($callfortenderNewForm->isValid()) {
                        $autoinc = $em->getRepository('AcfDataBundle:Autoinc')->findOneBy(array(
                            'name' => 'AO'
                        ));
                        if (null == $autoinc) {
                            $autoinc = new Autoinc(1, 0);
                            $autoinc->setName('AO');
                        } else {
                            $autoinc->setCount($autoinc->getCount() + 1);
                        }
                        $em->persist($autoinc);
                        $em->flush();

                        $callfortender->setRef($autoinc->getValue());

                        $imgFile = $callfortenderNewForm['img']->getData();

                        if (null != $imgFile) {
                            $imgDir = $this->getParameter('kernel.root_dir') . '/../web/res/Ao';
                            $fileName = sha1(uniqid(mt_rand(), true)) . '.' . strtolower($imgFile->getClientOriginalExtension());
                            $imgFile->move($imgDir, $fileName);
                            $callfortender->setImg($fileName);
                        }

                        $em->persist($callfortender);
                        $em->flush();

                        $this->gvars['tabActive'] = 1;
                        $this->getSession()->set('tabActive', 1);

                        $this->flashMsgSession('success', $this->translate('AoCallfortender.add.success', array(
                            '%callfortender%' => $callfortender->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_callfortender_editGet', array(
                            'uid' => $callfortender->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoCallfortender.add.failure'));
                    }
                }

                $this->gvars['subCateg'] = $subCateg;
                $this->gvars['callfortender'] = $callfortender;

                $this->gvars['SubCategUpdateTitleForm'] = $subCategUpdateTitleForm->createView();
                $this->gvars['SubCategUpdateRefForm'] = $subCategUpdateRefForm->createView();
                $this->gvars['SubCategUpdatePriorityForm'] = $subCategUpdatePriorityForm->createView();
                $this->gvars['SubCategUpdateCategForm'] = $subCategUpdateCategForm->createView();
                $this->gvars['CallfortenderNewForm'] = $callfortenderNewForm->createView();

                $this->gvars['pagetitle'] = $this->translate('pagetitle.aoSubCateg.edit', array(
                    '%subCateg%' => $subCateg->getRef()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.aoSubCateg.edit.txt', array(
                    '%subCateg%' => $subCateg->getRef()
                ));

                return $this->renderResponse('AoVeAdminBundle:AoSubCateg:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }
}

