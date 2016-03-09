<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_dotacion_elemento")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuDotacionElementoRepository")
 */
class RhuDotacionElemento
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_dotacion_elemento_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDotacionElementoPk;                                        
    
    /**
     * @ORM\Column(name="dotacion", type="string", nullable=true)
     */
    private $dotacion;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuDotacionDetalle", mappedBy="dotacionElementoRel")
     */
    protected $elementosDotacionesDetalleDotacionElementoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuDotacionCargo", mappedBy="dotacionElementoRel")
     */
    protected $dotacionesCargosDotacionElementoRel;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->elementosDotacionesDetalleDotacionElementoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->dotacionesCargosDotacionElementoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoDotacionElementoPk
     *
     * @return integer
     */
    public function getCodigoDotacionElementoPk()
    {
        return $this->codigoDotacionElementoPk;
    }

    /**
     * Set dotacion
     *
     * @param string $dotacion
     *
     * @return RhuDotacionElemento
     */
    public function setDotacion($dotacion)
    {
        $this->dotacion = $dotacion;

        return $this;
    }

    /**
     * Get dotacion
     *
     * @return string
     */
    public function getDotacion()
    {
        return $this->dotacion;
    }

    /**
     * Add elementosDotacionesDetalleDotacionElementoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDotacionDetalle $elementosDotacionesDetalleDotacionElementoRel
     *
     * @return RhuDotacionElemento
     */
    public function addElementosDotacionesDetalleDotacionElementoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDotacionDetalle $elementosDotacionesDetalleDotacionElementoRel)
    {
        $this->elementosDotacionesDetalleDotacionElementoRel[] = $elementosDotacionesDetalleDotacionElementoRel;

        return $this;
    }

    /**
     * Remove elementosDotacionesDetalleDotacionElementoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDotacionDetalle $elementosDotacionesDetalleDotacionElementoRel
     */
    public function removeElementosDotacionesDetalleDotacionElementoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDotacionDetalle $elementosDotacionesDetalleDotacionElementoRel)
    {
        $this->elementosDotacionesDetalleDotacionElementoRel->removeElement($elementosDotacionesDetalleDotacionElementoRel);
    }

    /**
     * Get elementosDotacionesDetalleDotacionElementoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getElementosDotacionesDetalleDotacionElementoRel()
    {
        return $this->elementosDotacionesDetalleDotacionElementoRel;
    }

    /**
     * Add dotacionesCargosDotacionElementoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDotacionCargo $dotacionesCargosDotacionElementoRel
     *
     * @return RhuDotacionElemento
     */
    public function addDotacionesCargosDotacionElementoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDotacionCargo $dotacionesCargosDotacionElementoRel)
    {
        $this->dotacionesCargosDotacionElementoRel[] = $dotacionesCargosDotacionElementoRel;

        return $this;
    }

    /**
     * Remove dotacionesCargosDotacionElementoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDotacionCargo $dotacionesCargosDotacionElementoRel
     */
    public function removeDotacionesCargosDotacionElementoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDotacionCargo $dotacionesCargosDotacionElementoRel)
    {
        $this->dotacionesCargosDotacionElementoRel->removeElement($dotacionesCargosDotacionElementoRel);
    }

    /**
     * Get dotacionesCargosDotacionElementoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDotacionesCargosDotacionElementoRel()
    {
        return $this->dotacionesCargosDotacionElementoRel;
    }
}
