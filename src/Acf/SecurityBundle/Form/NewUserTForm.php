<?php
namespace Acf\SecurityBundle\Form;

use Acf\DataBundle\Entity\User;
use Acf\DataBundle\Repository\LangRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class NewUserTForm extends AbstractType
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
        $builder->add('username', TextType::class, array(
            'label' => 'NewUser.username.label'
        ));

        $builder->add('email', EmailType::class, array(
            'label' => 'NewUser.email.label'
        ));

        $builder->add('preferedLang', EntityType::class, array(
            'label' => 'NewUser.preferedLang.label',
            'class' => 'AcfDataBundle:Lang',
            'query_builder' => function (LangRepository $lr) {
                return $lr->createQueryBuilder('l')->orderBy('l.locale', 'ASC');
            },
            'choice_label' => 'fullLocale',
            'multiple' => false,
            'by_reference' => true,
            'required' => false,
            'placeholder' => 'Options.choose',
            'empty_data' => null
        ));

        $builder->add('sexe', ChoiceType::class, array(
            'label' => 'NewUser.sexe.label',
            'choices_as_values' => true,
            'choices' => User::choiceSexe(),
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('lastName', TextType::class, array(
            'label' => 'NewUser.lastName.label'
        ));

        $builder->add('firstName', TextType::class, array(
            'label' => 'NewUser.firstName.label'
        ));

        $builder->add('birthday', DateType::class, array(
            'label' => 'NewUser.birthday.label',
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'required' => false
        ));

        $builder->add('corporateName', TextType::class, array(
            'label' => 'NewUser.corporateName.label',
            'required' => false,
            'mapped' => false
        ));
        $builder->add('type', TextType::class, array(
            'label' => 'NewUser.type.label',
            'required' => false,
            'mapped' => false
        ));
        $builder->add('fisc', TextType::class, array(
            'label' => 'NewUser.fisc.label',
            'required' => false,
            'mapped' => false
        ));
        $builder->add('commercialRegister', TextType::class, array(
            'label' => 'NewUser.commercialRegister.label',
            'required' => false,
            'mapped' => false
        ));

        $builder->add('streetNum', IntegerType::class, array(
            'label' => 'NewUser.streetNum.label',
            'scale' => 0
        ));

        $builder->add('address', TextareaType::class, array(
            'label' => 'NewUser.address.label'
        ));

        $builder->add('address2', TextareaType::class, array(
            'label' => 'NewUser.address2.label',
            'required' => false
        ));

        $builder->add('town', TextType::class, array(
            'label' => 'NewUser.town.label'
        ));

        $builder->add('zipCode', TextType::class, array(
            'label' => 'NewUser.zipCode.label'
        ));

        $builder->add('country', CountryType::class, array(
            'label' => 'NewUser.country.label',
            'data' => 'TN'
        ));

        $builder->add('phone', TextType::class, array(
            'label' => 'NewUser.phone.label'
        ));

        $builder->add('mobile', TextType::class, array(
            'label' => 'NewUser.mobile.label'
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'NewUserForm';
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
                'username',
                'email',
                'sexe',
                'birthday',
                'streetNum',
                'town',
                'zipCode',
                'country',
                'phone',
                'mobile'
            ),
            'csrf_protection' => false
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
