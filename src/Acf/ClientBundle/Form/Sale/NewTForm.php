<?php
namespace Acf\ClientBundle\Form\Sale;

use Acf\ClientBundle\Form\Sale\Doc\SaleDocTForm;
use Acf\ClientBundle\Form\Sale\SecondaryVat\SaleSecondaryVatTForm;
use Acf\DataBundle\Entity\MBSale;
use Acf\DataBundle\Entity\Sale;
use Acf\DataBundle\Entity\Vat;
use Acf\DataBundle\Repository\AccountRepository;
use Acf\DataBundle\Repository\CustomerRepository;
use Acf\DataBundle\Repository\MBSaleRepository;
use Acf\DataBundle\Repository\VatRepository;
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
            $builder->add('monthlyBalance', EntityType::class, array(
                'label' => 'Sale.monthlyBalance.label',
                'class' => 'AcfDataBundle:MBSale',
                'query_builder' => function (MBSaleRepository $mbsr) {
                    return $mbsr->createQueryBuilder('mbs')
                        ->orderBy('mbs.ref', 'ASC');
                },
                'choice_label' => 'ref',
                'multiple' => false,
                'by_reference' => true,
                'required' => true
            ));
        } else {
            $mbsaleId = $this->mbsale->getId();
            $builder->add('monthlyBalance', EntityidType::class, array(
                'label' => 'Sale.monthlyBalance.label',
                'class' => 'AcfDataBundle:MBSale',
                'query_builder' => function (MBSaleRepository $mbsr) use ($mbsaleId) {
                    return $mbsr->createQueryBuilder('mbs')
                        ->where('mbs.id = :id')
                        ->setParameter('id', $mbsaleId)
                        ->orderBy('mbs.ref', 'ASC');
                },
                'choice_label' => 'id',
                'multiple' => false,
                'by_reference' => true,
                'required' => true
            ));
        }

        $builder->add('dtActivation', DateType::class, array(
            'label' => 'Sale.dtActivation.label',
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd'
        ));

        $builder->add('bill', TextType::class, array(
            'label' => 'Sale.bill.label',
            'required' => false
        ));

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

        $builder->add('label', TextType::class, array(
            'label' => 'Sale.label.label'
        ));

        $builder->add('devise', CurrencyType::class, array(
            'label' => 'Sale.devise.label'
        ));

        $builder->add('conversionRate', NumberType::class, array(
            'label' => 'Sale.conversionRate.label'
        ));

        $builder->add('vat', NumberType::class, array(
            'label' => 'Sale.vat.label',
            'required' => false
        ));

        $builder->add('stamp', NumberType::class, array(
            'label' => 'Sale.stamp.label',
            'required' => false
        ));

        $builder->add('balanceTtc', NumberType::class, array(
            'label' => 'Sale.balanceTtc.label',
            'required' => false
        ));

        $builder->add('balanceNet', NumberType::class, array(
            'label' => 'Sale.balanceNet.label',
            'required' => false
        ));

        $builder->add('vatDevise', NumberType::class, array(
            'label' => 'Sale.vatDevise.label',
            'required' => false
        ));

        $builder->add('stampDevise', NumberType::class, array(
            'label' => 'Sale.stampDevise.label',
            'required' => false
        ));

        $builder->add('balanceTtcDevise', NumberType::class, array(
            'label' => 'Sale.balanceTtcDevise.label',
            'required' => false
        ));

        $builder->add('balanceNetDevise', NumberType::class, array(
            'label' => 'Sale.balanceNetDevise.label',
            'required' => false
        ));

        $builder->add('vatInfo', EntityType::class, array(
            'class' => 'AcfDataBundle:Vat',
            'label' => 'Sale.vatInfo.label',
            'query_builder' => function (VatRepository $vr) {
                return $vr->createQueryBuilder('v')
                    ->orderBy('v.title', 'ASC');
            },
            'choice_label' => 'title',
            'choice_value' => function ($entity = null) {
                if ($entity instanceof Vat) {
                    return $entity ? $entity->getTitle() : '';
                } else {
                    return $entity;
                }
            },
            'multiple' => false,
            'required' => true
        ));

        $builder->add('regime', ChoiceType::class, array(
            'label' => 'Sale.regime.label',
            'choices' => Sale::choiceRegime(),
            'attr' => array(
                'choice_label_trans' => true
            ),
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

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

        $builder->add('paymentType', ChoiceType::class, array(
            'label' => 'Sale.paymentType.label',
            'choices' => Sale::choicePaymentType(),
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('transactionStatus', ChoiceType::class, array(
            'label' => 'Sale.transactionStatus.label',
            'choices' => Sale::choiceTransactionStatus(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('dtPayment', DateType::class, array(
            'label' => 'Sale.dtPayment.label',
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'required' => false
        ));

        if (null == $this->mbsale) {
            $builder->add('account', EntityType::class, array(
                'label' => 'Sale.account.label',
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
            $companyId = $this->mbsale->getCompany()->getId();
            $builder->add('account', EntityType::class, array(
                'label' => 'Sale.account.label',
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
            'label' => 'Sale.docs.label',
            'entry_type' => SaleDocTForm::class,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'mapped' => false,
            'constraints' => new Valid()
        ));

        $builder->add('secondaryVats', CollectionType::class, array(
            'label' => 'Sale.secondaryVats.label',
            'entry_type' => SaleSecondaryVatTForm::class,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'mapped' => false
        ));

        $builder->add('otherInfos', TextareaType::class, array(
            'label' => 'Sale.otherInfos.label',
            'required' => false
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'SaleNewForm';
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
                'vatInfo',
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
