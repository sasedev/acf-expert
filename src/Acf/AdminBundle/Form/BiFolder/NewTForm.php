<?php
namespace Acf\AdminBundle\Form\BiFolder;

use Acf\DataBundle\Repository\BiFolderRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, array(
            'label' => 'BiFolder.title.label'
        ));

        $builder->add('parent', EntityType::class, array(
            'label' => 'BiFolder.parent.label',
            'class' => 'AcfDataBundle:BiFolder',
            'query_builder' => function (BiFolderRepository $dgr) {
                return $dgr->createQueryBuilder('d')->orderBy('d.pageUrlFull', 'ASC');
            },
            'choice_label' => 'pageUrlFull',
            'multiple' => false,
            'by_reference' => true,
            'required' => false,
            'placeholder' => 'Options.choose',
            'empty_data' => null
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'BiFolderNewForm';
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
                'parent'
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
