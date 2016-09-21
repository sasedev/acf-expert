<?php
namespace Acf\AdminBundle\Form\Product;

use Acf\DataBundle\Entity\OnlineProduct;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
   * Form builder
   *
   * @param FormBuilderInterface $builder
   * @param array $options
   *
   * @return null
   */
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('price', NumberType::class, array(
      'label' => 'Product.price.label'
    ));

    $builder->add('vat', NumberType::class, array(
      'label' => 'Product.vat.label'
    ));

    $builder->add('lockout', ChoiceType::class, array(
      'label' => 'Product.lockout.label',
      'choices_as_values' => true,
      'choices' => OnlineProduct::choiceLockout(),
      'attr' => array(
        'choice_label_trans' => true
      )
    ));

    $builder->add('label', TextareaType::class, array(
      'label' => 'Product.label.label'
    ));
  }

  /**
   *
   * {@inheritdoc} @see FormTypeInterface::getName()
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
        'price',
        'vat',
        'lockout',
        'label'
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
