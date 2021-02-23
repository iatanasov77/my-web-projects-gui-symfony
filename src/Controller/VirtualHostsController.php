<?php namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Component\Apache\Php;
use App\Form\Type\ProjectHostType;
use App\Entity\ProjectHost;
use App\Component\Globals;

use App\Component\Project\Host as HostTypes;
use App\Component\Apache\VirtualHost\VirtualHostLamp;
use App\Entity\ProjectHostOption;
use App\Component\Project\Host;

class VirtualHostsController extends Controller
{   
    /**
     * @Route("/hosts_new", name="virtual-hosts-new")
     */
    public function indexNew( Request $request )
    {
        $installedProjects  = getcwd() . '/../../vagrant.d/installed_projects.json';
        var_dump( json_decode( file_get_contents( $installedProjects ), true ) ); die;
    }
    
    /**
     * @Route("/hosts", name="virtual-hosts")
     */
    public function index( Request $request )
    {
        $virtualHosts       = $this->container->get( 'vs_app.apache_virtual_host_repository' );
        $phpBrew            = $this->container->get( 'vs_app.php_brew' );
        $installedVersions  = $phpBrew->getInstalledVersions();
        
        //$repository = $this->getDoctrine()->getRepository( ProjectHost::class );
        //$host       = $id ? $repository->find( $id ) : new ProjectHost();
        $formHost       = $this->_hostForm( new ProjectHost() );
        
        return $this->render('pages/virtual_hosts.html.twig', [
            'hosts'                 => $virtualHosts->virtualHosts(),
            'installedPhpVersions'  => $installedVersions,  
            'formHost'              => $formHost->createView(),
        ]);
    }
    
    /**
     * @Route("/hosts/create", name="virtual-hosts-create")
     */
    public function create( Request $request )
    {
        $projectHost    = new ProjectHost();
        $formHost       = $this->_hostForm( $projectHost );
        
        $formHost->handleRequest( $request );
        if ( $formHost->isSubmitted() ) {
            $em     = $this->getDoctrine()->getManager();
            $host   = $formHost->getData();
            
            $optionsField   = 'project_host_' . strtolower( $host->getHostType() ) . '_option';
            $host->setOptions( $request->request->get( $optionsField ) );
            
            $this->container->get( 'vs_app.apache_host_actions' )->createEnvironment( $host );
            
            $em->persist( $host );
            $em->flush();
            
            $this->createVirtualhost( $host );
            
            return $this->redirectToRoute( 'virtual-hosts' );
        }
    }
    
    /**
     * @Route("/hosts/{host}/delete", name="virtual-hosts-delete-host")
     */
    public function delete( $host, Request $request )
    {
        $vhosts     = $this->container->get( 'vs_app.apache_virtual_host_repository' );
        $hostConfig = $vhosts->getVirtualHostConfig( $host );
        exec( 'sudo rm -f ' . $hostConfig ); // Remove apache vhost
        
        $repository = $this->getDoctrine()->getRepository( ProjectHost::class );
        $hostEntity = $repository->findOneBy( ['host' => $host] );
        if ( $hostEntity ) {
            $em = $this->getDoctrine()->getManager();
            
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
    
    /**
     * @Route("/hosts/{host}/php-version", name="virtual-hosts-set-php-version")
     */
    public function setPhpVersion( Request $request )
    {
        if ( $request->isMethod( 'post' ) ) {
            $vhosts     = $this->container->get( 'vs_app.apache_virtual_host_repository' );
     
            $host       = $request->attributes->get( 'host' );
            $phpVersion = ltrim( $request->request->get( 'php_version' ), 'php-' );
            
            $vhosts->setVirtualhost( $host, $phpVersion );

            return $this->redirectToRoute( 'virtual-hosts' );
        }
    }
    
    private function _hostForm( ProjectHost $host )
    {
        $projectHost    = new ProjectHost();
        
        //$projectHost->sethostType( HostTypes::TYPE_LAMP );
        //$projectHost->setOptions( HostTypes::options( HostTypes::TYPE_LAMP ) );
        
        $form   = $this->createForm( ProjectHostType::class, $projectHost, [
            'action' => $this->generateUrl( 'virtual-hosts-create' ),
            'method' => 'POST'
        ]);
        
        return $form;
    }
    
    private function createVirtualhost( ProjectHost $host )
    {
        $vhosts     = $this->container->get( 'vs_app.apache_virtual_host_repository' );
        $factory    = $this->container->get( 'vs_app.apache_virtual_host_factory' );
        $vhost      = $factory->virtualHostFromEntity( $host );
        
        $vhosts->generateVirtualhost( $vhost, $vhost->getTemplate() );
    }
}
