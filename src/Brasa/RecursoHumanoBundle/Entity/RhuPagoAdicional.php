<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_pago_adicional")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPagoAdicionalRepository")
 */
class RhuPagoAdicional
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_adicional_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPagoAdicionalPk;         
        
    /**
     * @ORM\Column(name="codigo_pago_concepto_fk", type="integer", nullable=true)
     */    
    private $codigoPagoConceptoFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;       
    
    /**
     * @ORM\Column(name="cantidad", type="float")
     */
    private $cantidad = 0; 
    
    /**
     * @ORM\Column(name="valor", type="float")
     */
    private $valor = 0;     
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk; 
    
    /**
     * @ORM\Column(name="codigo_programacion_pago_fk", type="integer", nullable=true)
     */    
    private $codigoProgramacionPagoFk;    
    
    /**     
     * @ORM\Column(name="permanente", type="boolean")
     */    
    private $permanente = 0;     
    
    /**     
     * @ORM\Column(name="pagoAplicado", type="boolean")
     */    
    private $pagoAplicado = 0;    
    
    /**
     * @ORM\Column(name="detalle", type="string", length=250, nullable=true)
     */    
    private $detalle;    
    
    /**
     * @ORM\Column(name="codigo_pago_adicional_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoPagoAdicionalTipoFk;
    
    /**
     * @ORM\Column(name="codigo_pago_adicional_subtipo_fk", type="integer", nullable=true)
     */    
    private $codigoPagoAdicionalSubtipoFk;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuPagoAdicionalTipo", inversedBy="pagosAdicionalesPagoAdicionalTipoRel")
     * @ORM\JoinColumn(name="codigo_pago_adicional_tipo_fk", referencedColumnName="codigo_pago_adicional_tipo_pk")
     */
    protected $pagoAdicionalTipoRel; 
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuPagoAdicionalSubtipo", inversedBy="pagosAdicionalesPagoAdicionalSubtipoRel")
     * @ORM\JoinColumn(name="codigo_pago_adicional_subtipo_fk", referencedColumnName="codigo_pago_adicional_subtipo_pk")
     */
    protected $pagoAdicionalSubtipoRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="pagosAdicionalesCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuPagoConcepto", inversedBy="pagosAdicionalesPagoConceptoRel")
     * @ORM\JoinColumn(name="codigo_pago_concepto_fk", referencedColumnName="codigo_pago_concepto_pk")
     */
    protected $pagoConceptoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="pagosAdicionalesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuProgramacionPago", inversedBy="pagosAdicionalesProgramacionPagoRel")
     * @ORM\JoinColumn(name="codigo_programacion_pago_fk", referencedColumnName="codigo_programacion_pago_pk")
     */
    protected $programacionPagoRel;    
    

    /**
     * Get codigoPagoAdicionalPk
     *
     * @return integer
     */
    public function getCodigoPagoAdicionalPk()
    {
        return $this->codigoPagoAdicionalPk;
    }

    /**
     * Set codigoPagoConceptoFk
     *
     * @param integer $codigoPagoConceptoFk
     *
     * @return RhuPagoAdicional
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
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuPagoAdicional
     */
    public function setCodigoEmpleadoFk($codigoEmpleadoFk)
    {
        $this->codigoEmpleadoFk = $codigoEmpleadoFk;

        return $this;
    }

    /**
     * Get codigoEmpleadoFk
     *
     * @return integer
     */
    public function getCodigoEmpleadoFk()
    {
        return $this->codigoEmpleadoFk;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return RhuPagoAdicional
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set valor
     *
     * @param float $valor
     *
     * @return RhuPagoAdicional
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuPagoAdicional
     */
    public function setCodigoCentroCostoFk($codigoCentroCostoFk)
    {
        $this->codigoCentroCostoFk = $codigoCentroCostoFk;

        return $this;
    }

    /**
     * Get codigoCentroCostoFk
     *
     * @return integer
     */
    public function getCodigoCentroCostoFk()
    {
        return $this->codigoCentroCostoFk;
    }

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel
     *
     * @return RhuPagoAdicional
     */
    public function setCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel = null)
    {
        $this->centroCostoRel = $centroCostoRel;

        return $this;
    }

    /**
     * Get centroCostoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto
     */
    public function getCentroCostoRel()
    {
        return $this->centroCostoRel;
    }

    /**
     * Set pagoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto $pagoConceptoRel
     *
     * @return RhuPagoAdicional
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

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuPagoAdicional
     */
    public function setEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel = null)
    {
        $this->empleadoRel = $empleadoRel;

        return $this;
    }

    /**
     * Get empleadoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado
     */
    public function getEmpleadoRel()
    {
        return $this->empleadoRel;
    }

    /**
     * Set codigoProgramacionPagoFk
     *
     * @param integer $codigoProgramacionPagoFk
     *
     * @return RhuPagoAdicional
     */
    public function setCodigoProgramacionPagoFk($codigoProgramacionPagoFk)
    {
        $this->codigoProgramacionPagoFk = $codigoProgramacionPagoFk;

        return $this;
    }

    /**
     * Get codigoProgramacionPagoFk
     *
     * @return integer
     */
    public function getCodigoProgramacionPagoFk()
    {
        return $this->codigoProgramacionPagoFk;
    }

    /**
     * Set permanente
     *
     * @param boolean $permanente
     *
     * @return RhuPagoAdicional
     */
    public function setPermanente($permanente)
    {
        $this->permanente = $permanente;

        return $this;
    }

    /**
     * Get permanente
     *
     * @return boolean
     */
    public function getPermanente()
    {
        return $this->permanente;
    }

    /**
     * Set pagoAplicado
     *
     * @param boolean $pagoAplicado
     *
     * @return RhuPagoAdicional
     */
    public function setPagoAplicado($pagoAplicado)
    {
        $this->pagoAplicado = $pagoAplicado;

        return $this;
    }

    /**
     * Get pagoAplicado
     *
     * @return boolean
     */
    public function getPagoAplicado()
    {
        return $this->pagoAplicado;
    }

    /**
     * Set programacionPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago $programacionPagoRel
     *
     * @return RhuPagoAdicional
     */
    public function setProgramacionPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago $programacionPagoRel = null)
    {
        $this->programacionPagoRel = $programacionPagoRel;

        return $this;
    }

    /**
     * Get programacionPagoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago
     */
    public function getProgramacionPagoRel()
    {
        return $this->programacionPagoRel;
    }

    /**
     * Set detalle
     *
     * @param string $detalle
     *
     * @return RhuPagoAdicional
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
     * Set codigoPagoAdicionalTipoFk
     *
     * @param integer $codigoPagoAdicionalTipoFk
     *
     * @return RhuPagoAdicional
     */
    public function setCodigoPagoAdicionalTipoFk($codigoPagoAdicionalTipoFk)
    {
        $this->codigoPagoAdicionalTipoFk = $codigoPagoAdicionalTipoFk;

        return $this;
    }

    /**
     * Get codigoPagoAdicionalTipoFk
     *
     * @return integer
     */
    public function getCodigoPagoAdicionalTipoFk()
    {
        return $this->codigoPagoAdicionalTipoFk;
    }

    /**
     * Set codigoPagoAdicionalSubtipoFk
     *
     * @param integer $codigoPagoAdicionalSubtipoFk
     *
     * @return RhuPagoAdicional
     */
    public function setCodigoPagoAdicionalSubtipoFk($codigoPagoAdicionalSubtipoFk)
    {
        $this->codigoPagoAdicionalSubtipoFk = $codigoPagoAdicionalSubtipoFk;

        return $this;
    }

    /**
     * Get codigoPagoAdicionalSubtipoFk
     *
     * @return integer
     */
    public function getCodigoPagoAdicionalSubtipoFk()
    {
        return $this->codigoPagoAdicionalSubtipoFk;
    }

    /**
     * Set pagoAdicionalTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicionalTipo $pagoAdicionalTipoRel
     *
     * @return RhuPagoAdicional
     */
    public function setPagoAdicionalTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicionalTipo $pagoAdicionalTipoRel = null)
    {
        $this->pagoAdicionalTipoRel = $pagoAdicionalTipoRel;

        return $this;
    }

    /**
     * Get pagoAdicionalTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicionalTipo
     */
    public function getPagoAdicionalTipoRel()
    {
        return $this->pagoAdicionalTipoRel;
    }

    /**
     * Set pagoAdicionalSubtipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicionalSubtipo $pagoAdicionalSubtipoRel
     *
     * @return RhuPagoAdicional
     */
    public function setPagoAdicionalSubtipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicionalSubtipo $pagoAdicionalSubtipoRel = null)
    {
        $this->pagoAdicionalSubtipoRel = $pagoAdicionalSubtipoRel;

        return $this;
    }

    /**
     * Get pagoAdicionalSubtipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicionalSubtipo
     */
    public function getPagoAdicionalSubtipoRel()
    {
        return $this->pagoAdicionalSubtipoRel;
    }
}
