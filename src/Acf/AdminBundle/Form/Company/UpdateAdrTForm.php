<?php

namespace Acf\AdminBundle\Form\Company;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev
 */
class UpdateAdrTForm extends AbstractType
{

	/**
	 * Form builder
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('streetNum', IntegerType::class, array('label' => 'Company.streetNum.label', 'scale' => 0, 'required' => false));

		$builder->add('address', TextareaType::class, array('label' => 'Company.address.label', 'required' => false));

		$builder->add('address2', TextareaType::class, array('label' => 'Company.address2.label', 'required' => false));

		$builder->add('town', TextType::class, array('label' => 'Company.town.label', 'required' => false));

		$builder->add('zipCode', TextType::class, array('label' => 'Company.zipCode.label', 'required' => false));

		$builder->add('country', CountryType::class,
			array('label' => 'Company.country.label', 'required' => false, 'placeholder' => 'Options.choose', 'empty_data' => null));
	}

	/**
	 * {@inheritDoc} @see FormTypeInterface::getName()
	 *
	 * @return string
	 */
	public function getName()
	{
		return 'CompanyUpdateAdrForm';
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
		return array('validation_groups' => array('Default'));
	}

	/**
	 * {@inheritDoc} @see AbstractType::configureOptions()
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults($this->getDefaultOptions());
	}
}
