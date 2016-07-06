<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_cargo_supervigilancia")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCargoSupervigilanciaRepository")
 */
class RhuCargoSupervigilancia
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cargo_supervigilancia_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCargoSuperVigilanciaPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=150, nullable=true)
     */    
    private $nombre;
    
    
    

    /**
     * Get codigoCursoSuperVigilanciaPk
     *
     * @return integer
     */
    public function getCodigoCursoSuperVigilanciaPk()
    {
        return $this->codigoCursoSuperVigilanciaPk;
    }

    /**
     * Set codigoSupervigilanciaAlterno
     *
     * @param string $codigoSupervigilanciaAlterno
     *
     * @return RhuCursoSupervigilancia
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
     * @return RhuCursoSupervigilancia
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
     * Set vigencia
     *
     * @param string $vigencia
     *
     * @return RhuCursoSupervigilancia
     */
    public function setVigencia($vigencia)
    {
        $this->vigencia = $vigencia;

        return $this;
    }

    /**
     * Get vigencia
     *
     * @return string
     */
    public function getVigencia()
    {
        return $this->vigencia;
    }

    /**
     * Set codigoCargoSupervigilanciaFk
     *
     * @param integer $codigoCargoSupervigilanciaFk
     *
     * @return RhuCursoSupervigilancia
     */
    public function setCodigoCargoSupervigilanciaFk($codigoCargoSupervigilanciaFk)
    {
        $this->codigoCargoSupervigilanciaFk = $codigoCargoSupervigilanciaFk;

        return $this;
    }

    /**
     * Get codigoCargoSupervigilanciaFk
     *
     * @return integer
     */
    public function getCodigoCargoSupervigilanciaFk()
    {
        return $this->codigoCargoSupervigilanciaFk;
    }
}
