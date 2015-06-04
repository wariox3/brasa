<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_seleccion_grupo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSeleccionGrupoRepository")
 */
class RhuSeleccionGrupo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_grupo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSeleccionGrupoPk;        
    
    /**
     * @ORM\Column(name="fecha", type="datetime")
     */ 
    
    private $fecha;                   
             
    /**     
     * @ORM\Column(name="nombre", type="string")
     */    
    
    private $nombre;
    
    /**     
     * @ORM\Column(name="estado_aprobado", type="boolean")
     */    
    
    private $estadoAprobado = 0;        
    
    /**     
     * @ORM\Column(name="estado_abierto", type="boolean")
     */    
    private $estadoAbierto = 0;    
    
    /**     
     * @ORM\Column(name="presenta_pruebas", type="boolean")
     */    
    private $presentaPruebas = 0;
    
    /**     
     * @ORM\Column(name="referencias_verificadas", type="boolean")
     */    
    private $referenciasVerificadas = 0;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccion", mappedBy="selecccionGrupoRel")
     */
    protected $seleccionSeleccionGrupoRel;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->seleccionSeleccionGrupoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoSeleccionGrupoPk
     *
     * @return integer
     */
    public function getCodigoSeleccionGrupoPk()
    {
        return $this->codigoSeleccionGrupoPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuSeleccionGrupo
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuSeleccionGrupo
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
     * Set estadoAprobado
     *
     * @param boolean $estadoAprobado
     *
     * @return RhuSeleccionGrupo
     */
    public function setEstadoAprobado($estadoAprobado)
    {
        $this->estadoAprobado = $estadoAprobado;

        return $this;
    }

    /**
     * Get estadoAprobado
     *
     * @return boolean
     */
    public function getEstadoAprobado()
    {
        return $this->estadoAprobado;
    }

    /**
     * Set estadoAbierto
     *
     * @param boolean $estadoAbierto
     *
     * @return RhuSeleccionGrupo
     */
    public function setEstadoAbierto($estadoAbierto)
    {
        $this->estadoAbierto = $estadoAbierto;

        return $this;
    }

    /**
     * Get estadoAbierto
     *
     * @return boolean
     */
    public function getEstadoAbierto()
    {
        return $this->estadoAbierto;
    }

    /**
     * Set presentaPruebas
     *
     * @param boolean $presentaPruebas
     *
     * @return RhuSeleccionGrupo
     */
    public function setPresentaPruebas($presentaPruebas)
    {
        $this->presentaPruebas = $presentaPruebas;

        return $this;
    }

    /**
     * Get presentaPruebas
     *
     * @return boolean
     */
    public function getPresentaPruebas()
    {
        return $this->presentaPruebas;
    }

    /**
     * Set referenciasVerificadas
     *
     * @param boolean $referenciasVerificadas
     *
     * @return RhuSeleccionGrupo
     */
    public function setReferenciasVerificadas($referenciasVerificadas)
    {
        $this->referenciasVerificadas = $referenciasVerificadas;

        return $this;
    }

    /**
     * Get referenciasVerificadas
     *
     * @return boolean
     */
    public function getReferenciasVerificadas()
    {
        return $this->referenciasVerificadas;
    }

    /**
     * Add seleccionSeleccionGrupoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionSeleccionGrupoRel
     *
     * @return RhuSeleccionGrupo
     */
    public function addSeleccionSeleccionGrupoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionSeleccionGrupoRel)
    {
        $this->seleccionSeleccionGrupoRel[] = $seleccionSeleccionGrupoRel;

        return $this;
    }

    /**
     * Remove seleccionSeleccionGrupoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionSeleccionGrupoRel
     */
    public function removeSeleccionSeleccionGrupoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionSeleccionGrupoRel)
    {
        $this->seleccionSeleccionGrupoRel->removeElement($seleccionSeleccionGrupoRel);
    }

    /**
     * Get seleccionSeleccionGrupoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionSeleccionGrupoRel()
    {
        return $this->seleccionSeleccionGrupoRel;
    }
}
