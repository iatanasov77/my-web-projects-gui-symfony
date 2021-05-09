<?php namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Process\Process;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TomcatController extends Controller
{
    protected $tomcat;
    
    /**
     * @Route("/tomcat/instances", name="tomcat-instances")
     */
    public function instances( Request $request )
    {
        $this->tomcat           = $this->container->get( 'app.tomcat' );
        $installedVersions      = $this->tomcat->getInstalledVersions();
        //$availableVersions      = $this->tomcat->getAvailableVersions();
        
        return $this->render('pages/tomcat_instances.html.twig', [
            'versions_installed'        => $installedVersions,
            //'versions_available'        => $availableVersions,
        ]);
    }
}
