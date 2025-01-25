<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('login', null, [
                'label' => 'Pseudo ',
                'required' => true,
                'data' => $options['data']->getLogin() ?? 'Votre pseudo',
                'attr' => [
                    'placeholder' => 'Votre pseudo',
                    'class' => 'form-control',
                ],
            ])
            ->add('firstname', null, [
                'label' => 'Prénom',
                'required' => true,
                'data' => $options['data']->getFirstname() ?? 'Votre prénom',
                'attr' => [
                    'placeholder' => 'Votre prénom',
                    'class' => 'form-control',
                ],
            ])
            ->add('lastname', null, [
                'label' => 'Nom',
                'required' => true,
                'data' => $options['data']->getLastname() ?? 'Votre nom',
                'attr' => [
                    'placeholder' => 'Votre nom',
                    'class' => 'form-control',
                ],
            ])
            ->add('mobile', TelType::class, [
                'label' => 'Téléphone',
                'required' => false,
                'data' => $options['data']->getMobile() ?? 'Votre téléphone',
                'attr' => [
                    'placeholder' => 'Votre téléphone',
                    'class' => 'form-control',
                ],
            ])
            ->add('img', FileType::class, [
                'label' => 'Image de profil',
                'mapped' => false,
                'attr' => [
                    'accept' => 'image/png, image/jpeg, image/webp, image/jpg'
                ],
                'required' => false,
                'constraints' => [
                    new Image(
                        minWidth: 300,
                        maxWidth: 1400,
                        minHeight: 300,
                        maxHeight: 1400,
                        mimeTypes: [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                            'image/jpg'
                        ]
                    )
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'button btn-secondary-green py-2',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
