<?php namespace App\Component\Project\PredefinedProject;

class Symfony implements PredefinedProjectInterface
{
    const SOURCE_TYPE   = 'wget';
    const SOURCE_URL    = 'https://github.com/magento/magento2/archive/2.4.1.zip';
    const BRANCH        = '';
    
    
    
    public static function data()
    {
        return [
            'sourceType'    => self::SOURCE_TYPE,
            'sourceUrl'     => self::SOURCE_URL,
            'branch'        => self::BRANCH,
        ];
    }
    
    public function form()
    {
        return 'pages/projects/form_predefined/symfony.html.twig';
    }
    
    public function populate( &$project )
    {
        $project->setSourceType( self::SOURCE_TYPE );
        $project->setRepository( self::SOURCE_URL );
        $project->setBranch( self::BRANCH );
    }
}