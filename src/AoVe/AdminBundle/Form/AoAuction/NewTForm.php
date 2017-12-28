<?php
namespace AoVe\AdminBundle\Form\AoAuction;

use Acf\DataBundle\Entity\AoAuction;
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
     * Form builder
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @return null
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('img', FileType::class, array(
            'label' => 'AoAuction.img.label',
            'required' => false
        ));

        $builder->add('dtPublication', DateType::class, array(
            'label' => 'AoAuction.dtPublication.label',
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd'
        ));

        $builder->add('country', TextType::class, array(
            'label' => 'AoAuction.country.label'
        ));

        $builder->add('description', TextareaType::class, array(
            'label' => 'AoAuction.description.label',
            'required' => false
        ));

        $builder->add('company', TextType::class, array(
            'label' => 'AoAuction.company.label'
        ));

        $builder->add('nature', ChoiceType::class, array(
            'label' => 'AoAuction.nature.label',
            'choices' => AoAuction::choiceNaturetype(),
            'expanded' => false,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('dtEnd', DateType::class, array(
            'label' => 'AoAuction.dtEnd.label',
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'required' => false
        ));

        $builder->add('dtOpen', DateType::class, array(
            'label' => 'AoAuction.dtOpen.label',
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'required' => false
        ));

        $builder->add('adress', TextType::class, array(
            'label' => 'AoAuction.adress.label',
            'required' => false
        ));

        $builder->add('price', TextType::class, array(
            'label' => 'AoAuction.price.label',
            'required' => false
        ));

        $builder->add('addRef', TextType::class, array(
            'label' => 'AoAuction.addRef.label',
            'required' => false
        ));

        $builder->add('source', TextType::class, array(
            'label' => 'AoAuction.source.label',
            'required' => false
        ));

        $builder->add('status', ChoiceType::class, array(
            'label' => 'AoAuction.status.label',
            'choices' => AoAuction::choiceStatus(),
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
        return 'AoAuctionNewForm';
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
                'img',
                'dtPublication',
                'country',
                'description',
                'company',
                'nature',
                'dtEnd',
                'dtOpen',
                'adress',
                'price',
                'addRef',
                'source',
                'status'
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
