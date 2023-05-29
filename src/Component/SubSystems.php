<?php namespace App\Component;

final class SubSystems
{
    protected $projectDir;
    
    protected $envHost;
    
    public function __construct( string $projectDir, string $envHost )
    {
        $this->projectDir   = $projectDir;
        $this->envHost      = $envHost;
    }
    
    public function get(): array
    {
        $configSubsystemsFile   = $this->projectDir . "/var/subsystems-" . $this->envHost . ".json";
        
        if ( file_exists( $configSubsystemsFile ) ) {
            $configSubsystems   = json_decode( file_get_contents( $configSubsystemsFile ), true );
            
            return $configSubsystems;
        }
        
        return [];
    }
}