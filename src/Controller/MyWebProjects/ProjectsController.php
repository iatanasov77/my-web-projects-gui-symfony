<?php namespace App\Controller\MyWebProjects;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Form;
use Doctrine\Persistence\ManagerRegistry;

use App\Component\Command\Apache;
use App\Component\Apache\VirtualHostRepository;

use App\Component\Globals;
use App\Component\Project\Source\SourceFactory;
use App\Component\Project\PredefinedProject;
use App\Entity\Category;
use App\Entity\Project;
use App\Form\Type\ProjectType;
use App\Form\Type\ProjectInstallManualType;
use App\Form\Type\ProjectDeleteType;
use App\Form\Type\CategoryType;

class ProjectsController extends AbstractController
{
    private $doctrine;
    
    private $apacheService;
    
    private $vhRepo;
    
    private $predefinedProject;
    
    public function __construct(
        ManagerRegistry $doctrine,
        Apache $apache,
        VirtualHostRepository $vhRepo,
        PredefinedProject $predefinedProject
    ) {
        $this->doctrine             = $doctrine;
        $this->apacheService        = $apache;
        $this->vhRepo               = $vhRepo;
        $this->predefinedProject    = $predefinedProject;
    }
    
    public function index(): Response
    {
        $repository = $this->doctrine->getRepository( Category::class );
        
        return $this->render('Pages/projects.html.twig', [
            'categories'            => $repository->findAll(),
            'createCategoryForm'    => $this->_categoryForm( new Category() )->createView(),
            'createProjectForm'     => $this->_projectForm( new Project() )->createView(),
            'deleteProjectForm'     => $this->createForm( ProjectDeleteType::class, null, [
                'action'            => $this->generateUrl( 'projects_delete' ),
                'method'            => 'POST'
            ])->createView()
        ]);
    }
    
    public function editForm( $id, Request $request ): Response
    {
        $repository = $this->doctrine->getRepository( Project::class );
        $project    = $id ? $repository->find( $id ) : new Project();
        
        return $this->render( 'Pages/projects/project_form.html.twig', [
            'form'      => $this->_projectForm( $project )->createView(),
            'project'   => $project
        ]);
    }
    
    public function editCategoryForm( $id, Request $request ): Response
    {
        $repository = $this->doctrine->getRepository( Category::class );
        $category   = $id ? $repository->find( $id ) : new Category();
        
        return $this->render( 'Pages/projects/category_form.html.twig', [
            'form'      => $this->_categoryForm( $category )->createView()
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
    
    public function installManual( $id, Request $request ): Response
    {
        $repository     = $this->doctrine->getRepository( Project::class );
        $project        = $repository->find( $id );
        
        $source         = SourceFactory::source( $project );
        if ( ! $source ) {
            throw new \Exception( 'Project source type cannot instantiated' );
        }
        
        return new Response( $source->fetch() );
    }
    
    public function editInstallManual( $id, Request $request ): Response
    {
        $repository     = $this->doctrine->getRepository( Project::class );
        $project        = $repository->find( $id );
        
        $form           = $this->createForm( ProjectInstallManualType::class, $project, [
            'action' => $this->generateUrl( 'edit_install_manual', ['id' => (int)$project->getId()] ),
            'method' => 'POST'
        ]);
        
        $form->handleRequest( $request );
        if ( $form->isSubmitted() ) { // && $form->isValid()
            //$project->setInstallManual(  );
            $em = $this->doctrine->getManager();
            $em->persist( $form->getData() );
            $em->flush();
            
            return $this->redirectToRoute( 'projects' );
        }
        
        return $this->render( 'Pages/projects/project_install_manual.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    public function delete( Request $request ): Response
    {
        $status     = Globals::STATUS_ERROR;
        $em         = $this->doctrine->getManager();
        $repository = $this->doctrine->getRepository( Project::class );
        $form       = $this->createForm( ProjectDeleteType::class, null, [
            'action' => $this->generateUrl( 'projects_delete' ),
            'method' => 'POST'
        ]);
        
        $form->handleRequest( $request );
        if ( $form->isValid() ) {
            $data       = $form->getData();
            $project    = $repository->find( $data['projectId'] );
            $projectRoot= $project ? $project->getProjectRoot() : null;
            
            if ( $project ) {
                $hosts  = $project->getHosts();
                
                $em->remove( $project );
                $em->flush();
                
                /*
                 * When delete project all asociated hosts are deleted cascade,
                 * and need to delete vhost configs of its
                 */
                foreach( $hosts as $host ) {
                    $this->vhRepo->removeVirtualHost( $host->getHost() );
                }
                $this->apacheService->reload(); 
            }
            
            if ( $data['deleteFiles'] == true && is_dir( $projectRoot ) ) {
                system( "rm -rf " . escapeshellarg( $projectRoot ) );
            }
            
            $status     = Globals::STATUS_OK;
            $errors     = [];
        } else {
            foreach ( $form->getErrors( true, false ) as $error ) {
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
    
    public function editCategory( $id, Request $request ): Response
    {
        $status     = Globals::STATUS_ERROR;
        
        $em         = $this->doctrine->getManager();
        $repository = $this->doctrine->getRepository( Category::class );
        $category   = $id ? $repository->find( $id ) : new Category();
        $form       = $this->_categoryForm( $category );
        
        $form->handleRequest( $request );
        if ( $form->isValid() ) {
            $category    = $form->getData();
            
            $em->persist( $category );
            $em->flush();
            
            $status     = Globals::STATUS_OK;
            $errors     = [];
        } else {
            foreach ( $form->getErrors( true, false ) as  $error) {
                // My personnal need was to get translatable messages
                // $errors[] = $this->trans($error->current()->getMessage());
                
                if ( $error instanceof \Symfony\Component\Form\FormError ) {
                    $errors[] = $error->getMessage();
                } else {
                    // Previous Behaviour
                    $errors[$error->current()->getCause()->getPropertyPath()] = $error->current()->getMessage();
                }
            }
        }
        
        $repoProjects   = $this->doctrine->getRepository( Project::class );
        $html           = $this->renderView( 'Pages/projects/table_projects.html.twig', ['projects' => $repoProjects->findAll()] );
        $response   = [
            'status'    => $status,
            'data'      => $html,
            'errors'    => $errors
        ];
        
        return new JsonResponse( $response );
    }
    
    public function predefinedProjectForm( $predefinedType, Request $request ): Response
    {
        //$thirdPartyInstance = PredefinedProject::instance( $predefinedType );
        $thirdPartyInstance = $this->predefinedProject->instance( $predefinedType );
        
        $form               = $thirdPartyInstance->form();
        $parameters         = $thirdPartyInstance->parameters();
        
        //return new JsonResponse( $thirdPartyInstance->parameters() );
        return new Response(
            $this->renderView(
                $form,
                $parameters,
            ),
        );
    }
    
    private function _projectForm( Project $project ): Form
    {
        $form   = $this->createForm( ProjectType::class, $project, [
            'action' => $this->generateUrl( 'projects_create', ['id' => (int)$project->getId()] ),
            'method' => 'POST'
        ]);
        
        return $form;
    }
    
    private function _categoryForm( Category $category ): Form
    {
        
        $form   = $this->createForm( CategoryType::class, $category, [
            'action' => $this->generateUrl( 'category_create', ['id' => (int)$category->getId()] ),
            'method' => 'POST'
        ]);
        
        return $form;
    }
}
