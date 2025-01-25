<?php

namespace App\Controller\API;

use App\Service\Utils;
use Google\Service\Drive as ServiceDrive;
use Google\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GoogleDriveController extends AbstractController
{
    private ServiceDrive $service;

    public function __construct(private readonly ParameterBagInterface $params)
    {
        $client = new Client();
        if ($this->params->get('google_application_credentials')) {
            // use the application default credentials
            $client->setAuthConfig($this->params->get('google_application_credentials'));
            try {
                // Returns an instance of GuzzleHttp\Client that authenticates with the Google API.
                $httpClient = $client->authorize();
            } catch (\Exception $e) {
                dd($e);
            }
        } else {
            return $this->render('search/index.html.twig', [
                'error' => 'Missing service account details',
            ]);
        }
        // Set the application name
        $client->setApplicationName('semeursdejardins');
        // Set the redirect URI
        $client->setRedirectUri('http://127.0.0.1:8000/search/drive');
        // Set the scopes
        $client->setScopes('https://www.googleapis.com/auth/drive');
        // Set the subject
        $client->setSubject('rsj-23@rsj2025.iam.gserviceaccount.com');
        // Set the access type
        $client->setAccessType('select_account consent');
        // Create the service
        $this->service = new ServiceDrive($client);
        // Disable SSL verification
        $guzzleClient = new \GuzzleHttp\Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false,),));
        // Set the HTTP client
        $client->setHttpClient($guzzleClient);
        // Get the calendar list

    }
    #[Route('/search', name: 'app_search')]
    public function index(): Response
    {
        return $this->render('search/index.html.twig');
    }

    #[Route('/search/drive/{page?}', name: 'app_search_drive', methods: ['GET', 'POST'])]
    public function drive(?string $keywords = null, ?int $page = null, ?string $order = null, Request $request): Response
    {
        // check if the request is a POST request
        if ($request->isMethod('POST')) {
            // dd($request->request->get('keywords'));
            if ($request->request->get('keywords') != null) {
                $keywords = $request->request->get('keywords');
            }
            if ($request->request->get('order') != null) {
                $order = $request->request->get('order');
                // dd($order);
            }
        }
        if ($page == null) {
            $page = 0;
        }
        if ($order == null) {
            $order = 'all';
        }
        // R.Morez folder id
        $folderOneId = "'1sTXBydEI27J0mkwM-A50jXKRLpCsD4vI' in parents";
        // RSJ folder id
        $folderTwoId = "'1-HPm2j0bllynbUOkOj4xe6AhH0KMt9dQ' in parents";
        $files = [];
        $message = null;
        $filesPages = [];
        $folderId = [];
        // dd($folderId);
        try {
            // dd($order);
            if ($order == 'all' || $order == null) {
                array_push($folderId, $folderOneId);
                array_push($folderId, $folderTwoId);
            } else if ($folderOneId == $order) {
                array_push($folderId, $folderOneId);
                // dump($folderId);
            } else if ($folderTwoId == $order) {
                array_push($folderId, $folderTwoId);
                // dump($folderId);
            }
            // dd($order, $folderId);

            if (count($folderId) > 1) {
                $option = implode(' or ', $folderId);
                $q = "(" . $option . ") and trashed = false and mimeType != 'application/vnd.google-apps.folder'";
            } else {
                $option = $folderId[0];
                $q = "(" . $option . ") and trashed = false and mimeType != 'application/vnd.google-apps.folder'";
            }
            // dd($option);
            $optParams = array(
                'corpora' => "user",
                'orderBy' => "modifiedTime desc",
                'pageSize' => 25,
                'fields' => "nextPageToken, files(id, name, description, size, createdTime, modifiedTime, webViewLink, thumbnailLink, webContentLink, parents)",
                'q' => $q,
                'includeItemsFromAllDrives' => 'false',
                'supportsAllDrives' => 'false'
            );

            // dd($optParams);

            if ($keywords !== null) {
                $keywordssplited = str_replace("'", "\'", $keywords);
                $keywordssplited = str_replace(' ', "' and fullText contains '", $keywordssplited);
                $optParams['q'] = "(" . $option . ") and trashed = false and mimeType != 'application/vnd.google-apps.folder' and fullText contains '" . $keywordssplited . "'";
                // dd($optParams['q']);
            }
            // dd($optParams);
            $optParams['pageToken'] = null;
            // loop through the files
            do {
                // dd($optParams);
                try {
                    $results = $this->service->files->listFiles($optParams);
                    $files = $results->getFiles();
                    // dd($files);
                    // if the files are not empty, add them to the files array
                    if (count($files) > 0) {
                        foreach ($files as $file) {
                            $allFiles[] = $file;
                        }
                        $optParams['pageToken'] = $results->getNextPageToken();
                        // dump($files);
                        array_push($filesPages, $files);
                    } else {
                        $message = 'Aucun résultat trouvé';
                    }
                } catch (\Exception $e) {
                    $message = $e->getMessage();
                }
            } while ($optParams['pageToken'] != null);
            // dd($filesPages);
            if ($filesPages && $page > count($filesPages) - 1) {
                return $this->redirectToRoute('error_404');
            }
            // dd($filesPages);
        } catch (\Exception $e) {
            $message = 'Erreur lors de la récupération des événements : ' . $e->getMessage();
        }
        if ($request->isMethod('POST')) {
            return $this->render('search/drive.html.twig', [
                'files' => $filesPages,
                'message' => $message,
                'page' => $page,
                'order' => $order,
            ]);
        } else {
            return $this->render('search/index.html.twig', [
                'files' => $filesPages,
                'message' => $message,
                'page' => $page,
                'order' => $order,
            ]);
        }
    }
}
