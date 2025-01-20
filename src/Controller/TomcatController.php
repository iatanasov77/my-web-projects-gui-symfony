<?php namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Process\Process;
use Symfony\Component\HttpFoundation\StreamedResponse;

use App\Component\SubSystems;
use App\Component\Command\Tomcat;

class TomcatController extends AbstractController
{
    protected $subsystems;
    
    protected $tomcat;
    
    public function __construct( Tomcat $tomcat, SubSystems $subsystems )
    {
        $this->subsystems   = $subsystems;
        $this->tomcat       = $tomcat;
    }
    /**
     * @Route("/tomcat/instances", name="tomcat-instances")
     */
    public function instances( Request $request )
    {
        $installedVersions  = $this->tomcat->getInstalledVersions();
        $subsystems         = $this->subsystems->get();
        //echo "<pre>"; var_dump($subsystems['tomcat']['instances']); die;
        
        return $this->render('Pages/tomcat_instances.html.twig', [
            'versions_installed'    => $installedVersions,
            'instances_configs'     => $subsystems['tomcat']['instances'],
            'current_host'          => $request->getHost(),
        ]);
    }
}
