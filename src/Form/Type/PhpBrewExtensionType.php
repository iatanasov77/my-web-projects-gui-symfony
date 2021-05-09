<?php namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use App\Entity\PhpBrewExtension;

class PhpBrewExtensionType extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder
            ->add( 'name', HiddenType::class )
            ->add( 'description', TextType::class )
            ->add( 'githubRepo', TextType::class )
            ->add( 'branch', TextType::class )
        ;
    }
    
    public function configureOptions( OptionsResolver $resolver )
    {
        $resolver->setDefaults([
            'data_class' => PhpBrewExtension::class,
        ]);
    }
}
