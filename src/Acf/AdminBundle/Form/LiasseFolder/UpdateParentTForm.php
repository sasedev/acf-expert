<?php
namespace Acf\AdminBundle\Form\LiasseFolder;

use Acf\DataBundle\Repository\LiasseFolderRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
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
            'label' => 'LiasseFolder.parent.label',
            'class' => 'AcfDataBundle:LiasseFolder',
            'query_builder' => function (LiasseFolderRepository $dgr) use ($selfUrl) {
                $qb = $dgr->createQueryBuilder('d')->where('d.pageUrlFull NOT LIKE :url');
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
     * @return string
     */
    public function getName()
    {
        return 'LiasseFolderUpdateParentForm';
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
