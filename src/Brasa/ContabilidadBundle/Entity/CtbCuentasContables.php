<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ctb_cuentas_contables")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbCuentasContablesRepository")
 */
class CtbCuentasContables
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cuenta_pk", type="string", length=20)
     */        
    private $codigoCuentaPk;
    
    /**
     * @ORM\Column(name="nombre_cuenta", type="string", length=60)
     */     
    private $nombreCuenta;     
    
    /**
     * @ORM\Column(name="codigo_cuenta_padre_fk", type="integer", nullable=false)
     */ 
    private $codigo_cuenta_padre_fk;    

    
    /**
     * @ORM\Column(name="permite_movimientos", type="boolean")
     */    
    private $permiteMovimientos = 0;      

    /**
     * @ORM\Column(name="exige_nit", type="boolean")
     */    
    private $exigeNit = 0;    
    
    /**
     * @ORM\Column(name="exige_centro_costos", type="boolean")
     */    
    private $exigeCentroCostos = 0;     
    
    /**
     * @ORM\Column(name="porcentaje_retencion", type="float")
     */    
    private $porcentajeRetencion = 0;    

    /**
     * @ORM\OneToMany(targetEntity="Brasa\InventarioBundle\Entity\InvDescuentosFinancieros", mappedBy="cuentaContableRel")
     */
    protected $descuentosFinancienrosCuentaContableRel;

    /**
     * @ORM\OneToMany(targetEntity="Brasa\InventarioBundle\Entity\InvDocumentos", mappedBy="cuentaIvaRel")
     */
    protected $documentosCuentaIvaRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\InventarioBundle\Entity\InvDocumentos", mappedBy="cuentaRetencionFuenteRel")
     */
    protected $documentosCuentaRetencionFuenteRel;     

    /**
     * @ORM\OneToMany(targetEntity="Brasa\InventarioBundle\Entity\InvDocumentos", mappedBy="cuentaRetencionCREERel")
     */
    protected $documentosCuentaRetencionCREERel;         

    /**
     * @ORM\OneToMany(targetEntity="Brasa\InventarioBundle\Entity\InvDocumentos", mappedBy="cuentaRetencionIvaRel")
     */
    protected $documentosCuentaRetencionIvaRel;    

    /**
     * @ORM\OneToMany(targetEntity="Brasa\InventarioBundle\Entity\InvDocumentos", mappedBy="cuentaTesoreriaRel")
     */
    protected $documentosCuentaTesoreriaRel;        
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\InventarioBundle\Entity\InvDocumentos", mappedBy="cuentaCarteraRel")
     */
    protected $documentosCuentaCarteraRel;     
    
    public function __construct()
    {
        $this->descuentosFinancienrosCuentaContableRel = new ArrayCollection();
        $this->documentosCuentaIvaRel = new ArrayCollection();
        $this->documentosCuentaRetencionFuenteRel = new ArrayCollection();
        $this->documentosCuentaRetencionCREERel = new ArrayCollection();
        $this->documentosCuentaRetencionIvaRel = new ArrayCollection();
        $this->documentosCuentaTesoreriaRel = new ArrayCollection();
        $this->documentosCuentaCarteraRel = new ArrayCollection();
    }

    /**
     * Set codigoCuentaPk
     *
     * @param string $codigoCuentaPk
     * @return CtbCuentasContables
     */
    public function setCodigoCuentaPk($codigoCuentaPk)
    {
        $this->codigoCuentaPk = $codigoCuentaPk;

        return $this;
    }

    /**
     * Get codigoCuentaPk
     *
     * @return string 
     */
    public function getCodigoCuentaPk()
    {
        return $this->codigoCuentaPk;
    }

    /**
     * Set nombreCuenta
     *
     * @param string $nombreCuenta
     * @return CtbCuentasContables
     */
    public function setNombreCuenta($nombreCuenta)
    {
        $this->nombreCuenta = $nombreCuenta;

        return $this;
    }

    /**
     * Get nombreCuenta
     *
     * @return string 
     */
    public function getNombreCuenta()
    {
        return $this->nombreCuenta;
    }

    /**
     * Set codigo_cuenta_padre_fk
     *
     * @param integer $codigoCuentaPadreFk
     * @return CtbCuentasContables
     */
    public function setCodigoCuentaPadreFk($codigoCuentaPadreFk)
    {
        $this->codigo_cuenta_padre_fk = $codigoCuentaPadreFk;

        return $this;
    }

    /**
     * Get codigo_cuenta_padre_fk
     *
     * @return integer 
     */
    public function getCodigoCuentaPadreFk()
    {
        return $this->codigo_cuenta_padre_fk;
    }

    /**
     * Set permiteMovimientos
     *
     * @param boolean $permiteMovimientos
     * @return CtbCuentasContables
     */
    public function setPermiteMovimientos($permiteMovimientos)
    {
        $this->permiteMovimientos = $permiteMovimientos;

        return $this;
    }

    /**
     * Get permiteMovimientos
     *
     * @return boolean 
     */
    public function getPermiteMovimientos()
    {
        return $this->permiteMovimientos;
    }

    /**
     * Set exigeNit
     *
     * @param boolean $exigeNit
     * @return CtbCuentasContables
     */
    public function setExigeNit($exigeNit)
    {
        $this->exigeNit = $exigeNit;

        return $this;
    }

    /**
     * Get exigeNit
     *
     * @return boolean 
     */
    public function getExigeNit()
    {
        return $this->exigeNit;
    }

    /**
     * Set exigeCentroCostos
     *
     * @param boolean $exigeCentroCostos
     * @return CtbCuentasContables
     */
    public function setExigeCentroCostos($exigeCentroCostos)
    {
        $this->exigeCentroCostos = $exigeCentroCostos;

        return $this;
    }

    /**
     * Get exigeCentroCostos
     *
     * @return boolean 
     */
    public function getExigeCentroCostos()
    {
        return $this->exigeCentroCostos;
    }

    /**
     * Set porcentajeRetencion
     *
     * @param float $porcentajeRetencion
     * @return CtbCuentasContables
     */
    public function setPorcentajeRetencion($porcentajeRetencion)
    {
        $this->porcentajeRetencion = $porcentajeRetencion;

        return $this;
    }

    /**
     * Get porcentajeRetencion
     *
     * @return float 
     */
    public function getPorcentajeRetencion()
    {
        return $this->porcentajeRetencion;
    }

    /**
     * Add descuentosFinancienrosCuentaContableRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDescuentosFinancieros $descuentosFinancienrosCuentaContableRel
     * @return CtbCuentasContables
     */
    public function addDescuentosFinancienrosCuentaContableRel(\Brasa\InventarioBundle\Entity\InvDescuentosFinancieros $descuentosFinancienrosCuentaContableRel)
    {
        $this->descuentosFinancienrosCuentaContableRel[] = $descuentosFinancienrosCuentaContableRel;

        return $this;
    }

    /**
     * Remove descuentosFinancienrosCuentaContableRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDescuentosFinancieros $descuentosFinancienrosCuentaContableRel
     */
    public function removeDescuentosFinancienrosCuentaContableRel(\Brasa\InventarioBundle\Entity\InvDescuentosFinancieros $descuentosFinancienrosCuentaContableRel)
    {
        $this->descuentosFinancienrosCuentaContableRel->removeElement($descuentosFinancienrosCuentaContableRel);
    }

    /**
     * Get descuentosFinancienrosCuentaContableRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDescuentosFinancienrosCuentaContableRel()
    {
        return $this->descuentosFinancienrosCuentaContableRel;
    }

    /**
     * Add documentosCuentaIvaRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumentos $documentosCuentaIvaRel
     * @return CtbCuentasContables
     */
    public function addDocumentosCuentaIvaRel(\Brasa\InventarioBundle\Entity\InvDocumentos $documentosCuentaIvaRel)
    {
        $this->documentosCuentaIvaRel[] = $documentosCuentaIvaRel;

        return $this;
    }

    /**
     * Remove documentosCuentaIvaRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumentos $documentosCuentaIvaRel
     */
    public function removeDocumentosCuentaIvaRel(\Brasa\InventarioBundle\Entity\InvDocumentos $documentosCuentaIvaRel)
    {
        $this->documentosCuentaIvaRel->removeElement($documentosCuentaIvaRel);
    }

    /**
     * Get documentosCuentaIvaRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDocumentosCuentaIvaRel()
    {
        return $this->documentosCuentaIvaRel;
    }

    /**
     * Add documentosCuentaRetencionFuenteRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumentos $documentosCuentaRetencionFuenteRel
     * @return CtbCuentasContables
     */
    public function addDocumentosCuentaRetencionFuenteRel(\Brasa\InventarioBundle\Entity\InvDocumentos $documentosCuentaRetencionFuenteRel)
    {
        $this->documentosCuentaRetencionFuenteRel[] = $documentosCuentaRetencionFuenteRel;

        return $this;
    }

    /**
     * Remove documentosCuentaRetencionFuenteRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumentos $documentosCuentaRetencionFuenteRel
     */
    public function removeDocumentosCuentaRetencionFuenteRel(\Brasa\InventarioBundle\Entity\InvDocumentos $documentosCuentaRetencionFuenteRel)
    {
        $this->documentosCuentaRetencionFuenteRel->removeElement($documentosCuentaRetencionFuenteRel);
    }

    /**
     * Get documentosCuentaRetencionFuenteRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDocumentosCuentaRetencionFuenteRel()
    {
        return $this->documentosCuentaRetencionFuenteRel;
    }

    /**
     * Add documentosCuentaRetencionCREERel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumentos $documentosCuentaRetencionCREERel
     * @return CtbCuentasContables
     */
    public function addDocumentosCuentaRetencionCREERel(\Brasa\InventarioBundle\Entity\InvDocumentos $documentosCuentaRetencionCREERel)
    {
        $this->documentosCuentaRetencionCREERel[] = $documentosCuentaRetencionCREERel;

        return $this;
    }

    /**
     * Remove documentosCuentaRetencionCREERel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumentos $documentosCuentaRetencionCREERel
     */
    public function removeDocumentosCuentaRetencionCREERel(\Brasa\InventarioBundle\Entity\InvDocumentos $documentosCuentaRetencionCREERel)
    {
        $this->documentosCuentaRetencionCREERel->removeElement($documentosCuentaRetencionCREERel);
    }

    /**
     * Get documentosCuentaRetencionCREERel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDocumentosCuentaRetencionCREERel()
    {
        return $this->documentosCuentaRetencionCREERel;
    }

    /**
     * Add documentosCuentaRetencionIvaRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumentos $documentosCuentaRetencionIvaRel
     * @return CtbCuentasContables
     */
    public function addDocumentosCuentaRetencionIvaRel(\Brasa\InventarioBundle\Entity\InvDocumentos $documentosCuentaRetencionIvaRel)
    {
        $this->documentosCuentaRetencionIvaRel[] = $documentosCuentaRetencionIvaRel;

        return $this;
    }

    /**
     * Remove documentosCuentaRetencionIvaRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumentos $documentosCuentaRetencionIvaRel
     */
    public function removeDocumentosCuentaRetencionIvaRel(\Brasa\InventarioBundle\Entity\InvDocumentos $documentosCuentaRetencionIvaRel)
    {
        $this->documentosCuentaRetencionIvaRel->removeElement($documentosCuentaRetencionIvaRel);
    }

    /**
     * Get documentosCuentaRetencionIvaRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDocumentosCuentaRetencionIvaRel()
    {
        return $this->documentosCuentaRetencionIvaRel;
    }

    /**
     * Add documentosCuentaTesoreriaRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumentos $documentosCuentaTesoreriaRel
     * @return CtbCuentasContables
     */
    public function addDocumentosCuentaTesoreriaRel(\Brasa\InventarioBundle\Entity\InvDocumentos $documentosCuentaTesoreriaRel)
    {
        $this->documentosCuentaTesoreriaRel[] = $documentosCuentaTesoreriaRel;

        return $this;
    }

    /**
     * Remove documentosCuentaTesoreriaRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumentos $documentosCuentaTesoreriaRel
     */
    public function removeDocumentosCuentaTesoreriaRel(\Brasa\InventarioBundle\Entity\InvDocumentos $documentosCuentaTesoreriaRel)
    {
        $this->documentosCuentaTesoreriaRel->removeElement($documentosCuentaTesoreriaRel);
    }

    /**
     * Get documentosCuentaTesoreriaRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDocumentosCuentaTesoreriaRel()
    {
        return $this->documentosCuentaTesoreriaRel;
    }

    /**
     * Add documentosCuentaCarteraRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumentos $documentosCuentaCarteraRel
     * @return CtbCuentasContables
     */
    public function addDocumentosCuentaCarteraRel(\Brasa\InventarioBundle\Entity\InvDocumentos $documentosCuentaCarteraRel)
    {
        $this->documentosCuentaCarteraRel[] = $documentosCuentaCarteraRel;

        return $this;
    }

    /**
     * Remove documentosCuentaCarteraRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvDocumentos $documentosCuentaCarteraRel
     */
    public function removeDocumentosCuentaCarteraRel(\Brasa\InventarioBundle\Entity\InvDocumentos $documentosCuentaCarteraRel)
    {
        $this->documentosCuentaCarteraRel->removeElement($documentosCuentaCarteraRel);
    }

    /**
     * Get documentosCuentaCarteraRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDocumentosCuentaCarteraRel()
    {
        return $this->documentosCuentaCarteraRel;
    }
}
