<?php
namespace Acf\AdminBundle\Form\Buy;

use Acf\DataBundle\Entity\MBPurchase;
use Acf\DataBundle\Repository\CompanyNatureRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class UpdateNatureTForm extends AbstractType
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
            $builder->add('nature', EntityType::class, array(
                'label' => 'Buy.nature.label',
                'class' => 'AcfDataBundle:CompanyNature',
                'query_builder' => function (CompanyNatureRepository $ar) {
                    return $ar->createQueryBuilder('a')->orderBy('a.label', 'ASC');
                },
                'choice_label' => 'label',
                'multiple' => false,
                'by_reference' => true,
                'required' => true
            ));
        } else {
            $companyId = $this->mbpurchase->getCompany()->getId();
            $builder->add('nature', EntityType::class, array(
                'label' => 'Buy.nature.label',
                'class' => 'AcfDataBundle:CompanyNature',
                'query_builder' => function (CompanyNatureRepository $ar) use ($companyId) {
                    return $ar->createQueryBuilder('a')->join('a.company', 'c')->where('c.id = :cid')->setParameter('cid', $companyId)->orderBy('a.label', 'ASC');
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
     * @return string
     */
    public function getName()
    {
        return 'BuyUpdateNatureForm';
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
                'nature'
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
