<?php namespace App\Component\Apache\VirtualHost;

abstract class VirtualHost
{
    /**
     * @var string
     */
    protected $template;
    
    /**
     * @var string
     */
    protected $host;
    
    /**
     * @var string
     */
    protected $documentRoot;
    
    /**
     * @var string
     */
    protected $serverAdmin;
    
    /**
     * @var string
     */
    protected $apacheLogDir;
    
    /**
     * @var bool
     */
    protected $withSsl;
    
    public function __construct( $vhostConfig )
    {
        $this->template         = $vhostConfig['template'];
        $this->host             = $vhostConfig['ServerName'];
        $this->documentRoot     = $vhostConfig['DocumentRoot'];
        $this->serverAdmin      = isset( $vhostConfig['ServerAdmin'] ) ? $vhostConfig['ServerAdmin'] : 'webmaster@' . $this->host;
        $this->apacheLogDir     = $vhostConfig['LogDir'];
        $this->withSsl          = $vhostConfig['WithSsl'];
    }
    
    abstract public function type();
    
    public function twigVars() : Array
    {
        return [
            'host'          => $this->host,
            'documentRoot'  => $this->documentRoot,
            'serverAdmin'   => $this->serverAdmin,
            'apacheLogDir'  => $this->apacheLogDir,
        ];
    }
    
    public function getTemplate()
    {
        return $this->template;
    }
    
    public function setTemplate( $template )
    {
        $this->template = $template;
        
        return $this;
    }
    
    public function getHost()
    {
        return $this->host;
    }
    
    public function setHost( $host )
    {
        $this->host = $host;
        
        return $this;
    }
    
    public function getDocumentRoot()
    {
        return $this->documentRoot;
    }
    
    public function setDocumentRoot( $documentRoot )
    {
        $this->documentRoot = $documentRoot;
        
        return $this;
    }
    
    public function getServerAdmin()
    {
        return $this->serverAdmin;
    }
    
    public function setServerAdmin( $serverAdmin )
    {
        $this->serverAdmin = $serverAdmin;
        
        return $this;
    }
    
    public function getApacheLogDir()
    {
        return $this->apacheLogDir;
    }
    
    public function setApacheLogDir( $apacheLogDir )
    {
        $this->apacheLogDir = $apacheLogDir;
        
        return $this;
    }

    public function getWithSsl()
    {
        return $this->withSsl;
    }
    
    public function setWithSsl( $withSsl )
    {
        $this->withSsl  = $withSsl;
        
        return $this;
    }
}
