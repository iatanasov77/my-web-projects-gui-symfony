<?php namespace App\Component\Project\PredefinedProject;

class PrestaShop extends PredefinedProject
{
    const SOURCE_TYPE   = 'git';
    const SOURCE_URL    = 'https://github.com/PrestaShop/PrestaShop.git';
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
        return 'pages/projects/form_predefined/presta_shop.html.twig';
    }
    
    public function parameters()
    {
        $branches   = $this->projectSourceService->getGitBranches( self::SOURCE_URL );
        $tags       = $this->projectSourceService->getGitTags( self::SOURCE_URL );
        
        //return $this->projectSourceService->getGitTags( self::SOURCE_URL );
        return [
            'branches'  => \array_combine( $branches, $branches ),
            'tags'      => \array_combine( $tags, $tags ),
        ];
    }
    
    public function populate( &$project )
    {
        $project->setSourceType( self::SOURCE_TYPE );
        $project->setRepository( self::SOURCE_URL );
        $project->setBranch( self::BRANCH );
    }
}