<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_factura_detalle")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiFacturaDetalleRepository")
 */
class AfiFacturaDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_factura_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoFacturaDetallePk;    
    
    /**
     * @ORM\Column(name="codigo_factura_fk", type="integer")
     */    
    private $codigoFacturaFk;            

    /**
     * @ORM\Column(name="codigo_servicio_fk", type="integer")
     */    
    private $codigoServicioFk; 

    /**
     * @ORM\Column(name="codigo_periodo_fk", type="integer")
     */    
    private $codigoPeriodoFk;
    
    /**
     * @ORM\Column(name="precio", type="float")
     */
    private $precio = 0;             
    
    /**
     * @ORM\Column(name="total", type="float")
     */
    private $total = 0;     
    
    /**
     * @ORM\ManyToOne(targetEntity="AfiFactura", inversedBy="facturasDetallesFacturaRel")
     * @ORM\JoinColumn(name="codigo_factura_fk", referencedColumnName="codigo_factura_pk")
     */
    protected $facturaRel;         
    
    /**
     * @ORM\ManyToOne(targetEntity="AfiPeriodo", inversedBy="facturasDetallesPeriodoRel")
     * @ORM\JoinColumn(name="codigo_periodo_fk", referencedColumnName="codigo_periodo_pk")
     */
    protected $periodoRel;    
    


    /**
     * Get codigoFacturaDetallePk
     *
     * @return integer
     */
    public function getCodigoFacturaDetallePk()
    {
        return $this->codigoFacturaDetallePk;
    }

    /**
     * Set codigoFacturaFk
     *
     * @param integer $codigoFacturaFk
     *
     * @return AfiFacturaDetalle
     */
    public function setCodigoFacturaFk($codigoFacturaFk)
    {
        $this->codigoFacturaFk = $codigoFacturaFk;

        return $this;
    }

    /**
     * Get codigoFacturaFk
     *
     * @return integer
     */
    public function getCodigoFacturaFk()
    {
        return $this->codigoFacturaFk;
    }

    /**
     * Set codigoServicioFk
     *
     * @param integer $codigoServicioFk
     *
     * @return AfiFacturaDetalle
     */
    public function setCodigoServicioFk($codigoServicioFk)
    {
        $this->codigoServicioFk = $codigoServicioFk;

        return $this;
    }

    /**
     * Get codigoServicioFk
     *
     * @return integer
     */
    public function getCodigoServicioFk()
    {
        return $this->codigoServicioFk;
    }

    /**
     * Set codigoPeriodoFk
     *
     * @param integer $codigoPeriodoFk
     *
     * @return AfiFacturaDetalle
     */
    public function setCodigoPeriodoFk($codigoPeriodoFk)
    {
        $this->codigoPeriodoFk = $codigoPeriodoFk;

        return $this;
    }

    /**
     * Get codigoPeriodoFk
     *
     * @return integer
     */
    public function getCodigoPeriodoFk()
    {
        return $this->codigoPeriodoFk;
    }

    /**
     * Set precio
     *
     * @param float $precio
     *
     * @return AfiFacturaDetalle
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return float
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set total
     *
     * @param float $total
     *
     * @return AfiFacturaDetalle
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set facturaRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiFactura $facturaRel
     *
     * @return AfiFacturaDetalle
     */
    public function setFacturaRel(\Brasa\AfiliacionBundle\Entity\AfiFactura $facturaRel = null)
    {
        $this->facturaRel = $facturaRel;

        return $this;
    }

    /**
     * Get facturaRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiFactura
     */
    public function getFacturaRel()
    {
        return $this->facturaRel;
    }

    /**
     * Set periodoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPeriodo $periodoRel
     *
     * @return AfiFacturaDetalle
     */
    public function setPeriodoRel(\Brasa\AfiliacionBundle\Entity\AfiPeriodo $periodoRel = null)
    {
        $this->periodoRel = $periodoRel;

        return $this;
    }

    /**
     * Get periodoRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiPeriodo
     */
    public function getPeriodoRel()
    {
        return $this->periodoRel;
    }
}
