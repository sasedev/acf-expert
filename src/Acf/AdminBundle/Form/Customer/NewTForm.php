<?php
namespace Acf\AdminBundle\Form\Customer;

use Acf\DataBundle\Entity\Customer;
use Acf\DataBundle\Repository\CompanyRepository;
use Acf\DataBundle\Repository\SectorRepository;
use Sasedev\Form\EntityidBundle\Form\Type\EntityidType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
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
     * @param array                $options
     *
     * @return null
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->company = $options['company'];

        if (null == $this->company) {
            $builder->add('company', EntityType::class, array(
                'label' => 'Customer.company.label',
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
                'label' => 'Customer.company.label',
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
            'label' => 'Customer.label.label'
        ));

        $builder->add('number', IntegerType::class, array(
            'label' => 'Customer.number.label'
        ));

        $builder->add('fisc', TextType::class, array(
            'label' => 'Customer.fisc.label',
            'required' => false
        ));

        $builder->add('physicaltype', ChoiceType::class, array(
            'label' => 'Customer.physicaltype.label',
            'choices_as_values' => true,
            'choices' => Customer::choicePhysicaltype(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('cin', TextType::class, array(
            'label' => 'Customer.cin.label',
            'required' => false
        ));

        $builder->add('passport', TextType::class, array(
            'label' => 'Customer.passport.label',
            'required' => false
        ));

        $builder->add('commercialRegister', TextType::class, array(
            'label' => 'Customer.commercialRegister.label',
            'required' => false
        ));

        $builder->add('sectors', EntityType::class, array(
            'label' => 'Customer.sectors.label',
            'class' => 'AcfDataBundle:Sector',
            'query_builder' => function (SectorRepository $sr) {
                return $sr->createQueryBuilder('s')
                    ->orderBy('s.label', 'ASC');
            },
            'choice_label' => 'label',
            'multiple' => true,
            'by_reference' => true,
            'required' => false
        ));

        $builder->add('email', EmailType::class, array(
            'label' => 'Customer.email.label',
            'required' => false
        ));

        $builder->add('phone', TextType::class, array(
            'label' => 'Customer.phone.label',
            'required' => false
        ));

        $builder->add('mobile', TextType::class, array(
            'label' => 'Customer.mobile.label',
            'required' => false
        ));

        $builder->add('fax', TextType::class, array(
            'label' => 'Customer.fax.label',
            'required' => false
        ));

        $builder->add('streetNum', IntegerType::class, array(
            'label' => 'Customer.streetNum.label',
            'scale' => 0,
            'required' => false
        ));

        $builder->add('address', TextareaType::class, array(
            'label' => 'Customer.address.label',
            'required' => false
        ));

        $builder->add('address2', TextareaType::class, array(
            'label' => 'Customer.address2.label',
            'required' => false
        ));

        $builder->add('town', TextType::class, array(
            'label' => 'Customer.town.label',
            'required' => false
        ));

        $builder->add('zipCode', TextType::class, array(
            'label' => 'Customer.zipCode.label',
            'required' => false
        ));

        $builder->add('country', CountryType::class, array(
            'label' => 'Customer.country.label',
            'required' => false,
            'placeholder' => 'Options.choose',
            'empty_data' => null
        ));

        $builder->add('otherInfos', TextareaType::class, array(
            'label' => 'Customer.otherInfos.label',
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
        return 'CustomerNewForm';
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
                'phone',
                'mobile',
                'email',
                'number',
                'label'
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
