<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_cierre_mes")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurCierreMesRepository")
 */
class TurCierreMes
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cierre_mes_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCierreMesPk;             
    
    /**
     * @ORM\Column(name="anio", type="integer", nullable=true)
     */    
    private $anio;    
    
    /**
     * @ORM\Column(name="mes", type="integer", nullable=true)
     */    
    private $mes;               
    
    /**     
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = false;    

    /**
     * @ORM\OneToMany(targetEntity="TurCierreMesServicio", mappedBy="cierreMesRel", cascade={"persist", "remove"})
     */
    protected $cierresMesServiciosCierreMesRel;     

    /**
     * Get codigoCierreMesPk
     *
     * @return integer
     */
    public function getCodigoCierreMesPk()
    {
        return $this->codigoCierreMesPk;
    }

    /**
     * Set anio
     *
     * @param integer $anio
     *
     * @return TurCierreMes
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
     * @return TurCierreMes
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
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return TurCierreMes
     */
    public function setEstadoCerrado($estadoCerrado)
    {
        $this->estadoCerrado = $estadoCerrado;

        return $this;
    }

    /**
     * Get estadoCerrado
     *
     * @return boolean
     */
    public function getEstadoCerrado()
    {
        return $this->estadoCerrado;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cierresMesServiciosCierreMesRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add cierresMesServiciosCierreMesRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCierreMesServicio $cierresMesServiciosCierreMesRel
     *
     * @return TurCierreMes
     */
    public function addCierresMesServiciosCierreMesRel(\Brasa\TurnoBundle\Entity\TurCierreMesServicio $cierresMesServiciosCierreMesRel)
    {
        $this->cierresMesServiciosCierreMesRel[] = $cierresMesServiciosCierreMesRel;

        return $this;
    }

    /**
     * Remove cierresMesServiciosCierreMesRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCierreMesServicio $cierresMesServiciosCierreMesRel
     */
    public function removeCierresMesServiciosCierreMesRel(\Brasa\TurnoBundle\Entity\TurCierreMesServicio $cierresMesServiciosCierreMesRel)
    {
        $this->cierresMesServiciosCierreMesRel->removeElement($cierresMesServiciosCierreMesRel);
    }

    /**
     * Get cierresMesServiciosCierreMesRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCierresMesServiciosCierreMesRel()
    {
        return $this->cierresMesServiciosCierreMesRel;
    }
}
