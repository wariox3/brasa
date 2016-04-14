<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_factura_detalle_concepto")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurFacturaDetalleConceptoRepository")
 */
class TurFacturaDetalleConcepto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_factura_detalle_concepto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoFacturaDetalleConceptoPk;  
    
    /**
     * @ORM\Column(name="codigo_factura_fk", type="integer")
     */    
    private $codigoFacturaFk;         
    
    /**
     * @ORM\Column(name="codigo_factura_concepto_fk", type="integer")
     */    
    private $codigoFacturaConceptoFk;    
    
    /**
     * @ORM\Column(name="cantidad", type="float")
     */
    private $cantidad = 0;    
    
    /**
     * @ORM\Column(name="vr_precio", type="float")
     */
    private $vrPrecio = 0;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TurFactura", inversedBy="facturasDetallesConceptosFacturaRel")
     * @ORM\JoinColumn(name="codigo_factura_fk", referencedColumnName="codigo_factura_pk")
     */
    protected $facturaRel;          

    /**
     * @ORM\ManyToOne(targetEntity="TurFacturaConcepto", inversedBy="facturasDetallesConceptosFacturaConceptoRel")
     * @ORM\JoinColumn(name="codigo_factura_concepto_fk", referencedColumnName="codigo_factura_concepto_pk")
     */
    protected $facturaConceptoRel; 
    

    /**
     * Get codigoFacturaDetalleConceptoPk
     *
     * @return integer
     */
    public function getCodigoFacturaDetalleConceptoPk()
    {
        return $this->codigoFacturaDetalleConceptoPk;
    }

    /**
     * Set codigoFacturaFk
     *
     * @param integer $codigoFacturaFk
     *
     * @return TurFacturaDetalleConcepto
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
     * Set codigoFacturaConceptoFk
     *
     * @param integer $codigoFacturaConceptoFk
     *
     * @return TurFacturaDetalleConcepto
     */
    public function setCodigoFacturaConceptoFk($codigoFacturaConceptoFk)
    {
        $this->codigoFacturaConceptoFk = $codigoFacturaConceptoFk;

        return $this;
    }

    /**
     * Get codigoFacturaConceptoFk
     *
     * @return integer
     */
    public function getCodigoFacturaConceptoFk()
    {
        return $this->codigoFacturaConceptoFk;
    }

    /**
     * Set cantidad
     *
     * @param float $cantidad
     *
     * @return TurFacturaDetalleConcepto
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return float
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set vrPrecio
     *
     * @param float $vrPrecio
     *
     * @return TurFacturaDetalleConcepto
     */
    public function setVrPrecio($vrPrecio)
    {
        $this->vrPrecio = $vrPrecio;

        return $this;
    }

    /**
     * Get vrPrecio
     *
     * @return float
     */
    public function getVrPrecio()
    {
        return $this->vrPrecio;
    }

    /**
     * Set facturaRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFactura $facturaRel
     *
     * @return TurFacturaDetalleConcepto
     */
    public function setFacturaRel(\Brasa\TurnoBundle\Entity\TurFactura $facturaRel = null)
    {
        $this->facturaRel = $facturaRel;

        return $this;
    }

    /**
     * Get facturaRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurFactura
     */
    public function getFacturaRel()
    {
        return $this->facturaRel;
    }

    /**
     * Set facturaConceptoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFacturaConcepto $facturaConceptoRel
     *
     * @return TurFacturaDetalleConcepto
     */
    public function setFacturaConceptoRel(\Brasa\TurnoBundle\Entity\TurFacturaConcepto $facturaConceptoRel = null)
    {
        $this->facturaConceptoRel = $facturaConceptoRel;

        return $this;
    }

    /**
     * Get facturaConceptoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurFacturaConcepto
     */
    public function getFacturaConceptoRel()
    {
        return $this->facturaConceptoRel;
    }
}
