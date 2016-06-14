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
     * @ORM\OneToMany(targetEntity="TurFacturaDetalleConcepto", mappedBy="facturaConceptoRel")
     */
    protected $facturasDetallesConceptosFacturaConceptoRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="TurServicioDetalleConcepto", mappedBy="facturaConceptoRel")
     */
    protected $serviciosDetallesConceptosFacturaConceptoRel;     
    
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
}
