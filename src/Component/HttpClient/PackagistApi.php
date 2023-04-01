<?php namespace App\Component\HttpClient;

use Packagist\Api\Client;
use App\Component\Installer\ProjectSourceInterface;

class PackagistApi implements ProjectSourceInterface
{
    protected $client;
    
    public function __construct()
    {
        $this->client   = new Client();
    }
    
    public function getGitTags( string $repo ): array
    {
        return [];
    }
    
    public function getGitBranches( string $repo ): array
    {
        return [];
    }
    
    public function getVersions( string $package ): array
    {
        // Example: $package = 'sylius/sylius'
        $packages   = $this->client->getComposer( $package );
        $package    = $packages[$package];
        $versions   = $package->getVersions();
        
        return $versions;
    }
}
