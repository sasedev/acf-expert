<?php

namespace Acf\AdminBundle\Form\CompanyAdmin;

use Acf\DataBundle\Entity\Company;
use Acf\DataBundle\Entity\CompanyAdmin;
use Acf\DataBundle\Repository\CompanyRepository;
use Acf\DataBundle\Repository\UserRepository;
use Sasedev\Form\EntityidBundle\Form\Type\EntityidType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
		$company_id = $this->company->getId();

		$builder->add('company', EntityidType::class,
			array('label' => 'CompanyAdmin.company.label', 'class' => 'AcfDataBundle:Company',
				'query_builder' => function (CompanyRepository $br) use ($company_id)
				{
					return $br->createQueryBuilder('c')
						->where('c.id = :id')
						->setParameter('id', $company_id)
						->orderBy('c.corporateName', 'ASC');
				}, 'choice_label' => 'id', 'multiple' => false, 'by_reference' => true, 'required' => true)

			);
		$builder->add('user', EntityType::class,
			array('label' => 'CompanyAdmin.user.label', 'class' => 'AcfDataBundle:User',
				'query_builder' => function (UserRepository $ur) use ($company_id)
				{
					$already_users = $ur->createQueryBuilder('u')
						->select('u.id')
						->join('u.admCompanies', 'c')
						->where('c.id = :id')
						->setParameter('id', $company_id)
						->getQuery()
						->execute();

					if (count($already_users) != 0) {
						return $ur->createQueryBuilder('u')
							->where('u.id NOT IN (:ulist)')
							->setParameter('ulist', $already_users)
							->orderBy('u.username', 'ASC');
					} else {
						return $ur->createQueryBuilder('u')
							->orderBy('u.username', 'ASC');
					}
				}, 'choice_label' => 'fullName', 'multiple' => false, 'by_reference' => true, 'required' => true)

			);
	}

	/**
	 *
	 * {@inheritDoc} @see FormTypeInterface::getName()
	 * @return string
	 */
	public function getName()
	{
		return 'CompanyAdminNewForm';
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
		return array('validation_groups' => array('Default'), 'company' => new Company());
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
