<?php

namespace Acf\AdminBundle\Form\SecondaryVat;

use Acf\DataBundle\Entity\Sale;
use Acf\DataBundle\Entity\SecondaryVat;
use Acf\DataBundle\Repository\SaleRepository;
use Sasedev\Form\EntityidBundle\Form\Type\EntityidType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
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
	 * @var Sale
	 */
	private $sale;

	/**
	 * Form builder
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$this->sale = $options['sale'];

		if (null == $this->sale) {
			$builder->add('sale', EntityType::class,
				array('label' => 'SecondatVat.sale.label', 'class' => 'AcfDataBundle:Sale',
					'query_builder' => function (SaleRepository $sr)
					{
						return $sr->createQueryBuilder('s')
							->orderBy('s.number', 'ASC');
					}, 'choice_label' => 'number', 'multiple' => false, 'by_reference' => true, 'required' => true));
		} else {
			$sale_id = $this->sale->getId();
			$builder->add('sale', EntityidType::class,
				array('label' => 'SecondatVat.sale.label', 'class' => 'AcfDataBundle:Sale',
					'query_builder' => function (SaleRepository $sr) use ($sale_id)
					{
						return $sr->createQueryBuilder('s')
							->where('s.id = :id')
							->setParameter('id', $sale_id)
							->orderBy('s.number', 'ASC');
					}, 'choice_label' => 'id', 'multiple' => false, 'by_reference' => true, 'required' => true))

			;
		}

		$builder->add('vat', NumberType::class, array('label' => 'SecondaryVat.vat.label'));

		$builder->add('balanceTtc', NumberType::class, array('label' => 'SecondaryVat.balanceTtc.label'));

		$builder->add('balanceNet', NumberType::class, array('label' => 'SecondaryVat.balanceNet.label'));

		$builder->add('vatInfo', ChoiceType::class,
			array('label' => 'SecondaryVat.vatInfo.label', 'choices_as_values' => true, 'choices' => SecondaryVat::choiceVatInfo(),
				'attr' => array('choice_label_trans' => true)));
	}

	/**
	 *
	 * {@inheritDoc} @see FormTypeInterface::getName()
	 * @return string
	 */
	public function getName()
	{
		return 'SecondaryVatNewForm';
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
		return array('validation_groups' => array('vat', 'balanceTtc', 'vatInfo', 'balanceNet'), 'sale' => null);
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
