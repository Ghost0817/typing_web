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

use App\Entity\MyKeybroard;

class StudentKeyboardChange extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mykb', EntityType::class,array(
                'label'=>'Keyboard Layout',
                'class' => MyKeybroard::class,
                'choice_label' => 'name',
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
            'data_class' => 'App\Form\Model\StudentChangeKeyboard',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'intention'       => 'task_item',
        ));
    }
}
