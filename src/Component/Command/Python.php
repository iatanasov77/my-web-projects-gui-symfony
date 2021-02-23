<?php namespace App\Component\Command;

class Python
{
    public function virtualenv( $host )
    {
        $hostPath   = '/var/www/' . $host;
        if ( ! file_exists( $hostPath ) ) {
            exec( 'sudo mkdir ' . $hostPath );
            exec( 'sudo chown -R apache:root ' . $hostPath );
            exec( 'sudo chmod -R 0777 ' . $hostPath );
        }
            
        exec( 'virtualenv --python=/usr/bin/python3 ' . $hostPath . '/venv' );
    }
        
    public function createDjangoApplication( $host, $projectPath, $applicationName )
    {
        $venvPath   = '/var/www/' . $host . '/venv';
        
        exec( $venvPath . '/bin/pip3 install Django' );
        exec( 'cd ' . $projectPath . ' && ' . $venvPath . '/bin/django-admin startproject ' . $applicationName );
    }
}
