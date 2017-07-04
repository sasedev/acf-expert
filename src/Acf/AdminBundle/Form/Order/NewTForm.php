<?php
namespace Acf\AdminBundle\Form\Order;

use Acf\DataBundle\Entity\User;
use Acf\DataBundle\Repository\UserRepository;
use Sasedev\Form\EntityidBundle\Form\Type\EntityidType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Acf\DataBundle\Repository\OnlineProductRepository;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class NewTForm extends AbstractType
{

    /**
     *
     * @var User
     */
    private $user;

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
        $this->user = $options['user'];

        if (null == $this->user) {
            $builder->add('user', EntityType::class, array(
                'label' => 'Order.user.label',
                'class' => 'AcfDataBundle:User',
                'query_builder' => function (UserRepository $ur) {
                    return $ur->createQueryBuilder('u')->orderBy('u.username', 'ASC');
                },
                'choice_label' => 'fullName',
                'multiple' => false,
                'by_reference' => true,
                'required' => true
            ));
        } else {
            $userId = $this->user->getId();
            $builder->add('user', EntityidType::class, array(
                'label' => 'Order.user.label',
                'class' => 'AcfDataBundle:User',
                'query_builder' => function (UserRepository $ur) use ($userId) {
                    return $ur->createQueryBuilder('u')->where('u.id = :id')->setParameter('id', $userId)->orderBy('u.username', 'ASC');
                },
                'choice_label' => 'id',
                'multiple' => false,
                'by_reference' => true,
                'required' => true
            ));
        }
        $builder->add('oproducts', EntityType::class, array(
            'label' => 'Order.products.label',
            'class' => 'AcfDataBundle:OnlineProduct',
            'query_builder' => function (OnlineProductRepository $opr) {
                return $opr->createQueryBuilder('op');
            },
            'choice_label' => 'originalName',
            'multiple' => true,
            'by_reference' => false,
            'required' => true,
            'mapped' => false
        ));

        $builder->add('orderTo', TextareaType::class, array(
            'label' => 'Order.orderTo.label'
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'OrderNewForm';
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
                'user',
                'orderTo',
                'oproducts'
            ),
            'user' => null
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
