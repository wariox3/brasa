<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ctb_movimientos_tipos")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbMovimientosTiposRepository")
 */
class CtbMovimientosTipos
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_movimiento_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoMovimientoTipoPk;        
    
    /**
     * @ORM\Column(name="nombre_movimiento_tipo", type="string", length=40)
     */     
    private $nombreMovimientoTipo;       

    /**
     * @ORM\Column(name="consecutivo", type="integer")
     */        
    private $consecutivo = 1;     


    /**
     * Get codigoMovimientoTipoPk
     *
     * @return integer 
     */
    public function getCodigoMovimientoTipoPk()
    {
        return $this->codigoMovimientoTipoPk;
    }

    /**
     * Set nombreMovimientoTipo
     *
     * @param string $nombreMovimientoTipo
     */
    public function setNombreMovimientoTipo($nombreMovimientoTipo)
    {
        $this->nombreMovimientoTipo = $nombreMovimientoTipo;
    }

    /**
     * Get nombreMovimientoTipo
     *
     * @return string 
     */
    public function getNombreMovimientoTipo()
    {
        return $this->nombreMovimientoTipo;
    }

    /**
     * Set consecutivo
     *
     * @param integer $consecutivo
     */
    public function setConsecutivo($consecutivo)
    {
        $this->consecutivo = $consecutivo;
    }

    /**
     * Get consecutivo
     *
     * @return integer 
     */
    public function getConsecutivo()
    {
        return $this->consecutivo;
    }
}
