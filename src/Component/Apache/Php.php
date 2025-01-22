<?php namespace App\Component\Apache;

class Php
{
    const STATUS_INSTALLED              = 1;
    const STATUS_INSTALLED_NOT_STARTED  = 2;
    const STATUS_NOT_INSTALLED          = 3;
    
    protected static $phpStatuses          = [
        1   => 'Installed and Started',
        2   => 'Installed but Not Started',
        3   => 'Not Installed',
    ];
    
    public static function phpStatus( $statusId )
    {
        return isset( self::$phpStatuses[$statusId] ) ? self::$phpStatuses[$statusId] : 'Not Defined';
    }
}
