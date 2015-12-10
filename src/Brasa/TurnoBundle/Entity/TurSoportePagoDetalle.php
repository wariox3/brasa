<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_soporte_pago_detalle")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurSoportePagoDetalleRepository")
 */
class TurSoportePagoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_soporte_pago_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSoportePagoDetallePk;         
    
    /**
     * @ORM\Column(name="codigo_soporte_pago_fk", type="integer", nullable=true)
     */    
    private $codigoSoportePagoFk;    
    
    /**
     * @ORM\Column(name="codigo_recurso_fk", type="integer", nullable=true)
     */    
    private $codigoRecursoFk;
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;        
    
    /**     
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = false;    
    
    /**
     * @ORM\Column(name="horas", type="integer")
     */    
    private $horas = 0;    
    
    /**
     * @ORM\Column(name="horas_diurnas", type="integer")
     */    
    private $horasDiurnas = 0;     
    
    /**
     * @ORM\Column(name="horas_extras_ordinarias_diurnas", type="integer")
     */    
    private $horasExtrasOrdinariasDiurnas = 0;    

    /**
     * @ORM\Column(name="horas_extras_ordinarias_nocturnas", type="integer")
     */    
    private $horasExtrasOrdinariasNocturnas = 0;        

    /**
     * @ORM\Column(name="horas_extras_festivas_diurnas", type="integer")
     */    
    private $horasExtrasFestivasDiurnas = 0;    

    /**
     * @ORM\Column(name="horas_extras_festivas_nocturnas", type="integer")
     */    
    private $horasExtrasFestivasNocturnas = 0;    
    
    /**
     * @ORM\Column(name="codigo_turno_fk", type="string", length=5)
     */    
    private $codigoTurnoFk;    

    /**
     * @ORM\Column(name="codigo_programacion_detalle_fk", type="integer", nullable=true)
     */    
    private $codigoProgramacionDetalleFk;   
    
    /**
     * @ORM\ManyToOne(targetEntity="TurSoportePago", inversedBy="soportesPagosDetallesSoportePagoRel")
     * @ORM\JoinColumn(name="codigo_soporte_pago_fk", referencedColumnName="codigo_soporte_pago_pk")
     */
    protected $soportePagoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="TurRecurso", inversedBy="soportesPagosDetallesRecursoRel")
     * @ORM\JoinColumn(name="codigo_recurso_fk", referencedColumnName="codigo_recurso_pk")
     */
    protected $recursoRel;     

    /**
     * @ORM\ManyToOne(targetEntity="TurProgramacionDetalle", inversedBy="soportesPagosDetallesProgramacionDetalleRel")
     * @ORM\JoinColumn(name="codigo_programacion_detalle_fk", referencedColumnName="codigo_programacion_detalle_pk")
     */
    protected $programacionDetalleRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TurTurno", inversedBy="soportesPagosDetallesTurnoRel")
     * @ORM\JoinColumn(name="codigo_turno_fk", referencedColumnName="codigo_turno_pk")
     */
    protected $turnoRel;    
    



    /**
     * Get codigoSoportePagoDetallePk
     *
     * @return integer
     */
    public function getCodigoSoportePagoDetallePk()
    {
        return $this->codigoSoportePagoDetallePk;
    }

    /**
     * Set codigoRecursoFk
     *
     * @param integer $codigoRecursoFk
     *
     * @return TurSoportePagoDetalle
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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return TurSoportePagoDetalle
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
     * Set horas
     *
     * @param integer $horas
     *
     * @return TurSoportePagoDetalle
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
     * Set horasDiurnas
     *
     * @param integer $horasDiurnas
     *
     * @return TurSoportePagoDetalle
     */
    public function setHorasDiurnas($horasDiurnas)
    {
        $this->horasDiurnas = $horasDiurnas;

        return $this;
    }

    /**
     * Get horasDiurnas
     *
     * @return integer
     */
    public function getHorasDiurnas()
    {
        return $this->horasDiurnas;
    }

    /**
     * Set horasExtrasOrdinariasDiurnas
     *
     * @param integer $horasExtrasOrdinariasDiurnas
     *
     * @return TurSoportePagoDetalle
     */
    public function setHorasExtrasOrdinariasDiurnas($horasExtrasOrdinariasDiurnas)
    {
        $this->horasExtrasOrdinariasDiurnas = $horasExtrasOrdinariasDiurnas;

        return $this;
    }

    /**
     * Get horasExtrasOrdinariasDiurnas
     *
     * @return integer
     */
    public function getHorasExtrasOrdinariasDiurnas()
    {
        return $this->horasExtrasOrdinariasDiurnas;
    }

    /**
     * Set horasExtrasOrdinariasNocturnas
     *
     * @param integer $horasExtrasOrdinariasNocturnas
     *
     * @return TurSoportePagoDetalle
     */
    public function setHorasExtrasOrdinariasNocturnas($horasExtrasOrdinariasNocturnas)
    {
        $this->horasExtrasOrdinariasNocturnas = $horasExtrasOrdinariasNocturnas;

        return $this;
    }

    /**
     * Get horasExtrasOrdinariasNocturnas
     *
     * @return integer
     */
    public function getHorasExtrasOrdinariasNocturnas()
    {
        return $this->horasExtrasOrdinariasNocturnas;
    }

    /**
     * Set horasExtrasFestivasDiurnas
     *
     * @param integer $horasExtrasFestivasDiurnas
     *
     * @return TurSoportePagoDetalle
     */
    public function setHorasExtrasFestivasDiurnas($horasExtrasFestivasDiurnas)
    {
        $this->horasExtrasFestivasDiurnas = $horasExtrasFestivasDiurnas;

        return $this;
    }

    /**
     * Get horasExtrasFestivasDiurnas
     *
     * @return integer
     */
    public function getHorasExtrasFestivasDiurnas()
    {
        return $this->horasExtrasFestivasDiurnas;
    }

    /**
     * Set horasExtrasFestivasNocturnas
     *
     * @param integer $horasExtrasFestivasNocturnas
     *
     * @return TurSoportePagoDetalle
     */
    public function setHorasExtrasFestivasNocturnas($horasExtrasFestivasNocturnas)
    {
        $this->horasExtrasFestivasNocturnas = $horasExtrasFestivasNocturnas;

        return $this;
    }

    /**
     * Get horasExtrasFestivasNocturnas
     *
     * @return integer
     */
    public function getHorasExtrasFestivasNocturnas()
    {
        return $this->horasExtrasFestivasNocturnas;
    }

    /**
     * Set codigoTurnoFk
     *
     * @param string $codigoTurnoFk
     *
     * @return TurSoportePagoDetalle
     */
    public function setCodigoTurnoFk($codigoTurnoFk)
    {
        $this->codigoTurnoFk = $codigoTurnoFk;

        return $this;
    }

    /**
     * Get codigoTurnoFk
     *
     * @return string
     */
    public function getCodigoTurnoFk()
    {
        return $this->codigoTurnoFk;
    }

    /**
     * Set codigoProgramacionDetalleFk
     *
     * @param integer $codigoProgramacionDetalleFk
     *
     * @return TurSoportePagoDetalle
     */
    public function setCodigoProgramacionDetalleFk($codigoProgramacionDetalleFk)
    {
        $this->codigoProgramacionDetalleFk = $codigoProgramacionDetalleFk;

        return $this;
    }

    /**
     * Get codigoProgramacionDetalleFk
     *
     * @return integer
     */
    public function getCodigoProgramacionDetalleFk()
    {
        return $this->codigoProgramacionDetalleFk;
    }

    /**
     * Set recursoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurRecurso $recursoRel
     *
     * @return TurSoportePagoDetalle
     */
    public function setRecursoRel(\Brasa\TurnoBundle\Entity\TurRecurso $recursoRel = null)
    {
        $this->recursoRel = $recursoRel;

        return $this;
    }

    /**
     * Get recursoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurRecurso
     */
    public function getRecursoRel()
    {
        return $this->recursoRel;
    }

    /**
     * Set programacionDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionDetalleRel
     *
     * @return TurSoportePagoDetalle
     */
    public function setProgramacionDetalleRel(\Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionDetalleRel = null)
    {
        $this->programacionDetalleRel = $programacionDetalleRel;

        return $this;
    }

    /**
     * Get programacionDetalleRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurProgramacionDetalle
     */
    public function getProgramacionDetalleRel()
    {
        return $this->programacionDetalleRel;
    }

    /**
     * Set turnoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurTurno $turnoRel
     *
     * @return TurSoportePagoDetalle
     */
    public function setTurnoRel(\Brasa\TurnoBundle\Entity\TurTurno $turnoRel = null)
    {
        $this->turnoRel = $turnoRel;

        return $this;
    }

    /**
     * Get turnoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurTurno
     */
    public function getTurnoRel()
    {
        return $this->turnoRel;
    }

    /**
     * Set codigoSoportePagoFk
     *
     * @param integer $codigoSoportePagoFk
     *
     * @return TurSoportePagoDetalle
     */
    public function setCodigoSoportePagoFk($codigoSoportePagoFk)
    {
        $this->codigoSoportePagoFk = $codigoSoportePagoFk;

        return $this;
    }

    /**
     * Get codigoSoportePagoFk
     *
     * @return integer
     */
    public function getCodigoSoportePagoFk()
    {
        return $this->codigoSoportePagoFk;
    }

    /**
     * Set soportePagoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePago $soportePagoRel
     *
     * @return TurSoportePagoDetalle
     */
    public function setSoportePagoRel(\Brasa\TurnoBundle\Entity\TurSoportePago $soportePagoRel = null)
    {
        $this->soportePagoRel = $soportePagoRel;

        return $this;
    }

    /**
     * Get soportePagoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurSoportePago
     */
    public function getSoportePagoRel()
    {
        return $this->soportePagoRel;
    }

    /**
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return TurSoportePagoDetalle
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
}
