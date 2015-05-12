<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_incapacidad_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuIncapacidadTipoRepository")
 */
class RhuIncapacidadTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_incapacidad_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoIncapacidadTipoPk;                        
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;     
    
    /**
     * @ORM\Column(name="incapacidad_general", type="boolean")
     */    
    private $incapacidadGeneral = 0;      
    
    /**
     * @ORM\OneToMany(targetEntity="RhuIncapacidad", mappedBy="incapacidadTipoRel")
     */
    protected $incapacidadesIncapacidadTipoRel;
   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->incapacidadesIncapacidadTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoIncapacidadTipoPk
     *
     * @return integer
     */
    public function getCodigoIncapacidadTipoPk()
    {
        return $this->codigoIncapacidadTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuIncapacidadTipo
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
     * Set incapacidadGeneral
     *
     * @param boolean $incapacidadGeneral
     *
     * @return RhuIncapacidadTipo
     */
    public function setIncapacidadGeneral($incapacidadGeneral)
    {
        $this->incapacidadGeneral = $incapacidadGeneral;

        return $this;
    }

    /**
     * Get incapacidadGeneral
     *
     * @return boolean
     */
    public function getIncapacidadGeneral()
    {
        return $this->incapacidadGeneral;
    }

    /**
     * Add incapacidadesIncapacidadTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesIncapacidadTipoRel
     *
     * @return RhuIncapacidadTipo
     */
    public function addIncapacidadesIncapacidadTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesIncapacidadTipoRel)
    {
        $this->incapacidadesIncapacidadTipoRel[] = $incapacidadesIncapacidadTipoRel;

        return $this;
    }

    /**
     * Remove incapacidadesIncapacidadTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesIncapacidadTipoRel
     */
    public function removeIncapacidadesIncapacidadTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesIncapacidadTipoRel)
    {
        $this->incapacidadesIncapacidadTipoRel->removeElement($incapacidadesIncapacidadTipoRel);
    }

    /**
     * Get incapacidadesIncapacidadTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIncapacidadesIncapacidadTipoRel()
    {
        return $this->incapacidadesIncapacidadTipoRel;
    }
}
