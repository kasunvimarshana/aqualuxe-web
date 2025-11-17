<?php
namespace AQLX\DemoImporter\Providers;

if (!defined('ABSPATH')) { exit; }

class MediaProvider {
    /**
     * Return an associative array: [ 'url' => string, 'source' => string, 'license' => string ]
     */
    public static function fetch(string $provider, string $query): array {
        switch ($provider) {
            case 'wikimedia':
                return self::wikimedia($query);
            case 'unsplash': // stub (requires API key)
            case 'pexels':   // stub (requires API key)
            case 'pixabay':  // stub (requires API key)
            default:
                return self::wikimedia($query);
        }
    }

    private static function wikimedia(string $query): array {
        // Return a safe, random-ish image URL from Wikimedia Commons (no API key)
        $images = [
            [ 'url' => 'https://upload.wikimedia.org/wikipedia/commons/1/16/Aquarium.jpg', 'source' => 'https://commons.wikimedia.org/wiki/File:Aquarium.jpg', 'license' => 'CC BY-SA' ],
            [ 'url' => 'https://upload.wikimedia.org/wikipedia/commons/0/0f/Coral_reef.jpg', 'source' => 'https://commons.wikimedia.org/wiki/File:Coral_reef.jpg', 'license' => 'CC BY-SA' ],
            [ 'url' => 'https://upload.wikimedia.org/wikipedia/commons/6/6e/Aquarium_fish.jpg', 'source' => 'https://commons.wikimedia.org/wiki/File:Aquarium_fish.jpg', 'license' => 'CC BY-SA' ],
            [ 'url' => 'https://upload.wikimedia.org/wikipedia/commons/e/ed/Tropical_fish.jpg', 'source' => 'https://commons.wikimedia.org/wiki/File:Tropical_fish.jpg', 'license' => 'CC BY-SA' ],
            [ 'url' => 'https://upload.wikimedia.org/wikipedia/commons/f/f1/Blue_tang.jpg', 'source' => 'https://commons.wikimedia.org/wiki/File:Blue_tang.jpg', 'license' => 'CC BY-SA' ],
        ];
        return $images[array_rand($images)];
    }
}
