<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_programacion_inconsistencia")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurProgramacionInconsistenciaRepository")
 */
class TurProgramacionInconsistencia
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_programacion_inconsistencia_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProgramacionInconsistenciaPk;             

    /**
     * @ORM\Column(name="inconsistencia", type="string", length=100, nullable=true)
     */    
    private $inconsistencia;    
    
    /**
     * @ORM\Column(name="detalle", type="string", length=200, nullable=true)
     */    
    private $detalle;                    

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=true)
     */    
    private $numeroIdentificacion;     

    /**
     * Get codigoProgramacionInconsistenciaPk
     *
     * @return integer
     */
    public function getCodigoProgramacionInconsistenciaPk()
    {
        return $this->codigoProgramacionInconsistenciaPk;
    }

    /**
     * Set inconsistencia
     *
     * @param string $inconsistencia
     *
     * @return TurProgramacionInconsistencia
     */
    public function setInconsistencia($inconsistencia)
    {
        $this->inconsistencia = $inconsistencia;

        return $this;
    }

    /**
     * Get inconsistencia
     *
     * @return string
     */
    public function getInconsistencia()
    {
        return $this->inconsistencia;
    }

    /**
     * Set detalle
     *
     * @param string $detalle
     *
     * @return TurProgramacionInconsistencia
     */
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;

        return $this;
    }

    /**
     * Get detalle
     *
     * @return string
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * Set numeroIdentificacion
     *
     * @param string $numeroIdentificacion
     *
     * @return TurProgramacionInconsistencia
     */
    public function setNumeroIdentificacion($numeroIdentificacion)
    {
        $this->numeroIdentificacion = $numeroIdentificacion;

        return $this;
    }

    /**
     * Get numeroIdentificacion
     *
     * @return string
     */
    public function getNumeroIdentificacion()
    {
        return $this->numeroIdentificacion;
    }
}
