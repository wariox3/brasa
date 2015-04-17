<?php

namespace Brasa\GeneralBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_departamento")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenDepartamentoRepository")
 */
class GenDepartamento
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_departamento_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDepartamentoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     * @Assert\NotNull()(message="Debe escribir un nombre")
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="GenCiudad", mappedBy="departamentoRel")
     */
    protected $ciudadesRel;

    public function __construct()
    {
        $this->ciudadesRel = new ArrayCollection();
    }




    /**
     * Get codigoDepartamentoPk
     *
     * @return integer
     */
    public function getCodigoDepartamentoPk()
    {
        return $this->codigoDepartamentoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return GenDepartamento
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
     * Add ciudadesRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadesRel
     *
     * @return GenDepartamento
     */
    public function addCiudadesRel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadesRel)
    {
        $this->ciudadesRel[] = $ciudadesRel;

        return $this;
    }

    /**
     * Remove ciudadesRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadesRel
     */
    public function removeCiudadesRel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadesRel)
    {
        $this->ciudadesRel->removeElement($ciudadesRel);
    }

    /**
     * Get ciudadesRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCiudadesRel()
    {
        return $this->ciudadesRel;
    }
}
