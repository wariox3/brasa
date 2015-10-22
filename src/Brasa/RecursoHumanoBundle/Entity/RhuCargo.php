<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_cargo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCargoRepository")
 */
class RhuCargo
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cargo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCargoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;          
    
    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="cargoRel")
     */
    protected $contratosCargoRel;    

    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="cargoRel")
     */
    protected $empleadosCargoRel;   
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSsoAporte", mappedBy="cargoRel")
     */
    protected $ssoAportesCargoRel;    

    /**
     * @ORM\OneToMany(targetEntity="RhuRequisitoCargo", mappedBy="cargoRel")
     */
    protected $requisitosCargosCargoRel;    

    /**
     * @ORM\OneToMany(targetEntity="RhuRequisito", mappedBy="cargoRel")
     */
    protected $requisitosCargoRel;    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contratosCargoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->empleadosCargoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ssoAportesCargoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->requisitosCargosCargoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoCargoPk
     *
     * @return integer
     */
    public function getCodigoCargoPk()
    {
        return $this->codigoCargoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuCargo
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
     * Add contratosCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosCargoRel
     *
     * @return RhuCargo
     */
    public function addContratosCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosCargoRel)
    {
        $this->contratosCargoRel[] = $contratosCargoRel;

        return $this;
    }

    /**
     * Remove contratosCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosCargoRel
     */
    public function removeContratosCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosCargoRel)
    {
        $this->contratosCargoRel->removeElement($contratosCargoRel);
    }

    /**
     * Get contratosCargoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosCargoRel()
    {
        return $this->contratosCargoRel;
    }

    /**
     * Add empleadosCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosCargoRel
     *
     * @return RhuCargo
     */
    public function addEmpleadosCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosCargoRel)
    {
        $this->empleadosCargoRel[] = $empleadosCargoRel;

        return $this;
    }

    /**
     * Remove empleadosCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosCargoRel
     */
    public function removeEmpleadosCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosCargoRel)
    {
        $this->empleadosCargoRel->removeElement($empleadosCargoRel);
    }

    /**
     * Get empleadosCargoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosCargoRel()
    {
        return $this->empleadosCargoRel;
    }

    /**
     * Add ssoAportesCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesCargoRel
     *
     * @return RhuCargo
     */
    public function addSsoAportesCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesCargoRel)
    {
        $this->ssoAportesCargoRel[] = $ssoAportesCargoRel;

        return $this;
    }

    /**
     * Remove ssoAportesCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesCargoRel
     */
    public function removeSsoAportesCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesCargoRel)
    {
        $this->ssoAportesCargoRel->removeElement($ssoAportesCargoRel);
    }

    /**
     * Get ssoAportesCargoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSsoAportesCargoRel()
    {
        return $this->ssoAportesCargoRel;
    }

    /**
     * Add requisitosCargosCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoCargo $requisitosCargosCargoRel
     *
     * @return RhuCargo
     */
    public function addRequisitosCargosCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuRequisitoCargo $requisitosCargosCargoRel)
    {
        $this->requisitosCargosCargoRel[] = $requisitosCargosCargoRel;

        return $this;
    }

    /**
     * Remove requisitosCargosCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoCargo $requisitosCargosCargoRel
     */
    public function removeRequisitosCargosCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuRequisitoCargo $requisitosCargosCargoRel)
    {
        $this->requisitosCargosCargoRel->removeElement($requisitosCargosCargoRel);
    }

    /**
     * Get requisitosCargosCargoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRequisitosCargosCargoRel()
    {
        return $this->requisitosCargosCargoRel;
    }

    /**
     * Add requisitosCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuRequisito $requisitosCargoRel
     *
     * @return RhuCargo
     */
    public function addRequisitosCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuRequisito $requisitosCargoRel)
    {
        $this->requisitosCargoRel[] = $requisitosCargoRel;

        return $this;
    }

    /**
     * Remove requisitosCargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuRequisito $requisitosCargoRel
     */
    public function removeRequisitosCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuRequisito $requisitosCargoRel)
    {
        $this->requisitosCargoRel->removeElement($requisitosCargoRel);
    }

    /**
     * Get requisitosCargoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRequisitosCargoRel()
    {
        return $this->requisitosCargoRel;
    }
}
