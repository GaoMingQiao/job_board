<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Offer;
use App\Entity\ContractType;
use App\Entity\EntrepriseProfil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Titre de l'offre d'emploi"
                ]

            ])
            // ->add('slug')
            ->add('shortDescription')
            ->add('content')
            // ->add('createdAt')
            ->add('salary', MoneyType::class)
            ->add('location')
            // ->add('isActive') //créer une offre pas nessessary
            ->add('contractType', EntityType::class, [
                'class' => ContractType::class,
                'choice_label' => 'name',
            ])
            // ->add('entrepriseProfil', EntityType::class, [
            //     'class' => EntrepriseProfil::class,
            //     'choice_label' => 'id',
            // ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'name',
                'multiple' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offer::class,
        ]);
    }
}
