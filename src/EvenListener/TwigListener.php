<?php

namespace App\EventListener;

use App\Repository\AssociationRepository;
use App\Service\HeaderService;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\TwigFunction;

class TwigListener
{
    public function __construct(private HeaderService $headerService, private AssociationRepository $assoRepo) {}

    public function onKernelController(ControllerEvent $event)
    {
        $request = $event->getRequest();
        $request->attributes->set('header_data', $this->headerService->getHeaderData($this->assoRepo));
    }
}
