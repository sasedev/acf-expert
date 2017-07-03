<?php
namespace Acf\AdminBundle\Form\BulletinInfoTitle;

use Acf\DataBundle\Entity\BulletinInfo;
use Acf\DataBundle\Repository\BulletinInfoRepository;
use Sasedev\Form\EntityidBundle\Form\Type\EntityidType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
     * @var BulletinInfo
     */
    private $bulletinInfo;

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
        $this->bulletinInfo = $options['bulletinInfo'];

        if (null == $this->bulletinInfo) {
            $builder->add('bulletinInfo', EntityType::class, array(
                'label' => 'BulletinInfoTitle.bulletinInfo.label',
                'class' => 'AcfDataBundle:BulletinInfo',
                'query_builder' => function (BulletinInfoRepository $bir) {
                    return $bir->createQueryBuilder('bi')
                        ->orderBy('bi.num', 'ASC');
                },
                'choice_label' => 'num',
                'multiple' => false,
                'by_reference' => true,
                'required' => true
            ));
        } else {
            $biId = $this->bulletinInfo->getId();
            $builder->add('bulletinInfo', EntityidType::class, array(
                'label' => 'BulletinInfoTitle.bulletinInfo.label',
                'class' => 'AcfDataBundle:BulletinInfo',
                'query_builder' => function (BulletinInfoRepository $bir) use ($biId) {
                    return $bir->createQueryBuilder('bi')
                        ->where('bi.id = :id')
                        ->setParameter('id', $biId)
                        ->orderBy('bi.num', 'ASC');
                },
                'choice_label' => 'id',
                'multiple' => false,
                'by_reference' => true,
                'required' => true
            ));
        }

        $builder->add('title', TextType::class, array(
            'label' => 'BulletinInfoTitle.title.label'
        ));
    }

    /**
     *
     * {@inheritdoc} @see \Symfony\Component\Form\FormTypeInterface::getName()
     * @return string
     */
    public function getName()
    {
        return 'BulletinInfoTitleNewForm';
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
                'title'
            ),
            'bulletinInfo' => null
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
