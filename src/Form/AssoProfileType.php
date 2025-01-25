<?php

namespace App\Form;

use App\Entity\Association;
use App\Validator\Constraints\WordCount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Mime\Message;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\RegexValidator;

class AssoProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'attr' => ['class' => 'js-name'],
                'label' => 'Nom de l\'association',
                'required' => true,
                'data' => $options['data']->getName() ?? 'Le nom de l\'association',
            ])
            ->add('acronyme', null, [
                'attr' => ['class' => 'js-acronyme'],
                'label' => 'Acronyme',
                'required' => true,
                'data' => $options['data']->getAcronyme() ?? 'L\'acronyme de l\'association',
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'js-email'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer une adresse email',
                    ])
                ],
            ])
            ->add('founded_at', DateType::class, [
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker'],
                'html5' => false,
                'input'  => 'datetime_immutable',
                'data' => $options['data']->getFoundedAt() ?? new \DateTimeImmutable(),
            ])
            ->add('mantra', TextareaType::class, [
                'attr' => ['class' => 'js-textarea'],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mantra',
                    ]),
                    new WordCount(4, 10, null, 'Le mantra doit contenir au moins {{ min }} mots', 'Le mantra doit contenir au plus {{ max }} mots'),
                ],
            ])
            ->add('banner', FileType::class, [
                'label' => 'Bannière d\'accueil',
                'attr' => ['class' => 'js-file'],
                'mapped' => false,
                'attr' => [
                    'accept' => 'image/png, image/jpeg, image/webp, image/jpg, image/svg+xml'
                ],
                'constraints' => [
                    new Image(
                        minWidth: 300,
                        maxWidth: 4400,
                        minHeight: 300,
                        maxHeight: 4400,
                        mimeTypes: [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                            'image/jpg',
                            'image/svg+xml',
                        ],
                        maxSize: '15M',
                        maxSizeMessage: 'La taille du fichier est trop grande',
                    )
                ]
            ])
            ->add('mobile', TelType::class, [
                'label' => 'Téléphone',
                'attr' => ['class' => 'js-tel'],
                'required' => false,
                'data' => $options['data']->getMobile() ?? 'Le n° de téléphone de l\'association',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',

                'required' => false,
                'attr' => ['class' => 'js-textarea'],
                'data' => $options['data']->getDescription(),
                'empty_data' => 'La description de l\'association',
            ])
            ->add('logo', FileType::class, [
                'label' => 'Logo',
                'attr' => ['class' => 'js-file'],
                'mapped' => false,
                'attr' => [
                    'accept' => 'image/png, image/jpeg, image/webp, image/jpg, image/svg+xml'
                ],
                'constraints' => [
                    new Image(
                        minWidth: 300,
                        maxWidth: 2600,
                        minHeight: 300,
                        maxHeight: 2600,
                        mimeTypes: [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                            'image/jpg',
                            'image/svg+xml',
                        ],
                        maxSize: '7M',
                        maxSizeMessage: 'La taille du fichier est trop grande',
                    )
                ]
            ])
            ->add('Enregistrer', SubmitType::class, [

                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn btn-primary'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Association::class,
        ]);
    }
}
