<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_recurso")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurRecursoRepository")
 */
class TurRecurso
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_recurso_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRecursoPk;    
    
    /**
     * @ORM\Column(name="nombreCorto", type="string", length=120, nullable=true)
     */    
    private $nombreCorto;    
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurProgramacionDetalle", mappedBy="recursoRel")
     */
    protected $programacionesDetallesRecursoRel;    

    /**
     * Get codigoRecursoPk
     *
     * @return integer
     */
    public function getCodigoRecursoPk()
    {
        return $this->codigoRecursoPk;
    }

    /**
     * Set nombreCorto
     *
     * @param string $nombreCorto
     *
     * @return TurRecurso
     */
    public function setNombreCorto($nombreCorto)
    {
        $this->nombreCorto = $nombreCorto;

        return $this;
    }

    /**
     * Get nombreCorto
     *
     * @return string
     */
    public function getNombreCorto()
    {
        return $this->nombreCorto;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return TurRecurso
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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->programacionesDetallesRecursoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add programacionesDetallesRecursoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesRecursoRel
     *
     * @return TurRecurso
     */
    public function addProgramacionesDetallesRecursoRel(\Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesRecursoRel)
    {
        $this->programacionesDetallesRecursoRel[] = $programacionesDetallesRecursoRel;

        return $this;
    }

    /**
     * Remove programacionesDetallesRecursoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesRecursoRel
     */
    public function removeProgramacionesDetallesRecursoRel(\Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesRecursoRel)
    {
        $this->programacionesDetallesRecursoRel->removeElement($programacionesDetallesRecursoRel);
    }

    /**
     * Get programacionesDetallesRecursoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgramacionesDetallesRecursoRel()
    {
        return $this->programacionesDetallesRecursoRel;
    }
}
