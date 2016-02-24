<?php

namespace Brasa\GeneralBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_pais")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenPaisRepository")
 */
class GenPais
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pais_pk", type="integer")
     */
    private $codigoPaisPk;

    /**
     * @ORM\Column(name="pais", type="string", length=50)
     * @Assert\NotNull()(message="Debe escribir un pais")
     */
    private $pais;
    
    /**
     * @ORM\Column(name="gentilicio", type="string", length=50)
     * @Assert\NotNull()(message="Debe escribir un gentilicio")
     */
    private $gentilicio;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuEmpleado", mappedBy="paisRel")
     */
    protected $rhuEmpleadosPaisRel;
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rhuEmpleadosPaisRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set codigoPaisPk
     *
     * @param integer $codigoPaisPk
     *
     * @return GenPais
     */
    public function setCodigoPaisPk($codigoPaisPk)
    {
        $this->codigoPaisPk = $codigoPaisPk;

        return $this;
    }

    /**
     * Get codigoPaisPk
     *
     * @return integer
     */
    public function getCodigoPaisPk()
    {
        return $this->codigoPaisPk;
    }

    /**
     * Set pais
     *
     * @param string $pais
     *
     * @return GenPais
     */
    public function setPais($pais)
    {
        $this->pais = $pais;

        return $this;
    }

    /**
     * Get pais
     *
     * @return string
     */
    public function getPais()
    {
        return $this->pais;
    }

    /**
     * Set gentilicio
     *
     * @param string $gentilicio
     *
     * @return GenPais
     */
    public function setGentilicio($gentilicio)
    {
        $this->gentilicio = $gentilicio;

        return $this;
    }

    /**
     * Get gentilicio
     *
     * @return string
     */
    public function getGentilicio()
    {
        return $this->gentilicio;
    }

    /**
     * Add rhuEmpleadosPaisRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosPaisRel
     *
     * @return GenPais
     */
    public function addRhuEmpleadosPaisRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosPaisRel)
    {
        $this->rhuEmpleadosPaisRel[] = $rhuEmpleadosPaisRel;

        return $this;
    }

    /**
     * Remove rhuEmpleadosPaisRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosPaisRel
     */
    public function removeRhuEmpleadosPaisRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosPaisRel)
    {
        $this->rhuEmpleadosPaisRel->removeElement($rhuEmpleadosPaisRel);
    }

    /**
     * Get rhuEmpleadosPaisRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuEmpleadosPaisRel()
    {
        return $this->rhuEmpleadosPaisRel;
    }
}
