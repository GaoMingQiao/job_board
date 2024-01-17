<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Profile;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre nom'
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre prenom'
                ]
            ])

            ->add('adresse', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre adresse'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre city'
                ]
            ])

            ->add('zipCode', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez votre code postal'
                ]
            ])

            ->add('country', CountryType::class, [
                'label' => false,
                'preferred_choices' => [
                    'FR',
                    'BE',
                    'CH',
                    'LU',

                ],
            ])
            ->add('phoneNumber', TelType::class)
            ->add('jobSought', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entre le poste recherché'
                ]
            ])
            ->add('presentation', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entre la presentation'
                ]
            ])
            ->add('availability', CheckboxType::class, [
                'label' => 'Etes vous disponible?'
            ])
            ->add('website', UrlType::class)
            ->add('imageFile', FileType::class, [
                'label' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '3M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => "Merci d'upload une image de type jpg,jpeg,png ou webp",

                    ])
                ]
            ])
            //             ->add('user', EntityType::class, [
            //                 'class' => User::class,
            // 'choice_label' => 'id',
            //             ])//c'est pas à utilisateur à fournir
            ->add('ajouter', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }
}
