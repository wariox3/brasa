<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_servicio")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurServicioRepository")
 */
class TurServicio
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_servicio_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoServicioPk;                      
    
    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;    
    
    /**
     * @ORM\Column(name="codigo_sector_fk", type="integer", nullable=true)
     */    
    private $codigoSectorFk;                            
    
    /**     
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = false;     
    
    /**     
     * @ORM\Column(name="estado_aprobado", type="boolean")
     */    
    private $estadoAprobado = false;         
    
    /**     
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = false;    
    
    
    /**
     * @ORM\Column(name="cantidad", type="integer")
     */    
    private $cantidad = 0;    
    
    /**
     * @ORM\Column(name="horas", type="integer")
     */    
    private $horas = 0;    
    
    /**
     * @ORM\Column(name="horas_diurnas", type="integer")
     */    
    private $horasDiurnas = 0;     
    
    /**
     * @ORM\Column(name="horas_nocturnas", type="integer")
     */    
    private $horasNocturnas = 0;    
    
    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;    
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;                       
    
    /**
     * @ORM\ManyToOne(targetEntity="TurCliente", inversedBy="serviciosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurSector", inversedBy="serviciosSectorRel")
     * @ORM\JoinColumn(name="codigo_sector_fk", referencedColumnName="codigo_sector_pk")
     */
    protected $sectorRel;         
    
    /**
     * @ORM\OneToMany(targetEntity="TurServicioDetalle", mappedBy="servicioRel", cascade={"persist", "remove"})
     */
    protected $serviciosDetallesServicioRel; 


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->serviciosDetallesServicioRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoServicioPk
     *
     * @return integer
     */
    public function getCodigoServicioPk()
    {
        return $this->codigoServicioPk;
    }

    /**
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return TurServicio
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
     * Set codigoSectorFk
     *
     * @param integer $codigoSectorFk
     *
     * @return TurServicio
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
     * Set fechaProgramacion
     *
     * @param \DateTime $fechaProgramacion
     *
     * @return TurServicio
     */
    public function setFechaProgramacion($fechaProgramacion)
    {
        $this->fechaProgramacion = $fechaProgramacion;

        return $this;
    }

    /**
     * Get fechaProgramacion
     *
     * @return \DateTime
     */
    public function getFechaProgramacion()
    {
        return $this->fechaProgramacion;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return TurServicio
     */
    public function setEstadoAutorizado($estadoAutorizado)
    {
        $this->estadoAutorizado = $estadoAutorizado;

        return $this;
    }

    /**
     * Get estadoAutorizado
     *
     * @return boolean
     */
    public function getEstadoAutorizado()
    {
        return $this->estadoAutorizado;
    }

    /**
     * Set estadoAprobado
     *
     * @param boolean $estadoAprobado
     *
     * @return TurServicio
     */
    public function setEstadoAprobado($estadoAprobado)
    {
        $this->estadoAprobado = $estadoAprobado;

        return $this;
    }

    /**
     * Get estadoAprobado
     *
     * @return boolean
     */
    public function getEstadoAprobado()
    {
        return $this->estadoAprobado;
    }

    /**
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return TurServicio
     */
    public function setEstadoCerrado($estadoCerrado)
    {
        $this->estadoCerrado = $estadoCerrado;

        return $this;
    }

    /**
     * Get estadoCerrado
     *
     * @return boolean
     */
    public function getEstadoCerrado()
    {
        return $this->estadoCerrado;
    }

    /**
     * Set estadoProgramado
     *
     * @param boolean $estadoProgramado
     *
     * @return TurServicio
     */
    public function setEstadoProgramado($estadoProgramado)
    {
        $this->estadoProgramado = $estadoProgramado;

        return $this;
    }

    /**
     * Get estadoProgramado
     *
     * @return boolean
     */
    public function getEstadoProgramado()
    {
        return $this->estadoProgramado;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return TurServicio
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set horas
     *
     * @param integer $horas
     *
     * @return TurServicio
     */
    public function setHoras($horas)
    {
        $this->horas = $horas;

        return $this;
    }

    /**
     * Get horas
     *
     * @return integer
     */
    public function getHoras()
    {
        return $this->horas;
    }

    /**
     * Set horasDiurnas
     *
     * @param integer $horasDiurnas
     *
     * @return TurServicio
     */
    public function setHorasDiurnas($horasDiurnas)
    {
        $this->horasDiurnas = $horasDiurnas;

        return $this;
    }

    /**
     * Get horasDiurnas
     *
     * @return integer
     */
    public function getHorasDiurnas()
    {
        return $this->horasDiurnas;
    }

    /**
     * Set horasNocturnas
     *
     * @param integer $horasNocturnas
     *
     * @return TurServicio
     */
    public function setHorasNocturnas($horasNocturnas)
    {
        $this->horasNocturnas = $horasNocturnas;

        return $this;
    }

    /**
     * Get horasNocturnas
     *
     * @return integer
     */
    public function getHorasNocturnas()
    {
        return $this->horasNocturnas;
    }

    /**
     * Set vrTotal
     *
     * @param float $vrTotal
     *
     * @return TurServicio
     */
    public function setVrTotal($vrTotal)
    {
        $this->vrTotal = $vrTotal;

        return $this;
    }

    /**
     * Get vrTotal
     *
     * @return float
     */
    public function getVrTotal()
    {
        return $this->vrTotal;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return TurServicio
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
     * @return TurServicio
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
     * Set sectorRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSector $sectorRel
     *
     * @return TurServicio
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
     * Add serviciosDetallesServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesServicioRel
     *
     * @return TurServicio
     */
    public function addServiciosDetallesServicioRel(\Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesServicioRel)
    {
        $this->serviciosDetallesServicioRel[] = $serviciosDetallesServicioRel;

        return $this;
    }

    /**
     * Remove serviciosDetallesServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesServicioRel
     */
    public function removeServiciosDetallesServicioRel(\Brasa\TurnoBundle\Entity\TurServicioDetalle $serviciosDetallesServicioRel)
    {
        $this->serviciosDetallesServicioRel->removeElement($serviciosDetallesServicioRel);
    }

    /**
     * Get serviciosDetallesServicioRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosDetallesServicioRel()
    {
        return $this->serviciosDetallesServicioRel;
    }
}
