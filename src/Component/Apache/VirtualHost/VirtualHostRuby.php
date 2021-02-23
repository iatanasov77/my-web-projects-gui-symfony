<?php namespace App\Component\Apache\VirtualHost;

use App\Component\Project\Host as HostType;

class VirtualHostRuby extends VirtualHost
{
    
    public function __construct( $vhostConfig )
    {
        parent::__construct( $vhostConfig );
        
        
    }
    
    public function type()
    {
        return HostType::TYPE_RUBY;
    }
}
