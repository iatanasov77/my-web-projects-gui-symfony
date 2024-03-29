<?php namespace App\Component\Project;

use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Component\Installer\ProjectSource;
use App\Component\Project\PredefinedProject\PredefinedProjectInterface;
use App\Component\Project\PredefinedProject\Sylius;
use App\Component\Project\PredefinedProject\Magento;
use App\Component\Project\PredefinedProject\Symfony;
use App\Component\Project\PredefinedProject\Laravel;
use App\Component\Project\PredefinedProject\PrestaShop;
use App\Component\Project\PredefinedProject\Django;

class PredefinedProject
{
    const SYMFONY       = 'symfony';
    const LARAVEL       = 'laravel';
    const SYLIUS        = 'sylius';
    const MAGENTO       = 'magento';
    const PRESTA_SHOP   = 'presta_shop';
    const DJANGO        = 'django';
    
    private $container;
    
    public function __construct( ContainerInterface $container )
    {
        $this->container    = $container;
    }
    
    public static function json()
    {
        return \json_encode([
            self::SYLIUS        => Sylius::data(),
            self::MAGENTO       => Magento::data(),
            self::SYMFONY       => Symfony::data(),
            self::LARAVEL       => Laravel::data(),
            self::PRESTA_SHOP   => PrestaShop::data(),
            self::DJANGO        => Django::data(),
        ], JSON_FORCE_OBJECT);    
    }
    
    public static function choices()
    {
        return [
            'Symfony'       => self::SYMFONY,
            'Laravel'       => self::LARAVEL,
            'Sylius'        => self::SYLIUS,
            'Magento'       => self::MAGENTO,
            'PrestaShop'    => self::PRESTA_SHOP,
            'Python Django' => self::DJANGO,
        ];
    }
    
    public static function populate( &$project, $predefinedType )
    {
        self::instance( $predefinedType )->populate( $project );
    }
    
    //public static function instance( $predefinedType ): PredefinedProjectInterface
    public function instance( $predefinedType ): PredefinedProjectInterface
    {
        switch ( $predefinedType ) {
            case PredefinedProject::SYLIUS:
                return new Sylius( $this->container->get( 'my_projects.packagist_api' ) );
                break;
            case PredefinedProject::MAGENTO:
                return new Magento( $this->container->get( 'my_projects.packagist_api' ) );
                break;
            case PredefinedProject::SYMFONY:
                return new Symfony( $this->container->get( 'my_projects.packagist_api' ) );
                break;
            case PredefinedProject::LARAVEL:
                return new Laravel( $this->container->get( 'my_projects.packagist_api' ) );
                break;
            case PredefinedProject::PRESTA_SHOP:
                return new PrestaShop( $this->container->get( 'my_projects.packagist_api' ) );
                break;
            case PredefinedProject::DJANGO:
                return new Django( $this->container->get( 'my_projects.project_source' ) );
                break;
            default:
                throw new \Exception( 'Not Implemented' );
        }
    }
}
