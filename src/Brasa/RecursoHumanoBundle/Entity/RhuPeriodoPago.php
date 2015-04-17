<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_periodo_pago")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPeriodoPagoRepository")
 */
class RhuPeriodoPago
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_periodo_pago_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPeriodoPagoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre; 
    
    /**
     * @ORM\Column(name="dias", type="integer")
     */
    private $dias = 0; 
    
    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="periodoPagoRel")
     */
    protected $contratosPeriodoPagoRel;    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contratosPeriodoPagoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPeriodoPagoPk
     *
     * @return integer
     */
    public function getCodigoPeriodoPagoPk()
    {
        return $this->codigoPeriodoPagoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuPeriodoPago
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
     * Set dias
     *
     * @param integer $dias
     *
     * @return RhuPeriodoPago
     */
    public function setDias($dias)
    {
        $this->dias = $dias;

        return $this;
    }

    /**
     * Get dias
     *
     * @return integer
     */
    public function getDias()
    {
        return $this->dias;
    }

    /**
     * Add contratosPeriodoPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosPeriodoPagoRel
     *
     * @return RhuPeriodoPago
     */
    public function addContratosPeriodoPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosPeriodoPagoRel)
    {
        $this->contratosPeriodoPagoRel[] = $contratosPeriodoPagoRel;

        return $this;
    }

    /**
     * Remove contratosPeriodoPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosPeriodoPagoRel
     */
    public function removeContratosPeriodoPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosPeriodoPagoRel)
    {
        $this->contratosPeriodoPagoRel->removeElement($contratosPeriodoPagoRel);
    }

    /**
     * Get contratosPeriodoPagoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosPeriodoPagoRel()
    {
        return $this->contratosPeriodoPagoRel;
    }
}
