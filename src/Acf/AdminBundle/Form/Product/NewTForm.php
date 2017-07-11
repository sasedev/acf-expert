<?php
namespace Acf\AdminBundle\Form\Product;

use Acf\DataBundle\Entity\OnlineProduct;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class NewTForm extends AbstractType
{

    /**
     * Form builder
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @return null
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, array(
            'label' => 'Product.title.label'
        ));

        $builder->add('label', TextType::class, array(
            'label' => 'Product.label.label'
        ));

        $builder->add('description', TextareaType::class, array(
            'label' => 'Product.description.label'
        ));

        $builder->add('price', NumberType::class, array(
            'label' => 'Product.price.label'
        ));

        $builder->add('vat', NumberType::class, array(
            'label' => 'Product.vat.label'
        ));

        $builder->add('lockout', ChoiceType::class, array(
            'label' => 'Product.lockout.label',
            'choices' => OnlineProduct::choiceLockout(),
            'attr' => array(
                'choice_label_trans' => true
            )
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'ProductNewForm';
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
                'label',
                'title',
                'description',
                'price',
                'vat',
                'lockout'
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
