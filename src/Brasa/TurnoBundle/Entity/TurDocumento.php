<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_documento")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurDocumentoRepository")
 */
class TurDocumento
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_documento_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDocumentoPk;                
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    
    private $nombre;
    
    /**
     * @ORM\Column(name="operacion", type="integer")
     */    
    private $operacion = 0;
                               
    /**
     * @ORM\OneToMany(targetEntity="TurMovimiento", mappedBy="documentoRel")
     */
    protected $movimientosDocumentoRel;

    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->movimientosDocumentoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * @return TurDocumento
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
     * Set operacion
     *
     * @param integer $operacion
     *
     * @return TurDocumento
     */
    public function setOperacion($operacion)
    {
        $this->operacion = $operacion;

        return $this;
    }

    /**
     * Get operacion
     *
     * @return integer
     */
    public function getOperacion()
    {
        return $this->operacion;
    }

    /**
     * Add movimientosDocumentoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurMovimiento $movimientosDocumentoRel
     *
     * @return TurDocumento
     */
    public function addMovimientosDocumentoRel(\Brasa\TurnoBundle\Entity\TurMovimiento $movimientosDocumentoRel)
    {
        $this->movimientosDocumentoRel[] = $movimientosDocumentoRel;

        return $this;
    }

    /**
     * Remove movimientosDocumentoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurMovimiento $movimientosDocumentoRel
     */
    public function removeMovimientosDocumentoRel(\Brasa\TurnoBundle\Entity\TurMovimiento $movimientosDocumentoRel)
    {
        $this->movimientosDocumentoRel->removeElement($movimientosDocumentoRel);
    }

    /**
     * Get movimientosDocumentoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMovimientosDocumentoRel()
    {
        return $this->movimientosDocumentoRel;
    }
}
