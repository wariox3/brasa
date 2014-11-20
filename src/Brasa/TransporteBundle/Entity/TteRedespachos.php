<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_redespachos")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteRedespachosRepository")
 */
class TteRedespachos
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_redespacho_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRedespachoPk;    
    
    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */    
    private $fecha;    
    
    /**
     * @ORM\Column(name="codigo_guia_fk", type="integer", nullable=true)
     */    
    private $codigoGuiaFk; 

    /**
     * @ORM\ManyToOne(targetEntity="TteGuias", inversedBy="redespachosRel")
     * @ORM\JoinColumn(name="codigo_guia_fk", referencedColumnName="codigo_guia_pk")
     */
    protected $guiaRel;    

    /**
     * Get codigoRedespachoPk
     *
     * @return integer 
     */
    public function getCodigoRedespachoPk()
    {
        return $this->codigoRedespachoPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return TteRedespachos
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set codigoGuiaFk
     *
     * @param integer $codigoGuiaFk
     * @return TteRedespachos
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
     * Set guiaRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuias $guiaRel
     * @return TteRedespachos
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
