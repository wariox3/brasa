<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_credito_pago")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCreditoPagoRepository")
 */
class RhuCreditoPago
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_credito_pago_pk", type="integer")
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
     * @ORM\Column(name="seguro", type="float")
     */
    private $seguro = 0;
    
    /**
     * @ORM\Column(name="fecha_pago", type="date")
     */    
    private $fechaPago;
    
    /**
     * @ORM\Column(name="tipo_pago", type="string")
     */    
    private $tipoPago;


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
     * @return RhuCreditoPago
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
     * @return RhuCreditoPago
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
     * @return RhuCreditoPago
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

    /**
     * Set tipoPago
     *
     * @param string $tipoPago
     *
     * @return RhuCreditoPago
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
     * @param float $seguro
     *
     * @return RhuCreditoPago
     */
    public function setSeguro($seguro)
    {
        $this->seguro = $seguro;

        return $this;
    }

    /**
     * Get seguro
     *
     * @return float
     */
    public function getSeguro()
    {
        return $this->seguro;
    }
}
