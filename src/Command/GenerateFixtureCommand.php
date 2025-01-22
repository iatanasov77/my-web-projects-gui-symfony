<?php namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Yaml\Yaml;
use Psr\Container\ContainerInterface;
use Cocur\Slugify\Slugify;
use Symfony\Component\PropertyAccess\PropertyAccess;

#[AsCommand(
    name: 'vs:fixtures:generate',
    description: 'Genrate fixture yaml from records in database.',
    hidden: false
)]
class GenerateFixtureCommand extends Command
{
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
    
    protected function configure(): void
    {
        $this
            ->setHelp( 'Genrate fixture yaml from records in database.' )
        ;
    }
    
    protected function execute( InputInterface $input, OutputInterface $output ): int
    {
        $config   = Yaml::parseFile( $this->path . 'fixtures.yaml' );
        foreach ( $config['fixtures'] as $fixture ) {
            $this->generateFixtures( $fixture );
        }
        
        return Command::SUCCESS;
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
