<?php

namespace Brasa\GeneralBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_ciudades")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenCiudadesRepository")
 */
class GenCiudades
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_ciudad_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCiudadPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     * @Assert\NotNull()(message="Debe escribir un nombre")
     */
    private $nombre;
   
    /**
     * @ORM\Column(name="codigo_departamento_fk", type="integer")
     */
    private $codigoDepartamentoFk;     

    /**
     * @ORM\ManyToOne(targetEntity="GenDepartamentos", inversedBy="ciudadesRel")
     * @ORM\JoinColumn(name="codigo_departamento_fk", referencedColumnName="codigo_departamento_pk")
     */
    protected $departamentoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="GenTercerosDirecciones", mappedBy="ciudadRel")
     */
    protected $tercerosDireccionesRel;

    public function __construct()
    {
        $this->tercerosDireccionesRel = new ArrayCollection();
    }  


    /**
     * Get codigoCiudadPk
     *
     * @return integer 
     */
    public function getCodigoCiudadPk()
    {
        return $this->codigoCiudadPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return GenCiudades
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
     * Set codigoDepartamentoFk
     *
     * @param integer $codigoDepartamentoFk
     * @return GenCiudades
     */
    public function setCodigoDepartamentoFk($codigoDepartamentoFk)
    {
        $this->codigoDepartamentoFk = $codigoDepartamentoFk;

        return $this;
    }

    /**
     * Get codigoDepartamentoFk
     *
     * @return integer 
     */
    public function getCodigoDepartamentoFk()
    {
        return $this->codigoDepartamentoFk;
    }

    /**
     * Set departamentoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenDepartamentos $departamentoRel
     * @return GenCiudades
     */
    public function setDepartamentoRel(\Brasa\GeneralBundle\Entity\GenDepartamentos $departamentoRel = null)
    {
        $this->departamentoRel = $departamentoRel;

        return $this;
    }

    /**
     * Get departamentoRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenDepartamentos 
     */
    public function getDepartamentoRel()
    {
        return $this->departamentoRel;
    }

    /**
     * Add tercerosDireccionesRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTercerosDirecciones $tercerosDireccionesRel
     * @return GenCiudades
     */
    public function addTercerosDireccionesRel(\Brasa\GeneralBundle\Entity\GenTercerosDirecciones $tercerosDireccionesRel)
    {
        $this->tercerosDireccionesRel[] = $tercerosDireccionesRel;

        return $this;
    }

    /**
     * Remove tercerosDireccionesRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTercerosDirecciones $tercerosDireccionesRel
     */
    public function removeTercerosDireccionesRel(\Brasa\GeneralBundle\Entity\GenTercerosDirecciones $tercerosDireccionesRel)
    {
        $this->tercerosDireccionesRel->removeElement($tercerosDireccionesRel);
    }

    /**
     * Get tercerosDireccionesRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTercerosDireccionesRel()
    {
        return $this->tercerosDireccionesRel;
    }
}
