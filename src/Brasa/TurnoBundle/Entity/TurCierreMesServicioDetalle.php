<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_cierre_mes_servicio_detalle")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurCierreMesServicioDetalleRepository")
 */
class TurCierreMesServicioDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cierre_mes_servicio_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCierreMesServicioDetallePk;         
    
    /**
     * @ORM\Column(name="codigo_cierre_mes_servicio_fk", type="integer", nullable=true)
     */    
    private $codigoCierreMesServicioFk;    
    
    /**
     * @ORM\Column(name="codigo_cierre_mes_fk", type="integer", nullable=true)
     */    
    private $codigoCierreMesFk;                 
    
    /**
     * @ORM\ManyToOne(targetEntity="TurCierreMesServicio", inversedBy="cierresMesServiciosDetallesCierreMesServicioRel")
     * @ORM\JoinColumn(name="codigo_cierre_mes_servicio_fk", referencedColumnName="codigo_cierre_mes_servicio_pk")
     */
    protected $cierreMesServicioRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="TurCierreMes", inversedBy="cierresMesServiciosDetallesCierreMesRel")
     * @ORM\JoinColumn(name="codigo_cierre_mes_fk", referencedColumnName="codigo_cierre_mes_pk")
     */
    protected $cierreMesRel;        


    /**
     * Get codigoCierreMesServicioDetallePk
     *
     * @return integer
     */
    public function getCodigoCierreMesServicioDetallePk()
    {
        return $this->codigoCierreMesServicioDetallePk;
    }

    /**
     * Set codigoCierreMesServicioFk
     *
     * @param integer $codigoCierreMesServicioFk
     *
     * @return TurCierreMesServicioDetalle
     */
    public function setCodigoCierreMesServicioFk($codigoCierreMesServicioFk)
    {
        $this->codigoCierreMesServicioFk = $codigoCierreMesServicioFk;

        return $this;
    }

    /**
     * Get codigoCierreMesServicioFk
     *
     * @return integer
     */
    public function getCodigoCierreMesServicioFk()
    {
        return $this->codigoCierreMesServicioFk;
    }

    /**
     * Set codigoCierreMesFk
     *
     * @param integer $codigoCierreMesFk
     *
     * @return TurCierreMesServicioDetalle
     */
    public function setCodigoCierreMesFk($codigoCierreMesFk)
    {
        $this->codigoCierreMesFk = $codigoCierreMesFk;

        return $this;
    }

    /**
     * Get codigoCierreMesFk
     *
     * @return integer
     */
    public function getCodigoCierreMesFk()
    {
        return $this->codigoCierreMesFk;
    }

    /**
     * Set cierreMesServicioRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCierreMesServicio $cierreMesServicioRel
     *
     * @return TurCierreMesServicioDetalle
     */
    public function setCierreMesServicioRel(\Brasa\TurnoBundle\Entity\TurCierreMesServicio $cierreMesServicioRel = null)
    {
        $this->cierreMesServicioRel = $cierreMesServicioRel;

        return $this;
    }

    /**
     * Get cierreMesServicioRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurCierreMesServicio
     */
    public function getCierreMesServicioRel()
    {
        return $this->cierreMesServicioRel;
    }

    /**
     * Set cierreMesRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCierreMes $cierreMesRel
     *
     * @return TurCierreMesServicioDetalle
     */
    public function setCierreMesRel(\Brasa\TurnoBundle\Entity\TurCierreMes $cierreMesRel = null)
    {
        $this->cierreMesRel = $cierreMesRel;

        return $this;
    }

    /**
     * Get cierreMesRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurCierreMes
     */
    public function getCierreMesRel()
    {
        return $this->cierreMesRel;
    }
}
