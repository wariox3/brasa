<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_curso_detalle")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiCursoDetalleRepository")
 */
class AfiCursoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_curso_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCursoDetallePk;    
    
    /**
     * @ORM\Column(name="codigo_curso_fk", type="integer")
     */    
    private $codigoCursoFk;                
    
    /**
     * @ORM\Column(name="codigo_curso_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoCursoTipoFk;    

    /**
     * @ORM\Column(name="costo", type="float")
     */
    private $costo = 0;    

    /**
     * @ORM\Column(name="precio", type="float")
     */
    private $precio = 0;    
    
    /**
     * @ORM\ManyToOne(targetEntity="AfiCurso", inversedBy="cursosDetallesCursoRel")
     * @ORM\JoinColumn(name="codigo_curso_fk", referencedColumnName="codigo_curso_pk")
     */
    protected $cursoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="AfiCursoTipo", inversedBy="cursosDetallesCursoTipoRel")
     * @ORM\JoinColumn(name="codigo_curso_tipo_fk", referencedColumnName="codigo_curso_tipo_pk")
     */
    protected $cursoTipoRel;     



    /**
     * Get codigoCursoDetallePk
     *
     * @return integer
     */
    public function getCodigoCursoDetallePk()
    {
        return $this->codigoCursoDetallePk;
    }

    /**
     * Set codigoCursoFk
     *
     * @param integer $codigoCursoFk
     *
     * @return AfiCursoDetalle
     */
    public function setCodigoCursoFk($codigoCursoFk)
    {
        $this->codigoCursoFk = $codigoCursoFk;

        return $this;
    }

    /**
     * Get codigoCursoFk
     *
     * @return integer
     */
    public function getCodigoCursoFk()
    {
        return $this->codigoCursoFk;
    }

    /**
     * Set codigoCursoTipoFk
     *
     * @param integer $codigoCursoTipoFk
     *
     * @return AfiCursoDetalle
     */
    public function setCodigoCursoTipoFk($codigoCursoTipoFk)
    {
        $this->codigoCursoTipoFk = $codigoCursoTipoFk;

        return $this;
    }

    /**
     * Get codigoCursoTipoFk
     *
     * @return integer
     */
    public function getCodigoCursoTipoFk()
    {
        return $this->codigoCursoTipoFk;
    }

    /**
     * Set costo
     *
     * @param float $costo
     *
     * @return AfiCursoDetalle
     */
    public function setCosto($costo)
    {
        $this->costo = $costo;

        return $this;
    }

    /**
     * Get costo
     *
     * @return float
     */
    public function getCosto()
    {
        return $this->costo;
    }

    /**
     * Set precio
     *
     * @param float $precio
     *
     * @return AfiCursoDetalle
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return float
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set cursoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCurso $cursoRel
     *
     * @return AfiCursoDetalle
     */
    public function setCursoRel(\Brasa\AfiliacionBundle\Entity\AfiCurso $cursoRel = null)
    {
        $this->cursoRel = $cursoRel;

        return $this;
    }

    /**
     * Get cursoRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiCurso
     */
    public function getCursoRel()
    {
        return $this->cursoRel;
    }

    /**
     * Set cursoTipoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCursoTipo $cursoTipoRel
     *
     * @return AfiCursoDetalle
     */
    public function setCursoTipoRel(\Brasa\AfiliacionBundle\Entity\AfiCursoTipo $cursoTipoRel = null)
    {
        $this->cursoTipoRel = $cursoTipoRel;

        return $this;
    }

    /**
     * Get cursoTipoRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiCursoTipo
     */
    public function getCursoTipoRel()
    {
        return $this->cursoTipoRel;
    }
}
