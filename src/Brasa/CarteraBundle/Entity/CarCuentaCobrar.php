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
     * @ORM\Column(name="fecha", type="date")
     */    
    private $fecha;     

    /**
     * @ORM\Column(name="fecha_vence", type="date")
     */    
    private $fechaVence;    
    
    /**
     * @ORM\Column(name="codigo_movimiento_fk", type="integer", nullable=true)
     */     
    private $codigoMovimientoFk;
    
    /**
     * @ORM\Column(name="soporte", type="string", length=30, nullable=true)
     */    
    private $soporte;    
    
    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer")
     */    
    private $codigoTerceroFk;        
    
    /**
     * @ORM\Column(name="condicion", type="integer")
     */    
    private $condicion = 0;
    
    /**
     * @ORM\Column(name="valor_original", type="float")
     */    
    private $valorOriginal = 0;    
    
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
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenTercero", inversedBy="CarCuentaCobrar")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\InventarioBundle\Entity\InvMovimiento", inversedBy="CarCuentaCobrar")
     * @ORM\JoinColumn(name="codigo_movimiento_fk", referencedColumnName="codigo_movimiento_pk")
     */
    protected $movimientoRel;     
    


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
     * Set codigoMovimientoFk
     *
     * @param integer $codigoMovimientoFk
     *
     * @return CarCuentaCobrar
     */
    public function setCodigoMovimientoFk($codigoMovimientoFk)
    {
        $this->codigoMovimientoFk = $codigoMovimientoFk;

        return $this;
    }

    /**
     * Get codigoMovimientoFk
     *
     * @return integer
     */
    public function getCodigoMovimientoFk()
    {
        return $this->codigoMovimientoFk;
    }

    /**
     * Set soporte
     *
     * @param string $soporte
     *
     * @return CarCuentaCobrar
     */
    public function setSoporte($soporte)
    {
        $this->soporte = $soporte;

        return $this;
    }

    /**
     * Get soporte
     *
     * @return string
     */
    public function getSoporte()
    {
        return $this->soporte;
    }

    /**
     * Set codigoTerceroFk
     *
     * @param integer $codigoTerceroFk
     *
     * @return CarCuentaCobrar
     */
    public function setCodigoTerceroFk($codigoTerceroFk)
    {
        $this->codigoTerceroFk = $codigoTerceroFk;

        return $this;
    }

    /**
     * Get codigoTerceroFk
     *
     * @return integer
     */
    public function getCodigoTerceroFk()
    {
        return $this->codigoTerceroFk;
    }

    /**
     * Set condicion
     *
     * @param integer $condicion
     *
     * @return CarCuentaCobrar
     */
    public function setCondicion($condicion)
    {
        $this->condicion = $condicion;

        return $this;
    }

    /**
     * Get condicion
     *
     * @return integer
     */
    public function getCondicion()
    {
        return $this->condicion;
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
     * Set terceroRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTercero $terceroRel
     *
     * @return CarCuentaCobrar
     */
    public function setTerceroRel(\Brasa\GeneralBundle\Entity\GenTercero $terceroRel = null)
    {
        $this->terceroRel = $terceroRel;

        return $this;
    }

    /**
     * Get terceroRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenTercero
     */
    public function getTerceroRel()
    {
        return $this->terceroRel;
    }

    /**
     * Set movimientoRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimiento $movimientoRel
     *
     * @return CarCuentaCobrar
     */
    public function setMovimientoRel(\Brasa\InventarioBundle\Entity\InvMovimiento $movimientoRel = null)
    {
        $this->movimientoRel = $movimientoRel;

        return $this;
    }

    /**
     * Get movimientoRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvMovimiento
     */
    public function getMovimientoRel()
    {
        return $this->movimientoRel;
    }
}
