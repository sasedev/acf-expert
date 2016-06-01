<?php
namespace Acf\FrontBundle\Controller;

use FeedIo\Adapter\Guzzle\Client as FeedIoClient;
use FeedIo\FeedIo;
use GuzzleHttp\Client as GuzzleClient;
use Psr\Log\NullLogger;
use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Acf\DataBundle\Entity\FeedRead;
use Acf\DataBundle\Entity\GoodLink;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class BaseDocController extends BaseController
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
        $this->gvars['menu_active'] = 'home';
    }

    /**
     *
     * @return Response
     */
    public function indexAction()
    {
        $guzzle = new GuzzleClient(array(
            'timeout' => 30
        ));
        $client = new FeedIoClient($guzzle);

        // second dependency : a PSR-3 logger
        $logger = new NullLogger();

        $feedIo = new FeedIo($client, $logger);

        $em = $this->getEntityManager();
        $feeds = $em->getRepository('AcfDataBundle:FeedRead')->getAll();

        $items = array();

        foreach ($feeds as $feed) {
            try {
                if (null != $feed->getNbrDays() && $feed->getNbrDays() > 0) {
                    $result = $feedIo->readSince($feed->getUrl(), new \DateTime('-' . $feed->getNbrDays() . ' days'));
                } else {
                    $result = $feedIo->read($feed->getUrl());
                }
                $count = 0;
                foreach ($result->getFeed() as $item) {
                    $items[] = $item;
                    $count++;
                    if ($feed->getNbrItems() > 0 && $count >= $feed->getNbrItems()) {
                        break;
                    }
                }
            } catch (\Exception $e) {
                // ne rien faire
            }
        }
        $this->gvars['feeds'] = $items;

        $this->gvars['links'] = $em->getRepository('AcfDataBundle:GoodLink')->getAll();

        $this->gvars['docs'] = $em->getRepository('AcfDataBundle:GoodDoc')->getAll();

        return $this->renderResponse('AcfFrontBundle:Default:bdoc.html.twig', $this->gvars);
    }

    /**
     *
     * @param string $uid
     * @return RedirectResponse
     */
    public function redirectAction($uid)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_front_homepage');
        }
        $em = $this->getEntityManager();
        try {
            $goodLink = $em->getRepository('AcfDataBundle:GoodLink')->find($uid);

            if (null == $goodLink) {
                $logger = $this->getLogger();
                $logger->addError('Lien inconnu');
                $this->flashMsgSession('warning', $this->translate('GoodLink.edit.notfound'));
            } else {
                $goodLink->setNbrClicks($goodLink->getNbrClicks() + 1);
                $em->persist($goodLink);
                $em->flush($goodLink);

                return $this->redirect($goodLink->getUrl());
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
            $this->flashMsgSession('error', $e->getMessage());
            $this->flashMsgSession('warning', $this->translate('GoodLink.edit.notfound'));
        }

        return $this->redirect($urlFrom);
    }

    /**
     *
     * @param string $uid
     * @return StreamedResponse|RedirectResponse
     */
    public function docDlAction($uid)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_front_homepage');
        }
        $em = $this->getEntityManager();
        try {
            $goodDoc = $em->getRepository('AcfDataBundle:GoodDoc')->find($uid);

            if (null == $goodDoc) {
                $logger = $this->getLogger();
                $logger->addError('Document inconnu');
                $this->flashMsgSession('warning', $this->translate('GoodDoc.download.notfound'));
            } else {
                $goodDocDir = $this->getParameter('kernel.root_dir') . '/../web/res/goodDocs';
                $fileName = $goodDoc->getFileName();

                try {
                    $dlFile = new File($goodDocDir . '/' . $fileName);
                    $response = new StreamedResponse(function () use ($dlFile) {
                        $handle = fopen($dlFile->getRealPath(), 'r');
                        while (!feof($handle)) {
                            $buffer = fread($handle, 1024);
                            echo $buffer;
                            flush();
                        }
                        fclose($handle);
                    });

                    $response->headers->set('Content-Type', $goodDoc->getMimeType());
                    $response->headers->set('Cache-Control', '');
                    $response->headers->set('Content-Length', $goodDoc->getSize());
                    $response->headers->set('Last-Modified', gmdate('D, d M Y H:i:s', $goodDoc->getDtUpdate()->getTimestamp()));
                    $fallback = $this->normalize($goodDoc->getTitle());

                    $contentDisposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $goodDoc->getOriginalName(), $fallback);
                    $response->headers->set('Content-Disposition', $contentDisposition);

                    $goodDoc->setNbrDownloads($goodDoc->getNbrDownloads() + 1);
                    $em->persist($goodDoc);
                    $em->flush();

                    return $response;
                } catch (FileNotFoundException $fnfex) {
                    $logger = $this->getLogger();
                    $logger->addError('Fichier introuvable ou autre erreur');
                    $logger->addError($fnfex->getMessage());
                    $this->flashMsgSession('error', $fnfex->getMessage());
                    $this->flashMsgSession('warning', $this->translate('GoodDoc.download.notfound'));
                }
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
            $this->flashMsgSession('error', $e->getMessage());
            $this->flashMsgSession('warning', $this->translate('GoodDoc.download.notfound'));
        }

        return $this->redirect($urlFrom);
    }
}
