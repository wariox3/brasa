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
