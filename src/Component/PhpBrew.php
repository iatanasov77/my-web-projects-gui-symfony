<?php namespace App\Component;

class PhpBrew
{
    const EXTENSIONS   = [
        'MongoDB'   => 'mongodb', 
        'Cassandra' => 'cassandra',
        'Xdebug'    => 'xdebug'
    ];
    
    /*
     * XDEBUG 3 Add Configuration to ini_file: /opt/phpbrew/php/php-7.4.10/var/db/xdebug.ini
     * ========================================================================================
     * xdebug.mode = debug
     * xdebug.start_with_request = trigger
     * xdebug.discover_client_host = 1
     * xdebug.client_port = 9000
     */
    
    public static function extensions( $options = [] )
    {
        $extensions = [];
        foreach ( self::EXTENSIONS as $key => $val ) {
            if ( isset( $options['cassandraEnabled'] ) && $options['cassandraEnabled'] == false ) {
                continue;
            }
            $extensions[$key] = $val;
        }
        
        return $extensions;
    }
    
    public static function extensionChoices()
    {
        return self::EXTENSIONS;
    }
}
