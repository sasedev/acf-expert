<?php
namespace Acf\SecurityBundle\Form;

use Acf\DataBundle\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class EventAddAdminTForm extends AbstractType
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
        $builder->add('title', TextType::class, array(
            'label' => 'Agenda.title.label'
        ));

        $builder->add('comment', TextareaType::class, array(
            'label' => 'Agenda.comment.label'
        ));

        $builder->add('dtStart', DateTimeType::class, array(
            'label' => 'Agenda.dtStart.label',
            'widget' => 'single_text',
            'date_format' => 'Y-m-d\TH:i:sO',
            'attr' => array(
                'readonly' => true
            )
        ));

        $builder->add('dtEnd', DateTimeType::class, array(
            'label' => 'Agenda.dtEnd.label',
            'widget' => 'single_text',
            'date_format' => 'Y-m-d\TH:i:sO',
            'attr' => array(
                'readonly' => true
            )
        ));

        $builder->add('users', EntityType::class, array(
            'label' => 'Agenda.users.label',
            'class' => 'AcfDataBundle:User',
            'query_builder' => function (UserRepository $ur) {
                return $ur->createQueryBuilder('u')->orderBy('u.username', 'ASC');
            },
            'choice_label' => 'fullName',
            'multiple' => true,
            'by_reference' => true,
            'required' => false
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'EventAddForm';
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
                'title',
                'comment',
                'users'
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
