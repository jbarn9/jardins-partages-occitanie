<?php

namespace App\Controller\Admin;

use App\Entity\Addresses;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AddressesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Addresses::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud

            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Réseau')
            ->setEntityLabelInPlural('Réseaux')
            ->setPageTitle('index', 'Gestion du réseau')
            ->setFormThemes(['@EasyAdmin/crud/form_theme.html.twig', 'admin/posts/form.html.twig'])

        ;
    }
    public function configureAssets(Assets $assets): Assets
    {
        return $assets
            ->addAssetMapperEntry('app')
            ->addCssFile('styles/admin.css')
            ->addCssFile('styles/form_admin.css')
        ;
    }
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('street')->setLabel('Rue'),
            TextField::new('longitude')->setLabel('Longitude'),
            TextField::new('latitude')->setLabel('Latitude'),
            AssociationField::new('city')->setLabel('Ville'),
        ];
    }
}
