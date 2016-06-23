<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_pago_concepto")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPagoConceptoRepository")
 */
class RhuPagoConcepto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_concepto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPagoConceptoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;            
    
    /**
     * @ORM\Column(name="compone_salario", type="boolean")
     */    
    private $componeSalario = false; 

    /**
     * @ORM\Column(name="compone_porcentaje", type="boolean")
     */    
    private $componePorcentaje = false;     

    /**
     * @ORM\Column(name="compone_valor", type="boolean")
     */    
    private $componeValor = false;     
    
    /**
     * @ORM\Column(name="por_porcentaje", type="float")
     */
    private $porPorcentaje = 0;     
    
    /**
     * @ORM\Column(name="prestacional", type="boolean")
     */    
    private $prestacional = false;     
    
    /**
     * @ORM\Column(name="genera_ingreso_base_prestacion", type="boolean")
     */    
    private $generaIngresoBasePrestacion = false;    

    /**
     * @ORM\Column(name="genera_ingreso_base_cotizacion", type="boolean")
     */    
    private $generaIngresoBaseCotizacion = false;    
    
    /**
     * @ORM\Column(name="operacion", type="integer")
     */
    private $operacion = 0;            
    
    /**
     * @ORM\Column(name="concepto_adicion", type="boolean")
     */    
    private $conceptoAdicion = false;     
    
    /**
     * @ORM\Column(name="concepto_auxilio_transporte", type="boolean")
     */    
    private $conceptoAuxilioTransporte = false;     
    
    /**
     * @ORM\Column(name="concepto_incapacidad", type="boolean")
     */    
    private $conceptoIncapacidad = false;     

    /**
     * @ORM\Column(name="concepto_pension", type="boolean")
     */    
    private $conceptoPension = false;    

    /**
     * @ORM\Column(name="concepto_salud", type="boolean")
     */    
    private $conceptoSalud = false;        
    
    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaFk;     

    /**
     * @ORM\Column(name="tipo_cuenta", type="bigint")
     */     
    private $tipoCuenta = 1;     
    
    /**
     * @ORM\Column(name="codigo_cuenta_operacion_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaOperacionFk;    
    
    /**
     * @ORM\Column(name="tipo_cuenta_operacion", type="bigint", nullable=true)
     */     
    private $tipoCuentaOperacion = 1;   
    
    /**
     * 1=Bonificacion, 2=Descuento, 3=Comision
     * @ORM\Column(name="tipo_adicional", type="smallint")
     */    
    private $tipoAdicional = 1;       
    
    /**
     * @ORM\Column(name="codigo_interface", type="string", length=30, nullable=true)
     */    
    private $codigoInterface;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuPagoDetalle", mappedBy="pagoConceptoRel")
     */
    protected $pagosDetallesPagoConceptoRel;     

    /**
     * @ORM\OneToMany(targetEntity="RhuPagoDetalleSede", mappedBy="pagoConceptoRel")
     */
    protected $pagosDetallesSedesPagoConceptoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuPagoAdicional", mappedBy="pagoConceptoRel")
     */
    protected $pagosAdicionalesPagoConceptoRel;                                
    
    /**
     * @ORM\OneToMany(targetEntity="RhuLicenciaTipo", mappedBy="pagoConceptoRel")
     */
    protected $licenciasTiposPagoConceptoRel;         
    
    /**
     * @ORM\OneToMany(targetEntity="RhuIncapacidadTipo", mappedBy="pagoConceptoRel")
     */
    protected $incapacidadesTiposPagoConceptoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuTipoPension", mappedBy="pagoConceptoRel")
     */
    protected $tiposPensionesPagoConceptoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuTipoPension", mappedBy="pagoConceptoFondoRel")
     */
    protected $tiposPensionesPagoConceptoFondoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuTipoSalud", mappedBy="pagoConceptoRel")
     */
    protected $tiposSaludPagoConceptoRel;    
       


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pagosDetallesPagoConceptoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pagosDetallesSedesPagoConceptoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pagosAdicionalesPagoConceptoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->licenciasTiposPagoConceptoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->incapacidadesTiposPagoConceptoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tiposPensionesPagoConceptoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tiposSaludPagoConceptoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPagoConceptoPk
     *
     * @return integer
     */
    public function getCodigoPagoConceptoPk()
    {
        return $this->codigoPagoConceptoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuPagoConcepto
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
     * Set componeSalario
     *
     * @param boolean $componeSalario
     *
     * @return RhuPagoConcepto
     */
    public function setComponeSalario($componeSalario)
    {
        $this->componeSalario = $componeSalario;

        return $this;
    }

    /**
     * Get componeSalario
     *
     * @return boolean
     */
    public function getComponeSalario()
    {
        return $this->componeSalario;
    }

    /**
     * Set componePorcentaje
     *
     * @param boolean $componePorcentaje
     *
     * @return RhuPagoConcepto
     */
    public function setComponePorcentaje($componePorcentaje)
    {
        $this->componePorcentaje = $componePorcentaje;

        return $this;
    }

    /**
     * Get componePorcentaje
     *
     * @return boolean
     */
    public function getComponePorcentaje()
    {
        return $this->componePorcentaje;
    }

    /**
     * Set componeValor
     *
     * @param boolean $componeValor
     *
     * @return RhuPagoConcepto
     */
    public function setComponeValor($componeValor)
    {
        $this->componeValor = $componeValor;

        return $this;
    }

    /**
     * Get componeValor
     *
     * @return boolean
     */
    public function getComponeValor()
    {
        return $this->componeValor;
    }

    /**
     * Set porPorcentaje
     *
     * @param float $porPorcentaje
     *
     * @return RhuPagoConcepto
     */
    public function setPorPorcentaje($porPorcentaje)
    {
        $this->porPorcentaje = $porPorcentaje;

        return $this;
    }

    /**
     * Get porPorcentaje
     *
     * @return float
     */
    public function getPorPorcentaje()
    {
        return $this->porPorcentaje;
    }

    /**
     * Set prestacional
     *
     * @param boolean $prestacional
     *
     * @return RhuPagoConcepto
     */
    public function setPrestacional($prestacional)
    {
        $this->prestacional = $prestacional;

        return $this;
    }

    /**
     * Get prestacional
     *
     * @return boolean
     */
    public function getPrestacional()
    {
        return $this->prestacional;
    }

    /**
     * Set generaIngresoBasePrestacion
     *
     * @param boolean $generaIngresoBasePrestacion
     *
     * @return RhuPagoConcepto
     */
    public function setGeneraIngresoBasePrestacion($generaIngresoBasePrestacion)
    {
        $this->generaIngresoBasePrestacion = $generaIngresoBasePrestacion;

        return $this;
    }

    /**
     * Get generaIngresoBasePrestacion
     *
     * @return boolean
     */
    public function getGeneraIngresoBasePrestacion()
    {
        return $this->generaIngresoBasePrestacion;
    }

    /**
     * Set generaIngresoBaseCotizacion
     *
     * @param boolean $generaIngresoBaseCotizacion
     *
     * @return RhuPagoConcepto
     */
    public function setGeneraIngresoBaseCotizacion($generaIngresoBaseCotizacion)
    {
        $this->generaIngresoBaseCotizacion = $generaIngresoBaseCotizacion;

        return $this;
    }

    /**
     * Get generaIngresoBaseCotizacion
     *
     * @return boolean
     */
    public function getGeneraIngresoBaseCotizacion()
    {
        return $this->generaIngresoBaseCotizacion;
    }

    /**
     * Set operacion
     *
     * @param integer $operacion
     *
     * @return RhuPagoConcepto
     */
    public function setOperacion($operacion)
    {
        $this->operacion = $operacion;

        return $this;
    }

    /**
     * Get operacion
     *
     * @return integer
     */
    public function getOperacion()
    {
        return $this->operacion;
    }

    /**
     * Set conceptoAdicion
     *
     * @param boolean $conceptoAdicion
     *
     * @return RhuPagoConcepto
     */
    public function setConceptoAdicion($conceptoAdicion)
    {
        $this->conceptoAdicion = $conceptoAdicion;

        return $this;
    }

    /**
     * Get conceptoAdicion
     *
     * @return boolean
     */
    public function getConceptoAdicion()
    {
        return $this->conceptoAdicion;
    }

    /**
     * Set conceptoAuxilioTransporte
     *
     * @param boolean $conceptoAuxilioTransporte
     *
     * @return RhuPagoConcepto
     */
    public function setConceptoAuxilioTransporte($conceptoAuxilioTransporte)
    {
        $this->conceptoAuxilioTransporte = $conceptoAuxilioTransporte;

        return $this;
    }

    /**
     * Get conceptoAuxilioTransporte
     *
     * @return boolean
     */
    public function getConceptoAuxilioTransporte()
    {
        return $this->conceptoAuxilioTransporte;
    }

    /**
     * Set conceptoIncapacidad
     *
     * @param boolean $conceptoIncapacidad
     *
     * @return RhuPagoConcepto
     */
    public function setConceptoIncapacidad($conceptoIncapacidad)
    {
        $this->conceptoIncapacidad = $conceptoIncapacidad;

        return $this;
    }

    /**
     * Get conceptoIncapacidad
     *
     * @return boolean
     */
    public function getConceptoIncapacidad()
    {
        return $this->conceptoIncapacidad;
    }

    /**
     * Set conceptoPension
     *
     * @param boolean $conceptoPension
     *
     * @return RhuPagoConcepto
     */
    public function setConceptoPension($conceptoPension)
    {
        $this->conceptoPension = $conceptoPension;

        return $this;
    }

    /**
     * Get conceptoPension
     *
     * @return boolean
     */
    public function getConceptoPension()
    {
        return $this->conceptoPension;
    }

    /**
     * Set conceptoSalud
     *
     * @param boolean $conceptoSalud
     *
     * @return RhuPagoConcepto
     */
    public function setConceptoSalud($conceptoSalud)
    {
        $this->conceptoSalud = $conceptoSalud;

        return $this;
    }

    /**
     * Get conceptoSalud
     *
     * @return boolean
     */
    public function getConceptoSalud()
    {
        return $this->conceptoSalud;
    }

    /**
     * Set codigoCuentaFk
     *
     * @param string $codigoCuentaFk
     *
     * @return RhuPagoConcepto
     */
    public function setCodigoCuentaFk($codigoCuentaFk)
    {
        $this->codigoCuentaFk = $codigoCuentaFk;

        return $this;
    }

    /**
     * Get codigoCuentaFk
     *
     * @return string
     */
    public function getCodigoCuentaFk()
    {
        return $this->codigoCuentaFk;
    }

    /**
     * Set tipoCuenta
     *
     * @param integer $tipoCuenta
     *
     * @return RhuPagoConcepto
     */
    public function setTipoCuenta($tipoCuenta)
    {
        $this->tipoCuenta = $tipoCuenta;

        return $this;
    }

    /**
     * Get tipoCuenta
     *
     * @return integer
     */
    public function getTipoCuenta()
    {
        return $this->tipoCuenta;
    }

    /**
     * Set codigoCuentaOperacionFk
     *
     * @param string $codigoCuentaOperacionFk
     *
     * @return RhuPagoConcepto
     */
    public function setCodigoCuentaOperacionFk($codigoCuentaOperacionFk)
    {
        $this->codigoCuentaOperacionFk = $codigoCuentaOperacionFk;

        return $this;
    }

    /**
     * Get codigoCuentaOperacionFk
     *
     * @return string
     */
    public function getCodigoCuentaOperacionFk()
    {
        return $this->codigoCuentaOperacionFk;
    }

    /**
     * Set tipoCuentaOperacion
     *
     * @param integer $tipoCuentaOperacion
     *
     * @return RhuPagoConcepto
     */
    public function setTipoCuentaOperacion($tipoCuentaOperacion)
    {
        $this->tipoCuentaOperacion = $tipoCuentaOperacion;

        return $this;
    }

    /**
     * Get tipoCuentaOperacion
     *
     * @return integer
     */
    public function getTipoCuentaOperacion()
    {
        return $this->tipoCuentaOperacion;
    }

    /**
     * Set tipoAdicional
     *
     * @param integer $tipoAdicional
     *
     * @return RhuPagoConcepto
     */
    public function setTipoAdicional($tipoAdicional)
    {
        $this->tipoAdicional = $tipoAdicional;

        return $this;
    }

    /**
     * Get tipoAdicional
     *
     * @return integer
     */
    public function getTipoAdicional()
    {
        return $this->tipoAdicional;
    }

    /**
     * Add pagosDetallesPagoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle $pagosDetallesPagoConceptoRel
     *
     * @return RhuPagoConcepto
     */
    public function addPagosDetallesPagoConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle $pagosDetallesPagoConceptoRel)
    {
        $this->pagosDetallesPagoConceptoRel[] = $pagosDetallesPagoConceptoRel;

        return $this;
    }

    /**
     * Remove pagosDetallesPagoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle $pagosDetallesPagoConceptoRel
     */
    public function removePagosDetallesPagoConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle $pagosDetallesPagoConceptoRel)
    {
        $this->pagosDetallesPagoConceptoRel->removeElement($pagosDetallesPagoConceptoRel);
    }

    /**
     * Get pagosDetallesPagoConceptoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosDetallesPagoConceptoRel()
    {
        return $this->pagosDetallesPagoConceptoRel;
    }

    /**
     * Add pagosDetallesSedesPagoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalleSede $pagosDetallesSedesPagoConceptoRel
     *
     * @return RhuPagoConcepto
     */
    public function addPagosDetallesSedesPagoConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalleSede $pagosDetallesSedesPagoConceptoRel)
    {
        $this->pagosDetallesSedesPagoConceptoRel[] = $pagosDetallesSedesPagoConceptoRel;

        return $this;
    }

    /**
     * Remove pagosDetallesSedesPagoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalleSede $pagosDetallesSedesPagoConceptoRel
     */
    public function removePagosDetallesSedesPagoConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalleSede $pagosDetallesSedesPagoConceptoRel)
    {
        $this->pagosDetallesSedesPagoConceptoRel->removeElement($pagosDetallesSedesPagoConceptoRel);
    }

    /**
     * Get pagosDetallesSedesPagoConceptoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosDetallesSedesPagoConceptoRel()
    {
        return $this->pagosDetallesSedesPagoConceptoRel;
    }

    /**
     * Add pagosAdicionalesPagoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional $pagosAdicionalesPagoConceptoRel
     *
     * @return RhuPagoConcepto
     */
    public function addPagosAdicionalesPagoConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional $pagosAdicionalesPagoConceptoRel)
    {
        $this->pagosAdicionalesPagoConceptoRel[] = $pagosAdicionalesPagoConceptoRel;

        return $this;
    }

    /**
     * Remove pagosAdicionalesPagoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional $pagosAdicionalesPagoConceptoRel
     */
    public function removePagosAdicionalesPagoConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional $pagosAdicionalesPagoConceptoRel)
    {
        $this->pagosAdicionalesPagoConceptoRel->removeElement($pagosAdicionalesPagoConceptoRel);
    }

    /**
     * Get pagosAdicionalesPagoConceptoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosAdicionalesPagoConceptoRel()
    {
        return $this->pagosAdicionalesPagoConceptoRel;
    }

    /**
     * Add licenciasTiposPagoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLicenciaTipo $licenciasTiposPagoConceptoRel
     *
     * @return RhuPagoConcepto
     */
    public function addLicenciasTiposPagoConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuLicenciaTipo $licenciasTiposPagoConceptoRel)
    {
        $this->licenciasTiposPagoConceptoRel[] = $licenciasTiposPagoConceptoRel;

        return $this;
    }

    /**
     * Remove licenciasTiposPagoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLicenciaTipo $licenciasTiposPagoConceptoRel
     */
    public function removeLicenciasTiposPagoConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuLicenciaTipo $licenciasTiposPagoConceptoRel)
    {
        $this->licenciasTiposPagoConceptoRel->removeElement($licenciasTiposPagoConceptoRel);
    }

    /**
     * Get licenciasTiposPagoConceptoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLicenciasTiposPagoConceptoRel()
    {
        return $this->licenciasTiposPagoConceptoRel;
    }

    /**
     * Add incapacidadesTiposPagoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadTipo $incapacidadesTiposPagoConceptoRel
     *
     * @return RhuPagoConcepto
     */
    public function addIncapacidadesTiposPagoConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadTipo $incapacidadesTiposPagoConceptoRel)
    {
        $this->incapacidadesTiposPagoConceptoRel[] = $incapacidadesTiposPagoConceptoRel;

        return $this;
    }

    /**
     * Remove incapacidadesTiposPagoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadTipo $incapacidadesTiposPagoConceptoRel
     */
    public function removeIncapacidadesTiposPagoConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadTipo $incapacidadesTiposPagoConceptoRel)
    {
        $this->incapacidadesTiposPagoConceptoRel->removeElement($incapacidadesTiposPagoConceptoRel);
    }

    /**
     * Get incapacidadesTiposPagoConceptoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIncapacidadesTiposPagoConceptoRel()
    {
        return $this->incapacidadesTiposPagoConceptoRel;
    }

    /**
     * Add tiposPensionesPagoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTipoPension $tiposPensionesPagoConceptoRel
     *
     * @return RhuPagoConcepto
     */
    public function addTiposPensionesPagoConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuTipoPension $tiposPensionesPagoConceptoRel)
    {
        $this->tiposPensionesPagoConceptoRel[] = $tiposPensionesPagoConceptoRel;

        return $this;
    }

    /**
     * Remove tiposPensionesPagoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTipoPension $tiposPensionesPagoConceptoRel
     */
    public function removeTiposPensionesPagoConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuTipoPension $tiposPensionesPagoConceptoRel)
    {
        $this->tiposPensionesPagoConceptoRel->removeElement($tiposPensionesPagoConceptoRel);
    }

    /**
     * Get tiposPensionesPagoConceptoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTiposPensionesPagoConceptoRel()
    {
        return $this->tiposPensionesPagoConceptoRel;
    }

    /**
     * Add tiposSaludPagoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTipoSalud $tiposSaludPagoConceptoRel
     *
     * @return RhuPagoConcepto
     */
    public function addTiposSaludPagoConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuTipoSalud $tiposSaludPagoConceptoRel)
    {
        $this->tiposSaludPagoConceptoRel[] = $tiposSaludPagoConceptoRel;

        return $this;
    }

    /**
     * Remove tiposSaludPagoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTipoSalud $tiposSaludPagoConceptoRel
     */
    public function removeTiposSaludPagoConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuTipoSalud $tiposSaludPagoConceptoRel)
    {
        $this->tiposSaludPagoConceptoRel->removeElement($tiposSaludPagoConceptoRel);
    }

    /**
     * Get tiposSaludPagoConceptoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTiposSaludPagoConceptoRel()
    {
        return $this->tiposSaludPagoConceptoRel;
    }

    /**
     * Set codigoInterface
     *
     * @param string $codigoInterface
     *
     * @return RhuPagoConcepto
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
     * Add tiposPensionesPagoConceptoFondoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTipoPension $tiposPensionesPagoConceptoFondoRel
     *
     * @return RhuPagoConcepto
     */
    public function addTiposPensionesPagoConceptoFondoRel(\Brasa\RecursoHumanoBundle\Entity\RhuTipoPension $tiposPensionesPagoConceptoFondoRel)
    {
        $this->tiposPensionesPagoConceptoFondoRel[] = $tiposPensionesPagoConceptoFondoRel;

        return $this;
    }

    /**
     * Remove tiposPensionesPagoConceptoFondoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTipoPension $tiposPensionesPagoConceptoFondoRel
     */
    public function removeTiposPensionesPagoConceptoFondoRel(\Brasa\RecursoHumanoBundle\Entity\RhuTipoPension $tiposPensionesPagoConceptoFondoRel)
    {
        $this->tiposPensionesPagoConceptoFondoRel->removeElement($tiposPensionesPagoConceptoFondoRel);
    }

    /**
     * Get tiposPensionesPagoConceptoFondoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTiposPensionesPagoConceptoFondoRel()
    {
        return $this->tiposPensionesPagoConceptoFondoRel;
    }
}
