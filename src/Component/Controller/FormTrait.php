<?php
namespace App\Component\Controller;

trait FormTrait
{
    public function getFormErrors( $form )
    {
        $errors = [];
        
        foreach ($form->getErrors() as $key => $error) {
            if ($form->isRoot()) {
                $errors['#'][] = $error->getMessage();
            } else {
                $errors[] = $error->getMessage();
            }
        }
        
        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $msg                        = (string) $child->getErrors(true, false);
                $errors[$child->getName()]  = str_replace( 'ERROR', $child->getConfig()->getOption( 'label' ), $msg );
            }
        }
        
        return $errors;
    }
}
