<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ctb_cierres_mes")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbCierresMesRepository")
 */
class CtbCierresMes
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cierre_mes_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCierreMesPk;
    
    /**
     * @ORM\Column(name="annio", type="integer")
     */
    private $annio;

    /**
     * @ORM\Column(name="mes", type="smallint")
     */
    private $mes;

    /**
     * @ORM\Column(name="fecha_cierre", type="datetime", nullable=true)
     */    
    private $fechaCierre;
    
    /**
     * @ORM\Column(name="fecha_inicio", type="datetime", nullable=true)
     */    
    private $fechaInicio;      

    /**
     * @ORM\Column(name="fecha_fin", type="datetime", nullable=true)
     */    
    private $fechaFin;        
    
    /**
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = 0;     
    


    /**
     * Get codigoCierreMesContabilidadPk
     *
     * @return integer 
     */
    public function getCodigoCierreMesContabilidadPk()
    {
        return $this->codigoCierreMesContabilidadPk;
    }

    /**
     * Set annio
     *
     * @param integer $annio
     */
    public function setAnnio($annio)
    {
        $this->annio = $annio;
    }

    /**
     * Get annio
     *
     * @return integer 
     */
    public function getAnnio()
    {
        return $this->annio;
    }

    /**
     * Set mes
     *
     * @param smallint $mes
     */
    public function setMes($mes)
    {
        $this->mes = $mes;
    }

    /**
     * Get mes
     *
     * @return smallint 
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * Set fechaCierre
     *
     * @param datetime $fechaCierre
     */
    public function setFechaCierre($fechaCierre)
    {
        $this->fechaCierre = $fechaCierre;
    }

    /**
     * Get fechaCierre
     *
     * @return datetime 
     */
    public function getFechaCierre()
    {
        return $this->fechaCierre;
    }

    /**
     * Set fechaInicio
     *
     * @param datetime $fechaInicio
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;
    }

    /**
     * Get fechaInicio
     *
     * @return datetime 
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaFin
     *
     * @param datetime $fechaFin
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;
    }

    /**
     * Get fechaFin
     *
     * @return datetime 
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     */
    public function setEstadoCerrado($estadoCerrado)
    {
        $this->estadoCerrado = $estadoCerrado;
    }

    /**
     * Get estadoCerrado
     *
     * @return boolean 
     */
    public function getEstadoCerrado()
    {
        return $this->estadoCerrado;
    }

    /**
     * Get codigoCierreMesPk
     *
     * @return integer 
     */
    public function getCodigoCierreMesPk()
    {
        return $this->codigoCierreMesPk;
    }
}
