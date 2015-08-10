<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_incapacidad_registro_pago")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuIncapacidadRegistroPagoRepository")
 */
class RhuIncapacidadRegistroPago
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_incapacidad_registro_pago_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoIncapacidadRegistroPagoPk;                          
    
    /**
     * @ORM\Column(name="codigo_incapacidad_fk", type="integer", nullable=true)
     */    
    private $codigoIncapacidadFk;      
    
    /**
     * @ORM\Column(name="cantidad_afectada", type="integer")
     */
    private $cantidad_afectada = 0;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuIncapacidad", inversedBy="incapacidadesRegistrosPagosIncapcidadRel")
     * @ORM\JoinColumn(name="codigo_incapcidad_fk", referencedColumnName="codigo_incapacidad_pk")
     */
    protected $incapacidadRel; 
    

    /**
     * Get codigoIncapacidadRegistroPagoPk
     *
     * @return integer
     */
    public function getCodigoIncapacidadRegistroPagoPk()
    {
        return $this->codigoIncapacidadRegistroPagoPk;
    }

    /**
     * Set codigoIncapacidadFk
     *
     * @param integer $codigoIncapacidadFk
     *
     * @return RhuIncapacidadRegistroPago
     */
    public function setCodigoIncapacidadFk($codigoIncapacidadFk)
    {
        $this->codigoIncapacidadFk = $codigoIncapacidadFk;

        return $this;
    }

    /**
     * Get codigoIncapacidadFk
     *
     * @return integer
     */
    public function getCodigoIncapacidadFk()
    {
        return $this->codigoIncapacidadFk;
    }

    /**
     * Set cantidadAfectada
     *
     * @param integer $cantidadAfectada
     *
     * @return RhuIncapacidadRegistroPago
     */
    public function setCantidadAfectada($cantidadAfectada)
    {
        $this->cantidad_afectada = $cantidadAfectada;

        return $this;
    }

    /**
     * Get cantidadAfectada
     *
     * @return integer
     */
    public function getCantidadAfectada()
    {
        return $this->cantidad_afectada;
    }

    /**
     * Set incapacidadRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadRel
     *
     * @return RhuIncapacidadRegistroPago
     */
    public function setIncapacidadRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadRel = null)
    {
        $this->incapacidadRel = $incapacidadRel;

        return $this;
    }

    /**
     * Get incapacidadRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad
     */
    public function getIncapacidadRel()
    {
        return $this->incapacidadRel;
    }
}
