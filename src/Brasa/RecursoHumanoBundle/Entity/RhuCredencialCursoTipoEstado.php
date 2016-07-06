<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_credencial_curso_tipo_estado")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCredencialCursoTipoEstadoRepository")
 */
class RhuCredencialCursoTipoEstado
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_credencial_curso_tipo_estado_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCredencialCursoTipoEstadoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;
    
    


    /**
     * Get codigoCredencialCursoTipoEstadoPk
     *
     * @return integer
     */
    public function getCodigoCredencialCursoTipoEstadoPk()
    {
        return $this->codigoCredencialCursoTipoEstadoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuCredencialCursoTipoEstado
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }
}
