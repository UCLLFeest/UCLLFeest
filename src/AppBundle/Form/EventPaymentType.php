<?php
/**
 * Created by PhpStorm.
 * User: croewens
 * Date: 24/02/2016
 * Time: 13:10
 */
namespace AppBundle\Form;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * Form type for Event payment.
 *
 * @see AppBundle\Entity\Event Event
 * @package AppBundle\Form
 */
class EventPaymentType extends AbstractType
{
    /**
     * Builds the form.
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('selling', CheckboxType::class, array('required' => false, 'label' => 'Selling Tickets?'))
            ->add('price', MoneyType::class, array('required' => false))
            ->add('capacity', IntegerType::class, array('required' => false, 'label' => 'Capaciteit'))
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