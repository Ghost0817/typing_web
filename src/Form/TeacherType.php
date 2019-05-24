<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TeacherType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('organization', TextType::class)
            ->add('username', TextType::class, ['label' => 'Your Name'])
            ->add('email', EmailType::class)
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'Нууц үгүүд адилхан биш байна.',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
            ->add('orgType', ChoiceType::class, [
                'choices'  => [
                    'Public School' => 'public',
                    'Private School' => 'private',
                    'College Instructor' => 'college',
                    'Home School' => 'home',
                    'Corporation/Company' => 'company',
                    'Government' => 'government',
                    'Other' => 'other',
                ],
                'placeholder' => 'Organization Type',
                'label' => 'Organization Type'])
            ->add('save', SubmitType::class, array(
                'label'=>'Sign Up',
                'attr' => array('class' => 'btn btn-defualt'),
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Teacher',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'intention' => 'Teacher_item',
        ));
    }
}
