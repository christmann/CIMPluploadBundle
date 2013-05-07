<?php
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
				->setAttribute('required', $options['required'])
				->setAttribute('filter', $options['filter'])
		;
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildView(FormView $view, FormInterface $form, array $options)
	{
		/* @var $form \Symfony\Component\Form\Form */
		$data = $form->getData();
//		$filter = $form->getAttribute('filter');
		$filter = array(
			'jpg,png' => 'Bilder'
		);

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
			'multiple' => false, //$form->getAttribute('multiple')
			'filter' => json_encode($filtered),
		));

		$this->getListener()->addInstance($view->vars['id'], array(
															'full_name' => $view->vars['full_name'],
															'multiple' => $view->vars['multiple'],
															'filter' => $view->vars['filter'],
													   ));
	}

	/**
	 * {@inheritdoc}
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		/* @var $options array */
		$options = $resolver->resolve();

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

		if (!isset($options['choice_list']) || count($options['choice_list']) < 1)
		{
			$default['choice_list'] = new EntityChoiceList(
				$this->getManager($options['em']),				// ObjectManager $manager
				$options['class'],								// $class
				null,											// $labelPath = null
				$options['query_builder']						// EntityLoaderInterface $entityLoader = null
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