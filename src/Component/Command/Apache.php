<?php namespace App\Component\Command;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

use App\Component\Helper;

class Apache implements ContainerAwareInterface
{
    use ContainerAwareTrait;
    
    /**
     * @var string
     */
    protected $service;
    
    /**
     * @var string
     */
    protected $dirVhosts;
    
    /**
     * @var string
     */
    protected $dirLogs;
    
    public function __coonstruct()
    {
        $this->service      = 'centos' == Helper::OsId() ? 'httpd' : 'apache2';
        $this->dirVhosts    = 'centos' == Helper::OsId() ? '/etc/httpd/conf.d/' : '/etc/apache2/sites-available/';
        $this->dirLogs      = 'centos' == Helper::OsId() ? '/var/log/httpd/' : '/var/log/apache2/';
    }
    
    public function service(): String
    {
        return $this->service;
    }
    
    /**
     * @NOTE Not a good idea to use it from web
     */
    public function restart()
    {
        exec( "sudo service httpd restart" );
    }
    
    public function reload()
    {
        exec( "sudo service httpd reload" );
    }
}
