<?php namespace App\Component\Installer;

use Symfony\Component\Process\Process;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class MagentoInstaller extends Installer
{
   
    /**
     * 
     * Add support for Composer 2 | Milestone 2.5
     * https://github.com/magento/magento2/releases
     * 
     * Steps for Instalation
     * ==============================================================
     * # wget https://github.com/magento/magento2/archive/2.4.1.zip
     * # unzip 2.4.1.zip
     * # cd magento2-2.4.1
     * # composer install --prefer-source
     * # bin/magento setup::install
     * ==============================================================
     * Enjoy :)
     * 
     */
    public function install()
    {
        $command    = ['sudo', $this->createInstllScript()]; # ['sudo', 'phpbrew', 'install']
        $process    = new Process( $command, null, ['COMPOSER_HOME' => '/home/vagrant/.config/composer'] );
        
        $this->prepareDirectory();
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
        $filesystem->appendToFile( $installScript, "#!/bin/bash\n" );
        
        $filesystem->appendToFile( $installScript, "wget https://github.com/magento/magento2/archive/ " . $this->project->getBranch() . ".zip -P " .  $this->project->getProjectRoot() . "/ .\n" );
        
        $filesystem->appendToFile( $installScript, "rm -f composer.lock\n" );
        $filesystem->appendToFile( $installScript, "composer install --prefer-source\n" );
        
        // Setup Database
        $filesystem->appendToFile( $installScript, "sed -i 's/root@/root:vagrant@/g' .env\n" );
        
        // Magento Install
        $filesystem->appendToFile( $installScript, "bin/magento setup::install\n" );
    }
    
    private function createInstllWithComposerScript( string  $installScript, array $predefinedParams )
    {
        $filesystem = new Filesystem();
        $filesystem->appendToFile( $installScript, "#!/bin/bash\n" );
        
        
    }
}
