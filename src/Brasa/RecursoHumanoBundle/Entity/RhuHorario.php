<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_horario")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuHorarioRepository")
 */
class RhuHorario
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_horario_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoHorarioPk; 
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;
    
    /**
     * @ORM\Column(name="entrada", type="string", length=10, nullable=true)
     */    
    private $entrada;
    
    /**
     * @ORM\Column(name="salida", type="string", length=10, nullable=true)
     */    
    private $salida;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="horarioRel")
     */
    protected $empleadoHorarioRel;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadoHorarioRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoHorarioPk
     *
     * @return integer
     */
    public function getCodigoHorarioPk()
    {
        return $this->codigoHorarioPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuHorario
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
     * Set entrada
     *
     * @param string $entrada
     *
     * @return RhuHorario
     */
    public function setEntrada($entrada)
    {
        $this->entrada = $entrada;

        return $this;
    }

    /**
     * Get entrada
     *
     * @return string
     */
    public function getEntrada()
    {
        return $this->entrada;
    }

    /**
     * Set salida
     *
     * @param string $salida
     *
     * @return RhuHorario
     */
    public function setSalida($salida)
    {
        $this->salida = $salida;

        return $this;
    }

    /**
     * Get salida
     *
     * @return string
     */
    public function getSalida()
    {
        return $this->salida;
    }

    /**
     * Add empleadoHorarioRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoHorarioRel
     *
     * @return RhuHorario
     */
    public function addEmpleadoHorarioRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoHorarioRel)
    {
        $this->empleadoHorarioRel[] = $empleadoHorarioRel;

        return $this;
    }

    /**
     * Remove empleadoHorarioRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoHorarioRel
     */
    public function removeEmpleadoHorarioRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoHorarioRel)
    {
        $this->empleadoHorarioRel->removeElement($empleadoHorarioRel);
    }

    /**
     * Get empleadoHorarioRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadoHorarioRel()
    {
        return $this->empleadoHorarioRel;
    }
}
