<?php namespace App\Component\Project\Source;

use App\Component\Project\Source\GitSource;

abstract class Source
{
    private $project;
    
    public function __construct( $project )
    {
        $this->project  = $project;
    }
    
    public static function exists( $project )
    {
        global $bootstrap;
        
        return is_dir( $bootstrap['config']['projects_path'] . $project['project_root'] );
    }
    
    public static function installed( $project )
    {
        //global $bootstrap;
        
        return is_dir( $project->getProjectRoot() );
        
//         $hostsFile  = $project->getProjectRoot() . $bootstrap['config']['installed_hosts'];
//         $hosts      = [];
        
//         if ( file_exists( $hostsFile ) )
//         {
//             $json		= file_get_contents( $hostsFile );
//             $hosts 		= json_decode( $json, true );
//         }
        
//         return isset( $hosts[$project->getId()] );
    }
    
    abstract public function fetch();
}
