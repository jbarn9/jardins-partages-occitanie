<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NotificationsController extends AbstractController
{
    #[Route('/admin/notifications', name: 'app_admin_notifications')]
    public function index(): Response
    {
        return $this->render('admin/notifications/index.html.twig', [
            'controller_name' => 'NotificationsController',
        ]);
    }
}
