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
     * @ORM\Column(name="nit", type="string", length=15, nullable=false, unique=true)
     */
    private $nit;        
    
    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     */    
    private $codigoTerceroFk;     
    
    /**
     * @ORM\Column(name="nombre_corto", type="string", length=50)
     */
    private $nombreCorto;                         
    
    /**
     * @ORM\Column(name="codigo_sector_fk", type="integer")
     */    
    private $codigoSectorFk;     
    
    /**
     * @ORM\Column(name="estrato", type="string", length=5, nullable=true)
     */
    private $estrato;                
    
    /**
     * @ORM\Column(name="plazo_pago", type="integer")
     */    
    private $plazoPago = 0;    
    
    /**
     * @ORM\Column(name="direccion", type="string", length=120)
     */
    private $direccion;
    
    /**
     * @ORM\Column(name="telefono", type="string", length=30)
     */
    private $telefono;     
    
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
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;         
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenTercero", inversedBy="turClientesTerceroRel")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;        
    
    /**
     * @ORM\ManyToOne(targetEntity="TurSector", inversedBy="clientesSectorRel")
     * @ORM\JoinColumn(name="codigo_sector_fk", referencedColumnName="codigo_sector_pk")
     */
    protected $sectorRel;    
    
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
     * Set codigoTerceroFk
     *
     * @param integer $codigoTerceroFk
     *
     * @return TurCliente
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
     * Set terceroRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTercero $terceroRel
     *
     * @return TurCliente
     */
    public function setTerceroRel(\Brasa\GeneralBundle\Entity\GenTercero $terceroRel = null)
    {
        $this->terceroRel = $terceroRel;

        return $this;
    }

    /**
     * Get terceroRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenTercero
     */
    public function getTerceroRel()
    {
        return $this->terceroRel;
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
}
