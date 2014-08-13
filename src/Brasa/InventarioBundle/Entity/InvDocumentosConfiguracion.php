<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_documentos_configuracion")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvDocumentosConfiguracionRepository")
 */
class InvDocumentosConfiguracion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_documento_configuracion_pk", type="integer")
     */ 
    private $codigoDocumentoConfiguracionPk;    

    /**
     * @ORM\Column(name="requiere_fecha1", type="boolean")
     */    
    private $requiereFecha1 = 0;    
    
    /**
     * @ORM\Column(name="nombre_fecha1", type="string", length=30)
     */     
    private $nombreFecha1 = "Fecha1";
    
    /**
     * @ORM\Column(name="requiere_fecha2", type="boolean")
     */    
    private $requiereFecha2 = 0;    
    
    /**
     * @ORM\Column(name="nombre_fecha2", type="string", length=30)
     */     
    private $nombreFecha2 = "Fecha2";    

    /**
     * @ORM\Column(name="requiere_forma_pago", type="boolean")
     */    
    private $requiereFormaPago = 0;
    
    /**
     * @ORM\Column(name="requiere_direccion", type="boolean")
     */    
    private $requiereDireccion = 0;    

    /**
     * @ORM\Column(name="maneja_fletes", type="boolean")
     */    
    private $manejaFletes = 0;    
    
    /**
     * @ORM\Column(name="maneja_lote", type="boolean")
     */          
    private $manejaLote = 0;

    /**
     * @ORM\Column(name="maneja_bodega", type="boolean")
     */          
    private $manejaBodega = 0;      
    
    /**
     * @ORM\Column(name="editar_lote", type="boolean")
     */    
    private $editarLote = 0;    
    
    /**
     * @ORM\Column(name="editar_cantidad", type="boolean")
     */    
    private $editarCantidad = 0;    
    
    /**
     * @ORM\Column(name="editar_descuento", type="boolean")
     */    
    private $editarDescuento = 0;        
    
    /**
     * @ORM\Column(name="editar_precio", type="boolean")
     */    
    private $editarPrecio = 0;        
    
    /**
     * @internal Para saber si se le puede agregar un item por documento control
     * @ORM\Column(name="agregar_item_documento_control", type="boolean")
     */          
    private $agregarItemDocumentoControl = 0;     

    /**
     * @internal especifica si exige el mismo tercero en los documentos contro
     * @ORM\Column(name="exige_tercero_documento_control", type="boolean")
     */          
    private $exigeTerceroDocumentoControl = 0;    
    
    /**
     * @internal Para saber si se le puede agregar un item libre
     * @ORM\Column(name="agregar_item", type="boolean")
     */          
    private $agregarItem = 0;    
    

    /**
     * Set codigoDocumentoConfiguracionPk
     *
     * @param integer $codigoDocumentoConfiguracionPk
     * @return InvDocumentosConfiguracion
     */
    public function setCodigoDocumentoConfiguracionPk($codigoDocumentoConfiguracionPk)
    {
        $this->codigoDocumentoConfiguracionPk = $codigoDocumentoConfiguracionPk;

        return $this;
    }

    /**
     * Get codigoDocumentoConfiguracionPk
     *
     * @return integer 
     */
    public function getCodigoDocumentoConfiguracionPk()
    {
        return $this->codigoDocumentoConfiguracionPk;
    }

    /**
     * Set requiereFecha1
     *
     * @param boolean $requiereFecha1
     * @return InvDocumentosConfiguracion
     */
    public function setRequiereFecha1($requiereFecha1)
    {
        $this->requiereFecha1 = $requiereFecha1;

        return $this;
    }

    /**
     * Get requiereFecha1
     *
     * @return boolean 
     */
    public function getRequiereFecha1()
    {
        return $this->requiereFecha1;
    }

    /**
     * Set nombreFecha1
     *
     * @param string $nombreFecha1
     * @return InvDocumentosConfiguracion
     */
    public function setNombreFecha1($nombreFecha1)
    {
        $this->nombreFecha1 = $nombreFecha1;

        return $this;
    }

    /**
     * Get nombreFecha1
     *
     * @return string 
     */
    public function getNombreFecha1()
    {
        return $this->nombreFecha1;
    }

    /**
     * Set requiereFecha2
     *
     * @param boolean $requiereFecha2
     * @return InvDocumentosConfiguracion
     */
    public function setRequiereFecha2($requiereFecha2)
    {
        $this->requiereFecha2 = $requiereFecha2;

        return $this;
    }

    /**
     * Get requiereFecha2
     *
     * @return boolean 
     */
    public function getRequiereFecha2()
    {
        return $this->requiereFecha2;
    }

    /**
     * Set nombreFecha2
     *
     * @param string $nombreFecha2
     * @return InvDocumentosConfiguracion
     */
    public function setNombreFecha2($nombreFecha2)
    {
        $this->nombreFecha2 = $nombreFecha2;

        return $this;
    }

    /**
     * Get nombreFecha2
     *
     * @return string 
     */
    public function getNombreFecha2()
    {
        return $this->nombreFecha2;
    }

    /**
     * Set requiereFormaPago
     *
     * @param boolean $requiereFormaPago
     * @return InvDocumentosConfiguracion
     */
    public function setRequiereFormaPago($requiereFormaPago)
    {
        $this->requiereFormaPago = $requiereFormaPago;

        return $this;
    }

    /**
     * Get requiereFormaPago
     *
     * @return boolean 
     */
    public function getRequiereFormaPago()
    {
        return $this->requiereFormaPago;
    }

    /**
     * Set requiereDireccion
     *
     * @param boolean $requiereDireccion
     * @return InvDocumentosConfiguracion
     */
    public function setRequiereDireccion($requiereDireccion)
    {
        $this->requiereDireccion = $requiereDireccion;

        return $this;
    }

    /**
     * Get requiereDireccion
     *
     * @return boolean 
     */
    public function getRequiereDireccion()
    {
        return $this->requiereDireccion;
    }

    /**
     * Set manejaFletes
     *
     * @param boolean $manejaFletes
     * @return InvDocumentosConfiguracion
     */
    public function setManejaFletes($manejaFletes)
    {
        $this->manejaFletes = $manejaFletes;

        return $this;
    }

    /**
     * Get manejaFletes
     *
     * @return boolean 
     */
    public function getManejaFletes()
    {
        return $this->manejaFletes;
    }

    /**
     * Set manejaLote
     *
     * @param boolean $manejaLote
     * @return InvDocumentosConfiguracion
     */
    public function setManejaLote($manejaLote)
    {
        $this->manejaLote = $manejaLote;

        return $this;
    }

    /**
     * Get manejaLote
     *
     * @return boolean 
     */
    public function getManejaLote()
    {
        return $this->manejaLote;
    }

    /**
     * Set manejaBodega
     *
     * @param boolean $manejaBodega
     * @return InvDocumentosConfiguracion
     */
    public function setManejaBodega($manejaBodega)
    {
        $this->manejaBodega = $manejaBodega;

        return $this;
    }

    /**
     * Get manejaBodega
     *
     * @return boolean 
     */
    public function getManejaBodega()
    {
        return $this->manejaBodega;
    }

    /**
     * Set editarLote
     *
     * @param boolean $editarLote
     * @return InvDocumentosConfiguracion
     */
    public function setEditarLote($editarLote)
    {
        $this->editarLote = $editarLote;

        return $this;
    }

    /**
     * Get editarLote
     *
     * @return boolean 
     */
    public function getEditarLote()
    {
        return $this->editarLote;
    }

    /**
     * Set editarCantidad
     *
     * @param boolean $editarCantidad
     * @return InvDocumentosConfiguracion
     */
    public function setEditarCantidad($editarCantidad)
    {
        $this->editarCantidad = $editarCantidad;

        return $this;
    }

    /**
     * Get editarCantidad
     *
     * @return boolean 
     */
    public function getEditarCantidad()
    {
        return $this->editarCantidad;
    }

    /**
     * Set editarDescuento
     *
     * @param boolean $editarDescuento
     * @return InvDocumentosConfiguracion
     */
    public function setEditarDescuento($editarDescuento)
    {
        $this->editarDescuento = $editarDescuento;

        return $this;
    }

    /**
     * Get editarDescuento
     *
     * @return boolean 
     */
    public function getEditarDescuento()
    {
        return $this->editarDescuento;
    }

    /**
     * Set editarPrecio
     *
     * @param boolean $editarPrecio
     * @return InvDocumentosConfiguracion
     */
    public function setEditarPrecio($editarPrecio)
    {
        $this->editarPrecio = $editarPrecio;

        return $this;
    }

    /**
     * Get editarPrecio
     *
     * @return boolean 
     */
    public function getEditarPrecio()
    {
        return $this->editarPrecio;
    }

    /**
     * Set agregarItemDocumentoControl
     *
     * @param boolean $agregarItemDocumentoControl
     * @return InvDocumentosConfiguracion
     */
    public function setAgregarItemDocumentoControl($agregarItemDocumentoControl)
    {
        $this->agregarItemDocumentoControl = $agregarItemDocumentoControl;

        return $this;
    }

    /**
     * Get agregarItemDocumentoControl
     *
     * @return boolean 
     */
    public function getAgregarItemDocumentoControl()
    {
        return $this->agregarItemDocumentoControl;
    }

    /**
     * Set exigeTerceroDocumentoControl
     *
     * @param boolean $exigeTerceroDocumentoControl
     * @return InvDocumentosConfiguracion
     */
    public function setExigeTerceroDocumentoControl($exigeTerceroDocumentoControl)
    {
        $this->exigeTerceroDocumentoControl = $exigeTerceroDocumentoControl;

        return $this;
    }

    /**
     * Get exigeTerceroDocumentoControl
     *
     * @return boolean 
     */
    public function getExigeTerceroDocumentoControl()
    {
        return $this->exigeTerceroDocumentoControl;
    }

    /**
     * Set agregarItem
     *
     * @param boolean $agregarItem
     * @return InvDocumentosConfiguracion
     */
    public function setAgregarItem($agregarItem)
    {
        $this->agregarItem = $agregarItem;

        return $this;
    }

    /**
     * Get agregarItem
     *
     * @return boolean 
     */
    public function getAgregarItem()
    {
        return $this->agregarItem;
    }
}
