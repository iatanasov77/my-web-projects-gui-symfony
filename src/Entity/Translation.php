<?php  namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use VS\ApplicationBundle\Model\Translation as BaseTranslation;

/**
 * @ORM\Table(name="VSAPP_Translations")
 * @ORM\Entity
 */
class Translation extends BaseTranslation
{
    
}
