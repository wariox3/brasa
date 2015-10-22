<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_requisito_cargo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuRequisitoCargoRepository")
 */
class RhuRequisitoCargo
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_requisito_cargo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRequisitoCargoPk;    
    
    /**
     * @ORM\Column(name="codigo_requisito_concepto_pk", type="integer")
     */
    private $codigoRequisitoConceptoPk;                    

    /**
     * @ORM\Column(name="codigo_cargo_pk", type="integer")
     */
    private $codigoCargoPk; 
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuRequisitoConcepto", inversedBy="requisitosCargosRequisitoConceptoRel")
     * @ORM\JoinColumn(name="codigo_requisito_concepto_fk", referencedColumnName="codigo_requisito_concepto_pk")
     */
    protected $requisitoConceptoRel;  
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCargo", inversedBy="requisitosCargosCargoRel")
     * @ORM\JoinColumn(name="codigo_cargo_fk", referencedColumnName="codigo_cargo_pk")
     */
    protected $cargoRel;    
    
    

    /**
     * Get codigoRequisitoCargoPk
     *
     * @return integer
     */
    public function getCodigoRequisitoCargoPk()
    {
        return $this->codigoRequisitoCargoPk;
    }

    /**
     * Set codigoRequisitoConceptoPk
     *
     * @param integer $codigoRequisitoConceptoPk
     *
     * @return RhuRequisitoCargo
     */
    public function setCodigoRequisitoConceptoPk($codigoRequisitoConceptoPk)
    {
        $this->codigoRequisitoConceptoPk = $codigoRequisitoConceptoPk;

        return $this;
    }

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
     * Set codigoCargoPk
     *
     * @param integer $codigoCargoPk
     *
     * @return RhuRequisitoCargo
     */
    public function setCodigoCargoPk($codigoCargoPk)
    {
        $this->codigoCargoPk = $codigoCargoPk;

        return $this;
    }

    /**
     * Get codigoCargoPk
     *
     * @return integer
     */
    public function getCodigoCargoPk()
    {
        return $this->codigoCargoPk;
    }

    /**
     * Set requisitoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoConcepto $requisitoConceptoRel
     *
     * @return RhuRequisitoCargo
     */
    public function setRequisitoConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuRequisitoConcepto $requisitoConceptoRel = null)
    {
        $this->requisitoConceptoRel = $requisitoConceptoRel;

        return $this;
    }

    /**
     * Get requisitoConceptoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoConcepto
     */
    public function getRequisitoConceptoRel()
    {
        return $this->requisitoConceptoRel;
    }

    /**
     * Set cargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCargo $cargoRel
     *
     * @return RhuRequisitoCargo
     */
    public function setCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCargo $cargoRel = null)
    {
        $this->cargoRel = $cargoRel;

        return $this;
    }

    /**
     * Get cargoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCargo
     */
    public function getCargoRel()
    {
        return $this->cargoRel;
    }
}
