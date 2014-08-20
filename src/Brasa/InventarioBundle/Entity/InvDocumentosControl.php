<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_documentos_control")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvDocumentosControlRepository")
 */
class InvDocumentosControl
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_documento_padre_pk", type="integer")
     */ 
    private $codigoDocumentoPadrePk;    

    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_documento_hijo_pk", type="integer")
     */ 
    private $codigoDocumentoHijoPk;    
    
    /**
     * @ORM\Column(name="hereda_precio", type="boolean")
     */    
    private $heredaPrecio = 0;     
    
    /**
     * @ORM\ManyToOne(targetEntity="InvDocumentos", inversedBy="InvDocumentosControl")
     * @ORM\JoinColumn(name="codigo_documento_padre_pk", referencedColumnName="codigo_documento_pk")
     */
    protected $documentoPadreRel;      

    /**
     * @ORM\ManyToOne(targetEntity="InvDocumentos", inversedBy="InvDocumentosControl")
     * @ORM\JoinColumn(name="codigo_documento_hijo_pk", referencedColumnName="codigo_documento_pk")
     */
    protected $documentoHijoRel;    
    

    /**
     * Set codigoDocumentoPadrePk
     *
     * @param integer $codigoDocumentoPadrePk
     * @return InvDocumentosControl
     */
    public function setCodigoDocumentoPadrePk($codigoDocumentoPadrePk)
    {
        $this->codigoDocumentoPadrePk = $codigoDocumentoPadrePk;

        return $this;
    }

    /**
     * Get codigoDocumentoPadrePk
     *
     * @return integer 
     */
    public function getCodigoDocumentoPadrePk()
    {
        return $this->codigoDocumentoPadrePk;
    }

    /**
     * Set codigoDocumentoHijoPk
     *
     * @param integer $codigoDocumentoHijoPk
     * @return InvDocumentosControl
     */
    public function setCodigoDocumentoHijoPk($codigoDocumentoHijoPk)
    {
        $this->codigoDocumentoHijoPk = $codigoDocumentoHijoPk;

        return $this;
    }

    /**
     * Get codigoDocumentoHijoPk
     *
     * @return integer 
     */
    public function getCodigoDocumentoHijoPk()
    {
        return $this->codigoDocumentoHijoPk;
    }

    /**
     * Set heredaPrecio
     *
     * @param boolean $heredaPrecio
     * @return InvDocumentosControl
     */
    public function setHeredaPrecio($heredaPrecio)
    {
        $this->heredaPrecio = $heredaPrecio;

        return $this;
    }

    /**
     * Get heredaPrecio
     *
     * @return boolean 
     */
    public function getHeredaPrecio()
    {
        return $this->heredaPrecio;
    }

    /**
     * Set documentoPadreRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumentos $documentoPadreRel
     * @return InvDocumentosControl
     */
    public function setDocumentoPadreRel(\Brasa\InventarioBundle\Entity\InvDocumentos $documentoPadreRel = null)
    {
        $this->documentoPadreRel = $documentoPadreRel;

        return $this;
    }

    /**
     * Get documentoPadreRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvDocumentos 
     */
    public function getDocumentoPadreRel()
    {
        return $this->documentoPadreRel;
    }

    /**
     * Set documentoHijoRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumentos $documentoHijoRel
     * @return InvDocumentosControl
     */
    public function setDocumentoHijoRel(\Brasa\InventarioBundle\Entity\InvDocumentos $documentoHijoRel = null)
    {
        $this->documentoHijoRel = $documentoHijoRel;

        return $this;
    }

    /**
     * Get documentoHijoRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvDocumentos 
     */
    public function getDocumentoHijoRel()
    {
        return $this->documentoHijoRel;
    }
}
