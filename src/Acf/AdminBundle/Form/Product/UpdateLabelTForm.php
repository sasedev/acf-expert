<?php
namespace Acf\AdminBundle\Form\Product;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class UpdateLabelTForm extends AbstractType
{

  /**
   * Form builder
   *
   * @param FormBuilderInterface $builder
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
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
    return 'ProductUpdateLabelForm';
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
