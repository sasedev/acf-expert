<?php
namespace Ao\FrontBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

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
            $advertisement = $em->getRepository('AcfDataBundle:AoAdvertisement')->find($uid);

            if (null == $advertisement) {
                $this->flashMsgSession('warning', $this->translate('AoAdvertisement.edit.notfound'));
            } else {

                $this->gvars['advertisement'] = $advertisement;

                $this->gvars['pagetitle'] = $this->translate('AoAdvertisement.aoVe.' . $advertisement->getAoVe()) . ' : ' . $advertisement->getRef();
                $this->gvars['pagetitle_txt'] = $this->translate('AoAdvertisement.aoVe.' . $advertisement->getAoVe()) . ' : ' . $advertisement->getRef();

                return $this->renderResponse('AoFrontBundle:AoAdvertisement:edit.html.twig', $this->gvars);
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
            $advertisement = $em->getRepository('AcfDataBundle:AoAdvertisement')->find($uid);

            if (null == $advertisement) {
                $this->flashMsgSession('warning', $this->translate('AoAdvertisement.edit.notfound'));
            } else {

                if ($this->container->has('profiler')) {
                    $this->container->get('profiler')->disable();
                }

                $this->gvars['advertisement'] = $advertisement;

                $this->gvars['pagetitle'] = $this->translate('AoAdvertisement.aoVe.' . $advertisement->getAoVe()) . ' : ' . $advertisement->getRef();
                $this->gvars['pagetitle_txt'] = $this->translate('AoAdvertisement.aoVe.' . $advertisement->getAoVe()) . ' : ' . $advertisement->getRef();

                // return $this->renderResponse('AoFrontBundle:AoAdvertisement:pdf.html.twig', $this->gvars);

                $html = $this->renderView('AoFrontBundle:AoAdvertisement:pdf.html.twig', $this->gvars);

                $mpdf = new Mpdf(array(
                    'mode' => 'utf-8',
                    'format' => 'A4',
                    'CSSselectMedia' => 'screen'
                ));
                $mpdf->WriteHTML($html);

                $result = $mpdf->Output(null, Destination::STRING_RETURN);

                return new \TFox\MpdfPortBundle\Response\PDFResponse($result, $advertisement->getRef() . '.pdf"', 200, array(
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $advertisement->getRef() . '.pdf"'
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

