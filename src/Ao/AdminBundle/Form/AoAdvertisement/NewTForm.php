<?php
namespace Ao\AdminBundle\Form\AoAdvertisement;

use Acf\DataBundle\Entity\AoAdvertisement;
use Acf\DataBundle\Entity\AoSubCateg;
use Acf\DataBundle\Repository\AoSubCategRepository;
use Sasedev\Form\EntityidBundle\Form\Type\EntityidType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class NewTForm extends AbstractType
{

    /**
     *
     * @var AoSubCateg
     */
    private $grp;

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
        $this->grp = $options['grp'];

        if (null == $this->grp) {
            $builder->add('grp', EntityType::class, array(
                'label' => 'AoAdvertisement.grp.label',
                'class' => 'AcfDataBundle:AoSubCateg',
                'query_builder' => function (AoSubCategRepository $br) {
                    return $br->createQueryBuilder('sc')
                        ->orderBy('sc.priority', 'ASC')
                        ->addOrderBy('sc.ref', 'ASC');
                },
                'choice_label' => 'ref',
                'multiple' => false,
                'by_reference' => true,
                'required' => true
            ));
        } else {
            $grpId = $this->grp->getId();
            $builder->add('grp', EntityidType::class, array(
                'label' => 'AoAdvertisement.grp.label',
                'class' => 'AcfDataBundle:AoSubCateg',
                'query_builder' => function (AoSubCategRepository $br) use ($grpId) {
                    return $br->createQueryBuilder('sc')
                        ->where('sc.id = :id')
                        ->setParameter('id', $grpId)
                        ->orderBy('sc.priority', 'ASC')
                        ->addOrderBy('sc.ref', 'ASC');
                },
                'choice_label' => 'id',
                'multiple' => false,
                'by_reference' => true,
                'required' => true
            ));
        }

        $builder->add('img', FileType::class, array(
            'label' => 'AoAdvertisement.img.label',
            'required' => false
        ));

        $builder->add('dtPublication', DateType::class, array(
            'label' => 'AoAdvertisement.dtPublication.label',
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd'
        ));

        $builder->add('country', TextType::class, array(
            'label' => 'AoAdvertisement.country.label'
        ));

        $builder->add('description', TextareaType::class, array(
            'label' => 'AoAdvertisement.description.label',
            'required' => false
        ));

        $builder->add('company', TextType::class, array(
            'label' => 'AoAdvertisement.company.label'
        ));

        $builder->add('nature', ChoiceType::class, array(
            'label' => 'AoAdvertisement.nature.label',
            'choices' => AoAdvertisement::choiceNaturetype(),
            'expanded' => false,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('dtEnd', DateType::class, array(
            'label' => 'AoAdvertisement.dtEnd.label',
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd'
        ));

        $builder->add('aoVe', ChoiceType::class, array(
            'label' => 'AoAdvertisement.aoVe.label',
            'choices' => AoAdvertisement::choiceAoVe(),
            'expanded' => false,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('dtOpen', DateType::class, array(
            'label' => 'AoAdvertisement.dtOpen.label',
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'required' => false
        ));

        $builder->add('adress', TextType::class, array(
            'label' => 'AoAdvertisement.adress.label'
        ));

        $builder->add('price', TextType::class, array(
            'label' => 'AoAdvertisement.price.label'
        ));

        $builder->add('typeAvis', ChoiceType::class, array(
            'label' => 'AoAdvertisement.typeAvis.label',
            'choices' => AoAdvertisement::choiceTypeAvis(),
            'expanded' => false,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('addRef', TextType::class, array(
            'label' => 'AoAdvertisement.addRef.label',
            'required' => false
        ));

        $builder->add('source', TextType::class, array(
            'label' => 'AoAdvertisement.source.label'
        ));

        $builder->add('status', ChoiceType::class, array(
            'label' => 'AoAdvertisement.status.label',
            'choices' => AoAdvertisement::choiceStatus(),
            'expanded' => false,
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
        return 'AoAdvertisementNewForm';
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
                'grp',
                'img',
                'dtPublication',
                'country',
                'description',
                'company',
                'nature',
                'dtEnd',
                'aoVe',
                'dtOpen',
                'adress',
                'price',
                'typeAvis',
                'addRef',
                'source',
                'status'
            ),
            'grp' => null
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
