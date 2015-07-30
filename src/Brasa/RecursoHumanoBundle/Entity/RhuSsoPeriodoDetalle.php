<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_sso_periodo_detalle")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSsoPeriodoDetalleRepository")
 */
class RhuSsoPeriodoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_periodo_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPeriodoPk;   

    /**
     * @ORM\Column(name="codigo_periodo_fk", type="integer")
     */    
    private $codigoPeriodoFk;    
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */    
    private $codigoEmpleadoFk; 
    
    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer")
     */    
    private $codigoContratoFk;
    
    /**
     * @ORM\Column(name="tipo_registro", type="string", length=2, nullable=true)
     */    
    private $tipoRegistro;     

    /**
     * @ORM\Column(name="secuencia", type="string", length=5, nullable=true)
     */    
    private $secuencia;    
    
    /**
     * @ORM\Column(name="tipo_documento_cotizante", type="string", length=2, nullable=true)
     */    
    private $tipoDocumentoCotizante;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuSsoPeriodo", inversedBy="SsoPeriodosDetallesSsoPeriodoRel")
     * @ORM\JoinColumn(name="codigo_periodo_fk", referencedColumnName="codigo_periodo_pk")
     */
    protected $ssoPeriodoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="ssoPeriodosDetallesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="SsoPeriodosDetallesContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;    
    




    /**
     * Get codigoPeriodoPk
     *
     * @return integer
     */
    public function getCodigoPeriodoPk()
    {
        return $this->codigoPeriodoPk;
    }

    /**
     * Set codigoPeriodoFk
     *
     * @param integer $codigoPeriodoFk
     *
     * @return RhuSsoPeriodoDetalle
     */
    public function setCodigoPeriodoFk($codigoPeriodoFk)
    {
        $this->codigoPeriodoFk = $codigoPeriodoFk;

        return $this;
    }

    /**
     * Get codigoPeriodoFk
     *
     * @return integer
     */
    public function getCodigoPeriodoFk()
    {
        return $this->codigoPeriodoFk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuSsoPeriodoDetalle
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
     * Set codigoContratoFk
     *
     * @param integer $codigoContratoFk
     *
     * @return RhuSsoPeriodoDetalle
     */
    public function setCodigoContratoFk($codigoContratoFk)
    {
        $this->codigoContratoFk = $codigoContratoFk;

        return $this;
    }

    /**
     * Get codigoContratoFk
     *
     * @return integer
     */
    public function getCodigoContratoFk()
    {
        return $this->codigoContratoFk;
    }

    /**
     * Set tipoRegistro
     *
     * @param string $tipoRegistro
     *
     * @return RhuSsoPeriodoDetalle
     */
    public function setTipoRegistro($tipoRegistro)
    {
        $this->tipoRegistro = $tipoRegistro;

        return $this;
    }

    /**
     * Get tipoRegistro
     *
     * @return string
     */
    public function getTipoRegistro()
    {
        return $this->tipoRegistro;
    }

    /**
     * Set secuencia
     *
     * @param string $secuencia
     *
     * @return RhuSsoPeriodoDetalle
     */
    public function setSecuencia($secuencia)
    {
        $this->secuencia = $secuencia;

        return $this;
    }

    /**
     * Get secuencia
     *
     * @return string
     */
    public function getSecuencia()
    {
        return $this->secuencia;
    }

    /**
     * Set tipoDocumentoCotizante
     *
     * @param string $tipoDocumentoCotizante
     *
     * @return RhuSsoPeriodoDetalle
     */
    public function setTipoDocumentoCotizante($tipoDocumentoCotizante)
    {
        $this->tipoDocumentoCotizante = $tipoDocumentoCotizante;

        return $this;
    }

    /**
     * Get tipoDocumentoCotizante
     *
     * @return string
     */
    public function getTipoDocumentoCotizante()
    {
        return $this->tipoDocumentoCotizante;
    }

    /**
     * Set ssoPeriodoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo $ssoPeriodoRel
     *
     * @return RhuSsoPeriodoDetalle
     */
    public function setSsoPeriodoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo $ssoPeriodoRel = null)
    {
        $this->ssoPeriodoRel = $ssoPeriodoRel;

        return $this;
    }

    /**
     * Get ssoPeriodoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo
     */
    public function getSsoPeriodoRel()
    {
        return $this->ssoPeriodoRel;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuSsoPeriodoDetalle
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
     * Set contratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoRel
     *
     * @return RhuSsoPeriodoDetalle
     */
    public function setContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoRel = null)
    {
        $this->contratoRel = $contratoRel;

        return $this;
    }

    /**
     * Get contratoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuContrato
     */
    public function getContratoRel()
    {
        return $this->contratoRel;
    }
}
