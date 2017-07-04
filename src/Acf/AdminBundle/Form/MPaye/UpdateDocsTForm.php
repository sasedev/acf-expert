<?php
namespace Acf\AdminBundle\Form\MPaye;

use Acf\DataBundle\Entity\Company;
use Acf\DataBundle\Repository\DocRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class UpdateDocsTForm extends AbstractType
{

    /**
     *
     * @var Company
     */
    private $company;

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
        $this->company = $options['company'];

        if (null == $this->company) {
            $builder->add('docs', EntityType::class, array(
                'label' => 'MPaye.docs.label',
                'class' => 'AcfDataBundle:Doc',
                'query_builder' => function (DocRepository $dr) {
                    return $dr->createQueryBuilder('d')->orderBy('d.originalName', 'ASC');
                },
                'choice_label' => 'originalName',
                'multiple' => true,
                'by_reference' => false,
                'required' => false
            ));
        } else {
            $companyId = $this->company->getId();
            $builder->add('docs', EntityType::class, array(
                'label' => 'MPaye.docs.label',
                'class' => 'AcfDataBundle:Doc',
                'query_builder' => function (DocRepository $dr) use ($companyId) {
                    return $dr->createQueryBuilder('d')->join('d.company', 'c')->where('c.id = :cid')->orderBy('d.originalName', 'ASC')->setParameter('cid', $companyId);
                },
                'choice_label' => 'originalName',
                'multiple' => true,
                'by_reference' => false,
                'required' => false
            ));
        }
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'MPayeUpdateDocsForm';
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
                'docs'
            ),
            'company' => null
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
