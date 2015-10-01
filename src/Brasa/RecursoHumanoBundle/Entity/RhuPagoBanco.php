<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_pago_banco")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPagoBancoRepository")
 */
class RhuPagoBanco
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_banco_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPagoBancoPk;         
    
    /**
     * @ORM\Column(name="fecha_trasmision", type="date", nullable=true)
     */    
    private $fechaTrasmision;    
    
    /**
     * @ORM\Column(name="fecha_aplicacion", type="date", nullable=true)
     */    
    private $fechaAplicacion;    
    
    /**
     * @ORM\Column(name="secuencia", type="string", length=1, nullable=true)
     */    
    private $secuencia;    
    
    /**
     * @ORM\Column(name="descripcion", type="string", length=50, nullable=true)
     */    
    private $descripcion;     
    
    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="integer", nullable=true)
     */    
    private $codigoCuentaFk;
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCuenta", inversedBy="rhuPagosBancosCuentaRel")
     * @ORM\JoinColumn(name="codigo_cuenta_fk", referencedColumnName="codigo_cuenta_pk")
     */
    protected $cuentaRel;     

    /**
     * Get codigoPagoBancoPk
     *
     * @return integer
     */
    public function getCodigoPagoBancoPk()
    {
        return $this->codigoPagoBancoPk;
    }

    /**
     * Set fechaTrasmision
     *
     * @param \DateTime $fechaTrasmision
     *
     * @return RhuPagoBanco
     */
    public function setFechaTrasmision($fechaTrasmision)
    {
        $this->fechaTrasmision = $fechaTrasmision;

        return $this;
    }

    /**
     * Get fechaTrasmision
     *
     * @return \DateTime
     */
    public function getFechaTrasmision()
    {
        return $this->fechaTrasmision;
    }

    /**
     * Set fechaAplicacion
     *
     * @param \DateTime $fechaAplicacion
     *
     * @return RhuPagoBanco
     */
    public function setFechaAplicacion($fechaAplicacion)
    {
        $this->fechaAplicacion = $fechaAplicacion;

        return $this;
    }

    /**
     * Get fechaAplicacion
     *
     * @return \DateTime
     */
    public function getFechaAplicacion()
    {
        return $this->fechaAplicacion;
    }

    /**
     * Set secuencia
     *
     * @param string $secuencia
     *
     * @return RhuPagoBanco
     */
    public function setSecuencia($secuencia)
    {
        $this->secuencia = $secuencia;

        return $this;
    }

    /**
     * Get secuencia
     *
     * @return string
     */
    public function getSecuencia()
    {
        return $this->secuencia;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return RhuPagoBanco
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set codigoCuentaFk
     *
     * @param integer $codigoCuentaFk
     *
     * @return RhuPagoBanco
     */
    public function setCodigoCuentaFk($codigoCuentaFk)
    {
        $this->codigoCuentaFk = $codigoCuentaFk;

        return $this;
    }

    /**
     * Get codigoCuentaFk
     *
     * @return integer
     */
    public function getCodigoCuentaFk()
    {
        return $this->codigoCuentaFk;
    }

    /**
     * Set cuentaRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCuenta $cuentaRel
     *
     * @return RhuPagoBanco
     */
    public function setCuentaRel(\Brasa\GeneralBundle\Entity\GenCuenta $cuentaRel = null)
    {
        $this->cuentaRel = $cuentaRel;

        return $this;
    }

    /**
     * Get cuentaRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCuenta
     */
    public function getCuentaRel()
    {
        return $this->cuentaRel;
    }
}
