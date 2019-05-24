<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SettingsChange extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('scoreboard', ChoiceType::class, array(
                'label' => 'Enable Class Scoreboard:',
                'choices'  => array(
                    'yes' => 'Yes',
                    'no' => 'No',
                ),
                'required'    => true,
                'constraints' => array(
                   new NotBlank(),
                ),
            ))
            ->add('save', SubmitType::class, array(
                'label'=>'Update Settings',
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
            'data_class' => 'App\Form\Model\ChangeSettings',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'intention'       => 'task_item',
        ));
    }
}
