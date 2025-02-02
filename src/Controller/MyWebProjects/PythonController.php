<?php namespace App\Controller\MyWebProjects;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Process\Process;
use Symfony\Component\HttpFoundation\StreamedResponse;

use App\Component\Command\Python;

class PythonController extends AbstractController
{
    protected $python;
    
    public function __construct( Python $python ) {
        $this->python   = $python;
    }
    
    public function instances( Request $request ): Response
    {
        $virtualEnvironments    = $this->python->getVirtualEnvironments();
        
        return $this->render('Pages/python_virtual_environments.html.twig', [
            'virtual_environments'        => $virtualEnvironments,
        ]);
    }
}
