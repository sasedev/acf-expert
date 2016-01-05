<?php

namespace Acf\ClientBundle\Form\Company;

use Acf\DataBundle\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev
 */
class UpdatePhysicaltypeTForm extends AbstractType
{

	/**
	 * Form builder
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('physicaltype', ChoiceType::class,
			array('label' => 'Company.physicaltype.label', 'choices_as_values' => true, 'choices' => Company::choicePhysicaltype(),
				'expanded' => true, 'attr' => array('choice_label_trans' => true)));

		$builder->add('cin', TextType::class, array('label' => 'Company.cin.label', 'required' => false));

		$builder->add('passport', TextType::class, array('label' => 'Company.passport.label', 'required' => false));

		$builder->add('commercialRegister', TextType::class, array('label' => 'Company.commercialRegister.label', 'required' => false));

		$builder->add('commercialRegisterBureau', TextType::class,
			array('label' => 'Company.commercialRegisterBureau.label', 'required' => false));

		$builder->add('customsCode', TextType::class, array('label' => 'Company.customsCode.label', 'required' => false));
	}

	/**
	 *
	 * {@inheritDoc} @see FormTypeInterface::getName()
	 * @return string
	 */
	public function getName()
	{
		return 'CompanyUpdatePhysicaltypeForm';
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
	 *
	 * {@inheritDoc} @see AbstractType::configureOptions()
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults($this->getDefaultOptions());
	}
}
