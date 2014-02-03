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
