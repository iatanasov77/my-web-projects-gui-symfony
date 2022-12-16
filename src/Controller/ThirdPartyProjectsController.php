<?php namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Process\Process;
use Symfony\Component\HttpFoundation\StreamedResponse;
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
    
    /**
     * @Route("/third-party-projects/edit/{id}", name="third_party_projects_edit_form")
     */
    public function editForm( $id, Request $request )
    {
        $repository = $this->doctrine->getRepository( Project::class );
        $project    = $id ? $repository->find( $id ) : new Project();
        
        return $this->render( 'pages/projects/third_party_project_form.html.twig', [
            'form'      => $this->_projectForm( $project )->createView(),
            'project'   => $project
        ]);
    }
    
    /**
     * @Route("/third-party-projects/create/{id}", name="third_party_projects_create")
     */
    public function create( $id, Request $request )
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
        
        $html   = $this->renderView( 'pages/projects/table_projects.html.twig', ['projects' => $repository->findAll()] );
        $response   = [
            'status'    => $status,
            'data'      => $html,
            'errors'    => $errors
        ];
        
        return new JsonResponse( $response );
    }
    
    private function _projectForm( Project $project )
    {
        $form   = $this->createForm( ThirdPartyProjectType::class, $project, [
            'action' => $this->generateUrl( 'third_party_projects_create', ['id' => (int)$project->getId()] ),
            'method' => 'POST'
        ]);
        
        return $form;
    }
}
