<?php

namespace Brasa\GeneralBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_barrio")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenBarrioRepository")
 */
class GenBarrio
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_barrio_pk", type="integer")
     */
    private $codigoBarrioPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     * @Assert\NotNull()(message="Debe escribir un nombre")
     */
    private $nombre; 
    
    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer")
     */
    private $codigoCiudadFk;
    
    /**
     * @ORM\ManyToOne(targetEntity="GenCiudad", inversedBy="barriosRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuSeleccion", mappedBy="barrioRel")
     */
    protected $rhuSeleccionesBarrioRel;
    
 

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rhuSeleccionesBarrioRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set codigoBarrioPk
     *
     * @param integer $codigoBarrioPk
     *
     * @return GenBarrio
     */
    public function setCodigoBarrioPk($codigoBarrioPk)
    {
        $this->codigoBarrioPk = $codigoBarrioPk;

        return $this;
    }

    /**
     * Get codigoBarrioPk
     *
     * @return integer
     */
    public function getCodigoBarrioPk()
    {
        return $this->codigoBarrioPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return GenBarrio
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

    /**
     * Set codigoCiudadFk
     *
     * @param integer $codigoCiudadFk
     *
     * @return GenBarrio
     */
    public function setCodigoCiudadFk($codigoCiudadFk)
    {
        $this->codigoCiudadFk = $codigoCiudadFk;

        return $this;
    }

    /**
     * Get codigoCiudadFk
     *
     * @return integer
     */
    public function getCodigoCiudadFk()
    {
        return $this->codigoCiudadFk;
    }

    /**
     * Set ciudadRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel
     *
     * @return GenBarrio
     */
    public function setCiudadRel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel = null)
    {
        $this->ciudadRel = $ciudadRel;

        return $this;
    }

    /**
     * Get ciudadRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudad
     */
    public function getCiudadRel()
    {
        return $this->ciudadRel;
    }

    /**
     * Add rhuSeleccionesBarrioRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $rhuSeleccionesBarrioRel
     *
     * @return GenBarrio
     */
    public function addRhuSeleccionesBarrioRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $rhuSeleccionesBarrioRel)
    {
        $this->rhuSeleccionesBarrioRel[] = $rhuSeleccionesBarrioRel;

        return $this;
    }

    /**
     * Remove rhuSeleccionesBarrioRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $rhuSeleccionesBarrioRel
     */
    public function removeRhuSeleccionesBarrioRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $rhuSeleccionesBarrioRel)
    {
        $this->rhuSeleccionesBarrioRel->removeElement($rhuSeleccionesBarrioRel);
    }

    /**
     * Get rhuSeleccionesBarrioRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuSeleccionesBarrioRel()
    {
        return $this->rhuSeleccionesBarrioRel;
    }
}
