<?php
namespace Acf\AdminBundle\Form\Bank;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class UpdateOtherInfosTForm extends AbstractType
{

    /**
     * Form builder
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('contact', TextType::class, array(
            'label' => 'Bank.contact.label',
            'required' => false
        ));

        $builder->add('tel', TextType::class, array(
            'label' => 'Bank.tel.label',
            'required' => false
        ));

        $builder->add('fax', TextType::class, array(
            'label' => 'Bank.fax.label',
            'required' => false
        ));

        $builder->add('email', EmailType::class, array(
            'label' => 'Bank.email.label',
            'required' => false
        ));

        $builder->add('otherInfos', TextareaType::class, array(
            'label' => 'Bank.otherInfos.label',
            'required' => false
        ));
    }

    /**
     *
     * {@inheritdoc} @see \Symfony\Component\Form\FormTypeInterface::getName()
     * @return string
     */
    public function getName()
    {
        return 'BankUpdateOtherInfosForm';
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
                'contact',
                'tel',
                'fax',
                'email'
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
