<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_encabezado_pago_examen")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEncabezadoPagoExamenRepository")
 */
class RhuEncabezadoPagoExamen
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_encabezado_pago_examen_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEncabezadoPagoExamenPk;
    
    /**
     * @ORM\Column(name="codigo_entidad_examen_fk", type="integer", nullable=true)
     */    
    private $codigoEntidadExamenFk;                 
    
    /**
     * @ORM\Column(name="total", type="integer")
     */
    private $total = 0;  

    /**
     * @ORM\OneToMany(targetEntity="RhuEntidadExamen", mappedBy="entidadExamenRel")
     */
    protected $encabezadoEntidadExamenRel;
    
       /**
     * @ORM\OneToMany(targetEntity="RhuPagoExamenDetalle", mappedBy="pagosExamenDetalleRel")
     */
    protected $detalleEncabezadoPagoRel;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->encabezadoEntidadExamenRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->detalleEncabezadoPagoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoEncabezadoPagoExamenPk
     *
     * @return integer
     */
    public function getCodigoEncabezadoPagoExamenPk()
    {
        return $this->codigoEncabezadoPagoExamenPk;
    }

    /**
     * Set codigoEntidadExamenFk
     *
     * @param integer $codigoEntidadExamenFk
     *
     * @return RhuEncabezadoPagoExamen
     */
    public function setCodigoEntidadExamenFk($codigoEntidadExamenFk)
    {
        $this->codigoEntidadExamenFk = $codigoEntidadExamenFk;

        return $this;
    }

    /**
     * Get codigoEntidadExamenFk
     *
     * @return integer
     */
    public function getCodigoEntidadExamenFk()
    {
        return $this->codigoEntidadExamenFk;
    }

    /**
     * Set total
     *
     * @param integer $total
     *
     * @return RhuEncabezadoPagoExamen
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Add encabezadoEntidadExamenRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEntidadExamen $encabezadoEntidadExamenRel
     *
     * @return RhuEncabezadoPagoExamen
     */
    public function addEncabezadoEntidadExamenRel(\Brasa\RecursoHumanoBundle\Entity\RhuEntidadExamen $encabezadoEntidadExamenRel)
    {
        $this->encabezadoEntidadExamenRel[] = $encabezadoEntidadExamenRel;

        return $this;
    }

    /**
     * Remove encabezadoEntidadExamenRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEntidadExamen $encabezadoEntidadExamenRel
     */
    public function removeEncabezadoEntidadExamenRel(\Brasa\RecursoHumanoBundle\Entity\RhuEntidadExamen $encabezadoEntidadExamenRel)
    {
        $this->encabezadoEntidadExamenRel->removeElement($encabezadoEntidadExamenRel);
    }

    /**
     * Get encabezadoEntidadExamenRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEncabezadoEntidadExamenRel()
    {
        return $this->encabezadoEntidadExamenRel;
    }

    /**
     * Add detalleEncabezadoPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoExamenDetalle $detalleEncabezadoPagoRel
     *
     * @return RhuEncabezadoPagoExamen
     */
    public function addDetalleEncabezadoPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoExamenDetalle $detalleEncabezadoPagoRel)
    {
        $this->detalleEncabezadoPagoRel[] = $detalleEncabezadoPagoRel;

        return $this;
    }

    /**
     * Remove detalleEncabezadoPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoExamenDetalle $detalleEncabezadoPagoRel
     */
    public function removeDetalleEncabezadoPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoExamenDetalle $detalleEncabezadoPagoRel)
    {
        $this->detalleEncabezadoPagoRel->removeElement($detalleEncabezadoPagoRel);
    }

    /**
     * Get detalleEncabezadoPagoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDetalleEncabezadoPagoRel()
    {
        return $this->detalleEncabezadoPagoRel;
    }
}
