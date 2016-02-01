<?php

namespace Acf\InfoBundle\Controller;

use Acf\DataBundle\Entity\BiDoc;
use Sasedev\Commons\SharedBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class BiDocController extends BaseController
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

		$this->gvars['menu_active'] = 'biDoc';

	}

	public function downloadAction($uid)
	{
		$urlFrom = $this->getReferer();
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('_info_homepage');
		}
		$em = $this->getEntityManager();
		try {
			$biDoc = $em->getRepository('AcfDataBundle:BiDoc')->find($uid);

			if (null == $biDoc) {
				$logger = $this->getLogger();
				$logger->addError('Document inconnu');
				$this->flashMsgSession('warning', $this->translate('BiDoc.download.notfound'));
			} else {
				$biDocDir = $this->getParameter('kernel.root_dir').'/../web/res/biDocs';
				$fileName = $biDoc->getFileName();

				try {
					$dlFile = new File($biDocDir.'/'.$fileName);
					$response = new StreamedResponse(function() use($dlFile) {
						$handle = fopen($dlFile->getRealPath(), 'r');
						while (!feof($handle)) {
							$buffer = fread($handle, 1024);
							echo $buffer;
							flush();
						}
						fclose($handle);
					});

					$response->headers->set('Content-Type', $biDoc->getMimeType());
					$response->headers->set('Cache-Control', '');
					$response->headers->set('Content-Length', $biDoc->getSize());
					$response->headers->set('Last-Modified', gmdate('D, d M Y H:i:s', $biDoc->getDtUpdate()->getTimestamp()));
					$fallback = $this->normalize($biDoc->getTitle());

					$contentDisposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $biDoc->getOriginalName(), $fallback);
					$response->headers->set('Content-Disposition', $contentDisposition);

					$biDoc->setNbrDownloads($biDoc->getNbrDownloads() + 1);
					$em->persist($biDoc);
					$em->flush();

					return $response;
				} catch (FileNotFoundException $fnfex) {
					$logger = $this->getLogger();
					$logger->addError('Fichier introuvable ou autre erreur');
					$logger->addError($fnfex->getMessage());
					$this->flashMsgSession('error', $fnfex->getMessage());
					$this->flashMsgSession('warning', $this->translate('BiDoc.download.notfound'));
				}
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addCritical($e->getLine().' '.$e->getMessage().' '.$e->getTraceAsString());
			$this->flashMsgSession('error', $e->getMessage());
			$this->flashMsgSession('warning', $this->translate('BiDoc.download.notfound'));
		}

		return $this->redirect($urlFrom);

	}
}

?>
