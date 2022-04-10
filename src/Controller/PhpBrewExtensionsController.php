<?php namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Process\Process;
use Symfony\Component\HttpFoundation\StreamedResponse;

use App\Component\Globals;
use App\Component\PhpBrew;
use App\Entity\PhpBrewExtension;
use App\Form\Type\PhpBrewExtensionType;

class PhpBrewExtensionsController extends AbstractController
{
    protected $projectDir;
    
    public function __construct( string $projectDir ) {
        $this->projectDir   = $projectDir;
    }
    
    /**
     * @Route("/phpbrew/extensions", name="phpbrew_extensions_index")
     */
    public function index()
    {
        return $this->render( 'pages/phpbrew_extensions.html.twig', [
            'extensions'    => PhpBrew::extensions( $this->phpbrewExtensionsOptions() ),
        ]);
    }
    
    /**
     * @Route("/phpbrew/extensions/edit/{name}", name="phpbrew_extensions_edit_form")
     */
    public function editForm( $name, Request $request )
    {
        $repository         = $this->getDoctrine()->getRepository( PhpBrewExtension::class );
        $phpBrewExtension   = $repository->findOneBy( ['name' => $name] ) ?: new PhpBrewExtension();
        
        $phpBrewExtension->setName( $name );
        $form   = $this->createForm( PhpBrewExtensionType::class, $phpBrewExtension, [
            'action'        => $this->generateUrl( 'phpbrew_extensions_update', ['id' => (int)$phpBrewExtension->getId()] ),
            'method'        => 'POST',
        ]);
        
        return $this->render( 'pages/phpbrew/extension_form.html.twig', [
            'form'              => $form->createView(),
            'phpBrewExtension'  => $phpBrewExtension
        ]);
    }
    
    /**
     * @Route("/phpbrew/extensions/update/{id}", name="phpbrew_extensions_update")
     */
    public function update( $id, Request $request )
    {
        $status             = Globals::STATUS_ERROR;
        
        $em                 = $this->getDoctrine()->getManager();
        $repository         = $this->getDoctrine()->getRepository( PhpBrewExtension::class );
        $phpBrewExtension   = $repository->find( $id ) ?: new PhpBrewExtension();
        $form               = $this->createForm( PhpBrewExtensionType::class, $phpBrewExtension, [
            'action' => $this->generateUrl( 'phpbrew_extensions_update', ['id' => (int)$phpBrewExtension->getId()] ),
            'method' => 'POST'
        ]);
        
        $form->handleRequest( $request );
        if ( $form->isSubmitted() ) {
            $phpBrewExtension   = $form->getData();
            
            $em->persist( $phpBrewExtension );
            $em->flush();
            
            $status     = Globals::STATUS_OK;
            $errors     = [];
        } else {
            foreach ( $form->getErrors( true, false ) as  $error) {
                // My personnal need was to get translatable messages
                // $errors[] = $this->trans($error->current()->getMessage());
                
                $errors[$error->current()->getCause()->getPropertyPath()] = $error->current()->getMessage();
            }
        }
        
        $html   = $this->renderView( 'pages/phpbrew/table_extensions.html.twig', ['extensions' => PhpBrew::extensions( $this->phpbrewExtensionsOptions() )] );
        $response   = [
            'status'    => $status,
            'data'      => $html,
            'errors'    => $errors
        ];
        
        return new JsonResponse( $response );
    }
    
    protected function phpbrewExtensionsOptions()
    {
        $options                = [];
        $configSubsystemsFile   = $this->projectDir . "/var/subsystems.json";
        if ( file_exists( $configSubsystemsFile ) ) {
            $configSubsystems   = json_decode( file_get_contents( $configSubsystemsFile ), true );
            
            $options            = [
                'cassandraEnabled'  => $configSubsystems['cassandra']['enabled'],
            ];
        }
        
        return $options;
    }
}
