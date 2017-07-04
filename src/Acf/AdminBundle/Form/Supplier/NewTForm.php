<?php
namespace Acf\AdminBundle\Form\Supplier;

use Acf\DataBundle\Entity\Company;
use Acf\DataBundle\Entity\Supplier;
use Acf\DataBundle\Repository\CompanyRepository;
use Sasedev\Form\EntityidBundle\Form\Type\EntityidType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
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
                'label' => 'Supplier.company.label',
                'class' => 'AcfDataBundle:Company',
                'query_builder' => function (CompanyRepository $br) {
                    return $br->createQueryBuilder('c')->orderBy('c.corporateName', 'ASC');
                },
                'choice_label' => 'corporateName',
                'multiple' => false,
                'by_reference' => true,
                'required' => true
            ));
        } else {
            $companyId = $this->company->getId();
            $builder->add('company', EntityidType::class, array(
                'label' => 'Supplier.company.label',
                'class' => 'AcfDataBundle:Company',
                'query_builder' => function (CompanyRepository $br) use ($companyId) {
                    return $br->createQueryBuilder('c')->where('c.id = :id')->setParameter('id', $companyId)->orderBy('c.corporateName', 'ASC');
                },
                'choice_label' => 'id',
                'multiple' => false,
                'by_reference' => true,
                'required' => true
            ));
        }

        $builder->add('label', TextType::class, array(
            'label' => 'Supplier.label.label'
        ));

        $builder->add('number', IntegerType::class, array(
            'label' => 'Supplier.number.label'
        ));

        $builder->add('fisc', TextType::class, array(
            'label' => 'Supplier.fisc.label',
            'required' => false
        ));

        $builder->add('physicaltype', ChoiceType::class, array(
            'label' => 'Supplier.physicaltype.label',
            'choices_as_values' => true,
            'choices' => Supplier::choicePhysicaltype(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('cin', TextType::class, array(
            'label' => 'Supplier.cin.label',
            'required' => false
        ));

        $builder->add('passport', TextType::class, array(
            'label' => 'Supplier.passport.label',
            'required' => false
        ));

        $builder->add('commercialRegister', TextType::class, array(
            'label' => 'Supplier.commercialRegister.label',
            'required' => false
        ));

        $builder->add('email', EmailType::class, array(
            'label' => 'Supplier.email.label',
            'required' => false
        ));

        $builder->add('phone', TextType::class, array(
            'label' => 'Supplier.phone.label',
            'required' => false
        ));

        $builder->add('mobile', TextType::class, array(
            'label' => 'Supplier.mobile.label',
            'required' => false
        ));

        $builder->add('fax', TextType::class, array(
            'label' => 'Supplier.fax.label',
            'required' => false
        ));

        $builder->add('streetNum', IntegerType::class, array(
            'label' => 'Supplier.streetNum.label',
            'scale' => 0,
            'required' => false
        ));

        $builder->add('address', TextareaType::class, array(
            'label' => 'Supplier.address.label',
            'required' => false
        ));

        $builder->add('address2', TextareaType::class, array(
            'label' => 'Supplier.address2.label',
            'required' => false
        ));

        $builder->add('town', TextType::class, array(
            'label' => 'Supplier.town.label',
            'required' => false
        ));

        $builder->add('zipCode', TextType::class, array(
            'label' => 'Supplier.zipCode.label',
            'required' => false
        ));

        $builder->add('country', CountryType::class, array(
            'label' => 'Supplier.country.label',
            'required' => false,
            'placeholder' => 'Options.choose',
            'empty_data' => null
        ));

        $builder->add('otherInfos', TextareaType::class, array(
            'label' => 'Supplier.otherInfos.label',
            'required' => false
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'SupplierNewForm';
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
