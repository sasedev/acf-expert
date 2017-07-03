<?php
namespace Acf\ClientBundle\Form\Buy;

use Acf\DataBundle\Entity\MBPurchase;
use Acf\DataBundle\Repository\SupplierRepository;
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
     * @var MBPurchase
     */
    private $mbpurchase;

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
        $this->mbpurchase = $options['monthlybalance'];

        if (null == $this->mbpurchase) {
            $builder->add('relation', EntityType::class, array(
                'label' => 'Buy.relation.label',
                'class' => 'AcfDataBundle:Supplier',
                'query_builder' => function (SupplierRepository $sr) {
                    return $sr->createQueryBuilder('s')
                        ->orderBy('s.label', 'ASC');
                },
                'choice_label' => 'label',
                'multiple' => false,
                'by_reference' => true,
                'required' => true
            ));
        } else {
            $companyId = $this->mbpurchase->getCompany()->getId();
            $builder->add('relation', EntityType::class, array(
                'label' => 'Buy.relation.label',
                'class' => 'AcfDataBundle:Supplier',
                'query_builder' => function (SupplierRepository $sr) use ($companyId) {
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
     * {@inheritdoc} @see \Symfony\Component\Form\FormTypeInterface::getName()
     * @return string
     */
    public function getName()
    {
        return 'BuyUpdateRelationForm';
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
     * {@inheritdoc}
     *
     * {@inheritdoc} @see AbstractType::configureOptions()
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults($this->getDefaultOptions());
    }
}
