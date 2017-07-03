<?php
namespace Acf\AdminBundle\Form\Docgroupaudit;

use Acf\DataBundle\Entity\Company;
use Acf\DataBundle\Repository\DocgroupauditRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class UpdateParentTForm extends AbstractType
{

    /**
     *
     * @var Company
     */
    private $company;

    /**
     *
     * @var string
     */
    private $selfUrl;

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
        $this->selfUrl = $options['selfUrl'];
        $this->company = $options['company'];
        $selfUrl = $this->selfUrl;

        if (null == $this->company) {
            $builder->add('parent', EntityType::class, array(
                'label' => 'Docgroupaudit.parent.label',
                'class' => 'AcfDataBundle:Docgroupaudit',
                'query_builder' => function (DocgroupauditRepository $dgr) use ($selfUrl) {
                    $qb = $dgr->createQueryBuilder('d')
                        ->where('d.pageUrlFull NOT LIKE :url');
                    $qb->setParameter('url', $selfUrl . '%');

                    return $qb->addOrderBy('d.pageUrlFull', 'ASC');
                },
                'choice_label' => 'pageUrlFull',
                'multiple' => false,
                'by_reference' => true,
                'required' => false,
                'placeholder' => 'Options.choose',
                'empty_data' => null
            ));
        } else {
            $companyId = $this->company->getId();
            $builder->add('parent', EntityType::class, array(
                'label' => 'Docgroupaudit.parent.label',
                'class' => 'AcfDataBundle:Docgroupaudit',
                'query_builder' => function (DocgroupauditRepository $dgr) use ($selfUrl, $companyId) {
                    $qb = $dgr->createQueryBuilder('d')
                        ->join('d.company', 'c')
                        ->where('c.id = :cid')
                        ->andWhere('d.pageUrlFull NOT LIKE :url');
                    $qb->setParameter('url', $selfUrl . '%');
                    $qb->setParameter('cid', $companyId);

                    return $qb->addOrderBy('d.pageUrlFull', 'ASC');
                },
                'choice_label' => 'pageUrlFull',
                'multiple' => false,
                'by_reference' => true,
                'required' => false,
                'placeholder' => 'Options.choose',
                'empty_data' => null
            ));
        }
    }

    /**
     *
     * {@inheritdoc} @see \Symfony\Component\Form\FormTypeInterface::getName()
     * @return string
     */
    public function getName()
    {
        return 'DocgroupauditUpdateParentForm';
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
                'parent'
            ),
            'selfUrl' => '/',
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
