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
     * @ORM\Column(name="tipo", type="string", length=60)
     */
    private $tipo;
    
    /**
     * @ORM\Column(name="codigo_banco_fk", type="integer")
     */
    private $codigoBancoFk;
    
    /**
     * @ORM\ManyToOne(targetEntity="GenBanco", inversedBy="cuentasBancoRel")
     * @ORM\JoinColumn(name="codigo_banco_fk", referencedColumnName="codigo_banco_pk")
     */
    protected $bancoRel;    
    

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
}
