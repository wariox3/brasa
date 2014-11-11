<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_guias")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteGuiasRepository")
 */
class TteGuias
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_guia_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoGuiaPk;
    
    /**
     * @ORM\Column(name="numero_guia", type="integer", nullable=true)
     */    
    private $numeroGuia;    
    
    /**
     * @ORM\Column(name="fecha_ingreso", type="datetime", nullable=true)
     */    
    private $fechaIngreso;        

    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     */    
    private $codigoTerceroFk;    
    
    /**
     * @ORM\Column(name="codigo_despacho_fk", type="integer", nullable=true)
     */    
    private $codigoDespachoFk;    

    /**
     * @ORM\Column(name="codigo_factura_fk", type="integer", nullable=true)
     */    
    private $codigoFacturaFk; 
    
    /**
     * @ORM\Column(name="documento_cliente", type="string", length=60, nullable=true)
     */    
    private $documentoCliente;    

    /**
     * @ORM\Column(name="nombre_destinatario", type="string", length=80, nullable=true)
     */    
    private $nombreDestinatario;
    
    /**
     * @ORM\Column(name="direccion_destinatario", type="string", length=80, nullable=true)
     */    
    private $direccionDestinatario;    
    
    /**
     * @ORM\Column(name="telefono_destinatario", type="string", length=15, nullable=true)
     */    
    private $telefonoDestinatario;        
    
    /**
     * @ORM\Column(name="codigo_ciudad_origen_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadOrigenFk;     

    /**
     * @ORM\Column(name="codigo_ciudad_destino_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadDestinoFk;    
    
    /**
     * @ORM\Column(name="codigo_ruta_fk", type="integer", nullable=true)
     */    
    private $codigoRutaFk;     
    
    /**
     * @ORM\Column(name="codigo_tipo_servicio_fk", type="integer", nullable=true)
     */    
    private $codigoTipoServicioFk;    
    
    /**
     * @ORM\Column(name="codigo_tipo_pago_fk", type="integer", nullable=true)
     */    
    private $codigoTipoPagoFk;     

    /**
     * @ORM\Column(name="codigo_producto_fk", type="integer", nullable=true)
     */    
    private $codigoProductoFk;    
    
    /**
     * @ORM\Column(name="codigo_punto_operacion_ingreso_fk", type="integer", nullable=true)
     */    
    private $codigoPuntoOperacionIngresoFk;     

    /**
     * @ORM\Column(name="codigo_punto_operacion_actual_fk", type="integer", nullable=true)
     */    
    private $codigoPuntoOperacionActualFk;     
       
    /**
     * @ORM\Column(name="ct_unidades", type="integer")
     */
    private $ctUnidades = 0;

    /**
     * @ORM\Column(name="ct_peso_real", type="integer")
     */
    private $ctPesoReal = 0;    

    /**
     * @ORM\Column(name="ct_peso_volumen", type="integer")
     */
    private $ctPesoVolumen = 0;    
    
    /**
     * @ORM\Column(name="ct_peso_liquidar", type="integer")
     */
    private $ctPesoLiquidar = 0;    

    /**
     * @ORM\Column(name="vr_declarado", type="float")
     */
    private $vrDeclarado = 0;    
    
    /**
     * @ORM\Column(name="vr_flete", type="float")
     */
    private $vrFlete = 0;
    
    /**
     * @ORM\Column(name="vr_manejo", type="float")
     */
    private $vrManejo = 0;

    /**
     * @ORM\Column(name="vr_recaudo", type="float")
     */
    private $vrRecaudo = 0;    
    
    /**
     * @ORM\Column(name="vr_abonos_flete", type="float")
     */
    private $vrAbonosFlete = 0;    

    /**
     * @ORM\Column(name="vr_abonos_manejo", type="float")
     */
    private $vrAbonosManejo = 0;        
    
    /**
     * @ORM\Column(name="vr_neto", type="float")
     */
    private $vrNeto = 0;    
    
    /**
     * @ORM\Column(name="estado_impreso", type="boolean")
     */    
    private $estadoImpreso = 0;             

    /**
     * @ORM\Column(name="estado_anulada", type="boolean")
     */    
    private $estadoAnulada = 0;    

    /**
     * @ORM\Column(name="estado_despachada", type="boolean")
     */    
    private $estadoDespachada = 0;    
    
    /**
     * @ORM\Column(name="estado_entregada", type="boolean")
     */    
    private $estadoEntregada = 0;    
    
    /**
     * @ORM\Column(name="estado_descargada", type="boolean")
     */    
    private $estadoDescargada = 0;    

    /**
     * @ORM\Column(name="estado_generada", type="boolean")
     */    
    private $estadoGenerada = 0;     
    
    /**
     * @ORM\Column(name="fecha_entrega", type="datetime", nullable=true)
     */    
    private $fechaEntrega;
    
    /**
     * @ORM\Column(name="fecha_descargada", type="datetime", nullable=true)
     */    
    private $fechaDescargada;    
    
    /**
     * @ORM\Column(name="contenido", type="string", length=500, nullable=true)
     */    
    private $contenido;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=500, nullable=true)
     */    
    private $comentarios;    
    
    /**
     * @ORM\Column(name="forma_liquidacion", type="integer", nullable=true)
     */    
    private $formaLiquidacion = 1;     
    
    /**
     * @ORM\OneToMany(targetEntity="TteNovedades", mappedBy="guiaRel")
     */
    protected $novedadesRel;     

    /**
     * @ORM\OneToMany(targetEntity="TteRecibosCaja", mappedBy="guiaRel")
     */
    protected $recibosCajaRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TteGuiasCobrosAdicionales", mappedBy="guiaRel")
     */
    protected $guiasCobrosAdicionalesRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TteDespachos", inversedBy="guiasDetallesRel")
     * @ORM\JoinColumn(name="codigo_despacho_fk", referencedColumnName="codigo_despacho_pk")
     */
    protected $despachoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TteFacturas", inversedBy="guiasDetallesRel")
     * @ORM\JoinColumn(name="codigo_factura_fk", referencedColumnName="codigo_factura_pk")
     */
    protected $facturaRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudades", inversedBy="guiasCiudadOrigenRel")
     * @ORM\JoinColumn(name="codigo_ciudad_origen_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadOrigenRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudades", inversedBy="guiasCiudadDestinoRel")
     * @ORM\JoinColumn(name="codigo_ciudad_destino_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadDestinoRel;     
        
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenTerceros", inversedBy="guiasRel")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;    

    /**
     * @ORM\ManyToOne(targetEntity="TteRutas", inversedBy="guiasRel")
     * @ORM\JoinColumn(name="codigo_ruta_fk", referencedColumnName="codigo_ruta_pk")
     */
    protected $rutaRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TteTiposServicio", inversedBy="guiasRel")
     * @ORM\JoinColumn(name="codigo_tipo_servicio_fk", referencedColumnName="codigo_tipo_servicio_pk")
     */
    protected $tipoServicioRel; 
    
    /**
     * @ORM\ManyToOne(targetEntity="TteTiposPago", inversedBy="guiasRel")
     * @ORM\JoinColumn(name="codigo_tipo_pago_fk", referencedColumnName="codigo_tipo_pago_pk")
     */
    protected $tipoPagoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="TteProductos", inversedBy="guiasRel")
     * @ORM\JoinColumn(name="codigo_producto_fk", referencedColumnName="codigo_producto_pk")
     */
    protected $productoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TtePuntosOperacion", inversedBy="guiasPuntoOperacionIngresoRel")
     * @ORM\JoinColumn(name="codigo_punto_operacion_ingreso_fk", referencedColumnName="codigo_punto_operacion_pk")
     */
    protected $puntoOperacionIngresoRel;     

    /**
     * @ORM\ManyToOne(targetEntity="TtePuntosOperacion", inversedBy="guiasPuntoOperacionActualRel")
     * @ORM\JoinColumn(name="codigo_punto_operacion_actual_fk", referencedColumnName="codigo_punto_operacion_pk")
     */
    protected $puntoOperacionActualRel;    
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->novedadesRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->recibosCajaRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->guiasCobrosAdicionalesRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoGuiaPk
     *
     * @return integer 
     */
    public function getCodigoGuiaPk()
    {
        return $this->codigoGuiaPk;
    }

    /**
     * Set numeroGuia
     *
     * @param integer $numeroGuia
     * @return TteGuias
     */
    public function setNumeroGuia($numeroGuia)
    {
        $this->numeroGuia = $numeroGuia;

        return $this;
    }

    /**
     * Get numeroGuia
     *
     * @return integer 
     */
    public function getNumeroGuia()
    {
        return $this->numeroGuia;
    }

    /**
     * Set fechaIngreso
     *
     * @param \DateTime $fechaIngreso
     * @return TteGuias
     */
    public function setFechaIngreso($fechaIngreso)
    {
        $this->fechaIngreso = $fechaIngreso;

        return $this;
    }

    /**
     * Get fechaIngreso
     *
     * @return \DateTime 
     */
    public function getFechaIngreso()
    {
        return $this->fechaIngreso;
    }

    /**
     * Set codigoTerceroFk
     *
     * @param integer $codigoTerceroFk
     * @return TteGuias
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
     * Set codigoDespachoFk
     *
     * @param integer $codigoDespachoFk
     * @return TteGuias
     */
    public function setCodigoDespachoFk($codigoDespachoFk)
    {
        $this->codigoDespachoFk = $codigoDespachoFk;

        return $this;
    }

    /**
     * Get codigoDespachoFk
     *
     * @return integer 
     */
    public function getCodigoDespachoFk()
    {
        return $this->codigoDespachoFk;
    }

    /**
     * Set codigoFacturaFk
     *
     * @param integer $codigoFacturaFk
     * @return TteGuias
     */
    public function setCodigoFacturaFk($codigoFacturaFk)
    {
        $this->codigoFacturaFk = $codigoFacturaFk;

        return $this;
    }

    /**
     * Get codigoFacturaFk
     *
     * @return integer 
     */
    public function getCodigoFacturaFk()
    {
        return $this->codigoFacturaFk;
    }

    /**
     * Set documentoCliente
     *
     * @param string $documentoCliente
     * @return TteGuias
     */
    public function setDocumentoCliente($documentoCliente)
    {
        $this->documentoCliente = $documentoCliente;

        return $this;
    }

    /**
     * Get documentoCliente
     *
     * @return string 
     */
    public function getDocumentoCliente()
    {
        return $this->documentoCliente;
    }

    /**
     * Set nombreDestinatario
     *
     * @param string $nombreDestinatario
     * @return TteGuias
     */
    public function setNombreDestinatario($nombreDestinatario)
    {
        $this->nombreDestinatario = $nombreDestinatario;

        return $this;
    }

    /**
     * Get nombreDestinatario
     *
     * @return string 
     */
    public function getNombreDestinatario()
    {
        return $this->nombreDestinatario;
    }

    /**
     * Set direccionDestinatario
     *
     * @param string $direccionDestinatario
     * @return TteGuias
     */
    public function setDireccionDestinatario($direccionDestinatario)
    {
        $this->direccionDestinatario = $direccionDestinatario;

        return $this;
    }

    /**
     * Get direccionDestinatario
     *
     * @return string 
     */
    public function getDireccionDestinatario()
    {
        return $this->direccionDestinatario;
    }

    /**
     * Set telefonoDestinatario
     *
     * @param string $telefonoDestinatario
     * @return TteGuias
     */
    public function setTelefonoDestinatario($telefonoDestinatario)
    {
        $this->telefonoDestinatario = $telefonoDestinatario;

        return $this;
    }

    /**
     * Get telefonoDestinatario
     *
     * @return string 
     */
    public function getTelefonoDestinatario()
    {
        return $this->telefonoDestinatario;
    }

    /**
     * Set codigoCiudadOrigenFk
     *
     * @param integer $codigoCiudadOrigenFk
     * @return TteGuias
     */
    public function setCodigoCiudadOrigenFk($codigoCiudadOrigenFk)
    {
        $this->codigoCiudadOrigenFk = $codigoCiudadOrigenFk;

        return $this;
    }

    /**
     * Get codigoCiudadOrigenFk
     *
     * @return integer 
     */
    public function getCodigoCiudadOrigenFk()
    {
        return $this->codigoCiudadOrigenFk;
    }

    /**
     * Set codigoCiudadDestinoFk
     *
     * @param integer $codigoCiudadDestinoFk
     * @return TteGuias
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
     * Set codigoRutaFk
     *
     * @param integer $codigoRutaFk
     * @return TteGuias
     */
    public function setCodigoRutaFk($codigoRutaFk)
    {
        $this->codigoRutaFk = $codigoRutaFk;

        return $this;
    }

    /**
     * Get codigoRutaFk
     *
     * @return integer 
     */
    public function getCodigoRutaFk()
    {
        return $this->codigoRutaFk;
    }

    /**
     * Set codigoTipoServicioFk
     *
     * @param integer $codigoTipoServicioFk
     * @return TteGuias
     */
    public function setCodigoTipoServicioFk($codigoTipoServicioFk)
    {
        $this->codigoTipoServicioFk = $codigoTipoServicioFk;

        return $this;
    }

    /**
     * Get codigoTipoServicioFk
     *
     * @return integer 
     */
    public function getCodigoTipoServicioFk()
    {
        return $this->codigoTipoServicioFk;
    }

    /**
     * Set codigoTipoPagoFk
     *
     * @param integer $codigoTipoPagoFk
     * @return TteGuias
     */
    public function setCodigoTipoPagoFk($codigoTipoPagoFk)
    {
        $this->codigoTipoPagoFk = $codigoTipoPagoFk;

        return $this;
    }

    /**
     * Get codigoTipoPagoFk
     *
     * @return integer 
     */
    public function getCodigoTipoPagoFk()
    {
        return $this->codigoTipoPagoFk;
    }

    /**
     * Set codigoProductoFk
     *
     * @param integer $codigoProductoFk
     * @return TteGuias
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
     * Set codigoPuntoOperacionIngresoFk
     *
     * @param integer $codigoPuntoOperacionIngresoFk
     * @return TteGuias
     */
    public function setCodigoPuntoOperacionIngresoFk($codigoPuntoOperacionIngresoFk)
    {
        $this->codigoPuntoOperacionIngresoFk = $codigoPuntoOperacionIngresoFk;

        return $this;
    }

    /**
     * Get codigoPuntoOperacionIngresoFk
     *
     * @return integer 
     */
    public function getCodigoPuntoOperacionIngresoFk()
    {
        return $this->codigoPuntoOperacionIngresoFk;
    }

    /**
     * Set codigoPuntoOperacionActualFk
     *
     * @param integer $codigoPuntoOperacionActualFk
     * @return TteGuias
     */
    public function setCodigoPuntoOperacionActualFk($codigoPuntoOperacionActualFk)
    {
        $this->codigoPuntoOperacionActualFk = $codigoPuntoOperacionActualFk;

        return $this;
    }

    /**
     * Get codigoPuntoOperacionActualFk
     *
     * @return integer 
     */
    public function getCodigoPuntoOperacionActualFk()
    {
        return $this->codigoPuntoOperacionActualFk;
    }

    /**
     * Set ctUnidades
     *
     * @param integer $ctUnidades
     * @return TteGuias
     */
    public function setCtUnidades($ctUnidades)
    {
        $this->ctUnidades = $ctUnidades;

        return $this;
    }

    /**
     * Get ctUnidades
     *
     * @return integer 
     */
    public function getCtUnidades()
    {
        return $this->ctUnidades;
    }

    /**
     * Set ctPesoReal
     *
     * @param integer $ctPesoReal
     * @return TteGuias
     */
    public function setCtPesoReal($ctPesoReal)
    {
        $this->ctPesoReal = $ctPesoReal;

        return $this;
    }

    /**
     * Get ctPesoReal
     *
     * @return integer 
     */
    public function getCtPesoReal()
    {
        return $this->ctPesoReal;
    }

    /**
     * Set ctPesoVolumen
     *
     * @param integer $ctPesoVolumen
     * @return TteGuias
     */
    public function setCtPesoVolumen($ctPesoVolumen)
    {
        $this->ctPesoVolumen = $ctPesoVolumen;

        return $this;
    }

    /**
     * Get ctPesoVolumen
     *
     * @return integer 
     */
    public function getCtPesoVolumen()
    {
        return $this->ctPesoVolumen;
    }

    /**
     * Set ctPesoLiquidar
     *
     * @param integer $ctPesoLiquidar
     * @return TteGuias
     */
    public function setCtPesoLiquidar($ctPesoLiquidar)
    {
        $this->ctPesoLiquidar = $ctPesoLiquidar;

        return $this;
    }

    /**
     * Get ctPesoLiquidar
     *
     * @return integer 
     */
    public function getCtPesoLiquidar()
    {
        return $this->ctPesoLiquidar;
    }

    /**
     * Set vrDeclarado
     *
     * @param float $vrDeclarado
     * @return TteGuias
     */
    public function setVrDeclarado($vrDeclarado)
    {
        $this->vrDeclarado = $vrDeclarado;

        return $this;
    }

    /**
     * Get vrDeclarado
     *
     * @return float 
     */
    public function getVrDeclarado()
    {
        return $this->vrDeclarado;
    }

    /**
     * Set vrFlete
     *
     * @param float $vrFlete
     * @return TteGuias
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
     * @return TteGuias
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
     * Set vrRecaudo
     *
     * @param float $vrRecaudo
     * @return TteGuias
     */
    public function setVrRecaudo($vrRecaudo)
    {
        $this->vrRecaudo = $vrRecaudo;

        return $this;
    }

    /**
     * Get vrRecaudo
     *
     * @return float 
     */
    public function getVrRecaudo()
    {
        return $this->vrRecaudo;
    }

    /**
     * Set vrAbonosFlete
     *
     * @param float $vrAbonosFlete
     * @return TteGuias
     */
    public function setVrAbonosFlete($vrAbonosFlete)
    {
        $this->vrAbonosFlete = $vrAbonosFlete;

        return $this;
    }

    /**
     * Get vrAbonosFlete
     *
     * @return float 
     */
    public function getVrAbonosFlete()
    {
        return $this->vrAbonosFlete;
    }

    /**
     * Set vrAbonosManejo
     *
     * @param float $vrAbonosManejo
     * @return TteGuias
     */
    public function setVrAbonosManejo($vrAbonosManejo)
    {
        $this->vrAbonosManejo = $vrAbonosManejo;

        return $this;
    }

    /**
     * Get vrAbonosManejo
     *
     * @return float 
     */
    public function getVrAbonosManejo()
    {
        return $this->vrAbonosManejo;
    }

    /**
     * Set vrNeto
     *
     * @param float $vrNeto
     * @return TteGuias
     */
    public function setVrNeto($vrNeto)
    {
        $this->vrNeto = $vrNeto;

        return $this;
    }

    /**
     * Get vrNeto
     *
     * @return float 
     */
    public function getVrNeto()
    {
        return $this->vrNeto;
    }

    /**
     * Set estadoImpreso
     *
     * @param boolean $estadoImpreso
     * @return TteGuias
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
     * Set estadoAnulada
     *
     * @param boolean $estadoAnulada
     * @return TteGuias
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
     * Set estadoDespachada
     *
     * @param boolean $estadoDespachada
     * @return TteGuias
     */
    public function setEstadoDespachada($estadoDespachada)
    {
        $this->estadoDespachada = $estadoDespachada;

        return $this;
    }

    /**
     * Get estadoDespachada
     *
     * @return boolean 
     */
    public function getEstadoDespachada()
    {
        return $this->estadoDespachada;
    }

    /**
     * Set estadoEntregada
     *
     * @param boolean $estadoEntregada
     * @return TteGuias
     */
    public function setEstadoEntregada($estadoEntregada)
    {
        $this->estadoEntregada = $estadoEntregada;

        return $this;
    }

    /**
     * Get estadoEntregada
     *
     * @return boolean 
     */
    public function getEstadoEntregada()
    {
        return $this->estadoEntregada;
    }

    /**
     * Set estadoDescargada
     *
     * @param boolean $estadoDescargada
     * @return TteGuias
     */
    public function setEstadoDescargada($estadoDescargada)
    {
        $this->estadoDescargada = $estadoDescargada;

        return $this;
    }

    /**
     * Get estadoDescargada
     *
     * @return boolean 
     */
    public function getEstadoDescargada()
    {
        return $this->estadoDescargada;
    }

    /**
     * Set estadoGenerada
     *
     * @param boolean $estadoGenerada
     * @return TteGuias
     */
    public function setEstadoGenerada($estadoGenerada)
    {
        $this->estadoGenerada = $estadoGenerada;

        return $this;
    }

    /**
     * Get estadoGenerada
     *
     * @return boolean 
     */
    public function getEstadoGenerada()
    {
        return $this->estadoGenerada;
    }

    /**
     * Set fechaEntrega
     *
     * @param \DateTime $fechaEntrega
     * @return TteGuias
     */
    public function setFechaEntrega($fechaEntrega)
    {
        $this->fechaEntrega = $fechaEntrega;

        return $this;
    }

    /**
     * Get fechaEntrega
     *
     * @return \DateTime 
     */
    public function getFechaEntrega()
    {
        return $this->fechaEntrega;
    }

    /**
     * Set fechaDescargada
     *
     * @param \DateTime $fechaDescargada
     * @return TteGuias
     */
    public function setFechaDescargada($fechaDescargada)
    {
        $this->fechaDescargada = $fechaDescargada;

        return $this;
    }

    /**
     * Get fechaDescargada
     *
     * @return \DateTime 
     */
    public function getFechaDescargada()
    {
        return $this->fechaDescargada;
    }

    /**
     * Set contenido
     *
     * @param string $contenido
     * @return TteGuias
     */
    public function setContenido($contenido)
    {
        $this->contenido = $contenido;

        return $this;
    }

    /**
     * Get contenido
     *
     * @return string 
     */
    public function getContenido()
    {
        return $this->contenido;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     * @return TteGuias
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
     * Set formaLiquidacion
     *
     * @param integer $formaLiquidacion
     * @return TteGuias
     */
    public function setFormaLiquidacion($formaLiquidacion)
    {
        $this->formaLiquidacion = $formaLiquidacion;

        return $this;
    }

    /**
     * Get formaLiquidacion
     *
     * @return integer 
     */
    public function getFormaLiquidacion()
    {
        return $this->formaLiquidacion;
    }

    /**
     * Add novedadesRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteNovedades $novedadesRel
     * @return TteGuias
     */
    public function addNovedadesRel(\Brasa\TransporteBundle\Entity\TteNovedades $novedadesRel)
    {
        $this->novedadesRel[] = $novedadesRel;

        return $this;
    }

    /**
     * Remove novedadesRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteNovedades $novedadesRel
     */
    public function removeNovedadesRel(\Brasa\TransporteBundle\Entity\TteNovedades $novedadesRel)
    {
        $this->novedadesRel->removeElement($novedadesRel);
    }

    /**
     * Get novedadesRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNovedadesRel()
    {
        return $this->novedadesRel;
    }

    /**
     * Add recibosCajaRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteRecibosCaja $recibosCajaRel
     * @return TteGuias
     */
    public function addRecibosCajaRel(\Brasa\TransporteBundle\Entity\TteRecibosCaja $recibosCajaRel)
    {
        $this->recibosCajaRel[] = $recibosCajaRel;

        return $this;
    }

    /**
     * Remove recibosCajaRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteRecibosCaja $recibosCajaRel
     */
    public function removeRecibosCajaRel(\Brasa\TransporteBundle\Entity\TteRecibosCaja $recibosCajaRel)
    {
        $this->recibosCajaRel->removeElement($recibosCajaRel);
    }

    /**
     * Get recibosCajaRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRecibosCajaRel()
    {
        return $this->recibosCajaRel;
    }

    /**
     * Add guiasCobrosAdicionalesRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuiasCobrosAdicionales $guiasCobrosAdicionalesRel
     * @return TteGuias
     */
    public function addGuiasCobrosAdicionalesRel(\Brasa\TransporteBundle\Entity\TteGuiasCobrosAdicionales $guiasCobrosAdicionalesRel)
    {
        $this->guiasCobrosAdicionalesRel[] = $guiasCobrosAdicionalesRel;

        return $this;
    }

    /**
     * Remove guiasCobrosAdicionalesRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuiasCobrosAdicionales $guiasCobrosAdicionalesRel
     */
    public function removeGuiasCobrosAdicionalesRel(\Brasa\TransporteBundle\Entity\TteGuiasCobrosAdicionales $guiasCobrosAdicionalesRel)
    {
        $this->guiasCobrosAdicionalesRel->removeElement($guiasCobrosAdicionalesRel);
    }

    /**
     * Get guiasCobrosAdicionalesRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGuiasCobrosAdicionalesRel()
    {
        return $this->guiasCobrosAdicionalesRel;
    }

    /**
     * Set despachoRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteDespachos $despachoRel
     * @return TteGuias
     */
    public function setDespachoRel(\Brasa\TransporteBundle\Entity\TteDespachos $despachoRel = null)
    {
        $this->despachoRel = $despachoRel;

        return $this;
    }

    /**
     * Get despachoRel
     *
     * @return \Brasa\TransporteBundle\Entity\TteDespachos 
     */
    public function getDespachoRel()
    {
        return $this->despachoRel;
    }

    /**
     * Set facturaRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteFacturas $facturaRel
     * @return TteGuias
     */
    public function setFacturaRel(\Brasa\TransporteBundle\Entity\TteFacturas $facturaRel = null)
    {
        $this->facturaRel = $facturaRel;

        return $this;
    }

    /**
     * Get facturaRel
     *
     * @return \Brasa\TransporteBundle\Entity\TteFacturas 
     */
    public function getFacturaRel()
    {
        return $this->facturaRel;
    }

    /**
     * Set ciudadOrigenRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudades $ciudadOrigenRel
     * @return TteGuias
     */
    public function setCiudadOrigenRel(\Brasa\GeneralBundle\Entity\GenCiudades $ciudadOrigenRel = null)
    {
        $this->ciudadOrigenRel = $ciudadOrigenRel;

        return $this;
    }

    /**
     * Get ciudadOrigenRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudades 
     */
    public function getCiudadOrigenRel()
    {
        return $this->ciudadOrigenRel;
    }

    /**
     * Set ciudadDestinoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudades $ciudadDestinoRel
     * @return TteGuias
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
     * Set terceroRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTerceros $terceroRel
     * @return TteGuias
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
     * Set rutaRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteRutas $rutaRel
     * @return TteGuias
     */
    public function setRutaRel(\Brasa\TransporteBundle\Entity\TteRutas $rutaRel = null)
    {
        $this->rutaRel = $rutaRel;

        return $this;
    }

    /**
     * Get rutaRel
     *
     * @return \Brasa\TransporteBundle\Entity\TteRutas 
     */
    public function getRutaRel()
    {
        return $this->rutaRel;
    }

    /**
     * Set tipoServicioRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteTiposServicio $tipoServicioRel
     * @return TteGuias
     */
    public function setTipoServicioRel(\Brasa\TransporteBundle\Entity\TteTiposServicio $tipoServicioRel = null)
    {
        $this->tipoServicioRel = $tipoServicioRel;

        return $this;
    }

    /**
     * Get tipoServicioRel
     *
     * @return \Brasa\TransporteBundle\Entity\TteTiposServicio 
     */
    public function getTipoServicioRel()
    {
        return $this->tipoServicioRel;
    }

    /**
     * Set tipoPagoRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteTiposPago $tipoPagoRel
     * @return TteGuias
     */
    public function setTipoPagoRel(\Brasa\TransporteBundle\Entity\TteTiposPago $tipoPagoRel = null)
    {
        $this->tipoPagoRel = $tipoPagoRel;

        return $this;
    }

    /**
     * Get tipoPagoRel
     *
     * @return \Brasa\TransporteBundle\Entity\TteTiposPago 
     */
    public function getTipoPagoRel()
    {
        return $this->tipoPagoRel;
    }

    /**
     * Set productoRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteProductos $productoRel
     * @return TteGuias
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
     * Set puntoOperacionIngresoRel
     *
     * @param \Brasa\TransporteBundle\Entity\TtePuntosOperacion $puntoOperacionIngresoRel
     * @return TteGuias
     */
    public function setPuntoOperacionIngresoRel(\Brasa\TransporteBundle\Entity\TtePuntosOperacion $puntoOperacionIngresoRel = null)
    {
        $this->puntoOperacionIngresoRel = $puntoOperacionIngresoRel;

        return $this;
    }

    /**
     * Get puntoOperacionIngresoRel
     *
     * @return \Brasa\TransporteBundle\Entity\TtePuntosOperacion 
     */
    public function getPuntoOperacionIngresoRel()
    {
        return $this->puntoOperacionIngresoRel;
    }

    /**
     * Set puntoOperacionActualRel
     *
     * @param \Brasa\TransporteBundle\Entity\TtePuntosOperacion $puntoOperacionActualRel
     * @return TteGuias
     */
    public function setPuntoOperacionActualRel(\Brasa\TransporteBundle\Entity\TtePuntosOperacion $puntoOperacionActualRel = null)
    {
        $this->puntoOperacionActualRel = $puntoOperacionActualRel;

        return $this;
    }

    /**
     * Get puntoOperacionActualRel
     *
     * @return \Brasa\TransporteBundle\Entity\TtePuntosOperacion 
     */
    public function getPuntoOperacionActualRel()
    {
        return $this->puntoOperacionActualRel;
    }
}
