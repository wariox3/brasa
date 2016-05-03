<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_curso_tipo")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiCursoTipoRepository")
 */
class AfiCursoTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_curso_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCursoTipoPk;                   
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;                             
    
    /**
     * @ORM\OneToMany(targetEntity="AfiCursoDetalle", mappedBy="cursoTipoRel")
     */
    protected $cursosDetallesCursoTipoRel; 

    /**
     * Get codigoCursoTipoPk
     *
     * @return integer
     */
    public function getCodigoCursoTipoPk()
    {
        return $this->codigoCursoTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return AfiCursoTipo
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
     * Constructor
     */
    public function __construct()
    {
        $this->cursosDetallesCursoTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add cursosDetallesCursoTipoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCursoDetalle $cursosDetallesCursoTipoRel
     *
     * @return AfiCursoTipo
     */
    public function addCursosDetallesCursoTipoRel(\Brasa\AfiliacionBundle\Entity\AfiCursoDetalle $cursosDetallesCursoTipoRel)
    {
        $this->cursosDetallesCursoTipoRel[] = $cursosDetallesCursoTipoRel;

        return $this;
    }

    /**
     * Remove cursosDetallesCursoTipoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCursoDetalle $cursosDetallesCursoTipoRel
     */
    public function removeCursosDetallesCursoTipoRel(\Brasa\AfiliacionBundle\Entity\AfiCursoDetalle $cursosDetallesCursoTipoRel)
    {
        $this->cursosDetallesCursoTipoRel->removeElement($cursosDetallesCursoTipoRel);
    }

    /**
     * Get cursosDetallesCursoTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCursosDetallesCursoTipoRel()
    {
        return $this->cursosDetallesCursoTipoRel;
    }
}
