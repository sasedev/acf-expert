<?php

namespace Acf\AdminBundle\Form\Supplier;

use Acf\DataBundle\Entity\Supplier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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
class UpdateTForm extends AbstractType
{

	/**
	 * Form builder
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('label', TextType::class, array('label' => 'Supplier.label.label'));

		$builder->add('number', IntegerType::class, array('label' => 'Supplier.number.label'));

		$builder->add('fisc', TextType::class, array('label' => 'Supplier.fisc.label'));

		$builder->add('physicaltype', ChoiceType::class,
			array('label' => 'Supplier.physicaltype.label', 'choices_as_values' => true, 'choices' => Supplier::choicePhysicaltype(),
				'expanded' => true, 'attr' => array('choice_label_trans' => true)));

		$builder->add('cin', TextType::class, array('label' => 'Supplier.cin.label', 'required' => false));

		$builder->add('passport', TextType::class, array('label' => 'Supplier.passport.label', 'required' => false));

		$builder->add('commercialRegister', TextType::class, array('label' => 'Supplier.commercialRegister.label', 'required' => false));

		$builder->add('email', EmailType::class, array('label' => 'Supplier.email.label', 'required' => false));

		$builder->add('phone', TextType::class, array('label' => 'Supplier.phone.label', 'required' => false));

		$builder->add('mobile', TextType::class, array('label' => 'Supplier.mobile.label', 'required' => false));

		$builder->add('fax', TextType::class, array('label' => 'Supplier.fax.label', 'required' => false));

		$builder->add('streetNum', IntegerType::class, array('label' => 'Supplier.streetNum.label', 'scale' => 0, 'required' => false));

		$builder->add('address', TextareaType::class, array('label' => 'Supplier.address.label', 'required' => false));

		$builder->add('address2', TextareaType::class, array('label' => 'Supplier.address2.label', 'required' => false));

		$builder->add('town', TextType::class, array('label' => 'Supplier.town.label', 'required' => false));

		$builder->add('zipCode', TextType::class, array('label' => 'Supplier.zipCode.label', 'required' => false));

		$builder->add('country', CountryType::class,
			array('label' => 'Supplier.country.label', 'required' => false, 'placeholder' => 'Options.choose', 'empty_data' => null));

		$builder->add('otherInfos', TextareaType::class, array('label' => 'Supplier.otherInfos.label', 'required' => false));
	}

	/**
	 *
	 * {@inheritDoc} @see FormTypeInterface::getName()
	 * @return string
	 */
	public function getName()
	{
		return 'SupplierUpdateForm';
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
		return array('validation_groups' => array('phone', 'mobile', 'email', 'number', 'label'));
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
