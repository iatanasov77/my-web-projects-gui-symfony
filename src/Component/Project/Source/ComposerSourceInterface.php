<?php namespace App\Component\Project\Source;

/**
 * https://packagist.org/packages/knplabs/packagist-api
 * https://packagist.org/apidoc
 */
interface ComposerSourceInterface
{
    public function getVersions( string $package ): array;
}