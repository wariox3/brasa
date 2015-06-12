<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_seguridad_social_periodo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSeguridadSocialPeriodoRepository")
 */
class RhuSeguridadSocialPeriodo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seguridad_social_periodo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSeguridadSocialPeriodoPk;   
    
    /**
     * @ORM\Column(name="anio", type="integer")
     */
    private $anio = 0;
    
    /**
     * @ORM\Column(name="mes", type="integer")
     */
    private $mes = 0; 
    
    /**     
     * @ORM\Column(name="estado_generado", type="boolean")
     */    
    private $estadoGenerado = 0;     

    /**
     * Get codigoSeguridadSocialPeriodoPk
     *
     * @return integer
     */
    public function getCodigoSeguridadSocialPeriodoPk()
    {
        return $this->codigoSeguridadSocialPeriodoPk;
    }

    /**
     * Set mes
     *
     * @param integer $mes
     *
     * @return RhuSeguridadSocialPeriodo
     */
    public function setMes($mes)
    {
        $this->mes = $mes;

        return $this;
    }

    /**
     * Get mes
     *
     * @return integer
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * Set anio
     *
     * @param integer $anio
     *
     * @return RhuSeguridadSocialPeriodo
     */
    public function setAnio($anio)
    {
        $this->anio = $anio;

        return $this;
    }

    /**
     * Get anio
     *
     * @return integer
     */
    public function getAnio()
    {
        return $this->anio;
    }

    /**
     * Set estadoGenerado
     *
     * @param boolean $estadoGenerado
     *
     * @return RhuSeguridadSocialPeriodo
     */
    public function setEstadoGenerado($estadoGenerado)
    {
        $this->estadoGenerado = $estadoGenerado;

        return $this;
    }

    /**
     * Get estadoGenerado
     *
     * @return boolean
     */
    public function getEstadoGenerado()
    {
        return $this->estadoGenerado;
    }
}
