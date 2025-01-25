<?php

namespace App\Service;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class Utils
{
    /**
     * Clean the input
     * @param string $value
     * @return null|string
     */
    public static function cleanInputStatic(string $value): ?string
    {
        return htmlspecialchars(strip_tags(trim($value)));
    }
    /**
     * Decode the value
     * @param string $value
     * @return null|string
     */
    public static function decode($value): ?string
    {
        return htmlspecialchars_decode(html_entity_decode($value, true));
    }
    /** 
     * @param string $date 
     * @param string $format 
     * @return bool 
     * Check if the date is valid
     */
    public static function isValid($date, $format = 'd/m/Y'): bool
    {
        $dt = \DateTime::createFromFormat($format, $date);
        return $dt && $dt->format($format) === $date;
    }
    /**
     * slugify the value
     * @param string $value
     * @return null|string
     */
    public static function slugify($value): ?string
    {
        return str_replace([" ", "'"], "-", $value);
    }

    /**
     * Hash the value
     * @param string $value
     * @return null|string
     */
    public static function hash(String $value): ?string
    {
        // Hash the value
        return password_hash($value, PASSWORD_DEFAULT);
    }

    /**
     * Verify the value
     * @param string $value
     * @param string $hashedValue
     * @return bool
     */
    public static function verify(String $value, String $hashedValue): bool
    {
        return password_verify($value, $hashedValue);
    }

    /**
     * Get the files in the folder
     * @param array $folderFiles
     * @param array $results
     * @return array
     */
    public static function getFilesInFolder(array $folderFiles, array &$results, $service): array
    {
        foreach ($results as $file) {
            if ($file->getMimeType() == 'application/vnd.google-apps.folder') {
                $optParams['q'] = ["'" . $file->getId() . "' in parents", "trashed = false"];
                $folderFiles = $service->files->listFiles($optParams)->getFiles();
                $results = array_merge($results, self::getFilesInFolder($folderFiles, $results, $service));
            }
        }
        return $results;
    }
}
