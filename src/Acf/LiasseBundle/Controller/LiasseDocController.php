<?php
namespace Acf\LiasseBundle\Controller;

use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class LiasseDocController extends BaseController
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
        $this->gvars['menu_active'] = 'liasseDoc';
    }

    /**
     *
     * @param string $uid
     *
     * @return StreamedResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function downloadAction($uid)
    {
        $urlFrom = $this->getReferer();
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('_liasse_homepage');
        }
        $em = $this->getEntityManager();
        try {
            $liasseDoc = $em->getRepository('AcfDataBundle:LiasseDoc')->find($uid);

            if (null == $liasseDoc) {
                $logger = $this->getLogger();
                $logger->addError('Document inconnu');
                $this->flashMsgSession('warning', $this->translate('LiasseDoc.download.notfound'));
            } else {
                $liasseDocDir = $this->getParameter('kernel.root_dir') . '/../web/res/liasseDocs';
                $fileName = $liasseDoc->getFileName();

                try {
                    $dlFile = new File($liasseDocDir . '/' . $fileName);
                    $response = new StreamedResponse(function () use ($dlFile) {
                        $handle = fopen($dlFile->getRealPath(), 'r');
                        while (!feof($handle)) {
                            $buffer = fread($handle, 1024);
                            echo $buffer;
                            flush();
                        }
                        fclose($handle);
                    });

                    $timestamp = $liasseDoc->getDtUpdate()->getTimestamp();
                    $response->headers->set('Content-Type', $liasseDoc->getMimeType());
                    $response->headers->set('Cache-Control', '');
                    $response->headers->set('Content-Length', $liasseDoc->getSize());
                    $response->headers->set('Last-Modified', gmdate('D, d M Y H:i:s', $timestamp));
                    $fallback = $this->normalizeString($this->normalize($liasseDoc->getTitle()));

                    $contentDisposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $liasseDoc->getOriginalName(), $fallback);
                    $response->headers->set('Content-Disposition', $contentDisposition);

                    $liasseDoc->setNbrDownloads($liasseDoc->getNbrDownloads() + 1);
                    $em->persist($liasseDoc);
                    $em->flush();

                    return $response;
                } catch (FileNotFoundException $fnfex) {
                    $logger = $this->getLogger();
                    $logger->addError('Fichier introuvable ou autre erreur');
                    $logger->addError($fnfex->getMessage());
                    $this->flashMsgSession('error', $fnfex->getMessage());
                    $this->flashMsgSession('warning', $this->translate('LiasseDoc.download.notfound'));
                }
            }
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addCritical($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTraceAsString());
            $this->flashMsgSession('error', $e->getMessage());
            $this->flashMsgSession('warning', $this->translate('LiasseDoc.download.notfound'));
        }

        return $this->redirect($urlFrom);
    }

    private static function normalizeString($str = '')
    {
        $str = strip_tags($str);
        $str = preg_replace('/[\r\n\t ]+/', ' ', $str);
        $str = preg_replace('/[\"\*\/\:\<\>\?\'\|]+/', ' ', $str);
        $str = strtolower($str);
        $str = html_entity_decode($str, ENT_QUOTES, "utf-8");
        $str = htmlentities($str, ENT_QUOTES, "utf-8");
        $str = preg_replace("/(&)([a-z])([a-z]+;)/i", '$2', $str);
        $str = str_replace(' ', '-', $str);
        $str = rawurlencode($str);
        $str = str_replace('%', '-', $str);
        return $str;
    }
}
