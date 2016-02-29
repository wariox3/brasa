<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="tur_recurso")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurRecursoRepository")
 * @DoctrineAssert\UniqueEntity(fields={"numeroIdentificacion"},message="Ya existe este número de identificación")
 */
class TurRecurso
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_recurso_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRecursoPk;    
    
    /**
     * @ORM\Column(name="codigo_recurso_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoRecursoTipoFk;    
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk;    
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;            
    
    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=false, unique=true)     
     */         
    private $numeroIdentificacion;    
    
    /**
     * @ORM\Column(name="nombre_corto", type="string", length=120, nullable=true)
     */    
    private $nombreCorto;    
    
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
     * @ORM\Column(name="correo", type="string", length=80, nullable=true)
     */    
    private $correo; 
    
    /**
     * @ORM\Column(name="fecha_nacimiento", type="date", nullable=true)
     */ 
    private $fechaNacimiento;               
    
    /**
     * @ORM\Column(name="ruta_foto", type="string", length=250, nullable=true)
     */    
    private $rutaFoto;    
    
    /**     
     * @ORM\Column(name="pago_promedio", type="boolean")
     */    
    private $pagoPromedio = false;    

    /**     
     * @ORM\Column(name="pago_variable", type="boolean")
     */    
    private $pagoVariable = false;                
    
    /**
     * @ORM\Column(name="apodo", type="string", length=80, nullable=true)
     */    
    private $apodo;     
    
    /**     
     * @ORM\Column(name="estado_activo", type="boolean")
     */    
    private $estadoActivo = true;    
    
    /**
     * @ORM\Column(name="codigo_turno_fijo_nomina_fk", type="string", length=5, nullable=true)
     */    
    private $codigoTurnoFijoNominaFk;     
    
    /**
     * @ORM\Column(name="codigo_turno_fijo_descanso_fk", type="string", length=5, nullable=true)
     */    
    private $codigoTurnoFijoDescansoFk;     
    
    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */    
    private $usuario;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;     
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuEmpleado", inversedBy="turRecursosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurRecursoTipo", inversedBy="recursosRecursoTipoRel")
     * @ORM\JoinColumn(name="codigo_recurso_tipo_fk", referencedColumnName="codigo_recurso_tipo_pk")
     */
    protected $recursoTipoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurCentroCosto", inversedBy="recursosCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurProgramacionDetalle", mappedBy="recursoRel")
     */
    protected $programacionesDetallesRecursoRel;    

   /**
     * @ORM\OneToMany(targetEntity="TurSoportePago", mappedBy="recursoRel")
     */
    protected $soportesPagosRecursoRel;      
    
   /**
     * @ORM\OneToMany(targetEntity="TurSoportePagoDetalle", mappedBy="recursoRel")
     */
    protected $soportesPagosDetallesRecursoRel;            
    
    /**
     * @ORM\OneToMany(targetEntity="TurPedidoDetalleRecurso", mappedBy="recursoRel")
     */
    protected $pedidosDetallesRecursosRecursoRel;     

    /**
     * @ORM\OneToMany(targetEntity="TurServicioDetalleRecurso", mappedBy="recursoRel")
     */
    protected $serviciosDetallesRecursosRecursoRel;     
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->programacionesDetallesRecursoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->soportesPagosRecursoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->soportesPagosDetallesRecursoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pedidosDetallesRecursosRecursoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->serviciosDetallesRecursosRecursoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoRecursoPk
     *
     * @return integer
     */
    public function getCodigoRecursoPk()
    {
        return $this->codigoRecursoPk;
    }

    /**
     * Set codigoRecursoTipoFk
     *
     * @param integer $codigoRecursoTipoFk
     *
     * @return TurRecurso
     */
    public function setCodigoRecursoTipoFk($codigoRecursoTipoFk)
    {
        $this->codigoRecursoTipoFk = $codigoRecursoTipoFk;

        return $this;
    }

    /**
     * Get codigoRecursoTipoFk
     *
     * @return integer
     */
    public function getCodigoRecursoTipoFk()
    {
        return $this->codigoRecursoTipoFk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return TurRecurso
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
     * Set numeroIdentificacion
     *
     * @param string $numeroIdentificacion
     *
     * @return TurRecurso
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
     * @return TurRecurso
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
     * Set telefono
     *
     * @param string $telefono
     *
     * @return TurRecurso
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
     * @return TurRecurso
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
     * @return TurRecurso
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
     * Set correo
     *
     * @param string $correo
     *
     * @return TurRecurso
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
     * @return TurRecurso
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * Get fechaNacimiento
     *
     * @return \DateTime
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * Set rutaFoto
     *
     * @param string $rutaFoto
     *
     * @return TurRecurso
     */
    public function setRutaFoto($rutaFoto)
    {
        $this->rutaFoto = $rutaFoto;

        return $this;
    }

    /**
     * Get rutaFoto
     *
     * @return string
     */
    public function getRutaFoto()
    {
        return $this->rutaFoto;
    }

    /**
     * Set pagoPromedio
     *
     * @param boolean $pagoPromedio
     *
     * @return TurRecurso
     */
    public function setPagoPromedio($pagoPromedio)
    {
        $this->pagoPromedio = $pagoPromedio;

        return $this;
    }

    /**
     * Get pagoPromedio
     *
     * @return boolean
     */
    public function getPagoPromedio()
    {
        return $this->pagoPromedio;
    }

    /**
     * Set pagoVariable
     *
     * @param boolean $pagoVariable
     *
     * @return TurRecurso
     */
    public function setPagoVariable($pagoVariable)
    {
        $this->pagoVariable = $pagoVariable;

        return $this;
    }

    /**
     * Get pagoVariable
     *
     * @return boolean
     */
    public function getPagoVariable()
    {
        return $this->pagoVariable;
    }

    /**
     * Set apodo
     *
     * @param string $apodo
     *
     * @return TurRecurso
     */
    public function setApodo($apodo)
    {
        $this->apodo = $apodo;

        return $this;
    }

    /**
     * Get apodo
     *
     * @return string
     */
    public function getApodo()
    {
        return $this->apodo;
    }

    /**
     * Set estadoActivo
     *
     * @param boolean $estadoActivo
     *
     * @return TurRecurso
     */
    public function setEstadoActivo($estadoActivo)
    {
        $this->estadoActivo = $estadoActivo;

        return $this;
    }

    /**
     * Get estadoActivo
     *
     * @return boolean
     */
    public function getEstadoActivo()
    {
        return $this->estadoActivo;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return TurRecurso
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return TurRecurso
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
     * Set recursoTipoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurRecursoTipo $recursoTipoRel
     *
     * @return TurRecurso
     */
    public function setRecursoTipoRel(\Brasa\TurnoBundle\Entity\TurRecursoTipo $recursoTipoRel = null)
    {
        $this->recursoTipoRel = $recursoTipoRel;

        return $this;
    }

    /**
     * Get recursoTipoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurRecursoTipo
     */
    public function getRecursoTipoRel()
    {
        return $this->recursoTipoRel;
    }

    /**
     * Add programacionesDetallesRecursoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesRecursoRel
     *
     * @return TurRecurso
     */
    public function addProgramacionesDetallesRecursoRel(\Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesRecursoRel)
    {
        $this->programacionesDetallesRecursoRel[] = $programacionesDetallesRecursoRel;

        return $this;
    }

    /**
     * Remove programacionesDetallesRecursoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesRecursoRel
     */
    public function removeProgramacionesDetallesRecursoRel(\Brasa\TurnoBundle\Entity\TurProgramacionDetalle $programacionesDetallesRecursoRel)
    {
        $this->programacionesDetallesRecursoRel->removeElement($programacionesDetallesRecursoRel);
    }

    /**
     * Get programacionesDetallesRecursoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgramacionesDetallesRecursoRel()
    {
        return $this->programacionesDetallesRecursoRel;
    }

    /**
     * Add soportesPagosRecursoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePago $soportesPagosRecursoRel
     *
     * @return TurRecurso
     */
    public function addSoportesPagosRecursoRel(\Brasa\TurnoBundle\Entity\TurSoportePago $soportesPagosRecursoRel)
    {
        $this->soportesPagosRecursoRel[] = $soportesPagosRecursoRel;

        return $this;
    }

    /**
     * Remove soportesPagosRecursoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePago $soportesPagosRecursoRel
     */
    public function removeSoportesPagosRecursoRel(\Brasa\TurnoBundle\Entity\TurSoportePago $soportesPagosRecursoRel)
    {
        $this->soportesPagosRecursoRel->removeElement($soportesPagosRecursoRel);
    }

    /**
     * Get soportesPagosRecursoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSoportesPagosRecursoRel()
    {
        return $this->soportesPagosRecursoRel;
    }

    /**
     * Add soportesPagosDetallesRecursoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesRecursoRel
     *
     * @return TurRecurso
     */
    public function addSoportesPagosDetallesRecursoRel(\Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesRecursoRel)
    {
        $this->soportesPagosDetallesRecursoRel[] = $soportesPagosDetallesRecursoRel;

        return $this;
    }

    /**
     * Remove soportesPagosDetallesRecursoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesRecursoRel
     */
    public function removeSoportesPagosDetallesRecursoRel(\Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesRecursoRel)
    {
        $this->soportesPagosDetallesRecursoRel->removeElement($soportesPagosDetallesRecursoRel);
    }

    /**
     * Get soportesPagosDetallesRecursoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSoportesPagosDetallesRecursoRel()
    {
        return $this->soportesPagosDetallesRecursoRel;
    }

    /**
     * Add pedidosDetallesRecursosRecursoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalleRecurso $pedidosDetallesRecursosRecursoRel
     *
     * @return TurRecurso
     */
    public function addPedidosDetallesRecursosRecursoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalleRecurso $pedidosDetallesRecursosRecursoRel)
    {
        $this->pedidosDetallesRecursosRecursoRel[] = $pedidosDetallesRecursosRecursoRel;

        return $this;
    }

    /**
     * Remove pedidosDetallesRecursosRecursoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPedidoDetalleRecurso $pedidosDetallesRecursosRecursoRel
     */
    public function removePedidosDetallesRecursosRecursoRel(\Brasa\TurnoBundle\Entity\TurPedidoDetalleRecurso $pedidosDetallesRecursosRecursoRel)
    {
        $this->pedidosDetallesRecursosRecursoRel->removeElement($pedidosDetallesRecursosRecursoRel);
    }

    /**
     * Get pedidosDetallesRecursosRecursoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPedidosDetallesRecursosRecursoRel()
    {
        return $this->pedidosDetallesRecursosRecursoRel;
    }

    /**
     * Add serviciosDetallesRecursosRecursoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalleRecurso $serviciosDetallesRecursosRecursoRel
     *
     * @return TurRecurso
     */
    public function addServiciosDetallesRecursosRecursoRel(\Brasa\TurnoBundle\Entity\TurServicioDetalleRecurso $serviciosDetallesRecursosRecursoRel)
    {
        $this->serviciosDetallesRecursosRecursoRel[] = $serviciosDetallesRecursosRecursoRel;

        return $this;
    }

    /**
     * Remove serviciosDetallesRecursosRecursoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalleRecurso $serviciosDetallesRecursosRecursoRel
     */
    public function removeServiciosDetallesRecursosRecursoRel(\Brasa\TurnoBundle\Entity\TurServicioDetalleRecurso $serviciosDetallesRecursosRecursoRel)
    {
        $this->serviciosDetallesRecursosRecursoRel->removeElement($serviciosDetallesRecursosRecursoRel);
    }

    /**
     * Get serviciosDetallesRecursosRecursoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosDetallesRecursosRecursoRel()
    {
        return $this->serviciosDetallesRecursosRecursoRel;
    }

    /**
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return TurRecurso
     */
    public function setCodigoCentroCostoFk($codigoCentroCostoFk)
    {
        $this->codigoCentroCostoFk = $codigoCentroCostoFk;

        return $this;
    }

    /**
     * Get codigoCentroCostoFk
     *
     * @return integer
     */
    public function getCodigoCentroCostoFk()
    {
        return $this->codigoCentroCostoFk;
    }

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCentroCosto $centroCostoRel
     *
     * @return TurRecurso
     */
    public function setCentroCostoRel(\Brasa\TurnoBundle\Entity\TurCentroCosto $centroCostoRel = null)
    {
        $this->centroCostoRel = $centroCostoRel;

        return $this;
    }

    /**
     * Get centroCostoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurCentroCosto
     */
    public function getCentroCostoRel()
    {
        return $this->centroCostoRel;
    }

    /**
     * Set codigoTurnoFijoNominaFk
     *
     * @param string $codigoTurnoFijoNominaFk
     *
     * @return TurRecurso
     */
    public function setCodigoTurnoFijoNominaFk($codigoTurnoFijoNominaFk)
    {
        $this->codigoTurnoFijoNominaFk = $codigoTurnoFijoNominaFk;

        return $this;
    }

    /**
     * Get codigoTurnoFijoNominaFk
     *
     * @return string
     */
    public function getCodigoTurnoFijoNominaFk()
    {
        return $this->codigoTurnoFijoNominaFk;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     *
     * @return TurRecurso
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return string
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set codigoTurnoFijoDescansoFk
     *
     * @param string $codigoTurnoFijoDescansoFk
     *
     * @return TurRecurso
     */
    public function setCodigoTurnoFijoDescansoFk($codigoTurnoFijoDescansoFk)
    {
        $this->codigoTurnoFijoDescansoFk = $codigoTurnoFijoDescansoFk;

        return $this;
    }

    /**
     * Get codigoTurnoFijoDescansoFk
     *
     * @return string
     */
    public function getCodigoTurnoFijoDescansoFk()
    {
        return $this->codigoTurnoFijoDescansoFk;
    }
}
