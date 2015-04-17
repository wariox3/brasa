<?php

namespace Brasa\InventarioBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_documento_tipo")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvDocumentoTipoRepository")
 */
class InvDocumentoTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_documento_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDocumentoTipoPk;

    /**
     * @ORM\Column(name="nombre_documento_tipo", type="string", length=50)
     */
    private $nombreDocumentoTipo;
    
    /**
     * @ORM\OneToMany(targetEntity="InvMovimiento", mappedBy="documentoTipoRel")
     */
    protected $movimientosRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="InvDocumento", mappedBy="documentoTipoRel")
     */
    protected $documentosRel;    
    
    
    
    


    /**
     * Get codigoDocumentoTipoPk
     *
     * @return integer 
     */
    public function getCodigoDocumentoTipoPk()
    {
        return $this->codigoDocumentoTipoPk;
    }

    /**
     * Set nombreDocumentoTipo
     *
     * @param string $nombreDocumentoTipo
     * @return InvDocumentoTipo
     */
    public function setNombreDocumentoTipo($nombreDocumentoTipo)
    {
        $this->nombreDocumentoTipo = $nombreDocumentoTipo;

        return $this;
    }

    /**
     * Get nombreDocumentoTipo
     *
     * @return string 
     */
    public function getNombreDocumentoTipo()
    {
        return $this->nombreDocumentoTipo;
    }

    /**
     * Add documentosRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumento $documentosRel
     * @return InvDocumentoTipo
     */
    public function addDocumentosRel(\Brasa\InventarioBundle\Entity\InvDocumento $documentosRel)
    {
        $this->documentosRel[] = $documentosRel;

        return $this;
    }

    /**
     * Remove documentosRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumento $documentosRel
     */
    public function removeDocumentosRel(\Brasa\InventarioBundle\Entity\InvDocumento $documentosRel)
    {
        $this->documentosRel->removeElement($documentosRel);
    }

    /**
     * Get documentosRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDocumentosRel()
    {
        return $this->documentosRel;
    }

    /**
     * Add movimientosRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimiento $movimientosRel
     * @return InvDocumentoTipo
     */
    public function addMovimientosRel(\Brasa\InventarioBundle\Entity\InvMovimiento $movimientosRel)
    {
        $this->movimientosRel[] = $movimientosRel;

        return $this;
    }

    /**
     * Remove movimientosRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimiento $movimientosRel
     */
    public function removeMovimientosRel(\Brasa\InventarioBundle\Entity\InvMovimiento $movimientosRel)
    {
        $this->movimientosRel->removeElement($movimientosRel);
    }

    /**
     * Get movimientosRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMovimientosRel()
    {
        return $this->movimientosRel;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->movimientosRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->documentosRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
