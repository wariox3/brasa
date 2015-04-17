<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_pago")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPagoRepository")
 */
class RhuPago
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPagoPk;
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;      
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;    
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta;    

    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $vr_salario = 0;     
    
    /**
     * @ORM\Column(name="vr_devengado", type="float")
     */
    private $vr_devengado = 0;    

    /**
     * @ORM\Column(name="vr_deducciones", type="float")
     */
    private $vr_deducciones = 0;    
    
    /**
     * @ORM\Column(name="vr_total_pagado", type="float")
     */
    private $vr_total_pagado = 0;    
    


    /**
     * Get codigoPagoPk
     *
     * @return integer
     */
    public function getCodigoPagoPk()
    {
        return $this->codigoPagoPk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuPago
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
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return RhuPago
     */
    public function setFechaDesde($fechaDesde)
    {
        $this->fechaDesde = $fechaDesde;

        return $this;
    }

    /**
     * Get fechaDesde
     *
     * @return \DateTime
     */
    public function getFechaDesde()
    {
        return $this->fechaDesde;
    }

    /**
     * Set fechaHasta
     *
     * @param \DateTime $fechaHasta
     *
     * @return RhuPago
     */
    public function setFechaHasta($fechaHasta)
    {
        $this->fechaHasta = $fechaHasta;

        return $this;
    }

    /**
     * Get fechaHasta
     *
     * @return \DateTime
     */
    public function getFechaHasta()
    {
        return $this->fechaHasta;
    }

    /**
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return RhuPago
     */
    public function setVrSalario($vrSalario)
    {
        $this->vr_salario = $vrSalario;

        return $this;
    }

    /**
     * Get vrSalario
     *
     * @return float
     */
    public function getVrSalario()
    {
        return $this->vr_salario;
    }

    /**
     * Set vrDevengado
     *
     * @param float $vrDevengado
     *
     * @return RhuPago
     */
    public function setVrDevengado($vrDevengado)
    {
        $this->vr_devengado = $vrDevengado;

        return $this;
    }

    /**
     * Get vrDevengado
     *
     * @return float
     */
    public function getVrDevengado()
    {
        return $this->vr_devengado;
    }

    /**
     * Set vrDeducciones
     *
     * @param float $vrDeducciones
     *
     * @return RhuPago
     */
    public function setVrDeducciones($vrDeducciones)
    {
        $this->vr_deducciones = $vrDeducciones;

        return $this;
    }

    /**
     * Get vrDeducciones
     *
     * @return float
     */
    public function getVrDeducciones()
    {
        return $this->vr_deducciones;
    }

    /**
     * Set vrTotalPagado
     *
     * @param float $vrTotalPagado
     *
     * @return RhuPago
     */
    public function setVrTotalPagado($vrTotalPagado)
    {
        $this->vr_total_pagado = $vrTotalPagado;

        return $this;
    }

    /**
     * Get vrTotalPagado
     *
     * @return float
     */
    public function getVrTotalPagado()
    {
        return $this->vr_total_pagado;
    }
}
