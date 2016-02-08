<?php

namespace Brasa\SeguridadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="seg_permiso_documento")
 * @ORM\Entity(repositoryClass="Brasa\SeguridadBundle\Repository\SegPermisoDocumentoRepository")
 */
class SegPermisoDocumento
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_permiso_documento_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPermisoDocumentoPk;
    
    /**
     * @ORM\Column(name="codigo_usuario_fk", type="integer")
     */
    private $codigoUsuarioFk;    

    /**
     * @ORM\Column(name="codigo_documento_fk", type="integer")
     */
    private $codigoDocumentoFk;     

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="permisosDocumentosUsuarioRel")
     * @ORM\JoinColumn(name="codigo_usuario_fk", referencedColumnName="id")
     */
    protected $usuarioRel;        
    
    /**
     * @ORM\ManyToOne(targetEntity="SegDocumento", inversedBy="permisosDocumentosDocumentoRel")
     * @ORM\JoinColumn(name="codigo_documento_fk", referencedColumnName="codigo_documento_pk")
     */
    protected $documentoRel;     
    

    /**
     * Get codigoPermisoDocumentoPk
     *
     * @return integer
     */
    public function getCodigoPermisoDocumentoPk()
    {
        return $this->codigoPermisoDocumentoPk;
    }

    /**
     * Set codigoUsuarioFk
     *
     * @param integer $codigoUsuarioFk
     *
     * @return SegPermisoDocumento
     */
    public function setCodigoUsuarioFk($codigoUsuarioFk)
    {
        $this->codigoUsuarioFk = $codigoUsuarioFk;

        return $this;
    }

    /**
     * Get codigoUsuarioFk
     *
     * @return integer
     */
    public function getCodigoUsuarioFk()
    {
        return $this->codigoUsuarioFk;
    }

    /**
     * Set codigoDocumentoFk
     *
     * @param integer $codigoDocumentoFk
     *
     * @return SegPermisoDocumento
     */
    public function setCodigoDocumentoFk($codigoDocumentoFk)
    {
        $this->codigoDocumentoFk = $codigoDocumentoFk;

        return $this;
    }

    /**
     * Get codigoDocumentoFk
     *
     * @return integer
     */
    public function getCodigoDocumentoFk()
    {
        return $this->codigoDocumentoFk;
    }

    /**
     * Set usuarioRel
     *
     * @param \Brasa\SeguridadBundle\Entity\User $usuarioRel
     *
     * @return SegPermisoDocumento
     */
    public function setUsuarioRel(\Brasa\SeguridadBundle\Entity\User $usuarioRel = null)
    {
        $this->usuarioRel = $usuarioRel;

        return $this;
    }

    /**
     * Get usuarioRel
     *
     * @return \Brasa\SeguridadBundle\Entity\User
     */
    public function getUsuarioRel()
    {
        return $this->usuarioRel;
    }

    /**
     * Set documentoRel
     *
     * @param \Brasa\SeguridadBundle\Entity\SegDocumento $documentoRel
     *
     * @return SegPermisoDocumento
     */
    public function setDocumentoRel(\Brasa\SeguridadBundle\Entity\SegDocumento $documentoRel = null)
    {
        $this->documentoRel = $documentoRel;

        return $this;
    }

    /**
     * Get documentoRel
     *
     * @return \Brasa\SeguridadBundle\Entity\SegDocumento
     */
    public function getDocumentoRel()
    {
        return $this->documentoRel;
    }
}
