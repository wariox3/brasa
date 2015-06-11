<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_examen_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuExamenTipoRepository")
 */
class RhuExamenTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_examen_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoExamenTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;            
    
    /**
     * @ORM\OneToMany(targetEntity="RhuExamenTipo", mappedBy="examenTipoRel")
     */
    protected $examenesExamenTipoRel;

    /**
     * Get codigoExamenTipoPk
     *
     * @return integer
     */
    public function getCodigoExamenTipoPk()
    {
        return $this->codigoExamenTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuExamenTipo
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
     * Constructor
     */
    public function __construct()
    {
        $this->examenesExamenTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add examenesExamenTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenTipo $examenesExamenTipoRel
     *
     * @return RhuExamenTipo
     */
    public function addExamenesExamenTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenTipo $examenesExamenTipoRel)
    {
        $this->examenesExamenTipoRel[] = $examenesExamenTipoRel;

        return $this;
    }

    /**
     * Remove examenesExamenTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenTipo $examenesExamenTipoRel
     */
    public function removeExamenesExamenTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenTipo $examenesExamenTipoRel)
    {
        $this->examenesExamenTipoRel->removeElement($examenesExamenTipoRel);
    }

    /**
     * Get examenesExamenTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExamenesExamenTipoRel()
    {
        return $this->examenesExamenTipoRel;
    }
}
