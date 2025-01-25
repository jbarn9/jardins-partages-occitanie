<?php

namespace App\Service;

class TextAnalyzerService
{
    public function getWordCount(string $text): int
    {
        return str_word_count($text);
    }

    public function isTextTooLong(string $text, int $maxWords = 255): bool
    {
        return $this->getWordCount($text) > $maxWords;
    }

    public function getWordStats(string $text): array
    {
        return [
            'total' => $this->getWordCount($text),
            'unique' => count(array_unique(str_word_count($text, 1))),
            'chars' => strlen($text)
        ];
    }

    public function splitTextAtPosition(string $text, int $position): array
    {
        // Si la position est après un espace, on coupe directement
        if ($text[$position] === ' ') {
            return [
                substr($text, 0, $position),
                substr($text, $position + 1)
            ];
        }

        // Chercher le dernier espace avant la position
        $lastSpace = strrpos(substr($text, 0, $position), ' ');

        // Si aucun espace n'est trouvé, chercher le premier espace après
        if ($lastSpace === false) {
            $nextSpace = strpos($text, ' ', $position);
            if ($nextSpace === false) {
                return [$text, '']; // Retourne tout dans la première partie si pas d'espace
            }
            return [
                substr($text, 0, $nextSpace),
                substr($text, $nextSpace + 1)
            ];
        }

        return [
            trim(substr($text, 0, $lastSpace)),
            trim(substr($text, $lastSpace + 1))
        ];
    }

    public function splitTextByWordCount(string $text, int $wordCount): array
    {
        $words = str_word_count($text, 2); // Retourne un tableau avec positions
        $positions = array_keys($words);

        if (count($positions) <= $wordCount) {
            return [$text, ''];
        }

        $splitPosition = $positions[$wordCount];
        return $this->splitTextAtPosition($text, $splitPosition);
    }

    public function splitAtComma(string $text): array
    {
        // Si pas de virgule, retourner le texte entier dans la première partie
        if (!str_contains($text, ',')) {
            return [$text, ''];
        }

        // Trouver la position de la première virgule
        $commaPosition = strpos($text, ',');

        return [
            trim(substr($text, 0, $commaPosition)),  // Partie avant la virgule
            trim(substr($text, $commaPosition + 1))  // Partie après la virgule
        ];
    }

    public function splitAtAllCommas(string $text): array
    {
        // Diviser le texte à chaque virgule et nettoyer les espaces
        return array_map(
            'trim',
            explode(',', $text)
        );
    }

    public function hasComma(string $text): bool
    {
        return str_contains($text, ',');
    }
}
