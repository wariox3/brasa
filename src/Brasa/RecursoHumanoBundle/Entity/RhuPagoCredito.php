<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_pago_credito")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPagoCreditoRepository")
 */
class RhuPagoCredito
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_credito_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPagoCreditoPk;
    
    /**
     * @ORM\Column(name="codigo_credito_fk", type="integer")
     */    
    private $codigoCreditoFk;
    
    /**
     * @ORM\Column(name="vr_cuota", type="float")
     */
    private $vrCuota = 0;
    
    /**
     * @ORM\Column(name="fecha_pago", type="date")
     */    
    private $fechaPago;
    
    

    /**
     * Get codigoPagoCreditoPk
     *
     * @return integer
     */
    public function getCodigoPagoCreditoPk()
    {
        return $this->codigoPagoCreditoPk;
    }

    /**
     * Set codigoCreditoFk
     *
     * @param integer $codigoCreditoFk
     *
     * @return RhuPagoCredito
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
     * Set vrCuota
     *
     * @param float $vrCuota
     *
     * @return RhuPagoCredito
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
     * Set fechaPago
     *
     * @param \DateTime $fechaPago
     *
     * @return RhuPagoCredito
     */
    public function setFechaPago($fechaPago)
    {
        $this->fechaPago = $fechaPago;

        return $this;
    }

    /**
     * Get fechaPago
     *
     * @return \DateTime
     */
    public function getFechaPago()
    {
        return $this->fechaPago;
    }
}
