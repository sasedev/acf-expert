<?php

namespace Acf\ClientBundle\Form\Company;

use Acf\DataBundle\Repository\CompanyTypeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev
 */
class UpdateTypeTForm extends AbstractType
{

	/**
	 * Form builder
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('type', EntityType::class,
			array('label' => 'Company.type.label', 'class' => 'AcfDataBundle:CompanyType',
				'query_builder' => function (CompanyTypeRepository $ctr)
				{
					return $ctr->createQueryBuilder('ct')
						->orderBy('ct.label', 'ASC');
				}, 'choice_label' => 'label', 'multiple' => false, 'by_reference' => true, 'required' => true,
				'placeholder' => 'Options.choose', 'empty_data' => null));
	}

	/**
	 *
	 * {@inheritDoc} @see FormTypeInterface::getName()
	 * @return string
	 */
	public function getName()
	{
		return 'CompanyUpdateTypeForm';
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
		return array('validation_groups' => array('type'));
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
