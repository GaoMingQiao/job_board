<?php

namespace App\Controller\Admin;

use App\Entity\HomeSetting;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class HomeSettingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return HomeSetting::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(), //hideOnIndex() on peut voir ce champs sur page d'accueil de admin
            TextField::new('message'),
            //parce que j'ai pas
            // TextEditorField::new('description'),
            TextField::new('callToAction', 'Bouton'),
            ImageField::new('image')
                ->setBasePath('uploads/')
                ->setUploadDir('public/uploads')
                ->setUploadedFileNamePattern('[randomhash].[extension]') // Corrected line
                //en cas de modifier so false
                ->setRequired(false),
            DateTimeField::new('createdAt', 'Ajouté le')->hideOnForm()->setFormat('dd/MM/yyyy'),
        ];
    }
}
