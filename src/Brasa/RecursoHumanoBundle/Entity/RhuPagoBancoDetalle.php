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
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=false)
     */         
    private $numeroIdentificacion;    
    
    /**
     * @ORM\Column(name="nombre_corto", type="string", length=80, nullable=true)
     */    
    private $nombreCorto;    
    
    /**
     * @ORM\Column(name="codigo_banco_fk", type="integer", nullable=true)
     */    
    private $codigoBancoFk;      
    
    /**
     * @ORM\Column(name="cuenta", type="string", length=20, nullable=true)
     */    
    private $cuenta;
    
    /**
     * @ORM\Column(name="vr_pago", type="float")
     */
    private $vrPago = 0;     
    
    /**
     * @ORM\Column(name="estado_contabilizado", type="boolean")
     */
    private $estadoContabilizado = 0;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuPagoBanco", inversedBy="pagosBancosDetallesPagoBancoRel")
     * @ORM\JoinColumn(name="codigo_pago_banco_fk", referencedColumnName="codigo_pago_banco_pk")
     */
    protected $pagoBancoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="RhuPago", inversedBy="pagosBancosDetallePagoRel")
     * @ORM\JoinColumn(name="codigo_pago_fk", referencedColumnName="codigo_pago_pk")
     */
    protected $pagoRel;        

    /**
     * @ORM\ManyToOne(targetEntity="RhuBanco", inversedBy="pagosBancosDetallesBancoRel")
     * @ORM\JoinColumn(name="codigo_banco_fk", referencedColumnName="codigo_banco_pk")
     */
    protected $bancoRel; 

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
     * Set numeroIdentificacion
     *
     * @param string $numeroIdentificacion
     *
     * @return RhuPagoBancoDetalle
     */
    public function setNumeroIdentificacion($numeroIdentificacion)
    {
        $this->numeroIdentificacion = $numeroIdentificacion;

        return $this;
    }

    /**
     * Get numeroIdentificacion
     *
     * @return string
     */
    public function getNumeroIdentificacion()
    {
        return $this->numeroIdentificacion;
    }

    /**
     * Set nombreCorto
     *
     * @param string $nombreCorto
     *
     * @return RhuPagoBancoDetalle
     */
    public function setNombreCorto($nombreCorto)
    {
        $this->nombreCorto = $nombreCorto;

        return $this;
    }

    /**
     * Get nombreCorto
     *
     * @return string
     */
    public function getNombreCorto()
    {
        return $this->nombreCorto;
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
     * Set estadoContabilizado
     *
     * @param boolean $estadoContabilizado
     *
     * @return RhuPagoBancoDetalle
     */
    public function setEstadoContabilizado($estadoContabilizado)
    {
        $this->estadoContabilizado = $estadoContabilizado;

        return $this;
    }

    /**
     * Get estadoContabilizado
     *
     * @return boolean
     */
    public function getEstadoContabilizado()
    {
        return $this->estadoContabilizado;
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

    /**
     * Set codigoBancoFk
     *
     * @param integer $codigoBancoFk
     *
     * @return RhuPagoBancoDetalle
     */
    public function setCodigoBancoFk($codigoBancoFk)
    {
        $this->codigoBancoFk = $codigoBancoFk;

        return $this;
    }

    /**
     * Get codigoBancoFk
     *
     * @return integer
     */
    public function getCodigoBancoFk()
    {
        return $this->codigoBancoFk;
    }

    /**
     * Set bancoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuBanco $bancoRel
     *
     * @return RhuPagoBancoDetalle
     */
    public function setBancoRel(\Brasa\RecursoHumanoBundle\Entity\RhuBanco $bancoRel = null)
    {
        $this->bancoRel = $bancoRel;

        return $this;
    }

    /**
     * Get bancoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuBanco
     */
    public function getBancoRel()
    {
        return $this->bancoRel;
    }
}
