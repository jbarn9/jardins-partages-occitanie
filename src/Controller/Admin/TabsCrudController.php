<?php

namespace App\Controller\Admin;

use App\Entity\Tabs;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\AssetDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TabsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tabs::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Onglet')
            ->setEntityLabelInPlural('Onglets')
            ->setPageTitle('index', 'Listes des %entity_label_plural%')
            ->setPageTitle('detail', fn(Tabs $tab) => (string) $tab)
            ->setPageTitle('edit', fn(Tabs $tab) => sprintf('Edition de "<b>%s</b>"', $tab->getLabel()))
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
            ->disable('new')
            ->disable('delete');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('label')->setLabel('Nom de l\'onglet'),
            SlugField::new('slug')->setTargetFieldName('label')->setUnlockConfirmationMessage(
                'Il est recommandé d\'utiliser les slugs automatiques, mais vous pouvez les personnaliser'
            ),
            AssociationField::new('tabs_posts')->setLabel('Article(s) associé(s)'),
            AssociationField::new('pages')->setLabel('Nom de la page')
        ];
    }
}
