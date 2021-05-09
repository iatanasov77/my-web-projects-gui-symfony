<?php namespace App\Component;

class PhpBrew
{
    const EXTENSIONS   = [
        'MongoDB'   => 'mongodb', 
        'Cassandra' => 'cassandra',
    ];
    
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
