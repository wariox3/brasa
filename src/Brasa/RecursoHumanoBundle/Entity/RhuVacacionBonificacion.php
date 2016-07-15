<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_vacacion_bonificacion")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuVacacionBonificacionRepository")
 */
class RhuVacacionBonificacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_vacacion_bonificacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoVacacionBonificacionPk;
    
    /**
     * @ORM\Column(name="codigo_vacacion_fk", type="integer", nullable=true)
     */    
    private $codigoVacacionFk;   
    
    /**
     * @ORM\Column(name="codigo_pago_concepto_fk", type="integer", nullable=true)
     */    
    private $codigoPagoConceptoFk;    
    
    /**
     * @ORM\Column(name="vr_bonificacion", type="float")
     */
    private $vrBonificacion = 0;         
    
    /**
     * @ORM\Column(name="detalle", type="string", length=250, nullable=true)
     */    
    private $detalle;     
        
    /**
     * @ORM\ManyToOne(targetEntity="RhuVacacion", inversedBy="vacacionesBonificacionesVacacionRel")
     * @ORM\JoinColumn(name="codigo_vacacion_fk", referencedColumnName="codigo_vacacion_pk")
     */
    protected $vacacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuPagoConcepto", inversedBy="vacacionesBonificacionesPagoConceptoRel")
     * @ORM\JoinColumn(name="codigo_pago_concepto_fk", referencedColumnName="codigo_pago_concepto_pk")
     */
    protected $pagoConceptoRel;    
    


    /**
     * Get codigoVacacionBonificacionPk
     *
     * @return integer
     */
    public function getCodigoVacacionBonificacionPk()
    {
        return $this->codigoVacacionBonificacionPk;
    }

    /**
     * Set codigoVacacionFk
     *
     * @param integer $codigoVacacionFk
     *
     * @return RhuVacacionBonificacion
     */
    public function setCodigoVacacionFk($codigoVacacionFk)
    {
        $this->codigoVacacionFk = $codigoVacacionFk;

        return $this;
    }

    /**
     * Get codigoVacacionFk
     *
     * @return integer
     */
    public function getCodigoVacacionFk()
    {
        return $this->codigoVacacionFk;
    }

    /**
     * Set codigoPagoConceptoFk
     *
     * @param integer $codigoPagoConceptoFk
     *
     * @return RhuVacacionBonificacion
     */
    public function setCodigoPagoConceptoFk($codigoPagoConceptoFk)
    {
        $this->codigoPagoConceptoFk = $codigoPagoConceptoFk;

        return $this;
    }

    /**
     * Get codigoPagoConceptoFk
     *
     * @return integer
     */
    public function getCodigoPagoConceptoFk()
    {
        return $this->codigoPagoConceptoFk;
    }

    /**
     * Set vrBonificacion
     *
     * @param float $vrBonificacion
     *
     * @return RhuVacacionBonificacion
     */
    public function setVrBonificacion($vrBonificacion)
    {
        $this->vrBonificacion = $vrBonificacion;

        return $this;
    }

    /**
     * Get vrBonificacion
     *
     * @return float
     */
    public function getVrBonificacion()
    {
        return $this->vrBonificacion;
    }

    /**
     * Set detalle
     *
     * @param string $detalle
     *
     * @return RhuVacacionBonificacion
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
     * Set vacacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuVacacion $vacacionRel
     *
     * @return RhuVacacionBonificacion
     */
    public function setVacacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuVacacion $vacacionRel = null)
    {
        $this->vacacionRel = $vacacionRel;

        return $this;
    }

    /**
     * Get vacacionRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuVacacion
     */
    public function getVacacionRel()
    {
        return $this->vacacionRel;
    }

    /**
     * Set pagoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto $pagoConceptoRel
     *
     * @return RhuVacacionBonificacion
     */
    public function setPagoConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto $pagoConceptoRel = null)
    {
        $this->pagoConceptoRel = $pagoConceptoRel;

        return $this;
    }

    /**
     * Get pagoConceptoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto
     */
    public function getPagoConceptoRel()
    {
        return $this->pagoConceptoRel;
    }
}
