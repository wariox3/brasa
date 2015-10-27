<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_capacitacion_detalle")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCapacitacionDetalleRepository")
 */
class RhuCapacitacionDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_capacitacion_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCapacitacionDetallePk;                    
    
    /**
     * @ORM\Column(name="codigo_capacitacion_fk", type="integer", nullable=true)
     */    
    private $codigoCapacitacionFk;   

    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=false)
     */         
    private $numeroIdentificacion;
    
    /**
     * @ORM\Column(name="nombre_corto", type="string", length=80, nullable=true)
     */    
    private $nombreCorto;            
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCapacitacion", inversedBy="capacitacionesDetallesCapacitacionRel")
     * @ORM\JoinColumn(name="codigo_capacitacion_fk", referencedColumnName="codigo_capacitacion_pk")
     */
    protected $capacitacionRel;



    /**
     * Get codigoCapacitacionDetallePk
     *
     * @return integer
     */
    public function getCodigoCapacitacionDetallePk()
    {
        return $this->codigoCapacitacionDetallePk;
    }

    /**
     * Set codigoCapacitacionFk
     *
     * @param integer $codigoCapacitacionFk
     *
     * @return RhuCapacitacionDetalle
     */
    public function setCodigoCapacitacionFk($codigoCapacitacionFk)
    {
        $this->codigoCapacitacionFk = $codigoCapacitacionFk;

        return $this;
    }

    /**
     * Get codigoCapacitacionFk
     *
     * @return integer
     */
    public function getCodigoCapacitacionFk()
    {
        return $this->codigoCapacitacionFk;
    }

    /**
     * Set numeroIdentificacion
     *
     * @param string $numeroIdentificacion
     *
     * @return RhuCapacitacionDetalle
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
     * Set nombreCorto
     *
     * @param string $nombreCorto
     *
     * @return RhuCapacitacionDetalle
     */
    public function setNombreCorto($nombreCorto)
    {
        $this->nombreCorto = $nombreCorto;

        return $this;
    }

    /**
     * Get nombreCorto
     *
     * @return string
     */
    public function getNombreCorto()
    {
        return $this->nombreCorto;
    }

    /**
     * Set capacitacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacion $capacitacionRel
     *
     * @return RhuCapacitacionDetalle
     */
    public function setCapacitacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuCapacitacion $capacitacionRel = null)
    {
        $this->capacitacionRel = $capacitacionRel;

        return $this;
    }

    /**
     * Get capacitacionRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacion
     */
    public function getCapacitacionRel()
    {
        return $this->capacitacionRel;
    }
}
