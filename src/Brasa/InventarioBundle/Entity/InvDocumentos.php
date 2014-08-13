<?php

namespace Brasa\InventarioBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_documentos")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvDocumentosRepository")
 */
class InvDocumentos
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_documento_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDocumentoPk;

    /**
     * @ORM\Column(name="nombre_documento", type="string", length=50)
     */
    private $nombreDocumento;

    /**
     * @ORM\Column(name="codigo_documento_tipo_fk", type="integer")
     */
    private $codigoDocumentoTipoFk;    

    /**
     * @ORM\Column(name="abreviatura", type="string", length=10)
     */     
    private $abreviatura; 
    
    /**
     * @ORM\Column(name="codigo_documento_subtipo_fk", type="integer", nullable=true)
     */      
    private $codigoDocumentoSubtipoFk;           
    
    /**
     * @ORM\Column(name="codigo_comprobante_contable_fk", type="integer", nullable=true)
     */     
    private $codigoComprobanteContableFk;           

    /**
     * @ORM\Column(name="operacion_inventario", type="smallint")
     */          
    private $operacionInventario = 0;
    
    /**
     * @ORM\Column(name="operacion_comercial", type="smallint")
     * Este campo es para saber el valor de este documento que signo tiene 
     * por ejemplo las facturas son negativas para inventario porque son
     * salidas pero su operacion comercial es positiva
     */          
    private $operacionComercial = 0;    

    /**
     * @ORM\Column(name="factura_pos", type="boolean")
     */          
    private $facturaPOS = 0;    
    
    /**
     * @ORM\Column(name="genera_cartera", type="boolean")
     */          
    private $generaCartera = 0;

    /**
     * @ORM\Column(name="tipo_asiento_cartera", type="smallint", nullable=true)
     * 1 - Debito
     * 2 - Credito
     */          
    private $tipoAsientoCartera;        
    
    /**
     * @ORM\Column(name="genera_tesoreria", type="boolean")
     */          
    private $generaTesoreria = 0;    

    /**
     * @ORM\Column(name="tipo_asiento_tesoreria", type="smallint", nullable=true)
     * 1 - Debito
     * 2 - Credito
     */          
    private $tipoAsientoTesoreria;    
    
    /**
     * @ORM\Column(name="tipo_valor", type="smallint")
     * 0 - Ninguno
     * 1 - Compra
     * 2 - Venta
     */          
    private $tipoValor = 0;     
    
    /**
     * @ORM\Column(name="consecutivo", type="integer")
     */          
    private $consecutivo = 0;      
    
    /**
     * @ORM\Column(name="tipo_cuenta_ingreso", type="smallint", nullable=true)
     * 1 - Debito
     * 2 - Credito
     */          
    private $tipoCuentaIngreso = 0;     

    /**
     * @ORM\Column(name="tipo_cuenta_costo", type="smallint", nullable=true)
     * 1 - Debito
     * 2 - Credito
     */          
    private $tipoCuentaCosto = 0;     
    
    /**
     * @ORM\Column(name="tipo_cuenta_iva", type="smallint", nullable=true)
     * 1 - Debito
     * 2 - Credito
     */          
    private $tipoCuentaIva = 0;    
    
    /**
     * @ORM\Column(name="codigo_cuenta_iva_fk", type="string", length=15, nullable=true)
     */    
    private $codigo_cuenta_iva_fk;     

    /**
     * @ORM\Column(name="tipo_cuenta_retencion_fuente", type="smallint", nullable=true)
     * 1 - Debito
     * 2 - Credito
     */          
    private $tipoCuentaRetencionFuente = 0;    
    
    /**
     * @ORM\Column(name="codigo_cuenta_retencion_fuente_fk", type="string", length=15, nullable=true)
     */    
    private $codigoCuentaRetencionFuenteFk;     

    /**
     * @ORM\Column(name="tipo_cuenta_retencion_cree", type="smallint", nullable=true)
     * 1 - Debito
     * 2 - Credito
     */          
    private $tipoCuentaRetencionCREE = 0;    
    
    /**
     * @ORM\Column(name="codigo_cuenta_retencion_cree_fk", type="string", length=15, nullable=true)
     */    
    private $codigoCuentaRetencionCREEFk;    
    
    /**
     * @ORM\Column(name="tipo_cuenta_retencion_iva", type="smallint", nullable=true)
     * 1 - Debito
     * 2 - Credito
     */          
    private $tipoCuentaRetencionIva = 0;    
    
    /**
     * @ORM\Column(name="codigo_cuenta_retencion_iva_fk", type="string", length=15, nullable=true)
     */    
    private $codigoCuentaRetencionIvaFk;    
    
    /**
     * @ORM\Column(name="tipo_cuenta_tesoreria", type="smallint", nullable=true)
     * 1 - Debito
     * 2 - Credito
     */          
    private $tipoCuentaTesoreria = 0;    
    
    /**
     * @ORM\Column(name="codigo_cuenta_tesoreria_fk", type="string", length=15, nullable=true)
     */    
    private $codigoCuentaTesoreriaFk;     
    
    /**
     * @ORM\Column(name="tipo_cuenta_cartera", type="smallint", nullable=true)
     * 1 - Debito
     * 2 - Credito
     */          
    private $tipoCuentaCartera = 0;    
    
    /**
     * @ORM\Column(name="codigo_cuenta_cartera_fk", type="string", length=15, nullable=true)
     */    
    private $codigoCuentaCarteraFk;    

    /**
     * @ORM\Column(name="asignar_consecutivo_impresion", type="boolean")
     */          
    private $asignarConsecutivoImpresion = 0;          
    
    /**
     * @internal Para saber si el documento genera costo promedio
     * @ORM\Column(name="genera_costo_promedio", type="boolean")
     */          
    private $generaCostoPromedio = 0;  
    
    /**
     * @ORM\Column(name="tipo_tercero", type="smallint")
     * 0 - Ninguno
     * 1 - Cliente
     * 2 - Proveedor
     */          
    private $tipoTercero = 0;    
    
    
    /**
     * @ORM\ManyToOne(targetEntity="InvDocumentosTipos", inversedBy="documentosRel")
     * @ORM\JoinColumn(name="codigo_documento_tipo_fk", referencedColumnName="codigo_documento_tipo_pk")
     */
    protected $documentoTipoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="InvMovimientos", mappedBy="documentoRel")
     */
    protected $movimientosRel;

  
    

    /**
     * Get codigoDocumentoPk
     *
     * @return integer 
     */
    public function getCodigoDocumentoPk()
    {
        return $this->codigoDocumentoPk;
    }

    /**
     * Set nombreDocumento
     *
     * @param string $nombreDocumento
     * @return InvDocumentos
     */
    public function setNombreDocumento($nombreDocumento)
    {
        $this->nombreDocumento = $nombreDocumento;

        return $this;
    }

    /**
     * Get nombreDocumento
     *
     * @return string 
     */
    public function getNombreDocumento()
    {
        return $this->nombreDocumento;
    }

    /**
     * Set codigoDocumentoTipoFk
     *
     * @param integer $codigoDocumentoTipoFk
     * @return InvDocumentos
     */
    public function setCodigoDocumentoTipoFk($codigoDocumentoTipoFk)
    {
        $this->codigoDocumentoTipoFk = $codigoDocumentoTipoFk;

        return $this;
    }

    /**
     * Get codigoDocumentoTipoFk
     *
     * @return integer 
     */
    public function getCodigoDocumentoTipoFk()
    {
        return $this->codigoDocumentoTipoFk;
    }

    /**
     * Set documentoTipoRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumentosTipos $documentoTipoRel
     * @return InvDocumentos
     */
    public function setDocumentoTipoRel(\Brasa\InventarioBundle\Entity\InvDocumentosTipos $documentoTipoRel = null)
    {
        $this->documentoTipoRel = $documentoTipoRel;

        return $this;
    }

    /**
     * Get documentoTipoRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvDocumentosTipos 
     */
    public function getDocumentoTipoRel()
    {
        return $this->documentoTipoRel;
    }

    /**
     * Add movimientosRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimientos $movimientosRel
     * @return InvDocumentos
     */
    public function addMovimientosRel(\Brasa\InventarioBundle\Entity\InvMovimientos $movimientosRel)
    {
        $this->movimientosRel[] = $movimientosRel;

        return $this;
    }

    /**
     * Remove movimientosRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimientos $movimientosRel
     */
    public function removeMovimientosRel(\Brasa\InventarioBundle\Entity\InvMovimientos $movimientosRel)
    {
        $this->movimientosRel->removeElement($movimientosRel);
    }

    /**
     * Get movimientosRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMovimientosRel()
    {
        return $this->movimientosRel;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->movimientosRel = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set abreviatura
     *
     * @param string $abreviatura
     * @return InvDocumentos
     */
    public function setAbreviatura($abreviatura)
    {
        $this->abreviatura = $abreviatura;

        return $this;
    }

    /**
     * Get abreviatura
     *
     * @return string 
     */
    public function getAbreviatura()
    {
        return $this->abreviatura;
    }

    /**
     * Set codigoDocumentoSubtipoFk
     *
     * @param integer $codigoDocumentoSubtipoFk
     * @return InvDocumentos
     */
    public function setCodigoDocumentoSubtipoFk($codigoDocumentoSubtipoFk)
    {
        $this->codigoDocumentoSubtipoFk = $codigoDocumentoSubtipoFk;

        return $this;
    }

    /**
     * Get codigoDocumentoSubtipoFk
     *
     * @return integer 
     */
    public function getCodigoDocumentoSubtipoFk()
    {
        return $this->codigoDocumentoSubtipoFk;
    }

    /**
     * Set codigoComprobanteContableFk
     *
     * @param integer $codigoComprobanteContableFk
     * @return InvDocumentos
     */
    public function setCodigoComprobanteContableFk($codigoComprobanteContableFk)
    {
        $this->codigoComprobanteContableFk = $codigoComprobanteContableFk;

        return $this;
    }

    /**
     * Get codigoComprobanteContableFk
     *
     * @return integer 
     */
    public function getCodigoComprobanteContableFk()
    {
        return $this->codigoComprobanteContableFk;
    }

    /**
     * Set operacionInventario
     *
     * @param integer $operacionInventario
     * @return InvDocumentos
     */
    public function setOperacionInventario($operacionInventario)
    {
        $this->operacionInventario = $operacionInventario;

        return $this;
    }

    /**
     * Get operacionInventario
     *
     * @return integer 
     */
    public function getOperacionInventario()
    {
        return $this->operacionInventario;
    }

    /**
     * Set operacionComercial
     *
     * @param integer $operacionComercial
     * @return InvDocumentos
     */
    public function setOperacionComercial($operacionComercial)
    {
        $this->operacionComercial = $operacionComercial;

        return $this;
    }

    /**
     * Get operacionComercial
     *
     * @return integer 
     */
    public function getOperacionComercial()
    {
        return $this->operacionComercial;
    }

    /**
     * Set facturaPOS
     *
     * @param boolean $facturaPOS
     * @return InvDocumentos
     */
    public function setFacturaPOS($facturaPOS)
    {
        $this->facturaPOS = $facturaPOS;

        return $this;
    }

    /**
     * Get facturaPOS
     *
     * @return boolean 
     */
    public function getFacturaPOS()
    {
        return $this->facturaPOS;
    }

    /**
     * Set generaCartera
     *
     * @param boolean $generaCartera
     * @return InvDocumentos
     */
    public function setGeneraCartera($generaCartera)
    {
        $this->generaCartera = $generaCartera;

        return $this;
    }

    /**
     * Get generaCartera
     *
     * @return boolean 
     */
    public function getGeneraCartera()
    {
        return $this->generaCartera;
    }

    /**
     * Set tipoAsientoCartera
     *
     * @param integer $tipoAsientoCartera
     * @return InvDocumentos
     */
    public function setTipoAsientoCartera($tipoAsientoCartera)
    {
        $this->tipoAsientoCartera = $tipoAsientoCartera;

        return $this;
    }

    /**
     * Get tipoAsientoCartera
     *
     * @return integer 
     */
    public function getTipoAsientoCartera()
    {
        return $this->tipoAsientoCartera;
    }

    /**
     * Set generaTesoreria
     *
     * @param boolean $generaTesoreria
     * @return InvDocumentos
     */
    public function setGeneraTesoreria($generaTesoreria)
    {
        $this->generaTesoreria = $generaTesoreria;

        return $this;
    }

    /**
     * Get generaTesoreria
     *
     * @return boolean 
     */
    public function getGeneraTesoreria()
    {
        return $this->generaTesoreria;
    }

    /**
     * Set tipoAsientoTesoreria
     *
     * @param integer $tipoAsientoTesoreria
     * @return InvDocumentos
     */
    public function setTipoAsientoTesoreria($tipoAsientoTesoreria)
    {
        $this->tipoAsientoTesoreria = $tipoAsientoTesoreria;

        return $this;
    }

    /**
     * Get tipoAsientoTesoreria
     *
     * @return integer 
     */
    public function getTipoAsientoTesoreria()
    {
        return $this->tipoAsientoTesoreria;
    }

    /**
     * Set tipoValor
     *
     * @param integer $tipoValor
     * @return InvDocumentos
     */
    public function setTipoValor($tipoValor)
    {
        $this->tipoValor = $tipoValor;

        return $this;
    }

    /**
     * Get tipoValor
     *
     * @return integer 
     */
    public function getTipoValor()
    {
        return $this->tipoValor;
    }

    /**
     * Set consecutivo
     *
     * @param integer $consecutivo
     * @return InvDocumentos
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

    /**
     * Set tipoCuentaIngreso
     *
     * @param integer $tipoCuentaIngreso
     * @return InvDocumentos
     */
    public function setTipoCuentaIngreso($tipoCuentaIngreso)
    {
        $this->tipoCuentaIngreso = $tipoCuentaIngreso;

        return $this;
    }

    /**
     * Get tipoCuentaIngreso
     *
     * @return integer 
     */
    public function getTipoCuentaIngreso()
    {
        return $this->tipoCuentaIngreso;
    }

    /**
     * Set tipoCuentaCosto
     *
     * @param integer $tipoCuentaCosto
     * @return InvDocumentos
     */
    public function setTipoCuentaCosto($tipoCuentaCosto)
    {
        $this->tipoCuentaCosto = $tipoCuentaCosto;

        return $this;
    }

    /**
     * Get tipoCuentaCosto
     *
     * @return integer 
     */
    public function getTipoCuentaCosto()
    {
        return $this->tipoCuentaCosto;
    }

    /**
     * Set tipoCuentaIva
     *
     * @param integer $tipoCuentaIva
     * @return InvDocumentos
     */
    public function setTipoCuentaIva($tipoCuentaIva)
    {
        $this->tipoCuentaIva = $tipoCuentaIva;

        return $this;
    }

    /**
     * Get tipoCuentaIva
     *
     * @return integer 
     */
    public function getTipoCuentaIva()
    {
        return $this->tipoCuentaIva;
    }

    /**
     * Set codigo_cuenta_iva_fk
     *
     * @param string $codigoCuentaIvaFk
     * @return InvDocumentos
     */
    public function setCodigoCuentaIvaFk($codigoCuentaIvaFk)
    {
        $this->codigo_cuenta_iva_fk = $codigoCuentaIvaFk;

        return $this;
    }

    /**
     * Get codigo_cuenta_iva_fk
     *
     * @return string 
     */
    public function getCodigoCuentaIvaFk()
    {
        return $this->codigo_cuenta_iva_fk;
    }

    /**
     * Set tipoCuentaRetencionFuente
     *
     * @param integer $tipoCuentaRetencionFuente
     * @return InvDocumentos
     */
    public function setTipoCuentaRetencionFuente($tipoCuentaRetencionFuente)
    {
        $this->tipoCuentaRetencionFuente = $tipoCuentaRetencionFuente;

        return $this;
    }

    /**
     * Get tipoCuentaRetencionFuente
     *
     * @return integer 
     */
    public function getTipoCuentaRetencionFuente()
    {
        return $this->tipoCuentaRetencionFuente;
    }

    /**
     * Set codigoCuentaRetencionFuenteFk
     *
     * @param string $codigoCuentaRetencionFuenteFk
     * @return InvDocumentos
     */
    public function setCodigoCuentaRetencionFuenteFk($codigoCuentaRetencionFuenteFk)
    {
        $this->codigoCuentaRetencionFuenteFk = $codigoCuentaRetencionFuenteFk;

        return $this;
    }

    /**
     * Get codigoCuentaRetencionFuenteFk
     *
     * @return string 
     */
    public function getCodigoCuentaRetencionFuenteFk()
    {
        return $this->codigoCuentaRetencionFuenteFk;
    }

    /**
     * Set tipoCuentaRetencionCREE
     *
     * @param integer $tipoCuentaRetencionCREE
     * @return InvDocumentos
     */
    public function setTipoCuentaRetencionCREE($tipoCuentaRetencionCREE)
    {
        $this->tipoCuentaRetencionCREE = $tipoCuentaRetencionCREE;

        return $this;
    }

    /**
     * Get tipoCuentaRetencionCREE
     *
     * @return integer 
     */
    public function getTipoCuentaRetencionCREE()
    {
        return $this->tipoCuentaRetencionCREE;
    }

    /**
     * Set codigoCuentaRetencionCREEFk
     *
     * @param string $codigoCuentaRetencionCREEFk
     * @return InvDocumentos
     */
    public function setCodigoCuentaRetencionCREEFk($codigoCuentaRetencionCREEFk)
    {
        $this->codigoCuentaRetencionCREEFk = $codigoCuentaRetencionCREEFk;

        return $this;
    }

    /**
     * Get codigoCuentaRetencionCREEFk
     *
     * @return string 
     */
    public function getCodigoCuentaRetencionCREEFk()
    {
        return $this->codigoCuentaRetencionCREEFk;
    }

    /**
     * Set tipoCuentaRetencionIva
     *
     * @param integer $tipoCuentaRetencionIva
     * @return InvDocumentos
     */
    public function setTipoCuentaRetencionIva($tipoCuentaRetencionIva)
    {
        $this->tipoCuentaRetencionIva = $tipoCuentaRetencionIva;

        return $this;
    }

    /**
     * Get tipoCuentaRetencionIva
     *
     * @return integer 
     */
    public function getTipoCuentaRetencionIva()
    {
        return $this->tipoCuentaRetencionIva;
    }

    /**
     * Set codigoCuentaRetencionIvaFk
     *
     * @param string $codigoCuentaRetencionIvaFk
     * @return InvDocumentos
     */
    public function setCodigoCuentaRetencionIvaFk($codigoCuentaRetencionIvaFk)
    {
        $this->codigoCuentaRetencionIvaFk = $codigoCuentaRetencionIvaFk;

        return $this;
    }

    /**
     * Get codigoCuentaRetencionIvaFk
     *
     * @return string 
     */
    public function getCodigoCuentaRetencionIvaFk()
    {
        return $this->codigoCuentaRetencionIvaFk;
    }

    /**
     * Set tipoCuentaTesoreria
     *
     * @param integer $tipoCuentaTesoreria
     * @return InvDocumentos
     */
    public function setTipoCuentaTesoreria($tipoCuentaTesoreria)
    {
        $this->tipoCuentaTesoreria = $tipoCuentaTesoreria;

        return $this;
    }

    /**
     * Get tipoCuentaTesoreria
     *
     * @return integer 
     */
    public function getTipoCuentaTesoreria()
    {
        return $this->tipoCuentaTesoreria;
    }

    /**
     * Set codigoCuentaTesoreriaFk
     *
     * @param string $codigoCuentaTesoreriaFk
     * @return InvDocumentos
     */
    public function setCodigoCuentaTesoreriaFk($codigoCuentaTesoreriaFk)
    {
        $this->codigoCuentaTesoreriaFk = $codigoCuentaTesoreriaFk;

        return $this;
    }

    /**
     * Get codigoCuentaTesoreriaFk
     *
     * @return string 
     */
    public function getCodigoCuentaTesoreriaFk()
    {
        return $this->codigoCuentaTesoreriaFk;
    }

    /**
     * Set tipoCuentaCartera
     *
     * @param integer $tipoCuentaCartera
     * @return InvDocumentos
     */
    public function setTipoCuentaCartera($tipoCuentaCartera)
    {
        $this->tipoCuentaCartera = $tipoCuentaCartera;

        return $this;
    }

    /**
     * Get tipoCuentaCartera
     *
     * @return integer 
     */
    public function getTipoCuentaCartera()
    {
        return $this->tipoCuentaCartera;
    }

    /**
     * Set codigoCuentaCarteraFk
     *
     * @param string $codigoCuentaCarteraFk
     * @return InvDocumentos
     */
    public function setCodigoCuentaCarteraFk($codigoCuentaCarteraFk)
    {
        $this->codigoCuentaCarteraFk = $codigoCuentaCarteraFk;

        return $this;
    }

    /**
     * Get codigoCuentaCarteraFk
     *
     * @return string 
     */
    public function getCodigoCuentaCarteraFk()
    {
        return $this->codigoCuentaCarteraFk;
    }

    /**
     * Set asignarConsecutivoImpresion
     *
     * @param boolean $asignarConsecutivoImpresion
     * @return InvDocumentos
     */
    public function setAsignarConsecutivoImpresion($asignarConsecutivoImpresion)
    {
        $this->asignarConsecutivoImpresion = $asignarConsecutivoImpresion;

        return $this;
    }

    /**
     * Get asignarConsecutivoImpresion
     *
     * @return boolean 
     */
    public function getAsignarConsecutivoImpresion()
    {
        return $this->asignarConsecutivoImpresion;
    }

    /**
     * Set generaCostoPromedio
     *
     * @param boolean $generaCostoPromedio
     * @return InvDocumentos
     */
    public function setGeneraCostoPromedio($generaCostoPromedio)
    {
        $this->generaCostoPromedio = $generaCostoPromedio;

        return $this;
    }

    /**
     * Get generaCostoPromedio
     *
     * @return boolean 
     */
    public function getGeneraCostoPromedio()
    {
        return $this->generaCostoPromedio;
    }

    /**
     * Set tipoTercero
     *
     * @param integer $tipoTercero
     * @return InvDocumentos
     */
    public function setTipoTercero($tipoTercero)
    {
        $this->tipoTercero = $tipoTercero;

        return $this;
    }

    /**
     * Get tipoTercero
     *
     * @return integer 
     */
    public function getTipoTercero()
    {
        return $this->tipoTercero;
    }
}
