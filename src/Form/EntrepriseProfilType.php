<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\EntrepriseProfil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EntrepriseProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)

            ->add('address')
            ->add('city')
            ->add('zipCode')
            ->add('country')
            ->add('phoneNumber')
            ->add('activityArea')
            ->add('email', EmailType::class)
            ->add('description', TextareaType::class)
            ->add('logoEntreprise', FileType::class, [
                'label' => false,
                'mapped' => false,
                'attr' => [
                    'placeholder' => "Entrez le logo de votre entreprise"
                ]
            ])
            ->add('website', UrlType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Entrez le website de votre entreprise"
                ]
            ]);
        // ->add('user', EntityType::class, [
        //     'class' => User::class,
        //     'choice_label' => 'id',
        // ])
        // ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EntrepriseProfil::class,
        ]);
    }
}
