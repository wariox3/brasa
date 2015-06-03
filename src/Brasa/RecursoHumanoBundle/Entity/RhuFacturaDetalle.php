<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_factura_detalle")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuFacturaDetalleRepository")
 */
class RhuFacturaDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_factura_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoFacturaDetallePk;
    
    /**
     * @ORM\Column(name="codigo_factura_fk", type="integer", nullable=true)
     */    
    private $codigoFacturaFk;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuFactura", inversedBy="facturasDetallesFacturaRel")
     * @ORM\JoinColumn(name="codigo_factura_fk", referencedColumnName="codigo_factura_pk")
     */
    protected $facturaRel;    
    


    /**
     * Get codigoFacturaDetallePk
     *
     * @return integer
     */
    public function getCodigoFacturaDetallePk()
    {
        return $this->codigoFacturaDetallePk;
    }

    /**
     * Set codigoFacturaFk
     *
     * @param integer $codigoFacturaFk
     *
     * @return RhuFacturaDetalle
     */
    public function setCodigoFacturaFk($codigoFacturaFk)
    {
        $this->codigoFacturaFk = $codigoFacturaFk;

        return $this;
    }

    /**
     * Get codigoFacturaFk
     *
     * @return integer
     */
    public function getCodigoFacturaFk()
    {
        return $this->codigoFacturaFk;
    }

    /**
     * Set facturaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFactura $facturaRel
     *
     * @return RhuFacturaDetalle
     */
    public function setFacturaRel(\Brasa\RecursoHumanoBundle\Entity\RhuFactura $facturaRel = null)
    {
        $this->facturaRel = $facturaRel;

        return $this;
    }

    /**
     * Get facturaRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuFactura
     */
    public function getFacturaRel()
    {
        return $this->facturaRel;
    }
}
