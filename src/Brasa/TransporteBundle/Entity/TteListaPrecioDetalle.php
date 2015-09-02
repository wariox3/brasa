<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_lista_precio_detalle")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteListaPrecioDetalleRepository")
 */
class TteListaPrecioDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_lista_precios_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoListaPreciosDetallePk; 
    
    /**
     * @ORM\Column(name="codigo_lista_precios_fk", type="integer", nullable=true)
     */    
    private $codigoListaPreciosFk;   
    
    /**
     * @ORM\Column(name="codigo_producto_fk", type="integer", nullable=true)
     */    
    private $codigoProductoFk;    
    
    /**
     * @ORM\Column(name="codigo_ciudad_destino_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadDestinoFk;        
    
    /**
     * @ORM\Column(name="vr_kilo", type="float")
     */
    private $vrKilo = 0;    

    /**
     * @ORM\Column(name="vr_unidad", type="float")
     */
    private $vrUnidad = 0;        
    
    /**
     * @ORM\Column(name="ct_kilos_limite", type="float")
     */
    private $ctKilosLimite = 0;            

    /**
     * @ORM\Column(name="vr_kilos_limite", type="float")
     */
    private $vrKilosLimite = 0;        

    /**
     * @ORM\Column(name="vr_kilo_adicional", type="float")
     */
    private $vrKiloAdicional = 0;        
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="lpdCiudadDestinoRel")
     * @ORM\JoinColumn(name="codigo_ciudad_destino_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadDestinoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TteListaPrecio", inversedBy="listasPreciosDetallesRel")
     * @ORM\JoinColumn(name="codigo_lista_precios_fk", referencedColumnName="codigo_lista_precios_pk")
     */
    protected $listaPreciosRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TteProducto", inversedBy="listasPreciosDetallesRel")
     * @ORM\JoinColumn(name="codigo_producto_fk", referencedColumnName="codigo_producto_pk")
     */
    protected $productoRel;

    /**
     * Get codigoListaPreciosDetallePk
     *
     * @return integer 
     */
    public function getCodigoListaPreciosDetallePk()
    {
        return $this->codigoListaPreciosDetallePk;
    }

    /**
     * Set codigoListaPreciosFk
     *
     * @param integer $codigoListaPreciosFk
     * @return TteListaPrecioDetalle
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
     * Set listaPreciosRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteListaPrecio $listaPreciosRel
     * @return TteListaPrecioDetalle
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
     * Set codigoCiudadDestinoFk
     *
     * @param integer $codigoCiudadDestinoFk
     * @return TteListaPrecioDetalle
     */
    public function setCodigoCiudadDestinoFk($codigoCiudadDestinoFk)
    {
        $this->codigoCiudadDestinoFk = $codigoCiudadDestinoFk;

        return $this;
    }

    /**
     * Get codigoCiudadDestinoFk
     *
     * @return integer 
     */
    public function getCodigoCiudadDestinoFk()
    {
        return $this->codigoCiudadDestinoFk;
    }

    /**
     * Set ciudadDestinoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadDestinoRel
     * @return TteListaPrecioDetalle
     */
    public function setCiudadDestinoRel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadDestinoRel = null)
    {
        $this->ciudadDestinoRel = $ciudadDestinoRel;

        return $this;
    }

    /**
     * Get ciudadDestinoRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudad 
     */
    public function getCiudadDestinoRel()
    {
        return $this->ciudadDestinoRel;
    }

    /**
     * Set codigoProductoFk
     *
     * @param integer $codigoProductoFk
     * @return TteListaPrecioDetalle
     */
    public function setCodigoProductoFk($codigoProductoFk)
    {
        $this->codigoProductoFk = $codigoProductoFk;

        return $this;
    }

    /**
     * Get codigoProductoFk
     *
     * @return integer 
     */
    public function getCodigoProductoFk()
    {
        return $this->codigoProductoFk;
    }

    /**
     * Set productoRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteProducto $productoRel
     * @return TteListaPrecioDetalle
     */
    public function setProductoRel(\Brasa\TransporteBundle\Entity\TteProducto $productoRel = null)
    {
        $this->productoRel = $productoRel;

        return $this;
    }

    /**
     * Get productoRel
     *
     * @return \Brasa\TransporteBundle\Entity\TteProducto 
     */
    public function getProductoRel()
    {
        return $this->productoRel;
    }

    /**
     * Set vrKilo
     *
     * @param float $vrKilo
     * @return TteListaPrecioDetalle
     */
    public function setVrKilo($vrKilo)
    {
        $this->vrKilo = $vrKilo;

        return $this;
    }

    /**
     * Get vrKilo
     *
     * @return float 
     */
    public function getVrKilo()
    {
        return $this->vrKilo;
    }

    /**
     * Set vrUnidad
     *
     * @param float $vrUnidad
     * @return TteListaPrecioDetalle
     */
    public function setVrUnidad($vrUnidad)
    {
        $this->vrUnidad = $vrUnidad;

        return $this;
    }

    /**
     * Get vrUnidad
     *
     * @return float 
     */
    public function getVrUnidad()
    {
        return $this->vrUnidad;
    }

    /**
     * Set ctKilosLimite
     *
     * @param float $ctKilosLimite
     * @return TteListaPrecioDetalle
     */
    public function setCtKilosLimite($ctKilosLimite)
    {
        $this->ctKilosLimite = $ctKilosLimite;

        return $this;
    }

    /**
     * Get ctKilosLimite
     *
     * @return float 
     */
    public function getCtKilosLimite()
    {
        return $this->ctKilosLimite;
    }

    /**
     * Set vrKilosLimite
     *
     * @param float $vrKilosLimite
     * @return TteListaPrecioDetalle
     */
    public function setVrKilosLimite($vrKilosLimite)
    {
        $this->vrKilosLimite = $vrKilosLimite;

        return $this;
    }

    /**
     * Get vrKilosLimite
     *
     * @return float 
     */
    public function getVrKilosLimite()
    {
        return $this->vrKilosLimite;
    }

    /**
     * Set vrKiloAdicional
     *
     * @param float $vrKiloAdicional
     * @return TteListaPrecioDetalle
     */
    public function setVrKiloAdicional($vrKiloAdicional)
    {
        $this->vrKiloAdicional = $vrKiloAdicional;

        return $this;
    }

    /**
     * Get vrKiloAdicional
     *
     * @return float 
     */
    public function getVrKiloAdicional()
    {
        return $this->vrKiloAdicional;
    }
}
