<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContactType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label'=>'Name',
                'attr' => array('tabindex' => "1", 'placeholder' => 'Name'),
                'constraints' => array(
                   new NotBlank(),
                   new Length(array('min' => 7,'max' => 30)),
                ),
            ))
            ->add('email', EmailType::class, array('label'=>'Email',
                'attr' => array('tabindex' => "2", 'placeholder' => 'Email address'),
                'constraints' => array(
                   new NotBlank(),
                   new Length(array('min' => 7,'max' => 30)),
                ),
            ))
            ->add('body', TextareaType::class, array('label'=>'Message',
                'attr' => array( 'rows' => '8','tabindex' => "3", 'placeholder' => 'Enter message or comment'),
                'constraints' => array(
                   new NotBlank(),
                   new Length(array('min' => 7,'max' => 800)),
                ),
            ))
            ->add('save', SubmitType::class, array(
                'label'=>'Send Message',
                'attr' => array('class' => 'btn btn-defualt', 'onclick'=>'DisableButton(this);'),
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Contact',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'intention'       => 'contactus_item',
        ));
    }
}
