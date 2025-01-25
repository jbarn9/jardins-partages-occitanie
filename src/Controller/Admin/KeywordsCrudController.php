<?php

namespace App\Controller\Admin;

use App\Entity\Keywords;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class KeywordsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Keywords::class;
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

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('label')->setRequired(true),
        ];
    }
}
