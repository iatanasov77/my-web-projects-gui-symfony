<?php namespace App\Component\Project\PredefinedProject;

interface PredefinedProjectInterface
{
    public function form();
    public function populate( &$project );
}