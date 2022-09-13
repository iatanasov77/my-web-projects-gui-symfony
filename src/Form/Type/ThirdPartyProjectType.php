<?php namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Entity\Project;
use App\Entity\Category;

use App\Component\Project\PredefinedProject;

class ThirdPartyProjectType extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder
            ->add( 'name', TextType::class )
            ->add( 'category', EntityType::class, [
                'class'         => Category::class,
                'placeholder'   => '-- Choose a category --',
                'choice_label'  => 'name',
            ])
            ->add( 'description', TextareaType::class )
            
            
            ->add( 'predefinedType', ChoiceType::class, [
                'placeholder'   => '-- Select Predefined Project --',
                'choices'       => PredefinedProject::choices(),
            ])
            ->add(
                $builder
                    ->create( 'predefinedTypeParams', TextType::class )
                    ->addModelTransformer( new CallbackTransformer(
                        function ( $paramsAsArray ) {
                            // transform the array to a string
                            return is_array( $paramsAsArray ) ? implode( ', ', $paramsAsArray ) : $paramsAsArray;
                        },
                        function ( $paramsAsString ) {
                            // transform the string back to an array
                            return explode( ', ', $paramsAsString );
                        }
                    ))
             )
            
            ->add( 'projectRoot', TextType::class )
            ->add( 'projectUrl', TextType::class )
        ;
    }
    
    public function configureOptions( OptionsResolver $resolver )
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
