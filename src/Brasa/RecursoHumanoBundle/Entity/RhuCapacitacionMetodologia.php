<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_capacitacion_metodologia")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCapacitacionMetodologiaRepository")
 */
class RhuCapacitacionMetodologia
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_capacitacion_metodologia_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCapacitacionMetodologiaPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;
    
    

    /**
     * Get codigoCapacitacionMetodologiaPk
     *
     * @return integer
     */
    public function getCodigoCapacitacionMetodologiaPk()
    {
        return $this->codigoCapacitacionMetodologiaPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuCapacitacionMetodologia
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
