<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_plan_recogida")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TtePlanRecogidaRepository")
 */
class TtePlanRecogida
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
     *
     * @return TtePlanRecogida
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
     *
     * @return TtePlanRecogida
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
     *
     * @return TtePlanRecogida
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
     *
     * @return TtePlanRecogida
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
     *
     * @return TtePlanRecogida
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
     *
     * @return TtePlanRecogida
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
     *
     * @return TtePlanRecogida
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
     *
     * @return TtePlanRecogida
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
     *
     * @return TtePlanRecogida
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
     *
     * @return TtePlanRecogida
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
     * Set estadoDescargado
     *
     * @param boolean $estadoDescargado
     *
     * @return TtePlanRecogida
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
