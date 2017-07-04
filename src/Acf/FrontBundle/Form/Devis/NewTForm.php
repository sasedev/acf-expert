<?php
namespace Acf\FrontBundle\Form\Devis;

use Sasedev\Commons\SharedBundle\Validator\Phone;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

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
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lastname', TextType::class, array(
            'label' => 'Devis.lastname.label',
            'mapped' => false,
            'constraints' => new NotBlank()
        ));

        $builder->add('firstname', TextType::class, array(
            'label' => 'Devis.firstname.label',
            'mapped' => false,
            'constraints' => new NotBlank()
        ));

        $builder->add('email', EmailType::class, array(
            'label' => 'Devis.email.label',
            'mapped' => false,
            'constraints' => array(
                new NotBlank(),
                new Email(array(
                    'checkMX' => true,
                    'checkHost' => true
                ))
            )
        ));

        $builder->add('phone', TextType::class, array(
            'label' => 'Devis.phone.label',
            'mapped' => false,
            'constraints' => array(
                new NotBlank(),
                new Phone()
            )
        ));

        $builder->add('entreprise', TextType::class, array(
            'label' => 'Devis.entreprise.label',
            'mapped' => false,
            'constraints' => array(
                new NotBlank()
            )
        ));

        $builder->add('entrepriseForm', TextType::class, array(
            'label' => 'Devis.entrepriseForm.label',
            'mapped' => false,
            'constraints' => array(
                new NotBlank()
            )
        ));

        $builder->add('activity', TextType::class, array(
            'label' => 'Devis.activity.label',
            'mapped' => false,
            'constraints' => array(
                new NotBlank()
            )
        ));

        $builder->add('dtActivation', DateType::class, array(
            'label' => 'Devis.dtActivation.label',
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'mapped' => false,
            'required' => false,
            'constraints' => array(
                new GreaterThanOrEqual(array(
                    'value' => '1950-01-01'
                )),
                new LessThan((array(
                    'value' => '2100-01-01'
                )))
            )
        ));

        $builder->add('address', TextareaType::class, array(
            'label' => 'Devis.address.label',
            'mapped' => false,
            'constraints' => array(
                new NotBlank()
            )
        ));

        $builder->add('zipCode', TextType::class, array(
            'label' => 'Devis.zipCode.label',
            'mapped' => false,
            'constraints' => array(
                new NotBlank()
            )
        ));

        $builder->add('town', TextType::class, array(
            'label' => 'Devis.town.label',
            'mapped' => false,
            'constraints' => array(
                new NotBlank()
            )
        ));

        $builder->add('caYear', NumberType::class, array(
            'label' => 'Devis.caYear.label',
            'mapped' => false,
            'required' => false,
            'constraints' => array(
                new GreaterThanOrEqual(array(
                    'value' => 0
                ))
            )
        ));

        $builder->add('nbrInvoicesBuy', IntegerType::class, array(
            'label' => 'Devis.nbrInvoicesBuy.label',
            'mapped' => false,
            'required' => false,
            'data' => 0,
            'constraints' => array(
                new GreaterThanOrEqual(array(
                    'value' => 0
                ))
            )
        ));

        $builder->add('nbrInvoicesSale', IntegerType::class, array(
            'label' => 'Devis.nbrInvoicesSale.label',
            'mapped' => false,
            'required' => false,
            'data' => 0,
            'constraints' => array(
                new GreaterThanOrEqual(array(
                    'value' => 0
                ))
            )
        ));

        $builder->add('nbrSalaries', IntegerType::class, array(
            'label' => 'Devis.nbrSalaries.label',
            'mapped' => false,
            'required' => false,
            'data' => 0,
            'constraints' => array(
                new GreaterThanOrEqual(array(
                    'value' => 0
                ))
            )
        ));

        $builder->add('hasexpert', ChoiceType::class, array(
            'label' => 'Devis.hasexpert.label',
            'choices_as_values' => true,
            'choices' => array(
                'Devis.hasexpert.1' => 1,
                'Devis.hasexpert.2' => 2
            ),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            ),
            'mapped' => false
        ));

        $builder->add('otherInfos', TextareaType::class, array(
            'label' => 'Devis.otherInfos.label',
            'required' => false,
            'mapped' => false
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'DevisNewForm';
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
                'Default'
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
