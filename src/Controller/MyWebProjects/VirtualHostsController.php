<?php namespace App\Controller\MyWebProjects;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Persistence\ManagerRegistry;

use App\Component\Command\PhpBrew;
use App\Component\Apache\VirtualHostRepository;
use App\Component\Apache\VirtualHostFactory;
use App\Component\Apache\VirtualHostActions;

use App\Component\Apache\Php;
use App\Form\Type\ProjectHostType;
use App\Entity\ProjectHost;
use App\Component\Globals;

use App\Component\Project\Host as HostTypes;
use App\Component\Apache\VirtualHost\VirtualHostLamp;
use App\Component\Project\Host;

class VirtualHostsController extends AbstractController
{
    private $phpBrew;
    
    private $vhRepo;
    
    private $vhFactory;
    
    private $vhActions;
    
    public function __construct(
        ManagerRegistry $doctrine,
        PhpBrew $phpBrew,
        VirtualHostRepository $vhRepo,
        VirtualHostFactory $vhFactory,
        VirtualHostActions $vhActions
    ) {
        $this->doctrine     = $doctrine;
        $this->phpBrew      = $phpBrew;
        $this->vhRepo       = $vhRepo;
        $this->vhFactory    = $vhFactory;
        $this->vhActions    = $vhActions;
    }
    
    public function indexNew( Request $request ): Response
    {
        $installedProjects  = getcwd() . '/../../vagrant.d/installed_projects.json';
        var_dump( json_decode( file_get_contents( $installedProjects ), true ) ); die;
    }
    
    public function index( Request $request ): Response
    {
        $installedVersions  = $this->phpBrew->getInstalledVersions();
        $formHost           = $this->_hostForm( new ProjectHost() );
        
        return $this->render('Pages/virtual_hosts.html.twig', [
            'hosts'                 => $this->vhRepo->virtualHosts(),
            'installedPhpVersions'  => $installedVersions,  
            'formHost'              => $formHost->createView(),
        ]);
    }
    
    public function create( Request $request ): Response
    {
        $formHost       = $this->_hostForm( new ProjectHost() );
        
        $formHost->handleRequest( $request );
        if ( $formHost->isSubmitted() ) {
            $em     = $this->doctrine->getManager();
            $host   = $formHost->getData();
            
            $optionsField   = 'project_host_' . strtolower( $host->getHostType() ) . '_option';
            $host->setOptions( $request->request->all( $optionsField ) );
            
            $this->vhActions->createEnvironment( $host );
            
            $em->persist( $host );
            $em->flush();
            
            $this->createVirtualhost( $host );
            
            return $this->redirectToRoute( 'virtual-hosts' );
        }
    }
    
    public function update( $host, Request $request ): Response
    {
        $projectHost    = $this->loadHost( $host );
        $formHost       = $this->_hostForm( $projectHost, $this->generateUrl( 'virtual-hosts-update', ['host' => $host] ) );
        
        $formHost->handleRequest( $request );
        if ( $formHost->isSubmitted() ) {
            $em     = $this->doctrine->getManager();
            $host   = $formHost->getData();
            
            $optionsField   = 'project_host_' . strtolower( $host->getHostType() ) . '_option';
            $options        = $request->request->get( $optionsField );
            if ( $options ) {
                $host->setOptions( $options );
            }
            
            $this->vhActions->createEnvironment( $host );
            
            $em->persist( $host );
            $em->flush();
            
            $this->createVirtualhost( $host );
            
            return $this->redirectToRoute( 'virtual-hosts' );
        }
        
        return $this->render( 'Pages/virtual_hosts/forms/virtualhost.html.twig', [
            'form'  => $formHost->createView(),
        ]);
    }
    
    public function delete( $host, Request $request ): Response
    {
        $hostConfig = $this->vhRepo->getVirtualHostConfig( $host );
        exec( 'sudo rm -f ' . $hostConfig ); // Remove apache vhost
        
        $repository = $this->doctrine->getRepository( ProjectHost::class );
        $hostEntity = $repository->findOneBy( ['host' => $host] );
        if ( $hostEntity ) {
            $em = $this->doctrine->getManager();
            
            $em->remove( $hostEntity );
            $em->flush();
        }
        
        $response   = [
            'status'    => Globals::STATUS_OK,
            'data'      => '',
            'errors'    => [],
        ];
        return new JsonResponse( $response );
    }
    
    public function setPhpVersion( Request $request ): Response
    {
        if ( $request->isMethod( 'post' ) ) {
            $host               = $request->attributes->get( 'host' );
            $phpVersion         = ltrim( $request->request->get( 'php_version' ), 'php-' );
            $applicationAliases = $request->request->get( 'application_aliases' );
            
            $this->vhRepo->setVirtualhost( $host, $phpVersion );

            return $this->redirectToRoute( 'virtual-hosts' );
        }
    }
    
    private function _hostForm( ProjectHost $projectHost, string $action = null )
    {
        $form   = $this->createForm( ProjectHostType::class, $projectHost, [
            'action' => $action ?: $this->generateUrl( 'virtual-hosts-create' ),
            'method' => 'POST'
        ]);
        
        return $form;
    }
    
    private function createVirtualhost( ProjectHost $host )
    {
        $vhost      = $this->vhFactory->virtualHostFromEntity( $host );
        
        $this->vhRepo->generateVirtualhost( $vhost, $vhost->getTemplate() );
    }
    
    private function loadHost( $host )
    {
        $repository     = $this->doctrine->getRepository( ProjectHost::class );
        $projectHost    = $repository->findOneBy(['host' => $host]);
        
        if ( ! $projectHost ) {
            $projectHost    = new ProjectHost();
            $apacheHost     = $this->vhRepo->getVirtualHostByHost( $host );
            
            $projectHost->setHost( $apacheHost->getHost() );
            $projectHost->setHostType( HostTypes::TYPE_LAMP);
            $projectHost->setDocumentRoot( $apacheHost->getDocumentRoot() );
            $projectHost->setWithSsl( $apacheHost->getWithSsl() );
        }
        
        return $projectHost;
    }
}
