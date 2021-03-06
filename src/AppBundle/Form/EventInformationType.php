<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 11-2-2016
 * Time: 21:52
 */

namespace AppBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * Form type for Event information.
 *
 * @see AppBundle\Entity\Event Event
 * @package AppBundle\Form
 */
class EventInformationType extends AbstractType
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
            ->add('date', DateTimeType::class/*, array('attr' => array('style' => 'select margin-right:10px'))*/)
            ->add('description', TextareaType::class, array( 'required' => false, 'attr' => array('rows' => '10') ))
            ->add('foto', FotoType::class)
            ->add('save', SubmitType::class);
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