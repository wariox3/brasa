<?php

namespace Brasa\CarteraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="car_nota_credito")
 * @ORM\Entity(repositoryClass="Brasa\CarteraBundle\Repository\CarNotaCreditoRepository")
 */
class CarNotaCredito
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_nota_credito_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoNotaCreditoPk;        

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;     

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */     
    private $codigoClienteFk;
    
    /**
     * @ORM\Column(name="numero", type="string", length=30, nullable=true)
     */    
    private $numero;
    
    /**
     * @ORM\Column(name="valor", type="float")
     */    
    private $valor = 0;      
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;
    
    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */    
    private $usuario;
    
    /**
     * @ORM\ManyToOne(targetEntity="CarCliente", inversedBy="notasCreditosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;

    /**
     * @ORM\OneToMany(targetEntity="CarNotaCreditoDetalle", mappedBy="notaCreditoRel")
     */
    protected $notasCreditosDetallesNotaCreditoRel;
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->notasCreditosDetallesNotaCreditoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoNotaCreditoPk
     *
     * @return integer
     */
    public function getCodigoNotaCreditoPk()
    {
        return $this->codigoNotaCreditoPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return CarNotaCredito
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return CarNotaCredito
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
     * Set numero
     *
     * @param string $numero
     *
     * @return CarNotaCredito
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set valor
     *
     * @param float $valor
     *
     * @return CarNotaCredito
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return CarNotaCredito
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
     * Set usuario
     *
     * @param string $usuario
     *
     * @return CarNotaCredito
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
     * Set clienteRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCliente $clienteRel
     *
     * @return CarNotaCredito
     */
    public function setClienteRel(\Brasa\CarteraBundle\Entity\CarCliente $clienteRel = null)
    {
        $this->clienteRel = $clienteRel;

        return $this;
    }

    /**
     * Get clienteRel
     *
     * @return \Brasa\CarteraBundle\Entity\CarCliente
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
    }

    /**
     * Add notasCreditosDetallesNotaCreditoRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarNotaCreditoDetalle $notasCreditosDetallesNotaCreditoRel
     *
     * @return CarNotaCredito
     */
    public function addNotasCreditosDetallesNotaCreditoRel(\Brasa\CarteraBundle\Entity\CarNotaCreditoDetalle $notasCreditosDetallesNotaCreditoRel)
    {
        $this->notasCreditosDetallesNotaCreditoRel[] = $notasCreditosDetallesNotaCreditoRel;

        return $this;
    }

    /**
     * Remove notasCreditosDetallesNotaCreditoRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarNotaCreditoDetalle $notasCreditosDetallesNotaCreditoRel
     */
    public function removeNotasCreditosDetallesNotaCreditoRel(\Brasa\CarteraBundle\Entity\CarNotaCreditoDetalle $notasCreditosDetallesNotaCreditoRel)
    {
        $this->notasCreditosDetallesNotaCreditoRel->removeElement($notasCreditosDetallesNotaCreditoRel);
    }

    /**
     * Get notasCreditosDetallesNotaCreditoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotasCreditosDetallesNotaCreditoRel()
    {
        return $this->notasCreditosDetallesNotaCreditoRel;
    }
}
