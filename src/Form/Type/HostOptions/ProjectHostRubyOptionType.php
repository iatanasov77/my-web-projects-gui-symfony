<?php namespace App\Form\Type\HostOptions;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use App\Entity\ProjectHostOption;

class ProjectHostRubyOptionType extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder
            ->add( 'railsEnv', ChoiceType::class, [
                'label' => 'Rails Environment', 
                'choices' => [
                    'development'   => 'Development',
                    'production'    => 'Production',
                 ]
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
