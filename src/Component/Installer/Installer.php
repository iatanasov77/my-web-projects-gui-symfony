<?php namespace App\Component\Installer;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

use App\Entity\Project;

class Installer
{
    const INSTALL_SCRIPT    = 'vs_install_project.sh';
    
    const INSTALLED_HOSTS   = '/storage/installed_hosts.json';
    const COMPOSER_BIN      = '/usr/bin/composer';
    
    protected $project;
    
    public function __construct( Project $project )
    {
        $this->project  = $project;
    }
    
    public function install()
    {
        $this->setup(); 
    }
    
    public function setup()
    {
        $projectRoot	= $this->installPath . DIRECTORY_SEPARATOR . $this->project['project_root'];
        $documentRoot	= $this->installPath . DIRECTORY_SEPARATOR . $this->project['document_root'];
        
        echo "Checkout project source...   <br>";
        $repo = $this->checkout( $projectRoot );
        echo "Done!<br><br>";
        
        echo "Create Apache Virtual Host...   <br>";
        $this->createApacheVirtualHost( $documentRoot );
        echo "Done!<br><br>";
        
        echo "Run Composer...   <br>";
        $this->runComposer( $projectRoot );
        echo "Done!<br><br>";
        
        $this->registerInstalled( $this->project['dev_url'], $documentRoot );
    }
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    protected function prepareDirectory()
    {
        $filesystem     = new Filesystem();
        $projectRoot    = $this->project->getProjectRoot();
        
        try {
            if ( $filesystem->exists( $projectRoot ) ) {
                $filesystem->remove( $projectRoot );
            }
            
            $filesystem->mkdir( $projectRoot, 0777 );
            // changes the owner of the video directory recursively
            $filesystem->chown( $projectRoot, 'vagrant', true );
            // changes the group of the video directory recursively
            $filesystem->chgrp( $projectRoot, 'vagrant', true );
        } catch ( IOExceptionInterface $exception ) {
            echo "An error occurred while creating your directory at " . $exception->getPath();
        }
    }
    
    /**
     * @brief  Checkout source code from repository
     *
     * @param  string $localDir
     * @param  string $branch
     */
    protected function checkout( $localDir, $branch = null )
    {
        $repo  = null;
        
        if( ! file_exists( $localDir ) )
        {
            mkdir( $localDir, 2775, true );
        }
        chdir( $localDir );
        
        // Empty directory
        if( ! ( new \FilesystemIterator( $localDir ) )->valid() )
        {
            if ( $this->project['git_username'] && $this->project['git_password'] )
            {
                $gitUrl	= sprintf(
                    "https://%s:%s@%s",
                    $this->project['git_username'],
                    $this->project['git_password'],
                    $this->project['git_url']
                    );
            }
            else
            {
                $gitUrl	= sprintf(
                    "https://%s",
                    $this->project['git_url']
                    );
            }
            
            $maxNestingLevel = ini_get( 'xdebug.max_nesting_level' );
            if ( $maxNestingLevel )
                ini_set( 'xdebug.max_nesting_level', 9999 );
                
                $git  = new Git();
                $git->addOutputListener( new OutputListener() );
                $repo = $git->cloneRepository( $gitUrl, $localDir);
                
                if ( $maxNestingLevel )
                    ini_set( 'xdebug.max_nesting_level', $maxNestingLevel );
        }
        
        if ( $branch )
        {
            $prBranchCheckout = new Process( "git checkout $branch" );
            $prBranchCheckout->run();
        }
        
        return $repo;
    }
    
    protected function createApacheVirtualHost( $documentRoot )
    {
        if ( ! HttpHost::exists( 'http://' . $this->project['dev_url'] ) )
        {
            HttpHost::create( $this->project['dev_url'], $documentRoot, $_SERVER['SERVER_ADDR'] );
        }
    }
    
    protected function runComposer( $cwd )
    {
        if ( file_exists( $cwd . '/vendor/autoload.php' ) )
            return;
            
            $maxNestingLevel = ini_get( 'xdebug.max_nesting_level' );
            if ( $maxNestingLevel )
                ini_set( 'xdebug.max_nesting_level', 9999 );
                
                $composerProcesss = new Process( self::COMPOSER_BIN + ' install', $cwd, ['HOME' => '/home/vagrant'] );
                $composerProcesss->run();
                foreach( $composerProcesss as $output )
                {
                    echo "<br />" . $output;
                    echo '<script> $( \'html, body\' ).animate( { scrollTop: $(document).height() }, \'slow\' ); </script>';
                    
                    ob_flush();
                    flush();
                }
                
                if ( $maxNestingLevel )
                    ini_set( 'xdebug.max_nesting_level', $maxNestingLevel );
    }
    
    protected function registerInstalled( $hostName, $documentRoot )
    {
        $hosts		= [];
        $jsonFile	= APP_ROOT . self::INSTALLED_HOSTS;
        if ( file_exists( $jsonFile ) )
        {
            $json		= file_get_contents( $jsonFile );
            $hosts 		= json_decode( $json, true );
        }
        
        $hosts[$this->project['id']]	= [
            'hostName'		=> $hostName,
            'documentRoot'	=> $documentRoot
        ];
        
        file_put_contents( $jsonFile, json_encode( $hosts ) );
    }
}
