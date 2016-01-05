<?php

namespace Acf\AdminBundle\Form\Doc;

use Acf\DataBundle\Entity\Company;
use Acf\DataBundle\Repository\CompanyRepository;
use Sasedev\Form\EntityidBundle\Form\Type\EntityidType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
				array('label' => 'Customer.company.label', 'class' => 'AcfDataBundle:Company',
					'query_builder' => function (CompanyRepository $br)
					{
						return $br->createQueryBuilder('c')
							->orderBy('c.corporateName', 'ASC');
					}, 'choice_label' => 'corporateName', 'multiple' => false, 'by_reference' => true, 'required' => true));
		} else {
			$company_id = $this->company->getId();
			$builder->add('company', EntityidType::class,
				array('label' => 'Customer.company.label', 'class' => 'AcfDataBundle:Company',
					'query_builder' => function (CompanyRepository $br) use ($company_id)
					{
						return $br->createQueryBuilder('c')
							->where('c.id = :id')
							->setParameter('id', $company_id)
							->orderBy('c.corporateName', 'ASC');
					}, 'choice_label' => 'id', 'multiple' => false, 'by_reference' => true, 'required' => true))

			;
		}

		$builder->add('fileName', FileType::class, array('label' => 'Doc.fileName.label', 'multiple' => true));

		$builder->add('description', TextareaType::class, array('label' => 'Doc.description.label', 'required' => false));
	}

	/**
	 *
	 * {@inheritDoc} @see FormTypeInterface::getName()
	 * @return string
	 */
	public function getName()
	{
		return 'DocNewForm';
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
		return array('validation_groups' => array('fileNames'), 'company' => null);
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
