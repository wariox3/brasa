<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="inv_movimientos_descuentos_financieros")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvMovimientosDescuentosFinancierosRepository")
 */
class InvMovimientosDescuentosFinancieros
{
    /** 
     * @ORM\Id
     * @ORM\Column(name="codigo_movimiento_descuento_financiero_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */    
    private $codigoMovimientoDescuentoFinancieroPk;
    
    /**
     * @ORM\Column(name="codigo_movimiento_fk", type="integer", nullable=true)
     */     
    private $codigoMovimientoFk; 

    /**
     * @ORM\Column(name="codigo_descuento_financiero_fk", type="integer")
     */     
    private $codigoDescuentoFinanceroFk;     
    
    /**
     * @ORM\Column(name="base", type="float")
     */    
    private $base = 0;    
    
    /**
     * @ORM\Column(name="porcentaje", type="float")
     */    
    private $porcentaje = 0;      
    
    /**
     * @ORM\Column(name="vr_total", type="float")
     */    
    private $vrTotal = 0;    
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=40, nullable=true)
     */      
    private $comentarios;            
     
    /**
     * @ORM\ManyToOne(targetEntity="InvMovimientos", inversedBy="descuentosFinancierosRel")
     * @ORM\JoinColumn(name="codigo_movimiento_fk", referencedColumnName="codigo_movimiento_pk")
     */
    protected $movimientoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="InvDescuentosFinancieros", inversedBy="descuentosFinancierosRel")
     * @ORM\JoinColumn(name="codigo_descuento_financiero_fk", referencedColumnName="codigo_descuento_financiero_pk")
     */
    protected $descuentoFinancieroRel;
    
    


    /**
     * Get codigoMovimientoDescuentoFinancieroPk
     *
     * @return integer 
     */
    public function getCodigoMovimientoDescuentoFinancieroPk()
    {
        return $this->codigoMovimientoDescuentoFinancieroPk;
    }

    /**
     * Set codigoMovimientoFk
     *
     * @param integer $codigoMovimientoFk
     * @return InvMovimientosDescuentosFinancieros
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
     * Set codigoDescuentoFinanceroFk
     *
     * @param integer $codigoDescuentoFinanceroFk
     * @return InvMovimientosDescuentosFinancieros
     */
    public function setCodigoDescuentoFinanceroFk($codigoDescuentoFinanceroFk)
    {
        $this->codigoDescuentoFinanceroFk = $codigoDescuentoFinanceroFk;

        return $this;
    }

    /**
     * Get codigoDescuentoFinanceroFk
     *
     * @return integer 
     */
    public function getCodigoDescuentoFinanceroFk()
    {
        return $this->codigoDescuentoFinanceroFk;
    }

    /**
     * Set base
     *
     * @param float $base
     * @return InvMovimientosDescuentosFinancieros
     */
    public function setBase($base)
    {
        $this->base = $base;

        return $this;
    }

    /**
     * Get base
     *
     * @return float 
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * Set porcentaje
     *
     * @param float $porcentaje
     * @return InvMovimientosDescuentosFinancieros
     */
    public function setPorcentaje($porcentaje)
    {
        $this->porcentaje = $porcentaje;

        return $this;
    }

    /**
     * Get porcentaje
     *
     * @return float 
     */
    public function getPorcentaje()
    {
        return $this->porcentaje;
    }

    /**
     * Set vrTotal
     *
     * @param float $vrTotal
     * @return InvMovimientosDescuentosFinancieros
     */
    public function setVrTotal($vrTotal)
    {
        $this->vrTotal = $vrTotal;

        return $this;
    }

    /**
     * Get vrTotal
     *
     * @return float 
     */
    public function getVrTotal()
    {
        return $this->vrTotal;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     * @return InvMovimientosDescuentosFinancieros
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
     * Set movimientoRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimientos $movimientoRel
     * @return InvMovimientosDescuentosFinancieros
     */
    public function setMovimientoRel(\Brasa\InventarioBundle\Entity\InvMovimientos $movimientoRel = null)
    {
        $this->movimientoRel = $movimientoRel;

        return $this;
    }

    /**
     * Get movimientoRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvMovimientos 
     */
    public function getMovimientoRel()
    {
        return $this->movimientoRel;
    }

    /**
     * Set descuentoFinancieroRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDescuentosFinancieros $descuentoFinancieroRel
     * @return InvMovimientosDescuentosFinancieros
     */
    public function setDescuentoFinancieroRel(\Brasa\InventarioBundle\Entity\InvDescuentosFinancieros $descuentoFinancieroRel = null)
    {
        $this->descuentoFinancieroRel = $descuentoFinancieroRel;

        return $this;
    }

    /**
     * Get descuentoFinancieroRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvDescuentosFinancieros 
     */
    public function getDescuentoFinancieroRel()
    {
        return $this->descuentoFinancieroRel;
    }
}
