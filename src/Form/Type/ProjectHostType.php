<?php namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

//use App\Component\Project\Project as ProjectTypes;
use App\Component\Project\Host as HostTypes;
use App\Component\Command\PhpBrew;
use App\Entity\ProjectHost;
use App\Entity\Project;

use App\Form\Type\HostOptions\ProjectHostLampOptionType;
use App\Entity\ProjectHostOption;
use App\Form\Type\HostOptions\ProjectHostDotnetOptionType;
use App\Form\Type\HostOptions\ProjectHostTomcatOptionType;
use App\Form\Type\HostOptions\ProjectHostPythonOptionType;
use App\Form\Type\HostOptions\ProjectHostRubyOptionType;

class ProjectHostType extends AbstractType
{
    public function __construct()
    {
        $this->hostTypes    = [
            HostTypes::TYPE_LAMP            => HostTypes::TYPE_LAMP,
            HostTypes::TYPE_ASPNET_REVERSE  => HostTypes::TYPE_ASPNET_REVERSE,
            HostTypes::TYPE_JSP             => HostTypes::TYPE_JSP,
            HostTypes::TYPE_JSP_REVERSE     => HostTypes::TYPE_JSP_REVERSE,
            HostTypes::TYPE_PYTHON          => HostTypes::TYPE_PYTHON,
            HostTypes::TYPE_RUBY            => HostTypes::TYPE_RUBY,
        ];
    }
    
    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder
            ->add( 'project', EntityType::class, [
                'class'         => Project::class,
                'placeholder'   => '-- Choose a project --',
                'choice_label'  => 'name',
            ])
            ->add( 'hostType', ChoiceType::class, [ 'choices' => $this->hostTypes ] )
            
            ->add( 'host', TextType::class, [ 'label' => 'Host name'] )
            ->add( 'documentRoot', TextType::class )
            ->add( 'withSsl', CheckboxType::class, [ 'required' => false ] )
            
            //->add( 'hostOptions', ProjectHostLampOptionType::class, ['mapped' => false])
//             ->add( 'options', CollectionType::class, [
//                 'entry_type'    => ProjectHostPythonOptionType::class,
//                 'entry_options' => ['label' => false],
//                 'allow_add'     => true,
//             ]);
        ;
        
        $builder->get( 'withSsl' )
                ->addModelTransformer( new CallbackTransformer(
                    function ( $withSsl ) {
                        // transform the array to a string
                        return (boolean)$withSsl;
                    },
                    function (  $withSsl ) {
                        // transform the string back to an array
                        return (boolean)$withSsl;
                    }
                ));
    }
    
    public function configureOptions( OptionsResolver $resolver )
    {
        /*
         * The form's view data is expected to be an instance of class App\Entity\ProjectHostOption, 
         * but is an instance of class Doctrine\Common\Collections\ArrayCollection. 
         * You can avoid this error by setting the "data_class" option to null or 
         * by adding a view transformer that transforms an instance of class Doctrine\Common\Collections\ArrayCollection 
         * to an instance of App\Entity\ProjectHostOption.
         */
        $resolver->setDefaults([
            'data_class' => ProjectHost::class,
        ]);
    }
}
