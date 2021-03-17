<?php namespace App\Component\Command;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Process\Process;

class Tomcat implements ContainerAwareInterface
{
    use ContainerAwareTrait;
    
    protected $availableVersions;
    protected $installedVersions;
    protected $installationDir;
    protected $installationVersionDir;
    
    protected $currentCommand;
    
    public function getInstalledVersions()
    {
        $this->_init();
        
        return $this->installedVersions;
    }
    
    public function getAvailableVersions()
    {
        $this->_init();
        
        return $this->availableVersions;
    }
    
    public function getStatus( $version, $customName = '' )
    {
        $this->_init();
        
        $versionId  = $version;
        if ( ! empty( $customName ) ) {
            $versionId  .= $versionId . '-' . $customName;
        }
        
        if ( array_key_exists( $versionId, $this->installedVersions ) ) {
            return $this->installedVersions[$versionId]['Status'];
        } else {
            return 'NOT_INSTALLED';
        }
    }
    
    public function getCurrentCommand()
    {
        return $this->currentCommand;
    }
    
    public function setCurrentCommand( $currentCommand )
    {
        $this->currentCommand   = implode( ' ', $currentCommand );
    }
    
    /**
     * See Build Log:  tail -F '/opt/phpbrew/build/php-7.4.1/build.log'
     */
    public function install( String $version, Array $variants = [], $displayBuildOutput = false, $phpBrewCustomName = '' ): Process
    {
        
    }
    
    protected function _init()
    {
        if ( ! $this->installedVersions ) {
            $this->installationDir  = $this->container->getParameter( 'tomcat_instances_dir' );
            
            $this->installedVersions    = [];
            
            $handle = is_dir( $this->installationDir ) ? opendir( $this->installationDir ) : null;
            if ( $handle ) {
                
                while ( false !== ( $entry = readdir( $handle ) ) ) {
                    if ( $entry != "." && $entry != ".." ) { 
                        $this->installedVersions[$entry] = [
                            'Status' => 'INSTALLED'
                        ];
                    }
                }
                
                closedir( $handle );
            }
        }
        
        if ( ! $this->availableVersions ) {
            $json   = file_get_contents( "/opt/phpbrew/php-releases.json" );
            $this->availableVersions    = json_decode( $json, true );
        }
    }
}
