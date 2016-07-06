<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_credencial_curso_tipo_no_valido")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCredencialCursoTipoNoValidoRepository")
 */
class RhuCredencialCursoTipoNoValido
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_credencial_curso_tipo_no_valido_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCredencialCursoTipoNoValidoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;
    
    

    /**
     * Get codigoCredencialCursoTipoNoValidoPk
     *
     * @return integer
     */
    public function getCodigoCredencialCursoTipoNoValidoPk()
    {
        return $this->codigoCredencialCursoTipoNoValidoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuCredencialCursoTipoNoValido
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
