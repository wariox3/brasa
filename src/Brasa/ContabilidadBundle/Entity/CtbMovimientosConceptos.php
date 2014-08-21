<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ctb_movimientos_conceptos")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbMovimientosConceptosRepository")
 */
class CtbMovimientosConceptos
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_movimiento_concepto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoMovimientoConceptoPk;        
    
    /**
     * @ORM\Column(name="nombre_movimiento_concepto", type="string", length=40)
     */     
    private $nombreMovimientoConcepto;       
    
    /**
     * @ORM\Column(name="codigo_movimiento_tipo_fk", type="integer")
     */     
    private $codigoMovimientoTipoFk;            

    /**
     * @ORM\Column(name="codigo_comprobante_contable_fk", type="integer", nullable=true)
     */     
    private $codigoComprobanteContableFk;
    
    /**
     * @ORM\Column(name="codigo_cuenta_total_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaTotalFk;     

    /**
     * @ORM\Column(name="codigo_cuenta_bruto_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaBrutoFk;     
    
    /**
     * @ORM\Column(name="tipo_registro", type="smallint")
     */    
    private $tipoRegistro = 1;  

    /**
     * @ORM\Column(name="aplica_cuentas_pagar", type="boolean")
     */    
    private $aplicaCuentasPagar = 0;      

    /**
     * @ORM\Column(name="aplica_cuentas_cobrar", type="boolean")
     */    
    private $aplicaCuentasCobrar = 0;    
    
    /**
     * @ORM\Column(name="afecta_cuentas_pagar", type="boolean")
     */    
    private $afectaCuentasPagar = 0;      

    /**
     * @ORM\Column(name="afecta_cuentas_cobrar", type="boolean")
     */    
    private $afectaCuentasCobrar = 0;          
    
    /**
     * @ORM\Column(name="tipo_registro_total", type="smallint", nullable=true)
     */    
    private $tipoRegistroTotal;    
    
    /**
     * @ORM\Column(name="maneja_impuesto_iva", type="boolean")
     */    
    private $manejaImpuestoIva = 0;     

    /**
     * @ORM\Column(name="maneja_impuesto_retencion_fuente", type="boolean")
     */    
    private $manejaImpuestoRetencionFuente = 0;    
    
    /**
     * @ORM\Column(name="maneja_impuesto_retencion_cree", type="boolean")
     */    
    private $manejaImpuestoRetencionCREE = 0;     

    /**
     * @ORM\Column(name="requiere_tercero", type="boolean")
     */    
    private $requiereTercero = 0;     
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbMovimientosTipos", inversedBy="CtbMovimientosConceptos")
     * @ORM\JoinColumn(name="codigo_movimiento_tipo_fk", referencedColumnName="codigo_movimiento_tipo_pk")
     */
    protected $movimientoTipoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbCuentasContables", inversedBy="CtbMovimientosConceptos")
     * @ORM\JoinColumn(name="codigo_cuenta_total_fk", referencedColumnName="codigo_cuenta_pk")
     */
    protected $cuentaTotalRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbCuentasContables", inversedBy="CtbMovimientosConceptos")
     * @ORM\JoinColumn(name="codigo_cuenta_bruto_fk", referencedColumnName="codigo_cuenta_pk")
     */
    protected $cuentaBrutoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="CtbComprobantesContables", inversedBy="CtbMovimientosConceptos")
     * @ORM\JoinColumn(name="codigo_comprobante_contable_fk", referencedColumnName="codigo_comprobante_contable_pk")
     */
    protected $comprobanteContableRel; 
    
    /**
     * @ORM\Column(name="consecutivo", type="integer")
     */        
    private $consecutivo = 1;     
    

    /**
     * Get codigoMovimientoConceptoPk
     *
     * @return integer 
     */
    public function getCodigoMovimientoConceptoPk()
    {
        return $this->codigoMovimientoConceptoPk;
    }

    /**
     * Set nombreMovimientoConcepto
     *
     * @param string $nombreMovimientoConcepto
     */
    public function setNombreMovimientoConcepto($nombreMovimientoConcepto)
    {
        $this->nombreMovimientoConcepto = $nombreMovimientoConcepto;
    }

    /**
     * Get nombreMovimientoConcepto
     *
     * @return string 
     */
    public function getNombreMovimientoConcepto()
    {
        return $this->nombreMovimientoConcepto;
    }

    /**
     * Set codigoMovimientoTipoFk
     *
     * @param integer $codigoMovimientoTipoFk
     */
    public function setCodigoMovimientoTipoFk($codigoMovimientoTipoFk)
    {
        $this->codigoMovimientoTipoFk = $codigoMovimientoTipoFk;
    }

    /**
     * Get codigoMovimientoTipoFk
     *
     * @return integer 
     */
    public function getCodigoMovimientoTipoFk()
    {
        return $this->codigoMovimientoTipoFk;
    }

    /**
     * Set codigoComprobanteContableFk
     *
     * @param integer $codigoComprobanteContableFk
     */
    public function setCodigoComprobanteContableFk($codigoComprobanteContableFk)
    {
        $this->codigoComprobanteContableFk = $codigoComprobanteContableFk;
    }

    /**
     * Get codigoComprobanteContableFk
     *
     * @return integer 
     */
    public function getCodigoComprobanteContableFk()
    {
        return $this->codigoComprobanteContableFk;
    }

    /**
     * Set codigoCuentaTotalFk
     *
     * @param string $codigoCuentaTotalFk
     */
    public function setCodigoCuentaTotalFk($codigoCuentaTotalFk)
    {
        $this->codigoCuentaTotalFk = $codigoCuentaTotalFk;
    }

    /**
     * Get codigoCuentaTotalFk
     *
     * @return string 
     */
    public function getCodigoCuentaTotalFk()
    {
        return $this->codigoCuentaTotalFk;
    }

    /**
     * Set codigoCuentaBrutoFk
     *
     * @param string $codigoCuentaBrutoFk
     */
    public function setCodigoCuentaBrutoFk($codigoCuentaBrutoFk)
    {
        $this->codigoCuentaBrutoFk = $codigoCuentaBrutoFk;
    }

    /**
     * Get codigoCuentaBrutoFk
     *
     * @return string 
     */
    public function getCodigoCuentaBrutoFk()
    {
        return $this->codigoCuentaBrutoFk;
    }

    /**
     * Set tipoRegistro
     *
     * @param smallint $tipoRegistro
     */
    public function setTipoRegistro($tipoRegistro)
    {
        $this->tipoRegistro = $tipoRegistro;
    }

    /**
     * Get tipoRegistro
     *
     * @return smallint 
     */
    public function getTipoRegistro()
    {
        return $this->tipoRegistro;
    }

    /**
     * Set aplicaCuentasPagar
     *
     * @param boolean $aplicaCuentasPagar
     */
    public function setAplicaCuentasPagar($aplicaCuentasPagar)
    {
        $this->aplicaCuentasPagar = $aplicaCuentasPagar;
    }

    /**
     * Get aplicaCuentasPagar
     *
     * @return boolean 
     */
    public function getAplicaCuentasPagar()
    {
        return $this->aplicaCuentasPagar;
    }

    /**
     * Set aplicaCuentasCobrar
     *
     * @param boolean $aplicaCuentasCobrar
     */
    public function setAplicaCuentasCobrar($aplicaCuentasCobrar)
    {
        $this->aplicaCuentasCobrar = $aplicaCuentasCobrar;
    }

    /**
     * Get aplicaCuentasCobrar
     *
     * @return boolean 
     */
    public function getAplicaCuentasCobrar()
    {
        return $this->aplicaCuentasCobrar;
    }

    /**
     * Set afectaCuentasPagar
     *
     * @param boolean $afectaCuentasPagar
     */
    public function setAfectaCuentasPagar($afectaCuentasPagar)
    {
        $this->afectaCuentasPagar = $afectaCuentasPagar;
    }

    /**
     * Get afectaCuentasPagar
     *
     * @return boolean 
     */
    public function getAfectaCuentasPagar()
    {
        return $this->afectaCuentasPagar;
    }

    /**
     * Set afectaCuentasCobrar
     *
     * @param boolean $afectaCuentasCobrar
     */
    public function setAfectaCuentasCobrar($afectaCuentasCobrar)
    {
        $this->afectaCuentasCobrar = $afectaCuentasCobrar;
    }

    /**
     * Get afectaCuentasCobrar
     *
     * @return boolean 
     */
    public function getAfectaCuentasCobrar()
    {
        return $this->afectaCuentasCobrar;
    }

    /**
     * Set tipoRegistroTotal
     *
     * @param smallint $tipoRegistroTotal
     */
    public function setTipoRegistroTotal($tipoRegistroTotal)
    {
        $this->tipoRegistroTotal = $tipoRegistroTotal;
    }

    /**
     * Get tipoRegistroTotal
     *
     * @return smallint 
     */
    public function getTipoRegistroTotal()
    {
        return $this->tipoRegistroTotal;
    }

    /**
     * Set manejaImpuestoIva
     *
     * @param boolean $manejaImpuestoIva
     */
    public function setManejaImpuestoIva($manejaImpuestoIva)
    {
        $this->manejaImpuestoIva = $manejaImpuestoIva;
    }

    /**
     * Get manejaImpuestoIva
     *
     * @return boolean 
     */
    public function getManejaImpuestoIva()
    {
        return $this->manejaImpuestoIva;
    }

    /**
     * Set manejaImpuestoRetencionFuente
     *
     * @param boolean $manejaImpuestoRetencionFuente
     */
    public function setManejaImpuestoRetencionFuente($manejaImpuestoRetencionFuente)
    {
        $this->manejaImpuestoRetencionFuente = $manejaImpuestoRetencionFuente;
    }

    /**
     * Get manejaImpuestoRetencionFuente
     *
     * @return boolean 
     */
    public function getManejaImpuestoRetencionFuente()
    {
        return $this->manejaImpuestoRetencionFuente;
    }

    /**
     * Set manejaImpuestoRetencionCREE
     *
     * @param boolean $manejaImpuestoRetencionCREE
     */
    public function setManejaImpuestoRetencionCREE($manejaImpuestoRetencionCREE)
    {
        $this->manejaImpuestoRetencionCREE = $manejaImpuestoRetencionCREE;
    }

    /**
     * Get manejaImpuestoRetencionCREE
     *
     * @return boolean 
     */
    public function getManejaImpuestoRetencionCREE()
    {
        return $this->manejaImpuestoRetencionCREE;
    }

    /**
     * Set requiereTercero
     *
     * @param boolean $requiereTercero
     */
    public function setRequiereTercero($requiereTercero)
    {
        $this->requiereTercero = $requiereTercero;
    }

    /**
     * Get requiereTercero
     *
     * @return boolean 
     */
    public function getRequiereTercero()
    {
        return $this->requiereTercero;
    }

    /**
     * Set movimientoTipoRel
     *
     * @param Brasa\ContabilidadBundle\Entity\CtbMovimientosTipos $movimientoTipoRel
     */
    public function setMovimientoTipoRel(\Brasa\ContabilidadBundle\Entity\CtbMovimientosTipos $movimientoTipoRel)
    {
        $this->movimientoTipoRel = $movimientoTipoRel;
    }

    /**
     * Get movimientoTipoRel
     *
     * @return Brasa\ContabilidadBundle\Entity\CtbMovimientosTipos 
     */
    public function getMovimientoTipoRel()
    {
        return $this->movimientoTipoRel;
    }

    /**
     * Set cuentaTotalRel
     *
     * @param Brasa\ContabilidadBundle\Entity\CtbCuentasContables $cuentaTotalRel
     */
    public function setCuentaTotalRel(\Brasa\ContabilidadBundle\Entity\CtbCuentasContables $cuentaTotalRel)
    {
        $this->cuentaTotalRel = $cuentaTotalRel;
    }

    /**
     * Get cuentaTotalRel
     *
     * @return Brasa\ContabilidadBundle\Entity\CtbCuentasContables 
     */
    public function getCuentaTotalRel()
    {
        return $this->cuentaTotalRel;
    }

    /**
     * Set cuentaBrutoRel
     *
     * @param Brasa\ContabilidadBundle\Entity\CtbCuentasContables $cuentaBrutoRel
     */
    public function setCuentaBrutoRel(\Brasa\ContabilidadBundle\Entity\CtbCuentasContables $cuentaBrutoRel)
    {
        $this->cuentaBrutoRel = $cuentaBrutoRel;
    }

    /**
     * Get cuentaBrutoRel
     *
     * @return Brasa\ContabilidadBundle\Entity\CtbCuentasContables 
     */
    public function getCuentaBrutoRel()
    {
        return $this->cuentaBrutoRel;
    }

    /**
     * Set comprobanteContableRel
     *
     * @param Brasa\ContabilidadBundle\Entity\CtbComprobantesContables $comprobanteContableRel
     */
    public function setComprobanteContableRel(\Brasa\ContabilidadBundle\Entity\CtbComprobantesContables $comprobanteContableRel)
    {
        $this->comprobanteContableRel = $comprobanteContableRel;
    }

    /**
     * Get comprobanteContableRel
     *
     * @return Brasa\ContabilidadBundle\Entity\CtbComprobantesContables 
     */
    public function getComprobanteContableRel()
    {
        return $this->comprobanteContableRel;
    }

    /**
     * Set consecutivo
     *
     * @param integer $consecutivo
     */
    public function setConsecutivo($consecutivo)
    {
        $this->consecutivo = $consecutivo;
    }

    /**
     * Get consecutivo
     *
     * @return integer 
     */
    public function getConsecutivo()
    {
        return $this->consecutivo;
    }
}
