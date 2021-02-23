<?php namespace App\Component\Project\Source;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ManualSource extends Source
{
    public function __construct( $project )
    {
        $this->project   = $project;
    }
    
    public function fetch()
    {
        echo $this->project->getInstallManual();
    }
}
