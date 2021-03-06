<?php

/**
 * Class RoleType
 */

namespace AppBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * Form type for Role.
 *
 * @see AppBundle\Entity\Role Role
 * @package AppBundle\Form
 */
class RoleType extends AbstractType
{
	/**
	 * Builds the form.
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('name', TextType::class)
			->add('requiredRole', EntityType::class,
				array(
					'class' => 'AppBundle:Role',
					'choice_label' => 'name',
					'placeholder' => 'None',
					'required' => false
				))
			->add('locked', CheckboxType::class,
				array(
					'label' => 'Lock this role?',
					'required' => false
				));
	}

	/**
	 * Sets options.
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\Role',
		));
	}
}