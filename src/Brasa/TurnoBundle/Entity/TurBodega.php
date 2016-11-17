<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_bodega")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurBodegaRepository")
 */
class TurBodega
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_bodega_pk", type="string", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoBodegaPk;                
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    
    private $nombre;
                                       
    

    /**
     * Get codigoBodegaPk
     *
     * @return string
     */
    public function getCodigoBodegaPk()
    {
        return $this->codigoBodegaPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurBodega
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
