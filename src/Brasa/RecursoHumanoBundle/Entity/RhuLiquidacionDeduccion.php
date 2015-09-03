<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_liquidacion_deduccion")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuLiquidacionDeduccionRepository")
 */
class RhuLiquidacionDeduccion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_liquidacion_deduccion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoLiquidacionDeduccionPk;
    
    /**
     * @ORM\Column(name="codigo_liquidacion_fk", type="integer", nullable=true)
     */    
    private $codigoLiquidacionFk;
    
    /**
     * @ORM\Column(name="codigo_credito_fk", type="integer", nullable=true)
     */    
    private $codigoCreditoFk;
    
    /**
     * @ORM\Column(name="codigo_liquidacion_deduccion_concepto_fk", type="integer", nullable=true)
     */    
    private $codigoLiquidacionDeduccionConceptoFk;    
    
    /**
     * @ORM\Column(name="vr_deduccion", type="float")
     */
    private $vrDeduccion = 0;         
    
    /**
     * @ORM\Column(name="detalle", type="string", length=250, nullable=true)
     */    
    private $detalle;     
        
    /**
     * @ORM\ManyToOne(targetEntity="RhuLiquidacion", inversedBy="liquidacionesDeduccionesLiquidacionRel")
     * @ORM\JoinColumn(name="codigo_liquidacion_fk", referencedColumnName="codigo_liquidacion_pk")
     */
    protected $liquidacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuCredito", inversedBy="liquidacionesDeduccionesCreditoRel")
     * @ORM\JoinColumn(name="codigo_credito_fk", referencedColumnName="codigo_credito_pk")
     */
    protected $creditoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuLiquidacionDeduccionConcepto", inversedBy="liquidacionesDeduccionesLiquidacionDeduccionConceptoRel")
     * @ORM\JoinColumn(name="codigo_liquidacion_deduccion_concepto_fk", referencedColumnName="codigo_liquidacion_deduccion_concepto_pk")
     */
    protected $liquidacionDeduccionConceptoRel;    



    /**
     * Get codigoLiquidacionDeduccionPk
     *
     * @return integer
     */
    public function getCodigoLiquidacionDeduccionPk()
    {
        return $this->codigoLiquidacionDeduccionPk;
    }

    /**
     * Set codigoLiquidacionFk
     *
     * @param integer $codigoLiquidacionFk
     *
     * @return RhuLiquidacionDeduccion
     */
    public function setCodigoLiquidacionFk($codigoLiquidacionFk)
    {
        $this->codigoLiquidacionFk = $codigoLiquidacionFk;

        return $this;
    }

    /**
     * Get codigoLiquidacionFk
     *
     * @return integer
     */
    public function getCodigoLiquidacionFk()
    {
        return $this->codigoLiquidacionFk;
    }

    /**
     * Set codigoCreditoFk
     *
     * @param integer $codigoCreditoFk
     *
     * @return RhuLiquidacionDeduccion
     */
    public function setCodigoCreditoFk($codigoCreditoFk)
    {
        $this->codigoCreditoFk = $codigoCreditoFk;

        return $this;
    }

    /**
     * Get codigoCreditoFk
     *
     * @return integer
     */
    public function getCodigoCreditoFk()
    {
        return $this->codigoCreditoFk;
    }

    /**
     * Set codigoLiquidacionDeduccionConceptoFk
     *
     * @param integer $codigoLiquidacionDeduccionConceptoFk
     *
     * @return RhuLiquidacionDeduccion
     */
    public function setCodigoLiquidacionDeduccionConceptoFk($codigoLiquidacionDeduccionConceptoFk)
    {
        $this->codigoLiquidacionDeduccionConceptoFk = $codigoLiquidacionDeduccionConceptoFk;

        return $this;
    }

    /**
     * Get codigoLiquidacionDeduccionConceptoFk
     *
     * @return integer
     */
    public function getCodigoLiquidacionDeduccionConceptoFk()
    {
        return $this->codigoLiquidacionDeduccionConceptoFk;
    }

    /**
     * Set vrDeduccion
     *
     * @param float $vrDeduccion
     *
     * @return RhuLiquidacionDeduccion
     */
    public function setVrDeduccion($vrDeduccion)
    {
        $this->vrDeduccion = $vrDeduccion;

        return $this;
    }

    /**
     * Get vrDeduccion
     *
     * @return float
     */
    public function getVrDeduccion()
    {
        return $this->vrDeduccion;
    }

    /**
     * Set detalle
     *
     * @param string $detalle
     *
     * @return RhuLiquidacionDeduccion
     */
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;

        return $this;
    }

    /**
     * Get detalle
     *
     * @return string
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * Set liquidacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion $liquidacionRel
     *
     * @return RhuLiquidacionDeduccion
     */
    public function setLiquidacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion $liquidacionRel = null)
    {
        $this->liquidacionRel = $liquidacionRel;

        return $this;
    }

    /**
     * Get liquidacionRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacion
     */
    public function getLiquidacionRel()
    {
        return $this->liquidacionRel;
    }

    /**
     * Set creditoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCredito $creditoRel
     *
     * @return RhuLiquidacionDeduccion
     */
    public function setCreditoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCredito $creditoRel = null)
    {
        $this->creditoRel = $creditoRel;

        return $this;
    }

    /**
     * Get creditoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCredito
     */
    public function getCreditoRel()
    {
        return $this->creditoRel;
    }

    /**
     * Set liquidacionDeduccionConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionDeduccionConcepto $liquidacionDeduccionConceptoRel
     *
     * @return RhuLiquidacionDeduccion
     */
    public function setLiquidacionDeduccionConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionDeduccionConcepto $liquidacionDeduccionConceptoRel = null)
    {
        $this->liquidacionDeduccionConceptoRel = $liquidacionDeduccionConceptoRel;

        return $this;
    }

    /**
     * Get liquidacionDeduccionConceptoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionDeduccionConcepto
     */
    public function getLiquidacionDeduccionConceptoRel()
    {
        return $this->liquidacionDeduccionConceptoRel;
    }
}
