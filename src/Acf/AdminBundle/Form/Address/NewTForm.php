<?php

namespace Acf\AdminBundle\Form\Address;

use Acf\DataBundle\Repository\CompanyRepository;
use Sasedev\Form\EntityidBundle\Form\Type\EntityidType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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
	 *
	 * @var Company
	 */
	private $company;

	/**
	 * Form builder
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$this->company = $options['company'];

		if (null == $this->company) {
			$builder->add('company', EntityType::class,
				array('label' => 'Address.company.label', 'class' => 'AcfDataBundle:Company',
					'query_builder' => function (CompanyRepository $br)
					{
						return $br->createQueryBuilder('c')
							->orderBy('c.corporateName', 'ASC');
					}, 'choice_label' => 'corporateName', 'multiple' => false, 'by_reference' => true, 'required' => true));
		} else {
			$company_id = $this->company->getId();
			$builder->add('company', EntityidType::class,
				array('label' => 'Address.company.label', 'class' => 'AcfDataBundle:Company',
					'query_builder' => function (CompanyRepository $br) use ($company_id)
					{
						return $br->createQueryBuilder('c')
							->where('c.id = :id')
							->setParameter('id', $company_id)
							->orderBy('c.corporateName', 'ASC');
					}, 'choice_label' => 'id', 'multiple' => false, 'by_reference' => true, 'required' => true));
		}

		$builder->add('label', TextType::class, array('label' => 'Address.label.label'));

		$builder->add('streetNum', IntegerType::class, array('label' => 'Address.streetNum.label', 'scale' => 0, 'required' => false));

		$builder->add('address', TextareaType::class, array('label' => 'Address.address.label', 'required' => false));

		$builder->add('address2', TextareaType::class, array('label' => 'Address.address2.label', 'required' => false));

		$builder->add('town', TextType::class, array('label' => 'Address.town.label', 'required' => false));

		$builder->add('zipCode', TextType::class, array('label' => 'Address.zipCode.label', 'required' => false));

		$builder->add('country', CountryType::class,
			array('label' => 'Address.country.label', 'required' => false, 'placeholder' => 'Options.choose', 'empty_data' => null));

		$builder->add('email', EmailType::class, array('label' => 'Address.email.label', 'required' => false));

		$builder->add('phone', TextType::class, array('label' => 'Address.phone.label', 'required' => false));

		$builder->add('mobile', TextType::class, array('label' => 'Address.mobile.label', 'required' => false));

		$builder->add('fax', TextType::class, array('label' => 'Address.fax.label', 'required' => false));

		$builder->add('otherInfos', TextareaType::class, array('label' => 'Address.otherInfos.label', 'required' => false));
	}

	/**
	 *
	 * {@inheritDoc} @see FormTypeInterface::getName()
	 * @return string
	 */
	public function getName()
	{
		return 'AddressNewForm';
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
		return array('validation_groups' => array('phone', 'mobile', 'fax', 'email'), 'company' => null);
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
