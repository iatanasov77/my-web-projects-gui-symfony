<?php namespace App\Component\Installer;

use App\Component\Project\PredefinedProject;
use App\Entity\Project;

class InstallerFactory {
    
    public static function installer( $predefinedType, Project $project )
    {
        switch ( $predefinedType ) {
            case PredefinedProject::SYLIUS:
                $installer = new SyliusInstaller( $project );
                break;
            case PredefinedProject::MAGENTO:
                $installer = new MagentoInstaller( $project );
                break;
            default:
                throw new \Exception( 'Not Implemented' );
        }
        
        return $installer;
    }
}
