<?php
namespace Acf\AdminBundle\Form\Docgroupsyst;

use Acf\DataBundle\Repository\CompanyRepository;
use Acf\DataBundle\Repository\DocgroupsystRepository;
use Sasedev\Form\EntityidBundle\Form\Type\EntityidType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
                'label' => 'Docgroupsyst.company.label',
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

            $builder->add('parent', EntityType::class, array(
                'label' => 'Docgroupsyst.parent.label',
                'class' => 'AcfDataBundle:Docgroupsyst',
                'query_builder' => function (DocgroupsystRepository $dgr) {
                    return $dgr->createQueryBuilder('d')
                        ->orderBy('d.pageUrlFull', 'ASC');
                },
                'choice_label' => 'pageUrlFull',
                'multiple' => false,
                'by_reference' => true,
                'required' => false,
                'placeholder' => 'Options.choose',
                'empty_data' => null
            ));

            $builder->add('clone', EntityType::class, array(
                'label' => 'Docgroup.clone.label',
                'class' => 'AcfDataBundle:Docgroupsyst',
                'query_builder' => function (DocgroupsystRepository $dgr) {
                    return $dgr->createQueryBuilder('d')
                        ->orderBy('d.pageUrlFull', 'ASC');
                },
                'choice_label' => 'pageUrlFull',
                'multiple' => false,
                'by_reference' => true,
                'required' => false,
                'placeholder' => 'Options.choose',
                'mapped' => false
            ));
        } else {
            $companyId = $this->company->getId();
            $builder->add('company', EntityidType::class, array(
                'label' => 'Docgroupsyst.company.label',
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

            $builder->add('parent', EntityType::class, array(
                'label' => 'Docgroupsyst.parent.label',
                'class' => 'AcfDataBundle:Docgroupsyst',
                'query_builder' => function (DocgroupsystRepository $dgr) use ($companyId) {
                    return $dgr->createQueryBuilder('d')
                        ->join('d.company', 'c')
                        ->where('c.id = :id')
                        ->setParameter('id', $companyId)
                        ->orderBy('d.pageUrlFull', 'ASC');
                },
                'choice_label' => 'pageUrlFull',
                'multiple' => false,
                'by_reference' => true,
                'required' => false,
                'placeholder' => 'Options.choose',
                'empty_data' => null
            ));

            $builder->add('clone', EntityType::class, array(
                'label' => 'Docgroup.clone.label',
                'class' => 'AcfDataBundle:Docgroupsyst',
                'query_builder' => function (DocgroupsystRepository $dgr) use ($companyId) {
                    return $dgr->createQueryBuilder('d')
                        ->join('d.company', 'c')
                        ->where('c.id = :id')
                        ->setParameter('id', $companyId)
                        ->orderBy('d.pageUrlFull', 'ASC');
                },
                'choice_label' => 'pageUrlFull',
                'multiple' => false,
                'by_reference' => true,
                'required' => false,
                'placeholder' => 'Options.choose',
                'mapped' => false
            ));
        }

        $builder->add('label', TextType::class, array(
            'label' => 'Docgroupsyst.label.label'
        ));

        $builder->add('otherInfos', TextareaType::class, array(
            'label' => 'Docgroupsyst.otherInfos.label',
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
        return 'DocgroupsystNewForm';
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
                'parent'
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
