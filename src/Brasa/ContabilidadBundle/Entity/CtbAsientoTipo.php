<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ctb_asiento_tipo")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbAsientoTipoRepository")
 */
class CtbAsientoTipo
{
    /** 
     * @ORM\Id
     * @ORM\Column(name="codigo_asiento_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */    
    private $codigoAsientoTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;      

    /**
     * @ORM\Column(name="consecutivo", type="integer")
     */        
    private $consecutivo = 1;     

    /**
     * @ORM\Column(name="codigo_comprobante_contable_fk", type="integer", nullable=true)
     */     
    private $codigoComprobanteContableFk;     
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbComprobante", inversedBy="CtbAsientoTipo")
     * @ORM\JoinColumn(name="codigo_comprobante_fk", referencedColumnName="codigo_comprobante_pk")
     */
    protected $comprobanteRel;


    

    /**
     * Get codigoAsientoTipoPk
     *
     * @return integer
     */
    public function getCodigoAsientoTipoPk()
    {
        return $this->codigoAsientoTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return CtbAsientoTipo
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
     * Set consecutivo
     *
     * @param integer $consecutivo
     *
     * @return CtbAsientoTipo
     */
    public function setConsecutivo($consecutivo)
    {
        $this->consecutivo = $consecutivo;

        return $this;
    }

    /**
     * Get consecutivo
     *
     * @return integer
     */
    public function getConsecutivo()
    {
        return $this->consecutivo;
    }

    /**
     * Set codigoComprobanteContableFk
     *
     * @param integer $codigoComprobanteContableFk
     *
     * @return CtbAsientoTipo
     */
    public function setCodigoComprobanteContableFk($codigoComprobanteContableFk)
    {
        $this->codigoComprobanteContableFk = $codigoComprobanteContableFk;

        return $this;
    }

    /**
     * Get codigoComprobanteContableFk
     *
     * @return integer
     */
    public function getCodigoComprobanteContableFk()
    {
        return $this->codigoComprobanteContableFk;
    }

    /**
     * Set comprobanteRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbComprobante $comprobanteRel
     *
     * @return CtbAsientoTipo
     */
    public function setComprobanteRel(\Brasa\ContabilidadBundle\Entity\CtbComprobante $comprobanteRel = null)
    {
        $this->comprobanteRel = $comprobanteRel;

        return $this;
    }

    /**
     * Get comprobanteRel
     *
     * @return \Brasa\ContabilidadBundle\Entity\CtbComprobante
     */
    public function getComprobanteRel()
    {
        return $this->comprobanteRel;
    }
}
