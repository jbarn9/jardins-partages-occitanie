<?php

namespace App\Controller\API;

use DateTime;
use DateTimeImmutable;
use Google\Service\Calendar as ServiceCalendar;
use Google_Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GoogleCalendarController extends AbstractController
{
    private ServiceCalendar $service;
    public function __construct(private readonly ParameterBagInterface $params)
    {
        $client = new \Google_Client();
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
            return $this->render('google_calendar/index.html.twig', [
                'error' => 'Missing service account details',
            ]);
        }
        // Set the application name
        $client->setApplicationName('semeursdejardins');
        // Set the redirect URI
        $client->setRedirectUri('http://127.0.0.1:8000/google/calendar/');
        // Set the scopes
        $client->setScopes('https://www.googleapis.com/auth/calendar.readonly');
        // Set the subject
        $client->setSubject('rsj-23@rsj2025.iam.gserviceaccount.com');
        // Set the access type
        $client->setAccessType('select_account consent');
        // Create the service
        $this->service = new ServiceCalendar($client);
        // Disable SSL verification
        $guzzleClient = new \GuzzleHttp\Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false,),));
        // Set the HTTP client
        $client->setHttpClient($guzzleClient);
        // Get the calendar list
    }
    #[Route('/google/calendar/', name: 'app_google_calendar')]
    public function index(): Response
    {
        // Get last 10 events
        try {
            $dateNow = new DateTimeImmutable('now');
            $dateNowPlusOneWeek = $dateNow->modify('+1 week');
            $events = $this->service->events->listEvents('semeursdejardinslr@gmail.com', [
                'orderBy' => 'startTime',
                'singleEvents' => true,
                'timeMin' => $dateNow->format(DateTime::RFC3339),
                'timeMax' => $dateNowPlusOneWeek->format(DateTime::RFC3339),

            ]);
        } catch (\Exception $e) {
            throw new \Exception('Erreur lors de la récupération des événements : ' . $e->getMessage());
        }
        // Get all events 
        $calendar = $this->service->events->listEvents('semeursdejardinslr@gmail.com', [
            'singleEvents' => true,
            'orderBy' => 'startTime',
            'timeZone' => 'Europe/Paris',
            'timeMin' => $dateNow->format(DateTime::RFC3339),
        ]);
        // dd($calendar, $events);

        return $this->render('google_calendar/index.html.twig', [
            'events' => $events,
            'calendarList' => $calendar,
        ]);
    }

    #[Route('/google/calendar/event/{id}', name: 'app_google_calendar_event')]
    public function event($id): JsonResponse
    {
        try {
            $event = $this->service->events->get('semeursdejardinslr@gmail.com', $id);
            // dd($events);
        } catch (\Exception $e) {
            throw new \Exception('Erreur lors de la récupération des événements : ' . $e->getMessage());
        }
        return new JsonResponse($event);
    }

    #[Route('/google/calendar/coming-soon', name: 'app_coming_soon')]
    public function comingSoon(): Response
    {
        // Get last 10 events
        try {
            $dateNow = new DateTimeImmutable('now');
            $dateNowPlusOneWeek = $dateNow->modify('+1 week');
            $events = $this->service->events->listEvents('semeursdejardinslr@gmail.com', [
                'maxResults' => 10,
                'orderBy' => 'startTime',
                'singleEvents' => true,
                'timeMin' => $dateNow->format(DateTime::RFC3339),
                'timeMax' => $dateNowPlusOneWeek->format(DateTime::RFC3339),

            ]);
        } catch (\Exception $e) {
            throw new \Exception('Erreur lors de la récupération des événements : ' . $e->getMessage());
        }
        return $this->render('google_calendar/coming.html.twig', [
            'events' => $events,
        ]);
    }
}
