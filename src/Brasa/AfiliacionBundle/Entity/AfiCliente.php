<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_cliente")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiClienteRepository")
 */
class AfiCliente
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cliente_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoClientePk;    
    
    /**
     * @ORM\Column(name="nit", type="string", length=15, nullable=false, unique=true)
     */
    private $nit;        
    
    /**
     * @ORM\Column(name="digito_verificacion", type="string", length=1, nullable=true)
     */
    private $digitoVerificacion;             
    
    /**
     * @ORM\Column(name="nombre_corto", type="string", length=50)
     */
    private $nombreCorto;                             
    
    /**
     * @ORM\Column(name="codigo_asesor_fk", type="integer", nullable=true)
     */    
    private $codigoAsesorFk;   
    
    /**
     * @ORM\Column(name="codigo_forma_pago_fk", type="integer", nullable=true)
     */    
    private $codigoFormaPagoFk;     
    
    /**
     * @ORM\Column(name="plazo_pago", type="integer")
     */    
    private $plazoPago = 0;    
    
    /**
     * @ORM\Column(name="direccion", type="string", length=120, nullable=true)
     */
    private $direccion;

    /**
     * @ORM\Column(name="barrio", type="string", length=120, nullable=true)
     */
    private $barrio;    
    
    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */
    private $codigoCiudadFk;         
    
    /**
     * @ORM\Column(name="telefono", type="string", length=30, nullable=true)
     */
    private $telefono;     
    
    /**
     * @ORM\Column(name="celular", type="string", length=20, nullable=true, nullable=true)
     */
    private $celular;    
        
    /**
     * @ORM\Column(name="fax", type="string", length=20, nullable=true, nullable=true)
     */
    private $fax;    
    
    /**
     * @ORM\Column(name="email", type="string", length=80, nullable=true)
     */
    private $email;             
    
    /**
     * @ORM\Column(name="contacto", type="string", length=80, nullable=true)
     */
    private $contacto;    

    /**
     * @ORM\Column(name="calular_contacto", type="string", length=20, nullable=true)
     */
    private $celularContacto;     

    /**
     * @ORM\Column(name="telefono_contacto", type="string", length=20, nullable=true)
     */
    private $telefonoContacto;    
    
    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */    
    private $usuario;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;                            
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenAsesor", inversedBy="afiClientesAsesorRel")
     * @ORM\JoinColumn(name="codigo_asesor_fk", referencedColumnName="codigo_asesor_pk")
     */
    protected $asesorRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenFormaPago", inversedBy="afiClientesFormaPagoRel")
     * @ORM\JoinColumn(name="codigo_forma_pago_fk", referencedColumnName="codigo_forma_pago_pk")
     */
    protected $formaPagoRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="afiClientesCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;             

    /**
     * @ORM\OneToMany(targetEntity="AfiContrato", mappedBy="clienteRel")
     */
    protected $contratosClienteRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="AfiPeriodo", mappedBy="clienteRel")
     */
    protected $periodosClienteRel;    

    /**
     * @ORM\OneToMany(targetEntity="AfiFactura", mappedBy="clienteRel")
     */
    protected $facturasClienteRel;    

    /**
     * @ORM\OneToMany(targetEntity="AfiCurso", mappedBy="clienteRel")
     */
    protected $cursosClienteRel;     

    /**
     * @ORM\OneToMany(targetEntity="AfiServicio", mappedBy="clienteRel")
     */
    protected $serviciosClienteRel;
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contratosClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->periodosClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->facturasClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cursosClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->serviciosClienteRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoClientePk
     *
     * @return integer
     */
    public function getCodigoClientePk()
    {
        return $this->codigoClientePk;
    }

    /**
     * Set nit
     *
     * @param string $nit
     *
     * @return AfiCliente
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
     * Set digitoVerificacion
     *
     * @param string $digitoVerificacion
     *
     * @return AfiCliente
     */
    public function setDigitoVerificacion($digitoVerificacion)
    {
        $this->digitoVerificacion = $digitoVerificacion;

        return $this;
    }

    /**
     * Get digitoVerificacion
     *
     * @return string
     */
    public function getDigitoVerificacion()
    {
        return $this->digitoVerificacion;
    }

    /**
     * Set nombreCorto
     *
     * @param string $nombreCorto
     *
     * @return AfiCliente
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
     * Set plazoPago
     *
     * @param integer $plazoPago
     *
     * @return AfiCliente
     */
    public function setPlazoPago($plazoPago)
    {
        $this->plazoPago = $plazoPago;

        return $this;
    }

    /**
     * Get plazoPago
     *
     * @return integer
     */
    public function getPlazoPago()
    {
        return $this->plazoPago;
    }

    /**
     * Set codigoFormaPagoFk
     *
     * @param integer $codigoFormaPagoFk
     *
     * @return AfiCliente
     */
    public function setCodigoFormaPagoFk($codigoFormaPagoFk)
    {
        $this->codigoFormaPagoFk = $codigoFormaPagoFk;

        return $this;
    }

    /**
     * Get codigoFormaPagoFk
     *
     * @return integer
     */
    public function getCodigoFormaPagoFk()
    {
        return $this->codigoFormaPagoFk;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return AfiCliente
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
     * Set barrio
     *
     * @param string $barrio
     *
     * @return AfiCliente
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
     * Set codigoCiudadFk
     *
     * @param integer $codigoCiudadFk
     *
     * @return AfiCliente
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
     * Set telefono
     *
     * @param string $telefono
     *
     * @return AfiCliente
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
     * @return AfiCliente
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
     * Set fax
     *
     * @param string $fax
     *
     * @return AfiCliente
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return AfiCliente
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set contacto
     *
     * @param string $contacto
     *
     * @return AfiCliente
     */
    public function setContacto($contacto)
    {
        $this->contacto = $contacto;

        return $this;
    }

    /**
     * Get contacto
     *
     * @return string
     */
    public function getContacto()
    {
        return $this->contacto;
    }

    /**
     * Set celularContacto
     *
     * @param string $celularContacto
     *
     * @return AfiCliente
     */
    public function setCelularContacto($celularContacto)
    {
        $this->celularContacto = $celularContacto;

        return $this;
    }

    /**
     * Get celularContacto
     *
     * @return string
     */
    public function getCelularContacto()
    {
        return $this->celularContacto;
    }

    /**
     * Set telefonoContacto
     *
     * @param string $telefonoContacto
     *
     * @return AfiCliente
     */
    public function setTelefonoContacto($telefonoContacto)
    {
        $this->telefonoContacto = $telefonoContacto;

        return $this;
    }

    /**
     * Get telefonoContacto
     *
     * @return string
     */
    public function getTelefonoContacto()
    {
        return $this->telefonoContacto;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     *
     * @return AfiCliente
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
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return AfiCliente
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
     * Set formaPagoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenFormaPago $formaPagoRel
     *
     * @return AfiCliente
     */
    public function setFormaPagoRel(\Brasa\GeneralBundle\Entity\GenFormaPago $formaPagoRel = null)
    {
        $this->formaPagoRel = $formaPagoRel;

        return $this;
    }

    /**
     * Get formaPagoRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenFormaPago
     */
    public function getFormaPagoRel()
    {
        return $this->formaPagoRel;
    }

    /**
     * Set ciudadRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel
     *
     * @return AfiCliente
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

    /**
     * Add contratosClienteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiContrato $contratosClienteRel
     *
     * @return AfiCliente
     */
    public function addContratosClienteRel(\Brasa\AfiliacionBundle\Entity\AfiContrato $contratosClienteRel)
    {
        $this->contratosClienteRel[] = $contratosClienteRel;

        return $this;
    }

    /**
     * Remove contratosClienteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiContrato $contratosClienteRel
     */
    public function removeContratosClienteRel(\Brasa\AfiliacionBundle\Entity\AfiContrato $contratosClienteRel)
    {
        $this->contratosClienteRel->removeElement($contratosClienteRel);
    }

    /**
     * Get contratosClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosClienteRel()
    {
        return $this->contratosClienteRel;
    }

    /**
     * Add periodosClienteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPeriodo $periodosClienteRel
     *
     * @return AfiCliente
     */
    public function addPeriodosClienteRel(\Brasa\AfiliacionBundle\Entity\AfiPeriodo $periodosClienteRel)
    {
        $this->periodosClienteRel[] = $periodosClienteRel;

        return $this;
    }

    /**
     * Remove periodosClienteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPeriodo $periodosClienteRel
     */
    public function removePeriodosClienteRel(\Brasa\AfiliacionBundle\Entity\AfiPeriodo $periodosClienteRel)
    {
        $this->periodosClienteRel->removeElement($periodosClienteRel);
    }

    /**
     * Get periodosClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPeriodosClienteRel()
    {
        return $this->periodosClienteRel;
    }

    /**
     * Add facturasClienteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiFactura $facturasClienteRel
     *
     * @return AfiCliente
     */
    public function addFacturasClienteRel(\Brasa\AfiliacionBundle\Entity\AfiFactura $facturasClienteRel)
    {
        $this->facturasClienteRel[] = $facturasClienteRel;

        return $this;
    }

    /**
     * Remove facturasClienteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiFactura $facturasClienteRel
     */
    public function removeFacturasClienteRel(\Brasa\AfiliacionBundle\Entity\AfiFactura $facturasClienteRel)
    {
        $this->facturasClienteRel->removeElement($facturasClienteRel);
    }

    /**
     * Get facturasClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasClienteRel()
    {
        return $this->facturasClienteRel;
    }

    /**
     * Add cursosClienteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCurso $cursosClienteRel
     *
     * @return AfiCliente
     */
    public function addCursosClienteRel(\Brasa\AfiliacionBundle\Entity\AfiCurso $cursosClienteRel)
    {
        $this->cursosClienteRel[] = $cursosClienteRel;

        return $this;
    }

    /**
     * Remove cursosClienteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCurso $cursosClienteRel
     */
    public function removeCursosClienteRel(\Brasa\AfiliacionBundle\Entity\AfiCurso $cursosClienteRel)
    {
        $this->cursosClienteRel->removeElement($cursosClienteRel);
    }

    /**
     * Get cursosClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCursosClienteRel()
    {
        return $this->cursosClienteRel;
    }

    /**
     * Add serviciosClienteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiServicio $serviciosClienteRel
     *
     * @return AfiCliente
     */
    public function addServiciosClienteRel(\Brasa\AfiliacionBundle\Entity\AfiServicio $serviciosClienteRel)
    {
        $this->serviciosClienteRel[] = $serviciosClienteRel;

        return $this;
    }

    /**
     * Remove serviciosClienteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiServicio $serviciosClienteRel
     */
    public function removeServiciosClienteRel(\Brasa\AfiliacionBundle\Entity\AfiServicio $serviciosClienteRel)
    {
        $this->serviciosClienteRel->removeElement($serviciosClienteRel);
    }

    /**
     * Get serviciosClienteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServiciosClienteRel()
    {
        return $this->serviciosClienteRel;
    }

    /**
     * Set codigoAsesorFk
     *
     * @param integer $codigoAsesorFk
     *
     * @return AfiCliente
     */
    public function setCodigoAsesorFk($codigoAsesorFk)
    {
        $this->codigoAsesorFk = $codigoAsesorFk;

        return $this;
    }

    /**
     * Get codigoAsesorFk
     *
     * @return integer
     */
    public function getCodigoAsesorFk()
    {
        return $this->codigoAsesorFk;
    }

    /**
     * Set asesorRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenAsesor $asesorRel
     *
     * @return AfiCliente
     */
    public function setAsesorRel(\Brasa\GeneralBundle\Entity\GenAsesor $asesorRel = null)
    {
        $this->asesorRel = $asesorRel;

        return $this;
    }

    /**
     * Get asesorRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenAsesor
     */
    public function getAsesorRel()
    {
        return $this->asesorRel;
    }
}
