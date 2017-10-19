<?php
namespace Acf\AdminBundle\Form\Order;

use Acf\DataBundle\Entity\User;
use Acf\DataBundle\Repository\CompanyRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class UpdateCompanyTForm extends AbstractType
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
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->user = $options['user'];
        $user = $this->user;

        $builder->add('company', EntityType::class, array(
            'label' => 'CompanyFrame.company.label',
            'class' => 'AcfDataBundle:Company',
            'query_builder' => function (CompanyRepository $br) use ($user) {
                if (null != $user) {
                    return $br->createQueryBuilder('c')->leftJoin('c.users', 'u')->where('u.id = :userid')->setParameter('userid', $user->getId())->orderBy('c.corporateName', 'ASC');
                } else {
                    return $br->createQueryBuilder('c')->orderBy('c.corporateName', 'ASC');
                }
            },
            'choice_label' => 'corporateName',
            'multiple' => false,
            'by_reference' => true,
            'required' => false
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'OrderUpdateCompanyForm';
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
                'company'
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
