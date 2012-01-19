<?php
namespace CIM\PluploadBundle\Listener;

use CIM\BaseBundle\Listener\InstanceListener;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\HttpFoundation\Response;

/**
 * PluploadTypeListener injects the Javascript Code.
 *
 * @author Fabian Martin <fabian.martin@christmann.info>
 */
class PluploadTypeListener extends InstanceListener
{
	public function addInstance($id, $options=array())
	{
		$this->instances[$id] = $options;
	}

	/**
	 * Injects the Javascript into the given Response.
	 *
	 * @param \Symfony\Component\HttpFoundation\Response $response A Response instance
	 */
	function inject(Response $response)
	{
		$this->injectJavascript($response, 'CIMPluploadBundle:Form:header.html.twig');
	}
}
