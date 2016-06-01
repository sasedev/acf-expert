<?php
namespace Acf\AdminBundle\Form\Sale;

use Acf\DataBundle\Repository\WithholdingRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class UpdateWithholdingTForm extends AbstractType
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
     * @param array                $options
     *
     * @return null
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->mbsale = $options['monthlybalance'];

        if (null == $this->mbsale) {
            $builder->add('withholding', EntityType::class, array(
                'label' => 'Sale.withholding.label',
                'class' => 'AcfDataBundle:Withholding',
                'query_builder' => function (WithholdingRepository $wr) {
                    return $wr->createQueryBuilder('w')
                        ->orderBy('w.label', 'ASC');
                },
                'choice_label' => 'label',
                'multiple' => false,
                'by_reference' => true,
                'required' => true
            ));
        } else {
            $companyId = $this->mbsale->getCompany()->getId();
            $builder->add('withholding', EntityType::class, array(
                'label' => 'Sale.withholding.label',
                'class' => 'AcfDataBundle:Withholding',
                'query_builder' => function (WithholdingRepository $wr) use ($companyId) {
                    return $wr->createQueryBuilder('w')
                        ->join('w.company', 'c')
                        ->where('c.id = :cid')
                        ->setParameter('cid', $companyId)
                        ->orderBy('w.label', 'ASC');
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
        return 'SaleUpdateWithholdingForm';
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
                'withholding'
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
