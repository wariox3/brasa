<?php

namespace Brasa\CarteraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="car_cuenta_cobrar")
 * @ORM\Entity(repositoryClass="Brasa\CarteraBundle\Repository\CarCuentaCobrarRepository")
 */
class CarCuentaCobrar
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cuenta_cobrar_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoCuentaCobrarPk;        

    /**
     * @ORM\Column(name="codigo_cuenta_cobrar_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoCuentCobrarTipoFk;
    
    /**
     * @ORM\Column(name="numero_documento", type="string", length=30, nullable=true)
     */    
    private $numero_documento;
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;     

    /**
     * @ORM\Column(name="fecha_vence", type="date", nullable=true)
     */    
    private $fechaVence; 
    
    /**
     * @ORM\Column(name="plazo", type="string", length=10, nullable=true)
     */    
    private $plazo;
    
    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;        
    
    /**
     * @ORM\Column(name="valor_original", type="float")
     */    
    private $valorOriginal = 0;    
    
    /**
     * @ORM\Column(name="abono", type="float")
     */    
    private $abono = 0;
    
    /**
     * @ORM\Column(name="saldo", type="float")
     */    
    private $saldo = 0;        
    
    /**
     * @ORM\Column(name="debitos", type="float")
     */    
    private $debitos = 0;
    
    /**
     * @ORM\Column(name="creditos", type="float")
     */    
    private $creditos = 0;              
    
    /**
     * @ORM\ManyToOne(targetEntity="CarCliente", inversedBy="cuentaCobrarClientesRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="CarCuentaCobrarTipo", inversedBy="cuentasCobrarTiposClienteRel")
     * @ORM\JoinColumn(name="codigo_cuenta_cobrar_tipo_fk", referencedColumnName="codigo_cuenta_cobrar_tipo_pk")
     */
    protected $cuentaCobrarTipoRel;

    

    /**
     * Get codigoCuentaCobrarPk
     *
     * @return integer
     */
    public function getCodigoCuentaCobrarPk()
    {
        return $this->codigoCuentaCobrarPk;
    }

    /**
     * Set codigoCuentCobrarTipoFk
     *
     * @param integer $codigoCuentCobrarTipoFk
     *
     * @return CarCuentaCobrar
     */
    public function setCodigoCuentCobrarTipoFk($codigoCuentCobrarTipoFk)
    {
        $this->codigoCuentCobrarTipoFk = $codigoCuentCobrarTipoFk;

        return $this;
    }

    /**
     * Get codigoCuentCobrarTipoFk
     *
     * @return integer
     */
    public function getCodigoCuentCobrarTipoFk()
    {
        return $this->codigoCuentCobrarTipoFk;
    }

    /**
     * Set numeroDocumento
     *
     * @param string $numeroDocumento
     *
     * @return CarCuentaCobrar
     */
    public function setNumeroDocumento($numeroDocumento)
    {
        $this->numero_documento = $numeroDocumento;

        return $this;
    }

    /**
     * Get numeroDocumento
     *
     * @return string
     */
    public function getNumeroDocumento()
    {
        return $this->numero_documento;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return CarCuentaCobrar
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
     * Set fechaVence
     *
     * @param \DateTime $fechaVence
     *
     * @return CarCuentaCobrar
     */
    public function setFechaVence($fechaVence)
    {
        $this->fechaVence = $fechaVence;

        return $this;
    }

    /**
     * Get fechaVence
     *
     * @return \DateTime
     */
    public function getFechaVence()
    {
        return $this->fechaVence;
    }

    /**
     * Set plazo
     *
     * @param string $plazo
     *
     * @return CarCuentaCobrar
     */
    public function setPlazo($plazo)
    {
        $this->plazo = $plazo;

        return $this;
    }

    /**
     * Get plazo
     *
     * @return string
     */
    public function getPlazo()
    {
        return $this->plazo;
    }

    /**
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return CarCuentaCobrar
     */
    public function setCodigoClienteFk($codigoClienteFk)
    {
        $this->codigoClienteFk = $codigoClienteFk;

        return $this;
    }

    /**
     * Get codigoClienteFk
     *
     * @return integer
     */
    public function getCodigoClienteFk()
    {
        return $this->codigoClienteFk;
    }

    /**
     * Set valorOriginal
     *
     * @param float $valorOriginal
     *
     * @return CarCuentaCobrar
     */
    public function setValorOriginal($valorOriginal)
    {
        $this->valorOriginal = $valorOriginal;

        return $this;
    }

    /**
     * Get valorOriginal
     *
     * @return float
     */
    public function getValorOriginal()
    {
        return $this->valorOriginal;
    }

    /**
     * Set abono
     *
     * @param float $abono
     *
     * @return CarCuentaCobrar
     */
    public function setAbono($abono)
    {
        $this->abono = $abono;

        return $this;
    }

    /**
     * Get abono
     *
     * @return float
     */
    public function getAbono()
    {
        return $this->abono;
    }

    /**
     * Set saldo
     *
     * @param float $saldo
     *
     * @return CarCuentaCobrar
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
     * Set debitos
     *
     * @param float $debitos
     *
     * @return CarCuentaCobrar
     */
    public function setDebitos($debitos)
    {
        $this->debitos = $debitos;

        return $this;
    }

    /**
     * Get debitos
     *
     * @return float
     */
    public function getDebitos()
    {
        return $this->debitos;
    }

    /**
     * Set creditos
     *
     * @param float $creditos
     *
     * @return CarCuentaCobrar
     */
    public function setCreditos($creditos)
    {
        $this->creditos = $creditos;

        return $this;
    }

    /**
     * Get creditos
     *
     * @return float
     */
    public function getCreditos()
    {
        return $this->creditos;
    }

    /**
     * Set clienteRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCliente $clienteRel
     *
     * @return CarCuentaCobrar
     */
    public function setClienteRel(\Brasa\CarteraBundle\Entity\CarCliente $clienteRel = null)
    {
        $this->clienteRel = $clienteRel;

        return $this;
    }

    /**
     * Get clienteRel
     *
     * @return \Brasa\CarteraBundle\Entity\CarCliente
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
    }

    /**
     * Set cuentaCobrarTipoRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCuentaCobrarTipo $cuentaCobrarTipoRel
     *
     * @return CarCuentaCobrar
     */
    public function setCuentaCobrarTipoRel(\Brasa\CarteraBundle\Entity\CarCuentaCobrarTipo $cuentaCobrarTipoRel = null)
    {
        $this->cuentaCobrarTipoRel = $cuentaCobrarTipoRel;

        return $this;
    }

    /**
     * Get cuentaCobrarTipoRel
     *
     * @return \Brasa\CarteraBundle\Entity\CarCuentaCobrarTipo
     */
    public function getCuentaCobrarTipoRel()
    {
        return $this->cuentaCobrarTipoRel;
    }
}
