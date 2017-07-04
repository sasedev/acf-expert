<?php
namespace Acf\AdminBundle\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class UploadAvatarTForm extends AbstractType
{

    /**
     * Form builder
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('avatar', FileType::class, array(
            'label' => 'User.avatar.label',
            'constraints' => array(
                new Image(array(
                    'mimeTypes' => array(
                        'image/jpg',
                        'image/jpeg',
                        'image/pjpeg'
                    ),
                    'maxSize' => '20480k'
                ))
            ),
            'mapped' => false
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'UserUploadAvatarForm';
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
