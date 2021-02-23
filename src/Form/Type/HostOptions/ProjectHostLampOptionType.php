<?php namespace App\Form\Type\HostOptions;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use App\Component\Command\PhpBrew;
use App\Entity\ProjectHostOption;

class ProjectHostLampOptionType extends AbstractType
{
    public function __construct( PhpBrew $phpBrew )
    {
        $this->phpVersions  = ['default' => 'default'];
        $installedVersions  = $phpBrew->getInstalledVersions();
        foreach( array_keys( $installedVersions ) as $choice ) {
            $version                        = ltrim( $choice, 'php-' );
            $this->phpVersions[$version]    = $version;
        }
    }
    
    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder
            ->add( 'phpVersion', ChoiceType::class, [
                'label' => 'PHP Version',
                'choices' => $this->phpVersions
            ])
        ;
    }
    
    public function configureOptions( OptionsResolver $resolver )
    {
        $resolver->setDefaults([
            //'data_class' => ProjectHostOption::class,
            'data_class' => null,
        ]);
    }
}
