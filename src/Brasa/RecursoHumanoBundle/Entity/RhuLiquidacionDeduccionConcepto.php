<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_liquidacion_deduccion_concepto")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuLiquidacionDeduccionConceptoRepository")
 */
class RhuLiquidacionDeduccionConcepto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_liquidacion_deduccion_concepto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoLiquidacionDeduccionConceptoPk;    
    
    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */    
    private $nombre;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuLiquidacionDeduccion", mappedBy="liquidacionDeduccionConceptoRel")
     */
    protected $liquidacionesDeduccionesLiquidacionDeduccionConceptoRel;     
        
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->liquidacionesDeduccionesLiquidacionDeduccionConceptoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoLiquidacionDeduccionConceptoPk
     *
     * @return integer
     */
    public function getCodigoLiquidacionDeduccionConceptoPk()
    {
        return $this->codigoLiquidacionDeduccionConceptoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuLiquidacionDeduccionConcepto
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
     * Add liquidacionesDeduccionesLiquidacionDeduccionConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionDeduccion $liquidacionesDeduccionesLiquidacionDeduccionConceptoRel
     *
     * @return RhuLiquidacionDeduccionConcepto
     */
    public function addLiquidacionesDeduccionesLiquidacionDeduccionConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionDeduccion $liquidacionesDeduccionesLiquidacionDeduccionConceptoRel)
    {
        $this->liquidacionesDeduccionesLiquidacionDeduccionConceptoRel[] = $liquidacionesDeduccionesLiquidacionDeduccionConceptoRel;

        return $this;
    }

    /**
     * Remove liquidacionesDeduccionesLiquidacionDeduccionConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionDeduccion $liquidacionesDeduccionesLiquidacionDeduccionConceptoRel
     */
    public function removeLiquidacionesDeduccionesLiquidacionDeduccionConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionDeduccion $liquidacionesDeduccionesLiquidacionDeduccionConceptoRel)
    {
        $this->liquidacionesDeduccionesLiquidacionDeduccionConceptoRel->removeElement($liquidacionesDeduccionesLiquidacionDeduccionConceptoRel);
    }

    /**
     * Get liquidacionesDeduccionesLiquidacionDeduccionConceptoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLiquidacionesDeduccionesLiquidacionDeduccionConceptoRel()
    {
        return $this->liquidacionesDeduccionesLiquidacionDeduccionConceptoRel;
    }
}
