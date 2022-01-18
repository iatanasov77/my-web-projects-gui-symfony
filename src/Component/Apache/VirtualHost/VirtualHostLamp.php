<?php namespace App\Component\Apache\VirtualHost;

use App\Component\Project\Host as HostType;

class VirtualHostLamp extends VirtualHost
{
    /**
     * @var string
     */
    protected $phpVersion;
    
    /**
     * @var int
     */
    protected $phpStatus;
    
    /**
     * @var string
     */
    protected $phpStatusLabel;
    
    /**
     * @var bool
     */
    protected $vsAliases;
    
    public function __construct( $vhostConfig )
    {
        parent::__construct( $vhostConfig );
        
        $this->phpVersion       = $vhostConfig['PhpVersion'];
        $this->phpStatus        = $vhostConfig['PhpStatus'];
        $this->phpStatusLabel   = $vhostConfig['PhpStatusLabel'];
        $this->vsAliases        = isset( $vhostConfig['VsAliases'] ) && $vhostConfig['VsAliases'];
    }
    
    public function type()
    {
        return HostType::TYPE_LAMP;
    }
    
    public function twigVars() : Array
    {
        return array_merge( parent::twigVars(), [
            'vsAliases'  => $this->vsAliases,
        ]);
    }
    
    public function getPhpVersion()
    {
        return $this->phpVersion;
    }
    
    public function setPhpVersion( $phpVersion )
    {
        $this->phpVersion = $phpVersion;
        
        return $this;
    }
    
    public function getPhpStatus()
    {
        return $this->phpStatus;
    }
    
    public function setPhpStatus( $phpStatus )
    {
        $this->phpStatus = $phpStatus;
        
        return $this;
    }
    
    public function getPhpStatusLabel()
    {
        return $this->phpStatusLabel;
    }
    
    public function setPhpStatusLabel( $phpStatusLabel )
    {
        $this->phpStatusLabel = $phpStatusLabel;
        
        return $this;
    }
    
    public function getVsAliases()
    {
        return $this->vsAliases;
    }
    
    public function setVsAliases( $vsAliases )
    {
        $this->vsAliases = $vsAliases;
        
        return $this;
    }
}
