<?php namespace App\Component;

final class SubSystems
{
    protected $projectDir;
    
    public function __construct( string $projectDir )
    {
        $this->projectDir   = $projectDir;
    }
    
    public function get(): array
    {
        $configSubsystemsFile   = $this->projectDir . "/var/subsystems.json";
        
        if ( file_exists( $configSubsystemsFile ) ) {
            $configSubsystems   = json_decode( file_get_contents( $configSubsystemsFile ), true );
            
            return $configSubsystems;
        }
        
        return [];
    }
}