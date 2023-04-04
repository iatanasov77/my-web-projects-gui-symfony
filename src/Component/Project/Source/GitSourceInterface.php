<?php namespace App\Component\Project\Source;

interface GitSourceInterface
{
    public function getGitTags( string $repo ): array;
    public function getGitBranches( string $repo ): array;
}