<?php
/**
 * Created by PhpStorm.
 * User: croewens
 * Date: 24/02/2016
 * Time: 13:09
 */

namespace AppBundle\Form;


use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Form type for Event venue.
 *
 * @see AppBundle\Entity\Event Event
 * @package AppBundle\Form
 */
class EventVenueType extends AbstractType
{
    /**
     * Builds the form.
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('venue', IntegerType::class, array('label' => 'Venue ID'))
            ->add('search', SubmitType::class);
    }

	/**
	 * Sets options.
	 * @param OptionsResolver $resolver
	 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Event',
        ));
    }
}