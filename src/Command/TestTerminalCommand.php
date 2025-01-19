<?php namespace App\Command;

use Vankosoft\ApplicationInstalatorBundle\Command\AbstractInstallCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[AsCommand(
    name: 'my-projects:test-terminal',
    description: 'MyWebProjects Test Terminal Command.',
    hidden: false
)]
final class TestTerminalCommand extends AbstractInstallCommand
{
    protected function configure(): void
    {
        $this
            ->setHelp(<<<EOT
The <info>%command.name%</info> command allows Testing JQuery Terminal.
EOT
            )
        ;
    }
    
    protected function execute( InputInterface $input, OutputInterface $output ): int
    {
        /** @var QuestionHelper $questionHelper */
        $questionHelper     = $this->getHelper( 'question' );
        
        $question       = $this->createTestQuestion();
        $testString     = $questionHelper->ask( $input, $output, $question );
        
        $outputStyle    = new SymfonyStyle( $input, $output );
        
        $outputStyle->newLine();
        $outputStyle->writeln( 'You Write: ' . $testString );
        $outputStyle->newLine();
        
        return Command::SUCCESS;
    }
    
    private function createTestQuestion(): Question
    {
        return ( new Question( 'Test Question (Write a Test Answer): ' ) )
            ->setValidator(
                function ( $value ): string {
                    /** @var ConstraintViolationListInterface $errors */
                    $errors = $this->get( 'validator' )->validate( (string) $value, [new Length([
                        'min' => 3,
                        'max' => 50,
                        'minMessage' => 'Your application name must be at least {{ limit }} characters long',
                        'maxMessage' => 'Your application name cannot be longer than {{ limit }} characters',
                    ])]);
                    foreach ( $errors as $error ) {
                        throw new \DomainException( $error->getMessage() );
                    }
                    
                    return $value;
                }
            )
            ->setMaxAttempts( 3 )
        ;
    }
}
