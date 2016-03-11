<?php

namespace Brasa\CarteraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="car_cuenta_cobrar_tipo")
 * @ORM\Entity(repositoryClass="Brasa\CarteraBundle\Repository\CarCuentaCobrarTipoRepository")
 */
class CarCuentaCobrarTipo
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cuenta_cobrar_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoCuentaCobrarTipoPk;        

    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;    
    
    /**
     * @ORM\OneToMany(targetEntity="CarCuentaCobrar", mappedBy="cuentaCobrarTipoRel")
     */
    protected $cuentasCobrarCuentaCobrarTipoRel;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cuentasCobrarCuentaCobrarTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoCuentaCobrarTipoPk
     *
     * @return integer
     */
    public function getCodigoCuentaCobrarTipoPk()
    {
        return $this->codigoCuentaCobrarTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return CarCuentaCobrarTipo
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
     * Add cuentasCobrarCuentaCobrarTipoRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCuentaCobrar $cuentasCobrarCuentaCobrarTipoRel
     *
     * @return CarCuentaCobrarTipo
     */
    public function addCuentasCobrarCuentaCobrarTipoRel(\Brasa\CarteraBundle\Entity\CarCuentaCobrar $cuentasCobrarCuentaCobrarTipoRel)
    {
        $this->cuentasCobrarCuentaCobrarTipoRel[] = $cuentasCobrarCuentaCobrarTipoRel;

        return $this;
    }

    /**
     * Remove cuentasCobrarCuentaCobrarTipoRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCuentaCobrar $cuentasCobrarCuentaCobrarTipoRel
     */
    public function removeCuentasCobrarCuentaCobrarTipoRel(\Brasa\CarteraBundle\Entity\CarCuentaCobrar $cuentasCobrarCuentaCobrarTipoRel)
    {
        $this->cuentasCobrarCuentaCobrarTipoRel->removeElement($cuentasCobrarCuentaCobrarTipoRel);
    }

    /**
     * Get cuentasCobrarCuentaCobrarTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCuentasCobrarCuentaCobrarTipoRel()
    {
        return $this->cuentasCobrarCuentaCobrarTipoRel;
    }
}
