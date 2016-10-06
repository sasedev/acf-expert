<?php
namespace Acf\AdminBundle\Form\Sale;

use Acf\DataBundle\Entity\Sale;
use Acf\DataBundle\Repository\CustomerRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class UpdateRelationTForm extends AbstractType
{

    /**
     *
     * @var MBSale
     */
    private $mbsale;

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
        $this->mbsale = $options['monthlybalance'];

        if (null == $this->mbsale) {
            $builder->add('relation', EntityType::class, array(
                'label' => 'Sale.relation.label',
                'class' => 'AcfDataBundle:Customer',
                'query_builder' => function (CustomerRepository $sr) {
                    return $sr->createQueryBuilder('s')
                        ->orderBy('s.label', 'ASC');
                },
                'choice_label' => 'label',
                'multiple' => false,
                'by_reference' => true,
                'required' => true
            ));
        } else {
            $companyId = $this->mbsale->getCompany()->getId();
            $builder->add('relation', EntityType::class, array(
                'label' => 'Sale.relation.label',
                'class' => 'AcfDataBundle:Customer',
                'query_builder' => function (CustomerRepository $sr) use ($companyId) {
                    return $sr->createQueryBuilder('s')
                        ->join('s.company', 'c')
                        ->where('c.id = :cid')
                        ->setParameter('cid', $companyId)
                        ->orderBy('s.label', 'ASC');
                },
                'choice_label' => 'label',
                'multiple' => false,
                'by_reference' => true,
                'required' => true
            ));
        }
    }

    /**
     *
     * {@inheritdoc} @see FormTypeInterface::getName()
     * @return string
     */
    public function getName()
    {
        return 'SaleUpdateRelationForm';
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
                'relation'
            ),
            'monthlybalance' => null
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
