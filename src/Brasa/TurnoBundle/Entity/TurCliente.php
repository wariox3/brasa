<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_cliente")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurClienteRepository")
 */
class TurCliente
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cliente_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoClientePk;    
    
    /**
     * @ORM\Column(name="nit", type="string", length=15, nullable=false)
     */
    private $nit;        
    
    /**
     * @ORM\Column(name="digito_verificacion", type="string", length=3, nullable=true)
     */
    private $digitoVerificacion;             
    
    /**
     * @ORM\Column(name="nombre_corto", type="string", length=60)
     */
    private $nombreCorto;                         
    
    /**
     * @ORM\Column(name="nombre_completo", type="string", length=200, nullable=true)
     */
    private $nombreCompleto;    
    
    /**
     * @ORM\Column(name="codigo_sector_fk", type="integer")
     */    
    private $codigoSectorFk;     
    
    /**
     * @ORM\Column(name="codigo_asesor_fk", type="integer", nullable=true)
     */    
    private $codigoAsesorFk;    
    
    /**
     * @ORM\Column(name="estrato", type="string", length=5, nullable=true)
     */
    private $estrato;                
    
    /**
     * @ORM\Column(name="plazo_pago", type="integer")
     */    
    private $plazoPago = 0;    
    
    /**
     * @ORM\Column(name="codigo_forma_pago_fk", type="integer", nullable=true)
     */    
    private $codigoFormaPagoFk;     
    
    /**
     * @ORM\Column(name="direccion", type="string", length=120, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(name="barrio", type="string", length=120, nullable=true)
     */
    private $barrio;    
    
    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;         
    
    /**
     * @ORM\Column(name="telefono", type="string", length=30, nullable=true)
     */
    private $telefono;     
    
    /**
     * @ORM\Column(name="celular", type="string", length=20, nullable=true, nullable=true)
     */
    private $celular;    
        
    /**
     * @ORM\Column(name="fax", type="string", length=20, nullable=true, nullable=true)
     */
    private $fax;    
    
    /**
     * @ORM\Column(name="email", type="string", length=80, nullable=true)
     */
    private $email;     
    
    /**
     * @ORM\Column(name="gerente", type="string", length=80, nullable=true)
     */
    private $gerente;    
    
    /**
     * @ORM\Column(name="calular_gerente", type="string", length=20, nullable=true)
     */
    private $celularGerente;  
    
    /**
     * @ORM\Column(name="financiero", type="string", length=80, nullable=true)
     */
    private $financiero;    
    
    /**
     * @ORM\Column(name="calular_financiero", type="string", length=20, nullable=true)
     */
    private $celularFinanciero;     
    
    /**
     * @ORM\Column(name="contacto", type="string", length=80, nullable=true)
     */
    private $contacto;    

    /**
     * @ORM\Column(name="calular_contacto", type="string", length=20, nullable=true)
     */
    private $celularContacto;     

    /**
     * @ORM\Column(name="telefono_contacto", type="string", length=20, nullable=true)
     */
    private $telefonoContacto;    
    
    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */    
    private $usuario;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;                    
    
    /**
     * @ORM\Column(name="codigo_interface", type="string", length=30, nullable=true)
     */
    private $codigoInterface;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurSector", inversedBy="clientesSectorRel")
     * @ORM\JoinColumn(name="codigo_sector_fk", referencedColumnName="codigo_sector_pk")
     */
    protected $sectorRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenFormaPago", inversedBy="turClientesFormaPagoRel")
     * @ORM\JoinColumn(name="codigo_forma_pago_fk", referencedColumnName="codigo_forma_pago_pk")
     */
    protected $formaPagoRel;            
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenAsesor", inversedBy="turClientesAsesorRel")
     * @ORM\JoinColumn(name="codigo_asesor_fk", referencedColumnName="codigo_asesor_pk")
     */
    protected $asesorRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="turClientesCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurCotizacion", mappedBy="clienteRel")
     */
    protected $cotizacionesClienteRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="TurPedido", mappedBy="clienteRel")
     */
    protected $pedidosClienteRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurServicio", mappedBy="clienteRel")
     */
    protected $serviciosClienteRel;    

    /**
     * @ORM\OneToMany(targetEntity="TurFactura", mappedBy="clienteRel")
     */
    protected $facturasClienteRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurProgramacion", mappedBy="clienteRel")
     */
    protected $programacionesClienteRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurPuesto", mappedBy="clienteRel")
     */
    protected $puestosClienteRel;     

    /**
     * @ORM\OneToMany(targetEntity="TurProyecto", mappedBy="clienteRel")
     */
    protected $proyectosClienteRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurGrupoFacturacion", mappedBy="clienteRel")
     */
    protected $gruposFacturacionesClienteRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurContrato", mappedBy="clienteRel")
     */
    protected $contratosClienteRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurClienteDireccion", mappedBy="clienteRel")
     */
    protected $clientesDireccionesClienteRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="TurCierreMesServicio", mappedBy="clienteRel")
     */
    protected $cierresMesServiciosClienteRel;     

    /**
     * @ORM\OneToMany(targetEntity="TurPuestoDotacion", mappedBy="clienteRel")
     */
    protected $puestosDotacionesClienteRel;    
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cotizacionesClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pedidosClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->serviciosClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->facturasClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->programacionesClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->puestosClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->proyectosClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->gruposFacturacionesClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contratosClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->clientesDireccionesClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cierresMesServiciosClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->puestosDotacionesClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoClientePk
     *
     * @return integer
     */
    public function getCodigoClientePk()
    {
        return $this->codigoClientePk;
    }

    /**
     * Set nit
     *
     * @param string $nit
     *
     * @return TurCliente
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

    /**
     * Set digitoVerificacion
     *
     * @param string $digitoVerificacion
     *
     * @return TurCliente
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
     * Set nombreCorto
     *
     * @param string $nombreCorto
     *
     * @return TurCliente
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
     * Set nombreCompleto
     *
     * @param string $nombreCompleto
     *
     * @return TurCliente
     */
    public function setNombreCompleto($nombreCompleto)
    {
        $this->nombreCompleto = $nombreCompleto;

        return $this;
    }

    /**
     * Get nombreCompleto
     *
     * @return string
     */
    public function getNombreCompleto()
    {
        return $this->nombreCompleto;
    }

    /**
     * Set codigoSectorFk
     *
     * @param integer $codigoSectorFk
     *
     * @return TurCliente
     */
    public function setCodigoSectorFk($codigoSectorFk)
    {
        $this->codigoSectorFk = $codigoSectorFk;

        return $this;
    }

    /**
     * Get codigoSectorFk
     *
     * @return integer
     */
    public function getCodigoSectorFk()
    {
        return $this->codigoSectorFk;
    }

    /**
     * Set codigoAsesorFk
     *
     * @param integer $codigoAsesorFk
     *
     * @return TurCliente
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
     * Set estrato
     *
     * @param string $estrato
     *
     * @return TurCliente
     */
    public function setEstrato($estrato)
    {
        $this->estrato = $estrato;

        return $this;
    }

    /**
     * Get estrato
     *
     * @return string
     */
    public function getEstrato()
    {
        return $this->estrato;
    }

    /**
     * Set plazoPago
     *
     * @param integer $plazoPago
     *
     * @return TurCliente
     */
    public function setPlazoPago($plazoPago)
    {
        $this->plazoPago = $plazoPago;

        return $this;
    }

    /**
     * Get plazoPago
     *
     * @return integer
     */
    public function getPlazoPago()
    {
        return $this->plazoPago;
    }

    /**
     * Set codigoFormaPagoFk
     *
     * @param integer $codigoFormaPagoFk
     *
     * @return TurCliente
     */
    public function setCodigoFormaPagoFk($codigoFormaPagoFk)
    {
        $this->codigoFormaPagoFk = $codigoFormaPagoFk;

        return $this;
    }

    /**
     * Get codigoFormaPagoFk
     *
     * @return integer
     */
    public function getCodigoFormaPagoFk()
    {
        return $this->codigoFormaPagoFk;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return TurCliente
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
     * Set barrio
     *
     * @param string $barrio
     *
     * @return TurCliente
     */
    public function setBarrio($barrio)
    {
        $this->barrio = $barrio;

        return $this;
    }

    /**
     * Get barrio
     *
     * @return string
     */
    public function getBarrio()
    {
        return $this->barrio;
    }

    /**
     * Set codigoCiudadFk
     *
     * @param integer $codigoCiudadFk
     *
     * @return TurCliente
     */
    public function setCodigoCiudadFk($codigoCiudadFk)
    {
        $this->codigoCiudadFk = $codigoCiudadFk;

        return $this;
    }

    /**
     * Get codigoCiudadFk
     *
     * @return integer
     */
    public function getCodigoCiudadFk()
    {
        return $this->codigoCiudadFk;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return TurCliente
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
     *
     * @return TurCliente
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
     *
     * @return TurCliente
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
     *
     * @return TurCliente
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
     * Set gerente
     *
     * @param string $gerente
     *
     * @return TurCliente
     */
    public function setGerente($gerente)
    {
        $this->gerente = $gerente;

        return $this;
    }

    /**
     * Get gerente
     *
     * @return string
     */
    public function getGerente()
    {
        return $this->gerente;
    }

    /**
     * Set celularGerente
     *
     * @param string $celularGerente
     *
     * @return TurCliente
     */
    public function setCelularGerente($celularGerente)
    {
        $this->celularGerente = $celularGerente;

        return $this;
    }

    /**
     * Get celularGerente
     *
     * @return string
     */
    public function getCelularGerente()
    {
        return $this->celularGerente;
    }

    /**
     * Set financiero
     *
     * @param string $financiero
     *
     * @return TurCliente
     */
    public function setFinanciero($financiero)
    {
        $this->financiero = $financiero;

        return $this;
    }

    /**
     * Get financiero
     *
     * @return string
     */
    public function getFinanciero()
    {
        return $this->financiero;
    }

    /**
     * Set celularFinanciero
     *
     * @param string $celularFinanciero
     *
     * @return TurCliente
     */
    public function setCelularFinanciero($celularFinanciero)
    {
        $this->celularFinanciero = $celularFinanciero;

        return $this;
    }

    /**
     * Get celularFinanciero
     *
     * @return string
     */
    public function getCelularFinanciero()
    {
        return $this->celularFinanciero;
    }

    /**
     * Set contacto
     *
     * @param string $contacto
     *
     * @return TurCliente
     */
    public function setContacto($contacto)
    {
        $this->contacto = $contacto;

        return $this;
    }

    /**
     * Get contacto
     *
     * @return string
     */
    public function getContacto()
    {
        return $this->contacto;
    }

    /**
     * Set celularContacto
     *
     * @param string $celularContacto
     *
     * @return TurCliente
     */
    public function setCelularContacto($celularContacto)
    {
        $this->celularContacto = $celularContacto;

        return $this;
    }

    /**
     * Get celularContacto
     *
     * @return string
     */
    public function getCelularContacto()
    {
        return $this->celularContacto;
    }

    /**
     * Set telefonoContacto
     *
     * @param string $telefonoContacto
     *
     * @return TurCliente
     */
    public function setTelefonoContacto($telefonoContacto)
    {
        $this->telefonoContacto = $telefonoContacto;

        return $this;
    }

    /**
     * Get telefonoContacto
     *
     * @return string
     */
    public function getTelefonoContacto()
    {
        return $this->telefonoContacto;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     *
     * @return TurCliente
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return string
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return TurCliente
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
     * Set codigoInterface
     *
     * @param string $codigoInterface
     *
     * @return TurCliente
     */
    public function setCodigoInterface($codigoInterface)
    {
        $this->codigoInterface = $codigoInterface;

        return $this;
    }

    /**
     * Get codigoInterface
     *
     * @return string
     */
    public function getCodigoInterface()
    {
        return $this->codigoInterface;
    }

    /**
     * Set sectorRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSector $sectorRel
     *
     * @return TurCliente
     */
    public function setSectorRel(\Brasa\TurnoBundle\Entity\TurSector $sectorRel = null)
    {
        $this->sectorRel = $sectorRel;

        return $this;
    }

    /**
     * Get sectorRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurSector
     */
    public function getSectorRel()
    {
        return $this->sectorRel;
    }

    /**
     * Set formaPagoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenFormaPago $formaPagoRel
     *
     * @return TurCliente
     */
    public function setFormaPagoRel(\Brasa\GeneralBundle\Entity\GenFormaPago $formaPagoRel = null)
    {
        $this->formaPagoRel = $formaPagoRel;

        return $this;
    }

    /**
     * Get formaPagoRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenFormaPago
     */
    public function getFormaPagoRel()
    {
        return $this->formaPagoRel;
    }

    /**
     * Set asesorRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenAsesor $asesorRel
     *
     * @return TurCliente
     */
    public function setAsesorRel(\Brasa\GeneralBundle\Entity\GenAsesor $asesorRel = null)
    {
        $this->asesorRel = $asesorRel;

        return $this;
    }

    /**
     * Get asesorRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenAsesor
     */
    public function getAsesorRel()
    {
        return $this->asesorRel;
    }

    /**
     * Set ciudadRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel
     *
     * @return TurCliente
     */
    public function setCiudadRel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel = null)
    {
        $this->ciudadRel = $ciudadRel;

        return $this;
    }

    /**
     * Get ciudadRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudad
     */
    public function getCiudadRel()
    {
        return $this->ciudadRel;
    }

    /**
     * Add cotizacionesClienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCotizacion $cotizacionesClienteRel
     *
     * @return TurCliente
     */
    public function addCotizacionesClienteRel(\Brasa\TurnoBundle\Entity\TurCotizacion $cotizacionesClienteRel)
    {
        $this->cotizacionesClienteRel[] = $cotizacionesClienteRel;

        return $this;
    }

    /**
     * Remove cotizacionesClienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCotizacion $cotizacionesClienteRel
     */
    public function removeCotizacionesClienteRel(\Brasa\TurnoBundle\Entity\TurCotizacion $cotizacionesClienteRel)
    {
        $this->cotizacionesClienteRel->removeElement($cotizacionesClienteRel);
    }

    /**
     * Get cotizacionesClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCotizacionesClienteRel()
    {
        return $this->cotizacionesClienteRel;
    }

    /**
     * Add pedidosClienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedido $pedidosClienteRel
     *
     * @return TurCliente
     */
    public function addPedidosClienteRel(\Brasa\TurnoBundle\Entity\TurPedido $pedidosClienteRel)
    {
        $this->pedidosClienteRel[] = $pedidosClienteRel;

        return $this;
    }

    /**
     * Remove pedidosClienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedido $pedidosClienteRel
     */
    public function removePedidosClienteRel(\Brasa\TurnoBundle\Entity\TurPedido $pedidosClienteRel)
    {
        $this->pedidosClienteRel->removeElement($pedidosClienteRel);
    }

    /**
     * Get pedidosClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosClienteRel()
    {
        return $this->pedidosClienteRel;
    }

    /**
     * Add serviciosClienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicio $serviciosClienteRel
     *
     * @return TurCliente
     */
    public function addServiciosClienteRel(\Brasa\TurnoBundle\Entity\TurServicio $serviciosClienteRel)
    {
        $this->serviciosClienteRel[] = $serviciosClienteRel;

        return $this;
    }

    /**
     * Remove serviciosClienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicio $serviciosClienteRel
     */
    public function removeServiciosClienteRel(\Brasa\TurnoBundle\Entity\TurServicio $serviciosClienteRel)
    {
        $this->serviciosClienteRel->removeElement($serviciosClienteRel);
    }

    /**
     * Get serviciosClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosClienteRel()
    {
        return $this->serviciosClienteRel;
    }

    /**
     * Add facturasClienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFactura $facturasClienteRel
     *
     * @return TurCliente
     */
    public function addFacturasClienteRel(\Brasa\TurnoBundle\Entity\TurFactura $facturasClienteRel)
    {
        $this->facturasClienteRel[] = $facturasClienteRel;

        return $this;
    }

    /**
     * Remove facturasClienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurFactura $facturasClienteRel
     */
    public function removeFacturasClienteRel(\Brasa\TurnoBundle\Entity\TurFactura $facturasClienteRel)
    {
        $this->facturasClienteRel->removeElement($facturasClienteRel);
    }

    /**
     * Get facturasClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasClienteRel()
    {
        return $this->facturasClienteRel;
    }

    /**
     * Add programacionesClienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProgramacion $programacionesClienteRel
     *
     * @return TurCliente
     */
    public function addProgramacionesClienteRel(\Brasa\TurnoBundle\Entity\TurProgramacion $programacionesClienteRel)
    {
        $this->programacionesClienteRel[] = $programacionesClienteRel;

        return $this;
    }

    /**
     * Remove programacionesClienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProgramacion $programacionesClienteRel
     */
    public function removeProgramacionesClienteRel(\Brasa\TurnoBundle\Entity\TurProgramacion $programacionesClienteRel)
    {
        $this->programacionesClienteRel->removeElement($programacionesClienteRel);
    }

    /**
     * Get programacionesClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgramacionesClienteRel()
    {
        return $this->programacionesClienteRel;
    }

    /**
     * Add puestosClienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuesto $puestosClienteRel
     *
     * @return TurCliente
     */
    public function addPuestosClienteRel(\Brasa\TurnoBundle\Entity\TurPuesto $puestosClienteRel)
    {
        $this->puestosClienteRel[] = $puestosClienteRel;

        return $this;
    }

    /**
     * Remove puestosClienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuesto $puestosClienteRel
     */
    public function removePuestosClienteRel(\Brasa\TurnoBundle\Entity\TurPuesto $puestosClienteRel)
    {
        $this->puestosClienteRel->removeElement($puestosClienteRel);
    }

    /**
     * Get puestosClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPuestosClienteRel()
    {
        return $this->puestosClienteRel;
    }

    /**
     * Add proyectosClienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProyecto $proyectosClienteRel
     *
     * @return TurCliente
     */
    public function addProyectosClienteRel(\Brasa\TurnoBundle\Entity\TurProyecto $proyectosClienteRel)
    {
        $this->proyectosClienteRel[] = $proyectosClienteRel;

        return $this;
    }

    /**
     * Remove proyectosClienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProyecto $proyectosClienteRel
     */
    public function removeProyectosClienteRel(\Brasa\TurnoBundle\Entity\TurProyecto $proyectosClienteRel)
    {
        $this->proyectosClienteRel->removeElement($proyectosClienteRel);
    }

    /**
     * Get proyectosClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProyectosClienteRel()
    {
        return $this->proyectosClienteRel;
    }

    /**
     * Add gruposFacturacionesClienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurGrupoFacturacion $gruposFacturacionesClienteRel
     *
     * @return TurCliente
     */
    public function addGruposFacturacionesClienteRel(\Brasa\TurnoBundle\Entity\TurGrupoFacturacion $gruposFacturacionesClienteRel)
    {
        $this->gruposFacturacionesClienteRel[] = $gruposFacturacionesClienteRel;

        return $this;
    }

    /**
     * Remove gruposFacturacionesClienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurGrupoFacturacion $gruposFacturacionesClienteRel
     */
    public function removeGruposFacturacionesClienteRel(\Brasa\TurnoBundle\Entity\TurGrupoFacturacion $gruposFacturacionesClienteRel)
    {
        $this->gruposFacturacionesClienteRel->removeElement($gruposFacturacionesClienteRel);
    }

    /**
     * Get gruposFacturacionesClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGruposFacturacionesClienteRel()
    {
        return $this->gruposFacturacionesClienteRel;
    }

    /**
     * Add contratosClienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurContrato $contratosClienteRel
     *
     * @return TurCliente
     */
    public function addContratosClienteRel(\Brasa\TurnoBundle\Entity\TurContrato $contratosClienteRel)
    {
        $this->contratosClienteRel[] = $contratosClienteRel;

        return $this;
    }

    /**
     * Remove contratosClienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurContrato $contratosClienteRel
     */
    public function removeContratosClienteRel(\Brasa\TurnoBundle\Entity\TurContrato $contratosClienteRel)
    {
        $this->contratosClienteRel->removeElement($contratosClienteRel);
    }

    /**
     * Get contratosClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosClienteRel()
    {
        return $this->contratosClienteRel;
    }

    /**
     * Add clientesDireccionesClienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurClienteDireccion $clientesDireccionesClienteRel
     *
     * @return TurCliente
     */
    public function addClientesDireccionesClienteRel(\Brasa\TurnoBundle\Entity\TurClienteDireccion $clientesDireccionesClienteRel)
    {
        $this->clientesDireccionesClienteRel[] = $clientesDireccionesClienteRel;

        return $this;
    }

    /**
     * Remove clientesDireccionesClienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurClienteDireccion $clientesDireccionesClienteRel
     */
    public function removeClientesDireccionesClienteRel(\Brasa\TurnoBundle\Entity\TurClienteDireccion $clientesDireccionesClienteRel)
    {
        $this->clientesDireccionesClienteRel->removeElement($clientesDireccionesClienteRel);
    }

    /**
     * Get clientesDireccionesClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getClientesDireccionesClienteRel()
    {
        return $this->clientesDireccionesClienteRel;
    }

    /**
     * Add cierresMesServiciosClienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCierreMesServicio $cierresMesServiciosClienteRel
     *
     * @return TurCliente
     */
    public function addCierresMesServiciosClienteRel(\Brasa\TurnoBundle\Entity\TurCierreMesServicio $cierresMesServiciosClienteRel)
    {
        $this->cierresMesServiciosClienteRel[] = $cierresMesServiciosClienteRel;

        return $this;
    }

    /**
     * Remove cierresMesServiciosClienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCierreMesServicio $cierresMesServiciosClienteRel
     */
    public function removeCierresMesServiciosClienteRel(\Brasa\TurnoBundle\Entity\TurCierreMesServicio $cierresMesServiciosClienteRel)
    {
        $this->cierresMesServiciosClienteRel->removeElement($cierresMesServiciosClienteRel);
    }

    /**
     * Get cierresMesServiciosClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCierresMesServiciosClienteRel()
    {
        return $this->cierresMesServiciosClienteRel;
    }

    /**
     * Add puestosDotacionesClienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuestoDotacion $puestosDotacionesClienteRel
     *
     * @return TurCliente
     */
    public function addPuestosDotacionesClienteRel(\Brasa\TurnoBundle\Entity\TurPuestoDotacion $puestosDotacionesClienteRel)
    {
        $this->puestosDotacionesClienteRel[] = $puestosDotacionesClienteRel;

        return $this;
    }

    /**
     * Remove puestosDotacionesClienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuestoDotacion $puestosDotacionesClienteRel
     */
    public function removePuestosDotacionesClienteRel(\Brasa\TurnoBundle\Entity\TurPuestoDotacion $puestosDotacionesClienteRel)
    {
        $this->puestosDotacionesClienteRel->removeElement($puestosDotacionesClienteRel);
    }

    /**
     * Get puestosDotacionesClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPuestosDotacionesClienteRel()
    {
        return $this->puestosDotacionesClienteRel;
    }
}
