<?php namespace App\Controller\MyWebProjects;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Form;
use Doctrine\Persistence\ManagerRegistry;

use App\Component\Globals;
use App\Entity\Project;
use App\Form\Type\ThirdPartyProjectType;

class ThirdPartyProjectsController extends AbstractController
{
    public function __construct( ManagerRegistry $doctrine )
    {
        $this->doctrine = $doctrine;
    }
    
    public function editForm( $id, Request $request ): Response
    {
        $repository = $this->doctrine->getRepository( Project::class );
        $project    = $id ? $repository->find( $id ) : new Project();
        
        return $this->render( 'Pages/projects/third_party_project_form.html.twig', [
            'form'      => $this->_projectForm( $project )->createView(),
            'project'   => $project
        ]);
    }
    
    public function create( $id, Request $request ): Response
    {
        $status     = Globals::STATUS_ERROR;
        $errors     = [];
        
        $em         = $this->doctrine->getManager();
        $repository = $this->doctrine->getRepository( Project::class );
        $project    = $id ? $repository->find( $id ) : new Project();
        $form       = $this->_projectForm( $project );
        
        $form->handleRequest( $request );
        if ( $form->isSubmitted() ) {
            $project                = $form->getData();
            
            $predefinedTypeParams   = $request->request->get( 'predefinedTypeParams' );
            $project->setPredefinedTypeParams( $predefinedTypeParams );
            //PredefinedProject::populate( $project, $predefinedType );
            
            $em->persist( $project );
            $em->flush();
            
            $status     = Globals::STATUS_OK;
        } else {
            foreach ( $form->getErrors( true, false ) as  $error) {
                // My personnal need was to get translatable messages
                // $errors[] = $this->trans($error->current()->getMessage());
                
                $errors[$error->current()->getCause()->getPropertyPath()] = $error->current()->getMessage();
            }
        }
        
        $html   = $this->renderView( 'Pages/projects/table_projects.html.twig', ['projects' => $repository->findAll()] );
        $response   = [
            'status'    => $status,
            'data'      => $html,
            'errors'    => $errors
        ];
        
        return new JsonResponse( $response );
    }
    
    private function _projectForm( Project $project ): Form
    {
        $form   = $this->createForm( ThirdPartyProjectType::class, $project, [
            'action' => $this->generateUrl( 'third_party_projects_create', ['id' => (int)$project->getId()] ),
            'method' => 'POST'
        ]);
        
        return $form;
    }
}
