<?php

namespace App\Controller\Admin;

use App\Entity\Association;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Asset;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AssociationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Association::class;
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

    function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable('new');
    }

    function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel('Informations générales')
                ->setIcon('fa fa-user')->addCssClass('required'),
            TextField::new('name')->setLabel('Nom'),
            TextField::new('acronyme')->setLabel('Acronyme'),
            TextField::new('mantra')->setLabel('Mantra'),
            TextField::new('description')->setLabel('Description'),
            FormField::addPanel('Informations de contact')
                ->setIcon('fa fa-mobile')->addCssClass('required'),
            TextField::new('email')->setLabel('Email'),
            TextField::new('mobile')->setLabel('Téléphone'),
            FormField::addPanel('Coordonnées')
                ->setIcon('fa fa-map-marker'),
            AssociationField::new('address')->setLabel('Adresse'),
        ];
    }
}
