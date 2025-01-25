<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PasswordStrength;

class ResetPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'invalid_message' => 'The password fields must match.',
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe', 'attr' => ['class' => 'password-field']],
                'second_options' => ['label' => 'Confirmer le mot de passe'],
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
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Envoyer'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
