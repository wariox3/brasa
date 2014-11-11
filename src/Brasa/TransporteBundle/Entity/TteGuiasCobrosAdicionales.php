<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_guias_cobros_adicionales")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteGuiasCobrosAdicionalesRepository")
 */
class TteGuiasCobrosAdicionales
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_guia_cobro_adicional_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoGuiaCobroAdicionalPk;
    
    /**
     * @ORM\Column(name="codigo_guia_fk", type="integer", nullable=true)
     */    
    private $codigoGuiaFk; 
    
    /**
     * @ORM\Column(name="vr_cobro", type="float")
     */
    private $vrCobro = 0;
    
    /**
     * @ORM\ManyToOne(targetEntity="TteGuias", inversedBy="guiasCobrosAdicionalesRel")
     * @ORM\JoinColumn(name="codigo_guia_fk", referencedColumnName="codigo_guia_pk")
     */
    protected $guiaRel;    
    


    /**
     * Get codigoGuiaCobroAdicionalPk
     *
     * @return integer 
     */
    public function getCodigoGuiaCobroAdicionalPk()
    {
        return $this->codigoGuiaCobroAdicionalPk;
    }

    /**
     * Set codigoGuiaFk
     *
     * @param integer $codigoGuiaFk
     * @return TteGuiasCobrosAdicionales
     */
    public function setCodigoGuiaFk($codigoGuiaFk)
    {
        $this->codigoGuiaFk = $codigoGuiaFk;

        return $this;
    }

    /**
     * Get codigoGuiaFk
     *
     * @return integer 
     */
    public function getCodigoGuiaFk()
    {
        return $this->codigoGuiaFk;
    }

    /**
     * Set vrCobro
     *
     * @param float $vrCobro
     * @return TteGuiasCobrosAdicionales
     */
    public function setVrCobro($vrCobro)
    {
        $this->vrCobro = $vrCobro;

        return $this;
    }

    /**
     * Get vrCobro
     *
     * @return float 
     */
    public function getVrCobro()
    {
        return $this->vrCobro;
    }

    /**
     * Set guiaRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuias $guiaRel
     * @return TteGuiasCobrosAdicionales
     */
    public function setGuiaRel(\Brasa\TransporteBundle\Entity\TteGuias $guiaRel = null)
    {
        $this->guiaRel = $guiaRel;

        return $this;
    }

    /**
     * Get guiaRel
     *
     * @return \Brasa\TransporteBundle\Entity\TteGuias 
     */
    public function getGuiaRel()
    {
        return $this->guiaRel;
    }
}
