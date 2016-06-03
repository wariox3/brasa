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
     * @ORM\Column(name="codigo_entidad_examen_ingreso", type="integer")
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
     * @ORM\Column(name="cuenta_nomina_pagar", type="string", length=20, nullable=true)
     */
    private $cuentaNominaPagar;
    
    /**
     * @ORM\Column(name="cuenta_pago", type="string", length=20, nullable=true)
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


    
}
