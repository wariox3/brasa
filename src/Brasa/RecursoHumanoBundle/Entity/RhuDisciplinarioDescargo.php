<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_disciplinario_descargo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuDisciplinarioDescargoRepository")
 */
class RhuDisciplinarioDescargo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_disciplinario_descargo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDisciplinarioDescargoPk;        
    
    /**
     * @ORM\Column(name="codigo_disciplinario_fk", type="integer")
     */    
    private $codigoDisciplinarioFk;             
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;  
    
    /**
     * @ORM\Column(name="descargo", type="text", nullable=true)
     */    
    private $descargo;      
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuDisciplinario", inversedBy="disciplinariosDescargosDisciplinarioRel")
     * @ORM\JoinColumn(name="codigo_disciplinario_fk", referencedColumnName="codigo_disciplinario_pk")
     */
    protected $disciplinarioRel;


    /**
     * Get codigoDisciplinarioDescargoPk
     *
     * @return integer
     */
    public function getCodigoDisciplinarioDescargoPk()
    {
        return $this->codigoDisciplinarioDescargoPk;
    }

    /**
     * Set codigoDisciplinarioFk
     *
     * @param integer $codigoDisciplinarioFk
     *
     * @return RhuDisciplinarioDescargo
     */
    public function setCodigoDisciplinarioFk($codigoDisciplinarioFk)
    {
        $this->codigoDisciplinarioFk = $codigoDisciplinarioFk;

        return $this;
    }

    /**
     * Get codigoDisciplinarioFk
     *
     * @return integer
     */
    public function getCodigoDisciplinarioFk()
    {
        return $this->codigoDisciplinarioFk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuDisciplinarioDescargo
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
     * Set descargo
     *
     * @param string $descargo
     *
     * @return RhuDisciplinarioDescargo
     */
    public function setDescargo($descargo)
    {
        $this->descargo = $descargo;

        return $this;
    }

    /**
     * Get descargo
     *
     * @return string
     */
    public function getDescargo()
    {
        return $this->descargo;
    }

    /**
     * Set disciplinarioRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario $disciplinarioRel
     *
     * @return RhuDisciplinarioDescargo
     */
    public function setDisciplinarioRel(\Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario $disciplinarioRel = null)
    {
        $this->disciplinarioRel = $disciplinarioRel;

        return $this;
    }

    /**
     * Get disciplinarioRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario
     */
    public function getDisciplinarioRel()
    {
        return $this->disciplinarioRel;
    }
}
