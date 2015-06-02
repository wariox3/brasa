<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_tipo_pension")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuTipoPensionRepository")
 */
class RhuTipoPension
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_tipo_pension_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoTipoPensionPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;      

    /**
     * @ORM\Column(name="porcentajeCotizacion", type="float")
     */    
    private $porcentajeCotizacion = 0;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="tipoPensionRel")
     */
    protected $contratosTipoPensionRel;    

    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="tipoPensionRel")
     */
    protected $empleadosTipoPensionRel;     
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contratosTipoPensionRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->empleadosTipoPensionRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoTipoPensionPk
     *
     * @return integer
     */
    public function getCodigoTipoPensionPk()
    {
        return $this->codigoTipoPensionPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuTipoPension
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
     * Set porcentajeCotizacion
     *
     * @param float $porcentajeCotizacion
     *
     * @return RhuTipoPension
     */
    public function setPorcentajeCotizacion($porcentajeCotizacion)
    {
        $this->porcentajeCotizacion = $porcentajeCotizacion;

        return $this;
    }

    /**
     * Get porcentajeCotizacion
     *
     * @return float
     */
    public function getPorcentajeCotizacion()
    {
        return $this->porcentajeCotizacion;
    }

    /**
     * Add contratosTipoPensionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosTipoPensionRel
     *
     * @return RhuTipoPension
     */
    public function addContratosTipoPensionRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosTipoPensionRel)
    {
        $this->contratosTipoPensionRel[] = $contratosTipoPensionRel;

        return $this;
    }

    /**
     * Remove contratosTipoPensionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosTipoPensionRel
     */
    public function removeContratosTipoPensionRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosTipoPensionRel)
    {
        $this->contratosTipoPensionRel->removeElement($contratosTipoPensionRel);
    }

    /**
     * Get contratosTipoPensionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosTipoPensionRel()
    {
        return $this->contratosTipoPensionRel;
    }

    /**
     * Add empleadosTipoPensionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosTipoPensionRel
     *
     * @return RhuTipoPension
     */
    public function addEmpleadosTipoPensionRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosTipoPensionRel)
    {
        $this->empleadosTipoPensionRel[] = $empleadosTipoPensionRel;

        return $this;
    }

    /**
     * Remove empleadosTipoPensionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosTipoPensionRel
     */
    public function removeEmpleadosTipoPensionRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosTipoPensionRel)
    {
        $this->empleadosTipoPensionRel->removeElement($empleadosTipoPensionRel);
    }

    /**
     * Get empleadosTipoPensionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosTipoPensionRel()
    {
        return $this->empleadosTipoPensionRel;
    }
}
