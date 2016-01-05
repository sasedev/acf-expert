<?php

namespace Acf\AdminBundle\Form\Docgroupaudit;

use Acf\DataBundle\Repository\CompanyRepository;
use Acf\DataBundle\Repository\DocgroupauditRepository;
use Sasedev\Form\EntityidBundle\Form\Type\EntityidType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
				array('label' => 'Docgroupaudit.company.label', 'class' => 'AcfDataBundle:Company',
					'query_builder' => function (CompanyRepository $br)
					{
						return $br->createQueryBuilder('c')
							->orderBy('c.corporateName', 'ASC');
					}, 'choice_label' => 'corporateName', 'multiple' => false, 'by_reference' => true, 'required' => true));

			$builder->add('parent', EntityType::class,
				array('label' => 'Docgroupaudit.parent.label', 'class' => 'AcfDataBundle:Docgroupaudit',
					'query_builder' => function (DocgroupauditRepository $dgr)
					{
						return $dgr->createQueryBuilder('d')
							->orderBy('d.pageUrlFull', 'ASC');
					}, 'choice_label' => 'pageUrlFull', 'multiple' => false, 'by_reference' => true, 'required' => false,
					'placeholder' => 'Options.choose', 'empty_data' => null));

			$builder->add('clone', EntityType::class,
				array('label' => 'Docgroup.clone.label', 'class' => 'AcfDataBundle:Docgroupaudit',
					'query_builder' => function (DocgroupauditRepository $dgr)
					{
						return $dgr->createQueryBuilder('d')
							->orderBy('d.pageUrlFull', 'ASC');
					}, 'choice_label' => 'pageUrlFull', 'multiple' => false, 'by_reference' => true, 'required' => false,
					'placeholder' => 'Options.choose', 'mapped' => false));
		} else {
			$company_id = $this->company->getId();
			$builder->add('company', EntityidType::class,
				array('label' => 'Docgroupaudit.company.label', 'class' => 'AcfDataBundle:Company',
					'query_builder' => function (CompanyRepository $br) use ($company_id)
					{
						return $br->createQueryBuilder('c')
							->where('c.id = :id')
							->setParameter('id', $company_id)
							->orderBy('c.corporateName', 'ASC');
					}, 'choice_label' => 'id', 'multiple' => false, 'by_reference' => true, 'required' => true))

			;

			$builder->add('parent', EntityType::class,
				array('label' => 'Docgroupaudit.parent.label', 'class' => 'AcfDataBundle:Docgroupaudit',
					'query_builder' => function (DocgroupauditRepository $dgr) use ($company_id)
					{
						return $dgr->createQueryBuilder('d')
							->join('d.company', 'c')
							->where('c.id = :id')
							->setParameter('id', $company_id)
							->orderBy('d.pageUrlFull', 'ASC');
					}, 'choice_label' => 'pageUrlFull', 'multiple' => false, 'by_reference' => true, 'required' => false,
					'placeholder' => 'Options.choose', 'empty_data' => null));

			$builder->add('clone', EntityType::class,
				array('label' => 'Docgroup.clone.label', 'class' => 'AcfDataBundle:Docgroupaudit',
					'query_builder' => function (DocgroupauditRepository $dgr) use ($company_id)
					{
						return $dgr->createQueryBuilder('d')
							->join('d.company', 'c')
							->where('c.id = :id')
							->setParameter('id', $company_id)
							->orderBy('d.pageUrlFull', 'ASC');
					}, 'choice_label' => 'pageUrlFull', 'multiple' => false, 'by_reference' => true, 'required' => false,
					'placeholder' => 'Options.choose', 'mapped' => false));
		}

		$builder->add('label', TextType::class, array('label' => 'Docgroupaudit.label.label'));

		$builder->add('otherInfos', TextareaType::class, array('label' => 'Docgroupaudit.otherInfos.label', 'required' => false));
	}

	/**
	 *
	 * {@inheritDoc} @see FormTypeInterface::getName()
	 * @return string
	 */
	public function getName()
	{
		return 'DocgroupauditNewForm';
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
		return array('validation_groups' => array('label', 'parent'), 'company' => null);
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
