<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ctb_registros_tipos")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbRegistrosTiposRepository")
 */
class CtbRegistrosTipos
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_registro_tipo_pk", type="smallint")
     */        
    private $codigoRegistroTipoPk;                      
    
    /**
     * @ORM\Column(name="nombre", type="string", length=20)
     */     
    private $nombre;      
    


    /**
     * Get codigoRegistroTipoPk
     *
     * @return smallint 
     */
    public function getCodigoRegistroTipoPk()
    {
        return $this->codigoRegistroTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set codigoRegistroTipoPk
     *
     * @param smallint $codigoRegistroTipoPk
     */
    public function setCodigoRegistroTipoPk($codigoRegistroTipoPk)
    {
        $this->codigoRegistroTipoPk = $codigoRegistroTipoPk;
    }
}
