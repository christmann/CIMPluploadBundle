<?php
namespace CIM\PluploadBundle\Form;

use \Symfony\Component\Form\Extension\Core\DataTransformer\ArrayToChoicesTransformer;
use \Symfony\Bridge\Doctrine\Form\DataTransformer\EntitiesToArrayTransformer;
use \Symfony\Bridge\Doctrine\Form\EventListener\MergeCollectionListener;
use \Localdev\FrameworkExtraBundle\Form\InjectionListenerType;
use \Symfony\Bridge\Doctrine\Form\DataTransformer\EntityToIdTransformer;
use \Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList;
use \Symfony\Component\Form\Extension\Core\DataTransformer\ScalarToChoiceTransformer;
use \Symfony\Component\Form\FormView;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

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
	public function buildForm(FormBuilder $builder, array $options)
	{
		if ($options['multiple'])
		{
			$builder
					->addEventSubscriber(new MergeCollectionListener())
					->prependClientTransformer(new EntitiesToArrayTransformer($options['choice_list']));
		}
		else
		{
			$builder->prependClientTransformer(new EntityToIdTransformer($options['choice_list']));
		}

		$builder
				->setAttribute('choice_list', $options['choice_list'])
				->setAttribute('multiple', $options['multiple'])
				->setAttribute('required', $options['required'])
				->setAttribute('filter', $options['filter']);
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildView(FormView $view, FormInterface $form)
	{
		$data = $form->getData();
		$filter = $form->getAttribute('filter');

		$filtered = array();
		foreach ($filter as $key => $item)
		{
			$filtered[] = array(
				'title' => $item,
				'extensions' => $key
			);
		}

		$view
				->set('data', $data)
				->set('multiple', $form->getAttribute('multiple'))
				->set('filter', json_encode($filtered))
		;

		$vars = $view->getVars();
		$this->getListener()->addInstance($vars['id'], array(
															'full_name' => $view->get('full_name'),
															'multiple' => $view->get('multiple'),
															'filter' => $view->get('filter'),
													   ));
	}

	/**
	 * {@inheritDoc}
	 */
	public function getDefaultOptions(array $options)
	{
		$entity = $this->getContainer()->getParameter('cim.plupload.entity');
		$default = array(
			'em' => null,
			'class' => $entity,
			'query_builder' => null,
			'multiple' => false,
			'filter' => array(
				'jpg,png' => 'Bilder'
			)
		);

		$options = array_replace($default, $options);

		if (!isset($options['choice_list']))
		{
			$default['choice_list'] = new EntityChoiceList(
				$this->getEntityManager($options['em']),
				$options['class'],
				null,
				$options['query_builder']
			);
		}

		return $default;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getParent(array $options)
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