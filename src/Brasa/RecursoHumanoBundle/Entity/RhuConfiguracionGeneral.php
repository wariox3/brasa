<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_configuracion_general")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuConfiguracionGeneralRepository")
 */
class RhuConfiguracionGeneral
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_configuracion_pk", type="integer")
     */
    private $codigoConfiguracionPk;
    
    /**
     * @ORM\Column(name="cuenta", type="string", length=30)
     */    
    private $cuenta;  
    
    /**
     * @ORM\Column(name="tipo_cuenta", type="string", length=3, nullable=false)
     */    
    private $tipoCuenta;
    
    /**
     * @ORM\Column(name="nit", type="float")
     */    
    private $nit;
    
    /**
     * @ORM\Column(name="empresa", type="string", length=90, nullable=false)
     */    
    private $empresa;
    

    /**
     * Set codigoConfiguracionPk
     *
     * @param integer $codigoConfiguracionPk
     *
     * @return RhuConfiguracionGeneral
     */
    public function setCodigoConfiguracionPk($codigoConfiguracionPk)
    {
        $this->codigoConfiguracionPk = $codigoConfiguracionPk;

        return $this;
    }

    /**
     * Get codigoConfiguracionPk
     *
     * @return integer
     */
    public function getCodigoConfiguracionPk()
    {
        return $this->codigoConfiguracionPk;
    }

    /**
     * Set cuenta
     *
     * @param float $cuenta
     *
     * @return RhuConfiguracionGeneral
     */
    public function setCuenta($cuenta)
    {
        $this->cuenta = $cuenta;

        return $this;
    }

    /**
     * Get cuenta
     *
     * @return float
     */
    public function getCuenta()
    {
        return $this->cuenta;
    }

    /**
     * Set tipoCuenta
     *
     * @param string $tipoCuenta
     *
     * @return RhuConfiguracionGeneral
     */
    public function setTipoCuenta($tipoCuenta)
    {
        $this->tipoCuenta = $tipoCuenta;

        return $this;
    }

    /**
     * Get tipoCuenta
     *
     * @return string
     */
    public function getTipoCuenta()
    {
        return $this->tipoCuenta;
    }

    /**
     * Set nit
     *
     * @param float $nit
     *
     * @return RhuConfiguracionGeneral
     */
    public function setNit($nit)
    {
        $this->nit = $nit;

        return $this;
    }

    /**
     * Get nit
     *
     * @return float
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * Set empresa
     *
     * @param string $empresa
     *
     * @return RhuConfiguracionGeneral
     */
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return string
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }
}
