<?php

namespace Brasa\GeneralBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_terceros")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenTercerosRepository")
 */
class GenTerceros
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_tercero_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoTerceroPk;

    /**
     * @ORM\Column(name="nit", type="string", length=11)
     */
    private $nit;    
    
    /**
     * @ORM\Column(name="digito_verificacion", type="string", length=1, nullable=true)
     */
    private $digitoVerificacion;     
    
    /**
     * @ORM\Column(name="nombre_corto", type="string", length=50)
     */
    private $nombreCorto;   
    
    /**
     * @ORM\Column(name="nombres", type="string", length=50, nullable=true)
     */
    private $nombres;

     /**
     * @ORM\Column(name="apellido1", type="string", length=50, nullable=true)
     */   
    private $apellido1;

    /**
     * @ORM\Column(name="apellido2", type="string", length=50, nullable=true)
     */
    private $apellido2;

    /**
     * @ORM\Column(name="codigo_asesor_fk", type="integer", nullable=true)
     */    
    private $codigoAsesorFk;    
    
    /**
     * @ORM\Column(name="codigo_lista_precio_fk", type="integer", nullable=true)
     */    
    private $codigoListaPrecioFk;

    /**
     * @ORM\Column(name="codigo_lista_costo_fk", type="integer", nullable=true)
     */    
    private $codigoListaCostoFk;    

    /**
     * @ORM\Column(name="codigo_clasificacion_tributaria_fk", type="integer", nullable=true)
     */    
    private $codigoClasificacionTributariaFk;    

    /**
     * @ORM\Column(name="codigo_forma_pago_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoFormaPagoClienteFk;        

    /**
     * @ORM\Column(name="plazo_pago", type="integer")
     */    
    private $plazoPagoCliente = 0;
    
    /**
     * @ORM\Column(name="codigo_forma_pago_proveedor_fk", type="integer", nullable=true)
     */    
    private $codigoFormaPagoProveedorFk;    
    
    /**
     * @ORM\Column(name="plazo_pago_proveedor", type="integer")
     */    
    private $plazoPagoProveedor = 0;    
    
    /**
     * @ORM\Column(name="direccion", type="string", length=80, nullable=true)
     */
    private $direccion;    

    /**
     * @ORM\Column(name="telefono", type="string", length=20, nullable=true)
     */
    private $telefono;    

    /**
     * @ORM\Column(name="celular", type="string", length=20, nullable=true)
     */
    private $celular;    
    
    
    /**
     * @ORM\Column(name="fax", type="string", length=20, nullable=true)
     */
    private $fax;    
    
    /**
     * @ORM\Column(name="email", type="string", length=80, nullable=true)
     */
    private $email;    

    /**
     * @ORM\Column(name="retencion_fuente_ventas", type="boolean", nullable=true)
     */    
    private $retencionFuenteVentas;    

    /**
     * @ORM\Column(name="retencion_fuente_ventas_sin_base", type="boolean", nullable=true)
     */    
    private $retencionFuenteVentasSinBase;    
    
    
    /**
     * @ORM\Column(name="autoretenedor", type="boolean", nullable=true)
     */    
    private $autoretenedor ;    

    /**
     * @ORM\Column(name="contacto_cliente", type="string", length=80, nullable=true)
     */
    private $contactoCliente;    

    /**
     * @ORM\Column(name="calular_contacto_cliente", type="string", length=20, nullable=true)
     */
    private $celularContactoCliente;        
    
    /**
     * @ORM\Column(name="contacto_proveedor", type="string", length=80, nullable=true)
     */
    private $contactoProveedor;    

    /**
     * @ORM\Column(name="celular_contacto_proveedor", type="string", length=20, nullable=true)
     */
    private $celularContactoProveedor;    
    
    /**
     * @ORM\Column(name="codigo_actividad_economica", type="integer", nullable=true)
     */
    private $codigoActividadEconomica;    
    
    /**
     * @ORM\Column(name="pocertaje_cree", type="float")
     */
    private $porcentajeCREE = 0;    
    
    /**
     * @ORM\ManyToOne(targetEntity="GenClasificacionesTributarias", inversedBy="tercerosClasificacionTributariaRel")
     * @ORM\JoinColumn(name="codigo_clasificacion_tributaria_fk", referencedColumnName="codigo_clasificacion_tributaria_pk")
     */
    protected $clasificacionTributariaRel;        

    /**
     * @ORM\ManyToOne(targetEntity="GenFormasPago", inversedBy="tercerosFormaPagoClienteRel")
     * @ORM\JoinColumn(name="codigo_forma_pago_cliente_fk", referencedColumnName="codigo_forma_pago_pk")
     */
    protected $formaPagoClienteRel;    

    /**
     * @ORM\ManyToOne(targetEntity="GenFormasPago", inversedBy="tercerosFormaPagoProveedorRel")
     * @ORM\JoinColumn(name="codigo_forma_pago_proveedor_fk", referencedColumnName="codigo_forma_pago_pk")
     */
    protected $formaPagoProveedorRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="GenAsesores", inversedBy="tercerosRel")
     * @ORM\JoinColumn(name="codigo_asesor_fk", referencedColumnName="codigo_asesor_pk")
     */
    protected $asesorRel;    

    /**
     * @ORM\OneToMany(targetEntity="GenTercerosDirecciones", mappedBy="terceroRel")
     */
    protected $tercerosDireccionesRel;

    /**
     * @ORM\OneToMany(targetEntity="Brasa\InventarioBundle\Entity\InvMovimientos", mappedBy="terceroRel")
     */
    protected $movimientosRel;    
          
    /**
     * @ORM\OneToMany(targetEntity="Brasa\TransporteBundle\Entity\TteGuias", mappedBy="terceroRel")
     */
    protected $guiasRel;    

    /**
     * Get codigoTerceroPk
     *
     * @return integer 
     */
    public function getCodigoTerceroPk()
    {
        return $this->codigoTerceroPk;
    }

    /**
     * Set nombreCorto
     *
     * @param string $nombreCorto
     * @return GenTerceros
     */
    public function setNombreCorto($nombreCorto)
    {
        $this->nombreCorto = $nombreCorto;

        return $this;
    }

    /**
     * Get nombreCorto
     *
     * @return string 
     */
    public function getNombreCorto()
    {
        return $this->nombreCorto;
    }

    /**
     * Add movimientosRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimientos $movimientosRel
     * @return GenTerceros
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
     * Set digitoVerificacion
     *
     * @param string $digitoVerificacion
     * @return GenTerceros
     */
    public function setDigitoVerificacion($digitoVerificacion)
    {
        $this->digitoVerificacion = $digitoVerificacion;

        return $this;
    }

    /**
     * Get digitoVerificacion
     *
     * @return string 
     */
    public function getDigitoVerificacion()
    {
        return $this->digitoVerificacion;
    }

    /**
     * Set nombres
     *
     * @param string $nombres
     * @return GenTerceros
     */
    public function setNombres($nombres)
    {
        $this->nombres = $nombres;

        return $this;
    }

    /**
     * Get nombres
     *
     * @return string 
     */
    public function getNombres()
    {
        return $this->nombres;
    }

    /**
     * Set apellido1
     *
     * @param string $apellido1
     * @return GenTerceros
     */
    public function setApellido1($apellido1)
    {
        $this->apellido1 = $apellido1;

        return $this;
    }

    /**
     * Get apellido1
     *
     * @return string 
     */
    public function getApellido1()
    {
        return $this->apellido1;
    }

    /**
     * Set apellido2
     *
     * @param string $apellido2
     * @return GenTerceros
     */
    public function setApellido2($apellido2)
    {
        $this->apellido2 = $apellido2;

        return $this;
    }

    /**
     * Get apellido2
     *
     * @return string 
     */
    public function getApellido2()
    {
        return $this->apellido2;
    }

    /**
     * Set codigoAsesorFk
     *
     * @param integer $codigoAsesorFk
     * @return GenTerceros
     */
    public function setCodigoAsesorFk($codigoAsesorFk)
    {
        $this->codigoAsesorFk = $codigoAsesorFk;

        return $this;
    }

    /**
     * Get codigoAsesorFk
     *
     * @return integer 
     */
    public function getCodigoAsesorFk()
    {
        return $this->codigoAsesorFk;
    }

    /**
     * Set codigoListaPrecioFk
     *
     * @param integer $codigoListaPrecioFk
     * @return GenTerceros
     */
    public function setCodigoListaPrecioFk($codigoListaPrecioFk)
    {
        $this->codigoListaPrecioFk = $codigoListaPrecioFk;

        return $this;
    }

    /**
     * Get codigoListaPrecioFk
     *
     * @return integer 
     */
    public function getCodigoListaPrecioFk()
    {
        return $this->codigoListaPrecioFk;
    }

    /**
     * Set codigoListaCostoFk
     *
     * @param integer $codigoListaCostoFk
     * @return GenTerceros
     */
    public function setCodigoListaCostoFk($codigoListaCostoFk)
    {
        $this->codigoListaCostoFk = $codigoListaCostoFk;

        return $this;
    }

    /**
     * Get codigoListaCostoFk
     *
     * @return integer 
     */
    public function getCodigoListaCostoFk()
    {
        return $this->codigoListaCostoFk;
    }

    /**
     * Set codigoClasificacionTributariaFk
     *
     * @param integer $codigoClasificacionTributariaFk
     * @return GenTerceros
     */
    public function setCodigoClasificacionTributariaFk($codigoClasificacionTributariaFk)
    {
        $this->codigoClasificacionTributariaFk = $codigoClasificacionTributariaFk;

        return $this;
    }

    /**
     * Get codigoClasificacionTributariaFk
     *
     * @return integer 
     */
    public function getCodigoClasificacionTributariaFk()
    {
        return $this->codigoClasificacionTributariaFk;
    }

    /**
     * Set codigoFormaPagoClienteFk
     *
     * @param integer $codigoFormaPagoClienteFk
     * @return GenTerceros
     */
    public function setCodigoFormaPagoClienteFk($codigoFormaPagoClienteFk)
    {
        $this->codigoFormaPagoClienteFk = $codigoFormaPagoClienteFk;

        return $this;
    }

    /**
     * Get codigoFormaPagoClienteFk
     *
     * @return integer 
     */
    public function getCodigoFormaPagoClienteFk()
    {
        return $this->codigoFormaPagoClienteFk;
    }

    /**
     * Set plazoPagoCliente
     *
     * @param integer $plazoPagoCliente
     * @return GenTerceros
     */
    public function setPlazoPagoCliente($plazoPagoCliente)
    {
        $this->plazoPagoCliente = $plazoPagoCliente;

        return $this;
    }

    /**
     * Get plazoPagoCliente
     *
     * @return integer 
     */
    public function getPlazoPagoCliente()
    {
        return $this->plazoPagoCliente;
    }

    /**
     * Set codigoFormaPagoProveedorFk
     *
     * @param integer $codigoFormaPagoProveedorFk
     * @return GenTerceros
     */
    public function setCodigoFormaPagoProveedorFk($codigoFormaPagoProveedorFk)
    {
        $this->codigoFormaPagoProveedorFk = $codigoFormaPagoProveedorFk;

        return $this;
    }

    /**
     * Get codigoFormaPagoProveedorFk
     *
     * @return integer 
     */
    public function getCodigoFormaPagoProveedorFk()
    {
        return $this->codigoFormaPagoProveedorFk;
    }

    /**
     * Set plazoPagoProveedor
     *
     * @param integer $plazoPagoProveedor
     * @return GenTerceros
     */
    public function setPlazoPagoProveedor($plazoPagoProveedor)
    {
        $this->plazoPagoProveedor = $plazoPagoProveedor;

        return $this;
    }

    /**
     * Get plazoPagoProveedor
     *
     * @return integer 
     */
    public function getPlazoPagoProveedor()
    {
        return $this->plazoPagoProveedor;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     * @return GenTerceros
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return GenTerceros
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set celular
     *
     * @param string $celular
     * @return GenTerceros
     */
    public function setCelular($celular)
    {
        $this->celular = $celular;

        return $this;
    }

    /**
     * Get celular
     *
     * @return string 
     */
    public function getCelular()
    {
        return $this->celular;
    }

    /**
     * Set fax
     *
     * @param string $fax
     * @return GenTerceros
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string 
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return GenTerceros
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set retencionFuenteVentas
     *
     * @param boolean $retencionFuenteVentas
     * @return GenTerceros
     */
    public function setRetencionFuenteVentas($retencionFuenteVentas)
    {
        $this->retencionFuenteVentas = $retencionFuenteVentas;

        return $this;
    }

    /**
     * Get retencionFuenteVentas
     *
     * @return boolean 
     */
    public function getRetencionFuenteVentas()
    {
        return $this->retencionFuenteVentas;
    }

    /**
     * Set retencionFuenteVentasSinBase
     *
     * @param boolean $retencionFuenteVentasSinBase
     * @return GenTerceros
     */
    public function setRetencionFuenteVentasSinBase($retencionFuenteVentasSinBase)
    {
        $this->retencionFuenteVentasSinBase = $retencionFuenteVentasSinBase;

        return $this;
    }

    /**
     * Get retencionFuenteVentasSinBase
     *
     * @return boolean 
     */
    public function getRetencionFuenteVentasSinBase()
    {
        return $this->retencionFuenteVentasSinBase;
    }

    /**
     * Set autoretenedor
     *
     * @param boolean $autoretenedor
     * @return GenTerceros
     */
    public function setAutoretenedor($autoretenedor)
    {
        $this->autoretenedor = $autoretenedor;

        return $this;
    }

    /**
     * Get autoretenedor
     *
     * @return boolean 
     */
    public function getAutoretenedor()
    {
        return $this->autoretenedor;
    }

    /**
     * Set contactoCliente
     *
     * @param string $contactoCliente
     * @return GenTerceros
     */
    public function setContactoCliente($contactoCliente)
    {
        $this->contactoCliente = $contactoCliente;

        return $this;
    }

    /**
     * Get contactoCliente
     *
     * @return string 
     */
    public function getContactoCliente()
    {
        return $this->contactoCliente;
    }

    /**
     * Set celularContactoCliente
     *
     * @param string $celularContactoCliente
     * @return GenTerceros
     */
    public function setCelularContactoCliente($celularContactoCliente)
    {
        $this->celularContactoCliente = $celularContactoCliente;

        return $this;
    }

    /**
     * Get celularContactoCliente
     *
     * @return string 
     */
    public function getCelularContactoCliente()
    {
        return $this->celularContactoCliente;
    }

    /**
     * Set contactoProveedor
     *
     * @param string $contactoProveedor
     * @return GenTerceros
     */
    public function setContactoProveedor($contactoProveedor)
    {
        $this->contactoProveedor = $contactoProveedor;

        return $this;
    }

    /**
     * Get contactoProveedor
     *
     * @return string 
     */
    public function getContactoProveedor()
    {
        return $this->contactoProveedor;
    }

    /**
     * Set celularContactoProveedor
     *
     * @param string $celularContactoProveedor
     * @return GenTerceros
     */
    public function setCelularContactoProveedor($celularContactoProveedor)
    {
        $this->celularContactoProveedor = $celularContactoProveedor;

        return $this;
    }

    /**
     * Get celularContactoProveedor
     *
     * @return string 
     */
    public function getCelularContactoProveedor()
    {
        return $this->celularContactoProveedor;
    }

    /**
     * Set codigoActividadEconomica
     *
     * @param integer $codigoActividadEconomica
     * @return GenTerceros
     */
    public function setCodigoActividadEconomica($codigoActividadEconomica)
    {
        $this->codigoActividadEconomica = $codigoActividadEconomica;

        return $this;
    }

    /**
     * Get codigoActividadEconomica
     *
     * @return integer 
     */
    public function getCodigoActividadEconomica()
    {
        return $this->codigoActividadEconomica;
    }

    /**
     * Set porcentajeCREE
     *
     * @param float $porcentajeCREE
     * @return GenTerceros
     */
    public function setPorcentajeCREE($porcentajeCREE)
    {
        $this->porcentajeCREE = $porcentajeCREE;

        return $this;
    }

    /**
     * Get porcentajeCREE
     *
     * @return float 
     */
    public function getPorcentajeCREE()
    {
        return $this->porcentajeCREE;
    }

    /**
     * Set clasificacionTributariaRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenClasificacionesTributarias $clasificacionTributariaRel
     * @return GenTerceros
     */
    public function setClasificacionTributariaRel(\Brasa\GeneralBundle\Entity\GenClasificacionesTributarias $clasificacionTributariaRel = null)
    {
        $this->clasificacionTributariaRel = $clasificacionTributariaRel;

        return $this;
    }

    /**
     * Get clasificacionTributariaRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenClasificacionesTributarias 
     */
    public function getClasificacionTributariaRel()
    {
        return $this->clasificacionTributariaRel;
    }

    /**
     * Set formaPagoClienteRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenFormasPago $formaPagoClienteRel
     * @return GenTerceros
     */
    public function setFormaPagoClienteRel(\Brasa\GeneralBundle\Entity\GenFormasPago $formaPagoClienteRel = null)
    {
        $this->formaPagoClienteRel = $formaPagoClienteRel;

        return $this;
    }

    /**
     * Get formaPagoClienteRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenFormasPago 
     */
    public function getFormaPagoClienteRel()
    {
        return $this->formaPagoClienteRel;
    }

    /**
     * Set formaPagoProveedorRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenFormasPago $formaPagoProveedorRel
     * @return GenTerceros
     */
    public function setFormaPagoProveedorRel(\Brasa\GeneralBundle\Entity\GenFormasPago $formaPagoProveedorRel = null)
    {
        $this->formaPagoProveedorRel = $formaPagoProveedorRel;

        return $this;
    }

    /**
     * Get formaPagoProveedorRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenFormasPago 
     */
    public function getFormaPagoProveedorRel()
    {
        return $this->formaPagoProveedorRel;
    }

    /**
     * Set asesorRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenAsesores $asesorRel
     * @return GenTerceros
     */
    public function setAsesorRel(\Brasa\GeneralBundle\Entity\GenAsesores $asesorRel = null)
    {
        $this->asesorRel = $asesorRel;

        return $this;
    }

    /**
     * Get asesorRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenAsesores 
     */
    public function getAsesorRel()
    {
        return $this->asesorRel;
    }

    /**
     * Add tercerosDireccionesRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTercerosDirecciones $tercerosDireccionesRel
     * @return GenTerceros
     */
    public function addTercerosDireccionesRel(\Brasa\GeneralBundle\Entity\GenTercerosDirecciones $tercerosDireccionesRel)
    {
        $this->tercerosDireccionesRel[] = $tercerosDireccionesRel;

        return $this;
    }

    /**
     * Remove tercerosDireccionesRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTercerosDirecciones $tercerosDireccionesRel
     */
    public function removeTercerosDireccionesRel(\Brasa\GeneralBundle\Entity\GenTercerosDirecciones $tercerosDireccionesRel)
    {
        $this->tercerosDireccionesRel->removeElement($tercerosDireccionesRel);
    }

    /**
     * Get tercerosDireccionesRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTercerosDireccionesRel()
    {
        return $this->tercerosDireccionesRel;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tercerosDireccionesRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->movimientosRel = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Add guiasRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuias $guiasRel
     * @return GenTerceros
     */
    public function addGuiasRel(\Brasa\TransporteBundle\Entity\TteGuias $guiasRel)
    {
        $this->guiasRel[] = $guiasRel;

        return $this;
    }

    /**
     * Remove guiasRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuias $guiasRel
     */
    public function removeGuiasRel(\Brasa\TransporteBundle\Entity\TteGuias $guiasRel)
    {
        $this->guiasRel->removeElement($guiasRel);
    }

    /**
     * Get guiasRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGuiasRel()
    {
        return $this->guiasRel;
    }

    /**
     * Set nit
     *
     * @param string $nit
     * @return GenTerceros
     */
    public function setNit($nit)
    {
        $this->nit = $nit;

        return $this;
    }

    /**
     * Get nit
     *
     * @return string 
     */
    public function getNit()
    {
        return $this->nit;
    }
}
