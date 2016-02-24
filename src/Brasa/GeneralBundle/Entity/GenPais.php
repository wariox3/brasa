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
     * @ORM\OneToMany(targetEntity="GenDepartamento", mappedBy="paisRel")
     */
    protected $departamentoRel;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->departamentoRel = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add departamentoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenDepartamento $departamentoRel
     *
     * @return GenPais
     */
    public function addDepartamentoRel(\Brasa\GeneralBundle\Entity\GenDepartamento $departamentoRel)
    {
        $this->departamentoRel[] = $departamentoRel;

        return $this;
    }

    /**
     * Remove departamentoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenDepartamento $departamentoRel
     */
    public function removeDepartamentoRel(\Brasa\GeneralBundle\Entity\GenDepartamento $departamentoRel)
    {
        $this->departamentoRel->removeElement($departamentoRel);
    }

    /**
     * Get departamentoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDepartamentoRel()
    {
        return $this->departamentoRel;
    }
}
