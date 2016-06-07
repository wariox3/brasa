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
     * @ORM\Column(name="precio", type="float")
     */
    private $precio = 0;    
    
    /**
     * @ORM\OneToMany(targetEntity="AfiCursoDetalle", mappedBy="cursoTipoRel")
     */
    protected $cursosDetallesCursoTipoRel; 

    /**
     * @ORM\OneToMany(targetEntity="AfiCurso", mappedBy="cursoTipoRel")
     */
    protected $cursosCursoTipoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="AfiEntidadEntrenamientoCosto", mappedBy="cursoTipoRel")
     */
    protected $entidadesEntrenamientoCostosCursoTipoRel;     
    
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

    /**
     * Set precio
     *
     * @param float $precio
     *
     * @return AfiCursoTipo
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
     * Add entidadesEntrenamientoCostosCursoTipoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiEntidadEntrenamientoCosto $entidadesEntrenamientoCostosCursoTipoRel
     *
     * @return AfiCursoTipo
     */
    public function addEntidadesEntrenamientoCostosCursoTipoRel(\Brasa\AfiliacionBundle\Entity\AfiEntidadEntrenamientoCosto $entidadesEntrenamientoCostosCursoTipoRel)
    {
        $this->entidadesEntrenamientoCostosCursoTipoRel[] = $entidadesEntrenamientoCostosCursoTipoRel;

        return $this;
    }

    /**
     * Remove entidadesEntrenamientoCostosCursoTipoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiEntidadEntrenamientoCosto $entidadesEntrenamientoCostosCursoTipoRel
     */
    public function removeEntidadesEntrenamientoCostosCursoTipoRel(\Brasa\AfiliacionBundle\Entity\AfiEntidadEntrenamientoCosto $entidadesEntrenamientoCostosCursoTipoRel)
    {
        $this->entidadesEntrenamientoCostosCursoTipoRel->removeElement($entidadesEntrenamientoCostosCursoTipoRel);
    }

    /**
     * Get entidadesEntrenamientoCostosCursoTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEntidadesEntrenamientoCostosCursoTipoRel()
    {
        return $this->entidadesEntrenamientoCostosCursoTipoRel;
    }

    /**
     * Add cursosCursoTipoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCurso $cursosCursoTipoRel
     *
     * @return AfiCursoTipo
     */
    public function addCursosCursoTipoRel(\Brasa\AfiliacionBundle\Entity\AfiCurso $cursosCursoTipoRel)
    {
        $this->cursosCursoTipoRel[] = $cursosCursoTipoRel;

        return $this;
    }

    /**
     * Remove cursosCursoTipoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCurso $cursosCursoTipoRel
     */
    public function removeCursosCursoTipoRel(\Brasa\AfiliacionBundle\Entity\AfiCurso $cursosCursoTipoRel)
    {
        $this->cursosCursoTipoRel->removeElement($cursosCursoTipoRel);
    }

    /**
     * Get cursosCursoTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCursosCursoTipoRel()
    {
        return $this->cursosCursoTipoRel;
    }
}
