<?php namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Process\Process;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\Persistence\ManagerRegistry;

use Vankosoft\ApplicationBundle\Controller\Traits\ConsoleCommandTrait;
use App\Component\Command\Apache;
use App\Component\Command\PhpBrew as PhpBrewCommand;
use App\Component\PhpBrew;
use App\Component\SubSystems;
use App\Entity\PhpBrewExtension;

class PhpVersionsController extends AbstractController
{
    use ConsoleCommandTrait;
    
    private $kernel;
    
    private $doctrine;
    
    protected $phpBrew;
    
    protected $installProcess;
    
    protected $installProcessCallback;
    
    protected $apacheService;
    
    protected $subsystems;
    
    protected $phpbrewVariants;
    
    protected $phpbrewVariantsDefault;
    
    protected $projectDir;
    
    public function __construct(
        KernelInterface $kernel,
        ManagerRegistry $doctrine,
        PhpBrewCommand $phpBrewCommand,
        Apache $apache,
        SubSystems $subsystems,
        array $phpbrewVariants,
        array $phpbrewVariantsDefault,
        string $projectDir
    ) {
        $this->kernel                   = $kernel;
        $this->doctrine                 = $doctrine;
        $this->phpBrew                  = $phpBrewCommand;
        $this->apacheService            = $apache;
        $this->subsystems               = $subsystems;
        $this->phpbrewVariants          = $phpbrewVariants;
        $this->phpbrewVariantsDefault   = $phpbrewVariantsDefault;
        $this->projectDir               = $projectDir;
        
        $this->installProcessCallback   = function() {
            echo '<span style="font-weight: bold;">Running command:</span> ' . $this->phpBrew->getCurrentCommand();
            
            foreach ( $this->installProcess as $type => $data ) {
                if ( Process::ERR === $type ) {
                    echo '[ ERR ] '. nl2br( $data ) . '<br />';
                } else {
                    echo nl2br( $data );
                }
            }
        };
        
    }
    /**
     * @Route("/php-versions", name="php-versions")
     */
    public function index( Request $request )
    {
        $installedVersions  = $this->phpBrew->getInstalledVersions();
        $availableVersions  = $this->phpBrew->getAvailableVersions();
        
        $subsystems         = $this->subsystems->get();
        $cassandraEnabled   = isset( $subsystems['cassandra'] ) && $subsystems['cassandra']['enabled'];
        
        return $this->render('Pages/php_versions.html.twig', [
            'versions_installed'        => $installedVersions,
            'versions_available'        => $availableVersions,
            'phpbrew_variants'          => array_diff( $this->phpbrewVariants, $this->phpbrewVariantsDefault ),
            'phpbrew_extensions'        => PhpBrew::extensions( ['cassandraEnabled' => $cassandraEnabled] ),
            'phpbrew_variants_default'  => $this->phpbrewVariantsDefault,
            'cassandraEnabled'          => $cassandraEnabled,
        ]);
    }
    
    /**
     * @Route("/php-versions/{version}/remove", name="php-versions-remove")
     */
    public function remove( $version, Request $request )
    {
        $buildPath          = '/opt/phpbrew/build/php-' . $version;
        $installationPath = '/opt/phpbrew/php/php-' . $version;
        
        exec( 'sudo rm -rf ' . $installationPath );
        exec( 'sudo rm -rf ' . $buildPath );
        
        $referer    = $request->headers->get( 'referer' ); // get the referer, it can be empty!
        
        return $this->redirect( $referer );
    }
    
    /**
     * @Route("/php-versions/available-gtree", name="php-versions-available-gtree")
     */
    public function gtreeTableSource( Request $request ): Response
    {
        $versions       = $this->phpBrew->getAvailableVersions();
        
        $parent         = $request->query->get( 'version' );
        $level          = $parent ? 2 : 1;
        
        $gtreeTableData = $this->buildGtreeTableData( $parent ? $versions[$parent] : $versions, $level );
        
        return new JsonResponse( ['nodes' => $gtreeTableData, 'readonly' => true] );
    }
    
    /**
     * @Route("/php-versions/install", name="php-versions-install")
     * 
     * 
     * See Build Log:  tail -F '/opt/phpbrew/build/php-7.4.1/build.log'
     */
    public function installPhpVersion( Request $request ): Response
    {
        if ( $request->isMethod( 'post' ) ) {
            
            $version            = $request->request->get( 'version' );
            $phpBrewVariants    = $request->request->all( 'phpBrewVariants' ) ?? [];
            $phpExtensions      = $request->request->all( 'phpExtensions' ) ?? [];
            $phpBrewCustomName  = $request->request->get( 'phpBrewCustomName' );
            $displayBuildOutput = $request->request->get( 'displayBuildOutput' ) ? true : false;
            $useGithub          = $request->request->get( 'useGithub' ) ? true : false;
            if ( $useGithub ) {
                $this->transformPhpExtensions( $phpExtensions );
            }
            //var_dump($phpExtensions); die;
            
            $this->installProcess   = $this->phpBrew->install(
                $version,
                $phpBrewVariants,
                $phpExtensions,
                $displayBuildOutput, 
                $phpBrewCustomName
            );

            return $this->streamedProcessResponse( $this->installProcessCallback );
        }
    }
    
    /**
     * @Route("/php-versions/{version}/setup", name="php-versions-setup")
     */
    public function setupAfterInstall( Request $request ): Response
    {
        $requestedVersion   = $request->attributes->get( 'version' );
        $parts              = explode( '-', $requestedVersion );
        
        $this->phpBrew->setupFpm( $parts[0], '' );
        
        return new JsonResponse( ['success' => true] );
    }
    
    /**
     * EXAMPLES:   /php-versions/7.4.2/start-fpm
     *              /php-versions/7.4.2-CustomName/start-fpm
     * 
     * @Route("/php-versions/{version}/start-fpm", name="php-versions-start-fpm")
     */
    public function startPhpFpm( Request $request ): Response
    {
        $requestedVersion   = $request->attributes->get( 'version' );
        
        
        /*
         * Good bundle: https://github.com/cocur/background-process
         */
        /*
        $parts      = explode( '-', $requestedVersion );
        $command    = [
            '/bin/sudo',
            $this->projectDir . '/bin/console',
            'vs:phpfpm',
            'start',
            '-p',
            $parts[0]
        ];
        
        if ( isset( $parts[1] ) ) {
            $command[]  = '-c';
            $command[]  = $parts[1];
        }
        */
        
        $command    = [
            '/bin/sudo',
            "/opt/phpbrew/php/php-{$requestedVersion}/sbin/php-fpm",
        ];
        
        $process    = new Process( $command );
        $process->start();
        
        $referer    = $request->headers->get( 'referer' ); // get the referer, it can be empty!
        
        return $this->redirect( $referer );
    }
    
    /**
     * @Route("/php-versions/{version}/stop-fpm", name="php-versions-stop-fpm")
     */
    public function stopPhpFpm( Request $request ): Response
    {
        $requestedVersion   = $request->attributes->get( 'version' );
        $parts              = explode( '-', $requestedVersion );
        
        /*
         * Good bundle: https://github.com/cocur/background-process
         */
        $command    = [
            '/bin/sudo',
            $this->projectDir . '/bin/console',
            'vs:phpfpm',
            'stop',
            '-p',
            $parts[0]
        ];
        
        if ( isset( $parts[1] ) ) {
            $command[]  = '-c';
            $command[]  = $parts[1];
        }
        
        $process    = new Process( $command );
        $process->start();
        
        $referer    = $request->headers->get( 'referer' ); // get the referer, it can be empty!
        
        return $this->redirect( $referer );
    }
    
    /**
     * @Route("/php-versions/{version}/restart-fpm", name="php-versions-restart-fpm")
     */
    public function restartPhpFpm( Request $request ): Response
    {
        $requestedVersion   = $request->attributes->get( 'version' );
        $parts              = explode( '-', $requestedVersion );
        
        /*
         * Good bundle: https://github.com/cocur/background-process
         */
        $command    = [
            '/bin/sudo',
            $this->projectDir . '/bin/console',
            'vs:phpfpm',
            'restart',
            '-p',
            $parts[0]
        ];
        
        if ( isset( $parts[1] ) ) {
            $command[]  = '-c';
            $command[]  = $parts[1];
        }
        
        $process    = new Process( $command );
        $process->start();
        
        $this->apacheService->reload(); // Reload Apache Service
        $referer    = $request->headers->get( 'referer' ); // get the referer, it can be empty!
        
        return $this->redirect( $referer );
    }
    protected function buildGtreeTableData( $availableVersions, $level )
    {
        $installedVersions  = $this->phpBrew->getInstalledVersions();
        $data               = [];
        foreach ( $availableVersions as $k => $av ) {
            $version = [
                'id'        => $k,
                'name'      => $k,
                'level'     => $level,
                'type'      => in_array( 'php-' . $k, $installedVersions ) ? "installed" : "not_installed",
                'children'  => []
            ];
                
            $data[] = $version;
        }
        
        return $data;
    }
    
    protected function transformPhpExtensions( &$phpExtensions )
    {
        $repository         = $this->doctrine->getRepository( PhpBrewExtension::class );
        foreach ( $phpExtensions as $key => $ext ) {
            $extension = $repository->findOneBy( ['name' => $ext] );
            if ( $extension ) {
                if ( $extension->getGithubRepo() ) {
                    // Example: phpbrew ext install github:php-memcached-dev/php-memcached php7 -- --disable-memcached-sasl
                    $phpExtensions[$key]    = 'github:' . $extension->getGithubRepo() . ' ' . $extension->getBranch();
                }
            }
        }
    }
    
    protected function getKernel(): KernelInterface
    {
        return $this->kernel;
    }
}
