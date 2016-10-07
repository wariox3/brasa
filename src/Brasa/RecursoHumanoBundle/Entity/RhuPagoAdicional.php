<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_pago_adicional")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPagoAdicionalRepository")
 */
class RhuPagoAdicional
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_adicional_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPagoAdicionalPk;         
        
    /**
     * @ORM\Column(name="codigo_pago_concepto_fk", type="integer", nullable=true)
     */    
    private $codigoPagoConceptoFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;       

    /**
     * @ORM\Column(name="tipo_adicional", type="integer", nullable=false)
     */    
    private $tipoAdicional;    
    
    /**
     * @ORM\Column(name="modalidad", type="integer", nullable=true)
     */    
    private $modalidad = 0;    

    /**
     * @ORM\Column(name="codigo_periodo_fk", type="integer", nullable=true)
     */    
    private $codigoPeriodoFk;
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;     
    
    /**
     * @ORM\Column(name="cantidad", type="float")
     */
    private $cantidad = 0; 
    
    /**
     * @ORM\Column(name="valor", type="float")
     */
    private $valor = 0;     
    
    
    /**
     * @ORM\Column(name="codigo_programacion_pago_fk", type="integer", nullable=true)
     */    
    private $codigoProgramacionPagoFk;    
    
    /**     
     * @ORM\Column(name="permanente", type="boolean")
     */    
    private $permanente = 0;     

    /**     
     * @ORM\Column(name="aplica_dia_laborado", type="boolean")
     */    
    private $aplicaDiaLaborado = 0;                 
    
    /**
     * @ORM\Column(name="detalle", type="string", length=250, nullable=true)
     */    
    private $detalle;                         
    
    /**     
     * @ORM\Column(name="prestacional", type="boolean")
     */    
    private $prestacional = 0;

    /**     
     * @ORM\Column(name="estado_inactivo", type="boolean")
     */    
    private $estadoInactivo = 0;    
    
    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;
    
    /**
     * @ORM\Column(name="fecha_creacion", type="datetime", nullable=true)
     */    
    private $fechaCreacion;     

    /**
     * @ORM\Column(name="fecha_ultima_edicion", type="datetime", nullable=true)
     */    
    private $fechaUltimaEdicion;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuPagoConcepto", inversedBy="pagosAdicionalesPagoConceptoRel")
     * @ORM\JoinColumn(name="codigo_pago_concepto_fk", referencedColumnName="codigo_pago_concepto_pk")
     */
    protected $pagoConceptoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="pagosAdicionalesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuProgramacionPago", inversedBy="pagosAdicionalesProgramacionPagoRel")
     * @ORM\JoinColumn(name="codigo_programacion_pago_fk", referencedColumnName="codigo_programacion_pago_pk")
     */
    protected $programacionPagoRel;    
    


}
