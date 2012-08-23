<?php
namespace CIM\PluploadBundle\Controller;

use \Localdev\FrameworkExtraBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class UploadController extends Controller
{
	/**
	 * @Route("/up/", name="plupload_upload")
	 * @Template()
	 */
	public function uploadAction()
	{
		/* @var \Doctrine\ORM\EntityManager $em */
		$em = $this->getEntityManager();

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

		return $this->jsonResponse($result);
	}
}

