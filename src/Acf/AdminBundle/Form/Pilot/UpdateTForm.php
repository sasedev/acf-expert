<?php

namespace Acf\AdminBundle\Form\Pilot;

use Acf\DataBundle\Entity\Pilot;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author sasedev
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
		$builder->add('ref', TextType::class, array('label' => 'Pilot.ref.label', 'required' => false));

		$builder->add('mission', TextType::class, array('label' => 'Pilot.mission.label', 'required' => false));

		$builder->add('natureMission', TextType::class, array('label' => 'Pilot.natureMission.label', 'required' => false));

		$builder->add('prestataire', TextType::class, array('label' => 'Pilot.prestataire.label', 'required' => false));

		$builder->add('recetteFinance', TextType::class, array('label' => 'Pilot.recetteFinance.label', 'required' => false));

		$builder->add('pinAnce', TextType::class, array('label' => 'Pilot.pinAnce.label', 'required' => false));

		$builder->add('expirationAnce', TextType::class, array('label' => 'Pilot.expirationAnce.label', 'required' => false));

		$builder->add('mpImpots', TextType::class, array('label' => 'Pilot.mpImpots.label', 'required' => false));

		$builder->add('idCnss', TextType::class, array('label' => 'Pilot.idCnss.label', 'required' => false));

		$builder->add('mpCnss', TextType::class, array('label' => 'Pilot.mpCnss.label', 'required' => false));

		$builder->add('nomCac', TextType::class, array('label' => 'Pilot.nomCac.label', 'required' => false));

		$builder->add('dureeMandat', TextType::class, array('label' => 'Pilot.dureeMandat.label', 'required' => false));

		$builder->add('numMandat', TextType::class, array('label' => 'Pilot.numMandat.label', 'required' => false));

		$builder->add('rapportCac', TextType::class, array('label' => 'Pilot.rapportCac.label', 'required' => false));

		$builder->add('declEmpl', TextType::class, array('label' => 'Pilot.declEmpl.label', 'required' => false));

		$builder->add('isDur', TextType::class, array('label' => 'Pilot.isDur.label', 'required' => false));

		$builder->add('pvCa', TextType::class, array('label' => 'Pilot.pvCa.label', 'required' => false));

		$builder->add('rapportGerance', TextType::class, array('label' => 'Pilot.rapportGerance.label', 'required' => false));

		$builder->add('pvAgo', TextType::class, array('label' => 'Pilot.pvAgo.label', 'required' => false));

		$builder->add('pvAge', TextType::class, array('label' => 'Pilot.pvAge.label', 'required' => false));

		$builder->add('livresCotes', TextType::class, array('label' => 'Pilot.livresCotes.label', 'required' => false));

		$builder->add('honTeorAnn', NumberType::class, array('label' => 'Pilot.honTeorAnn.label', 'required' => false));

		$builder->add('modeFact', TextType::class, array('label' => 'Pilot.modeFact.label', 'required' => false));

		$builder->add('nonFactMont', NumberType::class, array('label' => 'Pilot.nonFactMont.label', 'required' => false));

		$builder->add('nonFactDesc', TextType::class, array('label' => 'Pilot.nonFactDesc.label', 'required' => false));

		$builder->add('nonEncMont', NumberType::class, array('label' => 'Pilot.nonEncMont.label', 'required' => false));

		$builder->add('nonEncDesc', TextType::class, array('label' => 'Pilot.nonEncDesc.label', 'required' => false));

		$builder->add('commentQuit', TextType::class, array('label' => 'Pilot.commentQuit.label', 'required' => false));

		$builder->add('mqQuitImpots', TextType::class, array('label' => 'Pilot.mqQuitImpots.label', 'required' => false));

		$builder->add('mqQuitCnss', TextType::class, array('label' => 'Pilot.mqQuitCnss.label', 'required' => false));

		$builder->add('comments', TextType::class, array('label' => 'Pilot.comments.label', 'required' => false));
	}

	/**
	 *
	 * {@inheritDoc} @see FormTypeInterface::getName()
	 * @return string
	 */
	public function getName()
	{
		return 'PilotUpdateForm';
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
			'validation_groups' => array('mission', 'recetteFinance', 'nomCac', 'dureeMandat', 'numMandat', 'rapportCac', 'declEmpl',
				'isDur', 'pvCa', 'pvAge', 'livresCotes', 'facturation', 'encaissement', 'pinAnce', 'expirationAnce', 'mpImpots', 'idCnss',
				'mpCnss'));
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
