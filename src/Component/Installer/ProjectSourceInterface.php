<?php namespace App\Component\Installer;

use App\Component\Project\Source\GitSourceInterface;
use App\Component\Project\Source\ComposerSourceInterface;

interface ProjectSourceInterface extends GitSourceInterface, ComposerSourceInterface
{
    
}