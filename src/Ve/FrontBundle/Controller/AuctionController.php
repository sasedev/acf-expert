<?php
namespace Ve\FrontBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

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
        $this->gvars['menu_active'] = 'vehome';
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
            $urlFrom = $this->generateUrl('ve_front_homepage');
        }
        $em = $this->getEntityManager();
        try {
            $auction = $em->getRepository('AcfDataBundle:AoAuction')->find($uid);

            if (null == $auction) {
                $this->flashMsgSession('warning', $this->translate('AoAuction.edit.notfound'));
            } else {

                $this->gvars['auction'] = $auction;

                $this->gvars['pagetitle'] = $this->translate('AoAuction') . ' : ' . $auction->getRef();
                $this->gvars['pagetitle_txt'] = $this->translate('AoAuction') . ' : ' . $auction->getRef();

                return $this->renderResponse('VeFrontBundle:AoAuction:edit.html.twig', $this->gvars);
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
            $urlFrom = $this->generateUrl('ve_front_homepage');
        }
        $em = $this->getEntityManager();
        try {
            $auction = $em->getRepository('AcfDataBundle:AoAuction')->find($uid);

            if (null == $auction) {
                $this->flashMsgSession('warning', $this->translate('AoAuction.edit.notfound'));
            } else {

                if ($this->container->has('profiler')) {
                    $this->container->get('profiler')->disable();
                }

                $this->gvars['auction'] = $auction;

                $this->gvars['pagetitle'] = $this->translate('AoAuction') . ' : ' . $auction->getRef();
                $this->gvars['pagetitle_txt'] = $this->translate('AoAuction') . ' : ' . $auction->getRef();

                // return $this->renderResponse('VeFrontBundle:AoAuction:pdf.html.twig', $this->gvars);

                $html = $this->renderView('VeFrontBundle:AoAuction:pdf.html.twig', $this->gvars);

                $mpdf = new Mpdf(array(
                    'mode' => 'utf-8',
                    'format' => 'A4',
                    'CSSselectMedia' => 'screen'
                ));
                $mpdf->WriteHTML($html);

                $result = $mpdf->Output(null, Destination::STRING_RETURN);

                return new \TFox\MpdfPortBundle\Response\PDFResponse($result, $auction->getRef() . '.pdf"', 200, array(
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $auction->getRef() . '.pdf"'
                ));
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }

        return $this->redirect($urlFrom);
    }
}

