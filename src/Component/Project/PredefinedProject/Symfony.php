<?php namespace App\Component\Project\PredefinedProject;

class Symfony extends PredefinedProject
{
    const SOURCE_TYPE   = 'wget';
    const SOURCE_URL    = 'https://github.com/magento/magento2/archive/2.4.1.zip';
    const BRANCH        = '';
    
    const PACKAGE       = 'symfony/skeleton';
    
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
        //return 'pages/projects/form_predefined/symfony.html.twig';
        return 'pages/projects/form_predefined/composer_project.html.twig';
    }
    
    public function parameters()
    {
        $branches   = $this->projectSourceService->getGitBranches( self::SOURCE_URL );
        $tags       = $this->projectSourceService->getGitTags( self::SOURCE_URL );
        
        $versions   = $this->projectSourceService->getVersions( self::PACKAGE );
        //var_dump( $versions ); die;
        \array_walk( $versions,
            function ( &$v ) {
                $v  = $v->getVersion();
            }
        );
        
        //return $this->projectSourceService->getGitTags( self::SOURCE_URL );
        return [
            'branches'  => \array_combine( $branches, $branches ),
            'tags'      => \array_combine( $tags, $tags ),
            'versions'  => \array_combine( $versions, $versions ),
        ];
    }
    
    public function populate( &$project )
    {
        $project->setSourceType( self::SOURCE_TYPE );
        $project->setRepository( self::SOURCE_URL );
        $project->setBranch( self::BRANCH );
    }
}