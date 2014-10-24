<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_listas_precios_detalles")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteListasPreciosDetallesRepository")
 */
class TteListasPreciosDetalles
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
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudades", inversedBy="lpdCiudadDestinoRel")
     * @ORM\JoinColumn(name="codigo_ciudad_destino_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadDestinoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TteListasPrecios", inversedBy="listasPreciosDetallesRel")
     * @ORM\JoinColumn(name="codigo_lista_precios_fk", referencedColumnName="codigo_lista_precios_pk")
     */
    protected $listaPreciosRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TteProductos", inversedBy="listasPreciosDetallesRel")
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
     * @return TteListasPreciosDetalles
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
     * @param \Brasa\TransporteBundle\Entity\TteListasPrecios $listaPreciosRel
     * @return TteListasPreciosDetalles
     */
    public function setListaPreciosRel(\Brasa\TransporteBundle\Entity\TteListasPrecios $listaPreciosRel = null)
    {
        $this->listaPreciosRel = $listaPreciosRel;

        return $this;
    }

    /**
     * Get listaPreciosRel
     *
     * @return \Brasa\TransporteBundle\Entity\TteListasPrecios 
     */
    public function getListaPreciosRel()
    {
        return $this->listaPreciosRel;
    }

    /**
     * Set codigoCiudadDestinoFk
     *
     * @param integer $codigoCiudadDestinoFk
     * @return TteListasPreciosDetalles
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
     * @param \Brasa\GeneralBundle\Entity\GenCiudades $ciudadDestinoRel
     * @return TteListasPreciosDetalles
     */
    public function setCiudadDestinoRel(\Brasa\GeneralBundle\Entity\GenCiudades $ciudadDestinoRel = null)
    {
        $this->ciudadDestinoRel = $ciudadDestinoRel;

        return $this;
    }

    /**
     * Get ciudadDestinoRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudades 
     */
    public function getCiudadDestinoRel()
    {
        return $this->ciudadDestinoRel;
    }

    /**
     * Set codigoProductoFk
     *
     * @param integer $codigoProductoFk
     * @return TteListasPreciosDetalles
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
     * @param \Brasa\TransporteBundle\Entity\TteProductos $productoRel
     * @return TteListasPreciosDetalles
     */
    public function setProductoRel(\Brasa\TransporteBundle\Entity\TteProductos $productoRel = null)
    {
        $this->productoRel = $productoRel;

        return $this;
    }

    /**
     * Get productoRel
     *
     * @return \Brasa\TransporteBundle\Entity\TteProductos 
     */
    public function getProductoRel()
    {
        return $this->productoRel;
    }

    /**
     * Set vrKilo
     *
     * @param float $vrKilo
     * @return TteListasPreciosDetalles
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
     * @return TteListasPreciosDetalles
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
}
