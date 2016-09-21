<?php
namespace Acf\AdminBundle\Controller;

use Acf\DataBundle\Entity\OnlineTaxe;
use Acf\AdminBundle\Form\Taxe\NewTForm as TaxeNewTForm;
use Acf\AdminBundle\Form\Taxe\UpdateLabelTForm as TaxeUpdateLabelTForm;
use Acf\AdminBundle\Form\Taxe\UpdateValueTForm as TaxeUpdateValueTForm;
use Acf\AdminBundle\Form\Taxe\UpdatePriorityTForm as TaxeUpdatePriorityTForm;
use Acf\AdminBundle\Form\Taxe\UpdateVisibleTForm as TaxeUpdateVisibleTForm;
use Acf\AdminBundle\Form\Taxe\UpdateTypeTForm as TaxeUpdateTypeTForm;
use Sasedev\Commons\SharedBundle\Controller\BaseController;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class TaxeController extends BaseController
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
    $this->gvars['menu_active'] = 'taxe';
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
    $taxes = $em->getRepository('AcfDataBundle:OnlineTaxe')->getAll();
    $this->gvars['taxes'] = $taxes;

    $this->gvars['smenu_active'] = 'list';
    $this->gvars['pagetitle'] = $this->translate('pagetitle.taxe.list');
    $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.taxe.list.txt');

    return $this->renderResponse('AcfAdminBundle:Taxe:list.html.twig', $this->gvars);
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
    $taxe = new OnlineTaxe();
    $taxeNewForm = $this->createForm(TaxeNewTForm::class, $taxe);
    $this->gvars['taxe'] = $taxe;
    $this->gvars['TaxeNewForm'] = $taxeNewForm->createView();

    $this->gvars['pagetitle'] = $this->translate('pagetitle.taxe.add');
    $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.taxe.add.txt');
    $this->gvars['smenu_active'] = 'add';

    return $this->renderResponse('AcfAdminBundle:Taxe:add.html.twig', $this->gvars);
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
      return $this->redirect($this->generateUrl('_admin_taxe_addGet'));
    }

    $taxe = new OnlineTaxe();
    $taxeNewForm = $this->createForm(TaxeNewTForm::class, $taxe);
    $this->gvars['taxe'] = $taxe;

    $request = $this->getRequest();
    $reqData = $request->request->all();

    if (isset($reqData['TaxeNewForm'])) {
      $taxeNewForm->handleRequest($request);
      if ($taxeNewForm->isValid()) {
        $em = $this->getEntityManager();
        $em->persist($taxe);
        $em->flush();
        $this->flashMsgSession('success', $this->translate('Taxe.add.success', array(
          '%taxe%' => $taxe->getLabel()
        )));

        return $this->redirect($this->generateUrl('_admin_taxe_editGet', array(
          'uid' => $taxe->getId()
        )));
      } else {
        $this->flashMsgSession('error', $this->translate('Taxe.add.failure'));
      }
    }
    $this->gvars['TaxeNewForm'] = $taxeNewForm->createView();

    $this->gvars['pagetitle'] = $this->translate('pagetitle.taxe.add');
    $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.taxe.add.txt');
    $this->gvars['smenu_active'] = 'add';

    return $this->renderResponse('AcfAdminBundle:Taxe:add.html.twig', $this->gvars);
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
      $urlFrom = $this->generateUrl('_admin_taxe_list');
    }
    $em = $this->getEntityManager();
    try {
      $taxe = $em->getRepository('AcfDataBundle:OnlineTaxe')->find($uid);

      if (null == $taxe) {
        $this->flashMsgSession('warning', $this->translate('Taxe.delete.notfound'));
      } else {
        $em->remove($taxe);
        $em->flush();

        $this->flashMsgSession('success', $this->translate('Taxe.delete.success', array(
          '%taxe%' => $taxe->getLabel()
        )));
      }
    } catch (\Exception $e) {
      $logger = $this->getLogger();
      $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());

      $this->flashMsgSession('error', $this->translate('Taxe.delete.failure'));
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
      $urlFrom = $this->generateUrl('_admin_taxe_list');
    }

    $em = $this->getEntityManager();
    try {
      $taxe = $em->getRepository('AcfDataBundle:OnlineTaxe')->find($uid);

      if (null == $taxe) {
        $this->flashMsgSession('warning', $this->translate('Taxe.edit.notfound'));
      } else {
        $taxeUpdateLabelForm = $this->createForm(TaxeUpdateLabelTForm::class, $taxe);
        $taxeUpdatePriorityForm = $this->createForm(TaxeUpdatePriorityTForm::class, $taxe);
        $taxeUpdateVisibleForm = $this->createForm(TaxeUpdateVisibleTForm::class, $taxe);
        $taxeUpdateTypeForm = $this->createForm(TaxeUpdateTypeTForm::class, $taxe);
        $taxeUpdateValueForm = $this->createForm(TaxeUpdateValueTForm::class, $taxe);

        $this->gvars['taxe'] = $taxe;
        $this->gvars['TaxeUpdatePriorityForm'] = $taxeUpdatePriorityForm->createView();
        $this->gvars['TaxeUpdateVisibleForm'] = $taxeUpdateVisibleForm->createView();
        $this->gvars['TaxeUpdateTypeForm'] = $taxeUpdateTypeForm->createView();
        $this->gvars['TaxeUpdateLabelForm'] = $taxeUpdateLabelForm->createView();
        $this->gvars['TaxeUpdateValueForm'] = $taxeUpdateValueForm->createView();

        $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 1);
        $this->getSession()->remove('tabActive');

        $this->gvars['pagetitle'] = $this->translate('pagetitle.taxe.edit', array(
          '%taxe%' => $taxe->getLabel()
        ));
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.taxe.edit.txt', array(
          '%taxe%' => $taxe->getLabel()
        ));

        return $this->renderResponse('AcfAdminBundle:Taxe:edit.html.twig', $this->gvars);
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
      return $this->redirect($this->generateUrl('_admin_taxe_list'));
    }

    $em = $this->getEntityManager();
    try {
      $taxe = $em->getRepository('AcfDataBundle:OnlineTaxe')->find($uid);

      if (null == $taxe) {
        $this->flashMsgSession('warning', $this->translate('Taxe.edit.notfound'));
      } else {
        $taxeUpdatePriorityForm = $this->createForm(TaxeUpdatePriorityTForm::class, $taxe);
        $taxeUpdateVisibleForm = $this->createForm(TaxeUpdateVisibleTForm::class, $taxe);
        $taxeUpdateTypeForm = $this->createForm(TaxeUpdateTypeTForm::class, $taxe);
        $taxeUpdateLabelForm = $this->createForm(TaxeUpdateLabelTForm::class, $taxe);
        $taxeUpdateValueForm = $this->createForm(TaxeUpdateValueTForm::class, $taxe);

        $this->gvars['tabActive'] = $this->getSession()->get('tabActive', 2);
        $this->getSession()->remove('tabActive');

        $request = $this->getRequest();
        $reqData = $request->request->all();

        if (isset($reqData['TaxeUpdatePriorityForm'])) {
          $this->gvars['tabActive'] = 2;
          $this->getSession()->set('tabActive', 2);
          $taxeUpdatePriorityForm->handleRequest($request);
          if ($taxeUpdatePriorityForm->isValid()) {
            $em->persist($taxe);
            $em->flush();
            $this->flashMsgSession('success', $this->translate('Taxe.edit.success', array(
              '%taxe%' => $taxe->getLabel()
            )));

            return $this->redirect($urlFrom);
          } else {
            $em->refresh($taxe);

            $this->flashMsgSession('error', $this->translate('Taxe.edit.failure', array(
              '%taxe%' => $taxe->getLabel()
            )));
          }
        } elseif (isset($reqData['TaxeUpdateVisibleForm'])) {
          $this->gvars['tabActive'] = 2;
          $this->getSession()->set('tabActive', 2);
          $taxeUpdateVisibleForm->handleRequest($request);
          if ($taxeUpdateVisibleForm->isValid()) {
            $em->persist($taxe);
            $em->flush();
            $this->flashMsgSession('success', $this->translate('Taxe.edit.success', array(
              '%taxe%' => $taxe->getLabel()
            )));

            return $this->redirect($urlFrom);
          } else {
            $em->refresh($taxe);

            $this->flashMsgSession('error', $this->translate('Taxe.edit.failure', array(
              '%taxe%' => $taxe->getLabel()
            )));
          }
        } elseif (isset($reqData['TaxeUpdateTypeForm'])) {
          $this->gvars['tabActive'] = 2;
          $this->getSession()->set('tabActive', 2);
          $taxeUpdateTypeForm->handleRequest($request);
          if ($taxeUpdateTypeForm->isValid()) {
            $em->persist($taxe);
            $em->flush();
            $this->flashMsgSession('success', $this->translate('Taxe.edit.success', array(
              '%taxe%' => $taxe->getLabel()
            )));

            return $this->redirect($urlFrom);
          } else {
            $em->refresh($taxe);

            $this->flashMsgSession('error', $this->translate('Taxe.edit.failure', array(
              '%taxe%' => $taxe->getLabel()
            )));
          }
        } elseif (isset($reqData['TaxeUpdateLabelForm'])) {
          $this->gvars['tabActive'] = 2;
          $this->getSession()->set('tabActive', 2);
          $taxeUpdateLabelForm->handleRequest($request);
          if ($taxeUpdateLabelForm->isValid()) {
            $em->persist($taxe);
            $em->flush();
            $this->flashMsgSession('success', $this->translate('Taxe.edit.success', array(
              '%taxe%' => $taxe->getLabel()
            )));

            return $this->redirect($urlFrom);
          } else {
            $em->refresh($taxe);

            $this->flashMsgSession('error', $this->translate('Taxe.edit.failure', array(
              '%taxe%' => $taxe->getLabel()
            )));
          }
        } elseif (isset($reqData['TaxeUpdateValueForm'])) {
          $this->gvars['tabActive'] = 2;
          $this->getSession()->set('tabActive', 2);
          $taxeUpdateValueForm->handleRequest($request);
          if ($taxeUpdateValueForm->isValid()) {
            $em->persist($taxe);
            $em->flush();
            $this->flashMsgSession('success', $this->translate('Taxe.edit.success', array(
              '%taxe%' => $taxe->getLabel()
            )));

            return $this->redirect($urlFrom);
          } else {
            $em->refresh($taxe);

            $this->flashMsgSession('error', $this->translate('Taxe.edit.failure', array(
              '%taxe%' => $taxe->getLabel()
            )));
          }
        }

        $this->gvars['taxe'] = $taxe;
        $this->gvars['TaxeUpdatePriorityForm'] = $taxeUpdatePriorityForm->createView();
        $this->gvars['TaxeUpdateVisibleForm'] = $taxeUpdateVisibleForm->createView();
        $this->gvars['TaxeUpdateTypeForm'] = $taxeUpdateTypeForm->createView();
        $this->gvars['TaxeUpdateLabelForm'] = $taxeUpdateLabelForm->createView();
        $this->gvars['TaxeUpdateValueForm'] = $taxeUpdateValueForm->createView();

        $this->gvars['pagetitle'] = $this->translate('pagetitle.taxe.edit', array(
          '%taxe%' => $taxe->getLabel()
        ));
        $this->gvars['pagetitle_txt'] = $this->translate('pagetitle.taxe.edit.txt', array(
          '%taxe%' => $taxe->getLabel()
        ));

        return $this->renderResponse('AcfAdminBundle:Taxe:edit.html.twig', $this->gvars);
      }
    } catch (\Exception $e) {
      $logger = $this->getLogger();
      $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
    }

    return $this->redirect($urlFrom);
  }
}