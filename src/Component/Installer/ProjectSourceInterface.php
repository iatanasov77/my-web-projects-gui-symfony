<?php namespace App\Component\Installer;

interface ProjectSourceInterface
{
    public function getGitTags( string $repo ): array;
    public function getGitBranches( string $repo ): array;
}