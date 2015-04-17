<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_empleado")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEmpleadoRepository")
 */
class RhuEmpleado
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_empleado_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEmpleadoPk;
    
    /**
     * @ORM\Column(name="numeroIdentificacion", type="string", length=20, nullable=false)
     */    
    private $numeroIdentificacion;        
    
    /**
     * @ORM\Column(name="nombre_corto", type="string", length=80, nullable=true)
     */    
    private $nombreCorto;    

    /**
     * @ORM\Column(name="nombre1", type="string", length=30, nullable=true)
     */    
    private $nombre1;        

    /**
     * @ORM\Column(name="nombre2", type="string", length=30, nullable=true)
     */    
    private $nombre2;    
    
    /**
     * @ORM\Column(name="apellido1", type="string", length=30, nullable=true)
     */    
    private $apellido1;    

    /**
     * @ORM\Column(name="apellido2", type="string", length=30, nullable=true)
     */    
    private $apellido2;    
    
    /**
     * @ORM\Column(name="telefono", type="string", length=15, nullable=true)
     */    
    private $telefono;    
    
    /**
     * @ORM\Column(name="celular", type="string", length=20, nullable=true)
     */    
    private $celular; 
    
    /**
     * @ORM\Column(name="direccion", type="string", length=30, nullable=true)
     */    
    private $direccion; 
    
    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadFk;    
    
    /**
     * @ORM\Column(name="barrio", type="string", length=80, nullable=true)
     */    
    private $barrio;    
    
    /**
     * @ORM\Column(name="codigo_rh_fk", type="integer", nullable=true)
     */    
    private $codigoRhFk;     
    
    /**
     * @ORM\Column(name="codigo_sexo_fk", type="integer", nullable=true)
     */    
    private $codigoSexoFk;     
    
    /**
     * @ORM\Column(name="correo", type="string", length=80, nullable=true)
     */    
    private $correo;     
        
    /**
     * @ORM\Column(name="fecha_nacimiento", type="date", nullable=true)
     */    
    private $fecha_nacimiento;     
    
    /**
     * @ORM\Column(name="codigo_estado_civil_fk", type="integer", nullable=true)
     */    
    private $codigoEstadoCivilFk;
    
    /**
     * @ORM\Column(name="cuenta", type="string", length=80, nullable=true)
     */    
    private $cuenta;    
    
    /**
     * @ORM\Column(name="codigo_banco_fk", type="integer", nullable=true)
     */    
    private $codigoBancoFk;         
    
    /**
     * @ORM\Column(name="codigo_centro_costos_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostosFk;    
        
    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="empleadoRel")
     */
    protected $contratosEmpleadoRel;    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contratosEmpleadoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoEmpleadoPk
     *
     * @return integer
     */
    public function getCodigoEmpleadoPk()
    {
        return $this->codigoEmpleadoPk;
    }

    /**
     * Set numeroIdentificacion
     *
     * @param string $numeroIdentificacion
     *
     * @return RhuEmpleado
     */
    public function setNumeroIdentificacion($numeroIdentificacion)
    {
        $this->numeroIdentificacion = $numeroIdentificacion;

        return $this;
    }

    /**
     * Get numeroIdentificacion
     *
     * @return string
     */
    public function getNumeroIdentificacion()
    {
        return $this->numeroIdentificacion;
    }

    /**
     * Set nombreCorto
     *
     * @param string $nombreCorto
     *
     * @return RhuEmpleado
     */
    public function setNombreCorto($nombreCorto)
    {
        $this->nombreCorto = $nombreCorto;

        return $this;
    }

    /**
     * Get nombreCorto
     *
     * @return string
     */
    public function getNombreCorto()
    {
        return $this->nombreCorto;
    }

    /**
     * Set nombre1
     *
     * @param string $nombre1
     *
     * @return RhuEmpleado
     */
    public function setNombre1($nombre1)
    {
        $this->nombre1 = $nombre1;

        return $this;
    }

    /**
     * Get nombre1
     *
     * @return string
     */
    public function getNombre1()
    {
        return $this->nombre1;
    }

    /**
     * Set nombre2
     *
     * @param string $nombre2
     *
     * @return RhuEmpleado
     */
    public function setNombre2($nombre2)
    {
        $this->nombre2 = $nombre2;

        return $this;
    }

    /**
     * Get nombre2
     *
     * @return string
     */
    public function getNombre2()
    {
        return $this->nombre2;
    }

    /**
     * Set apellido1
     *
     * @param string $apellido1
     *
     * @return RhuEmpleado
     */
    public function setApellido1($apellido1)
    {
        $this->apellido1 = $apellido1;

        return $this;
    }

    /**
     * Get apellido1
     *
     * @return string
     */
    public function getApellido1()
    {
        return $this->apellido1;
    }

    /**
     * Set apellido2
     *
     * @param string $apellido2
     *
     * @return RhuEmpleado
     */
    public function setApellido2($apellido2)
    {
        $this->apellido2 = $apellido2;

        return $this;
    }

    /**
     * Get apellido2
     *
     * @return string
     */
    public function getApellido2()
    {
        return $this->apellido2;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return RhuEmpleado
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
     * Set celular
     *
     * @param string $celular
     *
     * @return RhuEmpleado
     */
    public function setCelular($celular)
    {
        $this->celular = $celular;

        return $this;
    }

    /**
     * Get celular
     *
     * @return string
     */
    public function getCelular()
    {
        return $this->celular;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return RhuEmpleado
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
     * Set codigoCiudadFk
     *
     * @param integer $codigoCiudadFk
     *
     * @return RhuEmpleado
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
     * Set barrio
     *
     * @param string $barrio
     *
     * @return RhuEmpleado
     */
    public function setBarrio($barrio)
    {
        $this->barrio = $barrio;

        return $this;
    }

    /**
     * Get barrio
     *
     * @return string
     */
    public function getBarrio()
    {
        return $this->barrio;
    }

    /**
     * Set codigoRhFk
     *
     * @param integer $codigoRhFk
     *
     * @return RhuEmpleado
     */
    public function setCodigoRhFk($codigoRhFk)
    {
        $this->codigoRhFk = $codigoRhFk;

        return $this;
    }

    /**
     * Get codigoRhFk
     *
     * @return integer
     */
    public function getCodigoRhFk()
    {
        return $this->codigoRhFk;
    }

    /**
     * Set codigoSexoFk
     *
     * @param integer $codigoSexoFk
     *
     * @return RhuEmpleado
     */
    public function setCodigoSexoFk($codigoSexoFk)
    {
        $this->codigoSexoFk = $codigoSexoFk;

        return $this;
    }

    /**
     * Get codigoSexoFk
     *
     * @return integer
     */
    public function getCodigoSexoFk()
    {
        return $this->codigoSexoFk;
    }

    /**
     * Set correo
     *
     * @param string $correo
     *
     * @return RhuEmpleado
     */
    public function setCorreo($correo)
    {
        $this->correo = $correo;

        return $this;
    }

    /**
     * Get correo
     *
     * @return string
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * Set fechaNacimiento
     *
     * @param \DateTime $fechaNacimiento
     *
     * @return RhuEmpleado
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fecha_nacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * Get fechaNacimiento
     *
     * @return \DateTime
     */
    public function getFechaNacimiento()
    {
        return $this->fecha_nacimiento;
    }

    /**
     * Set codigoEstadoCivilFk
     *
     * @param integer $codigoEstadoCivilFk
     *
     * @return RhuEmpleado
     */
    public function setCodigoEstadoCivilFk($codigoEstadoCivilFk)
    {
        $this->codigoEstadoCivilFk = $codigoEstadoCivilFk;

        return $this;
    }

    /**
     * Get codigoEstadoCivilFk
     *
     * @return integer
     */
    public function getCodigoEstadoCivilFk()
    {
        return $this->codigoEstadoCivilFk;
    }

    /**
     * Set cuenta
     *
     * @param string $cuenta
     *
     * @return RhuEmpleado
     */
    public function setCuenta($cuenta)
    {
        $this->cuenta = $cuenta;

        return $this;
    }

    /**
     * Get cuenta
     *
     * @return string
     */
    public function getCuenta()
    {
        return $this->cuenta;
    }

    /**
     * Set codigoBancoFk
     *
     * @param integer $codigoBancoFk
     *
     * @return RhuEmpleado
     */
    public function setCodigoBancoFk($codigoBancoFk)
    {
        $this->codigoBancoFk = $codigoBancoFk;

        return $this;
    }

    /**
     * Get codigoBancoFk
     *
     * @return integer
     */
    public function getCodigoBancoFk()
    {
        return $this->codigoBancoFk;
    }

    /**
     * Set codigoCentroCostosFk
     *
     * @param integer $codigoCentroCostosFk
     *
     * @return RhuEmpleado
     */
    public function setCodigoCentroCostosFk($codigoCentroCostosFk)
    {
        $this->codigoCentroCostosFk = $codigoCentroCostosFk;

        return $this;
    }

    /**
     * Get codigoCentroCostosFk
     *
     * @return integer
     */
    public function getCodigoCentroCostosFk()
    {
        return $this->codigoCentroCostosFk;
    }

    /**
     * Add contratosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosEmpleadoRel
     *
     * @return RhuEmpleado
     */
    public function addContratosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosEmpleadoRel)
    {
        $this->contratosEmpleadoRel[] = $contratosEmpleadoRel;

        return $this;
    }

    /**
     * Remove contratosEmpleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosEmpleadoRel
     */
    public function removeContratosEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosEmpleadoRel)
    {
        $this->contratosEmpleadoRel->removeElement($contratosEmpleadoRel);
    }

    /**
     * Get contratosEmpleadoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosEmpleadoRel()
    {
        return $this->contratosEmpleadoRel;
    }
}
