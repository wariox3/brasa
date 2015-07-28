<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_configuracion")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuConfiguracionRepository")
 */
class RhuConfiguracion
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_configuracion_pk", type="integer")
     */
    private $codigoConfiguracionPk;
    
    /**
     * @ORM\Column(name="codigo_entidad_riesgo_fk", type="integer")
     */    
    private $codigoEntidadRiesgoFk;
    
    /**
     * @ORM\Column(name="vr_salario", type="float")
     */    
    private $vrSalario;  
    
    /**
     * @ORM\Column(name="codigo_auxilio_transporte", type="integer")
     */    
    private $codigoAuxilioTransporte;
    
    /**
     * @ORM\Column(name="vr_auxilio_transporte", type="float")
     */    
    private $vrAuxilioTransporte;
    
    /**
     * @ORM\Column(name="codigo_credito", type="integer")
     */    
    private $codigoCredito;
    
    /**
     * @ORM\Column(name="codigo_seguro", type="integer")
     */    
    private $codigoSeguro;
    
    /**
     * @ORM\Column(name="codigo_tiempo_suplementario", type="integer")
     */    
    private $codigoTiempoSuplementario;
    
    /**
     * @ORM\Column(name="codigo_hora_diurna_trabajada", type="integer")
     */    
    private $codigoHoraDiurnaTrabajada;
    
    /**
     * @ORM\Column(name="codigo_aporte_salud", type="integer")
     */    
    private $codigoAporteSalud;
    
    /**
     * @ORM\Column(name="codigo_aporte_pension", type="integer")
     */    
    private $codigoAportePension;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadRiesgoProfesional", inversedBy="configuracionEntidadRiesgoProfesionalRel")
     * @ORM\JoinColumn(name="codigo_entidad_riesgo_fk", referencedColumnName="codigo_entidad_riesgo_pk")
     */
    protected $entidadRiesgoProfesionalRel;

    

   

    /**
     * Set codigoConfiguracionPk
     *
     * @param integer $codigoConfiguracionPk
     *
     * @return RhuConfiguracion
     */
    public function setCodigoConfiguracionPk($codigoConfiguracionPk)
    {
        $this->codigoConfiguracionPk = $codigoConfiguracionPk;

        return $this;
    }

    /**
     * Get codigoConfiguracionPk
     *
     * @return integer
     */
    public function getCodigoConfiguracionPk()
    {
        return $this->codigoConfiguracionPk;
    }

    /**
     * Set codigoEntidadRiesgoFk
     *
     * @param integer $codigoEntidadRiesgoFk
     *
     * @return RhuConfiguracion
     */
    public function setCodigoEntidadRiesgoFk($codigoEntidadRiesgoFk)
    {
        $this->codigoEntidadRiesgoFk = $codigoEntidadRiesgoFk;

        return $this;
    }

    /**
     * Get codigoEntidadRiesgoFk
     *
     * @return integer
     */
    public function getCodigoEntidadRiesgoFk()
    {
        return $this->codigoEntidadRiesgoFk;
    }

    /**
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return RhuConfiguracion
     */
    public function setVrSalario($vrSalario)
    {
        $this->vrSalario = $vrSalario;

        return $this;
    }

    /**
     * Get vrSalario
     *
     * @return float
     */
    public function getVrSalario()
    {
        return $this->vrSalario;
    }

    /**
     * Set codigoAuxilioTransporte
     *
     * @param integer $codigoAuxilioTransporte
     *
     * @return RhuConfiguracion
     */
    public function setCodigoAuxilioTransporte($codigoAuxilioTransporte)
    {
        $this->codigoAuxilioTransporte = $codigoAuxilioTransporte;

        return $this;
    }

    /**
     * Get codigoAuxilioTransporte
     *
     * @return integer
     */
    public function getCodigoAuxilioTransporte()
    {
        return $this->codigoAuxilioTransporte;
    }

    /**
     * Set vrAuxilioTransporte
     *
     * @param float $vrAuxilioTransporte
     *
     * @return RhuConfiguracion
     */
    public function setVrAuxilioTransporte($vrAuxilioTransporte)
    {
        $this->vrAuxilioTransporte = $vrAuxilioTransporte;

        return $this;
    }

    /**
     * Get vrAuxilioTransporte
     *
     * @return float
     */
    public function getVrAuxilioTransporte()
    {
        return $this->vrAuxilioTransporte;
    }

    /**
     * Set codigoCredito
     *
     * @param integer $codigoCredito
     *
     * @return RhuConfiguracion
     */
    public function setCodigoCredito($codigoCredito)
    {
        $this->codigoCredito = $codigoCredito;

        return $this;
    }

    /**
     * Get codigoCredito
     *
     * @return integer
     */
    public function getCodigoCredito()
    {
        return $this->codigoCredito;
    }

    /**
     * Set codigoSeguro
     *
     * @param integer $codigoSeguro
     *
     * @return RhuConfiguracion
     */
    public function setCodigoSeguro($codigoSeguro)
    {
        $this->codigoSeguro = $codigoSeguro;

        return $this;
    }

    /**
     * Get codigoSeguro
     *
     * @return integer
     */
    public function getCodigoSeguro()
    {
        return $this->codigoSeguro;
    }

    /**
     * Set codigoTiempoSuplementario
     *
     * @param integer $codigoTiempoSuplementario
     *
     * @return RhuConfiguracion
     */
    public function setCodigoTiempoSuplementario($codigoTiempoSuplementario)
    {
        $this->codigoTiempoSuplementario = $codigoTiempoSuplementario;

        return $this;
    }

    /**
     * Get codigoTiempoSuplementario
     *
     * @return integer
     */
    public function getCodigoTiempoSuplementario()
    {
        return $this->codigoTiempoSuplementario;
    }

    /**
     * Set codigoHoraDiurnaTrabajada
     *
     * @param integer $codigoHoraDiurnaTrabajada
     *
     * @return RhuConfiguracion
     */
    public function setCodigoHoraDiurnaTrabajada($codigoHoraDiurnaTrabajada)
    {
        $this->codigoHoraDiurnaTrabajada = $codigoHoraDiurnaTrabajada;

        return $this;
    }

    /**
     * Get codigoHoraDiurnaTrabajada
     *
     * @return integer
     */
    public function getCodigoHoraDiurnaTrabajada()
    {
        return $this->codigoHoraDiurnaTrabajada;
    }

    /**
     * Set codigoAporteSalud
     *
     * @param integer $codigoAporteSalud
     *
     * @return RhuConfiguracion
     */
    public function setCodigoAporteSalud($codigoAporteSalud)
    {
        $this->codigoAporteSalud = $codigoAporteSalud;

        return $this;
    }

    /**
     * Get codigoAporteSalud
     *
     * @return integer
     */
    public function getCodigoAporteSalud()
    {
        return $this->codigoAporteSalud;
    }

    /**
     * Set codigoAportePension
     *
     * @param integer $codigoAportePension
     *
     * @return RhuConfiguracion
     */
    public function setCodigoAportePension($codigoAportePension)
    {
        $this->codigoAportePension = $codigoAportePension;

        return $this;
    }

    /**
     * Get codigoAportePension
     *
     * @return integer
     */
    public function getCodigoAportePension()
    {
        return $this->codigoAportePension;
    }

    /**
     * Set entidadRiesgoProfesionalRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEntidadRiesgoProfesional $entidadRiesgoProfesionalRel
     *
     * @return RhuConfiguracion
     */
    public function setEntidadRiesgoProfesionalRel(\Brasa\RecursoHumanoBundle\Entity\RhuEntidadRiesgoProfesional $entidadRiesgoProfesionalRel = null)
    {
        $this->entidadRiesgoProfesionalRel = $entidadRiesgoProfesionalRel;

        return $this;
    }

    /**
     * Get entidadRiesgoProfesionalRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEntidadRiesgoProfesional
     */
    public function getEntidadRiesgoProfesionalRel()
    {
        return $this->entidadRiesgoProfesionalRel;
    }
}
