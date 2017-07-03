<?php
namespace Acf\AdminBundle\Form\CompanyUser;

use Acf\DataBundle\Entity\Company;
use Acf\DataBundle\Entity\CompanyUser;
use Acf\DataBundle\Repository\CompanyRepository;
use Acf\DataBundle\Repository\UserRepository;
use Sasedev\Form\EntityidBundle\Form\Type\EntityidType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
        $companyId = $this->company->getId();

        $builder->add('company', EntityidType::class, array(
            'label' => 'CompanyUser.company.label',
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

        $builder->add('user', EntityType::class, array(
            'label' => 'CompanyUser.user.label',
            'class' => 'AcfDataBundle:User',
            'query_builder' => function (UserRepository $ur) use ($companyId) {
                $alreadyUsers = $ur->createQueryBuilder('u')
                    ->select('u.id')
                    ->join('u.companies', 'c')
                    ->where('c.id = :id')
                    ->setParameter('id', $companyId)
                    ->getQuery()
                    ->execute();

                if (count($alreadyUsers) != 0) {
                    return $ur->createQueryBuilder('u')
                        ->where('u.id NOT IN (:ulist)')
                        ->setParameter('ulist', $alreadyUsers)
                        ->orderBy('u.username', 'ASC');
                } else {
                    return $ur->createQueryBuilder('u')
                        ->orderBy('u.username', 'ASC');
                }
            },
            'choice_label' => 'fullName',
            'multiple' => false,
            'by_reference' => true,
            'required' => true
        ));

        $builder->add('editCompanyinfos', ChoiceType::class, array(
            'label' => 'CompanyUser.editCompanyinfos.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('addAddresses', ChoiceType::class, array(
            'label' => 'CompanyUser.addAddresses.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('editAddresses', ChoiceType::class, array(
            'label' => 'CompanyUser.editAddresses.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('deleteAddresses', ChoiceType::class, array(
            'label' => 'CompanyUser.deleteAddresses.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('addPhones', ChoiceType::class, array(
            'label' => 'CompanyUser.addPhones.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('editPhones', ChoiceType::class, array(
            'label' => 'CompanyUser.editPhones.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('deletePhones', ChoiceType::class, array(
            'label' => 'CompanyUser.deletePhones.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('addFrames', ChoiceType::class, array(
            'label' => 'CompanyUser.addFrames.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('editFrames', ChoiceType::class, array(
            'label' => 'CompanyUser.editFrames.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('deleteFrames', ChoiceType::class, array(
            'label' => 'CompanyUser.deleteFrames.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('addDocs', ChoiceType::class, array(
            'label' => 'CompanyUser.addDocs.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('editDocs', ChoiceType::class, array(
            'label' => 'CompanyUser.editDocs.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('deleteDocs', ChoiceType::class, array(
            'label' => 'CompanyUser.deleteDocs.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('addSuppliers', ChoiceType::class, array(
            'label' => 'CompanyUser.addSuppliers.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('editSuppliers', ChoiceType::class, array(
            'label' => 'CompanyUser.editSuppliers.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('deleteSuppliers', ChoiceType::class, array(
            'label' => 'CompanyUser.deleteSuppliers.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('addCustomers', ChoiceType::class, array(
            'label' => 'CompanyUser.addCustomers.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('editCustomers', ChoiceType::class, array(
            'label' => 'CompanyUser.editCustomers.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('deleteCustomers', ChoiceType::class, array(
            'label' => 'CompanyUser.deleteCustomers.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('addSales', ChoiceType::class, array(
            'label' => 'CompanyUser.addSales.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('editSales', ChoiceType::class, array(
            'label' => 'CompanyUser.editSales.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('deleteSales', ChoiceType::class, array(
            'label' => 'CompanyUser.deleteSales.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('addBuys', ChoiceType::class, array(
            'label' => 'CompanyUser.addBuys.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('editBuys', ChoiceType::class, array(
            'label' => 'CompanyUser.editBuys.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('deleteBuys', ChoiceType::class, array(
            'label' => 'CompanyUser.deleteBuys.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('addDocgroupComptables', ChoiceType::class, array(
            'label' => 'CompanyUser.addDocgroupComptables.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('editDocgroupComptables', ChoiceType::class, array(
            'label' => 'CompanyUser.editDocgroupComptables.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('addDocgroupBanks', ChoiceType::class, array(
            'label' => 'CompanyUser.addDocgroupBanks.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('editDocgroupBanks', ChoiceType::class, array(
            'label' => 'CompanyUser.editDocgroupBanks.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('addDocgroupJuridics', ChoiceType::class, array(
            'label' => 'CompanyUser.addDocgroupJuridics.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('editDocgroupJuridics', ChoiceType::class, array(
            'label' => 'CompanyUser.editDocgroupJuridics.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('addDocgroupFiscals', ChoiceType::class, array(
            'label' => 'CompanyUser.addDocgroupFiscals.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('editDocgroupFiscals', ChoiceType::class, array(
            'label' => 'CompanyUser.editDocgroupFiscals.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('addDocgroupPersos', ChoiceType::class, array(
            'label' => 'CompanyUser.addDocgroupPersos.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('editDocgroupPersos', ChoiceType::class, array(
            'label' => 'CompanyUser.editDocgroupPersos.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('addDocgroupSysts', ChoiceType::class, array(
            'label' => 'CompanyUser.addDocgroupSysts.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('editDocgroupSysts', ChoiceType::class, array(
            'label' => 'CompanyUser.editDocgroupSysts.label',
            'choices_as_values' => true,
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));
    }

    /**
     *
     * {@inheritdoc} @see \Symfony\Component\Form\FormTypeInterface::getName()
     * @return string
     */
    public function getName()
    {
        return 'CompanyUserNewForm';
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
                'editCompanyinfos',
                'addAddresses'
            ),
            'company' => new Company()
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
