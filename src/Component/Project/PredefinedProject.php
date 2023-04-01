<?php namespace App\Component\Project;

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
    
    private $projectSource;
    
    public function __construct( ProjectSource $projectSource )
    {
        $this->projectSource    = $projectSource;
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
                //return new Sylius( new ProjectSource() );
                return new Sylius( $this->projectSource );
                break;
            case PredefinedProject::MAGENTO:
                //return new Magento( new ProjectSource() );
                return new Magento( $this->projectSource );
                break;
            case PredefinedProject::SYMFONY:
                //return new Symfony( new ProjectSource() );
                return new Symfony( $this->projectSource );
                break;
            case PredefinedProject::LARAVEL:
                //return new Laravel( new ProjectSource() );
                return new Laravel( $this->projectSource );
                break;
            case PredefinedProject::PRESTA_SHOP:
                //return new PrestaShop( new ProjectSource() );
                return new PrestaShop( $this->projectSource );
                break;
            case PredefinedProject::DJANGO:
                //return new Django( new ProjectSource() );
                return new Django( $this->projectSource );
                break;
            default:
                throw new \Exception( 'Not Implemented' );
        }
    }
}
