<?php namespace App\Component\Command;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Python
{
    /**
     * @var ContainerInterface $container
     */
    private $container;
    
    protected $installationDir;
    protected $virtualEnviironments;
    
    public function __construct( ContainerInterface $container )
    {
        $this->container    = $container;
    }
    
    public function getVirtualEnvironments()
    {
        $this->_init();
        
        return $this->virtualEnviironments;
    }
    
    public function virtualenv( $host )
    {
        $hostPath   = '/var/www/' . $host;
        if ( ! file_exists( $hostPath ) ) {
            exec( 'sudo mkdir ' . $hostPath );
            exec( 'sudo chown -R apache:root ' . $hostPath );
            exec( 'sudo chmod -R 0777 ' . $hostPath );
        }
            
        exec( 'virtualenv --python=/usr/bin/python3 ' . $hostPath . '/venv' );
    }
        
    public function createDjangoApplication( $host, $projectPath, $applicationName )
    {
        $venvPath   = '/var/www/' . $host . '/venv';
        
        exec( $venvPath . '/bin/pip3 install Django' );
        exec( 'cd ' . $projectPath . ' && ' . $venvPath . '/bin/django-admin startproject ' . $applicationName );
    }
    
    protected function _init()
    {
        if ( ! $this->virtualEnviironments ) {
            $this->installationDir  = $this->container->getParameter( 'python_virtual_environements_dir' );
            
            $this->virtualEnviironments    = [];
            
            $handle = is_dir( $this->installationDir ) ? opendir( $this->installationDir ) : null;
            if ( $handle ) {
                
                while ( false !== ( $entry = readdir( $handle ) ) ) {
                    if ( $entry != "." && $entry != ".." ) {
                        $this->virtualEnviironments[$entry] = [
                            'Status' => 'INSTALLED'
                        ];
                    }
                }
                
                closedir( $handle );
            }
        }
    }
}
