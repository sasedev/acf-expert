<?php
namespace Acf\AdminBundle\Form\CompanyUser;

use Acf\DataBundle\Entity\CompanyUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class UpdateTForm extends AbstractType
{

    /**
     * Form builder
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('editCompanyinfos', ChoiceType::class, array(
            'label' => 'CompanyUser.editCompanyinfos.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('addAddresses', ChoiceType::class, array(
            'label' => 'CompanyUser.addAddresses.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('editAddresses', ChoiceType::class, array(
            'label' => 'CompanyUser.editAddresses.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('deleteAddresses', ChoiceType::class, array(
            'label' => 'CompanyUser.deleteAddresses.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('addPhones', ChoiceType::class, array(
            'label' => 'CompanyUser.addPhones.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('editPhones', ChoiceType::class, array(
            'label' => 'CompanyUser.editPhones.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('deletePhones', ChoiceType::class, array(
            'label' => 'CompanyUser.deletePhones.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('addFrames', ChoiceType::class, array(
            'label' => 'CompanyUser.addFrames.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('editFrames', ChoiceType::class, array(
            'label' => 'CompanyUser.editFrames.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('deleteFrames', ChoiceType::class, array(
            'label' => 'CompanyUser.deleteFrames.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('addDocs', ChoiceType::class, array(
            'label' => 'CompanyUser.addDocs.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('editDocs', ChoiceType::class, array(
            'label' => 'CompanyUser.editDocs.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('deleteDocs', ChoiceType::class, array(
            'label' => 'CompanyUser.deleteDocs.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('addSuppliers', ChoiceType::class, array(
            'label' => 'CompanyUser.addSuppliers.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('editSuppliers', ChoiceType::class, array(
            'label' => 'CompanyUser.editSuppliers.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('deleteSuppliers', ChoiceType::class, array(
            'label' => 'CompanyUser.deleteSuppliers.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('addCustomers', ChoiceType::class, array(
            'label' => 'CompanyUser.addCustomers.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('editCustomers', ChoiceType::class, array(
            'label' => 'CompanyUser.editCustomers.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('deleteCustomers', ChoiceType::class, array(
            'label' => 'CompanyUser.deleteCustomers.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('addSales', ChoiceType::class, array(
            'label' => 'CompanyUser.addSales.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('editSales', ChoiceType::class, array(
            'label' => 'CompanyUser.editSales.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('deleteSales', ChoiceType::class, array(
            'label' => 'CompanyUser.deleteSales.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('addBuys', ChoiceType::class, array(
            'label' => 'CompanyUser.addBuys.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('editBuys', ChoiceType::class, array(
            'label' => 'CompanyUser.editBuys.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('deleteBuys', ChoiceType::class, array(
            'label' => 'CompanyUser.deleteBuys.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('addDocgroupComptables', ChoiceType::class, array(
            'label' => 'CompanyUser.addDocgroupComptables.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('editDocgroupComptables', ChoiceType::class, array(
            'label' => 'CompanyUser.editDocgroupComptables.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('addDocgroupBanks', ChoiceType::class, array(
            'label' => 'CompanyUser.addDocgroupBanks.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('editDocgroupBanks', ChoiceType::class, array(
            'label' => 'CompanyUser.editDocgroupBanks.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('addDocgroupJuridics', ChoiceType::class, array(
            'label' => 'CompanyUser.addDocgroupJuridics.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('editDocgroupJuridics', ChoiceType::class, array(
            'label' => 'CompanyUser.editDocgroupJuridics.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('addDocgroupFiscals', ChoiceType::class, array(
            'label' => 'CompanyUser.addDocgroupFiscals.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('editDocgroupFiscals', ChoiceType::class, array(
            'label' => 'CompanyUser.editDocgroupFiscals.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('addDocgroupPersos', ChoiceType::class, array(
            'label' => 'CompanyUser.addDocgroupPersos.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('editDocgroupPersos', ChoiceType::class, array(
            'label' => 'CompanyUser.editDocgroupPersos.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('addDocgroupSysts', ChoiceType::class, array(
            'label' => 'CompanyUser.addDocgroupSysts.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
            'attr' => array(
                'choice_label_trans' => true
            )
        ));

        $builder->add('editDocgroupSysts', ChoiceType::class, array(
            'label' => 'CompanyUser.editDocgroupSysts.label',
            'choices' => CompanyUser::choiceTF(),
            'expanded' => true,
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
        return 'CompanyUserUpdateForm';
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
            )
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
