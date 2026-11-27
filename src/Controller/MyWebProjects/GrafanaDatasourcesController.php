<?php namespace App\Controller\MyWebProjects;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Form;
use Doctrine\Persistence\ManagerRegistry;

use App\Component\Globals;
use App\Entity\GrafanaDatasource;
use App\Entity\GrafanaDatasourceExporter;
use App\Form\Type\GrafanaDatasourceType;
use App\Form\Type\GrafanaDatasourceExporterType;

class GrafanaDatasourcesController extends AbstractController
{
    private $doctrine;
    
    public function __construct(
        ManagerRegistry $doctrine
    ) {
        $this->doctrine = $doctrine;
    }
    
    public function index(): Response
    {
        $repository = $this->doctrine->getRepository( GrafanaDatasource::class );
        
        return $this->render( 'Pages/grafana_datasources.html.twig', [
            'grafanaDatasources'    => $repository->findAll(),
            'grafanaUrl'            => $this->getParameter( 'grafana_url' ),
            'alertmanagerUrl'       => $this->getParameter( 'alertmanager_url' ),
        ]);
    }
    
    public function datasourceForm( $id, Request $request ): Response
    {
        if ( $id ) {
            $repository = $this->doctrine->getRepository( GrafanaDatasource::class );
            $datasource = $repository->find( $id );
        } else {
            $datasource = new GrafanaDatasource();
        }
        
        $form   = $this->createForm( GrafanaDatasourceType::class, $datasource, [
            'action' => $this->generateUrl( 'application-monitoring-grafana-datasource_form', ['id' => $id] ),
            'method' => 'POST'
        ]);
        
        $form->handleRequest( $request );
        if ( $form->isSubmitted() ) {
            $datasource = $form->getData();
            
            $em = $this->doctrine->getManager();
            $em->persist( $datasource );
            $em->flush();
            
            return $this->redirectToRoute( 'application-monitoring-grafana-datasources' );
        }
        
        return $this->render( 'Pages/monitoring/datasource_form.html.twig', [
            'form'          => $form->createView(),
            'datasource'    => $datasource
        ]);
    }
    
    public function exporterForm( $datasourceId, $id, Request $request ): Response
    {
        if ( $id ) {
            $repository = $this->doctrine->getRepository( GrafanaDatasourceExporter::class );
            $exporter   = $repository->find( $id );
        } else {
            $exporter = new GrafanaDatasourceExporter();
        }
        
        $form   = $this->createForm( GrafanaDatasourceExporterType::class, $exporter, [
            'action' => $this->generateUrl( 'application-monitoring-grafana-datasource_exporter_form', [
                'datasourceId'  => $datasourceId,
                'id'            => $id,
            ]),
            'method' => 'POST'
        ]);
        
        $form->handleRequest( $request );
        if ( $form->isSubmitted() ) {
            $exporter = $form->getData();
            
            $em = $this->doctrine->getManager();
            $em->persist( $exporter );
            $em->flush();
            
            return $this->redirectToRoute( 'application-monitoring-grafana-datasources' );
        }
        
        return $this->render( 'Pages/monitoring/datasource_exporter_form.html.twig', [
            'form'      => $form->createView(),
            'exporter'  => $exporter
        ]);
    }
}
