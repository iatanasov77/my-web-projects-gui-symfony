<?php namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Process\Process;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PythonController extends Controller
{
    protected $python;
    
    /**
     * @Route("/python/virtual-environments", name="python-venvs")
     */
    public function instances( Request $request )
    {
        $this->python           = $this->container->get( 'app.python' );
        $virtualEnvironments    = $this->python->getVirtualEnvironments();
        
        return $this->render('pages/python_virtual_environments.html.twig', [
            'virtual_environments'        => $virtualEnvironments,
        ]);
    }
}
