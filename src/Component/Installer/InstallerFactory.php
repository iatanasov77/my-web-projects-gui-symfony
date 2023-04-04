<?php namespace App\Component\Installer;

use App\Component\Project\PredefinedProject;
use App\Entity\Project;

class InstallerFactory {
    
    public static function installer( $predefinedType, Project $project )
    {
        switch ( $predefinedType ) {
            case PredefinedProject::PRESTA_SHOP:
                $installer = new PrestaShopInstaller( $project );
                break;
            case PredefinedProject::SYLIUS:
                $installer = new SyliusInstaller( $project );
                break;
            case PredefinedProject::MAGENTO:
                $installer = new MagentoInstaller( $project );
                break;
            case PredefinedProject::SYMFONY:
                $installer = new SymfonyInstaller( $project );
                break;
            case PredefinedProject::LARAVEL:
                $installer = new LaravelInstaller( $project );
                break;
            case PredefinedProject::DJANGO:
                $installer = new DjangoInstaller( $project );
                break;
            default:
                throw new \Exception( 'Not Implemented !!!' );
        }
        
        return $installer;
    }
}
