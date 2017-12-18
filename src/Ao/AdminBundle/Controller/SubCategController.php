<?php
namespace Ao\AdminBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Ao\AdminBundle\Form\AoSubCateg\UpdateTitleTForm as AoSubCategUpdateTitleTForm;
use Ao\AdminBundle\Form\AoSubCateg\UpdateRefTForm as AoSubCategUpdateRefTForm;
use Ao\AdminBundle\Form\AoSubCateg\UpdateCategTForm as AoSubCategUpdateCategTForm;
use Ao\AdminBundle\Form\AoSubCateg\UpdatePriorityTForm as AoSubCategUpdatePriorityTForm;
use Ao\AdminBundle\Form\AoAdvertisement\NewTForm as AoAdvertisementNewTForm;
use Acf\DataBundle\Entity\AoAdvertisement;
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
            $urlFrom = $this->generateUrl('ao_admin_categ_list');
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
            $urlFrom = $this->generateUrl('ao_admin_categ_list');
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

                $advertisement = new AoAdvertisement();
                $advertisement->setGrp($subCateg);
                $advertisementNewForm = $this->createForm(AoAdvertisementNewTForm::class, $advertisement, array(
                    'grp' => $subCateg
                ));
                $this->gvars['advertisement'] = $advertisement;

                $this->gvars['subCateg'] = $subCateg;

                $this->gvars['SubCategUpdateTitleForm'] = $subCategUpdateTitleForm->createView();
                $this->gvars['SubCategUpdateRefForm'] = $subCategUpdateRefForm->createView();
                $this->gvars['SubCategUpdatePriorityForm'] = $subCategUpdatePriorityForm->createView();
                $this->gvars['SubCategUpdateCategForm'] = $subCategUpdateCategForm->createView();
                $this->gvars['AdvertisementNewForm'] = $advertisementNewForm->createView();

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');

                $this->gvars['pagetitle'] = $this->translate('pagetitle.aoSubCateg.edit', array(
                    '%subCateg%' => $subCateg->getRef()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.aoSubCateg.edit.txt', array(
                    '%subCateg%' => $subCateg->getRef()
                ));

                return $this->renderResponse('AoAdminBundle:AoSubCateg:edit.html.twig', $this->gvars);
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
            $urlFrom = $this->generateUrl('ao_admin_categ_list');
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

                $advertisement = new AoAdvertisement();
                $advertisement->setGrp($subCateg);
                $advertisementNewForm = $this->createForm(AoAdvertisementNewTForm::class, $advertisement, array(
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

                        return $this->redirect($this->generateUrl('ao_admin_subcateg_editGet', array(
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

                        return $this->redirect($this->generateUrl('ao_admin_subcateg_editGet', array(
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

                        return $this->redirect($this->generateUrl('ao_admin_subcateg_editGet', array(
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

                        return $this->redirect($this->generateUrl('ao_admin_subcateg_editGet', array(
                            'uid' => $subCateg->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoSubCateg.edit.failure'));
                    }
                } elseif (isset($reqData['AoAdvertisementNewForm'])) {
                    $this->gvars['tabActive'] = 3;
                    $this->getSession()->set('tabActive', 3);
                    $advertisementNewForm->handleRequest($request);
                    if ($advertisementNewForm->isValid()) {
                        $autoinc = $em->getRepository('AcfDataBundle:Autoinc')->findOneBy(array(
                            'name' => 'AOVE'
                        ));
                        if (null == $autoinc) {
                            $autoinc = new Autoinc(1, 0);
                            $autoinc->setName('AOVE');
                        } else {
                            $autoinc->setCount($autoinc->getCount() + 1);
                        }
                        $em->persist($autoinc);
                        $em->flush();

                        $advertisement->setRef($autoinc->getValue());

                        $imgFile = $advertisementNewForm['img']->getData();

                        if (null != $imgFile) {
                            $imgDir = $this->getParameter('kernel.root_dir') . '/../web/res/AoVe';
                            $fileName = sha1(uniqid(mt_rand(), true)) . '.' . strtolower($imgFile->getClientOriginalExtension());
                            $imgFile->move($imgDir, $fileName);
                            $advertisement->setImg($fileName);
                        }

                        $em->persist($advertisement);
                        $em->flush();

                        $this->gvars['tabActive'] = 1;
                        $this->getSession()->set('tabActive', 1);

                        $this->flashMsgSession('success', $this->translate('AoAdvertisement.add.success', array(
                            '%advertisement%' => $advertisement->getRef()
                        )));

                        return $this->redirect($this->generateUrl('ao_admin_advertisement_editGet', array(
                            'uid' => $advertisement->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoAdvertisement.add.failure'));
                    }
                }

                $this->gvars['subCateg'] = $subCateg;
                $this->gvars['advertisement'] = $advertisement;

                $this->gvars['SubCategUpdateTitleForm'] = $subCategUpdateTitleForm->createView();
                $this->gvars['SubCategUpdateRefForm'] = $subCategUpdateRefForm->createView();
                $this->gvars['SubCategUpdatePriorityForm'] = $subCategUpdatePriorityForm->createView();
                $this->gvars['SubCategUpdateCategForm'] = $subCategUpdateCategForm->createView();
                $this->gvars['AdvertisementNewForm'] = $advertisementNewForm->createView();

                $this->gvars['pagetitle'] = $this->translate('pagetitle.aoSubCateg.edit', array(
                    '%subCateg%' => $subCateg->getRef()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.aoSubCateg.edit.txt', array(
                    '%subCateg%' => $subCateg->getRef()
                ));

                return $this->renderResponse('AoAdminBundle:AoSubCateg:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }
}

