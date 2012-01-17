<?php
namespace CIM\PluploadBundle\Form;

use \CIM\BaseBundle\Form\ListenerType;
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
class PluploadType extends ListenerType
{
	public function buildForm(FormBuilder $builder, array $options)
	{
		$builder->prependClientTransformer(new EntityToIdTransformer($options['choice_list']));
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildView(FormView $view, FormInterface $form)
	{
		parent::buildView($view, $form);

		$data = $form->getData();
		$view->set('data', $data);

		parent::buildView($view, $form);
	}

	public function getDefaultOptions(array $options)
	{
		return array(
			'data_class' => 'CIM\CMSBundle\Entity\File',
			'choice_list' => new EntityChoiceList(
				$this->registry->getEntityManager(),
				'CIM\CMSBundle\Entity\File'
			)
		);
	}

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