<?php namespace App\Component\Project\PredefinedProject;

class Sylius implements PredefinedProjectInterface
{
    const SOURCE_TYPE   = 'git';
    const SOURCE_URL    = 'https://github.com/Sylius/Sylius-Standard.git';
    const BRANCH        = '1.7';
    
    
    
    public static function data()
    {
        return [
            'sourceType' => self::SOURCE_TYPE,
            'sourceUrl'  => self::SOURCE_URL,
            'branch'    => self::BRANCH,
        ];
    }
    
    public function form()
    {
        return 'pages/projects/form_predefined/sylius.html.twig';
    }
    
    public function populate( &$project )
    {
        $project->setSourceType( self::SOURCE_TYPE );
        $project->setRepository( self::SOURCE_URL );
        $project->setBranch( self::BRANCH );
    }
}