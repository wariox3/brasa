<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_entidad_salud")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEntidadSaludRepository")
 */
class RhuEntidadSalud
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_entidad_salud_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEntidadSaludPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=120, nullable=true)
     */    
    private $nombre;    
    
    /**
     * @ORM\Column(name="nit", type="string", length=10, nullable=true)
     */    
    private $nit;    
    
    /**
     * @ORM\Column(name="direccion", type="string", length=80, nullable=true)
     */    
    private $direccion;    
    
    /**
     * @ORM\Column(name="telefono", type="string", length=15, nullable=true)
     */    
    private $telefono; 
    
    /**
     * @ORM\Column(name="codigo_interface", type="string", length=20, nullable=true)
     */    
    private $codigoInterface;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="entidadSaludRel")
     */
    protected $empleadosEntidadSaludRel;    

    
    
}
