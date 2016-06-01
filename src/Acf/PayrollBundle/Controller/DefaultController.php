<?php
namespace Acf\PayrollBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 *
 */
class DefaultController extends Controller
{

    /**
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('AcfPayrollBundle:Default:index.html.twig');
    }
}
