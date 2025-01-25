<?php

namespace App\Form;

use App\Entity\Association;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PasswordStrength;
use App\Validator\Constraints\WordCount;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer une adresse email',
                    ]),
                    new Email([
                        'message' => 'Veuillez entrer une adresse email valide',
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les conditions d\'utilisation.',
                    ]),
                ],

            ])
            ->add('firstname', TextType::class, [
                'attr' => ['class' => 'register-input']
            ])
            ->add('lastname', TextType::class, [
                'attr' => ['class' => 'register-input']
            ])
            ->add('login', TextType::class, [
                'attr' => ['class' => 'register-input']
            ])

            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'label' => 'Mot de passe',
                'attr' => ['autocomplete' => 'off', 'aria-label' => 'password', 'aria-describedby' => 'passwordHelp'],
                'invalid_message' => 'The password fields must match.',
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe', 'attr' => ['class' => 'form-control password-field']],
                'second_options' => ['label' => 'Confirmer le mot de passe', 'attr' => ['class' => 'form-control']],
                'constraints' => [
                    new Length([
                        'min' => 8,
                        'max' => 100,
                        'minMessage' => 'Le mot de passe doit contenir au moins 8 caractères',
                        'maxMessage' => 'Le mot de passe ne doit pas contenir plus de 100 caractères',
                    ]),
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new PasswordStrength([
                        'minScore' => PasswordStrength::STRENGTH_WEAK,
                        'message' => 'Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial
                        et contenir au moins 8 caractères',
                    ]),
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Éditeur' => 'ROLE_EDITOR',
                    'Administrateur' => 'ROLE_ADMIN'
                ],
                'expanded' => false, // Utiliser un menu déroulant
                'multiple' => true, // Assurez-vous que cela est à false pour une seule sélection
                'label' => 'Rôles sur le site',
                'required' => true,
                'placeholder' => 'Sélectionnez un rôle', // Optionnel : ajoute un placeholder
            ])
            ->add('user_asso', EntityType::class, [
                'class' => Association::class,
                'label' => 'A quel réseau appartient l\'utilisateur ?',
                'attr' => ['class' => 'register-input'],
                'choice_label' => 'name',
                'placeholder' => 'Sélectionnez une association',
                'multiple' => false,
                'expanded' => false,
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'register_intention',
        ]);
    }
}
