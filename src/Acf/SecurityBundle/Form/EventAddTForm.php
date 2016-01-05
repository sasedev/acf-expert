<?php

namespace Acf\SecurityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev
 */
class EventAddTForm extends AbstractType
{

	/**
	 * Form builder
	 *
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('title', TextType::class, array('label' => 'Agenda.title.label'));

		$builder->add('comment', TextareaType::class, array('label' => 'Agenda.comment.label', 'required' => false));

		$builder->add('dtStart', DateTimeType::class,
			array('label' => 'Agenda.dtStart.label', 'widget' => 'single_text', 'date_format' => 'Y-m-d\TH:i:sO', 'attr' => array('readonly' => true)));

		$builder->add('dtEnd', DateTimeType::class,
			array('label' => 'Agenda.dtEnd.label', 'widget' => 'single_text', 'date_format' => 'Y-m-d\TH:i:sO', 'attr' => array('readonly' => true)));
	}

	/**
	 *
	 * {@inheritDoc} @see FormTypeInterface::getName()
	 * @return string
	 */
	public function getName()
	{
		return 'EventAddForm';
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
		return array('validation_groups' => array('title', 'comment'), 'csrf_protection' => false);
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
