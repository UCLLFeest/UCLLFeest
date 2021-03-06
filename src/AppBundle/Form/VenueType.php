<?php
/**
 * Created by PhpStorm.
 * User: croewens
 * Date: 20/02/2016
 * Time: 18:58
 */

namespace AppBundle\Form;

namespace AppBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * Form type for Venue.
 *
 * @see AppBundle\Entity\Venue Venue
 * @package AppBundle\Form
 */
class VenueType extends AbstractType
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
            ->add('adress', TextType::class)
            ->add('city', TextType::class)
            ->add('postalCode', IntegerType::class)
            ->add('description', TextareaType::class, array( "required" => false,'attr' => array('rows' => '10') ))
            ->add('save', SubmitType::class);
    }

	/**
	 * Sets options.
	 * @param OptionsResolver $resolver
	 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Venue',
        ));
    }
}