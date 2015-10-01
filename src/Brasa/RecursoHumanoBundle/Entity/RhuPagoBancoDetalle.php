<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_pago_banco_detalle")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPagoBancoDetalleRepository")
 */
class RhuPagoBancoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_banco_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPagoBancoDetallePk;         
    
    /**
     * @ORM\Column(name="codigo_pago_banco_fk", type="integer", nullable=true)
     */    
    private $codigoPagoBancoFk;    
    
    /**
     * @ORM\Column(name="codigo_pago_fk", type="integer", nullable=true)
     */    
    private $codigoPagoFk;    
    
    /**
     * @ORM\Column(name="cuenta", type="string", length=20, nullable=true)
     */    
    private $cuenta;
    
    /**
     * @ORM\Column(name="vr_pago", type="float")
     */
    private $vrPago = 0;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuPagoBanco", inversedBy="pagosBancosDetallePagoBancoRel")
     * @ORM\JoinColumn(name="codigo_pago_banco_fk", referencedColumnName="codigo_pago_banco_pk")
     */
    protected $pagoBancoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="RhuPago", inversedBy="pagosBancosDetallePagoRel")
     * @ORM\JoinColumn(name="codigo_pago_fk", referencedColumnName="codigo_pago_pk")
     */
    protected $pagoRel;        

    /**
     * Get codigoPagoBancoDetallePk
     *
     * @return integer
     */
    public function getCodigoPagoBancoDetallePk()
    {
        return $this->codigoPagoBancoDetallePk;
    }

    /**
     * Set codigoPagoBancoFk
     *
     * @param integer $codigoPagoBancoFk
     *
     * @return RhuPagoBancoDetalle
     */
    public function setCodigoPagoBancoFk($codigoPagoBancoFk)
    {
        $this->codigoPagoBancoFk = $codigoPagoBancoFk;

        return $this;
    }

    /**
     * Get codigoPagoBancoFk
     *
     * @return integer
     */
    public function getCodigoPagoBancoFk()
    {
        return $this->codigoPagoBancoFk;
    }

    /**
     * Set cuenta
     *
     * @param string $cuenta
     *
     * @return RhuPagoBancoDetalle
     */
    public function setCuenta($cuenta)
    {
        $this->cuenta = $cuenta;

        return $this;
    }

    /**
     * Get cuenta
     *
     * @return string
     */
    public function getCuenta()
    {
        return $this->cuenta;
    }

    /**
     * Set vrPago
     *
     * @param float $vrPago
     *
     * @return RhuPagoBancoDetalle
     */
    public function setVrPago($vrPago)
    {
        $this->vrPago = $vrPago;

        return $this;
    }

    /**
     * Get vrPago
     *
     * @return float
     */
    public function getVrPago()
    {
        return $this->vrPago;
    }

    /**
     * Set pagoBancoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco $pagoBancoRel
     *
     * @return RhuPagoBancoDetalle
     */
    public function setPagoBancoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco $pagoBancoRel = null)
    {
        $this->pagoBancoRel = $pagoBancoRel;

        return $this;
    }

    /**
     * Get pagoBancoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco
     */
    public function getPagoBancoRel()
    {
        return $this->pagoBancoRel;
    }

    /**
     * Set codigoPagoFk
     *
     * @param integer $codigoPagoFk
     *
     * @return RhuPagoBancoDetalle
     */
    public function setCodigoPagoFk($codigoPagoFk)
    {
        $this->codigoPagoFk = $codigoPagoFk;

        return $this;
    }

    /**
     * Get codigoPagoFk
     *
     * @return integer
     */
    public function getCodigoPagoFk()
    {
        return $this->codigoPagoFk;
    }

    /**
     * Set pagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPago $pagoRel
     *
     * @return RhuPagoBancoDetalle
     */
    public function setPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPago $pagoRel = null)
    {
        $this->pagoRel = $pagoRel;

        return $this;
    }

    /**
     * Get pagoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuPago
     */
    public function getPagoRel()
    {
        return $this->pagoRel;
    }
}
