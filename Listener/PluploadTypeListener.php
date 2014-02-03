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

namespace CIM\PluploadBundle\Listener;

use \Localdev\FrameworkExtraBundle\EventListener\InjectionListener;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\HttpFoundation\Response;

/**
 * PluploadTypeListener injects the Javascript Code.
 *
 * @author Fabian Martin <fabian.martin@christmann.info>
 */
class PluploadTypeListener extends InjectionListener
{
	/**
	 * @param string $id
	 * @param array $options
	 */
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
