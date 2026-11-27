<?php namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use daddl3\SymfonyCKEditor5WebpackViteBundle\Form\Ckeditor5TextareaType;

use Vankosoft\CmsBundle\Form\Traits\FosCKEditor4Config;
use App\Entity\GrafanaDatasourceExporter;
use App\Entity\GrafanaDatasource;

class GrafanaDatasourceExporterType extends AbstractType
{
    use FosCKEditor4Config;
    
    /** @var string */
    private $useCkEditor;
    
    /** @var string */
    private $ckeditor5Editor;
    
    public function __construct(
        string $useCkEditor,
        string $ckeditor5Editor
    ) {
        $this->useCkEditor      = $useCkEditor;
        $this->ckeditor5Editor  = $ckeditor5Editor;
    }
    
    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder
            ->add( 'name', TextType::class, [
                'label'                 => 'vs_application.form.name',
                'translation_domain'    => 'VSApplicationBundle',
            ])
            
            ->add( 'datasource', EntityType::class, [
                'label'                 => 'Datasource',
                'placeholder'           => '-- Select a Datasource --',
                
                'class'                 => GrafanaDatasource::class,
                'choice_label'          => 'name',
            ])
            
            ->add( 'link', TextType::class, [
                'label'                 => 'Link',
                'required'              => false,
            ])
        ;
        
        if ( $this->useCkEditor == '5' ) {
            $builder->add( 'description', Ckeditor5TextareaType::class, [
                'label'                 => 'Description',
                'attr' => [
                    'data-ckeditor5-config' => 'devpage'
                ],
                'required'              => false,
            ]);
        } else {
            $builder->add( 'description', CKEditorType::class, [
                'label'                 => 'Description',
                // 'config'                => $this->ckEditorConfig( $options ),
                'required'              => false,
            ]);
        }
    }
    
    public function configureOptions( OptionsResolver $resolver )
    {
        $resolver->setDefaults([
            'data_class' => GrafanaDatasourceExporter::class,
        ]);
    }
}