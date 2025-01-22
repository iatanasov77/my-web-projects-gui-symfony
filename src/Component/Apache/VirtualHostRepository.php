<?php namespace App\Component\Apache;

use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Component\Apache\Php;
use App\Component\Apache\Config as ApacheConfig;

use App\Component\Apache\VirtualHost\VirtualHostLamp;

class VirtualHostRepository
{
    protected $container;
    
    protected $vHostDir;
    
    protected $vhosts;
    
    protected $vhostConfigs;    // to can get config by host name
    
    protected $factory;
    
    public function __construct( ContainerInterface $container )
    {
        $this->container    = $container;
        $this->factory      = $container->get( 'vs_app.apache_virtual_host_factory' );
        $this->vHostDir     = $this->container->getParameter( 'virtual_hosts_dir' );
        
        $this->_initVhosts();
    }
    
    public function virtualHosts()
    {
        return $this->vhosts;
    }
    
    public function getVirtualHostByConfig( $config )
    {
        if ( isset( $this->vhosts[$config] ) ) {
            return $this->vhosts[$config];
        }
        
        return null;
    }
    
    public function getVirtualHostByHost( $host ) : ?VirtualHostLamp
    {
        if ( isset( $this->vhostConfigs[$host] ) ) {
            $config = $this->vhostConfigs[$host];
            if ( isset( $this->vhosts[$config] ) ) {
                return $this->vhosts[$config];
            }
        }
        
        return null;
    }
    
    public function getVirtualHostConfig( $host ) : ?string
    {
        if ( isset( $this->vhostConfigs[$host] ) ) {
            return $this->vhostConfigs[$host];
        }
        
        return null;
    }
    
    public function generateVirtualHost( $vhost, $template = 'simple' )
    {
        $vhostConfig    = $this->createVirtualHostConfig( $vhost, $template );
        file_put_contents( '/tmp/vhost.conf', htmlspecialchars_decode( $vhostConfig ) );
        
        if ( isset( $this->vhostConfigs[$vhost->getHost()] ) ) {
            $vhostConfFile = $this->vhostConfigs[$vhost->getHost()];
        } else {
            $vhostConfFile	= '/etc/httpd/conf.d/' . $vhost->getHost() . '.conf';
        }
        
        exec( 'sudo mv -f /tmp/vhost.conf ' . $vhostConfFile ); // Write VHost file
        $this->container->get( 'vs_app.apache_service' )->reload(); // Reload Apache
    }
    
    public function setVirtualHost( $host, $phpVersion = 'default' )
    {
        $apache     = $this->container->get( 'vs_app.apache_service' );
        $vhost      = $this->getVirtualHostByHost( $host );
        $template   = $phpVersion == 'default' ? 'simple' : 'simple-fpm';
        
        $vhost->setPhpVersion( $phpVersion );
        $vhostConfig    = $this->createVirtualHostConfig( $vhost, $template );
        
        file_put_contents( '/tmp/vhost.conf', htmlspecialchars_decode( $vhostConfig ) );
        
        exec( 'sudo mv -f /tmp/vhost.conf ' . $this->vhostConfigs[$host] ); // Write VHost file
        $apache->reload(); // Reload Apache
    }
    
    public function createVirtualHostConfig( $vhost, $template )
    {
        $twig           = $this->container->get( 'twig' );
        $twigVars       = $vhost->twigVars();
        if ( $vhost instanceof VirtualHostLamp ) {
            $phpBrew        = $this->container->get( 'vs_app.php_brew' );
            $twigVars['fpmSocket']  = $phpBrew->fpmSocket( $vhost->getPhpVersion() );
        }
        
        if ( file_exists ( ApacheConfig::SSLCERT_VAGRANT_CRT ) && file_exists ( ApacheConfig::SSLCERT_VAGRANT_KEY ) ) {
            $twigVars['sslCertificate']     = ApacheConfig::SSLCERT_VAGRANT_CRT;
            $twigVars['sslCertificateKey']  =  ApacheConfig::SSLCERT_VAGRANT_KEY;
        } else {
            $twigVars['sslCertificate']     = ApacheConfig::SSLCERT_MYPROJECTS_CRT;
            $twigVars['sslCertificateKey']  = ApacheConfig::SSLCERT_MYPROJECTS_KEY;
        }
        
        $vhostConfig    = $twig->render( 'mkvhost/' . $template . '.twig', $twigVars );
        
        if ( $vhost->getWithSsl() )
        {
            $vhostConfig  .= "\n\n" . $twig->render( 'mkvhost/' . $template . '-ssl.twig', $twigVars );
        }
        
        return $vhostConfig;
    }
    
    public function removeVirtualHost( $host ) {
        if ( isset( $this->vhostConfigs[$host] ) ) {
            exec( 'sudo rm -f ' . $this->vhostConfigs[$host] );
            
            unset( $this->vhosts[$this->vhostConfigs[$host]] );
            unset( $this->vhostConfigs[$host] );
        }
    }
    
    protected function _initVhosts()
    {
        if ( ! is_dir( $this->vHostDir ) ) {
            throw new \Exception( 'Apache virtual host dir "' . $this->vHostDir . '" not exists!' );
        }
        
        $handle = opendir( $this->vHostDir );
        if ( ! $handle ) {
            throw new \Exception( 'Apache virtual host dir "' . $this->vHostDir . '" cannot be opened!' );
        }
        
        $this->vhosts   = [];
        while ( false !== ( $entry = readdir( $handle ) ) ) {
            if ( $entry != "." && $entry != ".." ) {
                $config                                 = $this->vHostDir . '/' . $entry;
                $vhost                                  = $this->factory->virtualHostFromConfig( $config );
           
                // Vagrant create double configs for https sites
                if ( ! $vhost || isset( $this->vhostConfigs[$vhost->getHost()] ) ) {
                    continue;
                }
                
                $this->vhosts[$config]                  = $vhost;     
                $this->vhostConfigs[$vhost->getHost()]  = $config;
            }
        }
        closedir( $handle );
    }
}
