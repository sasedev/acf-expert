<?php
namespace Acf\AdminBundle\Form\Product;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class UpdatePriceTForm extends AbstractType
{

  /**
   * Form builder
   *
   * @param FormBuilderInterface $builder
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('price', NumberType::class, array(
      'label' => 'Product.price.label'
    ));
  }

  /**
   *
   * {@inheritdoc} @see FormTypeInterface::getName()
   * @return string
   */
  public function getName()
  {
    return 'ProductUpdatePriceForm';
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
        'price'
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
