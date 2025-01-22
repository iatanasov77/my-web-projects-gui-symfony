<?php namespace App\Controller\MyWebProjects;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Process\InputStream;

class TestTerminalController extends AbstractController
{
    public function testTerminal( Request $request ): Response
    {
        if ( $request->isMethod( 'post' ) ) {
            $process    = $this->createProcess();
            
            return new StreamedResponse( function() use ( $process ) {
                foreach ( $process as $type => $data ) {
                    if ( Process::ERR === $type ) {
                        //echo '[ ERR ] '. nl2br( $data ) . '<br />';
                        echo \trim( $data );
                    } else {
                        //echo nl2br( $data );
                        echo \trim( $data );
                    }
                }
            });
        }
    }
    
    private function createProcess()
    {
        $command    = ['bin/console', 'my-projects:test-terminal'];
        //$process    = new Process( $command );
        
        ob_implicit_flush( 1 );
        ob_end_flush();
        
        if( ! defined( 'STDIN' ) )  define( 'STDIN',  fopen( 'php://stdin',  'rwb' ) );
        if( ! defined( 'STDOUT' ) ) define( 'STDOUT', fopen( 'php://stdout', 'rwb' ) );
        if( ! defined( 'STDERR' ) ) define( 'STDERR', fopen( 'php://stderr', 'rwb' ) );
        
        $inputStream = new InputStream();
        //$process = new Process( $command, null, [], fopen( 'php://stdin', 'rw' ), null );
        $process = new Process( $command, null, [], \STDIN, null );
        //$process = new Process( $command, $this->getParameter( 'kernel.project_dir' ), [], $inputStream, null );
        
        //$process->setTty(true);
        //$process->setPty( true );
        
        $process->setWorkingDirectory( $this->getParameter( 'kernel.project_dir' ) );
        $process->setTimeout( null );
        
        
        // Run The process
        $process->start();
        
        return $process;
    }
}
