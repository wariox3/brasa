<?php

namespace Brasa\InventarioBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_documentos_tipos")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvDocumentosTiposRepository")
 */
class InvDocumentosTipos
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
     * @ORM\OneToMany(targetEntity="InvMovimientos", mappedBy="documentoTipoRel")
     */
    protected $movimientosRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="InvDocumentos", mappedBy="documentoTipoRel")
     */
    protected $documentosRel;    
    
    public function __construct()
    {        
        $this->movimientosRel = new ArrayCollection();
        $this->documentosRel = new ArrayCollection();
    }    
    
    


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
     * @return InvDocumentosTipos
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
     * @param \Brasa\InventarioBundle\Entity\InvDocumentos $documentosRel
     * @return InvDocumentosTipos
     */
    public function addDocumentosRel(\Brasa\InventarioBundle\Entity\InvDocumentos $documentosRel)
    {
        $this->documentosRel[] = $documentosRel;

        return $this;
    }

    /**
     * Remove documentosRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumentos $documentosRel
     */
    public function removeDocumentosRel(\Brasa\InventarioBundle\Entity\InvDocumentos $documentosRel)
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
     * @param \Brasa\InventarioBundle\Entity\InvMovimientos $movimientosRel
     * @return InvDocumentosTipos
     */
    public function addMovimientosRel(\Brasa\InventarioBundle\Entity\InvMovimientos $movimientosRel)
    {
        $this->movimientosRel[] = $movimientosRel;

        return $this;
    }

    /**
     * Remove movimientosRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimientos $movimientosRel
     */
    public function removeMovimientosRel(\Brasa\InventarioBundle\Entity\InvMovimientos $movimientosRel)
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
}
