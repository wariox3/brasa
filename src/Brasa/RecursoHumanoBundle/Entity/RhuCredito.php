<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_credito")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCreditoRepository")
 */
class RhuCredito
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_credito_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCreditoPk;
    
    /**
     * @ORM\Column(name="codigo_credito_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoCreditoTipoFk;
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;        
    
    /**
     * @ORM\Column(name="fecha", type="date")
     */    
    private $fecha;     
    
    /**
     * @ORM\Column(name="vr_pagar", type="float")
     */
    private $vrPagar = 0;    

    /**
     * @ORM\Column(name="vr_cuota", type="float")
     */
    private $vrCuota = 0;    

    /**
     * @ORM\Column(name="saldo", type="float")
     */
    private $saldo = 0;    
    
    /**     
     * @ORM\Column(name="estado_pagado", type="boolean")
     */    
    private $estadoPagado = 0;     
    
    /**
     * @ORM\Column(name="numero_cuotas", type="integer")
     */
    private $numeroCuotas = 0;        
    
    /**
     * @ORM\Column(name="numero_cuota_actual", type="integer")
     */
    private $numeroCuotaActual = 0; 
    
    /**
     * @ORM\Column(name="tipo_pago", type="string")
     */
    private $tipoPago;
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios; 
    
    /**
     * @ORM\Column(name="aprobado", type="integer")
     */
    private $aprobado = 0;
    
    /**
     * @ORM\Column(name="seguro", type="integer")
     */
    private $seguro = 0;
    
    /**     
     * @ORM\Column(name="estado_suspendido", type="boolean")
     */    
    private $estadoSuspendido = 0;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="creditosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel; 

    /**
     * @ORM\ManyToOne(targetEntity="RhuCreditoTipo", inversedBy="creditosCreditoTipoRel")
     * @ORM\JoinColumn(name="codigo_credito_tipo_fk", referencedColumnName="codigo_credito_tipo_pk")
     */
    protected $creditoTipoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuCreditoPago", mappedBy="creditoRel")
     */
    protected $creditosPagosCreditoRel;

    /**
     * Get codigoCreditoPk
     *
     * @return integer
     */
    public function getCodigoCreditoPk()
    {
        return $this->codigoCreditoPk;
    }

    /**
     * Set codigoCreditoTipoFk
     *
     * @param integer $codigoCreditoTipoFk
     *
     * @return RhuCredito
     */
    public function setCodigoCreditoTipoFk($codigoCreditoTipoFk)
    {
        $this->codigoCreditoTipoFk = $codigoCreditoTipoFk;

        return $this;
    }

    /**
     * Get codigoCreditoTipoFk
     *
     * @return integer
     */
    public function getCodigoCreditoTipoFk()
    {
        return $this->codigoCreditoTipoFk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuCredito
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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuCredito
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set vrPagar
     *
     * @param float $vrPagar
     *
     * @return RhuCredito
     */
    public function setVrPagar($vrPagar)
    {
        $this->vrPagar = $vrPagar;

        return $this;
    }

    /**
     * Get vrPagar
     *
     * @return float
     */
    public function getVrPagar()
    {
        return $this->vrPagar;
    }

    /**
     * Set vrCuota
     *
     * @param float $vrCuota
     *
     * @return RhuCredito
     */
    public function setVrCuota($vrCuota)
    {
        $this->vrCuota = $vrCuota;

        return $this;
    }

    /**
     * Get vrCuota
     *
     * @return float
     */
    public function getVrCuota()
    {
        return $this->vrCuota;
    }

    /**
     * Set numeroCuotas
     *
     * @param integer $numeroCuotas
     *
     * @return RhuCredito
     */
    public function setNumeroCuotas($numeroCuotas)
    {
        $this->numeroCuotas = $numeroCuotas;

        return $this;
    }

    /**
     * Get numeroCuotas
     *
     * @return integer
     */
    public function getNumeroCuotas()
    {
        return $this->numeroCuotas;
    }

    /**
     * Set numeroCuotaActual
     *
     * @param integer $numeroCuotaActual
     *
     * @return RhuCredito
     */
    public function setNumeroCuotaActual($numeroCuotaActual)
    {
        $this->numeroCuotaActual = $numeroCuotaActual;

        return $this;
    }

    /**
     * Get numeroCuotaActual
     *
     * @return integer
     */
    public function getNumeroCuotaActual()
    {
        return $this->numeroCuotaActual;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuCredito
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

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuCredito
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
     * Set creditoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCreditoTipo $creditoTipoRel
     *
     * @return RhuCredito
     */
    public function setCreditoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCreditoTipo $creditoTipoRel = null)
    {
        $this->creditoTipoRel = $creditoTipoRel;

        return $this;
    }

    /**
     * Get creditoTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCreditoTipo
     */
    public function getCreditoTipoRel()
    {
        return $this->creditoTipoRel;
    }

    /**
     * Set saldo
     *
     * @param float $saldo
     *
     * @return RhuCredito
     */
    public function setSaldo($saldo)
    {
        $this->saldo = $saldo;

        return $this;
    }

    /**
     * Get saldo
     *
     * @return float
     */
    public function getSaldo()
    {
        return $this->saldo;
    }

    /**
     * Set estadoPagado
     *
     * @param boolean $estadoPagado
     *
     * @return RhuCredito
     */
    public function setEstadoPagado($estadoPagado)
    {
        $this->estadoPagado = $estadoPagado;

        return $this;
    }

    /**
     * Get estadoPagado
     *
     * @return boolean
     */
    public function getEstadoPagado()
    {
        return $this->estadoPagado;
    }

    /**
     * Set aprobado
     *
     * @param integer $aprobado
     *
     * @return RhuCredito
     */
    public function setAprobado($aprobado)
    {
        $this->aprobado = $aprobado;

        return $this;
    }

    /**
     * Get aprobado
     *
     * @return integer
     */
    public function getAprobado()
    {
        return $this->aprobado;
    }

    /**
     * Set tipoPago
     *
     * @param string $tipoPago
     *
     * @return RhuCredito
     */
    public function setTipoPago($tipoPago)
    {
        $this->tipoPago = $tipoPago;

        return $this;
    }

    /**
     * Get tipoPago
     *
     * @return string
     */
    public function getTipoPago()
    {
        return $this->tipoPago;
    }

    /**
     * Set seguro
     *
     * @param integer $seguro
     *
     * @return RhuCredito
     */
    public function setSeguro($seguro)
    {
        $this->seguro = $seguro;

        return $this;
    }

    /**
     * Get seguro
     *
     * @return integer
     */
    public function getSeguro()
    {
        return $this->seguro;
    }

   

    /**
     * Set estadoSuspendido
     *
     * @param boolean $estadoSuspendido
     *
     * @return RhuCredito
     */
    public function setEstadoSuspendido($estadoSuspendido)
    {
        $this->estadoSuspendido = $estadoSuspendido;

        return $this;
    }

    /**
     * Get estadoSuspendido
     *
     * @return boolean
     */
    public function getEstadoSuspendido()
    {
        return $this->estadoSuspendido;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->creditosPagosCreditoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add creditosPagosCreditoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCreditoPago $creditosPagosCreditoRel
     *
     * @return RhuCredito
     */
    public function addCreditosPagosCreditoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCreditoPago $creditosPagosCreditoRel)
    {
        $this->creditosPagosCreditoRel[] = $creditosPagosCreditoRel;

        return $this;
    }

    /**
     * Remove creditosPagosCreditoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCreditoPago $creditosPagosCreditoRel
     */
    public function removeCreditosPagosCreditoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCreditoPago $creditosPagosCreditoRel)
    {
        $this->creditosPagosCreditoRel->removeElement($creditosPagosCreditoRel);
    }

    /**
     * Get creditosPagosCreditoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCreditosPagosCreditoRel()
    {
        return $this->creditosPagosCreditoRel;
    }
}
