<?php
namespace Acf\AdminBundle\Form\Order;

use Acf\DataBundle\Entity\OnlineOrder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class UpdateStatusTForm extends AbstractType
{

  /**
   * Form builder
   *
   * @param FormBuilderInterface $builder
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('status', ChoiceType::class, array(
      'label' => 'Order.status.label',
      'choices_as_values' => true,
      'choices' => OnlineOrder::choiceStatus(),
      'attr' => array(
        'choice_label_trans' => true
      )
    ));
  }

  /**
   *
   * {@inheritdoc} @see FormTypeInterface::getName()
   * @return string
   */
  public function getName()
  {
    return 'OrderUpdateStatusForm';
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
        'status'
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
