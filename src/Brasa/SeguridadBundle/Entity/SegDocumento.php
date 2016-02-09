<?php

namespace Brasa\SeguridadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="seg_documento")
 * @ORM\Entity(repositoryClass="Brasa\SeguridadBundle\Repository\SegDocumentoRepository")
 */
class SegDocumento
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_documento_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDocumentoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */    
    private $nombre;
    
    /**
     * @ORM\Column(name="tipo", type="string", length=30, nullable=true)
     */    
    private $tipo;
    
    /**
     * @ORM\Column(name="modulo", type="string", length=30, nullable=true)
     */    
    private $modulo;
    

    

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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return SegDocumento
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
     * Set tipo
     *
     * @param string $tipo
     *
     * @return SegDocumento
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set modulo
     *
     * @param string $modulo
     *
     * @return SegDocumento
     */
    public function setModulo($modulo)
    {
        $this->modulo = $modulo;

        return $this;
    }

    /**
     * Get modulo
     *
     * @return string
     */
    public function getModulo()
    {
        return $this->modulo;
    }
}
