<?php namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Process\Process;
use Symfony\Component\HttpFoundation\StreamedResponse;

use App\Component\Globals;
use App\Component\Project\Source\SourceFactory;
use App\Component\Installer\InstallerFactory;
use App\Component\Project\PredefinedProject;
use App\Entity\Category;
use App\Entity\Project;
use App\Form\Type\PredefinedProjectType;
use App\Form\Type\ProjectType;
use App\Form\Type\ProjectInstallManualType;
use App\Form\Type\ProjectDeleteType;
use App\Form\Type\CategoryType;

class ProjectsInstallController extends Controller
{
    /**
     * @Route("/projects/install/{id}", name="projects_install")
     */
    public function install( Request $request, $id )
    {
        if ( $request->isMethod( 'post' ) ) {
            $repository     = $this->getDoctrine()->getRepository( Project::class );
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
    public function uninstall( $id )
    {
        $repository = $this->getDoctrine()->getRepository( Project::class );
        $project   =  $repository->find( $id );
        
        return $this->redirectToRoute( 'projects' );
    }
    
    private function _projectForm( Project $project )
    {
        
        $form   = $this->createForm( ProjectType::class, $project, [
            'action' => $this->generateUrl( 'projects_create', ['id' => (int)$project->getId()] ),
            'method' => 'POST'
        ]);
        
        return $form;
    }
}
