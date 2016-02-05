<?php

namespace Brasa\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_tarea")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenTareaRepository")
 */
class GenTarea
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_tarea_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoTareaPk;                    
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=false)
     */    
    private $fecha;             

    /**
     * @ORM\Column(name="hora", type="time", nullable=false)
     */    
    private $hora;    

    /**
     * @ORM\Column(name="asunto", type="string", length=80, nullable=true)
     */    
    private $asunto;               

    /**
     * @ORM\Column(name="estado_terminado", type="boolean")
     */    
    private $estadoTerminado = 0;  
    
    /**
     * @ORM\Column(name="usuario_crea_fk", type="string", length=50, nullable=true)
     */    
    private $usuarioCreaFk;    
    
    /**
     * @ORM\Column(name="usuario_termina_fk", type="string", length=50, nullable=true)
     */    
    private $usuarioTerminaFk;    
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=250, nullable=true)
     */    
    private $comentarios;           



    /**
     * Get codigoTareaPk
     *
     * @return integer
     */
    public function getCodigoTareaPk()
    {
        return $this->codigoTareaPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return GenTarea
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set hora
     *
     * @param \DateTime $hora
     *
     * @return GenTarea
     */
    public function setHora($hora)
    {
        $this->hora = $hora;

        return $this;
    }

    /**
     * Get hora
     *
     * @return \DateTime
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * Set asunto
     *
     * @param string $asunto
     *
     * @return GenTarea
     */
    public function setAsunto($asunto)
    {
        $this->asunto = $asunto;

        return $this;
    }

    /**
     * Get asunto
     *
     * @return string
     */
    public function getAsunto()
    {
        return $this->asunto;
    }

    /**
     * Set estadoTerminado
     *
     * @param boolean $estadoTerminado
     *
     * @return GenTarea
     */
    public function setEstadoTerminado($estadoTerminado)
    {
        $this->estadoTerminado = $estadoTerminado;

        return $this;
    }

    /**
     * Get estadoTerminado
     *
     * @return boolean
     */
    public function getEstadoTerminado()
    {
        return $this->estadoTerminado;
    }

    /**
     * Set usuarioCreaFk
     *
     * @param string $usuarioCreaFk
     *
     * @return GenTarea
     */
    public function setUsuarioCreaFk($usuarioCreaFk)
    {
        $this->usuarioCreaFk = $usuarioCreaFk;

        return $this;
    }

    /**
     * Get usuarioCreaFk
     *
     * @return string
     */
    public function getUsuarioCreaFk()
    {
        return $this->usuarioCreaFk;
    }

    /**
     * Set usuarioTerminaFk
     *
     * @param string $usuarioTerminaFk
     *
     * @return GenTarea
     */
    public function setUsuarioTerminaFk($usuarioTerminaFk)
    {
        $this->usuarioTerminaFk = $usuarioTerminaFk;

        return $this;
    }

    /**
     * Get usuarioTerminaFk
     *
     * @return string
     */
    public function getUsuarioTerminaFk()
    {
        return $this->usuarioTerminaFk;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return GenTarea
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
