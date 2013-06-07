<?php
namespace CIM\PluploadBundle\Controller;

use \Localdev\FrameworkExtraBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UploadController
 * @package CIM\PluploadBundle\Controller
 */
class UploadController extends Controller
{
	/**
	 * @Route("/up/", name="plupload_upload")
	 * @Template()
	 */
	public function uploadAction()
	{
		/* @var \Doctrine\ORM\EntityManager $em */
		$em = $this->getManager();

		/* @var \Symfony\Component\HttpFoundation\Request $request */
		$request = $this->getRequest();

		$class = $this->container->getParameter('cim.plupload.entity');
		/* @var \CIM\PluploadBundle\Entity\File $file */
		$file = new $class();
		$file->setFile($request->files->get('file'));

		$em->persist($file);
		$em->flush();

		/* @var \Avalanche\Bundle\ImagineBundle\Imagine\CachePathResolver $cachePath */
		$cachePath = $this->get('imagine.cache.path.resolver');

		$result = array(
			'id' => $file->getId(),
			'name' => $file->getName(),
			'path' => $file->getFullPath(),
			'thumb' => $cachePath->getBrowserPath($file->getFullPath(), 'admin_thumb'),
			'is_image' => $file->isImage()
		);

		$response = $this->jsonResponse($result);
		if(get_browser()->browser || preg_match('#MSIE #i', $request->server->get('HTTP_USER_AGENT')))
		{
			$response->headers->set('Content-Type', 'text/html');
		}
		return $response;
	}
}

