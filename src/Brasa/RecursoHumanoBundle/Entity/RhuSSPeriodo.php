<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_ss_periodo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSSPeriodoRepository")
 */
class RhuSSPeriodo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_periodo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPeriodoPk;   
    
    /**
     * @ORM\Column(name="anio", type="integer")
     */
    private $anio = 0;
    
    /**
     * @ORM\Column(name="mes", type="integer")
     */
    private $mes = 0; 
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;    
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta;     
    
    /**     
     * @ORM\Column(name="estado_generado", type="boolean")
     */    
    private $estadoGenerado = 0;     

    /**
     * @ORM\OneToMany(targetEntity="RhuSSPeriodoDetalle", mappedBy="ssPeriodoRel")
     */
    protected $SSPeriodosDetallesSSPeriodoRel; 

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->SSPeriodosDetallesSSPeriodoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPeriodoPk
     *
     * @return integer
     */
    public function getCodigoPeriodoPk()
    {
        return $this->codigoPeriodoPk;
    }

    /**
     * Set anio
     *
     * @param integer $anio
     *
     * @return RhuSSPeriodo
     */
    public function setAnio($anio)
    {
        $this->anio = $anio;

        return $this;
    }

    /**
     * Get anio
     *
     * @return integer
     */
    public function getAnio()
    {
        return $this->anio;
    }

    /**
     * Set mes
     *
     * @param integer $mes
     *
     * @return RhuSSPeriodo
     */
    public function setMes($mes)
    {
        $this->mes = $mes;

        return $this;
    }

    /**
     * Get mes
     *
     * @return integer
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return RhuSSPeriodo
     */
    public function setFechaDesde($fechaDesde)
    {
        $this->fechaDesde = $fechaDesde;

        return $this;
    }

    /**
     * Get fechaDesde
     *
     * @return \DateTime
     */
    public function getFechaDesde()
    {
        return $this->fechaDesde;
    }

    /**
     * Set fechaHasta
     *
     * @param \DateTime $fechaHasta
     *
     * @return RhuSSPeriodo
     */
    public function setFechaHasta($fechaHasta)
    {
        $this->fechaHasta = $fechaHasta;

        return $this;
    }

    /**
     * Get fechaHasta
     *
     * @return \DateTime
     */
    public function getFechaHasta()
    {
        return $this->fechaHasta;
    }

    /**
     * Set estadoGenerado
     *
     * @param boolean $estadoGenerado
     *
     * @return RhuSSPeriodo
     */
    public function setEstadoGenerado($estadoGenerado)
    {
        $this->estadoGenerado = $estadoGenerado;

        return $this;
    }

    /**
     * Get estadoGenerado
     *
     * @return boolean
     */
    public function getEstadoGenerado()
    {
        return $this->estadoGenerado;
    }

    /**
     * Add sSPeriodosDetallesSSPeriodoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSSPeriodoDetalle $sSPeriodosDetallesSSPeriodoRel
     *
     * @return RhuSSPeriodo
     */
    public function addSSPeriodosDetallesSSPeriodoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSSPeriodoDetalle $sSPeriodosDetallesSSPeriodoRel)
    {
        $this->SSPeriodosDetallesSSPeriodoRel[] = $sSPeriodosDetallesSSPeriodoRel;

        return $this;
    }

    /**
     * Remove sSPeriodosDetallesSSPeriodoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSSPeriodoDetalle $sSPeriodosDetallesSSPeriodoRel
     */
    public function removeSSPeriodosDetallesSSPeriodoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSSPeriodoDetalle $sSPeriodosDetallesSSPeriodoRel)
    {
        $this->SSPeriodosDetallesSSPeriodoRel->removeElement($sSPeriodosDetallesSSPeriodoRel);
    }

    /**
     * Get sSPeriodosDetallesSSPeriodoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSSPeriodosDetallesSSPeriodoRel()
    {
        return $this->SSPeriodosDetallesSSPeriodoRel;
    }
}
