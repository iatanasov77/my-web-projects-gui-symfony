<?php namespace App\Component\Project\PredefinedProject;

use App\Component\Installer\ProjectSourceInterface;

abstract class PredefinedProject implements PredefinedProjectInterface
{
    protected $projectSourceService;
    
    public function __construct(
        ProjectSourceInterface $projectSourceService
    ) {
        $this->projectSourceService = $projectSourceService;
    }
}