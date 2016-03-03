<?php
/**
 * Created by PhpStorm.
 * User: sven smets
 * Date: 12-2-2016
 * Time: 20:47
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Form type for Foto.
 *
 * @see AppBundle\Entity\Foto Foto
 * @package AppBundle\Form
 */
class FotoType extends AbstractType
{
    /**
     * Builds the form.
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'vich_image', array(
                'required'      => false,
                'allow_delete' => false,
                'download_link' => false
            ))
            ->getForm();
    }

	/**
	 * Sets options.
	 * @param OptionsResolver $resolver
	 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Foto',
        ));
    }


}