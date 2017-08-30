<?php
namespace Acf\AdminBundle\Form\LiasseDoc;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Acf\DataBundle\Entity\LiasseFolder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Acf\DataBundle\Repository\LiasseFolderRepository;
use Sasedev\Form\EntityidBundle\Form\Type\EntityidType;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class NewTForm extends AbstractType
{

    /**
     *
     * @var LiasseFolder
     */
    private $folder;

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
        $this->folder = $options['folder'];

        if (null == $this->folder) {
            $builder->add('folder', EntityType::class, array(
                'label' => 'LiasseDoc.folder.label',
                'class' => 'AcfDataBundle:LiasseFolder',
                'query_builder' => function (LiasseFolderRepository $br) {
                    return $br->createQueryBuilder('c')->orderBy('c.title', 'ASC');
                },
                'choice_label' => 'title',
                'multiple' => false,
                'by_reference' => true,
                'required' => true
            ));
        } else {
            $folderId = $this->folder->getId();
            $builder->add('folder', EntityidType::class, array(
                'label' => 'LiasseDoc.folder.label',
                'class' => 'AcfDataBundle:LiasseFolder',
                'query_builder' => function (LiasseFolderRepository $br) use ($folderId) {
                    return $br->createQueryBuilder('c')->where('c.id = :id')->setParameter('id', $folderId)->orderBy('c.title', 'ASC');
                },
                'choice_label' => 'id',
                'multiple' => false,
                'by_reference' => true,
                'required' => true
            ));
        }

        $builder->add('fileName', FileType::class, array(
            'label' => 'LiasseDoc.fileName.label'
        ));

        $builder->add('title', TextType::class, array(
            'label' => 'LiasseDoc.title.label'
        ));

        $builder->add('description', TextareaType::class, array(
            'label' => 'LiasseDoc.description.label',
            'required' => false
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'LiasseDocNewForm';
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
                'fileName',
                'description'
            ),
            'folder' => null
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
