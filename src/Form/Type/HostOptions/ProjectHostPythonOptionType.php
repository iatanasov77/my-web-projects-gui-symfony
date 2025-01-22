<?php namespace App\Form\Type\HostOptions;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use App\Entity\ProjectHostOption;

class ProjectHostPythonOptionType extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder
            ->add( 'appName', TextType::class, [ 'label' => 'Application Name'] )
            ->add( 'projectPath', TextType::class, [ 'label' => 'Project Path'] )
            ->add( 'venvPath', TextType::class, [ 'label' => 'Virtual Environment Path'] )
            ->add( 'scriptAlias', TextType::class, [ 'label' => 'Script Alias (Path to wsgi.py)'] )
            ->add( 'user', TextType::class, [
                'label' => 'User',
                'empty_data' => 'apache',
                'attr' => ['style' => 'width: 100px'],
            ])
            ->add( 'group', TextType::class, [
                'label' => 'Group',
                'empty_data' => 'apache',
                'attr' => ['style' => 'width: 100px'],
            ])
            ->add( 'processes', TextType::class, [
                'label' => 'Processes',
                'empty_data' => '1',
                'attr' => ['style' => 'width: 50px'],
            ])
            ->add( 'threads', TextType::class, [
                'label' => 'Threads',
                'empty_data' => '2',
                'attr' => ['style' => 'width: 50px'],
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
