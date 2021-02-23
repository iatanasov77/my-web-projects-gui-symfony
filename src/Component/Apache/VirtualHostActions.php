<?php namespace App\Component\Apache;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

use App\Component\Project\Host as HostTypes;

class VirtualHostActions implements ContainerAwareInterface
{
    use ContainerAwareTrait;
    
    public function createEnvironment( &$hostEntity )
    {
        switch ( $hostEntity->getHostType() ) {
            case HostTypes::TYPE_PYTHON:
                $options    = $hostEntity->getOptions();
                
                $this->createPythonHostEnvironment( $hostEntity->getHost() );
                $this->createDjangoApplication( $hostEntity->getHost(), $options['projectPath'], $options['appName'] );
                
                $hostEntity->setDocumentRoot( $hostEntity->getDocumentRoot() . '/' . $options['appName'] );
                break;
        }
    }
    
    private function createPythonHostEnvironment( $host )
    {
        $service    = $this->container->get( 'vs_app.python' );
        $service->virtualenv( $host );
    }
    
    private function createDjangoApplication( $host, $projectPath, $applicationName )
    {
        $service    = $this->container->get( 'vs_app.python' );
        $service->createDjangoApplication( $host, $projectPath, $applicationName );
    }
}
