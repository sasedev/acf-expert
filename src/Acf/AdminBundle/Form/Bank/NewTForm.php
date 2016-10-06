<?php
namespace Acf\AdminBundle\Form\Bank;

use Acf\DataBundle\Repository\CompanyRepository;
use Sasedev\Form\EntityidBundle\Form\Type\EntityidType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class NewTForm extends AbstractType
{

    /**
     *
     * @var Company
     */
    private $company;

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
        $this->company = $options['company'];

        if (null == $this->company) {
            $builder->add('company', EntityType::class, array(
                'label' => 'Bank.company.label',
                'class' => 'AcfDataBundle:Company',
                'query_builder' => function (CompanyRepository $br) {
                    return $br->createQueryBuilder('c')
                        ->orderBy('c.corporateName', 'ASC');
                },
                'choice_label' => 'corporateName',
                'multiple' => false,
                'by_reference' => true,
                'required' => true
            ));
        } else {
            $companyId = $this->company->getId();
            $builder->add('company', EntityidType::class, array(
                'label' => 'Bank.company.label',
                'class' => 'AcfDataBundle:Company',
                'query_builder' => function (CompanyRepository $br) use ($companyId) {
                    return $br->createQueryBuilder('c')
                        ->where('c.id = :id')
                        ->setParameter('id', $companyId)
                        ->orderBy('c.corporateName', 'ASC');
                },
                'choice_label' => 'id',
                'multiple' => false,
                'by_reference' => true,
                'required' => true
            ));
        }

        $builder->add('label', TextType::class, array(
            'label' => 'Bank.label.label'
        ));

        $builder->add('number', IntegerType::class, array(
            'label' => 'Bank.number.label'
        ));

        $builder->add('agency', TextType::class, array(
            'label' => 'Bank.agency.label'
        ));

        $builder->add('rib', TextType::class, array(
            'label' => 'Bank.rib.label'
        ));

        $builder->add('contact', TextType::class, array(
            'label' => 'Bank.contact.label',
            'required' => false
        ));

        $builder->add('tel', TextType::class, array(
            'label' => 'Bank.tel.label',
            'required' => false
        ));

        $builder->add('fax', TextType::class, array(
            'label' => 'Bank.fax.label',
            'required' => false
        ));

        $builder->add('email', EmailType::class, array(
            'label' => 'Bank.email.label',
            'required' => false
        ));

        $builder->add('otherInfos', TextareaType::class, array(
            'label' => 'Bank.otherInfos.label',
            'required' => false
        ));
    }

    /**
     *
     * {@inheritdoc} @see FormTypeInterface::getName()
     * @return string
     */
    public function getName()
    {
        return 'BankNewForm';
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
                'label',
                'number',
                'contact',
                'tel',
                'fax',
                'email'
            ),
            'company' => null
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
