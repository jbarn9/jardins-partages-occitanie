<?php

namespace App\Service;

use App\Repository\AssociationRepository;

class HeaderService
{
    public function __construct(private TextAnalyzerService $textAnalyzerService) {}

    public function getHeaderData(AssociationRepository $assoRepo): array
    {
        $asso = $assoRepo->findOneBy(['id' => 17]);
        if (empty($asso->getMantra())) {
            $assoMantra[0] = "Choisir un mantra";
        } else {
            $assoMantra = $asso->getMantra();
        }
        if (empty($asso->getBanner())) {
            $assoBanner = null;
        } else {
            $assoBanner = $asso->getBanner();
        }
        if (empty($asso->getLogo())) {
            $assoLogo = null;
        } else {
            $assoLogo = $asso->getLogo();
        }
        if (empty($asso->getAddress())) {
            $assoAddress = null;
        } else {
            $assoAddress = $asso->getAddress();
            // dd($assoAddress);
        }
        if (empty($asso->getDescription())) {
            $assoDescription = null;
        } else {
            $assoDescription = $asso->getDescription();
        }
        if (empty($asso->getEmail())) {
            $assoEmail = null;
        } else {
            $assoEmail = $asso->getEmail();
        }
        if (empty($asso->getMobile())) {
            $assoPhone = null;
        } else {
            $assoPhone = $asso->getMobile();
        }
        if (empty($asso->getLinks())) {
            $assoLinks = null;
        } else {
            $assoLinks = $asso->getLinks();
            $assoLinks = $assoLinks->toArray();
            foreach ($assoLinks as $link) {
                $assoLinks[] = Utils::decode($link->getUrl());
            }
        }

        // decode the data
        $assoMantra = Utils::decode($assoMantra);
        $assoBanner = Utils::decode($assoBanner);
        $assoLogo = Utils::decode($assoLogo);
        $assoAddress = Utils::decode($assoAddress);
        $assoDescription = Utils::decode($assoDescription);
        $assoEmail = Utils::decode($assoEmail);
        $assoPhone = Utils::decode($assoPhone);

        if ($this->textAnalyzerService->getWordCount($assoMantra) > 4 && !str_contains($assoMantra, ",")) {
            $assoMantra = $this->textAnalyzerService->splitTextByWordCount($assoMantra, 4);
        } else {
            $assoMantra = $this->textAnalyzerService->splitAtComma($assoMantra);
        }
        return [
            'assoMantra' => $assoMantra,
            'assoBanner' => $assoBanner,
            'assoLogo' => $assoLogo,
            'assoAddress' => $assoAddress,
            'assoDescription' => $assoDescription,
            'assoEmail' => $assoEmail,
            'assoPhone' => $assoPhone,
            'assoLinks' => $assoLinks,
        ];
    }
}
