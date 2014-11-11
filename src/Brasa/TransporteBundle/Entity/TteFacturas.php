<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_facturas")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteFacturasRepository")
 */
class TteFacturas
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_factura_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoFacturaPk;  
    
    /**
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */    
    private $numero;    
    
    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */    
    private $fecha;  
    
    /**
     * @ORM\Column(name="fecha_vencimiento", type="datetime", nullable=true)
     */    
    private $fechaVencimiento;    
    
    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     */    
    private $codigoTerceroFk;    
    
    /**
     * @ORM\Column(name="ct_unidades", type="float")
     */
    private $ctUnidades = 0;    
    
    /**
     * @ORM\Column(name="vr_flete", type="float")
     */
    private $vrFlete = 0;    
    
    /**
     * @ORM\Column(name="vr_manejo", type="float")
     */
    private $vrManejo = 0;    

    /**
     * @ORM\Column(name="vr_otros", type="float")
     */
    private $vrOtros = 0;    
    
    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;        

    /**
     * @ORM\Column(name="ct_guias", type="float")
     */
    private $ctGuias = 0;    
    
    /**
     * @ORM\Column(name="ct_planillas", type="float")
     */
    private $ctPlanillas = 0;    
    
    /**
     * @ORM\Column(name="ct_conceptos", type="float")
     */
    private $ctConceptos = 0;    
    
    /**
     * @ORM\Column(name="estado_impreso", type="boolean")
     */    
    private $estadoImpreso = 0;     
    
    /**
     * @ORM\Column(name="estado_anulada", type="boolean")
     */    
    private $estadoAnulada = 0;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=500, nullable=true)
     */    
    private $comentarios;     
    
    /**
     * @ORM\OneToMany(targetEntity="TteGuias", mappedBy="facturaRel")
     */
    protected $guiasDetallesRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenTerceros", inversedBy="tteFacturasRel")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;

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
     * @return TteFacturas
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
     * @return TteFacturas
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
     * Set fechaVencimiento
     *
     * @param \DateTime $fechaVencimiento
     * @return TteFacturas
     */
    public function setFechaVencimiento($fechaVencimiento)
    {
        $this->fechaVencimiento = $fechaVencimiento;

        return $this;
    }

    /**
     * Get fechaVencimiento
     *
     * @return \DateTime 
     */
    public function getFechaVencimiento()
    {
        return $this->fechaVencimiento;
    }

    /**
     * Set ctUnidades
     *
     * @param float $ctUnidades
     * @return TteFacturas
     */
    public function setCtUnidades($ctUnidades)
    {
        $this->ctUnidades = $ctUnidades;

        return $this;
    }

    /**
     * Get ctUnidades
     *
     * @return float 
     */
    public function getCtUnidades()
    {
        return $this->ctUnidades;
    }

    /**
     * Set vrFlete
     *
     * @param float $vrFlete
     * @return TteFacturas
     */
    public function setVrFlete($vrFlete)
    {
        $this->vrFlete = $vrFlete;

        return $this;
    }

    /**
     * Get vrFlete
     *
     * @return float 
     */
    public function getVrFlete()
    {
        return $this->vrFlete;
    }

    /**
     * Set vrManejo
     *
     * @param float $vrManejo
     * @return TteFacturas
     */
    public function setVrManejo($vrManejo)
    {
        $this->vrManejo = $vrManejo;

        return $this;
    }

    /**
     * Get vrManejo
     *
     * @return float 
     */
    public function getVrManejo()
    {
        return $this->vrManejo;
    }

    /**
     * Set vrOtros
     *
     * @param float $vrOtros
     * @return TteFacturas
     */
    public function setVrOtros($vrOtros)
    {
        $this->vrOtros = $vrOtros;

        return $this;
    }

    /**
     * Get vrOtros
     *
     * @return float 
     */
    public function getVrOtros()
    {
        return $this->vrOtros;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     * @return TteFacturas
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
     * Set codigoTerceroFk
     *
     * @param integer $codigoTerceroFk
     * @return TteFacturas
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
     * Set terceroRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTerceros $terceroRel
     * @return TteFacturas
     */
    public function setTerceroRel(\Brasa\GeneralBundle\Entity\GenTerceros $terceroRel = null)
    {
        $this->terceroRel = $terceroRel;

        return $this;
    }

    /**
     * Get terceroRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenTerceros 
     */
    public function getTerceroRel()
    {
        return $this->terceroRel;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->guiasDetallesRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add guiasDetallesRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuias $guiasDetallesRel
     * @return TteFacturas
     */
    public function addGuiasDetallesRel(\Brasa\TransporteBundle\Entity\TteGuias $guiasDetallesRel)
    {
        $this->guiasDetallesRel[] = $guiasDetallesRel;

        return $this;
    }

    /**
     * Remove guiasDetallesRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuias $guiasDetallesRel
     */
    public function removeGuiasDetallesRel(\Brasa\TransporteBundle\Entity\TteGuias $guiasDetallesRel)
    {
        $this->guiasDetallesRel->removeElement($guiasDetallesRel);
    }

    /**
     * Get guiasDetallesRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGuiasDetallesRel()
    {
        return $this->guiasDetallesRel;
    }

    /**
     * Set vrTotal
     *
     * @param float $vrTotal
     * @return TteFacturas
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
     * Set estadoAnulada
     *
     * @param boolean $estadoAnulada
     * @return TteFacturas
     */
    public function setEstadoAnulada($estadoAnulada)
    {
        $this->estadoAnulada = $estadoAnulada;

        return $this;
    }

    /**
     * Get estadoAnulada
     *
     * @return boolean 
     */
    public function getEstadoAnulada()
    {
        return $this->estadoAnulada;
    }

    /**
     * Set estadoImpreso
     *
     * @param boolean $estadoImpreso
     * @return TteFacturas
     */
    public function setEstadoImpreso($estadoImpreso)
    {
        $this->estadoImpreso = $estadoImpreso;

        return $this;
    }

    /**
     * Get estadoImpreso
     *
     * @return boolean 
     */
    public function getEstadoImpreso()
    {
        return $this->estadoImpreso;
    }

    /**
     * Set ctGuias
     *
     * @param float $ctGuias
     * @return TteFacturas
     */
    public function setCtGuias($ctGuias)
    {
        $this->ctGuias = $ctGuias;

        return $this;
    }

    /**
     * Get ctGuias
     *
     * @return float 
     */
    public function getCtGuias()
    {
        return $this->ctGuias;
    }

    /**
     * Set ctPlanillas
     *
     * @param float $ctPlanillas
     * @return TteFacturas
     */
    public function setCtPlanillas($ctPlanillas)
    {
        $this->ctPlanillas = $ctPlanillas;

        return $this;
    }

    /**
     * Get ctPlanillas
     *
     * @return float 
     */
    public function getCtPlanillas()
    {
        return $this->ctPlanillas;
    }

    /**
     * Set ctConceptos
     *
     * @param float $ctConceptos
     * @return TteFacturas
     */
    public function setCtConceptos($ctConceptos)
    {
        $this->ctConceptos = $ctConceptos;

        return $this;
    }

    /**
     * Get ctConceptos
     *
     * @return float 
     */
    public function getCtConceptos()
    {
        return $this->ctConceptos;
    }
}
