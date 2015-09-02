<?php

namespace Brasa\InventarioBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_bodega")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvBodegaRepository")
 */
class InvBodega
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_bodega_pk", type="integer")
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
     * @return integer 
     */
    public function getCodigoBodegaPk()
    {
        return $this->codigoBodegaPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return InvBodega
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
