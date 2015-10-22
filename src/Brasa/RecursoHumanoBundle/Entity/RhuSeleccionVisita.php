<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_seleccion_visita")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSeleccionVisitaRepository")
 */
class RhuSeleccionVisita
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_visita_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSeleccionVisitaPk;            
    
    /**
     * @ORM\Column(name="codigo_seleccion_fk", type="integer")
     */    
    private $codigoSeleccionFk;
    
    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */    
    private $fecha;
       
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;              
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuSeleccion", inversedBy="seleccionesVisitasSeleccionRel")
     * @ORM\JoinColumn(name="codigo_seleccion_fk", referencedColumnName="codigo_seleccion_pk")
     */
    protected $seleccionRel; 
    
    

    /**
     * Get codigoSeleccionVisitaPk
     *
     * @return integer
     */
    public function getCodigoSeleccionVisitaPk()
    {
        return $this->codigoSeleccionVisitaPk;
    }

    /**
     * Set codigoSeleccionFk
     *
     * @param integer $codigoSeleccionFk
     *
     * @return RhuSeleccionVisita
     */
    public function setCodigoSeleccionFk($codigoSeleccionFk)
    {
        $this->codigoSeleccionFk = $codigoSeleccionFk;

        return $this;
    }

    /**
     * Get codigoSeleccionFk
     *
     * @return integer
     */
    public function getCodigoSeleccionFk()
    {
        return $this->codigoSeleccionFk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuSeleccionVisita
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
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuSeleccionVisita
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

    /**
     * Set seleccionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionRel
     *
     * @return RhuSeleccionVisita
     */
    public function setSeleccionRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionRel = null)
    {
        $this->seleccionRel = $seleccionRel;

        return $this;
    }

    /**
     * Get seleccionRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion
     */
    public function getSeleccionRel()
    {
        return $this->seleccionRel;
    }
}
