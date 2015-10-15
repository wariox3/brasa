<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_entidad_salud")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEntidadSaludRepository")
 */
class RhuEntidadSalud
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_entidad_salud_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEntidadSaludPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=120, nullable=true)
     */    
    private $nombre;    
    
    /**
     * @ORM\Column(name="nit", type="string", length=10, nullable=true)
     */    
    private $nit;    
    
    /**
     * @ORM\Column(name="direccion", type="string", length=80, nullable=true)
     */    
    private $direccion;    
    
    /**
     * @ORM\Column(name="telefono", type="string", length=15, nullable=true)
     */    
    private $telefono; 
    
    /**
     * @ORM\Column(name="codigo_interface", type="string", length=20, nullable=true)
     */    
    private $codigoInterface;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="entidadSaludRel")
     */
    protected $empleadosEntidadSaludRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="entidadSaludRel")
     */
    protected $contratoEntidadSaludRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleadoFamilia", mappedBy="entidadSaludRel")
     */
    protected $empleadosFamiliasEntidadSaludRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuIncapacidadPago", mappedBy="entidadSaludRel")
     */
    protected $incapacidadesPagosEntidadSaludRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuIncapacidad", mappedBy="entidadSaludRel")
     */
    protected $incapacidadesEntidadSaludRel;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosEntidadSaludRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contratoEntidadSaludRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->empleadosFamiliasEntidadSaludRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->incapacidadesPagosEntidadSaludRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->incapacidadesEntidadSaludRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoEntidadSaludPk
     *
     * @return integer
     */
    public function getCodigoEntidadSaludPk()
    {
        return $this->codigoEntidadSaludPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuEntidadSalud
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
     * Set nit
     *
     * @param string $nit
     *
     * @return RhuEntidadSalud
     */
    public function setNit($nit)
    {
        $this->nit = $nit;

        return $this;
    }

    /**
     * Get nit
     *
     * @return string
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return RhuEntidadSalud
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return RhuEntidadSalud
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set codigoInterface
     *
     * @param string $codigoInterface
     *
     * @return RhuEntidadSalud
     */
    public function setCodigoInterface($codigoInterface)
    {
        $this->codigoInterface = $codigoInterface;

        return $this;
    }

    /**
     * Get codigoInterface
     *
     * @return string
     */
    public function getCodigoInterface()
    {
        return $this->codigoInterface;
    }

    /**
     * Add empleadosEntidadSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEntidadSaludRel
     *
     * @return RhuEntidadSalud
     */
    public function addEmpleadosEntidadSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEntidadSaludRel)
    {
        $this->empleadosEntidadSaludRel[] = $empleadosEntidadSaludRel;

        return $this;
    }

    /**
     * Remove empleadosEntidadSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEntidadSaludRel
     */
    public function removeEmpleadosEntidadSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEntidadSaludRel)
    {
        $this->empleadosEntidadSaludRel->removeElement($empleadosEntidadSaludRel);
    }

    /**
     * Get empleadosEntidadSaludRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosEntidadSaludRel()
    {
        return $this->empleadosEntidadSaludRel;
    }

    /**
     * Add contratoEntidadSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoEntidadSaludRel
     *
     * @return RhuEntidadSalud
     */
    public function addContratoEntidadSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoEntidadSaludRel)
    {
        $this->contratoEntidadSaludRel[] = $contratoEntidadSaludRel;

        return $this;
    }

    /**
     * Remove contratoEntidadSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoEntidadSaludRel
     */
    public function removeContratoEntidadSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoEntidadSaludRel)
    {
        $this->contratoEntidadSaludRel->removeElement($contratoEntidadSaludRel);
    }

    /**
     * Get contratoEntidadSaludRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratoEntidadSaludRel()
    {
        return $this->contratoEntidadSaludRel;
    }

    /**
     * Add empleadosFamiliasEntidadSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamilia $empleadosFamiliasEntidadSaludRel
     *
     * @return RhuEntidadSalud
     */
    public function addEmpleadosFamiliasEntidadSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamilia $empleadosFamiliasEntidadSaludRel)
    {
        $this->empleadosFamiliasEntidadSaludRel[] = $empleadosFamiliasEntidadSaludRel;

        return $this;
    }

    /**
     * Remove empleadosFamiliasEntidadSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamilia $empleadosFamiliasEntidadSaludRel
     */
    public function removeEmpleadosFamiliasEntidadSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamilia $empleadosFamiliasEntidadSaludRel)
    {
        $this->empleadosFamiliasEntidadSaludRel->removeElement($empleadosFamiliasEntidadSaludRel);
    }

    /**
     * Get empleadosFamiliasEntidadSaludRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosFamiliasEntidadSaludRel()
    {
        return $this->empleadosFamiliasEntidadSaludRel;
    }

    /**
     * Add incapacidadesPagosEntidadSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadPago $incapacidadesPagosEntidadSaludRel
     *
     * @return RhuEntidadSalud
     */
    public function addIncapacidadesPagosEntidadSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadPago $incapacidadesPagosEntidadSaludRel)
    {
        $this->incapacidadesPagosEntidadSaludRel[] = $incapacidadesPagosEntidadSaludRel;

        return $this;
    }

    /**
     * Remove incapacidadesPagosEntidadSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadPago $incapacidadesPagosEntidadSaludRel
     */
    public function removeIncapacidadesPagosEntidadSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadPago $incapacidadesPagosEntidadSaludRel)
    {
        $this->incapacidadesPagosEntidadSaludRel->removeElement($incapacidadesPagosEntidadSaludRel);
    }

    /**
     * Get incapacidadesPagosEntidadSaludRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIncapacidadesPagosEntidadSaludRel()
    {
        return $this->incapacidadesPagosEntidadSaludRel;
    }

    /**
     * Add incapacidadesEntidadSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesEntidadSaludRel
     *
     * @return RhuEntidadSalud
     */
    public function addIncapacidadesEntidadSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesEntidadSaludRel)
    {
        $this->incapacidadesEntidadSaludRel[] = $incapacidadesEntidadSaludRel;

        return $this;
    }

    /**
     * Remove incapacidadesEntidadSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesEntidadSaludRel
     */
    public function removeIncapacidadesEntidadSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesEntidadSaludRel)
    {
        $this->incapacidadesEntidadSaludRel->removeElement($incapacidadesEntidadSaludRel);
    }

    /**
     * Get incapacidadesEntidadSaludRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIncapacidadesEntidadSaludRel()
    {
        return $this->incapacidadesEntidadSaludRel;
    }
}
