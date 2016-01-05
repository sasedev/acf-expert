<?php

namespace Acf\ClientBundle\Form\Sale;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev
 */
class UpdateDeviseTForm extends AbstractType
{

	/**
	 * Form builder
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('devise', CurrencyType::class, array('label' => 'Sale.devise.label'));
	}

	/**
	 *
	 * {@inheritDoc} @see FormTypeInterface::getName()
	 * @return string
	 */
	public function getName()
	{
		return 'SaleUpdateDeviseForm';
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
		return array('validation_groups' => array('devise'));
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
