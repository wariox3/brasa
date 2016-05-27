<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_configuracion")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuConfiguracionRepository")
 */
class RhuConfiguracion
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_configuracion_pk", type="integer")
     */
    private $codigoConfiguracionPk;

    /**
     * @ORM\Column(name="codigo_entidad_riesgo_fk", type="integer")
     */
    private $codigoEntidadRiesgoFk;

    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $vrSalario;

    /**
     * @ORM\Column(name="codigo_auxilio_transporte", type="integer")
     */
    private $codigoAuxilioTransporte;

    /**
     * @ORM\Column(name="vr_auxilio_transporte", type="float")
     */
    private $vrAuxilioTransporte;

    /**
     * @ORM\Column(name="codigo_credito", type="integer")
     */
    private $codigoCredito;

    /**
     * @ORM\Column(name="codigo_seguro", type="integer")
     */
    private $codigoSeguro;

    /**
     * @ORM\Column(name="codigo_tiempo_suplementario", type="integer")
     */
    private $codigoTiempoSuplementario;

    /**
     * @ORM\Column(name="codigo_hora_diurna_trabajada", type="integer")
     */
    private $codigoHoraDiurnaTrabajada;

    /**
     * @ORM\Column(name="porcentaje_pension_extra", type="float")
     */
    private $porcentajePensionExtra;

    /**
     * @ORM\Column(name="codigo_incapacidad", type="integer")
     */
    private $codigoIncapacidad;

    /**
     * @ORM\Column(name="anio_actual", type="integer")
     */
    private $anioActual;

    /**
     * @ORM\Column(name="porcentaje_iva", type="float")
     */
    private $porcentajeIva;

    /**
     * @ORM\Column(name="codigo_retencion_fuente", type="integer")
     */
    private $codigoRetencionFuente;

    /**
     * @ORM\Column(name="edad_minima_empleado", type="integer")
     */
    private $edadMinimaEmpleado;

    /**
     * @ORM\Column(name="porcentaje_bonificacion_no_prestacional", type="float")
     */
    private $porcentajeBonificacionNoPrestacional = 40;

    /**
     * @ORM\Column(name="codigo_entidad_Examen_ingreso", type="integer")
     */
    private $codigoEntidadExamenIngreso;

    /**
     * @ORM\Column(name="codigo_comprobante_pago_nomina", type="integer")
     */
    private $codigoComprobantePagoNomina;

    /**
     * @ORM\Column(name="codigo_comprobante_pago_banco", type="integer")
     */
    private $codigoComprobantePagoBanco;

    /**
     * @ORM\Column(name="control_pago", type="boolean")
     */
    private $controlPago = 0;

    /**
     * @ORM\Column(name="prestaciones_porcentaje_cesantias", type="float")
     */
    private $prestacionesPorcentajeCesantias = 0;

    /**
     * @ORM\Column(name="prestaciones_porcentaje_intereses_cesantias", type="float")
     */
    private $prestacionesPorcentajeInteresesCesantias = 0;

    /**
     * @ORM\Column(name="prestaciones_porcentaje_primas", type="float")
     */
    private $prestacionesPorcentajePrimas = 0;

    /**
     * @ORM\Column(name="prestaciones_porcentaje_vacaciones", type="float")
     */
    private $prestacionesPorcentajeVacaciones = 0;

    /**
     * @ORM\Column(name="prestaciones_porcentaje_aporte_vacaciones", type="float")
     */
    private $prestacionesPorcentajeAporteVacaciones = 0;    
    
    /**
     * @ORM\Column(name="aportes_porcentaje_caja", type="float")
     */
    private $aportesPorcentajeCaja = 0;    
    
    /**
     * @ORM\Column(name="aportes_porcentaje_vacaciones", type="float")
     */
    private $aportesPorcentajeVacaciones = 0;
    
    /**
     * @ORM\Column(name="cuenta_nomina_pagar", type="integer")
     */
    private $cuentaNominaPagar;
    
    /**
     * @ORM\Column(name="cuenta_pago", type="integer")
     */
    private $cuentaPago;
    
    /**
     * Tipo de base para la liquidacion de vacaciones 1-salario 2-salario+prestaciones 3-salario+recargos
     * @ORM\Column(name="tipo_base_pago_vacaciones", type="integer")
     */
    private $tipoBasePagoVacaciones;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadRiesgoProfesional", inversedBy="configuracionEntidadRiesgoProfesionalRel")
     * @ORM\JoinColumn(name="codigo_entidad_riesgo_fk", referencedColumnName="codigo_entidad_riesgo_pk")
     */
    protected $entidadRiesgoProfesionalRel;


    /**
     * Set codigoConfiguracionPk
     *
     * @param integer $codigoConfiguracionPk
     *
     * @return RhuConfiguracion
     */
    public function setCodigoConfiguracionPk($codigoConfiguracionPk)
    {
        $this->codigoConfiguracionPk = $codigoConfiguracionPk;

        return $this;
    }

    /**
     * Get codigoConfiguracionPk
     *
     * @return integer
     */
    public function getCodigoConfiguracionPk()
    {
        return $this->codigoConfiguracionPk;
    }

    /**
     * Set codigoEntidadRiesgoFk
     *
     * @param integer $codigoEntidadRiesgoFk
     *
     * @return RhuConfiguracion
     */
    public function setCodigoEntidadRiesgoFk($codigoEntidadRiesgoFk)
    {
        $this->codigoEntidadRiesgoFk = $codigoEntidadRiesgoFk;

        return $this;
    }

    /**
     * Get codigoEntidadRiesgoFk
     *
     * @return integer
     */
    public function getCodigoEntidadRiesgoFk()
    {
        return $this->codigoEntidadRiesgoFk;
    }

    /**
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return RhuConfiguracion
     */
    public function setVrSalario($vrSalario)
    {
        $this->vrSalario = $vrSalario;

        return $this;
    }

    /**
     * Get vrSalario
     *
     * @return float
     */
    public function getVrSalario()
    {
        return $this->vrSalario;
    }

    /**
     * Set codigoAuxilioTransporte
     *
     * @param integer $codigoAuxilioTransporte
     *
     * @return RhuConfiguracion
     */
    public function setCodigoAuxilioTransporte($codigoAuxilioTransporte)
    {
        $this->codigoAuxilioTransporte = $codigoAuxilioTransporte;

        return $this;
    }

    /**
     * Get codigoAuxilioTransporte
     *
     * @return integer
     */
    public function getCodigoAuxilioTransporte()
    {
        return $this->codigoAuxilioTransporte;
    }

    /**
     * Set vrAuxilioTransporte
     *
     * @param float $vrAuxilioTransporte
     *
     * @return RhuConfiguracion
     */
    public function setVrAuxilioTransporte($vrAuxilioTransporte)
    {
        $this->vrAuxilioTransporte = $vrAuxilioTransporte;

        return $this;
    }

    /**
     * Get vrAuxilioTransporte
     *
     * @return float
     */
    public function getVrAuxilioTransporte()
    {
        return $this->vrAuxilioTransporte;
    }

    /**
     * Set codigoCredito
     *
     * @param integer $codigoCredito
     *
     * @return RhuConfiguracion
     */
    public function setCodigoCredito($codigoCredito)
    {
        $this->codigoCredito = $codigoCredito;

        return $this;
    }

    /**
     * Get codigoCredito
     *
     * @return integer
     */
    public function getCodigoCredito()
    {
        return $this->codigoCredito;
    }

    /**
     * Set codigoSeguro
     *
     * @param integer $codigoSeguro
     *
     * @return RhuConfiguracion
     */
    public function setCodigoSeguro($codigoSeguro)
    {
        $this->codigoSeguro = $codigoSeguro;

        return $this;
    }

    /**
     * Get codigoSeguro
     *
     * @return integer
     */
    public function getCodigoSeguro()
    {
        return $this->codigoSeguro;
    }

    /**
     * Set codigoTiempoSuplementario
     *
     * @param integer $codigoTiempoSuplementario
     *
     * @return RhuConfiguracion
     */
    public function setCodigoTiempoSuplementario($codigoTiempoSuplementario)
    {
        $this->codigoTiempoSuplementario = $codigoTiempoSuplementario;

        return $this;
    }

    /**
     * Get codigoTiempoSuplementario
     *
     * @return integer
     */
    public function getCodigoTiempoSuplementario()
    {
        return $this->codigoTiempoSuplementario;
    }

    /**
     * Set codigoHoraDiurnaTrabajada
     *
     * @param integer $codigoHoraDiurnaTrabajada
     *
     * @return RhuConfiguracion
     */
    public function setCodigoHoraDiurnaTrabajada($codigoHoraDiurnaTrabajada)
    {
        $this->codigoHoraDiurnaTrabajada = $codigoHoraDiurnaTrabajada;

        return $this;
    }

    /**
     * Get codigoHoraDiurnaTrabajada
     *
     * @return integer
     */
    public function getCodigoHoraDiurnaTrabajada()
    {
        return $this->codigoHoraDiurnaTrabajada;
    }

    /**
     * Set porcentajePensionExtra
     *
     * @param float $porcentajePensionExtra
     *
     * @return RhuConfiguracion
     */
    public function setPorcentajePensionExtra($porcentajePensionExtra)
    {
        $this->porcentajePensionExtra = $porcentajePensionExtra;

        return $this;
    }

    /**
     * Get porcentajePensionExtra
     *
     * @return float
     */
    public function getPorcentajePensionExtra()
    {
        return $this->porcentajePensionExtra;
    }

    /**
     * Set codigoIncapacidad
     *
     * @param integer $codigoIncapacidad
     *
     * @return RhuConfiguracion
     */
    public function setCodigoIncapacidad($codigoIncapacidad)
    {
        $this->codigoIncapacidad = $codigoIncapacidad;

        return $this;
    }

    /**
     * Get codigoIncapacidad
     *
     * @return integer
     */
    public function getCodigoIncapacidad()
    {
        return $this->codigoIncapacidad;
    }

    /**
     * Set anioActual
     *
     * @param integer $anioActual
     *
     * @return RhuConfiguracion
     */
    public function setAnioActual($anioActual)
    {
        $this->anioActual = $anioActual;

        return $this;
    }

    /**
     * Get anioActual
     *
     * @return integer
     */
    public function getAnioActual()
    {
        return $this->anioActual;
    }

    /**
     * Set porcentajeIva
     *
     * @param float $porcentajeIva
     *
     * @return RhuConfiguracion
     */
    public function setPorcentajeIva($porcentajeIva)
    {
        $this->porcentajeIva = $porcentajeIva;

        return $this;
    }

    /**
     * Get porcentajeIva
     *
     * @return float
     */
    public function getPorcentajeIva()
    {
        return $this->porcentajeIva;
    }

    /**
     * Set codigoRetencionFuente
     *
     * @param integer $codigoRetencionFuente
     *
     * @return RhuConfiguracion
     */
    public function setCodigoRetencionFuente($codigoRetencionFuente)
    {
        $this->codigoRetencionFuente = $codigoRetencionFuente;

        return $this;
    }

    /**
     * Get codigoRetencionFuente
     *
     * @return integer
     */
    public function getCodigoRetencionFuente()
    {
        return $this->codigoRetencionFuente;
    }

    /**
     * Set edadMinimaEmpleado
     *
     * @param integer $edadMinimaEmpleado
     *
     * @return RhuConfiguracion
     */
    public function setEdadMinimaEmpleado($edadMinimaEmpleado)
    {
        $this->edadMinimaEmpleado = $edadMinimaEmpleado;

        return $this;
    }

    /**
     * Get edadMinimaEmpleado
     *
     * @return integer
     */
    public function getEdadMinimaEmpleado()
    {
        return $this->edadMinimaEmpleado;
    }

    /**
     * Set porcentajeBonificacionNoPrestacional
     *
     * @param float $porcentajeBonificacionNoPrestacional
     *
     * @return RhuConfiguracion
     */
    public function setPorcentajeBonificacionNoPrestacional($porcentajeBonificacionNoPrestacional)
    {
        $this->porcentajeBonificacionNoPrestacional = $porcentajeBonificacionNoPrestacional;

        return $this;
    }

    /**
     * Get porcentajeBonificacionNoPrestacional
     *
     * @return float
     */
    public function getPorcentajeBonificacionNoPrestacional()
    {
        return $this->porcentajeBonificacionNoPrestacional;
    }

    /**
     * Set codigoEntidadExamenIngreso
     *
     * @param integer $codigoEntidadExamenIngreso
     *
     * @return RhuConfiguracion
     */
    public function setCodigoEntidadExamenIngreso($codigoEntidadExamenIngreso)
    {
        $this->codigoEntidadExamenIngreso = $codigoEntidadExamenIngreso;

        return $this;
    }

    /**
     * Get codigoEntidadExamenIngreso
     *
     * @return integer
     */
    public function getCodigoEntidadExamenIngreso()
    {
        return $this->codigoEntidadExamenIngreso;
    }

    /**
     * Set codigoComprobantePagoNomina
     *
     * @param integer $codigoComprobantePagoNomina
     *
     * @return RhuConfiguracion
     */
    public function setCodigoComprobantePagoNomina($codigoComprobantePagoNomina)
    {
        $this->codigoComprobantePagoNomina = $codigoComprobantePagoNomina;

        return $this;
    }

    /**
     * Get codigoComprobantePagoNomina
     *
     * @return integer
     */
    public function getCodigoComprobantePagoNomina()
    {
        return $this->codigoComprobantePagoNomina;
    }

    /**
     * Set codigoComprobantePagoBanco
     *
     * @param integer $codigoComprobantePagoBanco
     *
     * @return RhuConfiguracion
     */
    public function setCodigoComprobantePagoBanco($codigoComprobantePagoBanco)
    {
        $this->codigoComprobantePagoBanco = $codigoComprobantePagoBanco;

        return $this;
    }

    /**
     * Get codigoComprobantePagoBanco
     *
     * @return integer
     */
    public function getCodigoComprobantePagoBanco()
    {
        return $this->codigoComprobantePagoBanco;
    }

    /**
     * Set controlPago
     *
     * @param boolean $controlPago
     *
     * @return RhuConfiguracion
     */
    public function setControlPago($controlPago)
    {
        $this->controlPago = $controlPago;

        return $this;
    }

    /**
     * Get controlPago
     *
     * @return boolean
     */
    public function getControlPago()
    {
        return $this->controlPago;
    }

    /**
     * Set entidadRiesgoProfesionalRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEntidadRiesgoProfesional $entidadRiesgoProfesionalRel
     *
     * @return RhuConfiguracion
     */
    public function setEntidadRiesgoProfesionalRel(\Brasa\RecursoHumanoBundle\Entity\RhuEntidadRiesgoProfesional $entidadRiesgoProfesionalRel = null)
    {
        $this->entidadRiesgoProfesionalRel = $entidadRiesgoProfesionalRel;

        return $this;
    }

    /**
     * Get entidadRiesgoProfesionalRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEntidadRiesgoProfesional
     */
    public function getEntidadRiesgoProfesionalRel()
    {
        return $this->entidadRiesgoProfesionalRel;
    }

    /**
     * Set prestacionesPorcentajeCesantias
     *
     * @param float $prestacionesPorcentajeCesantias
     *
     * @return RhuConfiguracion
     */
    public function setPrestacionesPorcentajeCesantias($prestacionesPorcentajeCesantias)
    {
        $this->prestacionesPorcentajeCesantias = $prestacionesPorcentajeCesantias;

        return $this;
    }

    /**
     * Get prestacionesPorcentajeCesantias
     *
     * @return float
     */
    public function getPrestacionesPorcentajeCesantias()
    {
        return $this->prestacionesPorcentajeCesantias;
    }

    /**
     * Set prestacionesPorcentajeVacaciones
     *
     * @param float $prestacionesPorcentajeVacaciones
     *
     * @return RhuConfiguracion
     */
    public function setPrestacionesPorcentajeVacaciones($prestacionesPorcentajeVacaciones)
    {
        $this->prestacionesPorcentajeVacaciones = $prestacionesPorcentajeVacaciones;

        return $this;
    }

    /**
     * Get prestacionesPorcentajeVacaciones
     *
     * @return float
     */
    public function getPrestacionesPorcentajeVacaciones()
    {
        return $this->prestacionesPorcentajeVacaciones;
    }

    /**
     * Set aportesPorcentajeCaja
     *
     * @param float $aportesPorcentajeCaja
     *
     * @return RhuConfiguracion
     */
    public function setAportesPorcentajeCaja($aportesPorcentajeCaja)
    {
        $this->aportesPorcentajeCaja = $aportesPorcentajeCaja;

        return $this;
    }

    /**
     * Get aportesPorcentajeCaja
     *
     * @return float
     */
    public function getAportesPorcentajeCaja()
    {
        return $this->aportesPorcentajeCaja;
    }

    /**
     * Set prestacionesPorcentajeInteresesCesantias
     *
     * @param float $prestacionesPorcentajeInteresesCesantias
     *
     * @return RhuConfiguracion
     */
    public function setPrestacionesPorcentajeInteresesCesantias($prestacionesPorcentajeInteresesCesantias)
    {
        $this->prestacionesPorcentajeInteresesCesantias = $prestacionesPorcentajeInteresesCesantias;

        return $this;
    }

    /**
     * Get prestacionesPorcentajeInteresesCesantias
     *
     * @return float
     */
    public function getPrestacionesPorcentajeInteresesCesantias()
    {
        return $this->prestacionesPorcentajeInteresesCesantias;
    }

    /**
     * Set prestacionesPorcentajePrimas
     *
     * @param float $prestacionesPorcentajePrimas
     *
     * @return RhuConfiguracion
     */
    public function setPrestacionesPorcentajePrimas($prestacionesPorcentajePrimas)
    {
        $this->prestacionesPorcentajePrimas = $prestacionesPorcentajePrimas;

        return $this;
    }

    /**
     * Get prestacionesPorcentajePrimas
     *
     * @return float
     */
    public function getPrestacionesPorcentajePrimas()
    {
        return $this->prestacionesPorcentajePrimas;
    }

    /**
     * Set prestacionesPorcentajeAporteVacaciones
     *
     * @param float $prestacionesPorcentajeAporteVacaciones
     *
     * @return RhuConfiguracion
     */
    public function setPrestacionesPorcentajeAporteVacaciones($prestacionesPorcentajeAporteVacaciones)
    {
        $this->prestacionesPorcentajeAporteVacaciones = $prestacionesPorcentajeAporteVacaciones;

        return $this;
    }

    /**
     * Get prestacionesPorcentajeAporteVacaciones
     *
     * @return float
     */
    public function getPrestacionesPorcentajeAporteVacaciones()
    {
        return $this->prestacionesPorcentajeAporteVacaciones;
    }

    /**
     * Set tipoBasePagoVacaciones
     *
     * @param integer $tipoBasePagoVacaciones
     *
     * @return RhuConfiguracion
     */
    public function setTipoBasePagoVacaciones($tipoBasePagoVacaciones)
    {
        $this->tipoBasePagoVacaciones = $tipoBasePagoVacaciones;

        return $this;
    }

    /**
     * Get tipoBasePagoVacaciones
     *
     * @return integer
     */
    public function getTipoBasePagoVacaciones()
    {
        return $this->tipoBasePagoVacaciones;
    }

    /**
     * Set aportesPorcentajeVacaciones
     *
     * @param float $aportesPorcentajeVacaciones
     *
     * @return RhuConfiguracion
     */
    public function setAportesPorcentajeVacaciones($aportesPorcentajeVacaciones)
    {
        $this->aportesPorcentajeVacaciones = $aportesPorcentajeVacaciones;

        return $this;
    }

    /**
     * Get aportesPorcentajeVacaciones
     *
     * @return float
     */
    public function getAportesPorcentajeVacaciones()
    {
        return $this->aportesPorcentajeVacaciones;
    }

    /**
     * Set cuentaNominaPagar
     *
     * @param integer $cuentaNominaPagar
     *
     * @return RhuConfiguracion
     */
    public function setCuentaNominaPagar($cuentaNominaPagar)
    {
        $this->cuentaNominaPagar = $cuentaNominaPagar;

        return $this;
    }

    /**
     * Get cuentaNominaPagar
     *
     * @return integer
     */
    public function getCuentaNominaPagar()
    {
        return $this->cuentaNominaPagar;
    }

    /**
     * Set cuentaPago
     *
     * @param integer $cuentaPago
     *
     * @return RhuConfiguracion
     */
    public function setCuentaPago($cuentaPago)
    {
        $this->cuentaPago = $cuentaPago;

        return $this;
    }

    /**
     * Get cuentaPago
     *
     * @return integer
     */
    public function getCuentaPago()
    {
        return $this->cuentaPago;
    }
}
