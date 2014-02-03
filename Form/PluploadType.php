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

namespace CIM\PluploadBundle\Form;

use \Symfony\Component\Form\Extension\Core\DataTransformer\ArrayToChoicesTransformer;
use \Symfony\Bridge\Doctrine\Form\DataTransformer\EntitiesToArrayTransformer;
use \Symfony\Bridge\Doctrine\Form\EventListener\MergeCollectionListener;
use \Localdev\FrameworkExtraBundle\Form\InjectionListenerType;
use \Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use \Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList;
use \Symfony\Component\Form\Extension\Core\DataTransformer\ScalarToChoiceTransformer;
use \Symfony\Component\Form\FormView;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Plupload
 *
 * @author Fabian Martin <fabian.martin@christmann.info>
 */
class PluploadType extends InjectionListenerType
{
	/**
	 * {@inheritDoc}
	 */
	public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
	{
		$builder
				->setAttribute('choice_list', $options['choice_list'])
				->setAttribute('multiple', $options['multiple'])
				->setAttribute('placeholder', $options['placeholder'])
				->setAttribute('bootstrap', $options['bootstrap'])
				->setAttribute('showMaxSize', $options['showMaxSize'])
				->setAttribute('required', $options['required'])
				->setAttribute('filter', $options['filter']);
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildView(FormView $view, FormInterface $form, array $options)
	{
		$data = $form->getData();
		$filter = $form->getConfig()->getAttribute('filter');

		$filtered = array();
		foreach ($filter as $key => $item)
		{
			$filtered[] = array(
				'title' => $item,
				'extensions' => $key
			);
		}

		$view->vars = array_replace($view->vars, array(
			'data'        => $data,
			'multiple' => $form->getConfig()->getAttribute('multiple'),
			'placeholder' => $form->getConfig()->getAttribute('placeholder'),
			'bootstrap' => $form->getConfig()->getAttribute('bootstrap'),
			'showMaxSize' => $form->getConfig()->getAttribute('showMaxSize'),
			'filter' => json_encode($filtered),
		));

		$vars = $view->vars;
		$this->getListener()->addInstance($vars['id'], array(
															'full_name' => $vars['full_name'],
															'multiple' => $vars['multiple'],
															'placeholder' => $vars['placeholder'],
															'bootstrap' => $vars['bootstrap'],
															'showMaxSize' => $vars['showMaxSize'],
															'filter' => $vars['filter'],
													   ));
	}

	/**
	 * {@inheritDoc}
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$entity = $this->getContainer()->getParameter('cim.plupload.entity');
		$default = array(
			'em' => null,
			'class' => $entity,
			'query_builder' => null,
			'multiple' => false,
			'placeholder' => false,
			'bootstrap' => false,
			'showMaxSize' => false,
			'filter' => array(
				'jpg,png,gif,bmp,jpeg' => 'Bilder'
			)
		);

		if (!isset($default['choice_list']))
		{
			$default['choice_list'] = new EntityChoiceList(
				$this->getManager($default['em']),
				$default['class'],
				null,
				$default['query_builder']
			);
		}

		$resolver->setDefaults($default);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getParent()
	{
		return 'choice';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return 'plupload';
	}
}