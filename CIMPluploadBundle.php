<?php

namespace CIM\PluploadBundle;

use CIM\PluploadBundle\DependencyInjection\Compiler\TwigFormPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CIMPluploadBundle extends Bundle
{
	/**
	 * {@inheritDoc}
	 */
	public function build(ContainerBuilder $container)
	{
		parent::build($container);

		$container->addCompilerPass(new TwigFormPass());
	}
}
