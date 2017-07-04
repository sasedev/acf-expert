<?php
namespace Acf\AdminBundle\Form\Shareholder;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class UpdateTForm extends AbstractType
{

    /**
     * Form builder
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array(
            'label' => 'Shareholder.name.label'
        ));

        $builder->add('cin', TextType::class, array(
            'label' => 'Shareholder.cin.label',
            'required' => false
        ));

        $builder->add('quality', TextType::class, array(
            'label' => 'Shareholder.quality.label',
            'required' => false
        ));

        $builder->add('address', TextareaType::class, array(
            'label' => 'Shareholder.address.label',
            'required' => false
        ));

        $builder->add('trades', IntegerType::class, array(
            'label' => 'Shareholder.trades.label'
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'ShareholderUpdateForm';
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
                'name',
                'cin',
                'quality',
                'address',
                'trades'
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
