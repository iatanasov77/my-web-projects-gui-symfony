<?php namespace App\Form\Type\HostOptions;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use App\Entity\ProjectHostOption;

class ProjectHostTomcatOptionType extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder
        ->add( 'tomcatPort', TextType::class, [
                'label' => 'Tomcat Port',
                'empty_data' => '8080',
                'attr' => ['style' => 'width: 50px']
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
