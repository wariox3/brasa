<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_examen_detalle")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuExamenDetalleRepository")
 */
class RhuExamenDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_examen_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoExamenDetallePk;
    
    /**
     * @ORM\Column(name="codigo_examen_fk", type="integer", nullable=true)
     */    
    private $codigoExamenFk;
    
    /**
     * @ORM\Column(name="codigo_examen_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoExamenTipoFk;                
    
    /**     
     * @ORM\Column(name="estado_aprobado", type="boolean")
     */    
    private $estadoAprobado = 0;
    
    /**     
     * @ORM\Column(name="vr_precio", type="float")
     */    
    private $vrPrecio;
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuExamenTipo", inversedBy="examenesDetallesExamenTipoRel")
     * @ORM\JoinColumn(name="codigo_examen_tipo_fk", referencedColumnName="codigo_examen_tipo_pk")
     */
    protected $examenTipoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuExamen", inversedBy="examenesExamenDetalleRel")
     * @ORM\JoinColumn(name="codigo_examen_fk", referencedColumnName="codigo_examen_pk")
     */
    protected $examenRel;


    /**
     * Get codigoExamenDetallePk
     *
     * @return integer
     */
    public function getCodigoExamenDetallePk()
    {
        return $this->codigoExamenDetallePk;
    }

    /**
     * Set codigoExamenFk
     *
     * @param integer $codigoExamenFk
     *
     * @return RhuExamenDetalle
     */
    public function setCodigoExamenFk($codigoExamenFk)
    {
        $this->codigoExamenFk = $codigoExamenFk;

        return $this;
    }

    /**
     * Get codigoExamenFk
     *
     * @return integer
     */
    public function getCodigoExamenFk()
    {
        return $this->codigoExamenFk;
    }

    /**
     * Set codigoExamenTipoFk
     *
     * @param integer $codigoExamenTipoFk
     *
     * @return RhuExamenDetalle
     */
    public function setCodigoExamenTipoFk($codigoExamenTipoFk)
    {
        $this->codigoExamenTipoFk = $codigoExamenTipoFk;

        return $this;
    }

    /**
     * Get codigoExamenTipoFk
     *
     * @return integer
     */
    public function getCodigoExamenTipoFk()
    {
        return $this->codigoExamenTipoFk;
    }

    /**
     * Set estadoAprobado
     *
     * @param boolean $estadoAprobado
     *
     * @return RhuExamenDetalle
     */
    public function setEstadoAprobado($estadoAprobado)
    {
        $this->estadoAprobado = $estadoAprobado;

        return $this;
    }

    /**
     * Get estadoAprobado
     *
     * @return boolean
     */
    public function getEstadoAprobado()
    {
        return $this->estadoAprobado;
    }

    /**
     * Set vrPrecio
     *
     * @param float $vrPrecio
     *
     * @return RhuExamenDetalle
     */
    public function setVrPrecio($vrPrecio)
    {
        $this->vrPrecio = $vrPrecio;

        return $this;
    }

    /**
     * Get vrPrecio
     *
     * @return float
     */
    public function getVrPrecio()
    {
        return $this->vrPrecio;
    }

    /**
     * Set examenTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenTipo $examenTipoRel
     *
     * @return RhuExamenDetalle
     */
    public function setExamenTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenTipo $examenTipoRel = null)
    {
        $this->examenTipoRel = $examenTipoRel;

        return $this;
    }

    /**
     * Get examenTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuExamenTipo
     */
    public function getExamenTipoRel()
    {
        return $this->examenTipoRel;
    }

    /**
     * Set examenRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenRel
     *
     * @return RhuExamenDetalle
     */
    public function setExamenRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamen $examenRel = null)
    {
        $this->examenRel = $examenRel;

        return $this;
    }

    /**
     * Get examenRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuExamen
     */
    public function getExamenRel()
    {
        return $this->examenRel;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuExamenDetalle
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }
}
