<?php namespace App\Component\Installer;

use Symfony\Component\Process\Process;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class PrestaShopInstaller extends Installer
{
    /**
     * Steps for Instalation
     * =======================   
     * # composer create-project prestashop/prestashop <dir_to_install>
     * # cd <dir_to_install>
     * # php install-dev/index_cli.php --domain=prestashop-1.7.lh --db_name=PrestaShop_1.7 --db_user=root --db_password=vagrant --db_create=1
     * ========================
     * Enjoy :)
     */
    public function install()
    {
        $command    = ['sudo', $this->createInstllScript()]; # ['sudo', 'phpbrew', 'install']
        $process    = new Process( $command, null, [
            'COMPOSER_HOME' => '/home/vagrant/.config/composer',
        ]);
        
        /*
        if ( $installType != 'composer' ) {
            $this->prepareDirectory();
        }
        */
        
        $process->setWorkingDirectory( $this->project->getProjectRoot() );
        $process->setTimeout( null );
        
        // Run The process
        ob_implicit_flush( 1 );
        ob_end_flush();
        
        $process->start();
        
        return $process;
    }
    
    protected function createInstllScript()
    {
        $predefinedParams   = $this->project->getPredefinedTypeParams();
        $predefinedParams   = is_array( $predefinedParams ) ? $predefinedParams : [];
        
        $installScript      = sys_get_temp_dir() . '/' . Installer::INSTALL_SCRIPT;
        $filesystem         = new Filesystem();
        try {
            if ( $filesystem->exists( $installScript ) ) {
                $filesystem->remove( $installScript );
            }
            
            $filesystem->touch( $installScript );
            $filesystem->chmod( $installScript, 0777 );
            
            //$this->createInstllFromSourceScript( $installScript, $predefinedParams );
            $this->createInstllWithComposerScript( $installScript, $predefinedParams );
            
            return $installScript;
        } catch ( IOExceptionInterface $exception ) {
            echo "An error occurred while creating your directory at " . $exception->getPath();
        }
    }
    
    private function createInstllFromSourceScript( string  $installScript, array $predefinedParams )
    {
        $filesystem = new Filesystem();
        $filesystem->appendToFile( $installScript, "#!/bin/bash\n\n" );
    }
    
    private function createInstllWithComposerScript( string  $installScript, array $predefinedParams )
    {
        $filesystem = new Filesystem();
        $filesystem->appendToFile( $installScript, "#!/bin/bash\n\n" );
        
        $cmdComposer    = "/usr/local/bin/composer create-project prestashop/prestashop={$predefinedParams['version']} {$predefinedParams['version']}";
        $filesystem->appendToFile( $installScript, $cmdComposer );
    }
}
