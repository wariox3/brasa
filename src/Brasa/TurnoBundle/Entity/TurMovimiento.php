<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_movimiento")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurMovimientoRepository")
 */
class TurMovimiento
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_movimiento_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoMovimientoPk;    
    
    /**
     * @ORM\Column(name="codigo_documento_fk", type="integer")
     */
    
    private $codigoDocumentoFk;    
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;
    
    /**
     * @ORM\Column(name="numero", type="integer")
     */    
    private $numero = 0;

    /**
     * @ORM\ManyToOne(targetEntity="TurDocumento", inversedBy="movimientosDocumentoRel")
     * @ORM\JoinColumn(name="codigo_documento_fk", referencedColumnName="codigo_documento_pk")
     */
    protected $documentoRel;
        
    
    /**
     * @ORM\OneToMany(targetEntity="TurMovimientoDetalle", mappedBy="movimientoRel")
     */
    protected $movimientosDetallesMovimientoRel;
    

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->movimientosDetallesMovimientoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoMovimientoPk
     *
     * @return integer
     */
    public function getCodigoMovimientoPk()
    {
        return $this->codigoMovimientoPk;
    }

    /**
     * Set codigoDocumentoFk
     *
     * @param integer $codigoDocumentoFk
     *
     * @return TurMovimiento
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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return TurMovimiento
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return TurMovimiento
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set documentoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurDocumento $documentoRel
     *
     * @return TurMovimiento
     */
    public function setDocumentoRel(\Brasa\TurnoBundle\Entity\TurDocumento $documentoRel = null)
    {
        $this->documentoRel = $documentoRel;

        return $this;
    }

    /**
     * Get documentoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurDocumento
     */
    public function getDocumentoRel()
    {
        return $this->documentoRel;
    }

    /**
     * Add movimientosDetallesMovimientoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurMovimientoDetalle $movimientosDetallesMovimientoRel
     *
     * @return TurMovimiento
     */
    public function addMovimientosDetallesMovimientoRel(\Brasa\TurnoBundle\Entity\TurMovimientoDetalle $movimientosDetallesMovimientoRel)
    {
        $this->movimientosDetallesMovimientoRel[] = $movimientosDetallesMovimientoRel;

        return $this;
    }

    /**
     * Remove movimientosDetallesMovimientoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurMovimientoDetalle $movimientosDetallesMovimientoRel
     */
    public function removeMovimientosDetallesMovimientoRel(\Brasa\TurnoBundle\Entity\TurMovimientoDetalle $movimientosDetallesMovimientoRel)
    {
        $this->movimientosDetallesMovimientoRel->removeElement($movimientosDetallesMovimientoRel);
    }

    /**
     * Get movimientosDetallesMovimientoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMovimientosDetallesMovimientoRel()
    {
        return $this->movimientosDetallesMovimientoRel;
    }
}
