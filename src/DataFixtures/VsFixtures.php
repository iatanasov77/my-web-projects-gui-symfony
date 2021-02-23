<?php namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\PropertyAccess\PropertyAccess;

use App\Entity\Project;

class VsFixtures extends Fixture
{
    protected $manager;
    
    public function load( ObjectManager $manager )
    {
        $this->manager  = $manager;
        $config         = Yaml::parseFile( __DIR__ . '/../../config/fixtures/fixtures.yaml' );
        
        foreach ( $config['fixtures'] as $fixture ) {
            $this->loadFixtures( $fixture );
        }
    }
    
    protected function loadFixtures( $fixture )
    {
        $fixtures   = Yaml::parseFile( __DIR__ . '/../../config/fixtures/' . $fixture['name'] . '.yaml' );
        $accessor   = PropertyAccess::createPropertyAccessor();
        
        foreach( $fixtures as $fix ) {
            $entity = new $fixture['entity']();
            
            foreach( $fix as $column => $value ) {
                if ( $value !== null ) {
                    $accessor->setValue( $entity, $column, $value ); 
                }
            }
            
            $this->manager->persist( $entity ); 
        }
        
        $this->manager->flush();
    }
}
