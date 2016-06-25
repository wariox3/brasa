<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_factura_tipo")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurFacturaTipoRepository")
 */
class TurFacturaTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_factura_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoFacturaTipoPk;        
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;                                
    
    /**
     * @ORM\Column(name="tipo", type="integer")
     */    
    private $tipo = 0;      

    /**
     * @ORM\Column(name="consecutivo", type="integer")
     */    
    private $consecutivo = 0; 
    
    /**
     * @ORM\OneToMany(targetEntity="TurFactura", mappedBy="facturaTipoRel")
     */
    protected $facturasFacturaTipoRel; 
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->facturasFacturaTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoFacturaTipoPk
     *
     * @return integer
     */
    public function getCodigoFacturaTipoPk()
    {
        return $this->codigoFacturaTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurFacturaTipo
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
     * Add facturasFacturaTipoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFactura $facturasFacturaTipoRel
     *
     * @return TurFacturaTipo
     */
    public function addFacturasFacturaTipoRel(\Brasa\TurnoBundle\Entity\TurFactura $facturasFacturaTipoRel)
    {
        $this->facturasFacturaTipoRel[] = $facturasFacturaTipoRel;

        return $this;
    }

    /**
     * Remove facturasFacturaTipoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFactura $facturasFacturaTipoRel
     */
    public function removeFacturasFacturaTipoRel(\Brasa\TurnoBundle\Entity\TurFactura $facturasFacturaTipoRel)
    {
        $this->facturasFacturaTipoRel->removeElement($facturasFacturaTipoRel);
    }

    /**
     * Get facturasFacturaTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasFacturaTipoRel()
    {
        return $this->facturasFacturaTipoRel;
    }

    /**
     * Set tipo
     *
     * @param integer $tipo
     *
     * @return TurFacturaTipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return integer
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set consecutivo
     *
     * @param integer $consecutivo
     *
     * @return TurFacturaTipo
     */
    public function setConsecutivo($consecutivo)
    {
        $this->consecutivo = $consecutivo;

        return $this;
    }

    /**
     * Get consecutivo
     *
     * @return integer
     */
    public function getConsecutivo()
    {
        return $this->consecutivo;
    }
}
