<?php

namespace Brasa\GeneralBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_forma_pago")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenFormaPagoRepository")
 */
class GenFormaPago
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_forma_pago_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoFormaPagoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     * @Assert\NotNull()(message="Debe escribir un nombre de la forma de pago")
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="GenTercero", mappedBy="formaPagoClienteRel")
     */
    protected $tercerosFormaPagoClienteRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="GenTercero", mappedBy="formaPagoProveedorRel")     
     */
    protected $tercerosFormaPagoProveedorRel;

    /**
     * @ORM\OneToMany(targetEntity="Brasa\InventarioBundle\Entity\InvMovimiento", mappedBy="formaPagoRel")
     */
    protected $movimientosRel;        
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\TurnoBundle\Entity\TurCliente", mappedBy="formaPagoRel")
     */
    protected $turClientesFormaPagoRel;

    /**
     * @ORM\OneToMany(targetEntity="Brasa\CarteraBundle\Entity\CarCliente", mappedBy="formaPagoRel")
     */
    protected $carClientesFormaPagoRel;
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tercerosFormaPagoClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tercerosFormaPagoProveedorRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->movimientosRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->turClientesFormaPagoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoFormaPagoPk
     *
     * @return integer
     */
    public function getCodigoFormaPagoPk()
    {
        return $this->codigoFormaPagoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return GenFormaPago
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
     * Add tercerosFormaPagoClienteRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTercero $tercerosFormaPagoClienteRel
     *
     * @return GenFormaPago
     */
    public function addTercerosFormaPagoClienteRel(\Brasa\GeneralBundle\Entity\GenTercero $tercerosFormaPagoClienteRel)
    {
        $this->tercerosFormaPagoClienteRel[] = $tercerosFormaPagoClienteRel;

        return $this;
    }

    /**
     * Remove tercerosFormaPagoClienteRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTercero $tercerosFormaPagoClienteRel
     */
    public function removeTercerosFormaPagoClienteRel(\Brasa\GeneralBundle\Entity\GenTercero $tercerosFormaPagoClienteRel)
    {
        $this->tercerosFormaPagoClienteRel->removeElement($tercerosFormaPagoClienteRel);
    }

    /**
     * Get tercerosFormaPagoClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTercerosFormaPagoClienteRel()
    {
        return $this->tercerosFormaPagoClienteRel;
    }

    /**
     * Add tercerosFormaPagoProveedorRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTercero $tercerosFormaPagoProveedorRel
     *
     * @return GenFormaPago
     */
    public function addTercerosFormaPagoProveedorRel(\Brasa\GeneralBundle\Entity\GenTercero $tercerosFormaPagoProveedorRel)
    {
        $this->tercerosFormaPagoProveedorRel[] = $tercerosFormaPagoProveedorRel;

        return $this;
    }

    /**
     * Remove tercerosFormaPagoProveedorRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTercero $tercerosFormaPagoProveedorRel
     */
    public function removeTercerosFormaPagoProveedorRel(\Brasa\GeneralBundle\Entity\GenTercero $tercerosFormaPagoProveedorRel)
    {
        $this->tercerosFormaPagoProveedorRel->removeElement($tercerosFormaPagoProveedorRel);
    }

    /**
     * Get tercerosFormaPagoProveedorRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTercerosFormaPagoProveedorRel()
    {
        return $this->tercerosFormaPagoProveedorRel;
    }

    /**
     * Add movimientosRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimiento $movimientosRel
     *
     * @return GenFormaPago
     */
    public function addMovimientosRel(\Brasa\InventarioBundle\Entity\InvMovimiento $movimientosRel)
    {
        $this->movimientosRel[] = $movimientosRel;

        return $this;
    }

    /**
     * Remove movimientosRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimiento $movimientosRel
     */
    public function removeMovimientosRel(\Brasa\InventarioBundle\Entity\InvMovimiento $movimientosRel)
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
     * Add turClientesFormaPagoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $turClientesFormaPagoRel
     *
     * @return GenFormaPago
     */
    public function addTurClientesFormaPagoRel(\Brasa\TurnoBundle\Entity\TurCliente $turClientesFormaPagoRel)
    {
        $this->turClientesFormaPagoRel[] = $turClientesFormaPagoRel;

        return $this;
    }

    /**
     * Remove turClientesFormaPagoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $turClientesFormaPagoRel
     */
    public function removeTurClientesFormaPagoRel(\Brasa\TurnoBundle\Entity\TurCliente $turClientesFormaPagoRel)
    {
        $this->turClientesFormaPagoRel->removeElement($turClientesFormaPagoRel);
    }

    /**
     * Get turClientesFormaPagoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTurClientesFormaPagoRel()
    {
        return $this->turClientesFormaPagoRel;
    }

    /**
     * Add carClientesFormaPagoRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCliente $carClientesFormaPagoRel
     *
     * @return GenFormaPago
     */
    public function addCarClientesFormaPagoRel(\Brasa\CarteraBundle\Entity\CarCliente $carClientesFormaPagoRel)
    {
        $this->carClientesFormaPagoRel[] = $carClientesFormaPagoRel;

        return $this;
    }

    /**
     * Remove carClientesFormaPagoRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCliente $carClientesFormaPagoRel
     */
    public function removeCarClientesFormaPagoRel(\Brasa\CarteraBundle\Entity\CarCliente $carClientesFormaPagoRel)
    {
        $this->carClientesFormaPagoRel->removeElement($carClientesFormaPagoRel);
    }

    /**
     * Get carClientesFormaPagoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCarClientesFormaPagoRel()
    {
        return $this->carClientesFormaPagoRel;
    }
}
