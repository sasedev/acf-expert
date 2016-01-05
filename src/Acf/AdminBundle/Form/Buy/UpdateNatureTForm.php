<?php

namespace Acf\AdminBundle\Form\Buy;

use Acf\DataBundle\Repository\CompanyNatureRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev
 */
class UpdateNatureTForm extends AbstractType
{

	/**
	 *
	 * @var MBPurchase
	 */
	private $mbpurchase;

	/**
	 * Form builder
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$this->mbpurchase = $options['monthlybalance'];

		if (null == $this->mbpurchase) {
			$builder->add('nature', EntityType::class,
				array('label' => 'Buy.nature.label', 'class' => 'AcfDataBundle:CompanyNature',
					'query_builder' => function (CompanyNatureRepository $ar)
					{
						return $ar->createQueryBuilder('a')
							->orderBy('a.label', 'ASC');
					}, 'choice_label' => 'label', 'multiple' => false, 'by_reference' => true, 'required' => true));
		} else {
			$company_id = $this->mbpurchase->getCompany()->getId();
			$builder->add('nature', EntityType::class,
				array('label' => 'Buy.nature.label', 'class' => 'AcfDataBundle:CompanyNature',
					'query_builder' => function (CompanyNatureRepository $ar) use ($company_id)
					{
						return $ar->createQueryBuilder('a')
							->join('a.company', 'c')
							->where('c.id = :cid')
							->setParameter('cid', $company_id)
							->orderBy('a.label', 'ASC');
					}, 'choice_label' => 'label', 'multiple' => false, 'by_reference' => true, 'required' => true));
		}
	}

	/**
	 * {@inheritDoc} @see FormTypeInterface::getName()
	 *
	 * @return string
	 */
	public function getName()
	{
		return 'BuyUpdateNatureForm';
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
		return array('validation_groups' => array('nature'), 'monthlybalance' => null);
	}

	/**
	 * {@inheritDoc} @see AbstractType::configureOptions()
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults($this->getDefaultOptions());
	}
}
