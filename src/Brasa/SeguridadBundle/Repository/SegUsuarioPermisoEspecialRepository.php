<?php

namespace Brasa\SeguridadBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SegUsuarioPermisoEspecialRepository extends EntityRepository {

    public function permisoEspecial($arUsuario, $codigoPermisoEspecial, $arrRoles = NULL) {        
        $em = $this->getEntityManager();
        $arrRoles = $arUsuario->getRoles();
        $boolAdministrador = false;
        if($arrRoles) {
            foreach ($arrRoles as $rol) {
                if($rol == 'ROLE_ADMIN') {
                    $boolAdministrador = 1;
                }
            }            
        }
        $boolPermiso = false;
        if($boolAdministrador == 1) {
          $boolPermiso = 1;  
        } else {
            $arUsuarioPermisoEspecial = $em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->findOneBy(array('codigoUsuarioFk' => $arUsuario->getId(), 'codigoPermisoEspecialFk' => $codigoPermisoEspecial));
            if(count($arUsuarioPermisoEspecial) > 0) {
                if($arUsuarioPermisoEspecial->getPermitir() == 1) {
                    $boolPermiso = true;
                }
            }            
        }                        
        return $boolPermiso;        
    }
}