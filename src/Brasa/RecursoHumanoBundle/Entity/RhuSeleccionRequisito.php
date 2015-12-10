<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_seleccion_requisito")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSeleccionRequisitoRepository")
 */
class RhuSeleccionRequisito
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_requisito_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSeleccionRequisitoPk;        
    
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
     * @ORM\Column(name="fecha_pruebas", type="datetime", nullable=true)
     */ 
    
    private $fechaPruebas;         
    
    /**
     * @ORM\Column(name="estado_abierto", type="boolean")
     */
    private $estadoAbierto = 0;            
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk;
    
    /**
     * @ORM\Column(name="codigo_cargo_fk", type="integer", nullable=true)
     */    
    private $codigoCargoFk;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="seleccionesRequisitosCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCargo", inversedBy="seleccionesRequisitosCargoRel")
     * @ORM\JoinColumn(name="codigo_cargo_fk", referencedColumnName="codigo_cargo_pk")
     */
    protected $cargoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccion", mappedBy="seleccionRequisitoRel")
     */
    protected $seleccionesSeleccionRequisitoRel;
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->seleccionesSeleccionRequisitoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoSeleccionRequisitoPk
     *
     * @return integer
     */
    public function getCodigoSeleccionRequisitoPk()
    {
        return $this->codigoSeleccionRequisitoPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuSeleccionRequisito
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
     * @return RhuSeleccionRequisito
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
     * @return RhuSeleccionRequisito
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
     * @return RhuSeleccionRequisito
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
     * @param boolean $estadoAbierto
     *
     * @return RhuSeleccionRequisito
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
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuSeleccionRequisito
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
     * Set codigoCargoFk
     *
     * @param integer $codigoCargoFk
     *
     * @return RhuSeleccionRequisito
     */
    public function setCodigoCargoFk($codigoCargoFk)
    {
        $this->codigoCargoFk = $codigoCargoFk;

        return $this;
    }

    /**
     * Get codigoCargoFk
     *
     * @return integer
     */
    public function getCodigoCargoFk()
    {
        return $this->codigoCargoFk;
    }

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel
     *
     * @return RhuSeleccionRequisito
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
     * Set cargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCargo $cargoRel
     *
     * @return RhuSeleccionRequisito
     */
    public function setCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCargo $cargoRel = null)
    {
        $this->cargoRel = $cargoRel;

        return $this;
    }

    /**
     * Get cargoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCargo
     */
    public function getCargoRel()
    {
        return $this->cargoRel;
    }

    /**
     * Add seleccionesSeleccionRequisitoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesSeleccionRequisitoRel
     *
     * @return RhuSeleccionRequisito
     */
    public function addSeleccionesSeleccionRequisitoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesSeleccionRequisitoRel)
    {
        $this->seleccionesSeleccionRequisitoRel[] = $seleccionesSeleccionRequisitoRel;

        return $this;
    }

    /**
     * Remove seleccionesSeleccionRequisitoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesSeleccionRequisitoRel
     */
    public function removeSeleccionesSeleccionRequisitoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionesSeleccionRequisitoRel)
    {
        $this->seleccionesSeleccionRequisitoRel->removeElement($seleccionesSeleccionRequisitoRel);
    }

    /**
     * Get seleccionesSeleccionRequisitoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSeleccionesSeleccionRequisitoRel()
    {
        return $this->seleccionesSeleccionRequisitoRel;
    }
}
