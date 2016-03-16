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
    protected $cuentasCobrarTiposCuentaCobrarRel;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cuentasCobrarTiposCuentaCobrarRel = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add cuentasCobrarTiposCuentaCobrarRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCuentaCobrar $cuentasCobrarTiposCuentaCobrarRel
     *
     * @return CarCuentaCobrarTipo
     */
    public function addCuentasCobrarTiposCuentaCobrarRel(\Brasa\CarteraBundle\Entity\CarCuentaCobrar $cuentasCobrarTiposCuentaCobrarRel)
    {
        $this->cuentasCobrarTiposCuentaCobrarRel[] = $cuentasCobrarTiposCuentaCobrarRel;

        return $this;
    }

    /**
     * Remove cuentasCobrarTiposCuentaCobrarRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCuentaCobrar $cuentasCobrarTiposCuentaCobrarRel
     */
    public function removeCuentasCobrarTiposCuentaCobrarRel(\Brasa\CarteraBundle\Entity\CarCuentaCobrar $cuentasCobrarTiposCuentaCobrarRel)
    {
        $this->cuentasCobrarTiposCuentaCobrarRel->removeElement($cuentasCobrarTiposCuentaCobrarRel);
    }

    /**
     * Get cuentasCobrarTiposCuentaCobrarRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCuentasCobrarTiposCuentaCobrarRel()
    {
        return $this->cuentasCobrarTiposCuentaCobrarRel;
    }
}
