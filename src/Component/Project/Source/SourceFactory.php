<?php namespace App\Component\Project\Source;

class SourceFactory
{
    const SOURCE_WGET   = 'wget';
    const SOURCE_GIT    = 'git';
    const SOURCE_SVN    = 'svn';
    
    public static function source( $project )
    {
        switch ( $project->getSourceType() ) {
            case 'git':
                $source = new GitSource( $project );
                break;
            case 'install_manual':
                $source = new ManualSource( $project );
                break;
            default:
                $source = null;
        }
        
        return $source;
    }
    
    public static function choices()
    {
        return [
            'Download with `wget`'  => self::SOURCE_WGET,
            'Checkout with `git`'   => self::SOURCE_GIT,
            'Checkout with `svn`'   => self::SOURCE_SVN,
        ];
    }
}
