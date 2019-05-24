<?php
/**
 * Created by PhpStorm.
 * User: 22cc3355
 * Date: 1/27/2019
 * Time: 2:18 PM
 */

namespace App\Form;

use App\Entity\Mature;
use App\Entity\Student;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class UpgradedUsersType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tranDate', HiddenType::class)
            ->add('expDate', HiddenType::class)
            ->add('ipAddress', HiddenType::class)
            ->add('isPaid', HiddenType::class)
            ->add('invoiceNumber', HiddenType::class)
            ->add('isSend', HiddenType::class)
            ->add('upgrade', HiddenType::class)
            ->add('student', HiddenType::class)
            ->add('save', SubmitType::class, array(
                'label'=>'Upgrade now',
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
            'data_class' => 'App\Entity\UpgradedUsers'
        ));
    }
}