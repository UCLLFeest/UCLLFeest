<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use AppBundle\Entity\Gender;

/**
 * Form type for Edit User.
 *
 * @see AppBundle\Entity\User User
 * @package AppBundle\Form
 */
class EditUserType extends AbstractType
{
    /**
     * Builds the form.
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, array(
                'label' => 'First name'
            ))
            ->add('lastname', TextType::class, array(
                'label' => 'Last name'
            ))
            ->add('gender', ChoiceType::class, array(
                'placeholder' => 'Choose an option',
                'choices' => Gender::getPrettyMap(),
                'choices_as_values' => true,
            ))
            ->add('birthday', BirthdayType::class);
    }

	/**
	 * Sets options.
	 * @param OptionsResolver $resolver
	 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }
}