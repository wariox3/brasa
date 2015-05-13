<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_programacion_pago")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuProgramacionPagoRepository")
 */
class RhuProgramacionPago
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_programacion_pago_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoProgramacionPagoPk;
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;    
    
    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta;
    
    /**
     * @ORM\Column(name="dias", type="integer")
     */
    private $dias = 0;         
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk;     
    
    /**
     * @ORM\Column(name="estado_generado", type="boolean")
     */    
    private $estadoGenerado = 0;    
    
    /**
     * @ORM\Column(name="estado_pagado", type="boolean")
     */    
    private $estadoPagado = 0;    
    
    /**
     * @ORM\Column(name="estado_anulado", type="boolean")
     */    
    private $estadoAnulado = 0;     
    
    /**
     * @ORM\Column(name="archivoExportado", type="boolean")
     */    
    private $archivoExportado = 0;    
    
    /**     
     * @ORM\Column(name="verificar_pagos_adicionales", type="boolean")
     */    
    private $verificarPagosAdicionales = 0;    

    /**     
     * @ORM\Column(name="verificar_incapacidades", type="boolean")
     */    
    private $verificarIncapacidades = 0;     
    
    /**
     * @ORM\Column(name="novedades_verificadas", type="boolean")
     */    
    private $novedadesVerificadas = 0;     
    
    /**
     * @ORM\Column(name="vr_total_neto", type="float")
     */
    private $vr_total_neto = 0;    
    

    /**     
     * Cuando se deshace un periodo esta propiedad ayuda a que no vuelva a generar periodo nuevo
     * @ORM\Column(name="no_generar_periodo", type="boolean")
     */    
    private $noGeneraPeriodo = 0;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="programacionesPagosCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuPago", mappedBy="programacionPagoRel")
     */
    protected $pagosProgramacionPagoRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="RhuPagoAdicional", mappedBy="programacionPagoRel")
     */
    protected $pagosAdicionalesProgramacionPagoRel;    

    /**
     * @ORM\OneToMany(targetEntity="RhuDescuentoAdicional", mappedBy="programacionPagoRel")
     */
    protected $descuentosAdicionalesProgramacionPagoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuLicenciaRegistroPago", mappedBy="programacionPagoRel")
     */
    protected $licenciasRegistrosPagosProgramacionPagoRel;     
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pagosProgramacionPagoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoProgramacionPagoPk
     *
     * @return integer
     */
    public function getCodigoProgramacionPagoPk()
    {
        return $this->codigoProgramacionPagoPk;
    }

    /**
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return RhuProgramacionPago
     */
    public function setFechaDesde($fechaDesde)
    {
        $this->fechaDesde = $fechaDesde;

        return $this;
    }

    /**
     * Get fechaDesde
     *
     * @return \DateTime
     */
    public function getFechaDesde()
    {
        return $this->fechaDesde;
    }

    /**
     * Set fechaHasta
     *
     * @param \DateTime $fechaHasta
     *
     * @return RhuProgramacionPago
     */
    public function setFechaHasta($fechaHasta)
    {
        $this->fechaHasta = $fechaHasta;

        return $this;
    }

    /**
     * Get fechaHasta
     *
     * @return \DateTime
     */
    public function getFechaHasta()
    {
        return $this->fechaHasta;
    }

    /**
     * Set dias
     *
     * @param integer $dias
     *
     * @return RhuProgramacionPago
     */
    public function setDias($dias)
    {
        $this->dias = $dias;

        return $this;
    }

    /**
     * Get dias
     *
     * @return integer
     */
    public function getDias()
    {
        return $this->dias;
    }

    /**
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuProgramacionPago
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
     * Set estadoGenerado
     *
     * @param boolean $estadoGenerado
     *
     * @return RhuProgramacionPago
     */
    public function setEstadoGenerado($estadoGenerado)
    {
        $this->estadoGenerado = $estadoGenerado;

        return $this;
    }

    /**
     * Get estadoGenerado
     *
     * @return boolean
     */
    public function getEstadoGenerado()
    {
        return $this->estadoGenerado;
    }

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel
     *
     * @return RhuProgramacionPago
     */
    public function setCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel = null)
    {
        $this->centroCostoRel = $centroCostoRel;

        return $this;
    }

    /**
     * Get centroCostoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto
     */
    public function getCentroCostoRel()
    {
        return $this->centroCostoRel;
    }

    /**
     * Add pagosProgramacionPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosProgramacionPagoRel
     *
     * @return RhuProgramacionPago
     */
    public function addPagosProgramacionPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosProgramacionPagoRel)
    {
        $this->pagosProgramacionPagoRel[] = $pagosProgramacionPagoRel;

        return $this;
    }

    /**
     * Remove pagosProgramacionPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosProgramacionPagoRel
     */
    public function removePagosProgramacionPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosProgramacionPagoRel)
    {
        $this->pagosProgramacionPagoRel->removeElement($pagosProgramacionPagoRel);
    }

    /**
     * Get pagosProgramacionPagoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosProgramacionPagoRel()
    {
        return $this->pagosProgramacionPagoRel;
    }

    /**
     * Set verificarPagosAdicionales
     *
     * @param boolean $verificarPagosAdicionales
     *
     * @return RhuProgramacionPago
     */
    public function setVerificarPagosAdicionales($verificarPagosAdicionales)
    {
        $this->verificarPagosAdicionales = $verificarPagosAdicionales;

        return $this;
    }

    /**
     * Get verificarPagosAdicionales
     *
     * @return boolean
     */
    public function getVerificarPagosAdicionales()
    {
        return $this->verificarPagosAdicionales;
    }

    /**
     * Set verificarIncapacidades
     *
     * @param boolean $verificarIncapacidades
     *
     * @return RhuProgramacionPago
     */
    public function setVerificarIncapacidades($verificarIncapacidades)
    {
        $this->verificarIncapacidades = $verificarIncapacidades;

        return $this;
    }

    /**
     * Get verificarIncapacidades
     *
     * @return boolean
     */
    public function getVerificarIncapacidades()
    {
        return $this->verificarIncapacidades;
    }

    /**
     * Add pagosAdicionalesProgramacionPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional $pagosAdicionalesProgramacionPagoRel
     *
     * @return RhuProgramacionPago
     */
    public function addPagosAdicionalesProgramacionPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional $pagosAdicionalesProgramacionPagoRel)
    {
        $this->pagosAdicionalesProgramacionPagoRel[] = $pagosAdicionalesProgramacionPagoRel;

        return $this;
    }

    /**
     * Remove pagosAdicionalesProgramacionPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional $pagosAdicionalesProgramacionPagoRel
     */
    public function removePagosAdicionalesProgramacionPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional $pagosAdicionalesProgramacionPagoRel)
    {
        $this->pagosAdicionalesProgramacionPagoRel->removeElement($pagosAdicionalesProgramacionPagoRel);
    }

    /**
     * Get pagosAdicionalesProgramacionPagoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosAdicionalesProgramacionPagoRel()
    {
        return $this->pagosAdicionalesProgramacionPagoRel;
    }

    /**
     * Set estadoPagado
     *
     * @param boolean $estadoPagado
     *
     * @return RhuProgramacionPago
     */
    public function setEstadoPagado($estadoPagado)
    {
        $this->estadoPagado = $estadoPagado;

        return $this;
    }

    /**
     * Get estadoPagado
     *
     * @return boolean
     */
    public function getEstadoPagado()
    {
        return $this->estadoPagado;
    }

    /**
     * Set archivoExportado
     *
     * @param boolean $archivoExportado
     *
     * @return RhuProgramacionPago
     */
    public function setArchivoExportado($archivoExportado)
    {
        $this->archivoExportado = $archivoExportado;

        return $this;
    }

    /**
     * Get archivoExportado
     *
     * @return boolean
     */
    public function getArchivoExportado()
    {
        return $this->archivoExportado;
    }

    /**
     * Set vrTotalNeto
     *
     * @param float $vrTotalNeto
     *
     * @return RhuProgramacionPago
     */
    public function setVrTotalNeto($vrTotalNeto)
    {
        $this->vr_total_neto = $vrTotalNeto;

        return $this;
    }

    /**
     * Get vrTotalNeto
     *
     * @return float
     */
    public function getVrTotalNeto()
    {
        return $this->vr_total_neto;
    }

    /**
     * Set estadoAnulado
     *
     * @param boolean $estadoAnulado
     *
     * @return RhuProgramacionPago
     */
    public function setEstadoAnulado($estadoAnulado)
    {
        $this->estadoAnulado = $estadoAnulado;

        return $this;
    }

    /**
     * Get estadoAnulado
     *
     * @return boolean
     */
    public function getEstadoAnulado()
    {
        return $this->estadoAnulado;
    }

    /**
     * Add descuentosAdicionalesProgramacionPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDescuentoAdicional $descuentosAdicionalesProgramacionPagoRel
     *
     * @return RhuProgramacionPago
     */
    public function addDescuentosAdicionalesProgramacionPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDescuentoAdicional $descuentosAdicionalesProgramacionPagoRel)
    {
        $this->descuentosAdicionalesProgramacionPagoRel[] = $descuentosAdicionalesProgramacionPagoRel;

        return $this;
    }

    /**
     * Remove descuentosAdicionalesProgramacionPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDescuentoAdicional $descuentosAdicionalesProgramacionPagoRel
     */
    public function removeDescuentosAdicionalesProgramacionPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDescuentoAdicional $descuentosAdicionalesProgramacionPagoRel)
    {
        $this->descuentosAdicionalesProgramacionPagoRel->removeElement($descuentosAdicionalesProgramacionPagoRel);
    }

    /**
     * Get descuentosAdicionalesProgramacionPagoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDescuentosAdicionalesProgramacionPagoRel()
    {
        return $this->descuentosAdicionalesProgramacionPagoRel;
    }

    /**
     * Set novedadesVerificadas
     *
     * @param boolean $novedadesVerificadas
     *
     * @return RhuProgramacionPago
     */
    public function setNovedadesVerificadas($novedadesVerificadas)
    {
        $this->novedadesVerificadas = $novedadesVerificadas;

        return $this;
    }

    /**
     * Get novedadesVerificadas
     *
     * @return boolean
     */
    public function getNovedadesVerificadas()
    {
        return $this->novedadesVerificadas;
    }

    /**
     * Add licenciasRegistrosPagosProgramacionPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLicenciaRegistroPago $licenciasRegistrosPagosProgramacionPagoRel
     *
     * @return RhuProgramacionPago
     */
    public function addLicenciasRegistrosPagosProgramacionPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuLicenciaRegistroPago $licenciasRegistrosPagosProgramacionPagoRel)
    {
        $this->licenciasRegistrosPagosProgramacionPagoRel[] = $licenciasRegistrosPagosProgramacionPagoRel;

        return $this;
    }

    /**
     * Remove licenciasRegistrosPagosProgramacionPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLicenciaRegistroPago $licenciasRegistrosPagosProgramacionPagoRel
     */
    public function removeLicenciasRegistrosPagosProgramacionPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuLicenciaRegistroPago $licenciasRegistrosPagosProgramacionPagoRel)
    {
        $this->licenciasRegistrosPagosProgramacionPagoRel->removeElement($licenciasRegistrosPagosProgramacionPagoRel);
    }

    /**
     * Get licenciasRegistrosPagosProgramacionPagoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLicenciasRegistrosPagosProgramacionPagoRel()
    {
        return $this->licenciasRegistrosPagosProgramacionPagoRel;
    }

    /**
     * Set noGeneraPeriodo
     *
     * @param boolean $noGeneraPeriodo
     *
     * @return RhuProgramacionPago
     */
    public function setNoGeneraPeriodo($noGeneraPeriodo)
    {
        $this->noGeneraPeriodo = $noGeneraPeriodo;

        return $this;
    }

    /**
     * Get noGeneraPeriodo
     *
     * @return boolean
     */
    public function getNoGeneraPeriodo()
    {
        return $this->noGeneraPeriodo;
    }
}
