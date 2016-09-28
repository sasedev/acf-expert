<?php
namespace Acf\SecurityBundle\Form;

use Acf\DataBundle\Entity\OnlineOrder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class NewOnlineOrderTForm extends AbstractType
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
    $builder->add('orderTo', TextareaType::class, array(
      'label' => 'OnlineOrder.orderTo.label'
    ));

    $builder->add('renew', ChoiceType::class, array(
      'label' => 'OnlineOrder.renew.label',
      'choices_as_values' => true,
      'choices' => OnlineOrder::choiceRenew(),
      'expanded' => true,
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
    return 'NewOnlineOrderForm';
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
        'orderTo'
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