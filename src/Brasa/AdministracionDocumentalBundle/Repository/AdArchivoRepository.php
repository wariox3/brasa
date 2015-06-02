<?php

namespace Brasa\AdministracionDocumentalBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AdArchivoRepository extends EntityRepository {
    public function listaDQL($codigoArchivoTipo, $numero) {                
        $dql   = "SELECT a FROM BrasaAdministracionDocumentalBundle:AdArchivo a "
                . "WHERE a.codigoArchivoTipoFk = " . $codigoArchivoTipo . " AND a.numero = " . $numero;
        return $dql;
    }                            
}