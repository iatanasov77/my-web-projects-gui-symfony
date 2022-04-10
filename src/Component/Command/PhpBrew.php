<?php namespace App\Component\Command;

use Symfony\Component\Process\Process;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

use App\Component\Apache\Php;

class PhpBrew
{
    const DIR_PREFIX    = 'php-';
    
    protected $availableVersions;
    protected $installedVersions;
    protected $installationDir;
    protected $installationVersionDir;
    
    protected $currentCommand;
    
    private $phpbrewVariantsDefault;
    private $phpbrewVersionsDir;
    
    public function __construct( array $phpbrewVariantsDefault, string $phpbrewVersionsDir )
    {
        $this->phpbrewVariantsDefault   = $phpbrewVariantsDefault;
        $this->phpbrewVersionsDir       = $phpbrewVersionsDir;
    }
    
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
    
    public function getPhpStatus( $version, $customName = '' )
    {
        $this->_init();
        
        $versionId  = self::DIR_PREFIX . $version;
        if ( ! empty( $customName ) ) {
            $versionId  .= $versionId . '-' . $customName;
        }
        
        if ( array_key_exists( $versionId, $this->installedVersions ) ) {
            return $this->installedVersions[$versionId]['PhpStatus'];
        } else {
            return Php::STATUS_NOT_INSTALLED;
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
    public function install(
        String $version,
        Array $variants = [],
        $extensions = [],
        $displayBuildOutput = false,
        $phpBrewCustomName = '' 
    ): Process {
        // Create the Command
        $options            = [];
        $command            = ['sudo', 'phpbrew', '--debug', 'install'];
        
        if ( $displayBuildOutput ) {
            $options[]  = '--stdout';
        }
        
        if ( ! empty( $phpBrewCustomName ) ) {
            $options[]  =  '--name=php-' . $version . '-' . $phpBrewCustomName;
        }
        
        $currentCommand = array_merge( $command, $options, [$version], $this->variantsDefaults, $variants );
        
        if ( PHP_INT_SIZE === 8 ) {
            $currentCommand[] = '--';
            $currentCommand[] = '--with-libdir=lib64';
        }
        
        $this->setCurrentCommand( $currentCommand );
        $installScript  = ['sudo', $this->createInstallScript( $version, implode( ' ', $currentCommand ), $extensions )];
        
        // Run the Command
        $process    = new Process( $installScript );
        //$process    = new Process( $currentCommand );
        
        // Run The process
        ob_implicit_flush( 1 );
        ob_end_flush();
        
        $process->setTimeout( null );
        $process->start();
        
        return $process;
    }
    
    public function fpmSocket( $phpVersion, $customName = '' )
    {
        $this->_init();
        
        $versionId  = self::DIR_PREFIX . $phpVersion;
        if ( ! empty( $customName ) ) {
            $versionId  .= $versionId . '-' . $customName;
        }
        
        return $this->installationDir . '/' . $versionId . '/var/run/php-fpm.sock';
    }
    
    /**
     * Using bin: /opt/phpbrew/php/php-7.0.33/bin/php --version
     * Using fpm: /opt/phpbrew/php/php-7.0.33/sbin/php-fpm
     * Configs:
     *          /opt/phpbrew/php/php-7.0.33/etc/php.ini
     *          /opt/phpbrew/php/php-7.0.33/etc/php-fpm.conf
     *          /opt/phpbrew/php/php-7.0.33/etc/php-fpm.d/www.conf
     * 
     * @NOTE: NOT USE DIRECTLY TO PREPAIR THE CONFIGS AND RUN FPM SERVICE BECAUSE
     *          MAY BE YOU SHOULD HAVE ROOT PERMISSIONS TO WRITE CONFIG FILES ETC.
     *          USE `bin/phpfpm` instead.
     *          
     *
     * @param string $version
     */
    public function startFpm( String $version, String $customName = '' )
    {
        $dirName        = empty( $customName ) ? self::DIR_PREFIX . $version : self::DIR_PREFIX . $version . '-' . $customName;
        $versionPath    = $this->installationDir . '/' . $dirName;
        if ( ! is_dir( $versionPath ) ) {
            throw new \Exception( 'This Installation path not exists!' );
        }
        
        if ( true ) {
            $this->setupFpm( $version, $customName );
        }
        
        exec( $versionPath . '/sbin/php-fpm' );
    }
    
    public function setupFpm( $version, String $customName )
    {
        $this->installationDir  = $this->phpbrewVersionsDir;
        $dirName                = empty( $customName ) ? self::DIR_PREFIX . $version : self::DIR_PREFIX . $version . '-' . $customName;
        $versionPath            = $this->installationDir . '/' . $dirName;
        if ( ! is_dir( $versionPath ) ) {
            throw new \Exception( 'This Installation path not exists: ' . $versionPath );
        }
        
        switch ( true )
        {
            case file_exists( $versionPath . '/etc/php-fpm.d/www.conf' ):
                $confFile   = $versionPath . '/etc/php-fpm.d/www.conf';
                break;
            case file_exists( $versionPath . '/etc/php-fpm.conf' ):
                $confFile   = $versionPath . '/etc/php-fpm.conf';
                break;
            default:
                throw new \Exception( 'Cannot find PhpFpm config file !!!' );
        }
        $fpmConfig  = file_get_contents( $confFile );
        
        $newConfig  = strtr( $fpmConfig, [
            'nobody'                    => 'apache',
            ';listen.owner'             => 'listen.owner',
            ';listen.group'             => 'listen.group',
            ';listen.mode'              => 'listen.mode',
            //';listen.allowed_clients'   => 'listen.allowed_clients',
        ]);
        
        file_put_contents( '/tmp/fpmConf', $newConfig );
        exec( 'sudo mv -f /tmp/fpmConf ' .  $confFile );
    }
    
    protected function _init()
    {
        if ( ! $this->installedVersions ) {
            $this->installationDir  = $this->phpbrewVersionsDir;
            
            $this->installedVersions    = [];
            
            $handle = is_dir( $this->installationDir ) ? opendir( $this->installationDir ) : null;
            if ( $handle ) {
                
                while ( false !== ( $entry = readdir( $handle ) ) ) {
                    if ( $entry != "." && $entry != ".." ) {
                        $fpmSocket  = $this->installationDir . '/' . $entry . '/var/run/php-fpm.sock';
                        
                        $this->installedVersions[$entry] = [
                            'PhpStatus' => file_exists( $fpmSocket ) ? Php::STATUS_INSTALLED : Php::STATUS_INSTALLED_NOT_STARTED
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
    
    protected function createInstallScript( $phpVersion, $installCommand, $extensions = [] )
    {
        $installScript      = sys_get_temp_dir() . '/vs_phpbrew_install.sh';
        $filesystem         = new Filesystem();
        try {
            if ( $filesystem->exists( $installScript ) ) {
                $filesystem->remove( $installScript );
            }
            
            $filesystem->touch( $installScript );
            $filesystem->chmod( $installScript, 0777 );
            $filesystem->appendToFile( $installScript, "#!/bin/bash\n" );
            
            $filesystem->appendToFile( $installScript, $installCommand );
            
            if ( ! empty( $extensions) ) {
                $filesystem->appendToFile( $installScript, " && source /root/.phpbrew/bashrc" );
                $filesystem->appendToFile( $installScript, " && phpbrew use " . $phpVersion );
                foreach ( $extensions as $ext ) {
                    $filesystem->appendToFile( $installScript, " && phpbrew --debug ext install " . $ext . " -- --with-openssl=/usr/local/opt/openssl" );
                }
            }
            
            return $installScript;
        } catch ( IOExceptionInterface $exception ) {
            echo "An error occurred while creating your directory at " . $exception->getPath();
        }
    }
}
