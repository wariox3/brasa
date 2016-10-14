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
    protected $documentosDocumentoSubtipoRel;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->documentosDocumentoSubtipoRel = new \Doctrine\Common\Collections\ArrayCollection();
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
     *
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
     * Add documentosDocumentoSubtipoRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumento $documentosDocumentoSubtipoRel
     *
     * @return InvDocumentoSubtipo
     */
    public function addDocumentosDocumentoSubtipoRel(\Brasa\InventarioBundle\Entity\InvDocumento $documentosDocumentoSubtipoRel)
    {
        $this->documentosDocumentoSubtipoRel[] = $documentosDocumentoSubtipoRel;

        return $this;
    }

    /**
     * Remove documentosDocumentoSubtipoRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumento $documentosDocumentoSubtipoRel
     */
    public function removeDocumentosDocumentoSubtipoRel(\Brasa\InventarioBundle\Entity\InvDocumento $documentosDocumentoSubtipoRel)
    {
        $this->documentosDocumentoSubtipoRel->removeElement($documentosDocumentoSubtipoRel);
    }

    /**
     * Get documentosDocumentoSubtipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocumentosDocumentoSubtipoRel()
    {
        return $this->documentosDocumentoSubtipoRel;
    }
}
