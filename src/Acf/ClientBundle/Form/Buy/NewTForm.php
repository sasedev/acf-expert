<?php
namespace Acf\ClientBundle\Form\Buy;

use Acf\ClientBundle\Form\Buy\Doc\BuyDocTForm;
use Acf\DataBundle\Entity\Buy;
use Acf\DataBundle\Entity\MBPurchase;
use Acf\DataBundle\Repository\AccountRepository;
use Acf\DataBundle\Repository\CompanyNatureRepository;
use Acf\DataBundle\Repository\MBPurchaseRepository;
use Acf\DataBundle\Repository\SupplierRepository;
use Acf\DataBundle\Repository\WithholdingRepository;
use Sasedev\Form\EntityidBundle\Form\Type\EntityidType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class NewTForm extends AbstractType
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
            $builder->add('monthlyBalance', EntityType::class, array(
                'label' => 'Buy.monthlyBalance.label',
                'class' => 'AcfDataBundle:MBPurchase',
                'query_builder' => function (MBPurchaseRepository $mbsr) {
                    return $mbsr->createQueryBuilder('mbs')
                        ->orderBy('mbs.ref', 'ASC');
                },
                'choice_label' => 'ref',
                'multiple' => false,
                'by_reference' => true,
                'required' => true
            ));
        } else {
            $mbpurchaseId = $this->mbpurchase->getId();
            $builder->add('monthlyBalance', EntityidType::class, array(
                'label' => 'Buy.monthlyBalance.label',
                'class' => 'AcfDataBundle:MBPurchase',
                'query_builder' => function (MBPurchaseRepository $mbsr) use ($mbpurchaseId) {
                    return $mbsr->createQueryBuilder('mbs')
                        ->where('mbs.id = :id')
                        ->setParameter('id', $mbpurchaseId)
                        ->orderBy('mbs.ref', 'ASC');
                },
                'choice_label' => 'id',
                'multiple' => false,
                'by_reference' => true,
                'required' => true
            ));
        }

        $builder->add('dtActivation', DateType::class, array(
            'label' => 'Buy.dtActivation.label',
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd'
        ));

        $builder->add('bill', TextType::class, array(
            'label' => 'Buy.bill.label',
            'required' => false
        ));

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

        $builder->add('label', TextType::class, array(
            'label' => 'Buy.label.label'
        ));

        $builder->add('devise', CurrencyType::class, array(
            'label' => 'Buy.devise.label'
        ));

        $builder->add('conversionRate', NumberType::class, array(
            'label' => 'Buy.conversionRate.label'
        ));

        $builder->add('vat', NumberType::class, array(
            'label' => 'Buy.vat.label',
            'required' => false
        ));

        $builder->add('stamp', NumberType::class, array(
            'label' => 'Buy.stamp.label',
            'required' => false
        ));

        $builder->add('balanceTtc', NumberType::class, array(
            'label' => 'Buy.balanceTtc.label',
            'required' => false
        ));

        $builder->add('balanceNet', NumberType::class, array(
            'label' => 'Buy.balanceNet.label',
            'required' => false
        ));

        $builder->add('vatDevise', NumberType::class, array(
            'label' => 'Buy.vatDevise.label',
            'required' => false
        ));

        $builder->add('stampDevise', NumberType::class, array(
            'label' => 'Buy.stampDevise.label',
            'required' => false
        ));

        $builder->add('balanceTtcDevise', NumberType::class, array(
            'label' => 'Buy.balanceTtcDevise.label',
            'required' => false
        ));

        $builder->add('balanceNetDevise', NumberType::class, array(
            'label' => 'Buy.balanceNetDevise.label',
            'required' => false
        ));

        $builder->add('regime', ChoiceType::class, array(
            'label' => 'Buy.regime.label',
            'choices' => Buy::choiceRegime(),
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        if (null == $this->mbpurchase) {
            $builder->add('withholding', EntityType::class, array(
                'label' => 'Buy.withholding.label',
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
            $companyId = $this->mbpurchase->getCompany()->getId();
            $builder->add('withholding', EntityType::class, array(
                'label' => 'Buy.withholding.label',
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

        if (null == $this->mbpurchase) {
            $builder->add('nature', EntityType::class, array(
                'label' => 'Buy.nature.label',
                'class' => 'AcfDataBundle:CompanyNature',
                'query_builder' => function (CompanyNatureRepository $cnr) {
                    return $cnr->createQueryBuilder('cn')
                        ->orderBy('cn.label', 'ASC');
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
                'query_builder' => function (CompanyNatureRepository $cnr) use ($companyId) {
                    return $cnr->createQueryBuilder('cn')
                        ->join('cn.company', 'c')
                        ->where('c.id = :cid')
                        ->setParameter('cid', $companyId)
                        ->orderBy('cn.label', 'ASC');
                },
                'choice_label' => 'label',
                'multiple' => false,
                'by_reference' => true,
                'required' => true
            ));
        }

        $builder->add('paymentType', ChoiceType::class, array(
            'label' => 'Buy.paymentType.label',
            'choices' => Buy::choicePaymentType(),
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('transactionStatus', ChoiceType::class, array(
            'label' => 'Buy.transactionStatus.label',
            'choices' => Buy::choiceTransactionStatus(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('dtPayment', DateType::class, array(
            'label' => 'Buy.dtPayment.label',
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'required' => false
        ));

        if (null == $this->mbpurchase) {
            $builder->add('account', EntityType::class, array(
                'label' => 'Buy.account.label',
                'class' => 'AcfDataBundle:Account',
                'query_builder' => function (AccountRepository $ar) {
                    return $ar->createQueryBuilder('a')
                        ->orderBy('a.label', 'ASC');
                },
                'choice_label' => 'label',
                'multiple' => false,
                'by_reference' => true,
                'required' => true
            ));
        } else {
            $companyId = $this->mbpurchase->getCompany()->getId();
            $builder->add('account', EntityType::class, array(
                'label' => 'Buy.account.label',
                'class' => 'AcfDataBundle:Account',
                'query_builder' => function (AccountRepository $ar) use ($companyId) {
                    return $ar->createQueryBuilder('a')
                        ->join('a.company', 'c')
                        ->where('c.id = :cid')
                        ->setParameter('cid', $companyId)
                        ->orderBy('a.label', 'ASC');
                },
                'choice_label' => 'label',
                'multiple' => false,
                'by_reference' => true,
                'required' => true
            ));
        }

        $builder->add('docs', CollectionType::class, array(
            'label' => 'Buy.docs.label',
            'entry_type' => BuyDocTForm::class,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'mapped' => false,
            'constraints' => new Valid()
        ));

        $builder->add('otherInfos', TextareaType::class, array(
            'label' => 'Buy.otherInfos.label',
            'required' => false
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'BuyNewForm';
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
                'dtActivation',
                'bill',
                'relation',
                'label',
                'vat',
                'stamp',
                'balanceTtc',
                'regime',
                'withholding',
                'balanceNet',
                'paymentType',
                'dtPayment',
                'transactionStatus',
                'account',
                'otherInfos'
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
