<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_descuento_adicional")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuDescuentoAdicionalRepository")
 */
class RhuDescuentoAdicional
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_descuento_adicional_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDescuentoAdicionalPk;         
        
    /**
     * @ORM\Column(name="codigo_pago_concepto_fk", type="integer", nullable=true)
     */    
    private $codigoPagoConceptoFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;       
    
    /**
     * @ORM\Column(name="cantidad", type="integer")
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
     * @ORM\Column(name="descuentoAplicado", type="boolean")
     */    
    private $descuentoAplicado = 0; 
    /**     
     * @ORM\Column(name="permanente", type="boolean")
     */    
    private $permanente = 0;             
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="descuentosAdicionalesCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuPagoConcepto", inversedBy="descuentosAdicionalesPagoConceptoRel")
     * @ORM\JoinColumn(name="codigo_pago_concepto_fk", referencedColumnName="codigo_pago_concepto_pk")
     */
    protected $pagoConceptoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="descuentosAdicionalesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuProgramacionPago", inversedBy="descuentosAdicionalesProgramacionPagoRel")
     * @ORM\JoinColumn(name="codigo_programacion_pago_fk", referencedColumnName="codigo_programacion_pago_pk")
     */
    protected $programacionPagoRel;    
    


    /**
     * Get codigoDescuentoAdicionalPk
     *
     * @return integer
     */
    public function getCodigoDescuentoAdicionalPk()
    {
        return $this->codigoDescuentoAdicionalPk;
    }

    /**
     * Set codigoPagoConceptoFk
     *
     * @param integer $codigoPagoConceptoFk
     *
     * @return RhuDescuentoAdicional
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
     * @return RhuDescuentoAdicional
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
     * @return RhuDescuentoAdicional
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
     * Set cantidadPendiente
     *
     * @param integer $cantidadPendiente
     *
     * @return RhuDescuentoAdicional
     */
    public function setCantidadPendiente($cantidadPendiente)
    {
        $this->cantidadPendiente = $cantidadPendiente;

        return $this;
    }

    /**
     * Get cantidadPendiente
     *
     * @return integer
     */
    public function getCantidadPendiente()
    {
        return $this->cantidadPendiente;
    }

    /**
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuDescuentoAdicional
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
     * Set codigoProgramacionPagoFk
     *
     * @param integer $codigoProgramacionPagoFk
     *
     * @return RhuDescuentoAdicional
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
     * Set descuentoAplicado
     *
     * @param boolean $descuentoAplicado
     *
     * @return RhuDescuentoAdicional
     */
    public function setDescuentoAplicado($descuentoAplicado)
    {
        $this->descuentoAplicado = $descuentoAplicado;

        return $this;
    }

    /**
     * Get descuentoAplicado
     *
     * @return boolean
     */
    public function getDescuentoAplicado()
    {
        return $this->descuentoAplicado;
    }

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel
     *
     * @return RhuDescuentoAdicional
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
     * @return RhuDescuentoAdicional
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
     * @return RhuDescuentoAdicional
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
     * Set programacionPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago $programacionPagoRel
     *
     * @return RhuDescuentoAdicional
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
     * Set valor
     *
     * @param float $valor
     *
     * @return RhuDescuentoAdicional
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
     * Set permanente
     *
     * @param boolean $permanente
     *
     * @return RhuDescuentoAdicional
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
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuDescuentoAdicional
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }
}
