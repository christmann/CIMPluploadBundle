<?php
/**
 * CIMPluploadBundle - Provides a plupload upload for Symfony2
 * Copyright (C) 2013-2014 christmann informationstechnik + medien GmbH & Co. KG
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

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

