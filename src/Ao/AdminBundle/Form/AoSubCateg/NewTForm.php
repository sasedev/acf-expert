<?php
namespace Ao\AdminBundle\Form\AoSubCateg;

use Acf\DataBundle\Entity\AoCateg;
use Acf\DataBundle\Repository\AoCategRepository;
use Sasedev\Form\EntityidBundle\Form\Type\EntityidType;
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
     *
     * @var AoCateg
     */
    private $categ;

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
        $this->categ = $options['categ'];

        if (null == $this->categ) {
            $builder->add('categ', EntityType::class, array(
                'label' => 'AoSubCateg.categ.label',
                'class' => 'AcfDataBundle:AoCateg',
                'query_builder' => function (AoCategRepository $br) {
                    return $br->createQueryBuilder('c')
                        ->orderBy('c.title', 'ASC');
                },
                'choice_label' => 'title',
                'multiple' => false,
                'by_reference' => true,
                'required' => true
            ));
        } else {
            $categId = $this->categ->getId();
            $builder->add('categ', EntityidType::class, array(
                'label' => 'AoSubCateg.categ.label',
                'class' => 'AcfDataBundle:AoCateg',
                'query_builder' => function (AoCategRepository $br) use ($categId) {
                    return $br->createQueryBuilder('c')
                        ->where('c.id = :id')
                        ->setParameter('id', $categId)
                        ->orderBy('c.title', 'ASC');
                },
                'choice_label' => 'id',
                'multiple' => false,
                'by_reference' => true,
                'required' => true
            ));
        }

        $builder->add('ref', TextType::class, array(
            'label' => 'AoSubCateg.ref.label'
        ));
        $builder->add('title', TextType::class, array(
            'label' => 'AoSubCateg.title.label'
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'AoSubCategNewForm';
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
                'categ',
                'ref',
                'title'
            ),
            'categ' => null
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
