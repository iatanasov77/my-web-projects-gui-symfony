<?php namespace App\Controller\MyWebProjects;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    public function index()
    {
        $redirectUrl    = $this->generateUrl( 'projects' );
        
        return new RedirectResponse( $redirectUrl, 307 );
    }
    
    public function setLanguage( Request $request ): Response
    {
        $lang   = $request->attributes->get( 'lang' );
        $request->getSession()->set( '_locale', $lang );
        
        return $this->redirect( $request->headers->get( 'referer' ) );
    }
    
    public function setLocale( Request $request ): Response
    {
        $locale   = $request->attributes->get( 'locale' );
        $request->getSession()->set( '_locale', $locale );
        
        return $this->redirect( $request->headers->get( 'referer' ) );
    }
}