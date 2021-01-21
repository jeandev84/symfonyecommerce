<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Class ResetPasswordType
 * @package App\Form
*/
class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
//                'mapped' => false,
                'invalid_message' => 'Le mot de passe et la confirmation doivent etre identique.',
                'label' => 'Mon nouveau mot de password',
                'required' => true,
                'first_options' => [
                    'label' => 'Mon nouveau mot de passe',
                    'attr'  => [
                        'placeholder' => 'Merci de saisir votre nouveau mot de password'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmez votre nouveau mot de passe',
                    'attr'  => [
                        'placeholder' => 'Merci de confirmer votre nouveau mot password'
                    ]
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Mettre a jour mon mot de passe",
                'attr' => [
                    'class' => 'btn-block btn-info'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
