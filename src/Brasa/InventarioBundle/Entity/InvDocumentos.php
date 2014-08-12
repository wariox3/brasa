<?php

namespace Brasa\InventarioBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_documentos")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvDocumentosRepository")
 */
class InvDocumentos
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_documento_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDocumentoPk;

    /**
     * @ORM\Column(name="nombre_documento", type="string", length=50)
     */
    private $nombreDocumento;

    /**
     * @ORM\Column(name="codigo_documento_tipo_fk", type="integer")
     */
    private $codigoDocumentoTipoFk;    

    /**
     * @ORM\ManyToOne(targetEntity="InvDocumentosTipos", inversedBy="documentosRel")
     * @ORM\JoinColumn(name="codigo_documento_tipo_fk", referencedColumnName="codigo_documento_tipo_pk")
     */
    protected $documentoTipoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="InvMovimientos", mappedBy="documentoRel")
     */
    protected $movimientosRel;

  
    

    /**
     * Get codigoDocumentoPk
     *
     * @return integer 
     */
    public function getCodigoDocumentoPk()
    {
        return $this->codigoDocumentoPk;
    }

    /**
     * Set nombreDocumento
     *
     * @param string $nombreDocumento
     * @return InvDocumentos
     */
    public function setNombreDocumento($nombreDocumento)
    {
        $this->nombreDocumento = $nombreDocumento;

        return $this;
    }

    /**
     * Get nombreDocumento
     *
     * @return string 
     */
    public function getNombreDocumento()
    {
        return $this->nombreDocumento;
    }

    /**
     * Set codigoDocumentoTipoFk
     *
     * @param integer $codigoDocumentoTipoFk
     * @return InvDocumentos
     */
    public function setCodigoDocumentoTipoFk($codigoDocumentoTipoFk)
    {
        $this->codigoDocumentoTipoFk = $codigoDocumentoTipoFk;

        return $this;
    }

    /**
     * Get codigoDocumentoTipoFk
     *
     * @return integer 
     */
    public function getCodigoDocumentoTipoFk()
    {
        return $this->codigoDocumentoTipoFk;
    }

    /**
     * Set documentoTipoRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumentosTipos $documentoTipoRel
     * @return InvDocumentos
     */
    public function setDocumentoTipoRel(\Brasa\InventarioBundle\Entity\InvDocumentosTipos $documentoTipoRel = null)
    {
        $this->documentoTipoRel = $documentoTipoRel;

        return $this;
    }

    /**
     * Get documentoTipoRel
     *
     * @return \Brasa\InventarioBundle\Entity\InvDocumentosTipos 
     */
    public function getDocumentoTipoRel()
    {
        return $this->documentoTipoRel;
    }

    /**
     * Add movimientosRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimientos $movimientosRel
     * @return InvDocumentos
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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->movimientosRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
