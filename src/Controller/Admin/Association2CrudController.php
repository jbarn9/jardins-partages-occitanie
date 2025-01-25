<?php

namespace App\Controller\Admin;

use App\Entity\Association;
use App\Repository\AssociationRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class Association2CrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Association::class;
    }
    // public static function getSubscribedEvents()
    // {
    //     return [
    //         BeforeEntityPersistedEvent::class => ['setAssociation'],
    //     ];
    // }
    // public function setAssociation(BeforeEntityPersistedEvent $event)
    // {
    //     $entity = $event->getEntityInstance();

    //     if (!($entity instanceof Association)) {
    //         return;
    //     }

    //     dump($entity);
    // }
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
    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('logo')->setLabel('Logo')->setUploadDir('assets/uploads/profiles/SDJ/logo')->setBasePath('/assets/uploads/profiles/SDJ/logo/'),
            ImageField::new('banner')->setLabel('Bannière')->setUploadDir('assets/uploads/profiles/SDJ/banner')->setBasePath('/assets/uploads/profiles/SDJ/banner/'),

        ];
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
            ->disable('delete')
        ;
    }
}
