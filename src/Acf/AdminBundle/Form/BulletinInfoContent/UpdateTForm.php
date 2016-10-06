<?php
namespace Acf\AdminBundle\Form\BulletinInfoContent;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
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
        $builder->add('content', TextareaType::class, array(
            'label' => 'BulletinInfoContent.content.label',
            'required' => false
        ));

        $builder->add('theme', TextType::class, array(
            'label' => 'BulletinInfoContent.theme.label',
            'required' => false
        ));

        $builder->add('jort', TextType::class, array(
            'label' => 'BulletinInfoContent.jort.label',
            'required' => false
        ));

        $builder->add('txtNum', TextType::class, array(
            'label' => 'BulletinInfoContent.txtNum.label',
            'required' => false
        ));

        $builder->add('artTxt', TextType::class, array(
            'label' => 'BulletinInfoContent.artTxt.label',
            'required' => false
        ));

        $builder->add('dtTxt', TextType::class, array(
            'label' => 'BulletinInfoContent.dtTxt.label',
            'required' => false
        ));

        $builder->add('artCode', TextType::class, array(
            'label' => 'BulletinInfoContent.artCode.label',
            'required' => false
        ));

        $builder->add('companyType', TextType::class, array(
            'label' => 'BulletinInfoContent.companyType.label',
            'required' => false
        ));

        $builder->add('dtApplication', TextType::class, array(
            'label' => 'BulletinInfoContent.dtApplication.label',
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
        return 'BulletinInfoContentUpdateForm';
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
                'content',
                'theme',
                'jort',
                'txtNum',
                'artTxt',
                'dtTxt',
                'artCode',
                'companyType',
                'dtApplication'
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
