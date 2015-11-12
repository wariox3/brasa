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
     * @ORM\Column(name="nombreCorto", type="string", length=120, nullable=true)
     */    
    private $nombreCorto;    
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurPedido", mappedBy="clienteRel")
     */
    protected $pedidosClienteRel;  
    
    /**
     * @ORM\OneToMany(targetEntity="TurProgramacion", mappedBy="clienteRel")
     */
    protected $programacionesClienteRel;     

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pedidosClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->programacionesClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
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
}
