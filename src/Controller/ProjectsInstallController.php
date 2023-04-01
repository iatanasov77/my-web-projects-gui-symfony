<?php namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Process\Process;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Form\Form;
use Doctrine\Persistence\ManagerRegistry;

use App\Component\Installer\InstallerFactory;
use App\Entity\Project;
use App\Form\Type\ProjectType;

class ProjectsInstallController extends AbstractController
{
    public function __construct( ManagerRegistry $doctrine )
    {
        $this->doctrine = $doctrine;
    }
    
    /**
     * @Route("/projects/install/{id}", name="projects_install")
     */
    public function install( Request $request, $id ): Response
    {
        if ( $request->isMethod( 'post' ) ) {
            $repository     = $this->doctrine->getRepository( Project::class );
            $project        = $repository->find( $id );
            $form           = $this->_projectForm( $project );
            
            $predefinedType = $project->getPredefinedType();
            $installer      = InstallerFactory::installer( $predefinedType, $project );
            $process        = $installer->install();
            
            return new StreamedResponse( function() use ( $process ) {
                foreach ( $process as $type => $data ) {
                    if ( Process::ERR === $type ) {
                        echo '[ ERR ] '. nl2br( $data ) . '<br />';
                    } else {
                        echo nl2br( $data );
                    }
                }
            });
        }
    }
    
    /**
     * @Route("/projects/uninstall/{id}", name="projects_uninstall")
     */
    public function uninstall( $id ): Response
    {
        $repository = $this->doctrine->getRepository( Project::class );
        $project   =  $repository->find( $id );
        
        return $this->redirectToRoute( 'projects' );
    }
    
    private function _projectForm( Project $project ): Form
    {
        
        $form   = $this->createForm( ProjectType::class, $project, [
            'action' => $this->generateUrl( 'projects_create', ['id' => (int)$project->getId()] ),
            'method' => 'POST'
        ]);
        
        return $form;
    }
}
