<?php namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(
    name: 'vs:mkvhost',
    description: 'Creates a virtual host.',
    hidden: false
)]
class CreateVirtualHostCommand extends ContainerAwareCommand
{
    protected function configure(): void
    {
        $this
            ->setHelp( '"Usage: php bin/console vs:mkvhost -t vhost_template -s your_domain.com -d /path/to/document_root --fpm-socket /path/to/fpm.sock";' )
        ;
        
        $this
            ->addOption( 'template', 't', InputOption::VALUE_OPTIONAL, 'Select a template for the virtual host configuration', 'simple' )
            ->addOption( 'host', 's', InputOption::VALUE_OPTIONAL, 'Select a host address for the server', 'example.com' )
            ->addOption( 'documentroot', 'd', InputOption::VALUE_OPTIONAL, 'Select document root path for this virtual host', '/var/www/html' )
            ->addOption( 'fpm-socket', 'p', InputOption::VALUE_OPTIONAL, 'Add FPM Proxy', null )
            ->addOption( 'with-ssl', null, InputOption::VALUE_NONE )
        ;
    }
    
    protected function execute( InputInterface $input, OutputInterface $output ): int
    {
        $this->executeHere( $input, $output );
        //$this->executeWithVsMkVhost( $input, $output );
        
        return Command::SUCCESS;
    }
    
    protected function executeWithVsMkVhost( InputInterface $input, OutputInterface $output )
    {    
        $output = shell_exec( 'php /usr/local/bin/mkvhost' );
    }
    
    protected function executeHere( InputInterface $input, OutputInterface $output )
    {
        posix_getuid() === 0 || die( "You must to be root.\n" );
        
        $os = \App\Component\Helper::OsId();
        switch ( $os )
        {
            case 'centos':
                $this->createOnCentOs( $input, $output );
                break;
                
            default:
                $this->createOnUbuntu( $input, $output );
                break;
                
        }
        $this->setupHost( $input, $output );
        
        $output->writeln( 'Virtual host created successfully!' );
    }
    
    protected function setupHost( InputInterface $input, OutputInterface $output )
    {
        $ip     = '127.0.0.1';
        $host   = $input->getOption( 'host' );
        $documentRoot   = $input->getOption( 'documentroot' );
        
        $fpmSocket      = $input->getOption( 'fpm-socket' );
        $withSsl        = $input->getOption( 'with-ssl' );
        
        /*
        // Setup installed_hosts.json
        $output->writeln( 'Add host to the "installed_hosts.json" ...' );
        $jsonFile       = 'installed_hosts.json';
        $json           = file_get_contents( $jsonFile );
        $installedHosts = json_decode( $json, true );
        if ( ! isset( $installedHosts[$host] ) ) {
            $installedHosts[$host]  = [
                "hostName"      => $host,
                "documentRoot"  => $documentRoot,
                "withSsl"       => $withSsl,
                "fpmSocket"     => $fpmSocket
            ];
        }
        file_put_contents( $jsonFile, json_encode( $installedHosts, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) );
        */
        
        // Setup hosts file
        $output->writeln( 'Creating a /etc/hosts record...' );
        $hosts = file_get_contents('/etc/hosts');
        if( stripos( $host, $hosts ) === FALSE )
        {
            file_put_contents( '/etc/hosts', sprintf( "%s\t%s www.%s\n", $ip, $host, $host ), FILE_APPEND );
        }
        
    }
    protected function createOnCentos( InputInterface $input, OutputInterface $output )
    {
        $host   = $input->getOption( 'host' );
        $vhostConfFile	= '/etc/httpd/conf.d/' . $host . '.conf';
        $apacheLogDir   = '/var/log/httpd/';
        
        $fpmSocket      = $input->getOption( 'fpm-socket' );
        $template       = 'mkvhost/' . $input->getOption( 'template' ) . ( $fpmSocket ? '-fpm' : '' ) . '.twig';
        
        $documentRoot   = $input->getOption( 'documentroot' );
        $serverAdmin    = 'admin@' . $host;
        
        $withSsl        = $input->getOption( 'with-ssl' );
        
        $output->writeln([
            'VS Virtual Host Creator',
            '=======================',
            '',
        ]);
        
        // Create Virtual Host
        $output->writeln( 'Creating virtual host...' );
        
        $vhost  = $this->getContainer()->get('templating')->render( $template, [
            'host' => $host,
            'documentRoot'  => $documentRoot,
            'serverAdmin'   => $serverAdmin,
            'apacheLogDir'  => $apacheLogDir,
            'fpmSocket'     => $fpmSocket
        ]);
        
        if ( $withSsl )
        {
            $vhost  .= "\n\n" . $this->twig->render( 'templates/mkvhost/ssl.twig', [
                'host' => $host,
                'documentRoot'  => $documentRoot,
                'serverAdmin'   => $serverAdmin,
                'apacheLogDir'  =>$apacheLogDir
            ]);
        }
        file_put_contents( $vhostConfFile, $vhost );
        
        // Reload Apache
        $output->writeln( 'Restarting apache service...' );
        exec( "service httpd restart" );
    }
    
    protected function createOnUbuntu( InputInterface $input, OutputInterface $output )
    {
        $host   = $input->getOption( 'host' );
        $vhostConfFile	= '/etc/apache2/sites-available/' . $host . '.conf';
        $apacheLogDir   = '/var/log/apache2/';
        
        $template   = 'mkvhost/' . $input->getOption( 'template' ) . '.twig';
        $documentRoot   = $input->getOption( 'documentroot' );
        $serverAdmin    = 'admin@' . $host;
        $withSsl        = $input->getOption( 'with-ssl' );
        
        $output->writeln([
            'VS Virtual Host Creator',
            '=======================',
            '',
        ]);
        
        // Create Virtual Host
        $output->writeln( 'Creating virtual host...' );
        
        $vhost  = $this->getContainer()->get('templating')->render( $template, [
            'host' => $host,
            'documentRoot' => $documentRoot,
            'serverAdmin' => $serverAdmin,
            'apacheLogDir' =>$apacheLogDir
        ]);
        
        if ( $withSsl )
        {
            $vhost  .= "\n\n" . $this->twig->render( 'templates/mkvhost/ssl.twig', [
                'host' => $host,
                'documentRoot' => $documentRoot,
                'serverAdmin' => $serverAdmin,
                'apacheLogDir' =>$apacheLogDir
            ]);
        }
        file_put_contents( $vhostConfFile, $vhost );
        exec( "a2ensite {$host}" );
        
        // Reload Apache
        $output->writeln( 'Restarting apache service...' );
        exec( "service apache2 restart" );
    }
}
