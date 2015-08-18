<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_vacacion_credito")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuVacacionCreditoRepository")
 */
class RhuVacacionCredito
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_vacacion_credito_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoVacacionCreditoPk;
    
    /**
     * @ORM\Column(name="codigo_vacacion_fk", type="integer", nullable=true)
     */    
    private $codigoVacacionFk;
    
    /**
     * @ORM\Column(name="codigo_credito_fk", type="integer", nullable=true)
     */    
    private $codigoCreditoFk;
    
    /**
     * @ORM\Column(name="vr_deduccion", type="float")
     */
    private $vrDeduccion = 0;         
    
    /**
     * @ORM\Column(name="detalle", type="string", length=250, nullable=true)
     */    
    private $detalle;     
        
    /**
     * @ORM\ManyToOne(targetEntity="RhuVacacion", inversedBy="VacacionesCreditosVacacionRel")
     * @ORM\JoinColumn(name="codigo_vacacion_fk", referencedColumnName="codigo_vacacion_pk")
     */
    protected $vacacionRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuCredito", inversedBy="VacacionesCreditosCreditoRel")
     * @ORM\JoinColumn(name="codigo_credito_fk", referencedColumnName="codigo_credito_pk")
     */
    protected $creditoRel;    
    
    

    /**
     * Get codigoVacacionCreditoPk
     *
     * @return integer
     */
    public function getCodigoVacacionCreditoPk()
    {
        return $this->codigoVacacionCreditoPk;
    }

    /**
     * Set codigoVacacionFk
     *
     * @param integer $codigoVacacionFk
     *
     * @return RhuVacacionCredito
     */
    public function setCodigoVacacionFk($codigoVacacionFk)
    {
        $this->codigoVacacionFk = $codigoVacacionFk;

        return $this;
    }

    /**
     * Get codigoVacacionFk
     *
     * @return integer
     */
    public function getCodigoVacacionFk()
    {
        return $this->codigoVacacionFk;
    }

    /**
     * Set codigoCreditoFk
     *
     * @param integer $codigoCreditoFk
     *
     * @return RhuVacacionCredito
     */
    public function setCodigoCreditoFk($codigoCreditoFk)
    {
        $this->codigoCreditoFk = $codigoCreditoFk;

        return $this;
    }

    /**
     * Get codigoCreditoFk
     *
     * @return integer
     */
    public function getCodigoCreditoFk()
    {
        return $this->codigoCreditoFk;
    }

    /**
     * Set vrDeduccion
     *
     * @param float $vrDeduccion
     *
     * @return RhuVacacionCredito
     */
    public function setVrDeduccion($vrDeduccion)
    {
        $this->vrDeduccion = $vrDeduccion;

        return $this;
    }

    /**
     * Get vrDeduccion
     *
     * @return float
     */
    public function getVrDeduccion()
    {
        return $this->vrDeduccion;
    }

    /**
     * Set detalle
     *
     * @param string $detalle
     *
     * @return RhuVacacionCredito
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
     * Set vacacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuVacacion $vacacionRel
     *
     * @return RhuVacacionCredito
     */
    public function setVacacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuVacacion $vacacionRel = null)
    {
        $this->vacacionRel = $vacacionRel;

        return $this;
    }

    /**
     * Get vacacionRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuVacacion
     */
    public function getVacacionRel()
    {
        return $this->vacacionRel;
    }

    /**
     * Set creditoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCredito $creditoRel
     *
     * @return RhuVacacionCredito
     */
    public function setCreditoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCredito $creditoRel = null)
    {
        $this->creditoRel = $creditoRel;

        return $this;
    }

    /**
     * Get creditoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCredito
     */
    public function getCreditoRel()
    {
        return $this->creditoRel;
    }
}
