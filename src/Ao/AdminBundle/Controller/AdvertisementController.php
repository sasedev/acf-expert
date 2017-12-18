<?php
namespace Ao\AdminBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Ao\AdminBundle\Form\AoAdvertisement\UpdateGrpTForm as AoAdvertisementUpdateGrpTForm;
use Ao\AdminBundle\Form\AoAdvertisement\UpdateImgTForm as AoAdvertisementUpdateImgTForm;
use Ao\AdminBundle\Form\AoAdvertisement\UpdateDtPublicationTForm as AoAdvertisementUpdateDtPublicationTForm;
use Ao\AdminBundle\Form\AoAdvertisement\UpdateCountryTForm as AoAdvertisementUpdateCountryTForm;
use Ao\AdminBundle\Form\AoAdvertisement\UpdateDescriptionTForm as AoAdvertisementUpdateDescriptionTForm;
use Ao\AdminBundle\Form\AoAdvertisement\UpdateCompanyTForm as AoAdvertisementUpdateCompanyTForm;
use Ao\AdminBundle\Form\AoAdvertisement\UpdateNatureTForm as AoAdvertisementUpdateNatureTForm;
use Ao\AdminBundle\Form\AoAdvertisement\UpdateDtEndTForm as AoAdvertisementUpdateDtEndTForm;
use Ao\AdminBundle\Form\AoAdvertisement\UpdateAoVeTForm as AoAdvertisementUpdateAoVeTForm;
use Ao\AdminBundle\Form\AoAdvertisement\UpdateDtOpenTForm as AoAdvertisementUpdateDtOpenTForm;
use Ao\AdminBundle\Form\AoAdvertisement\UpdateAdressTForm as AoAdvertisementUpdateAdressTForm;
use Ao\AdminBundle\Form\AoAdvertisement\UpdatePriceTForm as AoAdvertisementUpdatePriceTForm;
use Ao\AdminBundle\Form\AoAdvertisement\UpdateTypeAvisTForm as AoAdvertisementUpdateTypeAvisTForm;
use Ao\AdminBundle\Form\AoAdvertisement\UpdateAddRefTForm as AoAdvertisementUpdateAddRefTForm;
use Ao\AdminBundle\Form\AoAdvertisement\UpdateStatusTForm as AoAdvertisementUpdateStatusTForm;
use Ao\AdminBundle\Form\AoAdvertisement\UpdateSourceTForm as AoAdvertisementUpdateSourceTForm;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class AdvertisementController extends BaseController
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
            $advertisement = $em->getRepository('AcfDataBundle:AoAdvertisement')->find($uid);

            if (null == $advertisement) {
                $this->flashMsgSession('warning', $this->translate('AoAdvertisement.delete.notfound'));
            } else {
                $em->remove($advertisement);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('AoAdvertisement.delete.success', array(
                    '%advertisement%' => $advertisement->getRef()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('AoAdvertisement.delete.failure'));
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
            $advertisement = $em->getRepository('AcfDataBundle:AoAdvertisement')->find($uid);

            if (null == $advertisement) {
                $this->flashMsgSession('warning', $this->translate('AoAdvertisement.edit.notfound'));
            } else {
                $advertisementUpdateGrpForm = $this->createForm(AoAdvertisementUpdateGrpTForm::class, $advertisement);
                $advertisementUpdateImgForm = $this->createForm(AoAdvertisementUpdateImgTForm::class, $advertisement);
                $advertisementUpdateDtPublicationForm = $this->createForm(AoAdvertisementUpdateDtPublicationTForm::class, $advertisement);
                $advertisementUpdateCountryForm = $this->createForm(AoAdvertisementUpdateCountryTForm::class, $advertisement);
                $advertisementUpdateDescriptionForm = $this->createForm(AoAdvertisementUpdateDescriptionTForm::class, $advertisement);
                $advertisementUpdateCompanyForm = $this->createForm(AoAdvertisementUpdateCompanyTForm::class, $advertisement);
                $advertisementUpdateNatureForm = $this->createForm(AoAdvertisementUpdateNatureTForm::class, $advertisement);
                $advertisementUpdateDtEndForm = $this->createForm(AoAdvertisementUpdateDtEndTForm::class, $advertisement);
                $advertisementUpdateAoVeForm = $this->createForm(AoAdvertisementUpdateAoVeTForm::class, $advertisement);
                $advertisementUpdateDtOpenForm = $this->createForm(AoAdvertisementUpdateDtOpenTForm::class, $advertisement);
                $advertisementUpdateAdressForm = $this->createForm(AoAdvertisementUpdateAdressTForm::class, $advertisement);
                $advertisementUpdatePriceForm = $this->createForm(AoAdvertisementUpdatePriceTForm::class, $advertisement);
                $advertisementUpdateTypeAvisForm = $this->createForm(AoAdvertisementUpdateTypeAvisTForm::class, $advertisement);
                $advertisementUpdateAddRefForm = $this->createForm(AoAdvertisementUpdateAddRefTForm::class, $advertisement);
                $advertisementUpdateStatusForm = $this->createForm(AoAdvertisementUpdateStatusTForm::class, $advertisement);
                $advertisementUpdateSourceForm = $this->createForm(AoAdvertisementUpdateSourceTForm::class, $advertisement);

                $this->gvars['advertisement'] = $advertisement;

                $this->gvars['AdvertisementUpdateGrpForm'] = $advertisementUpdateGrpForm->createView();
                $this->gvars['AdvertisementUpdateImgForm'] = $advertisementUpdateImgForm->createView();
                $this->gvars['AdvertisementUpdateDtPublicationForm'] = $advertisementUpdateDtPublicationForm->createView();
                $this->gvars['AdvertisementUpdateCountryForm'] = $advertisementUpdateCountryForm->createView();
                $this->gvars['AdvertisementUpdateDescriptionForm'] = $advertisementUpdateDescriptionForm->createView();
                $this->gvars['AdvertisementUpdateCompanyForm'] = $advertisementUpdateCompanyForm->createView();
                $this->gvars['AdvertisementUpdateNatureForm'] = $advertisementUpdateNatureForm->createView();
                $this->gvars['AdvertisementUpdateDtEndForm'] = $advertisementUpdateDtEndForm->createView();
                $this->gvars['AdvertisementUpdateAoVeForm'] = $advertisementUpdateAoVeForm->createView();
                $this->gvars['AdvertisementUpdateDtOpenForm'] = $advertisementUpdateDtOpenForm->createView();
                $this->gvars['AdvertisementUpdateAdressForm'] = $advertisementUpdateAdressForm->createView();
                $this->gvars['AdvertisementUpdatePriceForm'] = $advertisementUpdatePriceForm->createView();
                $this->gvars['AdvertisementUpdateTypeAvisForm'] = $advertisementUpdateTypeAvisForm->createView();
                $this->gvars['AdvertisementUpdateAddRefForm'] = $advertisementUpdateAddRefForm->createView();
                $this->gvars['AdvertisementUpdateStatusForm'] = $advertisementUpdateStatusForm->createView();
                $this->gvars['AdvertisementUpdateSourceForm'] = $advertisementUpdateSourceForm->createView();

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');

                $this->gvars['pagetitle'] = $this->translate('pagetitle.aoAdvertisement.edit', array(
                    '%advertisement%' => $advertisement->getRef()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.aoAdvertisement.edit.txt', array(
                    '%advertisement%' => $advertisement->getRef()
                ));

                return $this->renderResponse('AoAdminBundle:AoAdvertisement:edit.html.twig', $this->gvars);
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
            $advertisement = $em->getRepository('AcfDataBundle:AoAdvertisement')->find($uid);

            if (null == $advertisement) {
                $this->flashMsgSession('warning', $this->translate('AoAdvertisement.edit.notfound'));
            } else {
                $advertisementUpdateGrpForm = $this->createForm(AoAdvertisementUpdateGrpTForm::class, $advertisement);
                $advertisementUpdateImgForm = $this->createForm(AoAdvertisementUpdateImgTForm::class, $advertisement);
                $advertisementUpdateDtPublicationForm = $this->createForm(AoAdvertisementUpdateDtPublicationTForm::class, $advertisement);
                $advertisementUpdateCountryForm = $this->createForm(AoAdvertisementUpdateCountryTForm::class, $advertisement);
                $advertisementUpdateDescriptionForm = $this->createForm(AoAdvertisementUpdateDescriptionTForm::class, $advertisement);
                $advertisementUpdateCompanyForm = $this->createForm(AoAdvertisementUpdateCompanyTForm::class, $advertisement);
                $advertisementUpdateNatureForm = $this->createForm(AoAdvertisementUpdateNatureTForm::class, $advertisement);
                $advertisementUpdateDtEndForm = $this->createForm(AoAdvertisementUpdateDtEndTForm::class, $advertisement);
                $advertisementUpdateAoVeForm = $this->createForm(AoAdvertisementUpdateAoVeTForm::class, $advertisement);
                $advertisementUpdateDtOpenForm = $this->createForm(AoAdvertisementUpdateDtOpenTForm::class, $advertisement);
                $advertisementUpdateAdressForm = $this->createForm(AoAdvertisementUpdateAdressTForm::class, $advertisement);
                $advertisementUpdatePriceForm = $this->createForm(AoAdvertisementUpdatePriceTForm::class, $advertisement);
                $advertisementUpdateTypeAvisForm = $this->createForm(AoAdvertisementUpdateTypeAvisTForm::class, $advertisement);
                $advertisementUpdateAddRefForm = $this->createForm(AoAdvertisementUpdateAddRefTForm::class, $advertisement);
                $advertisementUpdateStatusForm = $this->createForm(AoAdvertisementUpdateStatusTForm::class, $advertisement);
                $advertisementUpdateSourceForm = $this->createForm(AoAdvertisementUpdateSourceTForm::class, $advertisement);

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                if (isset($reqData['AoAdvertisementUpdateGrpForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $advertisementUpdateGrpForm->handleRequest($request);
                    if ($advertisementUpdateGrpForm->isValid()) {
                        $em->persist($advertisement);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoAdvertisement.edit.success', array(
                            '%advertisement%' => $advertisement->getRef()
                        )));

                        return $this->redirect($this->generateUrl('ao_admin_advertisement_editGet', array(
                            'uid' => $advertisement->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoAdvertisement.edit.failure'));
                    }
                } elseif (isset($reqData['AoAdvertisementUpdateImgForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $advertisementUpdateImgForm->handleRequest($request);
                    if ($advertisementUpdateImgForm->isValid()) {
                        $imgFile = $advertisementUpdateImgForm['image']->getData();

                        if (null != $imgFile) {
                            $imgDir = $this->getParameter('kernel.root_dir') . '/../web/res/AoVe';
                            $fileName = sha1(uniqid(mt_rand(), true)) . '.' . strtolower($imgFile->getClientOriginalExtension());
                            $imgFile->move($imgDir, $fileName);
                            $advertisement->setImg($fileName);
                        } else {
                            $advertisement->setImg(null);
                        }
                        $em->persist($advertisement);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoAdvertisement.edit.success', array(
                            '%advertisement%' => $advertisement->getRef()
                        )));

                        return $this->redirect($this->generateUrl('ao_admin_advertisement_editGet', array(
                            'uid' => $advertisement->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoAdvertisement.edit.failure'));
                    }
                } elseif (isset($reqData['AoAdvertisementUpdateDtPublicationForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $advertisementUpdateDtPublicationForm->handleRequest($request);
                    if ($advertisementUpdateDtPublicationForm->isValid()) {
                        $em->persist($advertisement);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoAdvertisement.edit.success', array(
                            '%advertisement%' => $advertisement->getRef()
                        )));

                        return $this->redirect($this->generateUrl('ao_admin_advertisement_editGet', array(
                            'uid' => $advertisement->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoAdvertisement.edit.failure'));
                    }
                } elseif (isset($reqData['AoAdvertisementUpdateCountryForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $advertisementUpdateCountryForm->handleRequest($request);
                    if ($advertisementUpdateCountryForm->isValid()) {
                        $em->persist($advertisement);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoAdvertisement.edit.success', array(
                            '%advertisement%' => $advertisement->getRef()
                        )));

                        return $this->redirect($this->generateUrl('ao_admin_advertisement_editGet', array(
                            'uid' => $advertisement->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoAdvertisement.edit.failure'));
                    }
                } elseif (isset($reqData['AoAdvertisementUpdateDescriptionForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $advertisementUpdateDescriptionForm->handleRequest($request);
                    if ($advertisementUpdateDescriptionForm->isValid()) {
                        $em->persist($advertisement);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoAdvertisement.edit.success', array(
                            '%advertisement%' => $advertisement->getRef()
                        )));

                        return $this->redirect($this->generateUrl('ao_admin_advertisement_editGet', array(
                            'uid' => $advertisement->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoAdvertisement.edit.failure'));
                    }
                } elseif (isset($reqData['AoAdvertisementUpdateCompanyForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $advertisementUpdateCompanyForm->handleRequest($request);
                    if ($advertisementUpdateCompanyForm->isValid()) {
                        $em->persist($advertisement);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoAdvertisement.edit.success', array(
                            '%advertisement%' => $advertisement->getRef()
                        )));

                        return $this->redirect($this->generateUrl('ao_admin_advertisement_editGet', array(
                            'uid' => $advertisement->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoAdvertisement.edit.failure'));
                    }
                } elseif (isset($reqData['AoAdvertisementUpdateNatureForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $advertisementUpdateNatureForm->handleRequest($request);
                    if ($advertisementUpdateNatureForm->isValid()) {
                        $em->persist($advertisement);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoAdvertisement.edit.success', array(
                            '%advertisement%' => $advertisement->getRef()
                        )));

                        return $this->redirect($this->generateUrl('ao_admin_advertisement_editGet', array(
                            'uid' => $advertisement->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoAdvertisement.edit.failure'));
                    }
                } elseif (isset($reqData['AoAdvertisementUpdateDtEndForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $advertisementUpdateDtEndForm->handleRequest($request);
                    if ($advertisementUpdateDtEndForm->isValid()) {
                        $em->persist($advertisement);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoAdvertisement.edit.success', array(
                            '%advertisement%' => $advertisement->getRef()
                        )));

                        return $this->redirect($this->generateUrl('ao_admin_advertisement_editGet', array(
                            'uid' => $advertisement->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoAdvertisement.edit.failure'));
                    }
                } elseif (isset($reqData['AoAdvertisementUpdateAoVeForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $advertisementUpdateAoVeForm->handleRequest($request);
                    if ($advertisementUpdateAoVeForm->isValid()) {
                        $em->persist($advertisement);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoAdvertisement.edit.success', array(
                            '%advertisement%' => $advertisement->getRef()
                        )));

                        return $this->redirect($this->generateUrl('ao_admin_advertisement_editGet', array(
                            'uid' => $advertisement->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoAdvertisement.edit.failure'));
                    }
                } elseif (isset($reqData['AoAdvertisementUpdateDtOpenForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $advertisementUpdateDtOpenForm->handleRequest($request);
                    if ($advertisementUpdateDtOpenForm->isValid()) {
                        $em->persist($advertisement);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoAdvertisement.edit.success', array(
                            '%advertisement%' => $advertisement->getRef()
                        )));

                        return $this->redirect($this->generateUrl('ao_admin_advertisement_editGet', array(
                            'uid' => $advertisement->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoAdvertisement.edit.failure'));
                    }
                } elseif (isset($reqData['AoAdvertisementUpdateAdressForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $advertisementUpdateAdressForm->handleRequest($request);
                    if ($advertisementUpdateAdressForm->isValid()) {
                        $em->persist($advertisement);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoAdvertisement.edit.success', array(
                            '%advertisement%' => $advertisement->getRef()
                        )));

                        return $this->redirect($this->generateUrl('ao_admin_advertisement_editGet', array(
                            'uid' => $advertisement->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoAdvertisement.edit.failure'));
                    }
                } elseif (isset($reqData['AoAdvertisementUpdatePriceForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $advertisementUpdatePriceForm->handleRequest($request);
                    if ($advertisementUpdatePriceForm->isValid()) {
                        $em->persist($advertisement);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoAdvertisement.edit.success', array(
                            '%advertisement%' => $advertisement->getRef()
                        )));

                        return $this->redirect($this->generateUrl('ao_admin_advertisement_editGet', array(
                            'uid' => $advertisement->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoAdvertisement.edit.failure'));
                    }
                } elseif (isset($reqData['AoAdvertisementUpdateTypeAvisForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $advertisementUpdateTypeAvisForm->handleRequest($request);
                    if ($advertisementUpdateTypeAvisForm->isValid()) {
                        $em->persist($advertisement);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoAdvertisement.edit.success', array(
                            '%advertisement%' => $advertisement->getRef()
                        )));

                        return $this->redirect($this->generateUrl('ao_admin_advertisement_editGet', array(
                            'uid' => $advertisement->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoAdvertisement.edit.failure'));
                    }
                } elseif (isset($reqData['AoAdvertisementUpdateAddRefForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $advertisementUpdateAddRefForm->handleRequest($request);
                    if ($advertisementUpdateAddRefForm->isValid()) {
                        $em->persist($advertisement);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoAdvertisement.edit.success', array(
                            '%advertisement%' => $advertisement->getRef()
                        )));

                        return $this->redirect($this->generateUrl('ao_admin_advertisement_editGet', array(
                            'uid' => $advertisement->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoAdvertisement.edit.failure'));
                    }
                } elseif (isset($reqData['AoAdvertisementUpdateSourceForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $advertisementUpdateSourceForm->handleRequest($request);
                    if ($advertisementUpdateSourceForm->isValid()) {
                        $em->persist($advertisement);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoAdvertisement.edit.success', array(
                            '%advertisement%' => $advertisement->getRef()
                        )));

                        return $this->redirect($this->generateUrl('ao_admin_advertisement_editGet', array(
                            'uid' => $advertisement->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoAdvertisement.edit.failure'));
                    }
                } elseif (isset($reqData['AoAdvertisementUpdateStatusForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $advertisementUpdateStatusForm->handleRequest($request);
                    if ($advertisementUpdateStatusForm->isValid()) {
                        $em->persist($advertisement);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoAdvertisement.edit.success', array(
                            '%advertisement%' => $advertisement->getRef()
                        )));

                        return $this->redirect($this->generateUrl('ao_admin_advertisement_editGet', array(
                            'uid' => $advertisement->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoAdvertisement.edit.failure'));
                    }
                }

                $this->gvars['advertisement'] = $advertisement;

                $this->gvars['AdvertisementUpdateGrpForm'] = $advertisementUpdateGrpForm->createView();
                $this->gvars['AdvertisementUpdateImgForm'] = $advertisementUpdateImgForm->createView();
                $this->gvars['AdvertisementUpdateDtPublicationForm'] = $advertisementUpdateDtPublicationForm->createView();
                $this->gvars['AdvertisementUpdateCountryForm'] = $advertisementUpdateCountryForm->createView();
                $this->gvars['AdvertisementUpdateDescriptionForm'] = $advertisementUpdateDescriptionForm->createView();
                $this->gvars['AdvertisementUpdateCompanyForm'] = $advertisementUpdateCompanyForm->createView();
                $this->gvars['AdvertisementUpdateNatureForm'] = $advertisementUpdateNatureForm->createView();
                $this->gvars['AdvertisementUpdateDtEndForm'] = $advertisementUpdateDtEndForm->createView();
                $this->gvars['AdvertisementUpdateAoVeForm'] = $advertisementUpdateAoVeForm->createView();
                $this->gvars['AdvertisementUpdateDtOpenForm'] = $advertisementUpdateDtOpenForm->createView();
                $this->gvars['AdvertisementUpdateAdressForm'] = $advertisementUpdateAdressForm->createView();
                $this->gvars['AdvertisementUpdatePriceForm'] = $advertisementUpdatePriceForm->createView();
                $this->gvars['AdvertisementUpdateTypeAvisForm'] = $advertisementUpdateTypeAvisForm->createView();
                $this->gvars['AdvertisementUpdateAddRefForm'] = $advertisementUpdateAddRefForm->createView();
                $this->gvars['AdvertisementUpdateStatusForm'] = $advertisementUpdateStatusForm->createView();
                $this->gvars['AdvertisementUpdateSourceForm'] = $advertisementUpdateSourceForm->createView();

                $this->gvars['pagetitle'] = $this->translate('pagetitle.aoAdvertisement.edit', array(
                    '%advertisement%' => $advertisement->getRef()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.aoAdvertisement.edit.txt', array(
                    '%advertisement%' => $advertisement->getRef()
                ));

                return $this->renderResponse('AoAdminBundle:AoAdvertisement:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }
}

