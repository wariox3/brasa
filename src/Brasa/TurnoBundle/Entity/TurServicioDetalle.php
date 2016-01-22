<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_servicio_detalle")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurServicioDetalleRepository")
 */
class TurServicioDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_servicio_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoServicioDetallePk;  
    
    /**
     * @ORM\Column(name="codigo_servicio_fk", type="integer")
     */    
    private $codigoServicioFk;

    /**
     * @ORM\Column(name="codigo_puesto_fk", type="integer", nullable=true)
     */    
    private $codigoPuestoFk;            
    
    /**
     * @ORM\Column(name="codigo_concepto_servicio_fk", type="integer")
     */    
    private $codigoConceptoServicioFk;    
    
    /**
     * @ORM\Column(name="codigo_modalidad_servicio_fk", type="integer")
     */    
    private $codigoModalidadServicioFk;    
    
    /**
     * @ORM\Column(name="codigo_periodo_fk", type="integer")
     */    
    private $codigoPeriodoFk;     
    
    /**
     * @ORM\Column(name="codigo_plantilla_fk", type="integer", nullable=true)
     */    
    private $codigoPlantillaFk;    
    
    /**
     * @ORM\Column(name="dia_desde", type="integer")
     */    
    private $diaDesde = 1;     

    /**
     * @ORM\Column(name="dia_hasta", type="integer")
     */    
    private $diaHasta = 1;         
    
    /**
     * @ORM\Column(name="dias", type="integer")
     */    
    private $dias = 0; 
    
    /**
     * @ORM\Column(name="horas", type="integer")
     */    
    private $horas = 0;    

    /**
     * @ORM\Column(name="horas_diurnas", type="integer")
     */    
    private $horasDiurnas = 0;     
    
    /**
     * @ORM\Column(name="horas_nocturnas", type="integer")
     */    
    private $horasNocturnas = 0;     
    
    /**
     * @ORM\Column(name="cantidad", type="integer")
     */    
    private $cantidad = 0;     
    
    /**
     * @ORM\Column(name="cantidad_recurso", type="integer")
     */    
    private $cantidadRecurso = 0;         
    
    /**
     * @ORM\Column(name="vr_costo", type="float")
     */
    private $vrCosto = 0;
    
    /**
     * @ORM\Column(name="vr_precio_ajustado", type="float")
     */
    private $vrPrecioAjustado = 0;            

    /**
     * @ORM\Column(name="vr_precio_minimo", type="float")
     */
    private $vrPrecioMinimo = 0;        
    
    /**
     * @ORM\Column(name="vr_total_detalle", type="float")
     */
    private $vrTotalDetalle = 0;
    
    /**     
     * @ORM\Column(name="lunes", type="boolean")
     */    
    private $lunes = false;    
    
    /**     
     * @ORM\Column(name="martes", type="boolean")
     */    
    private $martes = false;        
    
    /**     
     * @ORM\Column(name="miercoles", type="boolean")
     */    
    private $miercoles = false;        
    
    /**     
     * @ORM\Column(name="jueves", type="boolean")
     */    
    private $jueves = false;        
    
    /**     
     * @ORM\Column(name="viernes", type="boolean")
     */    
    private $viernes = false;    
    
    /**     
     * @ORM\Column(name="sabado", type="boolean")
     */    
    private $sabado = false;        
    
    /**     
     * @ORM\Column(name="domingo", type="boolean")
     */    
    private $domingo = false;        
    
    /**     
     * @ORM\Column(name="festivo", type="boolean")
     */    
    private $festivo = false;        
    
    /**     
     * @ORM\Column(name="dia_31", type="boolean")
     */    
    private $dia31 = false;    
    
    /**
     * @ORM\Column(name="dias_secuencia", type="integer")
     */    
    private $diasSecuencia = 0;    
    
    /**
     * @ORM\Column(name="fecha_inicia_plantilla", type="date", nullable=true)
     */    
    private $fechaIniciaPlantilla;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TurServicio", inversedBy="serviciosDetallesServicioRel")
     * @ORM\JoinColumn(name="codigo_servicio_fk", referencedColumnName="codigo_servicio_pk")
     */
    protected $servicioRel;       

    /**
     * @ORM\ManyToOne(targetEntity="TurPuesto", inversedBy="serviciosDetallesPuestoRel")
     * @ORM\JoinColumn(name="codigo_puesto_fk", referencedColumnName="codigo_puesto_pk")
     */
    protected $puestoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurConceptoServicio", inversedBy="serviciosDetallesConceptoServicioRel")
     * @ORM\JoinColumn(name="codigo_concepto_servicio_fk", referencedColumnName="codigo_concepto_servicio_pk")
     */
    protected $conceptoServicioRel;      

    /**
     * @ORM\ManyToOne(targetEntity="TurModalidadServicio", inversedBy="serviciosDetallesModalidadServicioRel")
     * @ORM\JoinColumn(name="codigo_modalidad_servicio_fk", referencedColumnName="codigo_modalidad_servicio_pk")
     */
    protected $modalidadServicioRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurPeriodo", inversedBy="serviciosDetallesPeriodoRel")
     * @ORM\JoinColumn(name="codigo_periodo_fk", referencedColumnName="codigo_periodo_pk")
     */
    protected $periodoRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TurPlantilla", inversedBy="serviciosDetallesPlantillaRel")
     * @ORM\JoinColumn(name="codigo_plantilla_fk", referencedColumnName="codigo_plantilla_pk")
     */
    protected $plantillaRel;        

    /**
     * @ORM\OneToMany(targetEntity="TurServicioDetalleRecurso", mappedBy="servicioDetalleRel", cascade={"persist", "remove"})
     */
    protected $serviciosDetallesRecursosServicioDetalleRel;     

    /**
     * @ORM\OneToMany(targetEntity="TurServicioDetallePlantilla", mappedBy="servicioDetalleRel", cascade={"persist", "remove"})
     */
    protected $serviciosDetallesPlantillasServicioDetalleRel;    

    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalle", mappedBy="servicioDetalleRel")
     */
    protected $pedidosDetallesServicioDetalleRel;     
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->serviciosDetallesRecursosServicioDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->serviciosDetallesPlantillasServicioDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pedidosDetallesServicioDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoServicioDetallePk
     *
     * @return integer
     */
    public function getCodigoServicioDetallePk()
    {
        return $this->codigoServicioDetallePk;
    }

    /**
     * Set codigoServicioFk
     *
     * @param integer $codigoServicioFk
     *
     * @return TurServicioDetalle
     */
    public function setCodigoServicioFk($codigoServicioFk)
    {
        $this->codigoServicioFk = $codigoServicioFk;

        return $this;
    }

    /**
     * Get codigoServicioFk
     *
     * @return integer
     */
    public function getCodigoServicioFk()
    {
        return $this->codigoServicioFk;
    }

    /**
     * Set codigoPuestoFk
     *
     * @param integer $codigoPuestoFk
     *
     * @return TurServicioDetalle
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
     * Set codigoConceptoServicioFk
     *
     * @param integer $codigoConceptoServicioFk
     *
     * @return TurServicioDetalle
     */
    public function setCodigoConceptoServicioFk($codigoConceptoServicioFk)
    {
        $this->codigoConceptoServicioFk = $codigoConceptoServicioFk;

        return $this;
    }

    /**
     * Get codigoConceptoServicioFk
     *
     * @return integer
     */
    public function getCodigoConceptoServicioFk()
    {
        return $this->codigoConceptoServicioFk;
    }

    /**
     * Set codigoModalidadServicioFk
     *
     * @param integer $codigoModalidadServicioFk
     *
     * @return TurServicioDetalle
     */
    public function setCodigoModalidadServicioFk($codigoModalidadServicioFk)
    {
        $this->codigoModalidadServicioFk = $codigoModalidadServicioFk;

        return $this;
    }

    /**
     * Get codigoModalidadServicioFk
     *
     * @return integer
     */
    public function getCodigoModalidadServicioFk()
    {
        return $this->codigoModalidadServicioFk;
    }

    /**
     * Set codigoPeriodoFk
     *
     * @param integer $codigoPeriodoFk
     *
     * @return TurServicioDetalle
     */
    public function setCodigoPeriodoFk($codigoPeriodoFk)
    {
        $this->codigoPeriodoFk = $codigoPeriodoFk;

        return $this;
    }

    /**
     * Get codigoPeriodoFk
     *
     * @return integer
     */
    public function getCodigoPeriodoFk()
    {
        return $this->codigoPeriodoFk;
    }

    /**
     * Set codigoPlantillaFk
     *
     * @param integer $codigoPlantillaFk
     *
     * @return TurServicioDetalle
     */
    public function setCodigoPlantillaFk($codigoPlantillaFk)
    {
        $this->codigoPlantillaFk = $codigoPlantillaFk;

        return $this;
    }

    /**
     * Get codigoPlantillaFk
     *
     * @return integer
     */
    public function getCodigoPlantillaFk()
    {
        return $this->codigoPlantillaFk;
    }

    /**
     * Set diaDesde
     *
     * @param integer $diaDesde
     *
     * @return TurServicioDetalle
     */
    public function setDiaDesde($diaDesde)
    {
        $this->diaDesde = $diaDesde;

        return $this;
    }

    /**
     * Get diaDesde
     *
     * @return integer
     */
    public function getDiaDesde()
    {
        return $this->diaDesde;
    }

    /**
     * Set diaHasta
     *
     * @param integer $diaHasta
     *
     * @return TurServicioDetalle
     */
    public function setDiaHasta($diaHasta)
    {
        $this->diaHasta = $diaHasta;

        return $this;
    }

    /**
     * Get diaHasta
     *
     * @return integer
     */
    public function getDiaHasta()
    {
        return $this->diaHasta;
    }

    /**
     * Set dias
     *
     * @param integer $dias
     *
     * @return TurServicioDetalle
     */
    public function setDias($dias)
    {
        $this->dias = $dias;

        return $this;
    }

    /**
     * Get dias
     *
     * @return integer
     */
    public function getDias()
    {
        return $this->dias;
    }

    /**
     * Set horas
     *
     * @param integer $horas
     *
     * @return TurServicioDetalle
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
     * @return TurServicioDetalle
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
     * Set horasNocturnas
     *
     * @param integer $horasNocturnas
     *
     * @return TurServicioDetalle
     */
    public function setHorasNocturnas($horasNocturnas)
    {
        $this->horasNocturnas = $horasNocturnas;

        return $this;
    }

    /**
     * Get horasNocturnas
     *
     * @return integer
     */
    public function getHorasNocturnas()
    {
        return $this->horasNocturnas;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return TurServicioDetalle
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set cantidadRecurso
     *
     * @param integer $cantidadRecurso
     *
     * @return TurServicioDetalle
     */
    public function setCantidadRecurso($cantidadRecurso)
    {
        $this->cantidadRecurso = $cantidadRecurso;

        return $this;
    }

    /**
     * Get cantidadRecurso
     *
     * @return integer
     */
    public function getCantidadRecurso()
    {
        return $this->cantidadRecurso;
    }

    /**
     * Set vrCosto
     *
     * @param float $vrCosto
     *
     * @return TurServicioDetalle
     */
    public function setVrCosto($vrCosto)
    {
        $this->vrCosto = $vrCosto;

        return $this;
    }

    /**
     * Get vrCosto
     *
     * @return float
     */
    public function getVrCosto()
    {
        return $this->vrCosto;
    }

    /**
     * Set vrPrecioAjustado
     *
     * @param float $vrPrecioAjustado
     *
     * @return TurServicioDetalle
     */
    public function setVrPrecioAjustado($vrPrecioAjustado)
    {
        $this->vrPrecioAjustado = $vrPrecioAjustado;

        return $this;
    }

    /**
     * Get vrPrecioAjustado
     *
     * @return float
     */
    public function getVrPrecioAjustado()
    {
        return $this->vrPrecioAjustado;
    }

    /**
     * Set vrPrecioMinimo
     *
     * @param float $vrPrecioMinimo
     *
     * @return TurServicioDetalle
     */
    public function setVrPrecioMinimo($vrPrecioMinimo)
    {
        $this->vrPrecioMinimo = $vrPrecioMinimo;

        return $this;
    }

    /**
     * Get vrPrecioMinimo
     *
     * @return float
     */
    public function getVrPrecioMinimo()
    {
        return $this->vrPrecioMinimo;
    }

    /**
     * Set vrTotalDetalle
     *
     * @param float $vrTotalDetalle
     *
     * @return TurServicioDetalle
     */
    public function setVrTotalDetalle($vrTotalDetalle)
    {
        $this->vrTotalDetalle = $vrTotalDetalle;

        return $this;
    }

    /**
     * Get vrTotalDetalle
     *
     * @return float
     */
    public function getVrTotalDetalle()
    {
        return $this->vrTotalDetalle;
    }

    /**
     * Set lunes
     *
     * @param boolean $lunes
     *
     * @return TurServicioDetalle
     */
    public function setLunes($lunes)
    {
        $this->lunes = $lunes;

        return $this;
    }

    /**
     * Get lunes
     *
     * @return boolean
     */
    public function getLunes()
    {
        return $this->lunes;
    }

    /**
     * Set martes
     *
     * @param boolean $martes
     *
     * @return TurServicioDetalle
     */
    public function setMartes($martes)
    {
        $this->martes = $martes;

        return $this;
    }

    /**
     * Get martes
     *
     * @return boolean
     */
    public function getMartes()
    {
        return $this->martes;
    }

    /**
     * Set miercoles
     *
     * @param boolean $miercoles
     *
     * @return TurServicioDetalle
     */
    public function setMiercoles($miercoles)
    {
        $this->miercoles = $miercoles;

        return $this;
    }

    /**
     * Get miercoles
     *
     * @return boolean
     */
    public function getMiercoles()
    {
        return $this->miercoles;
    }

    /**
     * Set jueves
     *
     * @param boolean $jueves
     *
     * @return TurServicioDetalle
     */
    public function setJueves($jueves)
    {
        $this->jueves = $jueves;

        return $this;
    }

    /**
     * Get jueves
     *
     * @return boolean
     */
    public function getJueves()
    {
        return $this->jueves;
    }

    /**
     * Set viernes
     *
     * @param boolean $viernes
     *
     * @return TurServicioDetalle
     */
    public function setViernes($viernes)
    {
        $this->viernes = $viernes;

        return $this;
    }

    /**
     * Get viernes
     *
     * @return boolean
     */
    public function getViernes()
    {
        return $this->viernes;
    }

    /**
     * Set sabado
     *
     * @param boolean $sabado
     *
     * @return TurServicioDetalle
     */
    public function setSabado($sabado)
    {
        $this->sabado = $sabado;

        return $this;
    }

    /**
     * Get sabado
     *
     * @return boolean
     */
    public function getSabado()
    {
        return $this->sabado;
    }

    /**
     * Set domingo
     *
     * @param boolean $domingo
     *
     * @return TurServicioDetalle
     */
    public function setDomingo($domingo)
    {
        $this->domingo = $domingo;

        return $this;
    }

    /**
     * Get domingo
     *
     * @return boolean
     */
    public function getDomingo()
    {
        return $this->domingo;
    }

    /**
     * Set festivo
     *
     * @param boolean $festivo
     *
     * @return TurServicioDetalle
     */
    public function setFestivo($festivo)
    {
        $this->festivo = $festivo;

        return $this;
    }

    /**
     * Get festivo
     *
     * @return boolean
     */
    public function getFestivo()
    {
        return $this->festivo;
    }

    /**
     * Set dia31
     *
     * @param boolean $dia31
     *
     * @return TurServicioDetalle
     */
    public function setDia31($dia31)
    {
        $this->dia31 = $dia31;

        return $this;
    }

    /**
     * Get dia31
     *
     * @return boolean
     */
    public function getDia31()
    {
        return $this->dia31;
    }

    /**
     * Set diasSecuencia
     *
     * @param integer $diasSecuencia
     *
     * @return TurServicioDetalle
     */
    public function setDiasSecuencia($diasSecuencia)
    {
        $this->diasSecuencia = $diasSecuencia;

        return $this;
    }

    /**
     * Get diasSecuencia
     *
     * @return integer
     */
    public function getDiasSecuencia()
    {
        return $this->diasSecuencia;
    }

    /**
     * Set servicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicio $servicioRel
     *
     * @return TurServicioDetalle
     */
    public function setServicioRel(\Brasa\TurnoBundle\Entity\TurServicio $servicioRel = null)
    {
        $this->servicioRel = $servicioRel;

        return $this;
    }

    /**
     * Get servicioRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurServicio
     */
    public function getServicioRel()
    {
        return $this->servicioRel;
    }

    /**
     * Set puestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuesto $puestoRel
     *
     * @return TurServicioDetalle
     */
    public function setPuestoRel(\Brasa\TurnoBundle\Entity\TurPuesto $puestoRel = null)
    {
        $this->puestoRel = $puestoRel;

        return $this;
    }

    /**
     * Get puestoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurPuesto
     */
    public function getPuestoRel()
    {
        return $this->puestoRel;
    }

    /**
     * Set conceptoServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurConceptoServicio $conceptoServicioRel
     *
     * @return TurServicioDetalle
     */
    public function setConceptoServicioRel(\Brasa\TurnoBundle\Entity\TurConceptoServicio $conceptoServicioRel = null)
    {
        $this->conceptoServicioRel = $conceptoServicioRel;

        return $this;
    }

    /**
     * Get conceptoServicioRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurConceptoServicio
     */
    public function getConceptoServicioRel()
    {
        return $this->conceptoServicioRel;
    }

    /**
     * Set modalidadServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurModalidadServicio $modalidadServicioRel
     *
     * @return TurServicioDetalle
     */
    public function setModalidadServicioRel(\Brasa\TurnoBundle\Entity\TurModalidadServicio $modalidadServicioRel = null)
    {
        $this->modalidadServicioRel = $modalidadServicioRel;

        return $this;
    }

    /**
     * Get modalidadServicioRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurModalidadServicio
     */
    public function getModalidadServicioRel()
    {
        return $this->modalidadServicioRel;
    }

    /**
     * Set periodoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPeriodo $periodoRel
     *
     * @return TurServicioDetalle
     */
    public function setPeriodoRel(\Brasa\TurnoBundle\Entity\TurPeriodo $periodoRel = null)
    {
        $this->periodoRel = $periodoRel;

        return $this;
    }

    /**
     * Get periodoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurPeriodo
     */
    public function getPeriodoRel()
    {
        return $this->periodoRel;
    }

    /**
     * Set plantillaRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPlantilla $plantillaRel
     *
     * @return TurServicioDetalle
     */
    public function setPlantillaRel(\Brasa\TurnoBundle\Entity\TurPlantilla $plantillaRel = null)
    {
        $this->plantillaRel = $plantillaRel;

        return $this;
    }

    /**
     * Get plantillaRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurPlantilla
     */
    public function getPlantillaRel()
    {
        return $this->plantillaRel;
    }

    /**
     * Add serviciosDetallesRecursosServicioDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalleRecurso $serviciosDetallesRecursosServicioDetalleRel
     *
     * @return TurServicioDetalle
     */
    public function addServiciosDetallesRecursosServicioDetalleRel(\Brasa\TurnoBundle\Entity\TurServicioDetalleRecurso $serviciosDetallesRecursosServicioDetalleRel)
    {
        $this->serviciosDetallesRecursosServicioDetalleRel[] = $serviciosDetallesRecursosServicioDetalleRel;

        return $this;
    }

    /**
     * Remove serviciosDetallesRecursosServicioDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalleRecurso $serviciosDetallesRecursosServicioDetalleRel
     */
    public function removeServiciosDetallesRecursosServicioDetalleRel(\Brasa\TurnoBundle\Entity\TurServicioDetalleRecurso $serviciosDetallesRecursosServicioDetalleRel)
    {
        $this->serviciosDetallesRecursosServicioDetalleRel->removeElement($serviciosDetallesRecursosServicioDetalleRel);
    }

    /**
     * Get serviciosDetallesRecursosServicioDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosDetallesRecursosServicioDetalleRel()
    {
        return $this->serviciosDetallesRecursosServicioDetalleRel;
    }

    /**
     * Add serviciosDetallesPlantillasServicioDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetallePlantilla $serviciosDetallesPlantillasServicioDetalleRel
     *
     * @return TurServicioDetalle
     */
    public function addServiciosDetallesPlantillasServicioDetalleRel(\Brasa\TurnoBundle\Entity\TurServicioDetallePlantilla $serviciosDetallesPlantillasServicioDetalleRel)
    {
        $this->serviciosDetallesPlantillasServicioDetalleRel[] = $serviciosDetallesPlantillasServicioDetalleRel;

        return $this;
    }

    /**
     * Remove serviciosDetallesPlantillasServicioDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetallePlantilla $serviciosDetallesPlantillasServicioDetalleRel
     */
    public function removeServiciosDetallesPlantillasServicioDetalleRel(\Brasa\TurnoBundle\Entity\TurServicioDetallePlantilla $serviciosDetallesPlantillasServicioDetalleRel)
    {
        $this->serviciosDetallesPlantillasServicioDetalleRel->removeElement($serviciosDetallesPlantillasServicioDetalleRel);
    }

    /**
     * Get serviciosDetallesPlantillasServicioDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosDetallesPlantillasServicioDetalleRel()
    {
        return $this->serviciosDetallesPlantillasServicioDetalleRel;
    }

    /**
     * Add pedidosDetallesServicioDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesServicioDetalleRel
     *
     * @return TurServicioDetalle
     */
    public function addPedidosDetallesServicioDetalleRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesServicioDetalleRel)
    {
        $this->pedidosDetallesServicioDetalleRel[] = $pedidosDetallesServicioDetalleRel;

        return $this;
    }

    /**
     * Remove pedidosDetallesServicioDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesServicioDetalleRel
     */
    public function removePedidosDetallesServicioDetalleRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalle $pedidosDetallesServicioDetalleRel)
    {
        $this->pedidosDetallesServicioDetalleRel->removeElement($pedidosDetallesServicioDetalleRel);
    }

    /**
     * Get pedidosDetallesServicioDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosDetallesServicioDetalleRel()
    {
        return $this->pedidosDetallesServicioDetalleRel;
    }

    /**
     * Set fechaIniciaPlantilla
     *
     * @param \DateTime $fechaIniciaPlantilla
     *
     * @return TurServicioDetalle
     */
    public function setFechaIniciaPlantilla($fechaIniciaPlantilla)
    {
        $this->fechaIniciaPlantilla = $fechaIniciaPlantilla;

        return $this;
    }

    /**
     * Get fechaIniciaPlantilla
     *
     * @return \DateTime
     */
    public function getFechaIniciaPlantilla()
    {
        return $this->fechaIniciaPlantilla;
    }
}
