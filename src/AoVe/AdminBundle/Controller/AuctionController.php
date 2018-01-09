<?php
namespace AoVe\AdminBundle\Controller;

use Acf\DataBundle\Entity\AoAuction;
use Sasedev\Commons\SharedBundle\Controller\BaseController;
use AoVe\AdminBundle\Form\AoAuction\NewTForm as AoAuctionNewTForm;
use AoVe\AdminBundle\Form\AoAuction\UpdateImgTForm as AoAuctionUpdateImgTForm;
use AoVe\AdminBundle\Form\AoAuction\UpdateDtPublicationTForm as AoAuctionUpdateDtPublicationTForm;
use AoVe\AdminBundle\Form\AoAuction\UpdateCountryTForm as AoAuctionUpdateCountryTForm;
use AoVe\AdminBundle\Form\AoAuction\UpdateDescriptionTForm as AoAuctionUpdateDescriptionTForm;
use AoVe\AdminBundle\Form\AoAuction\UpdateCompanyTForm as AoAuctionUpdateCompanyTForm;
use AoVe\AdminBundle\Form\AoAuction\UpdateNatureTForm as AoAuctionUpdateNatureTForm;
use AoVe\AdminBundle\Form\AoAuction\UpdateDtEndTForm as AoAuctionUpdateDtEndTForm;
use AoVe\AdminBundle\Form\AoAuction\UpdateDtOpenTForm as AoAuctionUpdateDtOpenTForm;
use AoVe\AdminBundle\Form\AoAuction\UpdateAdressTForm as AoAuctionUpdateAdressTForm;
use AoVe\AdminBundle\Form\AoAuction\UpdatePriceTForm as AoAuctionUpdatePriceTForm;
use AoVe\AdminBundle\Form\AoAuction\UpdateAddRefTForm as AoAuctionUpdateAddRefTForm;
use AoVe\AdminBundle\Form\AoAuction\UpdateStatusTForm as AoAuctionUpdateStatusTForm;
use AoVe\AdminBundle\Form\AoAuction\UpdateSourceTForm as AoAuctionUpdateSourceTForm;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class AuctionController extends BaseController
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
        $this->gvars['menu_active'] = 'aoauction';
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addGetAction()
    {
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('aove_admin_homepage'));
        }
        $auction = new AoAuction();
        $auctionNewForm = $this->createForm(AoAuctionNewTForm::class, $auction);
        $this->gvars['auction'] = $auction;
        $this->gvars['AuctionNewForm'] = $auctionNewForm->createView();

        $this->gvars['pagetitle'] = $this->translate('pagetitle.aoAuction.add');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.aoAuction.add.txt');
        $this->gvars['smenu_active'] = 'add';

        return $this->renderResponse('AoVeAdminBundle:AoAuction:add.html.twig', $this->gvars);
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addPostAction()
    {
        if (!$this->hasRole('ROLE_SUPERADMIN')) {
            return $this->redirect($this->generateUrl('aove_admin_homepage'));
        }
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            return $this->redirect($this->generateUrl('aove_admin_auction_addGet'));
        }

        $auction = new AoAuction();
        $auctionNewForm = $this->createForm(AoAuctionNewTForm::class, $auction);

        $request = $this->getRequest();
        $reqData = $request->request->all();

        if (isset($reqData['AoAuctionNewForm'])) {
            $auctionNewForm->handleRequest($request);
            if ($auctionNewForm->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($auction);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('AoAuction.add.success', array(
                    '%auction%' => $auction->getRef()
                )));

                return $this->redirect($this->generateUrl('aove_admin_auction_editGet', array(
                    'uid' => $auction->getId()
                )));
            } else {
                $this->flashMsgSession('error', $this->translate('AoAuction.add.failure'));
            }
        }

        $this->gvars['auction'] = $auction;
        $this->gvars['AuctionNewForm'] = $auctionNewForm->createView();

        $this->gvars['pagetitle'] = $this->translate('pagetitle.aoAuction.add');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.aoAuction.add.txt');
        $this->gvars['smenu_active'] = 'add';

        return $this->renderResponse('AoVeAdminBundle:AoAuction:add.html.twig', $this->gvars);
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
            $urlFrom = $this->generateUrl('aove_admin_homepage');
        }
        $em = $this->getEntityManager();
        try {
            $auction = $em->getRepository('AcfDataBundle:AoAuction')->find($uid);

            if (null == $auction) {
                $this->flashMsgSession('warning', $this->translate('AoAuction.delete.notfound'));
            } else {
                $em->remove($auction);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('AoAuction.delete.success', array(
                    '%auction%' => $auction->getRef()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('AoAuction.delete.failure'));
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
            $urlFrom = $this->generateUrl('aove_admin_homepage');
        }
        $em = $this->getEntityManager();
        try {
            $auction = $em->getRepository('AcfDataBundle:AoAuction')->find($uid);

            if (null == $auction) {
                $this->flashMsgSession('warning', $this->translate('AoAuction.edit.notfound'));
            } else {
                $auctionUpdateImgForm = $this->createForm(AoAuctionUpdateImgTForm::class, $auction);
                $auctionUpdateDtPublicationForm = $this->createForm(AoAuctionUpdateDtPublicationTForm::class, $auction);
                $auctionUpdateCountryForm = $this->createForm(AoAuctionUpdateCountryTForm::class, $auction);
                $auctionUpdateDescriptionForm = $this->createForm(AoAuctionUpdateDescriptionTForm::class, $auction);
                $auctionUpdateCompanyForm = $this->createForm(AoAuctionUpdateCompanyTForm::class, $auction);
                $auctionUpdateNatureForm = $this->createForm(AoAuctionUpdateNatureTForm::class, $auction);
                $auctionUpdateDtEndForm = $this->createForm(AoAuctionUpdateDtEndTForm::class, $auction);
                $auctionUpdateDtOpenForm = $this->createForm(AoAuctionUpdateDtOpenTForm::class, $auction);
                $auctionUpdateAdressForm = $this->createForm(AoAuctionUpdateAdressTForm::class, $auction);
                $auctionUpdatePriceForm = $this->createForm(AoAuctionUpdatePriceTForm::class, $auction);
                $auctionUpdateAddRefForm = $this->createForm(AoAuctionUpdateAddRefTForm::class, $auction);
                $auctionUpdateStatusForm = $this->createForm(AoAuctionUpdateStatusTForm::class, $auction);
                $auctionUpdateSourceForm = $this->createForm(AoAuctionUpdateSourceTForm::class, $auction);

                $this->gvars['auction'] = $auction;

                $this->gvars['AuctionUpdateImgForm'] = $auctionUpdateImgForm->createView();
                $this->gvars['AuctionUpdateDtPublicationForm'] = $auctionUpdateDtPublicationForm->createView();
                $this->gvars['AuctionUpdateCountryForm'] = $auctionUpdateCountryForm->createView();
                $this->gvars['AuctionUpdateDescriptionForm'] = $auctionUpdateDescriptionForm->createView();
                $this->gvars['AuctionUpdateCompanyForm'] = $auctionUpdateCompanyForm->createView();
                $this->gvars['AuctionUpdateNatureForm'] = $auctionUpdateNatureForm->createView();
                $this->gvars['AuctionUpdateDtEndForm'] = $auctionUpdateDtEndForm->createView();
                $this->gvars['AuctionUpdateDtOpenForm'] = $auctionUpdateDtOpenForm->createView();
                $this->gvars['AuctionUpdateAdressForm'] = $auctionUpdateAdressForm->createView();
                $this->gvars['AuctionUpdatePriceForm'] = $auctionUpdatePriceForm->createView();
                $this->gvars['AuctionUpdateAddRefForm'] = $auctionUpdateAddRefForm->createView();
                $this->gvars['AuctionUpdateStatusForm'] = $auctionUpdateStatusForm->createView();
                $this->gvars['AuctionUpdateSourceForm'] = $auctionUpdateSourceForm->createView();

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $nextAuction = $em->getRepository('AcfDataBundle:AoAuction')->getNextById($auction);
                $this->gvars['next'] = $nextAuction;

                $prevAuction = $em->getRepository('AcfDataBundle:AoAuction')->getPrevById($auction);
                $this->gvars['prev'] = $prevAuction;

                $this->gvars['pagetitle'] = $this->translate('pagetitle.aoAuction.edit', array(
                    '%auction%' => $auction->getRef()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.aoAuction.edit.txt', array(
                    '%auction%' => $auction->getRef()
                ));

                return $this->renderResponse('AoVeAdminBundle:AoAuction:edit.html.twig', $this->gvars);
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
            $urlFrom = $this->generateUrl('aove_admin_homepage');
        }
        $em = $this->getEntityManager();
        try {
            $auction = $em->getRepository('AcfDataBundle:AoAuction')->find($uid);

            if (null == $auction) {
                $this->flashMsgSession('warning', $this->translate('AoAuction.edit.notfound'));
            } else {
                $auctionUpdateImgForm = $this->createForm(AoAuctionUpdateImgTForm::class, $auction);
                $auctionUpdateDtPublicationForm = $this->createForm(AoAuctionUpdateDtPublicationTForm::class, $auction);
                $auctionUpdateCountryForm = $this->createForm(AoAuctionUpdateCountryTForm::class, $auction);
                $auctionUpdateDescriptionForm = $this->createForm(AoAuctionUpdateDescriptionTForm::class, $auction);
                $auctionUpdateCompanyForm = $this->createForm(AoAuctionUpdateCompanyTForm::class, $auction);
                $auctionUpdateNatureForm = $this->createForm(AoAuctionUpdateNatureTForm::class, $auction);
                $auctionUpdateDtEndForm = $this->createForm(AoAuctionUpdateDtEndTForm::class, $auction);
                $auctionUpdateDtOpenForm = $this->createForm(AoAuctionUpdateDtOpenTForm::class, $auction);
                $auctionUpdateAdressForm = $this->createForm(AoAuctionUpdateAdressTForm::class, $auction);
                $auctionUpdatePriceForm = $this->createForm(AoAuctionUpdatePriceTForm::class, $auction);
                $auctionUpdateAddRefForm = $this->createForm(AoAuctionUpdateAddRefTForm::class, $auction);
                $auctionUpdateStatusForm = $this->createForm(AoAuctionUpdateStatusTForm::class, $auction);
                $auctionUpdateSourceForm = $this->createForm(AoAuctionUpdateSourceTForm::class, $auction);

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                if (isset($reqData['AoAuctionUpdateImgForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $auctionUpdateImgForm->handleRequest($request);
                    if ($auctionUpdateImgForm->isValid()) {
                        $imgFile = $auctionUpdateImgForm['image']->getData();

                        if (null != $imgFile) {
                            $imgDir = $this->getParameter('kernel.root_dir') . '/../web/res/Ve';
                            $fileName = sha1(uniqid(mt_rand(), true)) . '.' . strtolower($imgFile->getClientOriginalExtension());
                            $imgFile->move($imgDir, $fileName);
                            $auction->setImg($fileName);
                        } else {
                            $auction->setImg(null);
                        }
                        $em->persist($auction);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoAuction.edit.success', array(
                            '%auction%' => $auction->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_auction_editGet', array(
                            'uid' => $auction->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoAuction.edit.failure'));
                    }
                } elseif (isset($reqData['AoAuctionUpdateDtPublicationForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $auctionUpdateDtPublicationForm->handleRequest($request);
                    if ($auctionUpdateDtPublicationForm->isValid()) {
                        $em->persist($auction);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoAuction.edit.success', array(
                            '%auction%' => $auction->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_auction_editGet', array(
                            'uid' => $auction->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoAuction.edit.failure'));
                    }
                } elseif (isset($reqData['AoAuctionUpdateCountryForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $auctionUpdateCountryForm->handleRequest($request);
                    if ($auctionUpdateCountryForm->isValid()) {
                        $em->persist($auction);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoAuction.edit.success', array(
                            '%auction%' => $auction->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_auction_editGet', array(
                            'uid' => $auction->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoAuction.edit.failure'));
                    }
                } elseif (isset($reqData['AoAuctionUpdateDescriptionForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $auctionUpdateDescriptionForm->handleRequest($request);
                    if ($auctionUpdateDescriptionForm->isValid()) {
                        $em->persist($auction);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoAuction.edit.success', array(
                            '%auction%' => $auction->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_auction_editGet', array(
                            'uid' => $auction->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoAuction.edit.failure'));
                    }
                } elseif (isset($reqData['AoAuctionUpdateCompanyForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $auctionUpdateCompanyForm->handleRequest($request);
                    if ($auctionUpdateCompanyForm->isValid()) {
                        $em->persist($auction);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoAuction.edit.success', array(
                            '%auction%' => $auction->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_auction_editGet', array(
                            'uid' => $auction->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoAuction.edit.failure'));
                    }
                } elseif (isset($reqData['AoAuctionUpdateNatureForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $auctionUpdateNatureForm->handleRequest($request);
                    if ($auctionUpdateNatureForm->isValid()) {
                        $em->persist($auction);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoAuction.edit.success', array(
                            '%auction%' => $auction->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_auction_editGet', array(
                            'uid' => $auction->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoAuction.edit.failure'));
                    }
                } elseif (isset($reqData['AoAuctionUpdateDtEndForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $auctionUpdateDtEndForm->handleRequest($request);
                    if ($auctionUpdateDtEndForm->isValid()) {
                        $em->persist($auction);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoAuction.edit.success', array(
                            '%auction%' => $auction->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_auction_editGet', array(
                            'uid' => $auction->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoAuction.edit.failure'));
                    }
                } elseif (isset($reqData['AoAuctionUpdateDtOpenForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $auctionUpdateDtOpenForm->handleRequest($request);
                    if ($auctionUpdateDtOpenForm->isValid()) {
                        $em->persist($auction);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoAuction.edit.success', array(
                            '%auction%' => $auction->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_auction_editGet', array(
                            'uid' => $auction->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoAuction.edit.failure'));
                    }
                } elseif (isset($reqData['AoAuctionUpdateAdressForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $auctionUpdateAdressForm->handleRequest($request);
                    if ($auctionUpdateAdressForm->isValid()) {
                        $em->persist($auction);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoAuction.edit.success', array(
                            '%auction%' => $auction->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_auction_editGet', array(
                            'uid' => $auction->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoAuction.edit.failure'));
                    }
                } elseif (isset($reqData['AoAuctionUpdatePriceForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $auctionUpdatePriceForm->handleRequest($request);
                    if ($auctionUpdatePriceForm->isValid()) {
                        $em->persist($auction);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoAuction.edit.success', array(
                            '%auction%' => $auction->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_auction_editGet', array(
                            'uid' => $auction->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoAuction.edit.failure'));
                    }
                } elseif (isset($reqData['AoAuctionUpdateAddRefForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $auctionUpdateAddRefForm->handleRequest($request);
                    if ($auctionUpdateAddRefForm->isValid()) {
                        $em->persist($auction);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoAuction.edit.success', array(
                            '%auction%' => $auction->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_auction_editGet', array(
                            'uid' => $auction->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoAuction.edit.failure'));
                    }
                } elseif (isset($reqData['AoAuctionUpdateSourceForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $auctionUpdateSourceForm->handleRequest($request);
                    if ($auctionUpdateSourceForm->isValid()) {
                        $em->persist($auction);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoAuction.edit.success', array(
                            '%auction%' => $auction->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_auction_editGet', array(
                            'uid' => $auction->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoAuction.edit.failure'));
                    }
                } elseif (isset($reqData['AoAuctionUpdateStatusForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $auctionUpdateStatusForm->handleRequest($request);
                    if ($auctionUpdateStatusForm->isValid()) {
                        $em->persist($auction);
                        $em->flush();

                        $this->flashMsgSession('success', $this->translate('AoAuction.edit.success', array(
                            '%auction%' => $auction->getRef()
                        )));

                        return $this->redirect($this->generateUrl('aove_admin_auction_editGet', array(
                            'uid' => $auction->getId()
                        )));
                    } else {
                        $this->flashMsgSession('error', $this->translate('AoAuction.edit.failure'));
                    }
                }

                $this->gvars['auction'] = $auction;

                $this->gvars['AuctionUpdateImgForm'] = $auctionUpdateImgForm->createView();
                $this->gvars['AuctionUpdateDtPublicationForm'] = $auctionUpdateDtPublicationForm->createView();
                $this->gvars['AuctionUpdateCountryForm'] = $auctionUpdateCountryForm->createView();
                $this->gvars['AuctionUpdateDescriptionForm'] = $auctionUpdateDescriptionForm->createView();
                $this->gvars['AuctionUpdateCompanyForm'] = $auctionUpdateCompanyForm->createView();
                $this->gvars['AuctionUpdateNatureForm'] = $auctionUpdateNatureForm->createView();
                $this->gvars['AuctionUpdateDtEndForm'] = $auctionUpdateDtEndForm->createView();
                $this->gvars['AuctionUpdateDtOpenForm'] = $auctionUpdateDtOpenForm->createView();
                $this->gvars['AuctionUpdateAdressForm'] = $auctionUpdateAdressForm->createView();
                $this->gvars['AuctionUpdatePriceForm'] = $auctionUpdatePriceForm->createView();
                $this->gvars['AuctionUpdateAddRefForm'] = $auctionUpdateAddRefForm->createView();
                $this->gvars['AuctionUpdateStatusForm'] = $auctionUpdateStatusForm->createView();
                $this->gvars['AuctionUpdateSourceForm'] = $auctionUpdateSourceForm->createView();

                $nextAuction = $em->getRepository('AcfDataBundle:AoAuction')->getNextById($auction);
                $this->gvars['next'] = $nextAuction;

                $prevAuction = $em->getRepository('AcfDataBundle:AoAuction')->getPrevById($auction);
                $this->gvars['prev'] = $prevAuction;

                $this->gvars['pagetitle'] = $this->translate('pagetitle.aoAuction.edit', array(
                    '%auction%' => $auction->getRef()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.aoAuction.edit.txt', array(
                    '%auction%' => $auction->getRef()
                ));

                return $this->renderResponse('AoVeAdminBundle:AoAuction:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }
}

