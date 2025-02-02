<?php namespace App\Controller\MyWebProjects;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Component\Project\Host as HostTypes;
use App\Form\Type\HostOptions\ProjectHostLampOptionType;
use App\Form\Type\HostOptions\ProjectHostDotnetOptionType;
use App\Form\Type\HostOptions\ProjectHostTomcatOptionType;
use App\Form\Type\HostOptions\ProjectHostPythonOptionType;
use App\Form\Type\HostOptions\ProjectHostRubyOptionType;

class VirtualHostsOptionsController extends AbstractController
{
    public function optionsForm( Request $request ): Response
    {
        $hostType   = $request->query->get( 'hostType' );
        switch ( $hostType ) {
            case HostTypes::TYPE_LAMP:
                $form   = $this->createForm( ProjectHostLampOptionType::class )->createView();
                return $this->render('Pages/virtual_hosts/forms/lamp.html.twig', ['form' => $form]);
                break;
            case HostTypes::TYPE_ASPNET_REVERSE:
                $form   = $this->createForm( ProjectHostDotnetOptionType::class )->createView();
                return $this->render('Pages/virtual_hosts/forms/dotnet.html.twig', ['form' => $form]);
                break;
            case HostTypes::TYPE_JSP:
                $form   = $this->createForm( ProjectHostTomcatOptionType::class )->createView();
                return $this->render('Pages/virtual_hosts/forms/jsp.html.twig', ['form' => $form]);
                break;
            case HostTypes::TYPE_JSP_REVERSE:
                $form   = $this->createForm( ProjectHostTomcatOptionType::class )->createView();
                return $this->render('Pages/virtual_hosts/forms/jsp.html.twig', ['form' => $form]);
                break;
            case HostTypes::TYPE_PYTHON:
                $form   = $this->createForm( ProjectHostPythonOptionType::class )->createView();
                return $this->render('Pages/virtual_hosts/forms/python.html.twig', ['form' => $form]);
                break;
            case HostTypes::TYPE_RUBY:
                $form   = $this->createForm( ProjectHostRubyOptionType::class )->createView();
                return $this->render('Pages/virtual_hosts/forms/ruby.html.twig', ['form' => $form]);
                break;
        }
    }
}
