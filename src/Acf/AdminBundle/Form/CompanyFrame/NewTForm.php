<?php
namespace Acf\AdminBundle\Form\CompanyFrame;

use Acf\DataBundle\Entity\CompanyFrame;
use Acf\DataBundle\Repository\CompanyRepository;
use Acf\DataBundle\Repository\JobRepository;
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
     *
     * @var Job
     */
    private $job;

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
        $this->job = $options['job'];

        if (null == $this->company) {
            $builder->add('company', EntityType::class, array(
                'label' => 'CompanyFrame.company.label',
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
                'label' => 'CompanyFrame.company.label',
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
        if (null == $this->job) {
            $builder->add('job', EntityType::class, array(
                'label' => 'CompanyFrame.job.label',
                'class' => 'AcfDataBundle:Job',
                'query_builder' => function (JobRepository $br) {
                    return $br->createQueryBuilder('j')
                        ->orderBy('j.label', 'ASC');
                },
                'choice_label' => 'label',
                'multiple' => false,
                'by_reference' => true,
                'required' => true
            ));
        } else {
            $jobId = $this->job->getId();
            $builder->add('job', EntityidType::class, array(
                'label' => 'CompanyFrame.job.label',
                'class' => 'AcfDataBundle:Job',
                'query_builder' => function (JobRepository $br) use ($jobId) {
                    return $br->createQueryBuilder('j')
                        ->where('j.id = :id')
                        ->setParameter('id', $jobId)
                        ->orderBy('j.label', 'ASC');
                },
                'choice_label' => 'id',
                'multiple' => false,
                'by_reference' => true,
                'required' => true
            ));
        }

        $builder->add('sexe', ChoiceType::class, array(
            'label' => 'CompanyFrame.sexe.label',
            'choices_as_values' => true,
            'choices' => CompanyFrame::choiceSexe(),
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('firstName', TextType::class, array(
            'label' => 'CompanyFrame.firstName.label'
        ));

        $builder->add('lastName', TextType::class, array(
            'label' => 'CompanyFrame.lastName.label'
        ));

        $builder->add('cin', TextType::class, array(
            'label' => 'CompanyFrame.cin.label',
            'required' => false
        ));

        $builder->add('passport', TextType::class, array(
            'label' => 'CompanyFrame.passport.label',
            'required' => false
        ));

        $builder->add('email', EmailType::class, array(
            'label' => 'CompanyFrame.email.label',
            'required' => false
        ));

        $builder->add('phone', TextType::class, array(
            'label' => 'CompanyFrame.phone.label',
            'required' => false
        ));

        $builder->add('mobile', TextType::class, array(
            'label' => 'CompanyFrame.mobile.label',
            'required' => false
        ));

        $builder->add('streetNum', IntegerType::class, array(
            'label' => 'CompanyFrame.streetNum.label',
            'scale' => 0,
            'required' => false
        ));

        $builder->add('address', TextareaType::class, array(
            'label' => 'CompanyFrame.address.label',
            'required' => false
        ));

        $builder->add('address2', TextareaType::class, array(
            'label' => 'CompanyFrame.address2.label',
            'required' => false
        ));

        $builder->add('town', TextType::class, array(
            'label' => 'CompanyFrame.town.label',
            'required' => false
        ));

        $builder->add('zipCode', TextType::class, array(
            'label' => 'CompanyFrame.zipCode.label',
            'required' => false
        ));

        $builder->add('country', CountryType::class, array(
            'label' => 'CompanyFrame.country.label',
            'required' => false,
            'placeholder' => 'Options.choose',
            'empty_data' => null
        ));

        $builder->add('otherInfos', TextareaType::class, array(
            'label' => 'CompanyFrame.otherInfos.label',
            'required' => false
        ));
    }

    /**
     *
     * {@inheritdoc} @see \Symfony\Component\Form\FormTypeInterface::getName()
     * @return string
     */
    public function getName()
    {
        return 'CompanyFrameNewForm';
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
                'sexe',
                'phone',
                'email',
                'mobile'
            ),
            'company' => null,
            'job' => null
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
