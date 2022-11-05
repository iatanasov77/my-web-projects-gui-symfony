<?php namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Persistence\ManagerRegistry;

use App\Component\Globals;
use App\Form\Type\VagrantMachineType;
use App\Entity\VagrantMachine;

class VagrantMachinesController extends AbstractController
{
    /** @var ManagerRegistry */
    protected ManagerRegistry $doctrine;
    
    public function __construct( ManagerRegistry $doctrine )
    {
        $this->doctrine = $doctrine;
    }
    
    /**
     * @Route("/vagrant-machines", name="vagrant-machines")
     */
    public function index( Request $request )
    {
        $repo           = $this->doctrine->getRepository( VagrantMachine::class );
        $formMachine    = $this->_machineForm( new VagrantMachine() );
        
        return $this->render( 'pages/vagrant_machines.html.twig', [
            'machines'      => $repo->findAll(),
            'formMachine'   => $formMachine->createView(),
            'machineGroups' => Globals::VAGRANT_MACHINE_GROUPS,
        ]);
    }
    
    /**
     * @Route("/vagrant-machines/edit/{id}", name="vagrant-machines-edit-form")
     */
    public function editForm( $id, Request $request )
    {
        $repository = $this->doctrine->getRepository( VagrantMachine::class );
        $machine    = $id ? $repository->find( $id ) : new VagrantMachine();
        
        return $this->render( 'pages/vagrant_machine_form.html.twig', [
            'form'      => $this->_machineForm( $machine )->createView(),
            'machine'   => $machine
        ]);
    }
    
    /**
     * @Route("/vagrant-machines/create/{id}", name="vagrant-machines-create")
     */
    public function create( $id, Request $request )
    {
        $status     = Globals::STATUS_ERROR;
        $errors     = [];
        
        $repo       = $this->doctrine->getRepository( VagrantMachine::class );
        $machine    = $id ? $repo->find( $id ) : new VagrantMachine();
        $form       = $this->_machineForm( $machine );
        
        $form->handleRequest( $request );
        if ( $form->isSubmitted() ) {
            $em         = $this->doctrine->getManager();
            $machine    = $form->getData();
            
            $em->persist( $machine );
            $em->flush();
            
            $status     = Globals::STATUS_OK;
        } else {
            foreach ( $form->getErrors( true, false ) as  $error) {
                // My personnal need was to get translatable messages
                // $errors[] = $this->trans($error->current()->getMessage());
                
                $errors[$error->current()->getCause()->getPropertyPath()] = $error->current()->getMessage();
            }
        }
        
        $response   = [
            'status'    => $status,
            'errors'    => $errors
        ];
        return new JsonResponse( $response );
    }
    
    /**
     * @Route("/vagrant-machines/{id}/delete", name="vagrant-machines-delete-machine")
     */
    public function delete( $id, Request $request )
    {
        $repo       = $this->doctrine->getRepository( VagrantMachine::class );
        $machine    = $repo->findOneBy( ['id' => $id] );
        
        if ( $machine ) {
            $em = $this->doctrine->getManager();
            
            $em->remove( $machine );
            $em->flush();
        }
        
        $response   = [
            'status'    => Globals::STATUS_OK,
            'errors'    => [],
        ];
        return new JsonResponse( $response );
    }
    
    private function _machineForm( VagrantMachine $vagrantMachine )
    {
        $form   = $this->createForm( VagrantMachineType::class, $vagrantMachine, [
            'action' => $this->generateUrl( 'vagrant-machines-create', ['id' => $vagrantMachine->getId() ?: 0] ),
            'method' => 'POST'
        ]);
        
        return $form;
    }
}
