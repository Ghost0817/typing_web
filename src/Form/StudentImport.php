<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class StudentImport extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, array(
                'label' => 'Select CSV File:',
                'required'    => true,
                'constraints' => array(
                   new NotBlank(),
                ),
            ))
            ->add('grade', EntityType::class,array(
                'label'=>'Import to Class:',
                'class' => 'App:Grade',
                'required'    => false,
                'placeholder' => 'Ungrouped Students',
                'empty_data'  => null,
            ))
            ->add('save', SubmitType::class, array(
                'label'=>'Begin Import',
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
            'data_class' => 'App\Form\Model\ImportStudent',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'intention'       => 'task_item',
        ));
    }
}
