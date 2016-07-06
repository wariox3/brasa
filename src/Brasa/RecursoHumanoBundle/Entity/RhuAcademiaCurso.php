<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_academia_curso")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuAcademiaCursoRepository")
 */
class RhuAcademiaCurso
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_academia_curso_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoAcademiaCursoPk;
    
    /**
     * @ORM\Column(name="nit", type="string", length=10, nullable=true)
     */    
    private $nit;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;
    
    /**
     * @ORM\Column(name="sede", type="string", length=60, nullable=true)
     */    
    private $sede;
    
    /**
     * @ORM\Column(name="direccion", type="string", length=80, nullable=true)
     */    
    private $direccion;
    
    /**
     * @ORM\Column(name="telefono", type="string", length=15, nullable=true)
     */    
    private $telefono;
    
    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadFk;
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuAcademiaCursosCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;
    
    

    /**
     * Get codigoAcademiaCursoPk
     *
     * @return integer
     */
    public function getCodigoAcademiaCursoPk()
    {
        return $this->codigoAcademiaCursoPk;
    }

    /**
     * Set nit
     *
     * @param string $nit
     *
     * @return RhuAcademiaCurso
     */
    public function setNit($nit)
    {
        $this->nit = $nit;

        return $this;
    }

    /**
     * Get nit
     *
     * @return string
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuAcademiaCurso
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
     * Set sede
     *
     * @param string $sede
     *
     * @return RhuAcademiaCurso
     */
    public function setSede($sede)
    {
        $this->sede = $sede;

        return $this;
    }

    /**
     * Get sede
     *
     * @return string
     */
    public function getSede()
    {
        return $this->sede;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return RhuAcademiaCurso
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return RhuAcademiaCurso
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set codigoCiudadFk
     *
     * @param integer $codigoCiudadFk
     *
     * @return RhuAcademiaCurso
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
     * @return RhuAcademiaCurso
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
}
