<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_horario_periodo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuHorarioPeriodoRepository")
 */
class RhuHorarioPeriodo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_horario_periodo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoHorarioPeriodoPk;
    
    /**
     * @ORM\Column(name="periodo", type="date")
     */    
    private $periodo;
    
    /**     
     * @ORM\Column(name="estado_generado", type="boolean")
     */    
    private $estadoGenerado = FALSE;
    
    /**     
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = FALSE;

    /**
     * Get codigoHorarioPeriodoPk
     *
     * @return integer
     */
    public function getCodigoHorarioPeriodoPk()
    {
        return $this->codigoHorarioPeriodoPk;
    }

    /**
     * Set periodo
     *
     * @param \DateTime $periodo
     *
     * @return RhuHorarioPeriodo
     */
    public function setPeriodo($periodo)
    {
        $this->periodo = $periodo;

        return $this;
    }

    /**
     * Get periodo
     *
     * @return \DateTime
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }

    /**
     * Set estadoGenerado
     *
     * @param boolean $estadoGenerado
     *
     * @return RhuHorarioPeriodo
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

    /**
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return RhuHorarioPeriodo
     */
    public function setEstadoCerrado($estadoCerrado)
    {
        $this->estadoCerrado = $estadoCerrado;

        return $this;
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
}
