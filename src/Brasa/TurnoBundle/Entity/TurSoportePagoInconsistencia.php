<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_soporte_pago_inconsistencia")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurSoportePagoInconsistenciaRepository")
 */
class TurSoportePagoInconsistencia
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_soporte_pago_inconsistencia_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSoportePagoInconsistenciaPk;         
    
    /**
     * @ORM\Column(name="detalle", type="string", length=200, nullable=true)
     */    
    private $detalle;                    

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=true)
     */    
    private $numeroIdentificacion;      
    
    /**
     * @ORM\Column(name="codigo_recurso", type="integer", nullable=true)
     */    
    private $codigoRecurso;  
    

    /**
     * Get codigoSoportePagoInconsistenciaPk
     *
     * @return integer
     */
    public function getCodigoSoportePagoInconsistenciaPk()
    {
        return $this->codigoSoportePagoInconsistenciaPk;
    }

    /**
     * Set detalle
     *
     * @param string $detalle
     *
     * @return TurSoportePagoInconsistencia
     */
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;

        return $this;
    }

    /**
     * Get detalle
     *
     * @return string
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * Set numeroIdentificacion
     *
     * @param string $numeroIdentificacion
     *
     * @return TurSoportePagoInconsistencia
     */
    public function setNumeroIdentificacion($numeroIdentificacion)
    {
        $this->numeroIdentificacion = $numeroIdentificacion;

        return $this;
    }

    /**
     * Get numeroIdentificacion
     *
     * @return string
     */
    public function getNumeroIdentificacion()
    {
        return $this->numeroIdentificacion;
    }

    /**
     * Set codigoRecurso
     *
     * @param integer $codigoRecurso
     *
     * @return TurSoportePagoInconsistencia
     */
    public function setCodigoRecurso($codigoRecurso)
    {
        $this->codigoRecurso = $codigoRecurso;

        return $this;
    }

    /**
     * Get codigoRecurso
     *
     * @return integer
     */
    public function getCodigoRecurso()
    {
        return $this->codigoRecurso;
    }
}
