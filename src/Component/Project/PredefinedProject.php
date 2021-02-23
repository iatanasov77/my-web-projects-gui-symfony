<?php namespace App\Component\Project;

use App\Component\Project\PredefinedProject\PredefinedProjectInterface;
use App\Component\Project\PredefinedProject\Sylius;
use App\Component\Project\PredefinedProject\Magento;
use App\Component\Project\PredefinedProject\Symfony;
use App\Component\Project\PredefinedProject\Laravel;
use App\Component\Project\PredefinedProject\Django;

class PredefinedProject
{
    const SYMFONY   = 'symfony';
    const LARAVEL   = 'laravel';
    const SYLIUS    = 'sylius';
    const MAGENTO   = 'magento';
    const DJANGO    = 'django';
    
    public static function json()
    {
        return \json_encode([
            self::SYLIUS    => Sylius::data(),
            self::MAGENTO   => Magento::data(),
            self::SYMFONY   => Symfony::data(),
            self::LARAVEL   => Laravel::data(),
            self::DJANGO    => Django::data(),
        ], JSON_FORCE_OBJECT);    
    }
    
    public static function choices()
    {
        return [
            'Symfony'       => self::SYMFONY,
            'Laravel'       => self::LARAVEL,
            'Sylius'        => self::SYLIUS,
            'Magento'       => self::MAGENTO,
            'Python Django' => self::DJANGO,
        ];
    }
    
    public static function populate( &$project, $predefinedType )
    {
        self::instance( $predefinedType )->populate( $project );
    }
    
    public static function instance( $predefinedType ): PredefinedProjectInterface
    {
        switch ( $predefinedType ) {
            case PredefinedProject::SYLIUS:
                return new Sylius();
                break;
            case PredefinedProject::MAGENTO:
                return new Magento();
                break;
            case PredefinedProject::SYMFONY:
                return new Symfony();
                break;
            case PredefinedProject::LARAVEL:
                return new Laravel();
                break;
            case PredefinedProject::DJANGO:
                return new Django();
                break;
            default:
                throw new \Exception( 'Not Implemented' );
        }
    }
}
