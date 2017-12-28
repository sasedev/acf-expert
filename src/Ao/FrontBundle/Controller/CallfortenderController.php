<?php
namespace Ao\FrontBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

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
        $this->gvars['menu_active'] = 'aohome';
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
            $urlFrom = $this->generateUrl('ao_front_homepage');
        }
        $em = $this->getEntityManager();
        try {
            $callfortender = $em->getRepository('AcfDataBundle:AoCallfortender')->find($uid);

            if (null == $callfortender) {
                $this->flashMsgSession('warning', $this->translate('AoCallfortender.edit.notfound'));
            } else {

                $this->gvars['callfortender'] = $callfortender;

                $this->gvars['pagetitle'] = $this->translate('AoCallfortender') . ' : ' . $callfortender->getRef();
                $this->gvars['pagetitle_txt'] = $this->translate('AoCallfortender') . ' : ' . $callfortender->getRef();

                return $this->renderResponse('AoFrontBundle:AoCallfortender:edit.html.twig', $this->gvars);
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
    public function pdfAction($uid)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('ao_front_homepage');
        }
        $em = $this->getEntityManager();
        try {
            $callfortender = $em->getRepository('AcfDataBundle:AoCallfortender')->find($uid);

            if (null == $callfortender) {
                $this->flashMsgSession('warning', $this->translate('AoCallfortender.edit.notfound'));
            } else {

                if ($this->container->has('profiler')) {
                    $this->container->get('profiler')->disable();
                }

                $this->gvars['callfortender'] = $callfortender;

                $this->gvars['pagetitle'] = $this->translate('AoCallfortender') . ' : ' . $callfortender->getRef();
                $this->gvars['pagetitle_txt'] = $this->translate('AoCallfortender') . ' : ' . $callfortender->getRef();

                // return $this->renderResponse('AoFrontBundle:AoCallfortender:pdf.html.twig', $this->gvars);

                $html = $this->renderView('AoFrontBundle:AoCallfortender:pdf.html.twig', $this->gvars);

                $mpdf = new Mpdf(array(
                    'mode' => 'utf-8',
                    'format' => 'A4',
                    'CSSselectMedia' => 'screen'
                ));
                $mpdf->WriteHTML($html);

                $result = $mpdf->Output(null, Destination::STRING_RETURN);

                return new \TFox\MpdfPortBundle\Response\PDFResponse($result, $callfortender->getRef() . '.pdf"', 200, array(
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $callfortender->getRef() . '.pdf"'
                ));

                /*
                 * return new Response($pdfGenerator->generatePDF($html), 200, array(
                 * 'Content-Type' => 'application/pdf',
                 * 'Content-Disposition' => 'inline; filename="out.pdf"'
                 * ));
                 */
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }
}

