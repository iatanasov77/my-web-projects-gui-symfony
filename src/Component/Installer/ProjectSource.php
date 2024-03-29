<?php namespace App\Component\Installer;

class ProjectSource implements ProjectSourceInterface
{
    public function getGitTags( string $repo ): array
    {
        $gettags = shell_exec( "git ls-remote -t -h {$repo} refs/tags/*" );
        
        $tags    = \explode( "\n", $gettags );
        \array_walk( $tags,
            function ( &$v ) {
                $locationOfTag  = \strpos( $v, 'refs/tags/' );
                $v              = \substr( $v, $locationOfTag + 10 );
                $v              = \rtrim( $v, '^{}' );
            }
        );
        
        //return \array_reverse( \array_unique( $tags ), true );
        return \array_reverse( \array_unique( $tags ) );
    }
    
    public function getGitBranches( string $repo ): array
    {
        $getbranches    = \shell_exec( "git ls-remote --heads -h {$repo} refs/heads/*" );
        
        $branches       = \explode( "\n", $getbranches );
        \array_walk( $branches,
            function ( &$v ) {
                $locationOfBranch   = \strpos( $v, 'refs/heads/' );
                $v                  = \substr( $v, $locationOfBranch + 11 );
            }
        );
        
        return \array_unique( $branches );
    }
    
    public function getVersions( string $package ): array
    {
        return [];
    }
}
