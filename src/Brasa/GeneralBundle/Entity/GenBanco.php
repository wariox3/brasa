<?php

namespace Brasa\GeneralBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_banco")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenBancoRepository")
 */
class GenBanco
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_banco_general_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoBancoGeneralPk;

    /**
     * @ORM\Column(name="cuenta", type="string", length=20)
     */
    private $cuenta;    
    
    /**
     * @ORM\Column(name="codigo_banco_fk", type="integer")
     */
    private $codigoBancoFk;    

     /**
     * @ORM\ManyToOne(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuBanco", inversedBy="rhuBancosBancoRel")
     * @ORM\JoinColumn(name="codigo_banco_fk", referencedColumnName="codigo_banco_pk")
     */
    protected $bancoRel;     
    
    

    /**
     * Get codigoBancoGeneralPk
     *
     * @return integer
     */
    public function getCodigoBancoGeneralPk()
    {
        return $this->codigoBancoGeneralPk;
    }

    /**
     * Set cuenta
     *
     * @param string $cuenta
     *
     * @return GenBanco
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
     * Set codigoBancoFk
     *
     * @param integer $codigoBancoFk
     *
     * @return GenBanco
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
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuBanco $bancoRel
     *
     * @return GenBanco
     */
    public function setBancoRel(\Brasa\RecursoHumanoBundle\Entity\RhuBanco $bancoRel = null)
    {
        $this->bancoRel = $bancoRel;

        return $this;
    }

    /**
     * Get bancoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuBanco
     */
    public function getBancoRel()
    {
        return $this->bancoRel;
    }
}
