<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_requisito_concepto")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuRequisitoConceptoRepository")
 */
class RhuRequisitoConcepto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_requisito_concepto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRequisitoConceptoPk;                    
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuRequisitoDetalle", mappedBy="requisitoConceptoRel")
     */
    protected $requisitosDetallesRequisitoConceptoRel;    

    /**
     * Get codigoRequisitoConceptoPk
     *
     * @return integer
     */
    public function getCodigoRequisitoConceptoPk()
    {
        return $this->codigoRequisitoConceptoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuRequisitoConcepto
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
     * Constructor
     */
    public function __construct()
    {
        $this->requisitosDetallesRequisitoConceptoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add requisitosDetallesRequisitoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoDetalle $requisitosDetallesRequisitoConceptoRel
     *
     * @return RhuRequisitoConcepto
     */
    public function addRequisitosDetallesRequisitoConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuRequisitoDetalle $requisitosDetallesRequisitoConceptoRel)
    {
        $this->requisitosDetallesRequisitoConceptoRel[] = $requisitosDetallesRequisitoConceptoRel;

        return $this;
    }

    /**
     * Remove requisitosDetallesRequisitoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoDetalle $requisitosDetallesRequisitoConceptoRel
     */
    public function removeRequisitosDetallesRequisitoConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuRequisitoDetalle $requisitosDetallesRequisitoConceptoRel)
    {
        $this->requisitosDetallesRequisitoConceptoRel->removeElement($requisitosDetallesRequisitoConceptoRel);
    }

    /**
     * Get requisitosDetallesRequisitoConceptoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRequisitosDetallesRequisitoConceptoRel()
    {
        return $this->requisitosDetallesRequisitoConceptoRel;
    }
}
