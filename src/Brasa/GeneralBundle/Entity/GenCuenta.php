<?php

namespace Brasa\GeneralBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_cuenta")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenCuentaRepository")
 */
class GenCuenta
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cuenta_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCuentaPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=60)
     */
    private $nombre;
    
    /**
     * @ORM\Column(name="cuenta", type="string", length=20)
     */
    private $cuenta;
    
    /**
     * @ORM\Column(name="tipo", type="string", length=60)
     */
    private $tipo;
    
    /**
     * @ORM\Column(name="codigo_banco_fk", type="integer")
     */
    private $codigoBancoFk;
    
    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=20)
     */
    private $codigoCuentaFk;    
    
    /**
     * @ORM\ManyToOne(targetEntity="GenBanco", inversedBy="cuentasBancoRel")
     * @ORM\JoinColumn(name="codigo_banco_fk", referencedColumnName="codigo_banco_pk")
     */
    protected $bancoRel;        

    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco", mappedBy="cuentaRel")
     */
    protected $rhuPagosBancosCuentaRel;    
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rhuPagosBancosCuentaRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoCuentaPk
     *
     * @return integer
     */
    public function getCodigoCuentaPk()
    {
        return $this->codigoCuentaPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return GenCuenta
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
     * Set cuenta
     *
     * @param string $cuenta
     *
     * @return GenCuenta
     */
    public function setCuenta($cuenta)
    {
        $this->cuenta = $cuenta;

        return $this;
    }

    /**
     * Get cuenta
     *
     * @return string
     */
    public function getCuenta()
    {
        return $this->cuenta;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return GenCuenta
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set codigoBancoFk
     *
     * @param integer $codigoBancoFk
     *
     * @return GenCuenta
     */
    public function setCodigoBancoFk($codigoBancoFk)
    {
        $this->codigoBancoFk = $codigoBancoFk;

        return $this;
    }

    /**
     * Get codigoBancoFk
     *
     * @return integer
     */
    public function getCodigoBancoFk()
    {
        return $this->codigoBancoFk;
    }

    /**
     * Set codigoCuentaFk
     *
     * @param string $codigoCuentaFk
     *
     * @return GenCuenta
     */
    public function setCodigoCuentaFk($codigoCuentaFk)
    {
        $this->codigoCuentaFk = $codigoCuentaFk;

        return $this;
    }

    /**
     * Get codigoCuentaFk
     *
     * @return string
     */
    public function getCodigoCuentaFk()
    {
        return $this->codigoCuentaFk;
    }

    /**
     * Set bancoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenBanco $bancoRel
     *
     * @return GenCuenta
     */
    public function setBancoRel(\Brasa\GeneralBundle\Entity\GenBanco $bancoRel = null)
    {
        $this->bancoRel = $bancoRel;

        return $this;
    }

    /**
     * Get bancoRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenBanco
     */
    public function getBancoRel()
    {
        return $this->bancoRel;
    }

    /**
     * Add rhuPagosBancosCuentaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco $rhuPagosBancosCuentaRel
     *
     * @return GenCuenta
     */
    public function addRhuPagosBancosCuentaRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco $rhuPagosBancosCuentaRel)
    {
        $this->rhuPagosBancosCuentaRel[] = $rhuPagosBancosCuentaRel;

        return $this;
    }

    /**
     * Remove rhuPagosBancosCuentaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco $rhuPagosBancosCuentaRel
     */
    public function removeRhuPagosBancosCuentaRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco $rhuPagosBancosCuentaRel)
    {
        $this->rhuPagosBancosCuentaRel->removeElement($rhuPagosBancosCuentaRel);
    }

    /**
     * Get rhuPagosBancosCuentaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuPagosBancosCuentaRel()
    {
        return $this->rhuPagosBancosCuentaRel;
    }
}
