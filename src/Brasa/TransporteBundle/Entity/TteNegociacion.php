<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_negociacion")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteNegociacionRepository")
 */
class TteNegociacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_negociacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoNegociacionPk;  
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;
    
    /**
     * @ORM\Column(name="codigo_lista_precios_fk", type="integer", nullable=true)
     */    
    private $codigoListaPreciosFk;
    
    /**
     * @ORM\Column(name="liquidar_automaticamente_flete", type="boolean")
     */    
    private $liquidarAutomaticamenteFlete = 0;     

    /**
     * @ORM\Column(name="liquidar_automaticamente_manejo", type="boolean")
     */    
    private $liquidarAutomaticamenteManejo = 0;         
    
    /**
     * @ORM\Column(name="porcentaje_manejo", type="float")
     */
    private $porcentajeManejo = 0;     

    /**
     * @ORM\Column(name="vr_manejo_minimo_unidad", type="float")
     */
    private $vrManejoMinimoUnidad = 0;    

    /**
     * @ORM\Column(name="vr_manejo_minimo_despacho", type="float")
     */
    private $vrManejoMinimoDespacho = 0;        
    
    /**
     * @ORM\Column(name="descuento_kilos", type="float")
     */
    private $descuentoKilos = 0;     

    /**
     * @ORM\Column(name="ct_peso_minimo_unidad", type="float")
     */
    private $ctPesoMinimoUnidad = 0;        

    /**
     * @ORM\Column(name="paga_manejo_corriente", type="boolean")
     */
    private $pagaManejoCorriente = 0;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TteListaPrecio", inversedBy="negociacionesRel")
     * @ORM\JoinColumn(name="codigo_lista_precios_fk", referencedColumnName="codigo_lista_precios_pk")
     */
    protected $listaPreciosRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\GeneralBundle\Entity\GenTercero", mappedBy="negociacionRel")
     */
    protected $tercerosRel;

    /**
     * Get codigoNegociacionPk
     *
     * @return integer 
     */
    public function getCodigoNegociacionPk()
    {
        return $this->codigoNegociacionPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return TteNegociacion
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
     * Set codigoListaPreciosFk
     *
     * @param integer $codigoListaPreciosFk
     * @return TteNegociacion
     */
    public function setCodigoListaPreciosFk($codigoListaPreciosFk)
    {
        $this->codigoListaPreciosFk = $codigoListaPreciosFk;

        return $this;
    }

    /**
     * Get codigoListaPreciosFk
     *
     * @return integer 
     */
    public function getCodigoListaPreciosFk()
    {
        return $this->codigoListaPreciosFk;
    }

    /**
     * Set liquidarAutomaticamenteFlete
     *
     * @param boolean $liquidarAutomaticamenteFlete
     * @return TteNegociacion
     */
    public function setLiquidarAutomaticamenteFlete($liquidarAutomaticamenteFlete)
    {
        $this->liquidarAutomaticamenteFlete = $liquidarAutomaticamenteFlete;

        return $this;
    }

    /**
     * Get liquidarAutomaticamenteFlete
     *
     * @return boolean 
     */
    public function getLiquidarAutomaticamenteFlete()
    {
        return $this->liquidarAutomaticamenteFlete;
    }

    /**
     * Set porcentajeManejo
     *
     * @param float $porcentajeManejo
     * @return TteNegociacion
     */
    public function setPorcentajeManejo($porcentajeManejo)
    {
        $this->porcentajeManejo = $porcentajeManejo;

        return $this;
    }

    /**
     * Get porcentajeManejo
     *
     * @return float 
     */
    public function getPorcentajeManejo()
    {
        return $this->porcentajeManejo;
    }

    /**
     * Set listaPreciosRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteListaPrecio $listaPreciosRel
     * @return TteNegociacion
     */
    public function setListaPreciosRel(\Brasa\TransporteBundle\Entity\TteListaPrecio $listaPreciosRel = null)
    {
        $this->listaPreciosRel = $listaPreciosRel;

        return $this;
    }

    /**
     * Get listaPreciosRel
     *
     * @return \Brasa\TransporteBundle\Entity\TteListaPrecio 
     */
    public function getListaPreciosRel()
    {
        return $this->listaPreciosRel;
    }

    /**
     * Set liquidarAutomaticamenteManejo
     *
     * @param boolean $liquidarAutomaticamenteManejo
     * @return TteNegociacion
     */
    public function setLiquidarAutomaticamenteManejo($liquidarAutomaticamenteManejo)
    {
        $this->liquidarAutomaticamenteManejo = $liquidarAutomaticamenteManejo;

        return $this;
    }

    /**
     * Get liquidarAutomaticamenteManejo
     *
     * @return boolean 
     */
    public function getLiquidarAutomaticamenteManejo()
    {
        return $this->liquidarAutomaticamenteManejo;
    }

    /**
     * Set vrManejoMinimoUnidad
     *
     * @param float $vrManejoMinimoUnidad
     * @return TteNegociacion
     */
    public function setVrManejoMinimoUnidad($vrManejoMinimoUnidad)
    {
        $this->vrManejoMinimoUnidad = $vrManejoMinimoUnidad;

        return $this;
    }

    /**
     * Get vrManejoMinimoUnidad
     *
     * @return float 
     */
    public function getVrManejoMinimoUnidad()
    {
        return $this->vrManejoMinimoUnidad;
    }

    /**
     * Set vrManejoMinimoDespacho
     *
     * @param float $vrManejoMinimoDespacho
     * @return TteNegociacion
     */
    public function setVrManejoMinimoDespacho($vrManejoMinimoDespacho)
    {
        $this->vrManejoMinimoDespacho = $vrManejoMinimoDespacho;

        return $this;
    }

    /**
     * Get vrManejoMinimoDespacho
     *
     * @return float 
     */
    public function getVrManejoMinimoDespacho()
    {
        return $this->vrManejoMinimoDespacho;
    }

    /**
     * Set descuentoKilos
     *
     * @param float $descuentoKilos
     * @return TteNegociacion
     */
    public function setDescuentoKilos($descuentoKilos)
    {
        $this->descuentoKilos = $descuentoKilos;

        return $this;
    }

    /**
     * Get descuentoKilos
     *
     * @return float 
     */
    public function getDescuentoKilos()
    {
        return $this->descuentoKilos;
    }

    /**
     * Set ctPesoMinimoUnidad
     *
     * @param float $ctPesoMinimoUnidad
     * @return TteNegociacion
     */
    public function setCtPesoMinimoUnidad($ctPesoMinimoUnidad)
    {
        $this->ctPesoMinimoUnidad = $ctPesoMinimoUnidad;

        return $this;
    }

    /**
     * Get ctPesoMinimoUnidad
     *
     * @return float 
     */
    public function getCtPesoMinimoUnidad()
    {
        return $this->ctPesoMinimoUnidad;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tercerosRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add tercerosRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTercero $tercerosRel
     * @return TteNegociacion
     */
    public function addTercerosRel(\Brasa\GeneralBundle\Entity\GenTercero $tercerosRel)
    {
        $this->tercerosRel[] = $tercerosRel;

        return $this;
    }

    /**
     * Remove tercerosRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTercero $tercerosRel
     */
    public function removeTercerosRel(\Brasa\GeneralBundle\Entity\GenTercero $tercerosRel)
    {
        $this->tercerosRel->removeElement($tercerosRel);
    }

    /**
     * Get tercerosRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTercerosRel()
    {
        return $this->tercerosRel;
    }

    /**
     * Set pagaManejoCorriente
     *
     * @param boolean $pagaManejoCorriente
     * @return TteNegociacion
     */
    public function setPagaManejoCorriente($pagaManejoCorriente)
    {
        $this->pagaManejoCorriente = $pagaManejoCorriente;

        return $this;
    }

    /**
     * Get pagaManejoCorriente
     *
     * @return boolean 
     */
    public function getPagaManejoCorriente()
    {
        return $this->pagaManejoCorriente;
    }
}
