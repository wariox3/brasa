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
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;  

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;    
    
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
     * Constructor
     */
    public function __construct()
    {
        $this->pedidosDetallesPuestoRel = new \Doctrine\Common\Collections\ArrayCollection();
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
}
