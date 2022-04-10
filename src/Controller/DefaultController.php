<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $redirectUrl    = $this->generateUrl( 'projects' );
        
        return new RedirectResponse( $redirectUrl, 307 );
    }
    
    /**
     * @Route("/pr", name="pr")
     */
    public function projects()
    {
        $repository = $this->getDoctrine()->getRepository( Project::class );
        $projects   =  $repository->findAll();
        
        return $this->render('pages/projects.html.twig', [
            'projects'          => $projects,
            'createProjectForm' => $this->_projectForm( new Project() )->createView(),
            'deleteProjectForm' => $this->createForm( ProjectDeleteType::class, null, [
                'action' => $this->generateUrl( 'projects_delete' ),
                'method' => 'POST'
            ])->createView()
        ]);
    }
}