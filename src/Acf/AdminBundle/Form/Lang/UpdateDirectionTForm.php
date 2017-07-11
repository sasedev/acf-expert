<?php
namespace Acf\AdminBundle\Form\Lang;

use Acf\DataBundle\Entity\Lang;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class UpdateDirectionTForm extends AbstractType
{

    /**
     * Form builder
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('direction', ChoiceType::class, array(
            'label' => 'Lang.direction.label',
            'choices' => Lang::choiceDirection(),
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
        return 'LangUpdateDirectionForm';
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
                'direction'
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
