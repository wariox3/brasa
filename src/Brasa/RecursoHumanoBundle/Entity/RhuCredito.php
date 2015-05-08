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
     * @ORM\Column(name="fecha", type="date", nullable=true)
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
     * @ORM\Column(name="numero_cuotas", type="integer")
     */
    private $numeroCuotas = 0;        
    
    /**
     * @ORM\Column(name="numero_cuota_actual", type="integer")
     */
    private $numeroCuotaActual = 0;        
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;     
    
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
}
