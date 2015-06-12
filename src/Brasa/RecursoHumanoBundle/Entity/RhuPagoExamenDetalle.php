<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_pago_examen_detalle")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPagoExamenDetalleRepository")
 */
class RhuPagoExamenDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_examen_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPagoExamenDetallePk;
    
    /**
     * @ORM\Column(name="codigo_encabezado_pago_examen_fk", type="integer", nullable=true)
     */    
    private $codigoEncabezadoPagoExamenFk;
    
    /**
     * @ORM\Column(name="codigo_examen_fk", type="integer", nullable=true)
     */    
    private $codigoExamenFk;
    
    /**
     * @ORM\Column(name="codigo_examen_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoExamenTipoFk;
    
    /**
     * @ORM\Column(name="precio", type="integer")
     */
    private $precio;  

    /**
     * @ORM\ManyToOne(targetEntity="RhuExamen", inversedBy="examenespagoDetalleRel")
     * @ORM\JoinColumn(name="codigo_examen_fk", referencedColumnName="codigo_examen_pk")
     */
    protected $detalleExamenRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuEncabezadoPagoExamen", inversedBy="pagosExamenDetalleRel")
     * @ORM\JoinColumn(name="codigo_encabezado_pago_examen_fk", referencedColumnName="codigo_encabezado_pago_examen_pk")
     */
    protected $detalleEncabezadoPagoRel;
   

    /**
     * Get codigoPagoExamenDetallePk
     *
     * @return integer
     */
    public function getCodigoPagoExamenDetallePk()
    {
        return $this->codigoPagoExamenDetallePk;
    }

    /**
     * Set codigoEncabezadoPagoExamenFk
     *
     * @param integer $codigoEncabezadoPagoExamenFk
     *
     * @return RhuPagoExamenDetalle
     */
    public function setCodigoEncabezadoPagoExamenFk($codigoEncabezadoPagoExamenFk)
    {
        $this->codigoEncabezadoPagoExamenFk = $codigoEncabezadoPagoExamenFk;

        return $this;
    }

    /**
     * Get codigoEncabezadoPagoExamenFk
     *
     * @return integer
     */
    public function getCodigoEncabezadoPagoExamenFk()
    {
        return $this->codigoEncabezadoPagoExamenFk;
    }

    /**
     * Set codigoExamenFk
     *
     * @param integer $codigoExamenFk
     *
     * @return RhuPagoExamenDetalle
     */
    public function setCodigoExamenFk($codigoExamenFk)
    {
        $this->codigoExamenFk = $codigoExamenFk;

        return $this;
    }

    /**
     * Get codigoExamenFk
     *
     * @return integer
     */
    public function getCodigoExamenFk()
    {
        return $this->codigoExamenFk;
    }

    /**
     * Set codigoExamenTipoFk
     *
     * @param integer $codigoExamenTipoFk
     *
     * @return RhuPagoExamenDetalle
     */
    public function setCodigoExamenTipoFk($codigoExamenTipoFk)
    {
        $this->codigoExamenTipoFk = $codigoExamenTipoFk;

        return $this;
    }

    /**
     * Get codigoExamenTipoFk
     *
     * @return integer
     */
    public function getCodigoExamenTipoFk()
    {
        return $this->codigoExamenTipoFk;
    }

    /**
     * Set precio
     *
     * @param integer $precio
     *
     * @return RhuPagoExamenDetalle
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return integer
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set detalleExamenRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamen $detalleExamenRel
     *
     * @return RhuPagoExamenDetalle
     */
    public function setDetalleExamenRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamen $detalleExamenRel = null)
    {
        $this->detalleExamenRel = $detalleExamenRel;

        return $this;
    }

    /**
     * Get detalleExamenRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuExamen
     */
    public function getDetalleExamenRel()
    {
        return $this->detalleExamenRel;
    }

    /**
     * Set detalleEncabezadoPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEncabezadoPagoExamen $detalleEncabezadoPagoRel
     *
     * @return RhuPagoExamenDetalle
     */
    public function setDetalleEncabezadoPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEncabezadoPagoExamen $detalleEncabezadoPagoRel = null)
    {
        $this->detalleEncabezadoPagoRel = $detalleEncabezadoPagoRel;

        return $this;
    }

    /**
     * Get detalleEncabezadoPagoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEncabezadoPagoExamen
     */
    public function getDetalleEncabezadoPagoRel()
    {
        return $this->detalleEncabezadoPagoRel;
    }
}
