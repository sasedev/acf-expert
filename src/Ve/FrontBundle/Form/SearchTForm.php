<?php
namespace Ve\FrontBundle\Form;

use Acf\DataBundle\Entity\AoAuction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class SearchTForm extends AbstractType
{

    /**
     * Form builder
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $country = $options['country'];
        $nature = $options['nature'];
        $dtPublicationBegin = $options['dtPublicationBegin'];
        $dtPublicationEnd = $options['dtPublicationEnd'];
        $dtEndBegin = $options['dtEndBegin'];
        $dtEndEnd = $options['dtEndEnd'];
        $dtOpenBegin = $options['dtOpenBegin'];
        $dtOpenEnd = $options['dtOpenEnd'];

        $builder->add('country', TextType::class, array(
            'label' => 'AoAuction.country.label',
            'required' => false,
            'mapped' => false,
            'data' => $country
        ));

        $builder->add('nature', ChoiceType::class, array(
            'label' => 'AoAuction.nature.label',
            'choices' => AoAuction::choiceNaturetype(),
            'expanded' => false,
            'required' => false,
            'mapped' => false,
            'data' => $nature
        ));

        $builder->add('dtPublicationBegin', DateType::class, array(
            'label' => 'AoAuction.dtPublication.begin.label',
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'required' => false,
            'mapped' => false,
            'data' => $dtPublicationBegin
        ));

        $builder->add('dtPublicationEnd', DateType::class, array(
            'label' => 'AoAuction.dtPublication.end.label',
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'required' => false,
            'mapped' => false,
            'data' => $dtPublicationEnd
        ));

        $builder->add('dtEndBegin', DateType::class, array(
            'label' => 'AoAuction.dtEnd.begin.label',
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'required' => false,
            'mapped' => false,
            'data' => $dtEndBegin
        ));

        $builder->add('dtEndEnd', DateType::class, array(
            'label' => 'AoAuction.dtEnd.end.label',
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'required' => false,
            'mapped' => false,
            'data' => $dtEndEnd
        ));

        $builder->add('dtOpenBegin', DateType::class, array(
            'label' => 'AoAuction.dtOpen.begin.label',
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'required' => false,
            'mapped' => false,
            'data' => $dtOpenBegin
        ));

        $builder->add('dtOpenEnd', DateType::class, array(
            'label' => 'AoAuction.dtOpen.end.label',
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'required' => false,
            'mapped' => false,
            'data' => $dtOpenEnd
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'SearchForm';
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
                'Default'
            ),
            'country' => null,
            'nature' => null,
            'dtPublicationBegin' => null,
            'dtPublicationEnd' => null,
            'dtEndBegin' => null,
            'dtEndEnd' => null,
            'dtOpenBegin' => null,
            'dtOpenEnd' => null,
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
