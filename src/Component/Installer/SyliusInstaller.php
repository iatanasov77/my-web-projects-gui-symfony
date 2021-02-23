<?php namespace App\Component\Installer;

use Symfony\Component\Process\Process;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class SyliusInstaller extends Installer
{
    
    
    /**
     * Steps for Instalation
     * =======================
     * # composer create-project sylius/sylius-standard MyFirstShop
     * # bin/console sylius:install
     * # yarn install
     * # yarn build
     * =======================
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
            $filesystem->appendToFile( $installScript, "#!/bin/bash\n" );
        
            $filesystem->appendToFile( $installScript, "git clone --branch " . $this->project->getBranch() . " " . $this->project->getRepository()     . " .\n" );
            
            $filesystem->appendToFile( $installScript, "rm -f composer.lock\n" );
            $filesystem->appendToFile( $installScript, "composer install --prefer-source\n" );
            
            
            /*
             * bin/console sylius:install
             * ===========================
             * 1. Checking System requirements
             * 2. Creating Sylus Database
             * 3. Shop configuration    (Currency, Language, Administrator account)
             * 4. Installing assets
             */
            
            // Setup Database
            $filesystem->appendToFile( $installScript, "sed -i 's/root@/root:vagrant@/g' .env\n" );
            $filesystem->appendToFile( $installScript, "bin/console doctrine:database:create\n" );
            $filesystem->appendToFile( $installScript, "bin/console --no-interaction doctrine:migrations:migrate\n" );
            
            if ( isset( $predefinedParams['installSampleData'] ) ) {
                // bin/console sylius:install:sample-data
                $filesystem->appendToFile( $installScript, "bin/console --no-interaction sylius:fixtures:load\n" );
            }
            
            // Shop configuration
            // Default admin user/password: sylius/sylius
            $filesystem->appendToFile( $installScript, "bin/console --no-interaction sylius:install:setup\n" );
            
            // Installing Assets
            // Require Python 2 to be installed on system
            $filesystem->appendToFile( $installScript, "bin/console sylius:install:assets\n" );
            $filesystem->appendToFile( $installScript, "yarn install --no-bin-links\n" );
            $filesystem->appendToFile( $installScript, "yarn build\n" );
            
            return $installScript;
        } catch ( IOExceptionInterface $exception ) {
            echo "An error occurred while creating your directory at " . $exception->getPath();
        }
    }
}
