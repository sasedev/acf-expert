<?php
namespace Acf\AdminBundle\Form\BiFolder;

use Acf\DataBundle\Repository\BiFolderRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class UpdateParentTForm extends AbstractType
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
        $selfUrl = $options['selfUrl'];

        $builder->add('parent', EntityType::class, array(
            'label' => 'BiFolder.parent.label',
            'class' => 'AcfDataBundle:BiFolder',
            'query_builder' => function (BiFolderRepository $dgr) use ($selfUrl) {
                $qb = $dgr->createQueryBuilder('d')
                    ->where('d.pageUrlFull NOT LIKE :url');
                $qb->setParameter('url', $selfUrl . '%');

                return $qb->addOrderBy('d.pageUrlFull', 'ASC');
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
     * {@inheritdoc} @see FormTypeInterface::getName()
     * @return string
     */
    public function getName()
    {
        return 'BiFolderUpdateParentForm';
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
                'parent'
            ),
            'selfUrl' => ''
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
