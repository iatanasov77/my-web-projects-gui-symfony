<?php namespace App\Component\HttpClient;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Component\Installer\ProjectSourceInterface;

/**
 * ============================================================
 * Manual: https://symfony.com/doc/current/http_client.html
 * ============================================================
 * 
 * =================
 * TEST GIT HUB API
 * =================
 * curl -v "https://api.github.com/repos/PrestaShop/PrestaShop/tags"
 */
class GitHubApi implements ProjectSourceInterface
{
    protected $client;
    
    public function __construct( HttpClientInterface $githubApi )
    {
        //var_dump( $githubApi ); die;
        $this->client   = $githubApi;
    }
    
    public function getGitTags( string $repo ): array
    {
        $gettags    = $this->client->request( 'GET', $repo );
        $tags       = $gettags->toArray();
        \array_walk( $tags,
            function ( &$v ) {
                $v  = $v['name'];
            }
        );
         
        return $tags;
    }
    
    public function getGitBranches( string $repo ): array
    {
        $getbranches    = $this->client->request( 'GET', $repo );
        $branches       = $getbranches->toArray();
        \array_walk( $branches,
            function ( &$v ) {
                $v  = $v['name'];
            }
        );
        
        return $branches;
    }
}