<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_capacitacion")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCapacitacionRepository")
 */
class RhuCapacitacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_capacitacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCapacitacionPk;                    
    
    /**
     * @ORM\Column(name="codigo_capacitacion_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoCapacitacionTipoFk;     
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;
    
    /**
     * @ORM\Column(name="fecha_capacitacion", type="datetime", nullable=true)
     */    
    private $fechaCapacitacion;

    /**
     * @ORM\Column(name="tema", type="string", length=150, nullable=true)
     */    
    private $tema;
    
    /**
     * @ORM\Column(name="vr_capacitacion", type="float")
     */
    private $VrCapacitacion = 0;
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=250, nullable=true)
     */    
    private $comentarios;
    
    /**     
     * @ORM\Column(name="estado", type="boolean")
     */    
    private $estado = false;

    /**     
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = false;

    /**
     * @ORM\Column(name="numero_personas_capacitar", type="integer", nullable=true)
     */    
    private $numeroPersonasCapacitar;
    
    /**
     * @ORM\Column(name="numero_personas_asistieron", type="integer", nullable=true)
     */    
    private $numeroPersonasAsistieron;
    
    /**
     * @ORM\Column(name="lugar", type="string", length=150, nullable=true)
     */    
    private $lugar;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCapacitacionTipo", inversedBy="capacitacionesCapacitacionTipoRel")
     * @ORM\JoinColumn(name="codigo_capacitacion_tipo_fk", referencedColumnName="codigo_capacitacion_tipo_pk")
     */
    protected $capacitacionTipoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuCapacitacionDetalle", mappedBy="capacitacionRel", cascade={"persist", "remove"})
     */
    protected $capacitacionesDetallesCapacitacionRel;    

    /**
     * @ORM\OneToMany(targetEntity="RhuCapacitacionNota", mappedBy="capacitacionRel", cascade={"persist", "remove"})
     */
    protected $capacitacionesNotasCapacitacionRel;        
    
    
    
}
