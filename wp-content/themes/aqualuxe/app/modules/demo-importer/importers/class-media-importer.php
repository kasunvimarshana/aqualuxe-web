<?php

namespace App\Modules\DemoImporter\Importers;

/**
 * Class MediaImporter
 *
 * Imports media files.
 *
 * @package App\Modules\DemoImporter\Importers
 */
class MediaImporter implements ImporterInterface
{
    public function import(array $data)
    {
        // Implementation for importing media
    }

    public function get_name(): string
    {
        return 'media';
    }
}
