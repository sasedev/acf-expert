<?php
namespace Acf\AdminBundle\Form\Taxe;

use Acf\DataBundle\Entity\OnlineTaxe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
        $builder->add('label', TextType::class, array(
            'label' => 'Taxe.label.label'
        ));

        $builder->add('value', NumberType::class, array(
            'label' => 'Taxe.value.label'
        ));

        $builder->add('type', ChoiceType::class, array(
            'label' => 'Taxe.type.label',
            'choices_as_values' => true,
            'choices' => OnlineTaxe::choiceType(),
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('visible', ChoiceType::class, array(
            'label' => 'Taxe.visible.label',
            'choices_as_values' => true,
            'choices' => OnlineTaxe::choiceVisible(),
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('priority', NumberType::class, array(
            'label' => 'Taxe.priority.label'
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'TaxeNewForm';
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
                'value',
                'type',
                'visible',
                'priority'
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
