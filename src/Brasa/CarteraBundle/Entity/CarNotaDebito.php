<?php

namespace Brasa\CarteraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="car_nota_debito")
 * @ORM\Entity(repositoryClass="Brasa\CarteraBundle\Repository\CarNotaDebitoRepository")
 */
class CarNotaDebito
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_nota_debito_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoNotaDebitoPk;        

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
     * @ORM\ManyToOne(targetEntity="CarCliente", inversedBy="notasDebitosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;
    
    /**
     * @ORM\OneToMany(targetEntity="CarNotaDebitoDetalle", mappedBy="notaDebitoRel")
     */
    protected $notasDebitosDetallesNotaDebitoRel;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->notasDebitosDetallesNotaDebitoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoNotaDebitoPk
     *
     * @return integer
     */
    public function getCodigoNotaDebitoPk()
    {
        return $this->codigoNotaDebitoPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return CarNotaDebito
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
     * @return CarNotaDebito
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
     * @return CarNotaDebito
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
     * @return CarNotaDebito
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
     * @return CarNotaDebito
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
     * @return CarNotaDebito
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
     * @return CarNotaDebito
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
     * Add notasDebitosDetallesNotaDebitoRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarNotaDebitoDetalle $notasDebitosDetallesNotaDebitoRel
     *
     * @return CarNotaDebito
     */
    public function addNotasDebitosDetallesNotaDebitoRel(\Brasa\CarteraBundle\Entity\CarNotaDebitoDetalle $notasDebitosDetallesNotaDebitoRel)
    {
        $this->notasDebitosDetallesNotaDebitoRel[] = $notasDebitosDetallesNotaDebitoRel;

        return $this;
    }

    /**
     * Remove notasDebitosDetallesNotaDebitoRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarNotaDebitoDetalle $notasDebitosDetallesNotaDebitoRel
     */
    public function removeNotasDebitosDetallesNotaDebitoRel(\Brasa\CarteraBundle\Entity\CarNotaDebitoDetalle $notasDebitosDetallesNotaDebitoRel)
    {
        $this->notasDebitosDetallesNotaDebitoRel->removeElement($notasDebitosDetallesNotaDebitoRel);
    }

    /**
     * Get notasDebitosDetallesNotaDebitoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotasDebitosDetallesNotaDebitoRel()
    {
        return $this->notasDebitosDetallesNotaDebitoRel;
    }
}
