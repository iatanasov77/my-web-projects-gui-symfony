<?php namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;
use Cocur\Slugify\Slugify;
use Symfony\Component\PropertyAccess\PropertyAccess;

class GenerateFixtureCommand extends Command
{
    protected static $defaultName = 'vs:fixtures:generate';
    
    protected $doctrine;
    protected $path;
    protected $accessor;
    
    public function __construct( ContainerInterface $container )
    {
        parent::__construct();
        
        $this->doctrine = $container->get( 'doctrine' );
        $this->path     = __DIR__ . '/../../config/fixtures/';
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }
    
    protected function configure()
    {
        $this
            ->setDescription( 'Genrate fixture yaml from records in database.' )
            ->setHelp( 'Genrate fixture yaml from records in database.' )
        ;
    }
    
    protected function execute( InputInterface $input, OutputInterface $output )
    {
        $config   = Yaml::parseFile( $this->path . 'fixtures.yaml' );
        foreach ( $config['fixtures'] as $fixture ) {
            $this->generateFixtures( $fixture );
        }
    }
    
    protected function generateFixtures( $fixture )
    {
        $em         =   
        $projects   = $this->doctrine->getRepository( $fixture['entity'] )->findAll();
        $slugify    = new Slugify();
        
        $meta       = $em->getClassMetadata( $fixture['entity'] );
        $cols       = $meta->getColumnNames();
        $primary    = $meta->getSingleIdentifierFieldName();
        
        $fixtures   = [];
        foreach ( $projects as $pr ) {
            $fixtureId  = $slugify->slugify( $this->accessor->getValue( $pr, $fixture['mainField'] ) );
            $fixtures[ $fixtureId ] = [];
            foreach ( $cols as $c ) {
                if ( $c == $primary )
                    continue;
                    
                $fixtures[$fixtureId][$c] = $this->accessor->getValue( $pr, $c );
            }
        }
        
        $yaml = Yaml::dump( $fixtures );
        file_put_contents( $this->path . $fixture['name'] . '.yaml', $yaml );
    }
}
