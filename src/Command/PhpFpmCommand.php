<?php namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

#[AsCommand(
    name: 'vs:phpfpm',
    description: 'Start PhpFpm Service. Confugure it if it is not. Starting from /opt/phpbrew/php/.... for now.',
    hidden: false
)]
class PhpFpmCommand extends ContainerAwareCommand
{
    protected $installationPath;
    
    protected function configure(): void
    {
        $this
            ->setHelp( '"Usage: php bin/console vs:phpfpm start -v 7.4.8 -n MyCustomName";' )
            
            ->addArgument( 'run-command', InputArgument::REQUIRED, 'Available Values: start | stop | restart' )
        
            ->addOption( 'php-version', 'p', InputOption::VALUE_OPTIONAL, 'Select a template for the virtual host configuration' )
            ->addOption( 'custom-name', 'c', InputOption::VALUE_OPTIONAL, 'Select a custom build', '' )
        ;
    }
    
    protected function execute( InputInterface $input, OutputInterface $output ): int
    {
        posix_getuid() === 0 || die( "You must to be root.\n" );
        
        $runCommand = $input->getArgument( 'run-command' );
        
        $version    = $input->getOption( 'php-version' );
        $customName = $input->getOption( 'custom-name' );
        
        $this->installationPath = '/opt/phpbrew/php/php-' . $version;
        if ( ! empty( $customName ) ) {
            $this->installationPath .= '-' . $customName;
        }
        
        if ( ! file_exists( $this->installationPath . '/CONFIGURED' ) ) {
            $this->setup();
            
            file_put_contents( $this->installationPath . '/CONFIGURED', date( 'Y-m-d H:i:s' ) );
        }
        
        if ( $runCommand == 'start' ) {
            // @TODO Check if is not started already (Check the socket file)
            exec( 'sudo ' . $this->installationPath . '/sbin/php-fpm' );
            var_dump('sudo ' . $this->installationPath . '/sbin/php-fpm'); die; // Taka Raboti :D
            $output->writeln( 'PhpFpm Service has started!' );
        } elseif ( $runCommand == 'stop' ) {
            // @TODO Check if is not started already (Check the socket file)
            exec( 'sudo rm -f ' . $this->installationPath . '/var/run/php-fpm.sock' );
            var_dump('sudo ' . $this->installationPath . '/sbin/php-fpm'); die; // Taka Raboti :D
            $output->writeln( 'PhpFpm Service has stoped!' );
        } elseif ( $runCommand == 'restart' ) {
            exec( 'sudo rm -f ' . $this->installationPath . '/var/run/php-fpm.sock' );
            exec( $this->installationPath . '/sbin/php-fpm' );
            var_dump('sudo ' . $this->installationPath . '/sbin/php-fpm'); die; // Taka Raboti :D
            $output->writeln( 'PhpFpm Service has restarted!' );
        } else {
            throw  new \Exception( 'Unsupported command !!!' );
        }
        
        return Command::SUCCESS;
    }
    
    protected function setup()
    {
        switch ( true )
        {
            case file_exists( $this->installationPath . '/etc/php-fpm.d/www.conf' ):
                $confFile   = $this->installationPath . '/etc/php-fpm.d/www.conf';
                break;
            case file_exists( $this->installationPath . '/etc/php-fpm.conf' ):
                $confFile   = $this->installationPath . '/etc/php-fpm.conf';
                break;
            default:
                throw new \Exception( 'Cannot find PhpFpm config file !!!' );
        }
        
        
        $fpmConfig  = file_get_contents( $confFile );
        $newConfig  = strtr( $fpmConfig, [
            'nobody'                    => 'apache',
            ';listen.owner'             => 'listen.owner',
            ';listen.group'             => 'listen.group',
            ';listen.mode'              => 'listen.mode',
            ';listen.allowed_clients'   => 'listen.allowed_clients',
        ]);
        
        file_put_contents( $confFile, $newConfig );
        
        // # Allow The all of server memory
        // local iniConfig="${PHPBREW_PREFIX}/php/php-${PHP_VERSION}/etc/php.ini"
        // sed -i "s/^;\{0,1\}memory_limit *= *[^ ]*/memory_limit = -1/" ${iniConfig}
    }
}
