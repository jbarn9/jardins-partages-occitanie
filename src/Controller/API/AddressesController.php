<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/api/addresses', name: 'app_api_addresses')]
class AddressesController extends AbstractController
{
    #[Route('/', name: 'app_addresses')]
    public function index(HttpClientInterface $client): Response
    {
        // TODO: pour effectuer un appel sur l'API, il faut utiliser le client HTTP, pensez à changer l'URL et la méthode
        $response = $client->request('GET', 'https://api-adresse.data.gouv.fr/search/?q=8+bd+du+port&limit=15');

        return $this->render('addresses/index.html.twig', [
            'response' => $response->toArray(),
        ]);
    }
}
