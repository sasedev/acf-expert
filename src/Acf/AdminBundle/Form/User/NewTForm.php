<?php
namespace Acf\AdminBundle\Form\User;

use Acf\DataBundle\Entity\User;
use Acf\DataBundle\Repository\RoleRepository;
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
        $builder->add('username', TextType::class, array(
            'label' => 'User.username.label'
        ));

        $builder->add('email', EmailType::class, array(
            'label' => 'User.email.label'
        ));

        $builder->add('lockout', ChoiceType::class, array(
            'label' => 'User.lockout.label',
            'choices' => User::choiceLockout(),
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('preferedLang', EntityType::class, array(
            'label' => 'User.preferedLang.label',
            'class' => 'AcfDataBundle:Lang',
            'query_builder' => function (LangRepository $lr) {
                return $lr->createQueryBuilder('l')
                    ->orderBy('l.locale', 'ASC');
            },
            'choice_label' => 'fullLocale',
            'multiple' => false,
            'by_reference' => true,
            'required' => false,
            'placeholder' => 'Options.choose',
            'empty_data' => null
        ));

        $builder->add('sexe', ChoiceType::class, array(
            'label' => 'User.sexe.label',

            'choices' => User::choiceSexe(),
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('firstName', TextType::class, array(
            'label' => 'User.firstName.label',
            'required' => false
        ));

        $builder->add('lastName', TextType::class, array(
            'label' => 'User.lastName.label',
            'required' => false
        ));

        $builder->add('birthday', DateType::class, array(
            'label' => 'User.birthday.label',
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'required' => false
        ));

        $builder->add('streetNum', IntegerType::class, array(
            'label' => 'User.streetNum.label',
            'scale' => 0,
            'required' => false
        ));

        $builder->add('address', TextareaType::class, array(
            'label' => 'User.address.label',
            'required' => false
        ));

        $builder->add('address2', TextareaType::class, array(
            'label' => 'User.address2.label',
            'required' => false
        ));

        $builder->add('town', TextType::class, array(
            'label' => 'User.town.label',
            'required' => false
        ));

        $builder->add('zipCode', TextType::class, array(
            'label' => 'User.zipCode.label',
            'required' => false
        ));

        $builder->add('country', CountryType::class, array(
            'label' => 'User.country.label',
            'required' => false,
            'placeholder' => 'Options.choose',
            'empty_data' => null
        ));

        $builder->add('phone', TextType::class, array(
            'label' => 'User.phone.label',
            'required' => false
        ));

        $builder->add('mobile', TextType::class, array(
            'label' => 'User.mobile.label',
            'required' => false
        ));

        $builder->add('userRoles', EntityType::class, array(
            'label' => 'User.userRoles.label',
            'class' => 'AcfDataBundle:Role',
            'query_builder' => function (RoleRepository $rr) {
                return $rr->createQueryBuilder('r')
                    ->orderBy('r.name', 'ASC');
            },
            'choice_label' => 'name',
            'multiple' => true,
            'by_reference' => true,
            'required' => true,
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
        return 'UserNewForm';
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
                'Default',
                'admRegistration'
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
