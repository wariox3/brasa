<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_documento_subtipo")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvDocumentoSubtipoRepository")
 */
class InvDocumentoSubtipo
{   
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_documento_subtipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */ 
    private $codigoDocumentoSubtipoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */        
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="InvDocumento", mappedBy="documentoSubtipoRel")
     */
    protected $documentosRel;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->documentosRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoDocumentoSubtipoPk
     *
     * @return integer 
     */
    public function getCodigoDocumentoSubtipoPk()
    {
        return $this->codigoDocumentoSubtipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return InvDocumentoSubtipo
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
     * Add documentosRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumento $documentosRel
     * @return InvDocumentoSubtipo
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
}
