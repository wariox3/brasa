<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_planes_recogidas")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TtePlanesRecogidasRepository")
 */
class TtePlanesRecogidas
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_plan_recogida_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPlanRecogidaPk;                 
    
    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */    
    private $fecha;           
    
    /**
     * @ORM\Column(name="codigo_punto_operacion_fk", type="integer", nullable=true)
     */    
    private $codigoPuntoOperacionFk;            
    
    /**
     * @ORM\Column(name="codigo_conductor_fk", type="integer", nullable=true)
     */    
    private $codigoConductorFk;    
    
    /**
     * @ORM\Column(name="codigo_vehiculo_fk", type="integer", nullable=true)
     */    
    private $codigoVehiculoFk;     
    
    /**
     * @ORM\Column(name="vr_flete_pagado", type="float")
     */
    private $vrFletePagado = 0;
       
    /**
     * @ORM\Column(name="ct_peso_real", type="integer")
     */
    private $ctPesoReal = 0;    

    /**
     * @ORM\Column(name="ct_peso_volumen", type="integer")
     */
    private $ctPesoVolumen = 0;        

    /**
     * @ORM\Column(name="ct_unidades", type="integer")
     */
    private $ctUnidades = 0;    
    
    /**
     * @ORM\Column(name="ct_recogidas", type="integer")
     */
    private $ctRecogidas = 0;    
         
    /**
     * @ORM\Column(name="comentarios", type="string", length=500, nullable=true)
     */    
    private $comentarios;                             
    
    /**
     * @ORM\Column(name="estado_descargado", type="boolean")
     */    
    private $estadoDescargado = 0;    
    
    /**
     * @ORM\OneToMany(targetEntity="TteRecogidas", mappedBy="planRecogidaRel")
     */
    protected $recogidasRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TteConductores", inversedBy="planesRecogidasRel")
     * @ORM\JoinColumn(name="codigo_conductor_fk", referencedColumnName="codigo_conductor_pk")
     */
    protected $conductorRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TteVehiculos", inversedBy="planesRecogidasRel")
     * @ORM\JoinColumn(name="codigo_vehiculo_fk", referencedColumnName="codigo_vehiculo_pk")
     */
    protected $vehiculoRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TtePuntosOperacion", inversedBy="planesRecogidasRel")
     * @ORM\JoinColumn(name="codigo_punto_operacion_fk", referencedColumnName="codigo_punto_operacion_pk")
     */
    protected $puntoOperacionRel;    
    



    /**
     * Get codigoPlanRecogidaPk
     *
     * @return integer 
     */
    public function getCodigoPlanRecogidaPk()
    {
        return $this->codigoPlanRecogidaPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return TtePlanesRecogidas
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
     * Set codigoPuntoOperacionFk
     *
     * @param integer $codigoPuntoOperacionFk
     * @return TtePlanesRecogidas
     */
    public function setCodigoPuntoOperacionFk($codigoPuntoOperacionFk)
    {
        $this->codigoPuntoOperacionFk = $codigoPuntoOperacionFk;

        return $this;
    }

    /**
     * Get codigoPuntoOperacionFk
     *
     * @return integer 
     */
    public function getCodigoPuntoOperacionFk()
    {
        return $this->codigoPuntoOperacionFk;
    }

    /**
     * Set codigoConductorFk
     *
     * @param integer $codigoConductorFk
     * @return TtePlanesRecogidas
     */
    public function setCodigoConductorFk($codigoConductorFk)
    {
        $this->codigoConductorFk = $codigoConductorFk;

        return $this;
    }

    /**
     * Get codigoConductorFk
     *
     * @return integer 
     */
    public function getCodigoConductorFk()
    {
        return $this->codigoConductorFk;
    }

    /**
     * Set codigoVehiculoFk
     *
     * @param integer $codigoVehiculoFk
     * @return TtePlanesRecogidas
     */
    public function setCodigoVehiculoFk($codigoVehiculoFk)
    {
        $this->codigoVehiculoFk = $codigoVehiculoFk;

        return $this;
    }

    /**
     * Get codigoVehiculoFk
     *
     * @return integer 
     */
    public function getCodigoVehiculoFk()
    {
        return $this->codigoVehiculoFk;
    }

    /**
     * Set vrFletePagado
     *
     * @param float $vrFletePagado
     * @return TtePlanesRecogidas
     */
    public function setVrFletePagado($vrFletePagado)
    {
        $this->vrFletePagado = $vrFletePagado;

        return $this;
    }

    /**
     * Get vrFletePagado
     *
     * @return float 
     */
    public function getVrFletePagado()
    {
        return $this->vrFletePagado;
    }

    /**
     * Set ctPesoReal
     *
     * @param integer $ctPesoReal
     * @return TtePlanesRecogidas
     */
    public function setCtPesoReal($ctPesoReal)
    {
        $this->ctPesoReal = $ctPesoReal;

        return $this;
    }

    /**
     * Get ctPesoReal
     *
     * @return integer 
     */
    public function getCtPesoReal()
    {
        return $this->ctPesoReal;
    }

    /**
     * Set ctPesoVolumen
     *
     * @param integer $ctPesoVolumen
     * @return TtePlanesRecogidas
     */
    public function setCtPesoVolumen($ctPesoVolumen)
    {
        $this->ctPesoVolumen = $ctPesoVolumen;

        return $this;
    }

    /**
     * Get ctPesoVolumen
     *
     * @return integer 
     */
    public function getCtPesoVolumen()
    {
        return $this->ctPesoVolumen;
    }

    /**
     * Set ctUnidades
     *
     * @param integer $ctUnidades
     * @return TtePlanesRecogidas
     */
    public function setCtUnidades($ctUnidades)
    {
        $this->ctUnidades = $ctUnidades;

        return $this;
    }

    /**
     * Get ctUnidades
     *
     * @return integer 
     */
    public function getCtUnidades()
    {
        return $this->ctUnidades;
    }

    /**
     * Set ctRecogidas
     *
     * @param integer $ctRecogidas
     * @return TtePlanesRecogidas
     */
    public function setCtRecogidas($ctRecogidas)
    {
        $this->ctRecogidas = $ctRecogidas;

        return $this;
    }

    /**
     * Get ctRecogidas
     *
     * @return integer 
     */
    public function getCtRecogidas()
    {
        return $this->ctRecogidas;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     * @return TtePlanesRecogidas
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
     * Set conductorRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteConductores $conductorRel
     * @return TtePlanesRecogidas
     */
    public function setConductorRel(\Brasa\TransporteBundle\Entity\TteConductores $conductorRel = null)
    {
        $this->conductorRel = $conductorRel;

        return $this;
    }

    /**
     * Get conductorRel
     *
     * @return \Brasa\TransporteBundle\Entity\TteConductores 
     */
    public function getConductorRel()
    {
        return $this->conductorRel;
    }

    /**
     * Set vehiculoRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteVehiculos $vehiculoRel
     * @return TtePlanesRecogidas
     */
    public function setVehiculoRel(\Brasa\TransporteBundle\Entity\TteVehiculos $vehiculoRel = null)
    {
        $this->vehiculoRel = $vehiculoRel;

        return $this;
    }

    /**
     * Get vehiculoRel
     *
     * @return \Brasa\TransporteBundle\Entity\TteVehiculos 
     */
    public function getVehiculoRel()
    {
        return $this->vehiculoRel;
    }

    /**
     * Set puntoOperacionRel
     *
     * @param \Brasa\TransporteBundle\Entity\TtePuntosOperacion $puntoOperacionRel
     * @return TtePlanesRecogidas
     */
    public function setPuntoOperacionRel(\Brasa\TransporteBundle\Entity\TtePuntosOperacion $puntoOperacionRel = null)
    {
        $this->puntoOperacionRel = $puntoOperacionRel;

        return $this;
    }

    /**
     * Get puntoOperacionRel
     *
     * @return \Brasa\TransporteBundle\Entity\TtePuntosOperacion 
     */
    public function getPuntoOperacionRel()
    {
        return $this->puntoOperacionRel;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->recogidasRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add recogidasRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteRecogidas $recogidasRel
     * @return TtePlanesRecogidas
     */
    public function addRecogidasRel(\Brasa\TransporteBundle\Entity\TteRecogidas $recogidasRel)
    {
        $this->recogidasRel[] = $recogidasRel;

        return $this;
    }

    /**
     * Remove recogidasRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteRecogidas $recogidasRel
     */
    public function removeRecogidasRel(\Brasa\TransporteBundle\Entity\TteRecogidas $recogidasRel)
    {
        $this->recogidasRel->removeElement($recogidasRel);
    }

    /**
     * Get recogidasRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRecogidasRel()
    {
        return $this->recogidasRel;
    }

    /**
     * Set estadoDescargado
     *
     * @param boolean $estadoDescargado
     * @return TtePlanesRecogidas
     */
    public function setEstadoDescargado($estadoDescargado)
    {
        $this->estadoDescargado = $estadoDescargado;

        return $this;
    }

    /**
     * Get estadoDescargado
     *
     * @return boolean 
     */
    public function getEstadoDescargado()
    {
        return $this->estadoDescargado;
    }
}
