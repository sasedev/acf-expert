<?php
namespace Acf\AdminBundle\Form\Order;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Acf\DataBundle\Repository\OnlineProductRepository;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class AddProductTForm extends AbstractType
{

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
        $order = $options['order'];

        $builder->add('oproduct', EntityType::class, array(
            'label' => 'Order.products.label',
            'class' => 'AcfDataBundle:OnlineProduct',
            'query_builder' => function (OnlineProductRepository $opr) use ($order) {
                if (null == $order || \count($order->getProducts()) == 0) {
                    return $opr->createQueryBuilder('op');
                } else {
                    $opids = array();
                    foreach ($order->getProducts() as $prdexist) {
                        $opids[] = $prdexist->getProduct()
                            ->getId();
                    }
                    return $opr->createQueryBuilder('op')
                        ->where('op.id not in (:ids)')
                        ->setParameter('ids', $opids);
                }
            },
            'choice_label' => 'originalName',
            'multiple' => false,
            'by_reference' => false,
            'required' => true,
            'mapped' => false
        ));
    }

    /**
     *
     * {@inheritdoc} @see FormTypeInterface::getName()
     * @return string
     */
    public function getName()
    {
        return 'OrderAddProductForm';
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
                'oproduct'
            ),
            'order' => null
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