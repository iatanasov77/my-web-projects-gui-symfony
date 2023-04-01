<?php namespace App\Component\Project\Source;

interface ComposerSourceInterface
{
    public function getVersions( string $package ): array;
}