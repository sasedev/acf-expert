<?php
namespace Acf\AdminBundle\Form\CompanyAdmin;

use Acf\DataBundle\Entity\Company;
use Acf\DataBundle\Repository\CompanyRepository;
use Acf\DataBundle\Repository\UserRepository;
use Sasedev\Form\EntityidBundle\Form\Type\EntityidType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
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
            'label' => 'CompanyAdmin.company.label',
            'class' => 'AcfDataBundle:Company',
            'query_builder' => function (CompanyRepository $br) use ($companyId) {
                return $br->createQueryBuilder('c')->where('c.id = :id')->setParameter('id', $companyId)->orderBy('c.corporateName', 'ASC');
            },
            'choice_label' => 'id',
            'multiple' => false,
            'by_reference' => true,
            'required' => true
        ));

        $builder->add('user', EntityType::class, array(
            'label' => 'CompanyAdmin.user.label',
            'class' => 'AcfDataBundle:User',
            'query_builder' => function (UserRepository $ur) use ($companyId) {
                $alreadyUsers = $ur->createQueryBuilder('u')->select('u.id')->join('u.admCompanies', 'c')->where('c.id = :id')->setParameter('id', $companyId)->getQuery()->execute();

                if (count($alreadyUsers) != 0) {
                    return $ur->createQueryBuilder('u')->where('u.id NOT IN (:ulist)')->setParameter('ulist', $alreadyUsers)->orderBy('u.username', 'ASC');
                } else {
                    return $ur->createQueryBuilder('u')->orderBy('u.username', 'ASC');
                }
            },
            'choice_label' => 'fullName',
            'multiple' => false,
            'by_reference' => true,
            'required' => true
        ));
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'CompanyAdminNewForm';
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
