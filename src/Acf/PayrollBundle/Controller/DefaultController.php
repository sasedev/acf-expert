<?php

namespace Acf\PayrollBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AcfPayrollBundle:Default:index.html.twig');
    }
}
