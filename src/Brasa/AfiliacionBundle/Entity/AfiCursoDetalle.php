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
     * @ORM\ManyToOne(targetEntity="AfiCurso", inversedBy="cursosDetallesCursoRel")
     * @ORM\JoinColumn(name="codigo_curso_fk", referencedColumnName="codigo_curso_pk")
     */
    protected $cursoRel;    
    

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
}
