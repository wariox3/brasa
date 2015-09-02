<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_empleado_estudio")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEmpleadoEstudioRepository")
 */
class RhuEmpleadoEstudio
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_empleado_estudio_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEmpleadoEstudioPk;
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */    
    private $codigoEmpleadoFk;
    
    /**
     * @ORM\Column(name="codigo_empleado_estudio_tipo_fk", type="integer")
     */    
    private $codigoEmpleadoEstudioTipoFk;
    
    /**
     * @ORM\Column(name="institucion", type="string", length=150, nullable=true)
     */    
    private $institucion;
    
    /**
     * @ORM\Column(name="aprovados", type="string", length=100, nullable=true)
     */    
    private $aprobados;
    
    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadFk;
    
    /**
     * @ORM\Column(name="titulo", type="string", length=120, nullable=true)
     */    
    private $titulo;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="empleadosEstudiosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleadoEstudioTipo", inversedBy="empleadosEstudiosEmpleadoEstudioTipoRel")
     * @ORM\JoinColumn(name="codigo_empleado_estudio_tipo_fk", referencedColumnName="codigo_empleado_estudio_tipo_pk")
     */
    protected $empleadoEstudioTipoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuEmpleadosEstudiosCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;
    


    /**
     * Get codigoEmpleadoEstudioPk
     *
     * @return integer
     */
    public function getCodigoEmpleadoEstudioPk()
    {
        return $this->codigoEmpleadoEstudioPk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuEmpleadoEstudio
     */
    public function setCodigoEmpleadoFk($codigoEmpleadoFk)
    {
        $this->codigoEmpleadoFk = $codigoEmpleadoFk;

        return $this;
    }

    /**
     * Get codigoEmpleadoFk
     *
     * @return integer
     */
    public function getCodigoEmpleadoFk()
    {
        return $this->codigoEmpleadoFk;
    }

    /**
     * Set codigoEmpleadoEstudioTipoFk
     *
     * @param integer $codigoEmpleadoEstudioTipoFk
     *
     * @return RhuEmpleadoEstudio
     */
    public function setCodigoEmpleadoEstudioTipoFk($codigoEmpleadoEstudioTipoFk)
    {
        $this->codigoEmpleadoEstudioTipoFk = $codigoEmpleadoEstudioTipoFk;

        return $this;
    }

    /**
     * Get codigoEmpleadoEstudioTipoFk
     *
     * @return integer
     */
    public function getCodigoEmpleadoEstudioTipoFk()
    {
        return $this->codigoEmpleadoEstudioTipoFk;
    }

    /**
     * Set institucion
     *
     * @param string $institucion
     *
     * @return RhuEmpleadoEstudio
     */
    public function setInstitucion($institucion)
    {
        $this->institucion = $institucion;

        return $this;
    }

    /**
     * Get institucion
     *
     * @return string
     */
    public function getInstitucion()
    {
        return $this->institucion;
    }

    /**
     * Set aprobados
     *
     * @param string $aprobados
     *
     * @return RhuEmpleadoEstudio
     */
    public function setAprobados($aprobados)
    {
        $this->aprobados = $aprobados;

        return $this;
    }

    /**
     * Get aprobados
     *
     * @return string
     */
    public function getAprobados()
    {
        return $this->aprobados;
    }

    /**
     * Set codigoCiudadFk
     *
     * @param integer $codigoCiudadFk
     *
     * @return RhuEmpleadoEstudio
     */
    public function setCodigoCiudadFk($codigoCiudadFk)
    {
        $this->codigoCiudadFk = $codigoCiudadFk;

        return $this;
    }

    /**
     * Get codigoCiudadFk
     *
     * @return integer
     */
    public function getCodigoCiudadFk()
    {
        return $this->codigoCiudadFk;
    }

    /**
     * Set titulo
     *
     * @param string $titulo
     *
     * @return RhuEmpleadoEstudio
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuEmpleadoEstudio
     */
    public function setEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel = null)
    {
        $this->empleadoRel = $empleadoRel;

        return $this;
    }

    /**
     * Get empleadoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado
     */
    public function getEmpleadoRel()
    {
        return $this->empleadoRel;
    }

    /**
     * Set empleadoEstudioTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudioTipo $empleadoEstudioTipoRel
     *
     * @return RhuEmpleadoEstudio
     */
    public function setEmpleadoEstudioTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudioTipo $empleadoEstudioTipoRel = null)
    {
        $this->empleadoEstudioTipoRel = $empleadoEstudioTipoRel;

        return $this;
    }

    /**
     * Get empleadoEstudioTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudioTipo
     */
    public function getEmpleadoEstudioTipoRel()
    {
        return $this->empleadoEstudioTipoRel;
    }

    /**
     * Set ciudadRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel
     *
     * @return RhuEmpleadoEstudio
     */
    public function setCiudadRel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel = null)
    {
        $this->ciudadRel = $ciudadRel;

        return $this;
    }

    /**
     * Get ciudadRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudad
     */
    public function getCiudadRel()
    {
        return $this->ciudadRel;
    }
}
