<?php

namespace CIM\PluploadBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Adds the Twig form templates to the list of resources
 */
class TwigFormPass implements CompilerPassInterface
{

	/**
	 * {@inheritDoc}
	 */
	public function process(ContainerBuilder $container)
	{
		if ($container->hasParameter('twig.form.resources'))
		{
			$container->setParameter('twig.form.resources', array_merge(
							$container->getParameter('twig.form.resources'), array(
								'CIMPluploadBundle:Form:form.html.twig'
							)
					));
		}
	}

}
