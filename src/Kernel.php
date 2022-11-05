<?php namespace App;

use Vankosoft\ApplicationBundle\Component\Application\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    const VERSION   = '1.9.1';
    const APP_ID    = 'my_projects';
    
    /**
     * {@inheritDoc}
     * @see \Symfony\Component\HttpKernel\Kernel::getProjectDir()
     *
     * READ HERE: https://symfony.com/doc/current/reference/configuration/kernel.html#project-directory
     */
    public function getProjectDir(): string
    {
        return \dirname( __DIR__ );
    }
    
    protected function __getConfigDir(): string
    {
        return $this->getProjectDir() . '/config';
    }
}
