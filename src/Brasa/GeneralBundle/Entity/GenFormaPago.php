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
     * @ORM\Column(name="nombre_forma_pago", type="string", length=50)
     * @Assert\NotNull()(message="Debe escribir un nombre de la forma de pago")
     */
    private $nombreFormaPago;

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
    
    public function __construct()
    {
        $this->tercerosFormaPagoClienteRel = new ArrayCollection();
        $this->tercerosFormaPagoProveedorRel = new ArrayCollection();
        $this->movimientosRel = new ArrayCollection();
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
     * Set nombreFormaPago
     *
     * @param string $nombreFormaPago
     *
     * @return GenFormaPago
     */
    public function setNombreFormaPago($nombreFormaPago)
    {
        $this->nombreFormaPago = $nombreFormaPago;

        return $this;
    }

    /**
     * Get nombreFormaPago
     *
     * @return string
     */
    public function getNombreFormaPago()
    {
        return $this->nombreFormaPago;
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
}
