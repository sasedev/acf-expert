<?php
namespace AoVe\AdminBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use AoVe\AdminBundle\Form\AoCallfortender\UpdateGrpTForm as AoCallfortenderUpdateGrpTForm;
use AoVe\AdminBundle\Form\AoCallfortender\UpdateImgTForm as AoCallfortenderUpdateImgTForm;
use AoVe\AdminBundle\Form\AoCallfortender\UpdateDtPublicationTForm as AoCallfortenderUpdateDtPublicationTForm;
use AoVe\AdminBundle\Form\AoCallfortender\UpdateCountryTForm as AoCallfortenderUpdateCountryTForm;
use AoVe\AdminBundle\Form\AoCallfortender\UpdateDescriptionTForm as AoCallfortenderUpdateDescriptionTForm;
use AoVe\AdminBundle\Form\AoCallfortender\UpdateCompanyTForm as AoCallfortenderUpdateCompanyTForm;
use AoVe\AdminBundle\Form\AoCallfortender\UpdateNatureTForm as AoCallfortenderUpdateNatureTForm;
use AoVe\AdminBundle\Form\AoCallfortender\UpdateDtEndTForm as AoCallfortenderUpdateDtEndTForm;
use AoVe\AdminBundle\Form\AoCallfortender\UpdateDtOpenTForm as AoCallfortenderUpdateDtOpenTForm;
use AoVe\AdminBundle\Form\AoCallfortender\UpdateAdressTForm as AoCallfortenderUpdateAdressTForm;
use AoVe\AdminBundle\Form\AoCallfortender\UpdatePriceTForm as AoCallfortenderUpdatePriceTForm;
use AoVe\AdminBundle\Form\AoCallfortender\UpdateTypeAvisTForm as AoCallfortenderUpdateTypeAvisTForm;
use AoVe\AdminBundle\Form\AoCallfortender\UpdateAddRefTForm as AoCallfortenderUpdateAddRefTForm;
use AoVe\AdminBundle\Form\AoCallfortender\UpdateStatusTForm as AoCallfortenderUpdateStatusTForm;
use AoVe\AdminBundle\Form\AoCallfortender\UpdateSourceTForm as AoCallfortenderUpdateSourceTForm;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class CallfortenderController extends BaseController
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
            $callfortender = $em->getRepository('AcfDataBundle:AoCallfortender')->find($uid);

            if (null == $callfortender) {
                $this->flashMsgSession('warning', $this->translate('AoCallfortender.delete.notfound'));
            } else {
                $em->remove($callfortender);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('AoCallfortender.delete.success', array(
                    '%callfortender%' => $callfortender->getRef()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('AoCallfortender.delete.failure'));
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
            $callfortender = $em->getRepository('AcfDataBundle:AoCallfortender')->find($uid);

            if (null == $callfortender) {
                $this->flashMsgSession('warning', $this->translate('AoCallfortender.edit.notfound'));
            } else {
                $callfortenderUpdateGrpForm = $this->createForm(AoCallfortenderUpdateGrpTForm::class, $callfortender);
                $callfortenderUpdateImgForm = $this->createForm(AoCallfortenderUpdateImgTForm::class, $callfortender);
                $callfortenderUpdateDtPublicationForm = $this->createForm(AoCallfortenderUpdateDtPublicationTForm::class, $callfortender);
                $callfortenderUpdateCountryForm = $this->createForm(AoCallfortenderUpdateCountryTForm::class, $callfortender);
                $callfortenderUpdateDescriptionForm = $this->createForm(AoCallfortenderUpdateDescriptionTForm::class, $callfortender);
                $callfortenderUpdateCompanyForm = $this->createForm(AoCallfortenderUpdateCompanyTForm::class, $callfortender);
                $callfortenderUpdateNatureForm = $this->createForm(AoCallfortenderUpdateNatureTForm::class, $callfortender);
                $callfortenderUpdateDtEndForm = $this->createForm(AoCallfortenderUpdateDtEndTForm::class, $callfortender);
                $callfortenderUpdateDtOpenForm = $this->createForm(AoCallfortenderUpdateDtOpenTForm::class, $callfortender);
                $callfortenderUpdateAdressForm = $this->createForm(AoCallfortenderUpdateAdressTForm::class, $callfortender);
                $callfortenderUpdatePriceForm = $this->createForm(AoCallfortenderUpdatePriceTForm::class, $callfortender);
                $callfortenderUpdateTypeAvisForm = $this->createForm(AoCallfortenderUpdateTypeAvisTForm::class, $callfortender);
                $callfortenderUpdateAddRefForm = $this->createForm(AoCallfortenderUpdateAddRefTForm::class, $callfortender);
                $callfortenderUpdateStatusForm = $this->createForm(AoCallfortenderUpdateStatusTForm::class, $callfortender);
                $callfortenderUpdateSourceForm = $this->createForm(AoCallfortenderUpdateSourceTForm::class, $callfortender);

                $this->gvars['callfortender'] = $callfortender;

                $this->gvars['CallfortenderUpdateGrpForm'] = $callfortenderUpdateGrpForm->createView();
                $this->gvars['CallfortenderUpdateImgForm'] = $callfortenderUpdateImgForm->createView();
                $this->gvars['CallfortenderUpdateDtPublicationForm'] = $callfortenderUpdateDtPublicationForm->createView();
                $this->gvars['CallfortenderUpdateCountryForm'] = $callfortenderUpdateCountryForm->createView();
                $this->gvars['CallfortenderUpdateDescriptionForm'] = $callfortenderUpdateDescriptionForm->createView();
                $this->gvars['CallfortenderUpdateCompanyForm'] = $callfortenderUpdateCompanyForm->createView();
                $this->gvars['CallfortenderUpdateNatureForm'] = $callfortenderUpdateNatureForm->createView();
                $this->gvars['CallfortenderUpdateDtEndForm'] = $callfortenderUpdateDtEndForm->createView();
                $this->gvars['CallfortenderUpdateDtOpenForm'] = $callfortenderUpdateDtOpenForm->createView();
                $this->gvars['CallfortenderUpdateAdressForm'] = $callfortenderUpdateAdressForm->createView();
                $this->gvars['CallfortenderUpdatePriceForm'] = $callfortenderUpdatePriceForm->createView();
                $this->gvars['CallfortenderUpdateTypeAvisForm'] = $callfortenderUpdateTypeAvisForm->createView();
                $this->gvars['CallfortenderUpdateAddRefForm'] = $callfortenderUpdateAddRefForm->createView();
                $this->gvars['CallfortenderUpdateStatusForm'] = $callfortenderUpdateStatusForm->createView();
                $this->gvars['CallfortenderUpdateSourceForm'] = $callfortenderUpdateSourceForm->createView();

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');

                $this->gvars['pagetitle'] = $this->translate('pagetitle.aoCallfortender.edit', array(
                    '%callfortender%' => $callfortender->getRef()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.aoCallfortender.edit.txt', array(
                    '%callfortender%' => $callfortender->getRef()
                ));

                return $this->renderResponse('AoVeAdminBundle:AoCallfortender:edit.html.twig', $this->gvars);
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
            $callfortender = $em->getRepository('AcfDataBundle:AoCallfortender')->find($uid);

            if (null == $callfortender) {
                $this->flashMsgSession('warning', $this->translate('AoCallfortender.edit.notfound'));
            } else {
                $callfortenderUpdateGrpForm = $this->createForm(AoCallfortenderUpdateGrpTForm::class, $callfortender);
                $callfortenderUpdateImgForm = $this->createForm(AoCallfortenderUpdateImgTForm::class, $callfortender);
                $callfortenderUpdateDtPublicationForm = $this->createForm(AoCallfortenderUpdateDtPublicationTForm::class, $callfortender);
                $callfortenderUpdateCountryForm = $this->createForm(AoCallfortenderUpdateCountryTForm::class, $callfortender);
                $callfortenderUpdateDescriptionForm = $this->createForm(AoCallfortenderUpdateDescriptionTForm::class, $callfortender);
                $callfortenderUpdateCompanyForm = $this->createForm(AoCallfortenderUpdateCompanyTForm::class, $callfortender);
                $callfortenderUpdateNatureForm = $this->createForm(AoCallfortenderUpdateNatureTForm::class, $callfortender);
                $callfortenderUpdateDtEndForm = $this->createForm(AoCallfortenderUpdateDtEndTForm::class, $callfortender);
                $callfortenderUpdateDtOpenForm = $this->createForm(AoCallfortenderUpdateDtOpenTForm::class, $callfortender);
                $callfortenderUpdateAdressForm = $this->createForm(AoCallfortenderUpdateAdressTForm::class, $callfortender);
                $callfortenderUpdatePriceForm = $this->createForm(AoCallfortenderUpdatePriceTForm::class, $callfortender);
                $callfortenderUpdateTypeAvisForm = $this->createForm(AoCallfortenderUpdateTypeAvisTForm::class, $callfortender);
                $callfortenderUpdateAddRefForm = $this->createForm(AoCallfortenderUpdateAddRefTForm::class, $callfortender);
                $callfortenderUpdateStatusForm = $this->createForm(AoCallfortenderUpdateStatusTForm::class, $callfortender);
                $callfortenderUpdateSourceForm = $this->createForm(AoCallfortenderUpdateSourceTForm::class, $callfortender);

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                if (isset($reqData['AoCallfortenderUpdateGrpForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $callfortenderUpdateGrpForm->handleRequest($request);
                    if ($callfortenderUpdateGrpForm->isValid()) {
                        $em->persist($callfortender);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoCallfortender.edit.success', array(
                            '%callfortender%' => $callfortender->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_callfortender_editGet', array(
                            'uid' => $callfortender->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoCallfortender.edit.failure'));
                    }
                } elseif (isset($reqData['AoCallfortenderUpdateImgForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $callfortenderUpdateImgForm->handleRequest($request);
                    if ($callfortenderUpdateImgForm->isValid()) {
                        $imgFile = $callfortenderUpdateImgForm['image']->getData();

                        if (null != $imgFile) {
                            $imgDir = $this->getParameter('kernel.root_dir') . '/../web/res/Ao';
                            $fileName = sha1(uniqid(mt_rand(), true)) . '.' . strtolower($imgFile->getClientOriginalExtension());
                            $imgFile->move($imgDir, $fileName);
                            $callfortender->setImg($fileName);
                        } else {
                            $callfortender->setImg(null);
                        }
                        $em->persist($callfortender);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoCallfortender.edit.success', array(
                            '%callfortender%' => $callfortender->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_callfortender_editGet', array(
                            'uid' => $callfortender->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoCallfortender.edit.failure'));
                    }
                } elseif (isset($reqData['AoCallfortenderUpdateDtPublicationForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $callfortenderUpdateDtPublicationForm->handleRequest($request);
                    if ($callfortenderUpdateDtPublicationForm->isValid()) {
                        $em->persist($callfortender);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoCallfortender.edit.success', array(
                            '%callfortender%' => $callfortender->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_callfortender_editGet', array(
                            'uid' => $callfortender->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoCallfortender.edit.failure'));
                    }
                } elseif (isset($reqData['AoCallfortenderUpdateCountryForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $callfortenderUpdateCountryForm->handleRequest($request);
                    if ($callfortenderUpdateCountryForm->isValid()) {
                        $em->persist($callfortender);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoCallfortender.edit.success', array(
                            '%callfortender%' => $callfortender->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_callfortender_editGet', array(
                            'uid' => $callfortender->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoCallfortender.edit.failure'));
                    }
                } elseif (isset($reqData['AoCallfortenderUpdateDescriptionForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $callfortenderUpdateDescriptionForm->handleRequest($request);
                    if ($callfortenderUpdateDescriptionForm->isValid()) {
                        $em->persist($callfortender);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoCallfortender.edit.success', array(
                            '%callfortender%' => $callfortender->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_callfortender_editGet', array(
                            'uid' => $callfortender->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoCallfortender.edit.failure'));
                    }
                } elseif (isset($reqData['AoCallfortenderUpdateCompanyForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $callfortenderUpdateCompanyForm->handleRequest($request);
                    if ($callfortenderUpdateCompanyForm->isValid()) {
                        $em->persist($callfortender);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoCallfortender.edit.success', array(
                            '%callfortender%' => $callfortender->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_callfortender_editGet', array(
                            'uid' => $callfortender->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoCallfortender.edit.failure'));
                    }
                } elseif (isset($reqData['AoCallfortenderUpdateNatureForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $callfortenderUpdateNatureForm->handleRequest($request);
                    if ($callfortenderUpdateNatureForm->isValid()) {
                        $em->persist($callfortender);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoCallfortender.edit.success', array(
                            '%callfortender%' => $callfortender->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_callfortender_editGet', array(
                            'uid' => $callfortender->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoCallfortender.edit.failure'));
                    }
                } elseif (isset($reqData['AoCallfortenderUpdateDtEndForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $callfortenderUpdateDtEndForm->handleRequest($request);
                    if ($callfortenderUpdateDtEndForm->isValid()) {
                        $em->persist($callfortender);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoCallfortender.edit.success', array(
                            '%callfortender%' => $callfortender->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_callfortender_editGet', array(
                            'uid' => $callfortender->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoCallfortender.edit.failure'));
                    }
                } elseif (isset($reqData['AoCallfortenderUpdateDtOpenForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $callfortenderUpdateDtOpenForm->handleRequest($request);
                    if ($callfortenderUpdateDtOpenForm->isValid()) {
                        $em->persist($callfortender);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoCallfortender.edit.success', array(
                            '%callfortender%' => $callfortender->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_callfortender_editGet', array(
                            'uid' => $callfortender->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoCallfortender.edit.failure'));
                    }
                } elseif (isset($reqData['AoCallfortenderUpdateAdressForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $callfortenderUpdateAdressForm->handleRequest($request);
                    if ($callfortenderUpdateAdressForm->isValid()) {
                        $em->persist($callfortender);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoCallfortender.edit.success', array(
                            '%callfortender%' => $callfortender->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_callfortender_editGet', array(
                            'uid' => $callfortender->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoCallfortender.edit.failure'));
                    }
                } elseif (isset($reqData['AoCallfortenderUpdatePriceForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $callfortenderUpdatePriceForm->handleRequest($request);
                    if ($callfortenderUpdatePriceForm->isValid()) {
                        $em->persist($callfortender);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoCallfortender.edit.success', array(
                            '%callfortender%' => $callfortender->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_callfortender_editGet', array(
                            'uid' => $callfortender->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoCallfortender.edit.failure'));
                    }
                } elseif (isset($reqData['AoCallfortenderUpdateTypeAvisForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $callfortenderUpdateTypeAvisForm->handleRequest($request);
                    if ($callfortenderUpdateTypeAvisForm->isValid()) {
                        $em->persist($callfortender);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoCallfortender.edit.success', array(
                            '%callfortender%' => $callfortender->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_callfortender_editGet', array(
                            'uid' => $callfortender->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoCallfortender.edit.failure'));
                    }
                } elseif (isset($reqData['AoCallfortenderUpdateAddRefForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $callfortenderUpdateAddRefForm->handleRequest($request);
                    if ($callfortenderUpdateAddRefForm->isValid()) {
                        $em->persist($callfortender);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoCallfortender.edit.success', array(
                            '%callfortender%' => $callfortender->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_callfortender_editGet', array(
                            'uid' => $callfortender->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoCallfortender.edit.failure'));
                    }
                } elseif (isset($reqData['AoCallfortenderUpdateSourceForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $callfortenderUpdateSourceForm->handleRequest($request);
                    if ($callfortenderUpdateSourceForm->isValid()) {
                        $em->persist($callfortender);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoCallfortender.edit.success', array(
                            '%callfortender%' => $callfortender->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_callfortender_editGet', array(
                            'uid' => $callfortender->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoCallfortender.edit.failure'));
                    }
                } elseif (isset($reqData['AoCallfortenderUpdateStatusForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $callfortenderUpdateStatusForm->handleRequest($request);
                    if ($callfortenderUpdateStatusForm->isValid()) {
                        $em->persist($callfortender);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoCallfortender.edit.success', array(
                            '%callfortender%' => $callfortender->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_callfortender_editGet', array(
                            'uid' => $callfortender->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoCallfortender.edit.failure'));
                    }
                }

                $this->gvars['callfortender'] = $callfortender;

                $this->gvars['CallfortenderUpdateGrpForm'] = $callfortenderUpdateGrpForm->createView();
                $this->gvars['CallfortenderUpdateImgForm'] = $callfortenderUpdateImgForm->createView();
                $this->gvars['CallfortenderUpdateDtPublicationForm'] = $callfortenderUpdateDtPublicationForm->createView();
                $this->gvars['CallfortenderUpdateCountryForm'] = $callfortenderUpdateCountryForm->createView();
                $this->gvars['CallfortenderUpdateDescriptionForm'] = $callfortenderUpdateDescriptionForm->createView();
                $this->gvars['CallfortenderUpdateCompanyForm'] = $callfortenderUpdateCompanyForm->createView();
                $this->gvars['CallfortenderUpdateNatureForm'] = $callfortenderUpdateNatureForm->createView();
                $this->gvars['CallfortenderUpdateDtEndForm'] = $callfortenderUpdateDtEndForm->createView();
                $this->gvars['CallfortenderUpdateDtOpenForm'] = $callfortenderUpdateDtOpenForm->createView();
                $this->gvars['CallfortenderUpdateAdressForm'] = $callfortenderUpdateAdressForm->createView();
                $this->gvars['CallfortenderUpdatePriceForm'] = $callfortenderUpdatePriceForm->createView();
                $this->gvars['CallfortenderUpdateTypeAvisForm'] = $callfortenderUpdateTypeAvisForm->createView();
                $this->gvars['CallfortenderUpdateAddRefForm'] = $callfortenderUpdateAddRefForm->createView();
                $this->gvars['CallfortenderUpdateStatusForm'] = $callfortenderUpdateStatusForm->createView();
                $this->gvars['CallfortenderUpdateSourceForm'] = $callfortenderUpdateSourceForm->createView();

                $this->gvars['pagetitle'] = $this->translate('pagetitle.aoCallfortender.edit', array(
                    '%callfortender%' => $callfortender->getRef()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.aoCallfortender.edit.txt', array(
                    '%callfortender%' => $callfortender->getRef()
                ));

                return $this->renderResponse('AoVeAdminBundle:AoCallfortender:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }
}

