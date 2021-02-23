<?php
namespace App\Component\Doctrine;

use Doctrine\ORM\Internal\Hydration\AbstractHydrator;
use PDO;

class KeyValueHydrator extends AbstractHydrator
{
    protected function hydrateAllData()
    {
        $result = [];
        $data   = $this->_stmt->fetchAll();
        var_dump( $data );
        foreach ( $data as $d )
        {
            $result[$d[0]] = $d[1];
        }
        
        return $result;
    }
}
