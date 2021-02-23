<?php namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

use App\Entity\Project;
use App\Entity\Category;

class ProjectInstallManualType extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder
            ->add( 'installManual', CKEditorType::class, [
                'label'     => 'Install Manual',
                'config'    => ['uiColor' => '#ffffff'],
            ])
            
            ->add( 'btnSave', SubmitType::class, ['label' => 'Save'] )
            ->add( 'btnCancel', ButtonType::class, ['label' => 'Cancel'] )
        ;
    }
    
    public function configureOptions( OptionsResolver $resolver )
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
