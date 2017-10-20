<?php
namespace Acf\ClientBundle\Form\Sale;

use Acf\DataBundle\Entity\Vat;
use Acf\DataBundle\Repository\VatRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class UpdateVatInfoTForm extends AbstractType
{

    /**
     * Form builder
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('vatInfo', EntityType::class, array(
            'class' => 'AcfDataBundle:Vat',
            'label' => 'Sale.vatInfo.label',
            'query_builder' => function (VatRepository $vr) {
                return $vr->createQueryBuilder('v')
                    ->orderBy('v.title', 'ASC');
            },
            'choice_label' => 'title',
            'choice_value' => function ($entity = null) {
                if ($entity instanceof Vat) {
                    return $entity ? $entity->getTitle() : '';
                } else {
                    return $entity;
                }
            },
            'multiple' => false,
            'required' => true
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'SaleUpdateVatInfoForm';
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
                'vatInfo'
            )
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
