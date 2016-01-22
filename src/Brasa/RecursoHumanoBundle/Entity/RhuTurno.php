<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_turno")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuTurnoRepository")
 * @DoctrineAssert\UniqueEntity(fields={"codigoTurnoPk"},message="Ya existe este código para turno")
 */
class RhuTurno
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_turno_pk", type="string", length=5)
     */
    private $codigoTurnoPk;       
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;
    
    /**
     * @ORM\Column(name="hora_desde", type="time", nullable=true)
     */    
    private $horaDesde;    

    /**
     * @ORM\Column(name="hora_hasta", type="time", nullable=true)
     */    
    private $horaHasta;    
    
    /**
     * @ORM\Column(name="horas", type="float")
     */    
    private $horas = 0;    

    /**
     * @ORM\Column(name="horas_diurnas", type="float")
     */    
    private $horasDiurnas = 0;     
    
    /**
     * @ORM\Column(name="horas_nocturnas", type="float")
     */    
    private $horasNocturnas = 0;            
    
    /**
     * @ORM\Column(name="novedad", type="boolean", nullable=true)
     */    
    private $novedad = false;     

    /**
     * @ORM\Column(name="descanso", type="boolean", nullable=true)
     */    
    private $descanso = false;           
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;       

    

    /**
     * Set codigoTurnoPk
     *
     * @param string $codigoTurnoPk
     *
     * @return RhuTurno
     */
    public function setCodigoTurnoPk($codigoTurnoPk)
    {
        $this->codigoTurnoPk = $codigoTurnoPk;

        return $this;
    }

    /**
     * Get codigoTurnoPk
     *
     * @return string
     */
    public function getCodigoTurnoPk()
    {
        return $this->codigoTurnoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuTurno
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
     * Set horaDesde
     *
     * @param \DateTime $horaDesde
     *
     * @return RhuTurno
     */
    public function setHoraDesde($horaDesde)
    {
        $this->horaDesde = $horaDesde;

        return $this;
    }

    /**
     * Get horaDesde
     *
     * @return \DateTime
     */
    public function getHoraDesde()
    {
        return $this->horaDesde;
    }

    /**
     * Set horaHasta
     *
     * @param \DateTime $horaHasta
     *
     * @return RhuTurno
     */
    public function setHoraHasta($horaHasta)
    {
        $this->horaHasta = $horaHasta;

        return $this;
    }

    /**
     * Get horaHasta
     *
     * @return \DateTime
     */
    public function getHoraHasta()
    {
        return $this->horaHasta;
    }

    /**
     * Set horas
     *
     * @param float $horas
     *
     * @return RhuTurno
     */
    public function setHoras($horas)
    {
        $this->horas = $horas;

        return $this;
    }

    /**
     * Get horas
     *
     * @return float
     */
    public function getHoras()
    {
        return $this->horas;
    }

    /**
     * Set horasDiurnas
     *
     * @param float $horasDiurnas
     *
     * @return RhuTurno
     */
    public function setHorasDiurnas($horasDiurnas)
    {
        $this->horasDiurnas = $horasDiurnas;

        return $this;
    }

    /**
     * Get horasDiurnas
     *
     * @return float
     */
    public function getHorasDiurnas()
    {
        return $this->horasDiurnas;
    }

    /**
     * Set horasNocturnas
     *
     * @param float $horasNocturnas
     *
     * @return RhuTurno
     */
    public function setHorasNocturnas($horasNocturnas)
    {
        $this->horasNocturnas = $horasNocturnas;

        return $this;
    }

    /**
     * Get horasNocturnas
     *
     * @return float
     */
    public function getHorasNocturnas()
    {
        return $this->horasNocturnas;
    }

    /**
     * Set novedad
     *
     * @param boolean $novedad
     *
     * @return RhuTurno
     */
    public function setNovedad($novedad)
    {
        $this->novedad = $novedad;

        return $this;
    }

    /**
     * Get novedad
     *
     * @return boolean
     */
    public function getNovedad()
    {
        return $this->novedad;
    }

    /**
     * Set descanso
     *
     * @param boolean $descanso
     *
     * @return RhuTurno
     */
    public function setDescanso($descanso)
    {
        $this->descanso = $descanso;

        return $this;
    }

    /**
     * Get descanso
     *
     * @return boolean
     */
    public function getDescanso()
    {
        return $this->descanso;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuTurno
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }
}
