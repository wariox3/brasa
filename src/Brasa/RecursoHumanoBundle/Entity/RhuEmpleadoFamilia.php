<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_empleado_familia")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEmpleadoFamiliaRepository")
 */
class RhuEmpleadoFamilia
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_empleado_familia_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEmpleadoFamiliaPk;
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */    
    private $codigoEmpleadoFk;
    
    /**
     * @ORM\Column(name="conyugue", type="string", length=100, nullable=true)
     */    
    private $conyugue;
    
    /**
     * @ORM\Column(name="codigo_eps_conyugue_fk", type="integer", nullable=true)
     */    
    private $epsConyugue;
    
    /**
     * @ORM\Column(name="codigo_caja_conyugue_fk", type="integer", nullable=true)
     */    
    private $cajaConyugue;
    
    /**
     * @ORM\Column(name="fecha_nacimiento_conyugue", type="date", nullable=true)
     */ 
    private $fechaNacimientoConyugue;
    
    /**
     * @ORM\Column(name="ocupacion_conyugue", type="string", length=100, nullable=true)
     */    
    private $ocupacionConyugue;
    
    /**
     * @ORM\Column(name="telefono_conyugue", type="string", length=15, nullable=true)
     */    
    private $telefonoConyugue;
    
    /**
     * @ORM\Column(name="madre", type="string", length=100, nullable=true)
     */    
    private $madre;
    
    /**
     * @ORM\Column(name="codigo_eps_madre_fk", type="integer", nullable=true)
     */    
    private $epsMadre;
    
    /**
     * @ORM\Column(name="codigo_caja_madre_fk", type="integer", nullable=true)
     */    
    private $cajaMadre;
    
    /**
     * @ORM\Column(name="fecha_nacimiento_madre", type="date", nullable=true)
     */ 
    private $fechaNacimientoMadre;
    
    /**
     * @ORM\Column(name="ocupacion_madre", type="string", length=100, nullable=true)
     */    
    private $ocupacionMadre;
    
    /**
     * @ORM\Column(name="telefono_madre", type="string", length=15, nullable=true)
     */    
    private $telefonoMadre;
    
    /**
     * @ORM\Column(name="padre", type="string", length=100, nullable=true)
     */    
    private $padre;
    
    /**
     * @ORM\Column(name="codigo_eps_padre_fk", type="integer", nullable=true)
     */    
    private $epsPadre;
    
    /**
     * @ORM\Column(name="codigo_caja_padre_fk", type="integer", nullable=true)
     */    
    private $cajaPadre;
    
    /**
     * @ORM\Column(name="fecha_nacimiento_padre", type="date", nullable=true)
     */ 
    private $fechaNacimientoPadre;
    
    /**
     * @ORM\Column(name="ocupacion_padre", type="string", length=100, nullable=true)
     */    
    private $ocupacionPadre;
    
    /**
     * @ORM\Column(name="telefono_padre", type="string", length=15, nullable=true)
     */    
    private $telefonoPadre;
    
}
