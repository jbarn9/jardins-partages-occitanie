<?php

namespace App\Controller\Admin;

use App\Entity\SubjectEmail;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SubjectEmailCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SubjectEmail::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud

            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Objet')
            ->setEntityLabelInPlural('Objets')
            ->setPageTitle('index', 'Gestion des objets du formulaire de contact')
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
            FormField::addPanel('Définition des objets du formulaire de contact'),
            TextField::new('label', 'Titre de l\'objet')->setRequired(true),
        ];
    }
}
