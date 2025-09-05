<?php
namespace AquaLuxe\Core\Contracts;

interface Module {
    /** Boot module hooks, CPTs, assets, etc. */
    public function boot(): void;
}
