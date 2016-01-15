<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_factura")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurFacturaRepository")
 */
class TurFactura
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_factura_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoFacturaPk;           
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;        
    
    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     */    
    private $codigoTerceroFk;        
    
    /**     
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = false;             
    
    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;    
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;                  
    
    /**
     * @ORM\OneToMany(targetEntity="TurFacturaDetalle", mappedBy="facturaRel")
     */
    protected $facturasDetallesFacturaRel; 
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->facturasDetallesFacturaRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoFacturaPk
     *
     * @return integer
     */
    public function getCodigoFacturaPk()
    {
        return $this->codigoFacturaPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return TurFactura
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
     * Set codigoTerceroFk
     *
     * @param integer $codigoTerceroFk
     *
     * @return TurFactura
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
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return TurFactura
     */
    public function setEstadoAutorizado($estadoAutorizado)
    {
        $this->estadoAutorizado = $estadoAutorizado;

        return $this;
    }

    /**
     * Get estadoAutorizado
     *
     * @return boolean
     */
    public function getEstadoAutorizado()
    {
        return $this->estadoAutorizado;
    }

    /**
     * Set vrTotal
     *
     * @param float $vrTotal
     *
     * @return TurFactura
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
     *
     * @return TurFactura
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
     * Add facturasDetallesFacturaRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesFacturaRel
     *
     * @return TurFactura
     */
    public function addFacturasDetallesFacturaRel(\Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesFacturaRel)
    {
        $this->facturasDetallesFacturaRel[] = $facturasDetallesFacturaRel;

        return $this;
    }

    /**
     * Remove facturasDetallesFacturaRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesFacturaRel
     */
    public function removeFacturasDetallesFacturaRel(\Brasa\TurnoBundle\Entity\TurFacturaDetalle $facturasDetallesFacturaRel)
    {
        $this->facturasDetallesFacturaRel->removeElement($facturasDetallesFacturaRel);
    }

    /**
     * Get facturasDetallesFacturaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasDetallesFacturaRel()
    {
        return $this->facturasDetallesFacturaRel;
    }
}
