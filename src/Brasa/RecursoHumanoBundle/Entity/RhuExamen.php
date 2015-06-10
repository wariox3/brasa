<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_examen")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuExamenRepository")
 */
class RhuExamen
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_examen_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoExamenPk;
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk;
    
    /**
     * @ORM\Column(name="codigo_entidad_examen_fk", type="integer", nullable=true)
     */    
    private $codigoEntidadExamenFk;
    
    /**
     * @ORM\Column(name="fecha", type="date")
     */    
    private $fecha;            
    
    /**     
     * @ORM\Column(name="estado_aprobado", type="boolean")
     */    
    private $estadoAprobado = 0;     
    
    /**
     * @ORM\Column(name="nombreCorto", type="string")
     */
    private $nombreCorto;        
    
    /**
     * @ORM\Column(name="identificacion", type="integer")
     */
    private $identificacion;  

    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadExamen", inversedBy="examenesEntidadExamenRel")
     * @ORM\JoinColumn(name="codigo_entidad_examen_fk", referencedColumnName="codigo_entidad_examen_pk")
     */
    protected $entidadExamenRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuExamenTipo", inversedBy="examenesExamenTipoRel")
     * @ORM\JoinColumn(name="codigo_examen_tipo_fk", referencedColumnName="codigo_examen_tipo_pk")
     */
    protected $examenTipoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="examenesCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;
    
}
