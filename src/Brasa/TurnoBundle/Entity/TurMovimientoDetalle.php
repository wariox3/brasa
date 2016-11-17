<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_movimiento_detalle")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurMovimientoDetalleRepository")
 */
class TurMovimientoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_movimiento_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoMovimientoDetallePk;    
    
    /**
     * @ORM\Column(name="codigo_movimiento_fk", type="integer")
     */
    
    private $codigoMovimientoFk;    
    
    /**
     * @ORM\Column(name="codigo_bodega_fk", type="string", length=10)
     */
    
    private $codigoBodegaFk;
    
    /**
     * @ORM\Column(name="cantidad", type="integer")
     */    
    private $numero = 0;
    
    /**
     * @ORM\Column(name="costo", type="float")
     */
    private $costo = 0;  
    
    /**
     * @ORM\ManyToOne(targetEntity="TurBodega", inversedBy="movimientosDetallesBodegaRel")
     * @ORM\JoinColumn(name="codigo_bodega_fk", referencedColumnName="codigo_bodega_pk")
     */
    protected $bodegaRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="TurMovimiento", inversedBy="movimientosDetallesMovimientoRel")
     * @ORM\JoinColumn(name="codigo_movimiento_fk", referencedColumnName="codigo_movimiento_pk")
     */
    protected $movimientoRel;
    
                
        

    /**
     * Get codigoMovimientoDetallePk
     *
     * @return integer
     */
    public function getCodigoMovimientoDetallePk()
    {
        return $this->codigoMovimientoDetallePk;
    }

    /**
     * Set codigoMovimientoFk
     *
     * @param integer $codigoMovimientoFk
     *
     * @return TurMovimientoDetalle
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
     * Set codigoBodegaFk
     *
     * @param string $codigoBodegaFk
     *
     * @return TurMovimientoDetalle
     */
    public function setCodigoBodegaFk($codigoBodegaFk)
    {
        $this->codigoBodegaFk = $codigoBodegaFk;

        return $this;
    }

    /**
     * Get codigoBodegaFk
     *
     * @return string
     */
    public function getCodigoBodegaFk()
    {
        return $this->codigoBodegaFk;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return TurMovimientoDetalle
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set costo
     *
     * @param float $costo
     *
     * @return TurMovimientoDetalle
     */
    public function setCosto($costo)
    {
        $this->costo = $costo;

        return $this;
    }

    /**
     * Get costo
     *
     * @return float
     */
    public function getCosto()
    {
        return $this->costo;
    }

    /**
     * Set bodegaRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurBodega $bodegaRel
     *
     * @return TurMovimientoDetalle
     */
    public function setBodegaRel(\Brasa\TurnoBundle\Entity\TurBodega $bodegaRel = null)
    {
        $this->bodegaRel = $bodegaRel;

        return $this;
    }

    /**
     * Get bodegaRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurBodega
     */
    public function getBodegaRel()
    {
        return $this->bodegaRel;
    }

    /**
     * Set movimientoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurMovimiento $movimientoRel
     *
     * @return TurMovimientoDetalle
     */
    public function setMovimientoRel(\Brasa\TurnoBundle\Entity\TurMovimiento $movimientoRel = null)
    {
        $this->movimientoRel = $movimientoRel;

        return $this;
    }

    /**
     * Get movimientoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurMovimiento
     */
    public function getMovimientoRel()
    {
        return $this->movimientoRel;
    }
}
