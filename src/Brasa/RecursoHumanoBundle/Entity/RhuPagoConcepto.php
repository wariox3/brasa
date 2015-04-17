<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_pago_concepto")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPagoConceptoRepository")
 */
class RhuPagoConcepto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_concepto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPagoConceptoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;    
    
    /**
     * @ORM\Column(name="codigo_pago_concepto_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoPagoConceptoTipoFk;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuContratoDetalle", mappedBy="pagoConceptoRel")
     */
    protected $pagoConceptosContratoDetalleRel;    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pagoConceptosContratoDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPagoConceptoPk
     *
     * @return integer
     */
    public function getCodigoPagoConceptoPk()
    {
        return $this->codigoPagoConceptoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuPagoConcepto
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
     * Set codigoPagoConceptoTipoFk
     *
     * @param integer $codigoPagoConceptoTipoFk
     *
     * @return RhuPagoConcepto
     */
    public function setCodigoPagoConceptoTipoFk($codigoPagoConceptoTipoFk)
    {
        $this->codigoPagoConceptoTipoFk = $codigoPagoConceptoTipoFk;

        return $this;
    }

    /**
     * Get codigoPagoConceptoTipoFk
     *
     * @return integer
     */
    public function getCodigoPagoConceptoTipoFk()
    {
        return $this->codigoPagoConceptoTipoFk;
    }

    /**
     * Add pagoConceptosContratoDetalleRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContratoDetalle $pagoConceptosContratoDetalleRel
     *
     * @return RhuPagoConcepto
     */
    public function addPagoConceptosContratoDetalleRel(\Brasa\RecursoHumanoBundle\Entity\RhuContratoDetalle $pagoConceptosContratoDetalleRel)
    {
        $this->pagoConceptosContratoDetalleRel[] = $pagoConceptosContratoDetalleRel;

        return $this;
    }

    /**
     * Remove pagoConceptosContratoDetalleRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContratoDetalle $pagoConceptosContratoDetalleRel
     */
    public function removePagoConceptosContratoDetalleRel(\Brasa\RecursoHumanoBundle\Entity\RhuContratoDetalle $pagoConceptosContratoDetalleRel)
    {
        $this->pagoConceptosContratoDetalleRel->removeElement($pagoConceptosContratoDetalleRel);
    }

    /**
     * Get pagoConceptosContratoDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagoConceptosContratoDetalleRel()
    {
        return $this->pagoConceptosContratoDetalleRel;
    }
}
