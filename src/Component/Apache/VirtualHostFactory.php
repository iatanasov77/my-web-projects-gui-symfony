<?php namespace App\Component\Apache;

use Symfony\Component\DependencyInjection\ContainerInterface;

use App\Entity\ProjectHost;
use App\Component\Project\Host as HostType;
use App\Component\Apache\Php;
use App\Component\Apache\VirtualHost\VirtualHost;
use App\Component\Apache\VirtualHost\VirtualHostLamp;
use App\Component\Apache\VirtualHost\VirtualHostDotNet;
use App\Component\Apache\VirtualHost\VirtualHostJsp;
use App\Component\Apache\VirtualHost\VirtualHostPython;
use App\Component\Apache\VirtualHost\VirtualHostRuby;

class VirtualHostFactory
{
    protected $container;
    
    protected $apacheLogDir;
    
    public function __construct( ContainerInterface $container )
    {
        $this->container    = $container;
        $this->apacheLogDir = '/var/log/httpd/';
        
        //$this->vHostDir     = $this->container->getParameter( 'virtual_hosts_dir' );
      //  $this->_initVhosts();
    }
    
    public function virtualHostFromEntity( ProjectHost $hostEntity ) : ?VirtualHost
    {
        $hostOptions    = $hostEntity->getOptions();
        switch ( $hostEntity->getHostType() ) {
            case HostType::TYPE_LAMP:
                $phpVersion = isset( $hostOptions['phpVersion'] ) ? $hostOptions['phpVersion'] : 'default';
                $vhost      = new VirtualHostLamp([
                    'template'          => $phpVersion == 'default' ? 'simple' : 'simple-fpm',
                    
                    'PhpVersion'        => $phpVersion,
                    'PhpStatus'         => Php::STATUS_INSTALLED,
                    'PhpStatusLabel'    => Php::phpStatus( Php::STATUS_INSTALLED ),
                    
                    'ServerName'        => $hostEntity->getHost(),
                    'DocumentRoot'      => $hostEntity->getDocumentRoot(),
                    'ServerAdmin'       => 'webmaster@' . $hostEntity->getHost(),
                    'LogDir'            => '/var/log/httpd/',
                    'WithSsl'           => $hostEntity->getWithSsl(),
                ]);
                
                break;
            case HostType::TYPE_PYTHON:
                $vhost      = new VirtualHostPython([
                    'template'          => 'wsgi',
                    
                    'projectPath'       => $hostOptions['projectPath'],
                    'venvPath'          => $hostOptions['venvPath'],
                    'user'              => $hostOptions['user'],
                    'group'             => $hostOptions['group'],
                    'processes'         => $hostOptions['processes'],
                    'threads'           => $hostOptions['threads'],
                    'scriptAlias'       => $hostOptions['scriptAlias'],
                    
                    'ServerName'        => $hostEntity->getHost(),
                    'DocumentRoot'      => $hostEntity->getDocumentRoot(),
                    'ServerAdmin'       => 'webmaster@' . $hostEntity->getHost(),
                    'LogDir'            => '/var/log/httpd/',
                    'WithSsl'           => $hostEntity->getWithSsl(),
                ]);
                
                break;
            default:
                throw new \Exception( 'Undefined Host Type: ' . $hostEntity->getHostType() );
        }
        
        return $vhost;
    }
    
    public function virtualHostFromConfig( string $config ) : ?VirtualHost
    {
        $vhostConfig    = $this->_parseVhost( $config );
        
        // may be phpMyAdmin.conf
        if ( ! isset( $vhostConfig['ServerName'] ) ) {
            return null;
        }
        
        $vhostConfig['LogDir']        = $this->apacheLogDir;
        $vhostConfig['template']      = 'simple';
        
        switch ( true ) {
            case isset( $vhostConfig['pythonScriptAlias'] ):
                $vhost   = new VirtualHostPython( $vhostConfig );
                break;
            case isset( $vhostConfig['pythonScriptAlias'] ):
                $vhost   = new VirtualHostPython( $vhostConfig );
                break;
            case isset( $vhostConfig['pythonScriptAlias'] ):
                $vhost   = new VirtualHostPython( $vhostConfig );
                break;
            case isset( $vhostConfig['railsEnv'] ):
                $vhost   = new VirtualHostRuby( $vhostConfig );
                break;
            default:
                $vhost   = new VirtualHostLamp( $vhostConfig );
        }
        
        return $vhost;
    }
    
    protected function lampHost()
    {
        
    }
    
    protected function _parseVhost( $confFile )
    {
        $vhost  = [
            'config'            => $confFile,
            'PhpVersion'        => 'default',
            'PhpStatus'         => Php::STATUS_INSTALLED,
            'PhpStatusLabel'    => Php::phpStatus( Php::STATUS_INSTALLED ),
        ];
        
        $handle = fopen( $confFile, 'r' ) or die( 'Virtual host conf cannot be opened ..' );
        while( ! feof( $handle ) ) {
            $line = fgets( $handle );
            $line = trim( $line );
            
            $this->_parseLine( $line, $vhost );
            
        }
        fclose( $handle );
        
        return $vhost;
    }
    
    protected function _parseLine( $line, &$vhost )
    {
        $tokens = explode( ' ',$line );
        
        if( ! empty( $tokens ) ) {
            
            switch ( strtolower( $tokens[0] ) ) {
                case 'serveradmin':
                    $vhost['ServerAdmin'] = $tokens[1];
                    break;
                case 'documentroot':
                    $vhost['DocumentRoot'] = trim( $tokens[1], '"' );
                    break;
                case 'servername':
                    $vhost['ServerName'] = $tokens[1];
                    break;
                case 'serveralias':
                    $vhost['ServerAlias'] = $tokens[1];
                    break;
            }
            
            if( $tokens[0] == '<VirtualHost' ) {
                $vhost['WithSsl']   = $tokens[1] == '*:443' ? true : false;
            }
            
            if( $tokens[0] == '<Proxy' ) {
                if ( isset( $tokens[1] ) ) {
                    $proxyParts                     = explode( '/', $tokens[1] );
                    $phpVersionParts                = explode( '-', substr( $proxyParts[4], 4 ) );
                    
                    $vhost['PhpVersion']            = $phpVersionParts[0];
                    $vhost['PhpVersionCustomName']  = isset( $phpVersionParts[1] ) ? $phpVersionParts[1] : '';
                    
                    $phpBrew                        = $this->container->get( 'vs_app.php_brew' );
                    $phpStatus                      = $phpBrew->getPhpStatus( $vhost['PhpVersion'] );
                    
                    $vhost['PhpStatus']             = $phpStatus;
                    $vhost['PhpStatusLabel']        = Php::phpStatus( $phpStatus );
                }
            }
            
            if( $tokens[0] == 'WSGIDaemonProcess' ) {
                $this->_parseWsgiProcessDaemon( $tokens, $vhost );
            }
            
            if( $tokens[0] == 'WSGIScriptAlias' ) {
                $vhost['pythonScriptAlias']  = $tokens[2];
            }
            
            if( $tokens[0] == 'RailsEnv' ) {
                $vhost['railsEnv']  = $tokens[1];
            }
            
        } else {
            echo "Puked...";
        }
    }
    
    protected function _parseWsgiProcessDaemon( $tokens, &$vhost )
    {
        $vhost['pythonProcessName']  = $tokens[1];
        
        foreach ( $tokens as $t ) {
            switch ( true ) {
                case str_starts_with ( $t , 'user' ):
                    $vhost['pythonProcessUser']  = trim( substr( $t, strpos( $t, "=" ) + 1 ) );
                    break;
                case str_starts_with ( $t , 'group' ):
                    $vhost['pythonProcessGroup']  = trim( substr( $t, strpos( $t, "=" ) + 1 ) );
                    break;
                case str_starts_with ( $t , 'processes' ):
                    $vhost['pythonProcessProcesses']  = trim( substr( $t, strpos( $t, "=" ) + 1 ) );
                    break;
                case str_starts_with ( $t , 'threads' ):
                    $vhost['pythonProcessThreads']  = trim( substr( $t, strpos( $t, "=" ) + 1 ) );
                    break;
                case str_starts_with ( $t , 'python-home' ):
                    $vhost['pythonProcessPythonHome']  = trim( substr( $t, strpos( $t, "=" ) + 1 ) );
                    break;
                case str_starts_with ( $t , 'python-path' ):
                    $vhost['pythonProcessPythonPath']  = trim( substr( $t, strpos( $t, "=" ) + 1 ) );
                    break;
            }
        }
    }
}
