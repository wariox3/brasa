<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_banco")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuBancoRepository")
 */
class RhuBanco
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_banco_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoBancoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="bancoRel")
     */
    protected $empleadosBancoRel;    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosBancoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoBancoPk
     *
     * @return integer
     */
    public function getCodigoBancoPk()
    {
        return $this->codigoBancoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuBanco
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
     * Add empleadosBancoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosBancoRel
     *
     * @return RhuBanco
     */
    public function addEmpleadosBancoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosBancoRel)
    {
        $this->empleadosBancoRel[] = $empleadosBancoRel;

        return $this;
    }

    /**
     * Remove empleadosBancoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosBancoRel
     */
    public function removeEmpleadosBancoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosBancoRel)
    {
        $this->empleadosBancoRel->removeElement($empleadosBancoRel);
    }

    /**
     * Get empleadosBancoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosBancoRel()
    {
        return $this->empleadosBancoRel;
    }
}
