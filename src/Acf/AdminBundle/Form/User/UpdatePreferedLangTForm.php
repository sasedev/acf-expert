<?php
namespace Acf\AdminBundle\Form\User;

use Acf\DataBundle\Repository\LangRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class UpdatePreferedLangTForm extends AbstractType
{

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
        $builder->add('preferedLang', EntityType::class, array(
            'label' => 'User.preferedLang.label',
            'class' => 'AcfDataBundle:Lang',
            'query_builder' => function (LangRepository $lr) {
                return $lr->createQueryBuilder('l')
                    ->orderBy('l.locale', 'ASC');
            },
            'choice_label' => 'name',
            'multiple' => false,
            'by_reference' => true,
            'required' => false,
            'placeholder' => 'Options.choose',
            'empty_data' => null
        ));
    }

    /**
     *
     * {@inheritdoc} @see FormTypeInterface::getName()
     * @return string
     */
    public function getName()
    {
        return 'UserUpdatePreferedLangForm';
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
                'Default'
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
