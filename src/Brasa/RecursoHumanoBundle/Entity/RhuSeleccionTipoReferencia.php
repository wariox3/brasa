<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_seleccion_tipo_referencia")
 */
class RhuSeleccionTipoReferencia
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_tipo_referencia_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSeleccionTipoReferenciaPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", nullable=true)
     */    
    private $nombre;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccionReferencia", mappedBy="seleccionReferenciaTipoRel")
     */
    protected $seleccionTiposReferenciasRel;

}
