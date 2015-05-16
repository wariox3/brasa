<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_pago_adicional_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPagoAdicionalTipoRepository")
 */
class RhuPagoAdicionalTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_adicional_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPagoAdicionalTipoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;         

    /**
     * @ORM\OneToMany(targetEntity="RhuPagoAdicionalSubtipo", mappedBy="pagoAdicionalTipoRel")
     */
    protected $pagosAdicionalesTiposPagoAdicionalSubtipoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuPagoAdicional", mappedBy="pagoAdicionalTipoRel")
     */
    protected $pagosAdicionalesPagoAdicionalTipoRel;    

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pagosAdicionalesTiposPagoAdicionalSubtipoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pagosAdicionalesPagoAdicionalTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPagoAdicionalTipoPk
     *
     * @return integer
     */
    public function getCodigoPagoAdicionalTipoPk()
    {
        return $this->codigoPagoAdicionalTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuPagoAdicionalTipo
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
     * Add pagosAdicionalesTiposPagoAdicionalSubtipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicionalSubtipo $pagosAdicionalesTiposPagoAdicionalSubtipoRel
     *
     * @return RhuPagoAdicionalTipo
     */
    public function addPagosAdicionalesTiposPagoAdicionalSubtipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicionalSubtipo $pagosAdicionalesTiposPagoAdicionalSubtipoRel)
    {
        $this->pagosAdicionalesTiposPagoAdicionalSubtipoRel[] = $pagosAdicionalesTiposPagoAdicionalSubtipoRel;

        return $this;
    }

    /**
     * Remove pagosAdicionalesTiposPagoAdicionalSubtipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicionalSubtipo $pagosAdicionalesTiposPagoAdicionalSubtipoRel
     */
    public function removePagosAdicionalesTiposPagoAdicionalSubtipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicionalSubtipo $pagosAdicionalesTiposPagoAdicionalSubtipoRel)
    {
        $this->pagosAdicionalesTiposPagoAdicionalSubtipoRel->removeElement($pagosAdicionalesTiposPagoAdicionalSubtipoRel);
    }

    /**
     * Get pagosAdicionalesTiposPagoAdicionalSubtipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosAdicionalesTiposPagoAdicionalSubtipoRel()
    {
        return $this->pagosAdicionalesTiposPagoAdicionalSubtipoRel;
    }

    /**
     * Add pagosAdicionalesPagoAdicionalTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional $pagosAdicionalesPagoAdicionalTipoRel
     *
     * @return RhuPagoAdicionalTipo
     */
    public function addPagosAdicionalesPagoAdicionalTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional $pagosAdicionalesPagoAdicionalTipoRel)
    {
        $this->pagosAdicionalesPagoAdicionalTipoRel[] = $pagosAdicionalesPagoAdicionalTipoRel;

        return $this;
    }

    /**
     * Remove pagosAdicionalesPagoAdicionalTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional $pagosAdicionalesPagoAdicionalTipoRel
     */
    public function removePagosAdicionalesPagoAdicionalTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional $pagosAdicionalesPagoAdicionalTipoRel)
    {
        $this->pagosAdicionalesPagoAdicionalTipoRel->removeElement($pagosAdicionalesPagoAdicionalTipoRel);
    }

    /**
     * Get pagosAdicionalesPagoAdicionalTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosAdicionalesPagoAdicionalTipoRel()
    {
        return $this->pagosAdicionalesPagoAdicionalTipoRel;
    }
}
