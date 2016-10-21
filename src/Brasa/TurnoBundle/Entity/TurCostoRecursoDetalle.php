<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_costo_recurso_detalle")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurCostoRecursoDetalleRepository")
 */
class TurCostoRecursoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_costo_recurso_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCostoRecursoDetallePk;             
    
    /**
     * @ORM\Column(name="codigo_cierre_mes_fk", type="integer")
     */    
    private $codigoCierreMesFk;     
    
    /**
     * @ORM\Column(name="anio", type="integer", nullable=true)
     */    
    private $anio;    
    
    /**
     * @ORM\Column(name="mes", type="integer", nullable=true)
     */    
    private $mes;                     
    
    /**
     * @ORM\Column(name="codigo_recurso_fk", type="integer", nullable=true)
     */    
    private $codigoRecursoFk;    
    
    /**
     * @ORM\Column(name="codigo_programacion_fk", type="integer", nullable=true)
     */    
    private $codigoProgramacionFk;    
    
    /**
     * @ORM\Column(name="codigo_puesto_fk", type="integer", nullable=true)
     */    
    private $codigoPuestoFk;     
    
    /**
     * @ORM\Column(name="vr_nomina", type="float")
     */
    private $vrNomina = 0;    
    
    /**
     * @ORM\Column(name="vr_prestaciones", type="float")
     */
    private $vrPrestaciones = 0;    
    
    /**
     * @ORM\Column(name="vr_aportes_sociales", type="float")
     */
    private $vrAportesSociales = 0;    

    /**
     * @ORM\Column(name="vr_costo_total", type="float")
     */
    private $vrCostoTotal = 0;
    
    /**
     * @ORM\Column(name="horas", type="integer")
     */    
    private $horas = 0;    
    
    /**
     * @ORM\Column(name="vr_hora", type="float")
     */
    private $vrHora = 0;    
    

    /**
     * Get codigoCostoRecursoDetallePk
     *
     * @return integer
     */
    public function getCodigoCostoRecursoDetallePk()
    {
        return $this->codigoCostoRecursoDetallePk;
    }

    /**
     * Set codigoCierreMesFk
     *
     * @param integer $codigoCierreMesFk
     *
     * @return TurCostoRecursoDetalle
     */
    public function setCodigoCierreMesFk($codigoCierreMesFk)
    {
        $this->codigoCierreMesFk = $codigoCierreMesFk;

        return $this;
    }

    /**
     * Get codigoCierreMesFk
     *
     * @return integer
     */
    public function getCodigoCierreMesFk()
    {
        return $this->codigoCierreMesFk;
    }

    /**
     * Set anio
     *
     * @param integer $anio
     *
     * @return TurCostoRecursoDetalle
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
     * @return TurCostoRecursoDetalle
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
     * Set codigoRecursoFk
     *
     * @param integer $codigoRecursoFk
     *
     * @return TurCostoRecursoDetalle
     */
    public function setCodigoRecursoFk($codigoRecursoFk)
    {
        $this->codigoRecursoFk = $codigoRecursoFk;

        return $this;
    }

    /**
     * Get codigoRecursoFk
     *
     * @return integer
     */
    public function getCodigoRecursoFk()
    {
        return $this->codigoRecursoFk;
    }

    /**
     * Set codigoProgramacionFk
     *
     * @param integer $codigoProgramacionFk
     *
     * @return TurCostoRecursoDetalle
     */
    public function setCodigoProgramacionFk($codigoProgramacionFk)
    {
        $this->codigoProgramacionFk = $codigoProgramacionFk;

        return $this;
    }

    /**
     * Get codigoProgramacionFk
     *
     * @return integer
     */
    public function getCodigoProgramacionFk()
    {
        return $this->codigoProgramacionFk;
    }

    /**
     * Set codigoPuestoFk
     *
     * @param integer $codigoPuestoFk
     *
     * @return TurCostoRecursoDetalle
     */
    public function setCodigoPuestoFk($codigoPuestoFk)
    {
        $this->codigoPuestoFk = $codigoPuestoFk;

        return $this;
    }

    /**
     * Get codigoPuestoFk
     *
     * @return integer
     */
    public function getCodigoPuestoFk()
    {
        return $this->codigoPuestoFk;
    }

    /**
     * Set vrNomina
     *
     * @param float $vrNomina
     *
     * @return TurCostoRecursoDetalle
     */
    public function setVrNomina($vrNomina)
    {
        $this->vrNomina = $vrNomina;

        return $this;
    }

    /**
     * Get vrNomina
     *
     * @return float
     */
    public function getVrNomina()
    {
        return $this->vrNomina;
    }

    /**
     * Set vrPrestaciones
     *
     * @param float $vrPrestaciones
     *
     * @return TurCostoRecursoDetalle
     */
    public function setVrPrestaciones($vrPrestaciones)
    {
        $this->vrPrestaciones = $vrPrestaciones;

        return $this;
    }

    /**
     * Get vrPrestaciones
     *
     * @return float
     */
    public function getVrPrestaciones()
    {
        return $this->vrPrestaciones;
    }

    /**
     * Set vrAportesSociales
     *
     * @param float $vrAportesSociales
     *
     * @return TurCostoRecursoDetalle
     */
    public function setVrAportesSociales($vrAportesSociales)
    {
        $this->vrAportesSociales = $vrAportesSociales;

        return $this;
    }

    /**
     * Get vrAportesSociales
     *
     * @return float
     */
    public function getVrAportesSociales()
    {
        return $this->vrAportesSociales;
    }

    /**
     * Set vrCostoTotal
     *
     * @param float $vrCostoTotal
     *
     * @return TurCostoRecursoDetalle
     */
    public function setVrCostoTotal($vrCostoTotal)
    {
        $this->vrCostoTotal = $vrCostoTotal;

        return $this;
    }

    /**
     * Get vrCostoTotal
     *
     * @return float
     */
    public function getVrCostoTotal()
    {
        return $this->vrCostoTotal;
    }

    /**
     * Set horas
     *
     * @param integer $horas
     *
     * @return TurCostoRecursoDetalle
     */
    public function setHoras($horas)
    {
        $this->horas = $horas;

        return $this;
    }

    /**
     * Get horas
     *
     * @return integer
     */
    public function getHoras()
    {
        return $this->horas;
    }

    /**
     * Set vrHora
     *
     * @param float $vrHora
     *
     * @return TurCostoRecursoDetalle
     */
    public function setVrHora($vrHora)
    {
        $this->vrHora = $vrHora;

        return $this;
    }

    /**
     * Get vrHora
     *
     * @return float
     */
    public function getVrHora()
    {
        return $this->vrHora;
    }
}
