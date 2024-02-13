<?php

namespace App\Form;

use App\Entity\Application;
use App\Entity\EntrepriseProfil;
use App\Entity\Offer;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApplicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('status')
            // ->add('createdAt')
            ->add('message')
            //             ->add('User', EntityType::class, [
            //                 'class' => User::class,
            // 'choice_label' => 'id',
            //             ])
            //             ->add('Offer', EntityType::class, [
            //                 'class' => Offer::class,
            // 'choice_label' => 'id',
            //             ])
            //             ->add('Entreprise', EntityType::class, [
            //                 'class' => EntrepriseProfil::class,
            // 'choice_label' => 'id',
            //             ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Application::class,
        ]);
    }
}
