<?php

namespace Acf\AdminBundle\Form\BulletinInfoContent;

use Acf\DataBundle\Entity\BulletinInfoTitle;
use Acf\DataBundle\Repository\BulletinInfoTitleRepository;
use Sasedev\Form\EntityidBundle\Form\Type\EntityidType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev
 */
class NewTForm extends AbstractType
{

	/**
	 *
	 * @var BulletinInfoTitle
	 */
	private $bulletinInfoTitle;

	/**
	 * Form builder
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{

		$this->bulletinInfoTitle = $options['bulletinInfoTitle'];

		if (null == $this->bulletinInfoTitle) {
			$builder->add(
				'bulletinInfoTitle',
				EntityType::class,
				array(
					'label' => 'BulletinInfoContent.bulletinInfoTitle.label',
					'class' => 'AcfDataBundle:BulletinInfoTitle',
					'query_builder' => function (BulletinInfoTitleRepository $btr)
					{
						return $btr->createQueryBuilder('bt')
							->orderBy('bt.title', 'ASC');
					},
					'choice_label' => 'title',
					'multiple' => false,
					'by_reference' => true,
					'required' => true
				));
		} else {
			$bt_id = $this->bulletinInfoTitle->getId();
			$builder->add(
				'bulletinInfoTitle',
				EntityidType::class,
				array(
					'label' => 'BulletinInfoContent.bulletinInfoTitle.label',
					'class' => 'AcfDataBundle:BulletinInfoTitle',
					'query_builder' => function (BulletinInfoTitleRepository $btr) use($bt_id)
					{
						return $btr->createQueryBuilder('bt')
							->where('bt.id = :id')
							->setParameter('id', $bt_id)
							->orderBy('bt.title', 'ASC');
					},
					'choice_label' => 'id',
					'multiple' => false,
					'by_reference' => true,
					'required' => true
				));
		}

		$builder->add('title', TextType::class, array(
			'label' => 'BulletinInfoContent.title.label'
		));

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
	 * {@inheritDoc} @see FormTypeInterface::getName()
	 * @return string
	 */
	public function getName()
	{

		return 'BulletinInfoContentNewForm';

	}

	/**
	 *
	 * {@inheritDoc} @see AbstractType::getBlockPrefix()
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
				'title',
				'content',
				'theme',
				'jort',
				'txtNum',
				'artTxt',
				'dtTxt',
				'artCode',
				'companyType',
				'dtApplication'
			),
			'bulletinInfoTitle' => null
		);

	}

	/**
	 *
	 * {@inheritDoc} @see AbstractType::configureOptions()
	 */
	public function configureOptions(OptionsResolver $resolver)
	{

		$resolver->setDefaults($this->getDefaultOptions());

	}
}

?>
