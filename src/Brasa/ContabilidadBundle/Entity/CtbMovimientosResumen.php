<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ctb_movimientos_resumen")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbMovimientosResumenRepository")
 */
class CtbMovimientosResumen
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_movimiento_resumen_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoMovimientoResumenPk;       

    /**
     * @ORM\Column(name="codigo_cierre_mes_fk", type="integer")
     */     
    private $codigoCierreMesFk;     
    
    /**
     * @ORM\Column(name="annio", type="integer")
     */    
    private $annio;    

    /**
     * @ORM\Column(name="mes", type="smallint")
     */    
    private $mes;
    
    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=20)
     */     
    private $codigoCuentaFk;     
    
    /**
     * @ORM\Column(name="debito", type="float")
     */    
    private $debito = 0;
    
    /**
     * @ORM\Column(name="credito", type="float")
     */    
    private $credito = 0; 
    
    /**
     * @ORM\Column(name="base", type="float")
     */    
    private $base = 0;     
  
    /**
     * @ORM\ManyToOne(targetEntity="CtbCuentasContables", inversedBy="CtbMovimientosResumen")
     * @ORM\JoinColumn(name="codigo_cuenta_fk", referencedColumnName="codigo_cuenta_pk")
     */
    protected $cuentaRel;     

    /**
     * @ORM\ManyToOne(targetEntity="CtbCierresMes", inversedBy="CtbMovimientosResumen")
     * @ORM\JoinColumn(name="codigo_cierre_mes_fk", referencedColumnName="codigo_cierre_mes_pk")
     */
    protected $ciereMesContabilidadRel;     

    


    /**
     * Get codigoMovimientoResumenPk
     *
     * @return integer 
     */
    public function getCodigoMovimientoResumenPk()
    {
        return $this->codigoMovimientoResumenPk;
    }

    /**
     * Set codigoCierreMesContabilidadFk
     *
     * @param integer $codigoCierreMesContabilidadFk
     */
    public function setCodigoCierreMesContabilidadFk($codigoCierreMesContabilidadFk)
    {
        $this->codigoCierreMesContabilidadFk = $codigoCierreMesContabilidadFk;
    }

    /**
     * Get codigoCierreMesContabilidadFk
     *
     * @return integer 
     */
    public function getCodigoCierreMesContabilidadFk()
    {
        return $this->codigoCierreMesContabilidadFk;
    }

    /**
     * Set annio
     *
     * @param integer $annio
     */
    public function setAnnio($annio)
    {
        $this->annio = $annio;
    }

    /**
     * Get annio
     *
     * @return integer 
     */
    public function getAnnio()
    {
        return $this->annio;
    }

    /**
     * Set mes
     *
     * @param smallint $mes
     */
    public function setMes($mes)
    {
        $this->mes = $mes;
    }

    /**
     * Get mes
     *
     * @return smallint 
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * Set codigoCuentaFk
     *
     * @param string $codigoCuentaFk
     */
    public function setCodigoCuentaFk($codigoCuentaFk)
    {
        $this->codigoCuentaFk = $codigoCuentaFk;
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
     * Set debito
     *
     * @param float $debito
     */
    public function setDebito($debito)
    {
        $this->debito = $debito;
    }

    /**
     * Get debito
     *
     * @return float 
     */
    public function getDebito()
    {
        return $this->debito;
    }

    /**
     * Set credito
     *
     * @param float $credito
     */
    public function setCredito($credito)
    {
        $this->credito = $credito;
    }

    /**
     * Get credito
     *
     * @return float 
     */
    public function getCredito()
    {
        return $this->credito;
    }

    /**
     * Set base
     *
     * @param float $base
     */
    public function setBase($base)
    {
        $this->base = $base;
    }

    /**
     * Get base
     *
     * @return float 
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * Set cuentaRel
     *
     * @param Brasa\ContabilidadBundle\Entity\CuentasContables $cuentaRel
     */
    public function setCuentaRel(\Brasa\ContabilidadBundle\Entity\CuentasContables $cuentaRel)
    {
        $this->cuentaRel = $cuentaRel;
    }

    /**
     * Get cuentaRel
     *
     * @return Brasa\ContabilidadBundle\Entity\CuentasContables 
     */
    public function getCuentaRel()
    {
        return $this->cuentaRel;
    }

    /**
     * Set ciereMesContabilidadRel
     *
     * @param Brasa\ContabilidadBundle\Entity\CierresMesContabilidad $ciereMesContabilidadRel
     */
    public function setCiereMesContabilidadRel(\Brasa\ContabilidadBundle\Entity\CierresMesContabilidad $ciereMesContabilidadRel)
    {
        $this->ciereMesContabilidadRel = $ciereMesContabilidadRel;
    }

    /**
     * Get ciereMesContabilidadRel
     *
     * @return Brasa\ContabilidadBundle\Entity\CierresMesContabilidad 
     */
    public function getCiereMesContabilidadRel()
    {
        return $this->ciereMesContabilidadRel;
    }

    /**
     * Set codigoCierreMesFk
     *
     * @param integer $codigoCierreMesFk
     */
    public function setCodigoCierreMesFk($codigoCierreMesFk)
    {
        $this->codigoCierreMesFk = $codigoCierreMesFk;
    }

    /**
     * Get codigoCierreMesFk
     *
     * @return integer 
     */
    public function getCodigoCierreMesFk()
    {
        return $this->codigoCierreMesFk;
    }
}
