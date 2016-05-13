<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_proyecto")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurProyectoRepository")
 */
class TurProyecto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_proyecto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProyectoPk;        
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;                       
    
    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;            
    
    /**
     * @ORM\ManyToOne(targetEntity="TurCliente", inversedBy="proyectosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;          
    
}
