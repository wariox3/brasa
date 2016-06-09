<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_puesto")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurPuestoRepository")
 */
class TurPuesto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_puesto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPuestoPk;        
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60)
     */
    private $nombre;      
            
    /**
     * @ORM\Column(name="direccion", type="string", length=80, nullable=true)
     */
    private $direccion;    
    
    /**
     * @ORM\Column(name="telefono", type="string", length=30, nullable=true)
     */
    private $telefono;     
    
    /**
     * @ORM\Column(name="celular", type="string", length=30, nullable=true)
     */
    private $celular;     
    
    /**
     * @ORM\Column(name="contacto", type="string", length=90, nullable=true)
     */
    private $contacto;    

    /**
     * @ORM\Column(name="telefono_contacto", type="string", length=30, nullable=true)
     */
    private $telefonoContacto;    

    /**
     * @ORM\Column(name="celular_contacto", type="string", length=30, nullable=true)
     */
    private $celularContacto;    
    
    /**
     * @ORM\Column(name="costo_dotacion", type="float", nullable=true)
     */    
    private $costoDotacion = 0;     
    
    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;    
    
    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;   
    
    /**
     * @ORM\ManyToOne(targetEntity="TurCliente", inversedBy="puestosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="turPuestosCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalle", mappedBy="puestoRel")
     */
    protected $pedidosDetallesPuestoRel;    

    /**
     * @ORM\OneToMany(targetEntity="TurServicioDetalle", mappedBy="puestoRel")
     */
    protected $serviciosDetallesPuestoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurProgramacionDetalle", mappedBy="puestoRel")
     */
    protected $programacionesDetallesPuestoRel;        

    /**
     * @ORM\OneToMany(targetEntity="TurCierreMesServicio", mappedBy="puestoRel")
     */
    protected $cierresMesServiciosPuestoRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="TurPuestoDotacion", mappedBy="puestoRel")
     */
    protected $puestosDotacionesPuestoRel;     
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pedidosDetallesPuestoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->serviciosDetallesPuestoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->programacionesDetallesPuestoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPuestoPk
     *
     * @return integer
     */
    public function getCodigoPuestoPk()
    {
        return $this->codigoPuestoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurPuesto
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
     * Set telefono
     *
     * @param string $telefono
     *
     * @return TurPuesto
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
     * @return TurPuesto
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
     * Set contacto
     *
     * @param string $contacto
     *
     * @return TurPuesto
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
     * Set telefonoContacto
     *
     * @param string $telefonoContacto
     *
     * @return TurPuesto
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
     * Set celularContacto
     *
     * @param string $celularContacto
     *
     * @return TurPuesto
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
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return TurPuesto
     */
    public function setCodigoClienteFk($codigoClienteFk)
    {
        $this->codigoClienteFk = $codigoClienteFk;

        return $this;
    }

    /**
     * Get codigoClienteFk
     *
     * @return integer
     */
    public function getCodigoClienteFk()
    {
        return $this->codigoClienteFk;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return TurPuesto
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
     * Set clienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $clienteRel
     *
     * @return TurPuesto
     */
    public function setClienteRel(\Brasa\TurnoBundle\Entity\TurCliente $clienteRel = null)
    {
        $this->clienteRel = $clienteRel;

        return $this;
    }

    /**
     * Get clienteRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurCliente
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
    }

    /**
     * Add pedidosDetallesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesPuestoRel
     *
     * @return TurPuesto
     */
    public function addPedidosDetallesPuestoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesPuestoRel)
    {
        $this->pedidosDetallesPuestoRel[] = $pedidosDetallesPuestoRel;

        return $this;
    }

    /**
     * Remove pedidosDetallesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesPuestoRel
     */
    public function removePedidosDetallesPuestoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesPuestoRel)
    {
        $this->pedidosDetallesPuestoRel->removeElement($pedidosDetallesPuestoRel);
    }

    /**
     * Get pedidosDetallesPuestoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosDetallesPuestoRel()
    {
        return $this->pedidosDetallesPuestoRel;
    }

    /**
     * Add serviciosDetallesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesPuestoRel
     *
     * @return TurPuesto
     */
    public function addServiciosDetallesPuestoRel(\Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesPuestoRel)
    {
        $this->serviciosDetallesPuestoRel[] = $serviciosDetallesPuestoRel;

        return $this;
    }

    /**
     * Remove serviciosDetallesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesPuestoRel
     */
    public function removeServiciosDetallesPuestoRel(\Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesPuestoRel)
    {
        $this->serviciosDetallesPuestoRel->removeElement($serviciosDetallesPuestoRel);
    }

    /**
     * Get serviciosDetallesPuestoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosDetallesPuestoRel()
    {
        return $this->serviciosDetallesPuestoRel;
    }

    /**
     * Add programacionesDetallesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesPuestoRel
     *
     * @return TurPuesto
     */
    public function addProgramacionesDetallesPuestoRel(\Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesPuestoRel)
    {
        $this->programacionesDetallesPuestoRel[] = $programacionesDetallesPuestoRel;

        return $this;
    }

    /**
     * Remove programacionesDetallesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesPuestoRel
     */
    public function removeProgramacionesDetallesPuestoRel(\Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesPuestoRel)
    {
        $this->programacionesDetallesPuestoRel->removeElement($programacionesDetallesPuestoRel);
    }

    /**
     * Get programacionesDetallesPuestoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgramacionesDetallesPuestoRel()
    {
        return $this->programacionesDetallesPuestoRel;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return TurPuesto
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
     * Add cierresMesServiciosPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCierreMesServicio $cierresMesServiciosPuestoRel
     *
     * @return TurPuesto
     */
    public function addCierresMesServiciosPuestoRel(\Brasa\TurnoBundle\Entity\TurCierreMesServicio $cierresMesServiciosPuestoRel)
    {
        $this->cierresMesServiciosPuestoRel[] = $cierresMesServiciosPuestoRel;

        return $this;
    }

    /**
     * Remove cierresMesServiciosPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCierreMesServicio $cierresMesServiciosPuestoRel
     */
    public function removeCierresMesServiciosPuestoRel(\Brasa\TurnoBundle\Entity\TurCierreMesServicio $cierresMesServiciosPuestoRel)
    {
        $this->cierresMesServiciosPuestoRel->removeElement($cierresMesServiciosPuestoRel);
    }

    /**
     * Get cierresMesServiciosPuestoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCierresMesServiciosPuestoRel()
    {
        return $this->cierresMesServiciosPuestoRel;
    }

    /**
     * Add puestosDotacionesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuestoDotacion $puestosDotacionesPuestoRel
     *
     * @return TurPuesto
     */
    public function addPuestosDotacionesPuestoRel(\Brasa\TurnoBundle\Entity\TurPuestoDotacion $puestosDotacionesPuestoRel)
    {
        $this->puestosDotacionesPuestoRel[] = $puestosDotacionesPuestoRel;

        return $this;
    }

    /**
     * Remove puestosDotacionesPuestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuestoDotacion $puestosDotacionesPuestoRel
     */
    public function removePuestosDotacionesPuestoRel(\Brasa\TurnoBundle\Entity\TurPuestoDotacion $puestosDotacionesPuestoRel)
    {
        $this->puestosDotacionesPuestoRel->removeElement($puestosDotacionesPuestoRel);
    }

    /**
     * Get puestosDotacionesPuestoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPuestosDotacionesPuestoRel()
    {
        return $this->puestosDotacionesPuestoRel;
    }

    /**
     * Set costoDotacion
     *
     * @param float $costoDotacion
     *
     * @return TurPuesto
     */
    public function setCostoDotacion($costoDotacion)
    {
        $this->costoDotacion = $costoDotacion;

        return $this;
    }

    /**
     * Get costoDotacion
     *
     * @return float
     */
    public function getCostoDotacion()
    {
        return $this->costoDotacion;
    }

    /**
     * Set codigoCiudadFk
     *
     * @param integer $codigoCiudadFk
     *
     * @return TurPuesto
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
     * Set ciudadRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel
     *
     * @return TurPuesto
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
}
