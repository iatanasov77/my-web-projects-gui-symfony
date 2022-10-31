<?php namespace App\Component\Project\PredefinedProject;

use App\Component\Installer\ProjectSource;

abstract class PredefinedProject implements PredefinedProjectInterface
{
    protected $projectSourceService;
    
    public function __construct( ProjectSource $projectSourceService )
    {
        $this->projectSourceService = $projectSourceService;
    }
}