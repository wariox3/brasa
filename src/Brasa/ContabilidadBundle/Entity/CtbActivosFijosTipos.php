<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ctb_activos_fijos_tipos")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbActivosFijosTiposRepository")
 */
class CtbActivosFijosTipos
{
    /** 
     * @ORM\Id
     * @ORM\Column(name="codigo_activo_fijo_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */    
    private $codigoActivoFijoTipoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */    
    private $nombre;



    /**
     * Get codigoActivoFijoTipoPk
     *
     * @return integer 
     */
    public function getCodigoActivoFijoTipoPk()
    {
        return $this->codigoActivoFijoTipoPk;
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
}
