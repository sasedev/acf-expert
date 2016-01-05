<?php

namespace Acf\AdminBundle\Form\Company;

// use Acf\AdminBundle\Form\CompanyFrame\CompanyFrameTForm;
use Acf\DataBundle\Entity\Company;
use Acf\DataBundle\Repository\CompanyRepository;
use Acf\DataBundle\Repository\CompanyTypeRepository;
use Acf\DataBundle\Repository\SectorRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev
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
		$builder->add('ref', TextType::class, array(
			'label' => 'Company.ref.label'
		));

		$builder->add('corporateName', TextType::class, array(
			'label' => 'Company.corporateName.label'
		));

		$builder->add('type', EntityType::class,
			array(
				'label' => 'Company.type.label',
				'class' => 'AcfDataBundle:CompanyType',
				'query_builder' => function (CompanyTypeRepository $ctr)
				{
					return $ctr->createQueryBuilder('ct')
						->orderBy('ct.label', 'ASC');
				},
				'choice_label' => 'label',
				'multiple' => false,
				'by_reference' => true,
				'required' => true
			));

		$builder->add('sectors', EntityType::class,
			array(
				'label' => 'Company.sectors.label',
				'class' => 'AcfDataBundle:Sector',
				'query_builder' => function (SectorRepository $sr)
				{
					return $sr->createQueryBuilder('s')
						->orderBy('s.label', 'ASC');
				},
				'choice_label' => 'label',
				'multiple' => true,
				'by_reference' => true,
				'required' => false
			));

		$builder->add('tribunal', TextType::class, array(
			'label' => 'Company.tribunal.label',
			'required' => false
		));

		$builder->add('fisc', TextType::class, array(
			'label' => 'Company.fisc.label'
		));

		$builder->add('cnss', TextType::class, array(
			'label' => 'Company.cnss.label',
			'required' => false
		));

		$builder->add('cnssBureau', TextType::class, array(
			'label' => 'Company.cnssBureau.label',
			'required' => false
		));

		$builder->add('physicaltype', ChoiceType::class,
			array(
				'label' => 'Company.physicaltype.label',
				'choices_as_values' => true,
				'choices' => Company::choicePhysicaltype(),
				'expanded' => true,
				'attr' => array(
					'choice_label_trans' => true
				)
			));

		$builder->add('cin', TextType::class, array(
			'label' => 'Company.cin.label',
			'required' => false
		));

		$builder->add('passport', TextType::class, array(
			'label' => 'Company.passport.label',
			'required' => false
		));

		$builder->add('customsCode', TextType::class, array(
			'label' => 'Company.customsCode.label',
			'required' => false
		));

		$builder->add('commercialRegister', TextType::class, array(
			'label' => 'Company.commercialRegister.label',
			'required' => false
		));

		$builder->add('commercialRegisterBureau', TextType::class,
			array(
				'label' => 'Company.commercialRegisterBureau.label',
				'required' => false
			));

		$builder->add('actionvn', NumberType::class, array(
			'label' => 'Company.actionvn.label'
		));

		$builder->add('phone', TextType::class, array(
			'label' => 'Company.phone.label',
			'required' => false
		));

		$builder->add('mobile', TextType::class, array(
			'label' => 'Company.mobile.label',
			'required' => false
		));

		$builder->add('fax', TextType::class, array(
			'label' => 'Company.fax.label',
			'required' => false
		));

		$builder->add('email', EmailType::class, array(
			'label' => 'Company.email.label',
			'required' => false
		));

		$builder->add('streetNum', IntegerType::class, array(
			'label' => 'Company.streetNum.label',
			'scale' => 0,
			'required' => false
		));

		$builder->add('address', TextareaType::class, array(
			'label' => 'Company.address.label',
			'required' => false
		));

		$builder->add('address2', TextareaType::class, array(
			'label' => 'Company.address2.label',
			'required' => false
		));

		$builder->add('town', TextType::class, array(
			'label' => 'Company.town.label',
			'required' => false
		));

		$builder->add('zipCode', TextType::class, array(
			'label' => 'Company.zipCode.label',
			'required' => false
		));

		$builder->add('country', CountryType::class,
			array(
				'label' => 'Company.country.label',
				'required' => false,
				'placeholder' => 'Options.choose',
				'empty_data' => null
			));

		$builder->add('otherInfos', TextareaType::class, array(
			'label' => 'Company.otherInfos.label',
			'required' => false
		));

		$builder->add('clone', EntityType::class,
			array(
				'label' => 'Company.clone.label',
				'class' => 'AcfDataBundle:Company',
				'query_builder' => function (CompanyRepository $cr)
				{
					return $cr->createQueryBuilder('c')
						->orderBy('c.corporateName', 'ASC');
				},
				'choice_label' => 'corporateName',
				'multiple' => false,
				'by_reference' => true,
				'required' => false,
				'mapped' => false
			));
	}

	/**
	 *
	 * {@inheritDoc} @see FormTypeInterface::getName()
	 * @return string
	 */
	public function getName()
	{
		return 'CompanyNewForm';
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
		return array(
			'validation_groups' => array(
				'ref',
				'corporateName',
				'type',
				'actionvn',
				'phone',
				'mobile',
				'fax',
				'email',
				'physicaltype'
			)
		);
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
