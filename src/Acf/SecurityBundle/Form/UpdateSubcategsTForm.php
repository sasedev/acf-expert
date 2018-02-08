<?php
namespace Acf\SecurityBundle\Form;

use Acf\DataBundle\Repository\AoSubCategRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class UpdateSubcategsTForm extends AbstractType
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
        $builder->add('subcategs', EntityType::class, array(
            'label' => 'User.subcategs.label',
            'class' => 'AcfDataBundle:AoSubCateg',
            'query_builder' => function (AoSubCategRepository $scr) {
                return $scr->createQueryBuilder('sc')
                    ->orderBy('sc.priority', 'ASC')
                    ->addOrderBy('sc.ref', 'ASC');
            },
            'multiple' => true,
            'expanded' => true,
            'by_reference' => true,
            'required' => true
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'UserUpdateSubcategsForm';
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
                'subcategs'
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
