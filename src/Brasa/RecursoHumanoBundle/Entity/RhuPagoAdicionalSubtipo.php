<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_pago_adicional_subtipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPagoAdicionalSubtipoRepository")
 */
class RhuPagoAdicionalSubtipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_adicional_subtipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPagoAdicionalSubtipoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;         

    /**
     * @ORM\Column(name="detalle", type="string", length=80, nullable=true)
     */    
    private $detalle;    
    
    /**
     * @ORM\Column(name="codigo_pago_adicional_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoPagoAdicionalTipoFk;     
    
    /**
     * @ORM\Column(name="codigo_pago_concepto_fk", type="integer", nullable=true)
     */    
    private $codigoPagoConceptoFk;
    
    /**
     * @ORM\Column(name="porcentaje", type="float")
     */
    private $porcentaje = 0;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuPagoConcepto", inversedBy="pagosAdicionalesSubtiposPagoConceptoRel")
     * @ORM\JoinColumn(name="codigo_pago_concepto_fk", referencedColumnName="codigo_pago_concepto_pk")
     */
    protected $pagoConceptoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuPagoAdicionalTipo", inversedBy="pagosAdicionalesTiposPagoAdicionalSubtipoRel")
     * @ORM\JoinColumn(name="codigo_pago_adicional_tipo_fk", referencedColumnName="codigo_pago_adicional_tipo_pk")
     */
    protected $pagoAdicionalTipoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuPagoAdicional", mappedBy="pagoAdicionalSubtipoRel")
     */
    protected $pagosAdicionalesPagoAdicionalSubtipoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuLicencia", mappedBy="pagoAdicionalSubtipoRel")
     */
    protected $licenciasPagoAdicionalSubtipoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuIncapacidad", mappedBy="pagoAdicionalSubtipoRel")
     */
    protected $incapacidadesPagoAdicionalSubtipoRel;    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pagosAdicionalesPagoAdicionalSubtipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPagoAdicionalSubtipoPk
     *
     * @return integer
     */
    public function getCodigoPagoAdicionalSubtipoPk()
    {
        return $this->codigoPagoAdicionalSubtipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuPagoAdicionalSubtipo
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
     * Set detalle
     *
     * @param string $detalle
     *
     * @return RhuPagoAdicionalSubtipo
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
     * Set codigoPagoAdicionalTipoFk
     *
     * @param integer $codigoPagoAdicionalTipoFk
     *
     * @return RhuPagoAdicionalSubtipo
     */
    public function setCodigoPagoAdicionalTipoFk($codigoPagoAdicionalTipoFk)
    {
        $this->codigoPagoAdicionalTipoFk = $codigoPagoAdicionalTipoFk;

        return $this;
    }

    /**
     * Get codigoPagoAdicionalTipoFk
     *
     * @return integer
     */
    public function getCodigoPagoAdicionalTipoFk()
    {
        return $this->codigoPagoAdicionalTipoFk;
    }

    /**
     * Set codigoPagoConceptoFk
     *
     * @param integer $codigoPagoConceptoFk
     *
     * @return RhuPagoAdicionalSubtipo
     */
    public function setCodigoPagoConceptoFk($codigoPagoConceptoFk)
    {
        $this->codigoPagoConceptoFk = $codigoPagoConceptoFk;

        return $this;
    }

    /**
     * Get codigoPagoConceptoFk
     *
     * @return integer
     */
    public function getCodigoPagoConceptoFk()
    {
        return $this->codigoPagoConceptoFk;
    }

    /**
     * Set porcentaje
     *
     * @param float $porcentaje
     *
     * @return RhuPagoAdicionalSubtipo
     */
    public function setPorcentaje($porcentaje)
    {
        $this->porcentaje = $porcentaje;

        return $this;
    }

    /**
     * Get porcentaje
     *
     * @return float
     */
    public function getPorcentaje()
    {
        return $this->porcentaje;
    }

    /**
     * Set pagoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto $pagoConceptoRel
     *
     * @return RhuPagoAdicionalSubtipo
     */
    public function setPagoConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto $pagoConceptoRel = null)
    {
        $this->pagoConceptoRel = $pagoConceptoRel;

        return $this;
    }

    /**
     * Get pagoConceptoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto
     */
    public function getPagoConceptoRel()
    {
        return $this->pagoConceptoRel;
    }

    /**
     * Set pagoAdicionalTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicionalTipo $pagoAdicionalTipoRel
     *
     * @return RhuPagoAdicionalSubtipo
     */
    public function setPagoAdicionalTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicionalTipo $pagoAdicionalTipoRel = null)
    {
        $this->pagoAdicionalTipoRel = $pagoAdicionalTipoRel;

        return $this;
    }

    /**
     * Get pagoAdicionalTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicionalTipo
     */
    public function getPagoAdicionalTipoRel()
    {
        return $this->pagoAdicionalTipoRel;
    }

    /**
     * Add pagosAdicionalesPagoAdicionalSubtipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional $pagosAdicionalesPagoAdicionalSubtipoRel
     *
     * @return RhuPagoAdicionalSubtipo
     */
    public function addPagosAdicionalesPagoAdicionalSubtipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional $pagosAdicionalesPagoAdicionalSubtipoRel)
    {
        $this->pagosAdicionalesPagoAdicionalSubtipoRel[] = $pagosAdicionalesPagoAdicionalSubtipoRel;

        return $this;
    }

    /**
     * Remove pagosAdicionalesPagoAdicionalSubtipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional $pagosAdicionalesPagoAdicionalSubtipoRel
     */
    public function removePagosAdicionalesPagoAdicionalSubtipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional $pagosAdicionalesPagoAdicionalSubtipoRel)
    {
        $this->pagosAdicionalesPagoAdicionalSubtipoRel->removeElement($pagosAdicionalesPagoAdicionalSubtipoRel);
    }

    /**
     * Get pagosAdicionalesPagoAdicionalSubtipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosAdicionalesPagoAdicionalSubtipoRel()
    {
        return $this->pagosAdicionalesPagoAdicionalSubtipoRel;
    }

    /**
     * Add licenciasPagoAdicionalSubtipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLicencia $licenciasPagoAdicionalSubtipoRel
     *
     * @return RhuPagoAdicionalSubtipo
     */
    public function addLicenciasPagoAdicionalSubtipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuLicencia $licenciasPagoAdicionalSubtipoRel)
    {
        $this->licenciasPagoAdicionalSubtipoRel[] = $licenciasPagoAdicionalSubtipoRel;

        return $this;
    }

    /**
     * Remove licenciasPagoAdicionalSubtipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLicencia $licenciasPagoAdicionalSubtipoRel
     */
    public function removeLicenciasPagoAdicionalSubtipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuLicencia $licenciasPagoAdicionalSubtipoRel)
    {
        $this->licenciasPagoAdicionalSubtipoRel->removeElement($licenciasPagoAdicionalSubtipoRel);
    }

    /**
     * Get licenciasPagoAdicionalSubtipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLicenciasPagoAdicionalSubtipoRel()
    {
        return $this->licenciasPagoAdicionalSubtipoRel;
    }

    /**
     * Add incapacidadesPagoAdicionalSubtipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesPagoAdicionalSubtipoRel
     *
     * @return RhuPagoAdicionalSubtipo
     */
    public function addIncapacidadesPagoAdicionalSubtipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesPagoAdicionalSubtipoRel)
    {
        $this->incapacidadesPagoAdicionalSubtipoRel[] = $incapacidadesPagoAdicionalSubtipoRel;

        return $this;
    }

    /**
     * Remove incapacidadesPagoAdicionalSubtipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesPagoAdicionalSubtipoRel
     */
    public function removeIncapacidadesPagoAdicionalSubtipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesPagoAdicionalSubtipoRel)
    {
        $this->incapacidadesPagoAdicionalSubtipoRel->removeElement($incapacidadesPagoAdicionalSubtipoRel);
    }

    /**
     * Get incapacidadesPagoAdicionalSubtipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIncapacidadesPagoAdicionalSubtipoRel()
    {
        return $this->incapacidadesPagoAdicionalSubtipoRel;
    }
}
