<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_pago_detalle")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPagoDetalleRepository")
 */
class RhuPagoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPagoDetallePk;
    
    /**
     * @ORM\Column(name="codigo_pago_fk", type="integer", nullable=true)
     */    
    private $codigoPagoFk;      
    
    /**
     * @ORM\Column(name="vr_hora", type="float")
     */
    private $vr_hora = 0;     
    
    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vr_total = 0;    
    


    /**
     * Get codigoPagoDetallePk
     *
     * @return integer
     */
    public function getCodigoPagoDetallePk()
    {
        return $this->codigoPagoDetallePk;
    }

    /**
     * Set codigoPagoFk
     *
     * @param integer $codigoPagoFk
     *
     * @return RhuPagoDetalle
     */
    public function setCodigoPagoFk($codigoPagoFk)
    {
        $this->codigoPagoFk = $codigoPagoFk;

        return $this;
    }

    /**
     * Get codigoPagoFk
     *
     * @return integer
     */
    public function getCodigoPagoFk()
    {
        return $this->codigoPagoFk;
    }

    /**
     * Set vrHora
     *
     * @param float $vrHora
     *
     * @return RhuPagoDetalle
     */
    public function setVrHora($vrHora)
    {
        $this->vr_hora = $vrHora;

        return $this;
    }

    /**
     * Get vrHora
     *
     * @return float
     */
    public function getVrHora()
    {
        return $this->vr_hora;
    }

    /**
     * Set vrTotal
     *
     * @param float $vrTotal
     *
     * @return RhuPagoDetalle
     */
    public function setVrTotal($vrTotal)
    {
        $this->vr_total = $vrTotal;

        return $this;
    }

    /**
     * Get vrTotal
     *
     * @return float
     */
    public function getVrTotal()
    {
        return $this->vr_total;
    }
}
