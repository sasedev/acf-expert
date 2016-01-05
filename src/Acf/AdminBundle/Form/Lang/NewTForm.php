<?php

namespace Acf\AdminBundle\Form\Lang;

use Acf\DataBundle\Entity\Lang;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class NewTForm extends AbstractType
{

	/**
	 * Form builder
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('locale', LocaleType::class, array('label' => 'Lang.locale.label'));

		$builder->add('direction', ChoiceType::class,
			array('label' => 'Lang.direction.label', 'choices_as_values' => true, 'choices' => Lang::choiceDirection(),
				'attr' => array('choice_label_trans' => true)));

		$builder->add('status', ChoiceType::class,
			array('label' => 'Lang.status.label', 'choices_as_values' => true, 'choices' => Lang::choiceStatus(), 'expanded' => true,
				'attr' => array('choice_label_trans' => true)));
	}

	/**
	 *
	 * {@inheritDoc} @see FormTypeInterface::getName()
	 * @return string
	 */
	public function getName()
	{
		return 'LangNewForm';
	}

	/**
	 *
	 * {@inheritDoc} @see AbstractType::getBlockPrefix()
	 */
	public function getBlockPrefix()
	{
		return $this->getName();
	}

	/**
	 * get the default options
	 *
	 * @return multitype:string multitype:string
	 */
	public function getDefaultOptions()
	{
		return array('validation_groups' => array('locale'));
	}

	/**
	 *
	 * {@inheritDoc} @see AbstractType::configureOptions()
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults($this->getDefaultOptions());
	}
}
