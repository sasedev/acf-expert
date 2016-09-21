<?php
namespace Acf\SecurityBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class MyOrderController extends BaseController
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
    $this->gvars['menu_active'] = 'shopping';
  }

  public function indexAction()
  {
    $sc = $this->getSecurityTokenStorage();
    $user = $sc->getToken()->getUser();
  }
}