<?php
namespace Acf\ClientBundle\Form\Sale\SecondaryVat;

use Acf\DataBundle\Entity\SecondaryVat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class SaleSecondaryVatTForm extends AbstractType
{

    /**
     * Form builder
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('vatInfo', ChoiceType::class, array(
            'label' => 'SecondaryVat.vatInfo.label',
            'choices_as_values' => true,
            'choices' => SecondaryVat::choiceVatInfo(),
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('vat', NumberType::class, array(
            'label' => 'SecondaryVat.vat.label'
        ));

        $builder->add('balanceTtc', NumberType::class, array(
            'label' => 'SecondaryVat.balanceTtc.label'
        ));

        $builder->add('balanceNet', NumberType::class, array(
            'label' => 'SecondaryVat.balanceNet.label'
        ));
    }

    /**
     *
     * {@inheritdoc} @see \Symfony\Component\Form\FormTypeInterface::getName()
     * @return string
     */
    public function getName()
    {
        return 'SaleSecondaryVatForm';
    }

    /**
     *
     * {@inheritdoc} @see AbstractType::getBlockPrefix()
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
                'vatInfo',
                'vat',
                'balanceTtc',
                'balanceNet'
            ),
            'data_class' => 'Acf\DataBundle\Entity\SecondaryVat',
            'csrf_protection' => false
        );
    }

    /**
     *
     * {@inheritdoc} @see AbstractType::configureOptions()
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults($this->getDefaultOptions());
    }
}
