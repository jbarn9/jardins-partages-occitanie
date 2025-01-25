<?php

namespace App\Controller\Admin;

use App\Entity\Addresses;
use App\Entity\Association;
use App\Entity\Categories;
use App\Entity\Keywords;
use App\Entity\Links;
use App\Entity\Posts;
use App\Entity\SubjectEmail;
use App\Entity\Tabs;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/menu_dashboard.html.twig');
    }

    #[Route('/admin/crud', name: 'admin_crud')]
    public function crud(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(PostsCrudController::class)->generateUrl());
    }

    public function configureCrud(): Crud
    {
        return Crud::new();
    }
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle(' Réseau des Semeurs de Jardins')
            ->setFaviconPath('uploads/profiles/SDJ/logo/logo_SDJ.png')
            ->disableDarkMode()

        ;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::subMenu('Blog', 'fas fa-newspaper')->setSubItems([
            MenuItem::linkToCrud('Articles', 'fas fa-newspaper', Posts::class),
            MenuItem::linkToCrud('Images/Logos', 'fas fa-image', Association::class)->setController(Association2CrudController::class),
            MenuItem::linkToCrud('Onglets', 'fas fa-tags', Tabs::class),
            MenuItem::linkToCrud('Mots clés', 'fas fa-key', Keywords::class),
            MenuItem::linkToCrud('Catégories', 'fas fa-list', Categories::class),
            MenuItem::linkToCrud('Liens', 'fas fa-link', Links::class),
        ]);

        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class)->setPermission('ROLE_ADMIN');
        yield MenuItem::subMenu('RSJ', 'fas fa-file-alt')->setSubItems([
            MenuItem::linkToCrud('Page contact', 'fas fa-envelope', SubjectEmail::class),
            MenuItem::linkToCrud('Informations', 'fas fa-building', Association::class),
            MenuItem::linkToCrud('Coordonnées', 'fas fa-map-marker-alt', Addresses::class),
        ]);
    }
}
