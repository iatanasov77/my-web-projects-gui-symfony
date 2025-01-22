<?php namespace App\Component\Apache\VirtualHost;

use App\Component\Project\Host as HostType;

class VirtualHostPython extends VirtualHost
{
    /**
     * @var string
     */
    protected $pythonProjectPath;
    
    /**
     * @var int
     */
    protected $venvPath;
    
    /**
     * @var string
     */
    protected $wsgiProcessName;
    
    /**
     * @var string
     */
    protected $wsgiUser;
    
    /**
     * @var string
     */
    protected $wsgiGroup;
    
    /**
     * @var string
     */
    protected $wsgiProcesses;
    
    /**
     * @var string
     */
    protected $wsgiThreads;
    
    /**
     * @var string
     */
    protected $wsgiScriptAlias;
    
    public function __construct( $vhostConfig )
    {
        parent::__construct( $vhostConfig );
        //var_dump($vhostConfig); die;
        
        if (
            isset( $vhostConfig['projectPath'] ) &&  
            isset( $vhostConfig['venvPath'] ) && 
            isset( $vhostConfig['scriptAlias'] )
        ) { // WORKAROUND
            $this->pythonProjectPath    = $vhostConfig['projectPath'];
            $this->venvPath             = $vhostConfig['venvPath'];
            $this->wsgiProcessName      = $vhostConfig['ServerName'];
            
            $this->wsgiUser             = $vhostConfig['user'];
            $this->wsgiGroup            = $vhostConfig['group'];
            $this->wsgiProcesses        = $vhostConfig['processes'];
            $this->wsgiThreads          = $vhostConfig['threads'];
            $this->wsgiScriptAlias      = $vhostConfig['scriptAlias'];
        }
    }
    
    public function type()
    {
        return HostType::TYPE_PYTHON;
    }
    
    public function twigVars() : Array
    {
        return array_merge( parent::twigVars(), [
            'wsgiProcessName'   => $this->wsgiProcessName,
            'venvPath'          => $this->venvPath,
            'pythonProjectPath' => $this->pythonProjectPath,
            'wsgiUser'          => $this->wsgiUser,
            'wsgiGroup'         => $this->wsgiGroup,
            'wsgiProcesses'     => $this->wsgiProcesses,
            'wsgiThreads'       => $this->wsgiThreads,
            'wsgiScriptAlias'   => $this->wsgiScriptAlias,
        ]);
    }
}
