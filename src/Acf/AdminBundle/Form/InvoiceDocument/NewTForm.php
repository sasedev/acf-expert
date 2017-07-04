<?php
namespace Acf\AdminBundle\Form\InvoiceDocument;

use Acf\DataBundle\Entity\OnlineInvoice;
use Acf\DataBundle\Repository\OnlineInvoiceRepository;
use Sasedev\Form\EntityidBundle\Form\Type\EntityidType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Acf\DataBundle\Entity\OnlineInvoiceDocument;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class NewTForm extends AbstractType
{

    /**
     *
     * @var OnlineInvoice
     */
    private $invoice;

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
        $this->invoice = $options['invoice'];

        if (null == $this->invoice) {
            $builder->add('invoice', EntityType::class, array(
                'label' => 'InvoiceDocument.invoice.label',
                'class' => 'AcfDataBundle:OnlineInvoice',
                'query_builder' => function (OnlineInvoiceRepository $ir) {
                    return $ir->createQueryBuilder('i')->orderBy('i.dtCrea', 'ASC');
                },
                'choice_label' => 'ref',
                'multiple' => false,
                'by_reference' => true,
                'required' => true
            ));
        } else {
            $invoiceId = $this->invoice->getId();
            $builder->add('invoice', EntityidType::class, array(
                'label' => 'InvoiceDocument.invoice.label',
                'class' => 'AcfDataBundle:OnlineInvoice',
                'query_builder' => function (OnlineInvoiceRepository $br) use ($invoiceId) {
                    return $br->createQueryBuilder('i')->where('i.id = :id')->setParameter('id', $invoiceId)->orderBy('i.dtCrea', 'ASC');
                },
                'choice_label' => 'id',
                'multiple' => false,
                'by_reference' => true,
                'required' => true
            ));
        }

        $builder->add('fileName', FileType::class, array(
            'label' => 'InvoiceDocument.fileName.label'
        ));

        $builder->add('visible', ChoiceType::class, array(
            'label' => 'InvoiceDocument.visible.label',
            'choices_as_values' => true,
            'choices' => OnlineInvoiceDocument::choiceVisible(),
            'attr' => array(
                'choice_label_trans' => true
            )
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'InvoiceDocumentNewForm';
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
                'fileName'
            ),
            'invoice' => null
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
