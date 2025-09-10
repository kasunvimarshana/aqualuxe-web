<?php

namespace App\Modules\DemoImporter\Importers;

/**
 * Interface ImporterInterface
 *
 * Defines the contract for all data importers.
 *
 * @package App\Modules\DemoImporter\Importers
 */
interface ImporterInterface
{
    /**
     * Import data.
     *
     * @param array $data
     * @return mixed
     */
    public function import(array $data);

    /**
     * Get the name of the importer.
     *
     * @return string
     */
    public function get_name(): string;
}
