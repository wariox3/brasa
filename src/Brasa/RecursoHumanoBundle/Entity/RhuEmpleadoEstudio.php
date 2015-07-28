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
     * @ORM\Column(name="secundaria", type="string", length=100, nullable=true)
     */    
    private $secundaria;
    
    /**
     * @ORM\Column(name="institucion_secundaria", type="string", length=100, nullable=true)
     */    
    private $institucionSecundaria;
    
    /**
     * @ORM\Column(name="aprovados_secundaria", type="string", length=100, nullable=true)
     */    
    private $aprobadosSecundaria;
    
    /**
     * @ORM\Column(name="codigo_ciudad_secundaria_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadSecundariaFk;
    
    /**
     * @ORM\Column(name="titulo_secundaria", type="string", length=100, nullable=true)
     */    
    private $tituloSecundaria;
    
    /**
     * @ORM\Column(name="tecnica", type="string", length=100, nullable=true)
     */    
    private $tecnica;
    
    /**
     * @ORM\Column(name="institucion_tecnica", type="string", length=100, nullable=true)
     */    
    private $institucionTecnica;
    
    /**
     * @ORM\Column(name="aprovados_tecnica", type="string", length=100, nullable=true)
     */    
    private $aprobadosTecnica;
    
    /**
     * @ORM\Column(name="codigo_ciudad_tecnica_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadTecnicaFk;
    
    /**
     * @ORM\Column(name="titulo_Tecnica", type="string", length=100, nullable=true)
     */    
    private $tituloTecnica;
    
    /**
     * @ORM\Column(name="tecnologia", type="string", length=100, nullable=true)
     */    
    private $tecnologia;
    
    /**
     * @ORM\Column(name="institucion_tecnologia", type="string", length=100, nullable=true)
     */    
    private $institucionTecnologia;
    
    /**
     * @ORM\Column(name="aprovados_tecnologia", type="string", length=100, nullable=true)
     */    
    private $aprobadosTecnologia;
    
    /**
     * @ORM\Column(name="codigo_ciudad_tecnologia_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadTecnologiaFk;
    
    /**
     * @ORM\Column(name="titulo_tecnologia", type="string", length=100, nullable=true)
     */    
    private $tituloTecnologia;
    
    /**
     * @ORM\Column(name="universitario", type="string", length=100, nullable=true)
     */    
    private $universitario;
    
    /**
     * @ORM\Column(name="institucion_universitario", type="string", length=100, nullable=true)
     */    
    private $institucionUniversitario;
    
    /**
     * @ORM\Column(name="aprovados_universitario", type="string", length=100, nullable=true)
     */    
    private $aprobadosUniversitario;
    
    /**
     * @ORM\Column(name="codigo_ciudad_universitario_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadUniversitarioFk;
    
    /**
     * @ORM\Column(name="titulo_universitario", type="string", length=100, nullable=true)
     */    
    private $tituloUniversitario;
    
    /**
     * @ORM\Column(name="otro1", type="string", length=100, nullable=true)
     */    
    private $otro1;
    
    /**
     * @ORM\Column(name="institucion_otro1", type="string", length=100, nullable=true)
     */    
    private $institucionOtro1;
    
    /**
     * @ORM\Column(name="aprovados_otro1", type="string", length=100, nullable=true)
     */    
    private $aprobadosOtro1;
    
    /**
     * @ORM\Column(name="codigo_ciudad_otro1_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadOtro1Fk;
    
    /**
     * @ORM\Column(name="titulo_otro1", type="string", length=100, nullable=true)
     */    
    private $tituloOtro1;
    
    /**
     * @ORM\Column(name="otro2", type="string", length=100, nullable=true)
     */    
    private $otro2;
    
    /**
     * @ORM\Column(name="institucion_otro2", type="string", length=100, nullable=true)
     */    
    private $institucionOtro2;
    
    /**
     * @ORM\Column(name="aprovados_otro2", type="string", length=100, nullable=true)
     */    
    private $aprobadosOtro2;
    
    /**
     * @ORM\Column(name="codigo_ciudad_otro2_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadOtro2Fk;
    
    /**
     * @ORM\Column(name="titulo_otro2", type="string", length=100, nullable=true)
     */    
    private $tituloOtro2;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="empleadosEmpleadoEstudioRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuEmpleadoEstudioCiudadSecundariaRel")
     * @ORM\JoinColumn(name="codigo_ciudad_secundaria_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadSecundariaRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuEmpleadoEstudioCiudadtecnicaRel")
     * @ORM\JoinColumn(name="codigo_ciudad_tecnica_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadTecnicaRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuEmpleadoEstudioCiudadTecnologiaRel")
     * @ORM\JoinColumn(name="codigo_ciudad_tecnologia_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadTecnologiaRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuEmpleadoEstudioCiudadUniversitarioRel")
     * @ORM\JoinColumn(name="codigo_ciudad_universitario_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadUniversitarioRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuEmpleadoEstudioCiudadOtro1Rel")
     * @ORM\JoinColumn(name="codigo_ciudad_otro1_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadOtro1Rel;
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuEmpleadoEstudioCiudadOtro2Rel")
     * @ORM\JoinColumn(name="codigo_ciudad_otro2_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadOtro2Rel;

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
     * Set secundaria
     *
     * @param string $secundaria
     *
     * @return RhuEmpleadoEstudio
     */
    public function setSecundaria($secundaria)
    {
        $this->secundaria = $secundaria;

        return $this;
    }

    /**
     * Get secundaria
     *
     * @return string
     */
    public function getSecundaria()
    {
        return $this->secundaria;
    }

    /**
     * Set institucionSecundaria
     *
     * @param string $institucionSecundaria
     *
     * @return RhuEmpleadoEstudio
     */
    public function setInstitucionSecundaria($institucionSecundaria)
    {
        $this->institucionSecundaria = $institucionSecundaria;

        return $this;
    }

    /**
     * Get institucionSecundaria
     *
     * @return string
     */
    public function getInstitucionSecundaria()
    {
        return $this->institucionSecundaria;
    }

    /**
     * Set aprobadosSecundaria
     *
     * @param string $aprobadosSecundaria
     *
     * @return RhuEmpleadoEstudio
     */
    public function setAprobadosSecundaria($aprobadosSecundaria)
    {
        $this->aprobadosSecundaria = $aprobadosSecundaria;

        return $this;
    }

    /**
     * Get aprobadosSecundaria
     *
     * @return string
     */
    public function getAprobadosSecundaria()
    {
        return $this->aprobadosSecundaria;
    }

    /**
     * Set codigoCiudadSecundariaFk
     *
     * @param integer $codigoCiudadSecundariaFk
     *
     * @return RhuEmpleadoEstudio
     */
    public function setCodigoCiudadSecundariaFk($codigoCiudadSecundariaFk)
    {
        $this->codigoCiudadSecundariaFk = $codigoCiudadSecundariaFk;

        return $this;
    }

    /**
     * Get codigoCiudadSecundariaFk
     *
     * @return integer
     */
    public function getCodigoCiudadSecundariaFk()
    {
        return $this->codigoCiudadSecundariaFk;
    }

    /**
     * Set tituloSecundaria
     *
     * @param string $tituloSecundaria
     *
     * @return RhuEmpleadoEstudio
     */
    public function setTituloSecundaria($tituloSecundaria)
    {
        $this->tituloSecundaria = $tituloSecundaria;

        return $this;
    }

    /**
     * Get tituloSecundaria
     *
     * @return string
     */
    public function getTituloSecundaria()
    {
        return $this->tituloSecundaria;
    }

    /**
     * Set tecnica
     *
     * @param string $tecnica
     *
     * @return RhuEmpleadoEstudio
     */
    public function setTecnica($tecnica)
    {
        $this->tecnica = $tecnica;

        return $this;
    }

    /**
     * Get tecnica
     *
     * @return string
     */
    public function getTecnica()
    {
        return $this->tecnica;
    }

    /**
     * Set institucionTecnica
     *
     * @param string $institucionTecnica
     *
     * @return RhuEmpleadoEstudio
     */
    public function setInstitucionTecnica($institucionTecnica)
    {
        $this->institucionTecnica = $institucionTecnica;

        return $this;
    }

    /**
     * Get institucionTecnica
     *
     * @return string
     */
    public function getInstitucionTecnica()
    {
        return $this->institucionTecnica;
    }

    /**
     * Set aprobadosTecnica
     *
     * @param string $aprobadosTecnica
     *
     * @return RhuEmpleadoEstudio
     */
    public function setAprobadosTecnica($aprobadosTecnica)
    {
        $this->aprobadosTecnica = $aprobadosTecnica;

        return $this;
    }

    /**
     * Get aprobadosTecnica
     *
     * @return string
     */
    public function getAprobadosTecnica()
    {
        return $this->aprobadosTecnica;
    }

    /**
     * Set codigoCiudadTecnicaFk
     *
     * @param integer $codigoCiudadTecnicaFk
     *
     * @return RhuEmpleadoEstudio
     */
    public function setCodigoCiudadTecnicaFk($codigoCiudadTecnicaFk)
    {
        $this->codigoCiudadTecnicaFk = $codigoCiudadTecnicaFk;

        return $this;
    }

    /**
     * Get codigoCiudadTecnicaFk
     *
     * @return integer
     */
    public function getCodigoCiudadTecnicaFk()
    {
        return $this->codigoCiudadTecnicaFk;
    }

    /**
     * Set tituloTecnica
     *
     * @param string $tituloTecnica
     *
     * @return RhuEmpleadoEstudio
     */
    public function setTituloTecnica($tituloTecnica)
    {
        $this->tituloTecnica = $tituloTecnica;

        return $this;
    }

    /**
     * Get tituloTecnica
     *
     * @return string
     */
    public function getTituloTecnica()
    {
        return $this->tituloTecnica;
    }

    /**
     * Set tecnologia
     *
     * @param string $tecnologia
     *
     * @return RhuEmpleadoEstudio
     */
    public function setTecnologia($tecnologia)
    {
        $this->tecnologia = $tecnologia;

        return $this;
    }

    /**
     * Get tecnologia
     *
     * @return string
     */
    public function getTecnologia()
    {
        return $this->tecnologia;
    }

    /**
     * Set institucionTecnologia
     *
     * @param string $institucionTecnologia
     *
     * @return RhuEmpleadoEstudio
     */
    public function setInstitucionTecnologia($institucionTecnologia)
    {
        $this->institucionTecnologia = $institucionTecnologia;

        return $this;
    }

    /**
     * Get institucionTecnologia
     *
     * @return string
     */
    public function getInstitucionTecnologia()
    {
        return $this->institucionTecnologia;
    }

    /**
     * Set aprobadosTecnologia
     *
     * @param string $aprobadosTecnologia
     *
     * @return RhuEmpleadoEstudio
     */
    public function setAprobadosTecnologia($aprobadosTecnologia)
    {
        $this->aprobadosTecnologia = $aprobadosTecnologia;

        return $this;
    }

    /**
     * Get aprobadosTecnologia
     *
     * @return string
     */
    public function getAprobadosTecnologia()
    {
        return $this->aprobadosTecnologia;
    }

    /**
     * Set codigoCiudadTecnologiaFk
     *
     * @param integer $codigoCiudadTecnologiaFk
     *
     * @return RhuEmpleadoEstudio
     */
    public function setCodigoCiudadTecnologiaFk($codigoCiudadTecnologiaFk)
    {
        $this->codigoCiudadTecnologiaFk = $codigoCiudadTecnologiaFk;

        return $this;
    }

    /**
     * Get codigoCiudadTecnologiaFk
     *
     * @return integer
     */
    public function getCodigoCiudadTecnologiaFk()
    {
        return $this->codigoCiudadTecnologiaFk;
    }

    /**
     * Set tituloTecnologia
     *
     * @param string $tituloTecnologia
     *
     * @return RhuEmpleadoEstudio
     */
    public function setTituloTecnologia($tituloTecnologia)
    {
        $this->tituloTecnologia = $tituloTecnologia;

        return $this;
    }

    /**
     * Get tituloTecnologia
     *
     * @return string
     */
    public function getTituloTecnologia()
    {
        return $this->tituloTecnologia;
    }

    /**
     * Set universitario
     *
     * @param string $universitario
     *
     * @return RhuEmpleadoEstudio
     */
    public function setUniversitario($universitario)
    {
        $this->universitario = $universitario;

        return $this;
    }

    /**
     * Get universitario
     *
     * @return string
     */
    public function getUniversitario()
    {
        return $this->universitario;
    }

    /**
     * Set institucionUniversitario
     *
     * @param string $institucionUniversitario
     *
     * @return RhuEmpleadoEstudio
     */
    public function setInstitucionUniversitario($institucionUniversitario)
    {
        $this->institucionUniversitario = $institucionUniversitario;

        return $this;
    }

    /**
     * Get institucionUniversitario
     *
     * @return string
     */
    public function getInstitucionUniversitario()
    {
        return $this->institucionUniversitario;
    }

    /**
     * Set aprobadosUniversitario
     *
     * @param string $aprobadosUniversitario
     *
     * @return RhuEmpleadoEstudio
     */
    public function setAprobadosUniversitario($aprobadosUniversitario)
    {
        $this->aprobadosUniversitario = $aprobadosUniversitario;

        return $this;
    }

    /**
     * Get aprobadosUniversitario
     *
     * @return string
     */
    public function getAprobadosUniversitario()
    {
        return $this->aprobadosUniversitario;
    }

    /**
     * Set codigoCiudadUniversitarioFk
     *
     * @param integer $codigoCiudadUniversitarioFk
     *
     * @return RhuEmpleadoEstudio
     */
    public function setCodigoCiudadUniversitarioFk($codigoCiudadUniversitarioFk)
    {
        $this->codigoCiudadUniversitarioFk = $codigoCiudadUniversitarioFk;

        return $this;
    }

    /**
     * Get codigoCiudadUniversitarioFk
     *
     * @return integer
     */
    public function getCodigoCiudadUniversitarioFk()
    {
        return $this->codigoCiudadUniversitarioFk;
    }

    /**
     * Set tituloUniversitario
     *
     * @param string $tituloUniversitario
     *
     * @return RhuEmpleadoEstudio
     */
    public function setTituloUniversitario($tituloUniversitario)
    {
        $this->tituloUniversitario = $tituloUniversitario;

        return $this;
    }

    /**
     * Get tituloUniversitario
     *
     * @return string
     */
    public function getTituloUniversitario()
    {
        return $this->tituloUniversitario;
    }

    /**
     * Set otro1
     *
     * @param string $otro1
     *
     * @return RhuEmpleadoEstudio
     */
    public function setOtro1($otro1)
    {
        $this->otro1 = $otro1;

        return $this;
    }

    /**
     * Get otro1
     *
     * @return string
     */
    public function getOtro1()
    {
        return $this->otro1;
    }

    /**
     * Set institucionOtro1
     *
     * @param string $institucionOtro1
     *
     * @return RhuEmpleadoEstudio
     */
    public function setInstitucionOtro1($institucionOtro1)
    {
        $this->institucionOtro1 = $institucionOtro1;

        return $this;
    }

    /**
     * Get institucionOtro1
     *
     * @return string
     */
    public function getInstitucionOtro1()
    {
        return $this->institucionOtro1;
    }

    /**
     * Set aprobadosOtro1
     *
     * @param string $aprobadosOtro1
     *
     * @return RhuEmpleadoEstudio
     */
    public function setAprobadosOtro1($aprobadosOtro1)
    {
        $this->aprobadosOtro1 = $aprobadosOtro1;

        return $this;
    }

    /**
     * Get aprobadosOtro1
     *
     * @return string
     */
    public function getAprobadosOtro1()
    {
        return $this->aprobadosOtro1;
    }

    /**
     * Set codigoCiudadOtro1Fk
     *
     * @param integer $codigoCiudadOtro1Fk
     *
     * @return RhuEmpleadoEstudio
     */
    public function setCodigoCiudadOtro1Fk($codigoCiudadOtro1Fk)
    {
        $this->codigoCiudadOtro1Fk = $codigoCiudadOtro1Fk;

        return $this;
    }

    /**
     * Get codigoCiudadOtro1Fk
     *
     * @return integer
     */
    public function getCodigoCiudadOtro1Fk()
    {
        return $this->codigoCiudadOtro1Fk;
    }

    /**
     * Set tituloOtro1
     *
     * @param string $tituloOtro1
     *
     * @return RhuEmpleadoEstudio
     */
    public function setTituloOtro1($tituloOtro1)
    {
        $this->tituloOtro1 = $tituloOtro1;

        return $this;
    }

    /**
     * Get tituloOtro1
     *
     * @return string
     */
    public function getTituloOtro1()
    {
        return $this->tituloOtro1;
    }

    /**
     * Set otro2
     *
     * @param string $otro2
     *
     * @return RhuEmpleadoEstudio
     */
    public function setOtro2($otro2)
    {
        $this->otro2 = $otro2;

        return $this;
    }

    /**
     * Get otro2
     *
     * @return string
     */
    public function getOtro2()
    {
        return $this->otro2;
    }

    /**
     * Set institucionOtro2
     *
     * @param string $institucionOtro2
     *
     * @return RhuEmpleadoEstudio
     */
    public function setInstitucionOtro2($institucionOtro2)
    {
        $this->institucionOtro2 = $institucionOtro2;

        return $this;
    }

    /**
     * Get institucionOtro2
     *
     * @return string
     */
    public function getInstitucionOtro2()
    {
        return $this->institucionOtro2;
    }

    /**
     * Set aprobadosOtro2
     *
     * @param string $aprobadosOtro2
     *
     * @return RhuEmpleadoEstudio
     */
    public function setAprobadosOtro2($aprobadosOtro2)
    {
        $this->aprobadosOtro2 = $aprobadosOtro2;

        return $this;
    }

    /**
     * Get aprobadosOtro2
     *
     * @return string
     */
    public function getAprobadosOtro2()
    {
        return $this->aprobadosOtro2;
    }

    /**
     * Set codigoCiudadOtro2Fk
     *
     * @param integer $codigoCiudadOtro2Fk
     *
     * @return RhuEmpleadoEstudio
     */
    public function setCodigoCiudadOtro2Fk($codigoCiudadOtro2Fk)
    {
        $this->codigoCiudadOtro2Fk = $codigoCiudadOtro2Fk;

        return $this;
    }

    /**
     * Get codigoCiudadOtro2Fk
     *
     * @return integer
     */
    public function getCodigoCiudadOtro2Fk()
    {
        return $this->codigoCiudadOtro2Fk;
    }

    /**
     * Set tituloOtro2
     *
     * @param string $tituloOtro2
     *
     * @return RhuEmpleadoEstudio
     */
    public function setTituloOtro2($tituloOtro2)
    {
        $this->tituloOtro2 = $tituloOtro2;

        return $this;
    }

    /**
     * Get tituloOtro2
     *
     * @return string
     */
    public function getTituloOtro2()
    {
        return $this->tituloOtro2;
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
     * Set ciudadSecundariaRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadSecundariaRel
     *
     * @return RhuEmpleadoEstudio
     */
    public function setCiudadSecundariaRel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadSecundariaRel = null)
    {
        $this->ciudadSecundariaRel = $ciudadSecundariaRel;

        return $this;
    }

    /**
     * Get ciudadSecundariaRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudad
     */
    public function getCiudadSecundariaRel()
    {
        return $this->ciudadSecundariaRel;
    }

    /**
     * Set ciudadTecnicaRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadTecnicaRel
     *
     * @return RhuEmpleadoEstudio
     */
    public function setCiudadTecnicaRel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadTecnicaRel = null)
    {
        $this->ciudadTecnicaRel = $ciudadTecnicaRel;

        return $this;
    }

    /**
     * Get ciudadTecnicaRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudad
     */
    public function getCiudadTecnicaRel()
    {
        return $this->ciudadTecnicaRel;
    }

    /**
     * Set ciudadTecnologiaRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadTecnologiaRel
     *
     * @return RhuEmpleadoEstudio
     */
    public function setCiudadTecnologiaRel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadTecnologiaRel = null)
    {
        $this->ciudadTecnologiaRel = $ciudadTecnologiaRel;

        return $this;
    }

    /**
     * Get ciudadTecnologiaRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudad
     */
    public function getCiudadTecnologiaRel()
    {
        return $this->ciudadTecnologiaRel;
    }

    /**
     * Set ciudadUniversitarioRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadUniversitarioRel
     *
     * @return RhuEmpleadoEstudio
     */
    public function setCiudadUniversitarioRel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadUniversitarioRel = null)
    {
        $this->ciudadUniversitarioRel = $ciudadUniversitarioRel;

        return $this;
    }

    /**
     * Get ciudadUniversitarioRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudad
     */
    public function getCiudadUniversitarioRel()
    {
        return $this->ciudadUniversitarioRel;
    }

    /**
     * Set ciudadOtro1Rel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadOtro1Rel
     *
     * @return RhuEmpleadoEstudio
     */
    public function setCiudadOtro1Rel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadOtro1Rel = null)
    {
        $this->ciudadOtro1Rel = $ciudadOtro1Rel;

        return $this;
    }

    /**
     * Get ciudadOtro1Rel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudad
     */
    public function getCiudadOtro1Rel()
    {
        return $this->ciudadOtro1Rel;
    }

    /**
     * Set ciudadOtro2Rel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadOtro2Rel
     *
     * @return RhuEmpleadoEstudio
     */
    public function setCiudadOtro2Rel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadOtro2Rel = null)
    {
        $this->ciudadOtro2Rel = $ciudadOtro2Rel;

        return $this;
    }

    /**
     * Get ciudadOtro2Rel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudad
     */
    public function getCiudadOtro2Rel()
    {
        return $this->ciudadOtro2Rel;
    }
}
