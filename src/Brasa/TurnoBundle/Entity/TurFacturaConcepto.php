<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_factura_concepto")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurFacturaConceptoRepository")
 */
class TurFacturaConcepto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_factura_concepto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoFacturaConceptoPk;  
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */    
    private $nombre;     

    /**
     * @ORM\Column(name="por_iva", type="integer")
     */
    private $porIva = 0;    
    
    /**
     * @ORM\Column(name="codigo_concepto_servicio_fk", type="integer", nullable=true)
     */    
    private $codigoConceptoServicioFk;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TurConceptoServicio", inversedBy="facturasConceptosConceptoServicioRel")
     * @ORM\JoinColumn(name="codigo_concepto_servicio_fk", referencedColumnName="codigo_concepto_servicio_pk")
     */
    protected $conceptoServicioRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurFacturaDetalleConcepto", mappedBy="facturaConceptoRel")
     */
    protected $facturasDetallesConceptosFacturaConceptoRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="TurServicioDetalleConcepto", mappedBy="facturaConceptoRel")
     */
    protected $serviciosDetallesConceptosFacturaConceptoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalleConcepto", mappedBy="facturaConceptoRel")
     */
    protected $pedidosDetallesConceptosFacturaConceptoRel;      
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->facturasDetallesConceptosFacturaConceptoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->serviciosDetallesConceptosFacturaConceptoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoFacturaConceptoPk
     *
     * @return integer
     */
    public function getCodigoFacturaConceptoPk()
    {
        return $this->codigoFacturaConceptoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurFacturaConcepto
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set porIva
     *
     * @param integer $porIva
     *
     * @return TurFacturaConcepto
     */
    public function setPorIva($porIva)
    {
        $this->porIva = $porIva;

        return $this;
    }

    /**
     * Get porIva
     *
     * @return integer
     */
    public function getPorIva()
    {
        return $this->porIva;
    }

    /**
     * Add facturasDetallesConceptosFacturaConceptoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFacturaDetalleConcepto $facturasDetallesConceptosFacturaConceptoRel
     *
     * @return TurFacturaConcepto
     */
    public function addFacturasDetallesConceptosFacturaConceptoRel(\Brasa\TurnoBundle\Entity\TurFacturaDetalleConcepto $facturasDetallesConceptosFacturaConceptoRel)
    {
        $this->facturasDetallesConceptosFacturaConceptoRel[] = $facturasDetallesConceptosFacturaConceptoRel;

        return $this;
    }

    /**
     * Remove facturasDetallesConceptosFacturaConceptoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFacturaDetalleConcepto $facturasDetallesConceptosFacturaConceptoRel
     */
    public function removeFacturasDetallesConceptosFacturaConceptoRel(\Brasa\TurnoBundle\Entity\TurFacturaDetalleConcepto $facturasDetallesConceptosFacturaConceptoRel)
    {
        $this->facturasDetallesConceptosFacturaConceptoRel->removeElement($facturasDetallesConceptosFacturaConceptoRel);
    }

    /**
     * Get facturasDetallesConceptosFacturaConceptoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasDetallesConceptosFacturaConceptoRel()
    {
        return $this->facturasDetallesConceptosFacturaConceptoRel;
    }

    /**
     * Add serviciosDetallesConceptosFacturaConceptoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalleConcepto $serviciosDetallesConceptosFacturaConceptoRel
     *
     * @return TurFacturaConcepto
     */
    public function addServiciosDetallesConceptosFacturaConceptoRel(\Brasa\TurnoBundle\Entity\TurServicioDetalleConcepto $serviciosDetallesConceptosFacturaConceptoRel)
    {
        $this->serviciosDetallesConceptosFacturaConceptoRel[] = $serviciosDetallesConceptosFacturaConceptoRel;

        return $this;
    }

    /**
     * Remove serviciosDetallesConceptosFacturaConceptoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalleConcepto $serviciosDetallesConceptosFacturaConceptoRel
     */
    public function removeServiciosDetallesConceptosFacturaConceptoRel(\Brasa\TurnoBundle\Entity\TurServicioDetalleConcepto $serviciosDetallesConceptosFacturaConceptoRel)
    {
        $this->serviciosDetallesConceptosFacturaConceptoRel->removeElement($serviciosDetallesConceptosFacturaConceptoRel);
    }

    /**
     * Get serviciosDetallesConceptosFacturaConceptoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosDetallesConceptosFacturaConceptoRel()
    {
        return $this->serviciosDetallesConceptosFacturaConceptoRel;
    }

    /**
     * Add pedidosDetallesConceptosFacturaConceptoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalleConcepto $pedidosDetallesConceptosFacturaConceptoRel
     *
     * @return TurFacturaConcepto
     */
    public function addPedidosDetallesConceptosFacturaConceptoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalleConcepto $pedidosDetallesConceptosFacturaConceptoRel)
    {
        $this->pedidosDetallesConceptosFacturaConceptoRel[] = $pedidosDetallesConceptosFacturaConceptoRel;

        return $this;
    }

    /**
     * Remove pedidosDetallesConceptosFacturaConceptoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalleConcepto $pedidosDetallesConceptosFacturaConceptoRel
     */
    public function removePedidosDetallesConceptosFacturaConceptoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalleConcepto $pedidosDetallesConceptosFacturaConceptoRel)
    {
        $this->pedidosDetallesConceptosFacturaConceptoRel->removeElement($pedidosDetallesConceptosFacturaConceptoRel);
    }

    /**
     * Get pedidosDetallesConceptosFacturaConceptoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosDetallesConceptosFacturaConceptoRel()
    {
        return $this->pedidosDetallesConceptosFacturaConceptoRel;
    }

    /**
     * Set codigoConceptoServicioFk
     *
     * @param integer $codigoConceptoServicioFk
     *
     * @return TurFacturaConcepto
     */
    public function setCodigoConceptoServicioFk($codigoConceptoServicioFk)
    {
        $this->codigoConceptoServicioFk = $codigoConceptoServicioFk;

        return $this;
    }

    /**
     * Get codigoConceptoServicioFk
     *
     * @return integer
     */
    public function getCodigoConceptoServicioFk()
    {
        return $this->codigoConceptoServicioFk;
    }

    /**
     * Set conceptoServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurConceptoServicio $conceptoServicioRel
     *
     * @return TurFacturaConcepto
     */
    public function setConceptoServicioRel(\Brasa\TurnoBundle\Entity\TurConceptoServicio $conceptoServicioRel = null)
    {
        $this->conceptoServicioRel = $conceptoServicioRel;

        return $this;
    }

    /**
     * Get conceptoServicioRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurConceptoServicio
     */
    public function getConceptoServicioRel()
    {
        return $this->conceptoServicioRel;
    }
}
