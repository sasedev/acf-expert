<?php
namespace Acf\AdminBundle\Controller;

use Acf\DataBundle\Entity\OnlineProduct;
use Acf\AdminBundle\Form\Product\NewTForm as ProductNewTForm;
use Acf\AdminBundle\Form\Product\UpdateLabelTForm as ProductUpdateLabelTForm;
use Acf\AdminBundle\Form\Product\UpdateTitleTForm as ProductUpdateTitleTForm;
use Acf\AdminBundle\Form\Product\UpdateDescriptionTForm as ProductUpdateDescriptionTForm;
use Acf\AdminBundle\Form\Product\UpdatePriceTForm as ProductUpdatePriceTForm;
use Acf\AdminBundle\Form\Product\UpdateVatTForm as ProductUpdateVatTForm;
use Acf\AdminBundle\Form\Product\UpdateLockoutTForm as ProductUpdateLockoutTForm;
use Sasedev\Commons\SharedBundle\Controller\BaseController;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class ProductController extends BaseController
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
        $this->gvars['menu_active'] = 'product';
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
        $products = $em->getRepository('AcfDataBundle:OnlineProduct')->getAll();
        $this->gvars['products'] = $products;

        $this->gvars['smenu_active'] = 'list';
        $this->gvars['pagetitle'] = $this->translate('pagetitle.product.list');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.product.list.txt');

        return $this->renderResponse('AcfAdminBundle:Product:list.html.twig', $this->gvars);
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
        $product = new OnlineProduct();
        $productNewForm = $this->createForm(ProductNewTForm::class, $product);
        $this->gvars['product'] = $product;
        $this->gvars['ProductNewForm'] = $productNewForm->createView();

        $this->gvars['pagetitle'] = $this->translate('pagetitle.product.add');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.product.add.txt');
        $this->gvars['smenu_active'] = 'add';

        return $this->renderResponse('AcfAdminBundle:Product:add.html.twig', $this->gvars);
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
            return $this->redirect($this->generateUrl('_admin_product_addGet'));
        }

        $product = new OnlineProduct();
        $productNewForm = $this->createForm(ProductNewTForm::class, $product);
        $this->gvars['product'] = $product;

        $request = $this->getRequest();
        $reqData = $request->request->all();

        if (isset($reqData['ProductNewForm'])) {
            $productNewForm->handleRequest($request);
            if ($productNewForm->isValid()) {
                $em = $this->getEntityManager();
                $em->persist($product);
                $em->flush();
                $this->flashMsgSession('success', $this->translate('Product.add.success', array(
                    '%product%' => $product->getTitle()
                )));

                return $this->redirect($this->generateUrl('_admin_product_editGet', array(
                    'uid' => $product->getId()
                )));
            } else {
                $this->flashMsgSession('error', $this->translate('Product.add.failure'));
            }
        }
        $this->gvars['ProductNewForm'] = $productNewForm->createView();

        $this->gvars['pagetitle'] = $this->translate('pagetitle.product.add');
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.product.add.txt');
        $this->gvars['smenu_active'] = 'add';

        return $this->renderResponse('AcfAdminBundle:Product:add.html.twig', $this->gvars);
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
            $urlFrom = $this->generateUrl('_admin_product_list');
        }
        $em = $this->getEntityManager();
        try {
            $product = $em->getRepository('AcfDataBundle:OnlineProduct')->find($uid);

            if (null == $product) {
                $this->flashMsgSession('warning', $this->translate('Product.delete.notfound'));
            } else {
                $em->remove($product);
                $em->flush();

                $this->flashMsgSession('success', $this->translate('Product.delete.success', array(
                    '%product%' => $product->getId()
                )));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

            $this->flashMsgSession('error', $this->translate('Product.delete.failure'));
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
            $urlFrom = $this->generateUrl('_admin_product_list');
        }

        $em = $this->getEntityManager();
        try {
            $product = $em->getRepository('AcfDataBundle:OnlineProduct')->find($uid);

            if (null == $product) {
                $this->flashMsgSession('warning', $this->translate('Product.edit.notfound'));
            } else {
                $productUpdateLabelForm = $this->createForm(ProductUpdateLabelTForm::class, $product);
                $productUpdateTitleForm = $this->createForm(ProductUpdateTitleTForm::class, $product);
                $productUpdateDescriptionForm = $this->createForm(ProductUpdateDescriptionTForm::class, $product);
                $productUpdateVatForm = $this->createForm(ProductUpdateVatTForm::class, $product);
                $productUpdateLockoutForm = $this->createForm(ProductUpdateLockoutTForm::class, $product);
                $productUpdatePriceForm = $this->createForm(ProductUpdatePriceTForm::class, $product);

                $this->gvars['product'] = $product;
                $this->gvars['ProductUpdateLabelForm'] = $productUpdateLabelForm->createView();
                $this->gvars['ProductUpdateTitleForm'] = $productUpdateTitleForm->createView();
                $this->gvars['ProductUpdateDescriptionForm'] = $productUpdateDescriptionForm->createView();
                $this->gvars['ProductUpdateVatForm'] = $productUpdateVatForm->createView();
                $this->gvars['ProductUpdateLockoutForm'] = $productUpdateLockoutForm->createView();
                $this->gvars['ProductUpdatePriceForm'] = $productUpdatePriceForm->createView();

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
                $this->getSession()->remove('tabActive');

                $this->gvars['pagetitle'] = $this->translate('pagetitle.product.edit', array(
                    '%product%' => $product->getTitle()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.product.edit.txt', array(
                    '%product%' => $product->getTitle()
                ));

                return $this->renderResponse('AcfAdminBundle:Product:edit.html.twig', $this->gvars);
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
            return $this->redirect($this->generateUrl('_admin_product_list'));
        }

        $em = $this->getEntityManager();
        try {
            $product = $em->getRepository('AcfDataBundle:OnlineProduct')->find($uid);

            if (null == $product) {
                $this->flashMsgSession('warning', $this->translate('Product.edit.notfound'));
            } else {
                $productUpdateLabelForm = $this->createForm(ProductUpdateLabelTForm::class, $product);
                $productUpdateTitleForm = $this->createForm(ProductUpdateTitleTForm::class, $product);
                $productUpdateDescriptionForm = $this->createForm(ProductUpdateDescriptionTForm::class, $product);
                $productUpdateVatForm = $this->createForm(ProductUpdateVatTForm::class, $product);
                $productUpdateLockoutForm = $this->createForm(ProductUpdateLockoutTForm::class, $product);
                $productUpdatePriceForm = $this->createForm(ProductUpdatePriceTForm::class, $product);

                $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
                $this->getSession()->remove('tabActive');

                $request = $this->getRequest();
                $reqData = $request->request->all();

                if (isset($reqData['ProductUpdateLabelForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $productUpdateLabelForm->handleRequest($request);
                    if ($productUpdateLabelForm->isValid()) {
                        $em->persist($product);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Product.edit.success', array(
                            '%product%' => $product->getId()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($product);

                        $this->flashMsgSession('error', $this->translate('Product.edit.failure', array(
                            '%product%' => $product->getId()
                        )));
                    }
                } elseif (isset($reqData['ProductUpdateTitleForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $productUpdateTitleForm->handleRequest($request);
                    if ($productUpdateTitleForm->isValid()) {
                        $em->persist($product);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Product.edit.success', array(
                            '%product%' => $product->getId()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($product);

                        $this->flashMsgSession('error', $this->translate('Product.edit.failure', array(
                            '%product%' => $product->getId()
                        )));
                    }
                } elseif (isset($reqData['ProductUpdateDescriptionForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $productUpdateDescriptionForm->handleRequest($request);
                    if ($productUpdateDescriptionForm->isValid()) {
                        $em->persist($product);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Product.edit.success', array(
                            '%product%' => $product->getId()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($product);

                        $this->flashMsgSession('error', $this->translate('Product.edit.failure', array(
                            '%product%' => $product->getId()
                        )));
                    }
                } elseif (isset($reqData['ProductUpdateVatForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $productUpdateVatForm->handleRequest($request);
                    if ($productUpdateVatForm->isValid()) {
                        $em->persist($product);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Product.edit.success', array(
                            '%product%' => $product->getId()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($product);

                        $this->flashMsgSession('error', $this->translate('Product.edit.failure', array(
                            '%product%' => $product->getId()
                        )));
                    }
                } elseif (isset($reqData['ProductUpdateLockoutForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $productUpdateLockoutForm->handleRequest($request);
                    if ($productUpdateLockoutForm->isValid()) {
                        $em->persist($product);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Product.edit.success', array(
                            '%product%' => $product->getId()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($product);

                        $this->flashMsgSession('error', $this->translate('Product.edit.failure', array(
                            '%product%' => $product->getId()
                        )));
                    }
                } elseif (isset($reqData['ProductUpdatePriceForm'])) {
                    $this->gvars['tabActive'] = 2;
                    $this->getSession()->set('tabActive', 2);
                    $productUpdatePriceForm->handleRequest($request);
                    if ($productUpdatePriceForm->isValid()) {
                        $em->persist($product);
                        $em->flush();
                        $this->flashMsgSession('success', $this->translate('Product.edit.success', array(
                            '%product%' => $product->getId()
                        )));

                        return $this->redirect($urlFrom);
                    } else {
                        $em->refresh($product);

                        $this->flashMsgSession('error', $this->translate('Product.edit.failure', array(
                            '%product%' => $product->getId()
                        )));
                    }
                }

                $this->gvars['product'] = $product;
                $this->gvars['ProductUpdateLabelForm'] = $productUpdateLabelForm->createView();
                $this->gvars['ProductUpdateTitleForm'] = $productUpdateTitleForm->createView();
                $this->gvars['ProductUpdateDescriptionForm'] = $productUpdateDescriptionForm->createView();
                $this->gvars['ProductUpdateVatForm'] = $productUpdateVatForm->createView();
                $this->gvars['ProductUpdateLockoutForm'] = $productUpdateLockoutForm->createView();
                $this->gvars['ProductUpdatePriceForm'] = $productUpdatePriceForm->createView();

                $this->gvars['pagetitle'] = $this->translate('pagetitle.product.edit', array(
                    '%product%' => $product->getTitle()
                ));
                $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.product.edit.txt', array(
                    '%product%' => $product->getTitle()
                ));

                return $this->renderResponse('AcfAdminBundle:Product:edit.html.twig', $this->gvars);
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }
}