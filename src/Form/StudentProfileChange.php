<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class StudentProfileChange extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, array('label' => 'Username','required'    => true))
            ->add('email', EmailType::class, array('label' => 'Email'))
            ->add('firstname', TextType::class, array(
                'label' => 'First Name',
                'required'    => true,
                'constraints' => array(
                    new NotBlank(),
                    new Length(array('min' => 3,'max' => 60)),
                ),
            ))
            ->add('lastname', TextType::class, array(
                'label' => 'Last Name',
                'required'    => true,
                'constraints' => array(
                    new NotBlank(),
                    new Length(array('min' => 3,'max' => 60)),
                ),
            ))
            ->add('gender', ChoiceType::class, array(
                'choices' => array('Male' => 'm', 'Female' => 'f'),
            ))
            ->add('save', SubmitType::class, array(
                'label'=>'Save changes »',
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
            'data_class' => 'App\Form\Model\StudentChangeProfile',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'intention'       => 'task_item',
        ));
    }
}
