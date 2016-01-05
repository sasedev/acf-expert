<?php

namespace Acf\AdminBundle\Form\Sale;

use Acf\DataBundle\Repository\AccountRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev
 */
class UpdateAccountTForm extends AbstractType
{

	/**
	 *
	 * @var MBSale
	 */
	private $mbsale;

	/**
	 * Form builder
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$this->mbsale = $options['monthlybalance'];

		if (null == $this->mbsale) {
			$builder->add('account', EntityType::class,
				array('label' => 'Sale.account.label', 'class' => 'AcfDataBundle:Account',
					'query_builder' => function (AccountRepository $ar)
					{
						return $ar->createQueryBuilder('a')
							->orderBy('a.label', 'ASC');
					}, 'choice_label' => 'label', 'multiple' => false, 'by_reference' => true, 'required' => true));
		} else {
			$company_id = $this->mbsale->getCompany()->getId();
			$builder->add('account', EntityType::class,
				array('label' => 'Sale.account.label', 'class' => 'AcfDataBundle:Account',
					'query_builder' => function (AccountRepository $ar) use ($company_id)
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
	 *
	 * {@inheritDoc} @see FormTypeInterface::getName()
	 * @return string
	 */
	public function getName()
	{
		return 'SaleUpdateAccountForm';
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
		return array('validation_groups' => array('account'), 'monthlybalance' => null);
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
