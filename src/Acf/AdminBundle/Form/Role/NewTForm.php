<?php
namespace Acf\AdminBundle\Form\Role;

use Acf\DataBundle\Repository\RoleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
        $builder->add('parents', EntityType::class, array(
            'label' => 'Role.parents.label',
            'class' => 'AcfDataBundle:Role',
            'query_builder' => function (RoleRepository $rr) {
                return $rr->createQueryBuilder('r')
                    ->orderBy('r.name', 'ASC');
            },
            'choice_label' => 'description',
            'multiple' => true,
            'by_reference' => true,
            'required' => false
        ));

        $builder->add('name', TextType::class, array(
            'label' => 'Role.name.label'
        ));

        $builder->add('description', TextareaType::class, array(
            'label' => 'Role.description.label',
            'required' => false
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'RoleNewForm';
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
                'name'
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
