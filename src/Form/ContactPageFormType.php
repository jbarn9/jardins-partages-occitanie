<?php

namespace App\Form;

use App\Entity\SubjectEmail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ContactPageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', EntityType::class, [
                'class' => SubjectEmail::class,
                'choice_label' => 'label',
                'attr' => [
                    'class' => 'form-control form-select',
                ],
                'mapped' => false,
                'placeholder' => 'Choisir un objet',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'L\'objet est obligatoire',
                    ]),
                ],
            ])
            ->add('name', null, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Votre nom',
                    'class' => 'form-control input-name',
                ],
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom est obligatoire',
                    ]),
                    new Length([
                        'min' => 5,
                        'max' => 255,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le nom ne doit pas contenir plus de {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Votre email',
                    'class' => 'form-control input-email',
                ],
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'L\'email est obligatoire',
                    ]),
                    new Email([
                        'message' => 'L\'email est invalide',
                    ]),
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'attr' => [
                    'placeholder' => 'Votre message',
                    'class' => 'form-control textarea-message',
                ],
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le message est obligatoire',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SubjectEmail::class,
        ]);
    }
}
