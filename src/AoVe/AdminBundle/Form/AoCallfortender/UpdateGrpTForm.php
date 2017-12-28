<?php
namespace AoVe\AdminBundle\Form\AoCallfortender;

use Acf\DataBundle\Repository\AoSubCategRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class UpdateGrpTForm extends AbstractType
{

    /**
     * Form builder
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('grp', EntityType::class, array(
            'label' => 'AoCallfortender.grp.label',
            'class' => 'AcfDataBundle:AoSubCateg',
            'query_builder' => function (AoSubCategRepository $br) {
                return $br->createQueryBuilder('sc')
                    ->orderBy('sc.priority', 'ASC')
                    ->addOrderBy('sc.ref', 'ASC');
            },
            'multiple' => false,
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
        return 'AoCallfortenderUpdateGrpForm';
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
                'grp'
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
