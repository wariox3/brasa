<?php

namespace Brasa\CarteraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="car_cuentas_cobrar")
 * @ORM\Entity(repositoryClass="Brasa\CarteraBundle\Repository\CarCuentasCobrarRepository")
 */
class CarCuentasCobrar
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
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenTerceros", inversedBy="CarCuentasCobrar")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\InventarioBundle\Entity\InvMovimientos", inversedBy="CarCuentasCobrar")
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
     * @param date $fecha
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    /**
     * Get fecha
     *
     * @return date 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set codigoMovimientoFk
     *
     * @param integer $codigoMovimientoFk
     */
    public function setCodigoMovimientoFk($codigoMovimientoFk)
    {
        $this->codigoMovimientoFk = $codigoMovimientoFk;
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
     */
    public function setSoporte($soporte)
    {
        $this->soporte = $soporte;
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
     */
    public function setCodigoTerceroFk($codigoTerceroFk)
    {
        $this->codigoTerceroFk = $codigoTerceroFk;
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
     */
    public function setCondicion($condicion)
    {
        $this->condicion = $condicion;
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
     */
    public function setValorOriginal($valorOriginal)
    {
        $this->valorOriginal = $valorOriginal;
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
     */
    public function setSaldo($saldo)
    {
        $this->saldo = $saldo;
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
     */
    public function setDebitos($debitos)
    {
        $this->debitos = $debitos;
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
     */
    public function setCreditos($creditos)
    {
        $this->creditos = $creditos;
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
     * @param Brasa\GeneralBundle\Entity\GenTerceros $terceroRel
     */
    public function setTerceroRel(\Brasa\GeneralBundle\Entity\GenTerceros $terceroRel)
    {
        $this->terceroRel = $terceroRel;
    }

    /**
     * Get terceroRel
     *
     * @return Brasa\GeneralBundle\Entity\GenTerceros 
     */
    public function getTerceroRel()
    {
        return $this->terceroRel;
    }

    /**
     * Set movimientoRel
     *
     * @param Brasa\InventarioBundle\Entity\InvMovimientos $movimientoRel
     */
    public function setMovimientoRel(\Brasa\InventarioBundle\Entity\InvMovimientos $movimientoRel)
    {
        $this->movimientoRel = $movimientoRel;
    }

    /**
     * Get movimientoRel
     *
     * @return Brasa\InventarioBundle\Entity\InvMovimientos 
     */
    public function getMovimientoRel()
    {
        return $this->movimientoRel;
    }

    /**
     * Set fechaVence
     *
     * @param date $fechaVence
     */
    public function setFechaVence($fechaVence)
    {
        $this->fechaVence = $fechaVence;
    }

    /**
     * Get fechaVence
     *
     * @return date 
     */
    public function getFechaVence()
    {
        return $this->fechaVence;
    }
}
