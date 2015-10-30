<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_factura")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuFacturaRepository")
 */
class RhuFactura
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_factura_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoFacturaPk;
    
    /**
     * @ORM\Column(name="numero", type="integer")
     */
    private $numero = 0;    
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;         
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaVence;          
    
    /**
     * @ORM\Column(name="vr_bruto", type="float")
     */
    private $VrBruto = 0;    
    
    /**
     * @ORM\Column(name="vr_neto", type="float")
     */
    private $VrNeto = 0;    

    /**
     * @ORM\Column(name="vr_retencion_fuente", type="float")
     */
    private $VrRetencionFuente = 0;    
    
    /**
     * @ORM\Column(name="vr_retencion_cree", type="float")
     */
    private $VrRetencionCree = 0;    

    /**
     * @ORM\Column(name="vr_retencion_iva", type="float")
     */
    private $VrRetencionIva = 0;        
    
    /**
     * @ORM\Column(name="vr_base_aiu", type="float")
     */
    private $VrBaseAIU = 0;    
    
    /**
     * @ORM\Column(name="vr_total_administracion", type="float")
     */
    private $VrTotalAdministracion = 0;    
    
    /**
     * @ORM\Column(name="vr_ingreso_mision", type="float")
     */
    private $VrIngresoMision = 0;    
    
    /**
     * @ORM\Column(name="vr_iva", type="float")
     */
    private $VrIva = 0;    
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;    

    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk;

    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     */    
    private $codigoTerceroFk;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="facturasCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenTercero", inversedBy="rhuFacturasTerceroRel")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuFacturaDetalle", mappedBy="facturaRel")
     */
    protected $facturasDetallesFacturaRel;                

    /**
     * @ORM\OneToMany(targetEntity="RhuExamen", mappedBy="facturaRel")
     */
    protected $examenesFacturaRel;     
    
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
     * Set numero
     *
     * @param integer $numero
     *
     * @return RhuFactura
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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuFactura
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
     * @return RhuFactura
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
     * Set vrBruto
     *
     * @param float $vrBruto
     *
     * @return RhuFactura
     */
    public function setVrBruto($vrBruto)
    {
        $this->VrBruto = $vrBruto;

        return $this;
    }

    /**
     * Get vrBruto
     *
     * @return float
     */
    public function getVrBruto()
    {
        return $this->VrBruto;
    }

    /**
     * Set vrNeto
     *
     * @param float $vrNeto
     *
     * @return RhuFactura
     */
    public function setVrNeto($vrNeto)
    {
        $this->VrNeto = $vrNeto;

        return $this;
    }

    /**
     * Get vrNeto
     *
     * @return float
     */
    public function getVrNeto()
    {
        return $this->VrNeto;
    }

    /**
     * Set vrRetencionFuente
     *
     * @param float $vrRetencionFuente
     *
     * @return RhuFactura
     */
    public function setVrRetencionFuente($vrRetencionFuente)
    {
        $this->VrRetencionFuente = $vrRetencionFuente;

        return $this;
    }

    /**
     * Get vrRetencionFuente
     *
     * @return float
     */
    public function getVrRetencionFuente()
    {
        return $this->VrRetencionFuente;
    }

    /**
     * Set vrRetencionCree
     *
     * @param float $vrRetencionCree
     *
     * @return RhuFactura
     */
    public function setVrRetencionCree($vrRetencionCree)
    {
        $this->VrRetencionCree = $vrRetencionCree;

        return $this;
    }

    /**
     * Get vrRetencionCree
     *
     * @return float
     */
    public function getVrRetencionCree()
    {
        return $this->VrRetencionCree;
    }

    /**
     * Set vrRetencionIva
     *
     * @param float $vrRetencionIva
     *
     * @return RhuFactura
     */
    public function setVrRetencionIva($vrRetencionIva)
    {
        $this->VrRetencionIva = $vrRetencionIva;

        return $this;
    }

    /**
     * Get vrRetencionIva
     *
     * @return float
     */
    public function getVrRetencionIva()
    {
        return $this->VrRetencionIva;
    }

    /**
     * Set vrBaseAIU
     *
     * @param float $vrBaseAIU
     *
     * @return RhuFactura
     */
    public function setVrBaseAIU($vrBaseAIU)
    {
        $this->VrBaseAIU = $vrBaseAIU;

        return $this;
    }

    /**
     * Get vrBaseAIU
     *
     * @return float
     */
    public function getVrBaseAIU()
    {
        return $this->VrBaseAIU;
    }

    /**
     * Set vrTotalAdministracion
     *
     * @param float $vrTotalAdministracion
     *
     * @return RhuFactura
     */
    public function setVrTotalAdministracion($vrTotalAdministracion)
    {
        $this->VrTotalAdministracion = $vrTotalAdministracion;

        return $this;
    }

    /**
     * Get vrTotalAdministracion
     *
     * @return float
     */
    public function getVrTotalAdministracion()
    {
        return $this->VrTotalAdministracion;
    }

    /**
     * Set vrIngresoMision
     *
     * @param float $vrIngresoMision
     *
     * @return RhuFactura
     */
    public function setVrIngresoMision($vrIngresoMision)
    {
        $this->VrIngresoMision = $vrIngresoMision;

        return $this;
    }

    /**
     * Get vrIngresoMision
     *
     * @return float
     */
    public function getVrIngresoMision()
    {
        return $this->VrIngresoMision;
    }

    /**
     * Set vrIva
     *
     * @param float $vrIva
     *
     * @return RhuFactura
     */
    public function setVrIva($vrIva)
    {
        $this->VrIva = $vrIva;

        return $this;
    }

    /**
     * Get vrIva
     *
     * @return float
     */
    public function getVrIva()
    {
        return $this->VrIva;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuFactura
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
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuFactura
     */
    public function setCodigoCentroCostoFk($codigoCentroCostoFk)
    {
        $this->codigoCentroCostoFk = $codigoCentroCostoFk;

        return $this;
    }

    /**
     * Get codigoCentroCostoFk
     *
     * @return integer
     */
    public function getCodigoCentroCostoFk()
    {
        return $this->codigoCentroCostoFk;
    }

    /**
     * Set codigoTerceroFk
     *
     * @param integer $codigoTerceroFk
     *
     * @return RhuFactura
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
     * Set centroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel
     *
     * @return RhuFactura
     */
    public function setCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel = null)
    {
        $this->centroCostoRel = $centroCostoRel;

        return $this;
    }

    /**
     * Get centroCostoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto
     */
    public function getCentroCostoRel()
    {
        return $this->centroCostoRel;
    }

    /**
     * Set terceroRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTercero $terceroRel
     *
     * @return RhuFactura
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
     * Add facturasDetallesFacturaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle $facturasDetallesFacturaRel
     *
     * @return RhuFactura
     */
    public function addFacturasDetallesFacturaRel(\Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle $facturasDetallesFacturaRel)
    {
        $this->facturasDetallesFacturaRel[] = $facturasDetallesFacturaRel;

        return $this;
    }

    /**
     * Remove facturasDetallesFacturaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle $facturasDetallesFacturaRel
     */
    public function removeFacturasDetallesFacturaRel(\Brasa\RecursoHumanoBundle\Entity\RhuFacturaDetalle $facturasDetallesFacturaRel)
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

    /**
     * Add examenesFacturaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesFacturaRel
     *
     * @return RhuFactura
     */
    public function addExamenesFacturaRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesFacturaRel)
    {
        $this->examenesFacturaRel[] = $examenesFacturaRel;

        return $this;
    }

    /**
     * Remove examenesFacturaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesFacturaRel
     */
    public function removeExamenesFacturaRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenesFacturaRel)
    {
        $this->examenesFacturaRel->removeElement($examenesFacturaRel);
    }

    /**
     * Get examenesFacturaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExamenesFacturaRel()
    {
        return $this->examenesFacturaRel;
    }
}
