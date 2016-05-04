<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_pago_curso_detalle_curso")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiPagoCursoDetalleRepository")
 */
class AfiPagoCursoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_curso_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPagoCursoDetallePk;    
    
    /**
     * @ORM\Column(name="codigo_pago_curso_fk", type="integer")
     */    
    private $codigoPagoCursoFk;            

    /**
     * @ORM\Column(name="codigo_curso_fk", type="integer")
     */    
    private $codigoCursoFk; 
    
    /**
     * @ORM\Column(name="costo", type="float")
     */
    private $costo = 0;             
    
    /**
     * @ORM\Column(name="total", type="float")
     */
    private $total = 0;     
    
    /**
     * @ORM\ManyToOne(targetEntity="AfiPagoCurso", inversedBy="pagosCursosDetallesPagoCursoRel")
     * @ORM\JoinColumn(name="codigo_pago_curso_fk", referencedColumnName="codigo_pago_curso_pk")
     */
    protected $pagoCursoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="AfiCurso", inversedBy="pagosCursosDetallesCursoRel")
     * @ORM\JoinColumn(name="codigo_curso_fk", referencedColumnName="codigo_curso_pk")
     */
    protected $cursoRel; 
    

    /**
     * Get codigoPagoCursoDetallePk
     *
     * @return integer
     */
    public function getCodigoPagoCursoDetallePk()
    {
        return $this->codigoPagoCursoDetallePk;
    }

    /**
     * Set codigoPagoCursoFk
     *
     * @param integer $codigoPagoCursoFk
     *
     * @return AfiPagoCursoDetalle
     */
    public function setCodigoPagoCursoFk($codigoPagoCursoFk)
    {
        $this->codigoPagoCursoFk = $codigoPagoCursoFk;

        return $this;
    }

    /**
     * Get codigoPagoCursoFk
     *
     * @return integer
     */
    public function getCodigoPagoCursoFk()
    {
        return $this->codigoPagoCursoFk;
    }

    /**
     * Set codigoCursoFk
     *
     * @param integer $codigoCursoFk
     *
     * @return AfiPagoCursoDetalle
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
     * Set costo
     *
     * @param float $costo
     *
     * @return AfiPagoCursoDetalle
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
     * Set total
     *
     * @param float $total
     *
     * @return AfiPagoCursoDetalle
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set pagoCursoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPagoCurso $pagoCursoRel
     *
     * @return AfiPagoCursoDetalle
     */
    public function setPagoCursoRel(\Brasa\AfiliacionBundle\Entity\AfiPagoCurso $pagoCursoRel = null)
    {
        $this->pagoCursoRel = $pagoCursoRel;

        return $this;
    }

    /**
     * Get pagoCursoRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiPagoCurso
     */
    public function getPagoCursoRel()
    {
        return $this->pagoCursoRel;
    }

    /**
     * Set cursoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCurso $cursoRel
     *
     * @return AfiPagoCursoDetalle
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
}
