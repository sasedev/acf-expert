<?php
namespace Ao\FrontBundle\Form;

use Acf\DataBundle\Entity\AoCallfortender;
use Acf\DataBundle\Repository\AoSubCategRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
        $grp = $options['grp'];
        $typeAvis = $options['typeAvis'];
        $nature = $options['nature'];
        $dtPublicationBegin = $options['dtPublicationBegin'];
        $dtPublicationEnd = $options['dtPublicationEnd'];
        $dtEndBegin = $options['dtEndBegin'];
        $dtEndEnd = $options['dtEndEnd'];

        $builder->add('country', TextType::class, array(
            'label' => 'AoCallfortender.country.label',
            'required' => false,
            'mapped' => false,
            'data' => $country
        ));

        $builder->add('grp', EntityType::class, array(
            'label' => 'AoCallfortender.grp.label',
            'class' => 'AcfDataBundle:AoSubCateg',
            'query_builder' => function (AoSubCategRepository $br) {
                return $br->createQueryBuilder('sc')
                    ->orderBy('sc.priority', 'ASC')
                    ->addOrderBy('sc.ref', 'ASC');
            },
            'multiple' => false,
            'by_reference' => false,
            'required' => false,
            'mapped' => false,
            'data' => $grp
        ));

        $builder->add('typeAvis', ChoiceType::class, array(
            'label' => 'AoCallfortender.typeAvis.label',
            'choices' => AoCallfortender::choiceTypeAvis(),
            'expanded' => false,
            'required' => false,
            'mapped' => false,
            'data' => $typeAvis
        ));

        $builder->add('nature', ChoiceType::class, array(
            'label' => 'AoCallfortender.nature.label',
            'choices' => AoCallfortender::choiceNaturetype(),
            'expanded' => false,
            'required' => false,
            'mapped' => false,
            'data' => $nature
        ));

        $builder->add('dtPublicationBegin', DateType::class, array(
            'label' => 'AoCallfortender.dtPublication.begin.label',
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'required' => false,
            'mapped' => false,
            'data' => $dtPublicationBegin
        ));

        $builder->add('dtPublicationEnd', DateType::class, array(
            'label' => 'AoCallfortender.dtPublication.end.label',
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'required' => false,
            'mapped' => false,
            'data' => $dtPublicationEnd
        ));

        $builder->add('dtEndBegin', DateType::class, array(
            'label' => 'AoCallfortender.dtEnd.begin.label',
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'required' => false,
            'mapped' => false,
            'data' => $dtEndBegin
        ));

        $builder->add('dtEndEnd', DateType::class, array(
            'label' => 'AoCallfortender.dtEnd.end.label',
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'required' => false,
            'mapped' => false,
            'data' => $dtEndEnd
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
            'grp' => null,
            'typeAvis' => null,
            'nature' => null,
            'dtPublicationBegin' => null,
            'dtPublicationEnd' => null,
            'dtEndBegin' => null,
            'dtEndEnd' => null,
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
