<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_seleccion_motivo_cierre")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSeleccionMotivoCierreRepository")
 */
class RhuSeleccionMotivoCierre
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_motivo_cierre_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSeleccionMotivoCierrePk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre; 
    
    

    /**
     * Get codigoSeleccionMotivoCierrePk
     *
     * @return integer
     */
    public function getCodigoSeleccionMotivoCierrePk()
    {
        return $this->codigoSeleccionMotivoCierrePk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuSeleccionMotivoCierre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
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
