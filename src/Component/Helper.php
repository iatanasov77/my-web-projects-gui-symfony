<?php namespace App\Component;

class Helper
{
    public static function OsId()
    {
        $osId   = 'unkown';
        exec( 'cat /etc/os-release', $osInfo );
        foreach( $osInfo as $osVar ) {
            if ( strpos( $osVar, 'ID' ) === 0 )
            {
                $osId = trim( substr( $osVar, 3 ), '"' );
                break;
            }
        }
        
        return $osId;
    }
}
