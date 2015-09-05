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
     * @ORM\Column(name="fecha", type="date")
     */ 
    
    private $fecha;                   
    
    /**     
     * @ORM\Column(name="nombre", type="string")
     */    
    
    private $nombre;           
                
    /**     
     * @ORM\Column(name="cantidad_solicitada", type="integer")
     */    
    private $cantidadSolicitida;
    
    /**
     * @ORM\Column(name="fecha_pruebas", type="datetime")
     */ 
    
    private $fechaPruebas;         
    
    /**     
     * @ORM\Column(type="integer", name="estado_abierto", options={"unsigned":true, "default":"1"})
     */    
    private $estadoAbierto= 1;            
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk; 
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="seleccionesGruposCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccion", mappedBy="seleccionGrupoRel")
     */
    protected $seleccionesSeleccionGrupoRel;
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->seleccionesSeleccionGrupoRel = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set cantidadSolicitida
     *
     * @param integer $cantidadSolicitida
     *
     * @return RhuSeleccionGrupo
     */
    public function setCantidadSolicitida($cantidadSolicitida)
    {
        $this->cantidadSolicitida = $cantidadSolicitida;

        return $this;
    }

    /**
     * Get cantidadSolicitida
     *
     * @return integer
     */
    public function getCantidadSolicitida()
    {
        return $this->cantidadSolicitida;
    }

    /**
     * Set fechaPruebas
     *
     * @param \DateTime $fechaPruebas
     *
     * @return RhuSeleccionGrupo
     */
    public function setFechaPruebas($fechaPruebas)
    {
        $this->fechaPruebas = $fechaPruebas;

        return $this;
    }

    /**
     * Get fechaPruebas
     *
     * @return \DateTime
     */
    public function getFechaPruebas()
    {
        return $this->fechaPruebas;
    }

    /**
     * Set estadoAbierto
     *
     * @param integer $estadoAbierto
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
     * @return integer
     */
    public function getEstadoAbierto()
    {
        return $this->estadoAbierto;
    }

    /**
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuSeleccionGrupo
     */
    public function setCodigoCentroCostoFk($codigoCentroCostoFk)
    {
        $this->codigoCentroCostoFk = $codigoCentroCostoFk;

        return $this;
    }

    /**
     * Get codigoCentroCostoFk
     *
     * @return integer
     */
    public function getCodigoCentroCostoFk()
    {
        return $this->codigoCentroCostoFk;
    }

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel
     *
     * @return RhuSeleccionGrupo
     */
    public function setCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel = null)
    {
        $this->centroCostoRel = $centroCostoRel;

        return $this;
    }

    /**
     * Get centroCostoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto
     */
    public function getCentroCostoRel()
    {
        return $this->centroCostoRel;
    }

    /**
     * Add seleccionesSeleccionGrupoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesSeleccionGrupoRel
     *
     * @return RhuSeleccionGrupo
     */
    public function addSeleccionesSeleccionGrupoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesSeleccionGrupoRel)
    {
        $this->seleccionesSeleccionGrupoRel[] = $seleccionesSeleccionGrupoRel;

        return $this;
    }

    /**
     * Remove seleccionesSeleccionGrupoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesSeleccionGrupoRel
     */
    public function removeSeleccionesSeleccionGrupoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesSeleccionGrupoRel)
    {
        $this->seleccionesSeleccionGrupoRel->removeElement($seleccionesSeleccionGrupoRel);
    }

    /**
     * Get seleccionesSeleccionGrupoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesSeleccionGrupoRel()
    {
        return $this->seleccionesSeleccionGrupoRel;
    }
}
