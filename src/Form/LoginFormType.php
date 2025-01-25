<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PasswordStrength;
use Symfony\Component\Validator\Constraints\RegexValidator;

class LoginFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Votre email'
                ],
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer une adresse email',
                    ]),
                ],
            ])
            ->add('password', PasswordType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Mot de passe'
                ],
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer une adresse email',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit faire MINIMUM {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    // check if the password is strong (entropy)
                    new PasswordStrength([
                        'minScore' => PasswordStrength::STRENGTH_WEAK,
                        'message' => 'Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial',
                    ]),
                ],
            ])
            ->add('remember_me', CheckboxType::class, [
                'label' => 'Se souvenir de moi',
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Se connecter'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'csrf_field_name' => '_csrf_token',
            'csrf_token_id'   => 'authenticate',
            'forget_password_path' => '/forget-password'
        ]);
    }
}
