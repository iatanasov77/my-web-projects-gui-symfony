<?php namespace App\Component\Project\Source;

class SvnSource extends Source
{ 
    public function __construct( $project )
    {
        $this->project  = $project;
    }
    
    public function fetch()
    {
        
    }
}

