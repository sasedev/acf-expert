<?php

namespace Acf\AdminBundle\Form\Buy;

use Acf\DataBundle\Entity\MBPurchase;
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
			$builder->add('account', EntityType::class,
				array('label' => 'Buy.account.label', 'class' => 'AcfDataBundle:Account',
					'query_builder' => function (AccountRepository $ar)
					{
						return $ar->createQueryBuilder('a')
							->orderBy('a.label', 'ASC');
					}, 'choice_label' => 'label', 'multiple' => false, 'by_reference' => true, 'required' => true));
		} else {
			$company_id = $this->mbpurchase->getCompany()->getId();
			$builder->add('account', EntityType::class,
				array('label' => 'Buy.account.label', 'class' => 'AcfDataBundle:Account',
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
	 * {@inheritDoc} @see FormTypeInterface::getName()
	 *
	 * @return string
	 */
	public function getName()
	{
		return 'BuyUpdateAccountForm';
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
	 * {@inheritDoc} @see AbstractType::configureOptions()
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults($this->getDefaultOptions());
	}
}
