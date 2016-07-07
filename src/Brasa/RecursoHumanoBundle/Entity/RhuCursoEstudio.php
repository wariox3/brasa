<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_curso_estudio")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCursoEstudioRepository")
 */
class RhuCursoEstudio
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_curso_estudio_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCursoEstudioPk;
    
    /**
     * @ORM\Column(name="codigo_supervigilancia_alterno", type="string", length=10, nullable=true)
     */    
    private $codigoSupervigilanciaAlterno;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=150, nullable=true)
     */    
    private $nombre;
    
    /**
     * @ORM\Column(name="cargo", type="string", length=50, nullable=true)
     */    
    private $cargo;
    
    

    /**
     * Get codigoCursoEstudioPk
     *
     * @return integer
     */
    public function getCodigoCursoEstudioPk()
    {
        return $this->codigoCursoEstudioPk;
    }

    /**
     * Set codigoSupervigilanciaAlterno
     *
     * @param string $codigoSupervigilanciaAlterno
     *
     * @return RhuCursoEstudio
     */
    public function setCodigoSupervigilanciaAlterno($codigoSupervigilanciaAlterno)
    {
        $this->codigoSupervigilanciaAlterno = $codigoSupervigilanciaAlterno;

        return $this;
    }

    /**
     * Get codigoSupervigilanciaAlterno
     *
     * @return string
     */
    public function getCodigoSupervigilanciaAlterno()
    {
        return $this->codigoSupervigilanciaAlterno;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuCursoEstudio
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

    /**
     * Set cargo
     *
     * @param string $cargo
     *
     * @return RhuCursoEstudio
     */
    public function setCargo($cargo)
    {
        $this->cargo = $cargo;

        return $this;
    }

    /**
     * Get cargo
     *
     * @return string
     */
    public function getCargo()
    {
        return $this->cargo;
    }
}
