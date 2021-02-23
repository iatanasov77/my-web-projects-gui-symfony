<?php namespace App\Component\Project\Source;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class GitSource extends Source
{
    public function __construct( $project )
    {
        $this->project   = $project;
    }
    
    public function fetch()
    {
        //git config --global core.filemode false
        //$process = new Process( ['git', 'config', '--global', 'core.filemode', 'false'] );
        //$process->run();
        
        // exec ('git clone https://github.com/iatanasov77/jquery-duplicate-fields /projects/JqueryDuplicateFiles');
        $process = new Process( ['git', 'clone', $this->project->getRepository(), $this->project->getProjectRoot()] );
        $process->run();
        
        // executes after the command finishes
        if ( !$process->isSuccessful() )
        {
            throw new ProcessFailedException( $process );
        }
        
        echo $process->getOutput();
    }
}
