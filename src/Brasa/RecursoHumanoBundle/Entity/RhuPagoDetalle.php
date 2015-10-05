<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_pago_detalle")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPagoDetalleRepository")
 */
class RhuPagoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPagoDetallePk;
    
    /**
     * @ORM\Column(name="codigo_pago_fk", type="integer", nullable=true)
     */    
    private $codigoPagoFk;
    
    /**
     * @ORM\Column(name="codigo_credito_fk", type="integer", nullable=true)
     */    
    private $codigoCreditoFk;         
    
    /**
     * @ORM\Column(name="vr_pago", type="float")
     */
    private $vrPago = 0;     

    /**
     * @ORM\Column(name="operacion", type="integer")
     */
    private $operacion = 0;
    
    /**
     * @ORM\Column(name="vr_pago_operado", type="float")
     */
    private $vrPagoOperado = 0;    
    
    /**
     * @ORM\Column(name="numero_horas", type="float")
     */
    private $numeroHoras = 0;    
    
    /**
     * @ORM\Column(name="vr_hora", type="float")
     */
    private $vrHora = 0;     
    
    /**
     * @ORM\Column(name="porcentaje_aplicado", type="float")
     */
    private $porcentajeAplicado = 0;    
    
    /**
     * @ORM\Column(name="numero_dias", type="float")
     */
    private $numeroDias = 0;     
    
    /**
     * @ORM\Column(name="vr_dia", type="float")
     */
    private $vrDia = 0;    
    
    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;     
    
    /**
     * @ORM\Column(name="detalle", type="string", length=250, nullable=true)
     */    
    private $detalle;     
    
    /**
     * @ORM\Column(name="codigo_pago_concepto_fk", type="integer", nullable=true)
     */    
    private $codigoPagoConceptoFk;    
    
    /**
     * @ORM\Column(name="vr_ingreso_base_cotizacion", type="float")
     */
    private $vrIngresoBaseCotizacion = 0;     

    /**
     * @ORM\Column(name="vr_ingreso_base_prestacion", type="float")
     */
    private $vrIngresoBasePrestacion = 0;    
    
    /**
     * @ORM\Column(name="codigo_programacion_pago_detalle_fk", type="integer", nullable=true)
     */    
    private $codigoProgramacionPagoDetalleFk;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuPago", inversedBy="pagosDetallesPagoRel")
     * @ORM\JoinColumn(name="codigo_pago_fk", referencedColumnName="codigo_pago_pk")
     */
    protected $pagoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuPagoConcepto", inversedBy="pagosDetallesPagoConceptoRel")
     * @ORM\JoinColumn(name="codigo_pago_concepto_fk", referencedColumnName="codigo_pago_concepto_pk")
     */
    protected $pagoConceptoRel;     


    /**
     * @ORM\ManyToOne(targetEntity="RhuProgramacionPagoDetalle", inversedBy="pagosDetallesProgramacionPagoDetalleRel")
     * @ORM\JoinColumn(name="codigo_programacion_pago_detalle_fk", referencedColumnName="codigo_programacion_pago_detalle_pk")
     */
    protected $programacionPagoDetalleRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCredito", inversedBy="pagosDetallesCreditoRel")
     * @ORM\JoinColumn(name="codigo_credito_fk", referencedColumnName="codigo_credito_pk")
     */
    protected $creditoRel;
    


}
