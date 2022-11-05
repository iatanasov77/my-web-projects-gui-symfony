<?php namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use App\Component\Globals;
use App\Entity\VagrantMachine;

class VagrantMachineType extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder
            
            ->add( 'machinesGroup', ChoiceType::class, [ 'choices' => array_flip( Globals::VAGRANT_MACHINE_GROUPS ) ] )
            
            ->add( 'name', TextType::class, [ 'label' => 'Machine Name'] )
            ->add( 'description', TextType::class, [ 'label' => 'Machine Description'] )
            ->add( 'ipAddress', TextType::class, [ 'label' => 'IP Address'] )
        ;
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
            'data_class' => VagrantMachine::class,
        ]);
    }
}
