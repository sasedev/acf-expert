<?php

namespace Acf\SecurityBundle\Form;

use Acf\DataBundle\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev
 */
class EventEditAdminsTForm extends AbstractType
{

	/**
	 * Form builder
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('users', EntityType::class,
			array(
				'label' => 'Agenda.admins.label',
				'class' => 'AcfDataBundle:User',
				'query_builder' => function (UserRepository $ur)
				{
					return $ur->createQueryBuilder('u')
						->leftJoin('u.userRoles', 'r')
						->where('r.name IN (:roles)')
						->setParameter('roles', array(
							'ROLE_ADMIN',
							'ROLE_SUPERADMIN'
						))
						->orderBy('u.username', 'ASC');
				},
				'choice_label' => 'fullName',
				'multiple' => true,
				'by_reference' => true,
				'required' => false
			));
	}

	/**
	 *
	 * {@inheritDoc} @see FormTypeInterface::getName()
	 * @return string
	 */
	public function getName()
	{
		return 'EventEditAdminsForm';
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
				'users'
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
