<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_sso_periodo_empleado")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSsoPeriodoEmpleadoRepository")
 */
class RhuSsoPeriodoEmpleado
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_periodo_empleado_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPeriodoEmpleadoPk;   
    
    /**
     * @ORM\Column(name="codigo_periodo_fk", type="integer")
     */    
    private $codigoPeriodoFk;    

    /**
     * @ORM\Column(name="codigo_periodo_detalle_fk", type="integer")
     */    
    private $codigoPeriodoDetalleFk;     
    
    /**
     * @ORM\Column(name="codigo_sucursal_fk", type="integer")
     */    
    private $codigoSucursalFk; 

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */    
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer")
     */    
    private $codigoContratoFk;    
    
    /**
     * @ORM\Column(name="dias", type="integer")
     */    
    private $dias = 0;
    
    /**
     * @ORM\Column(name="vr_salario", type="float")
     */    
    private $vrSalario = 0;

    /**
     * @ORM\Column(name="vr_suplementario", type="float")
     */    
    private $vrSuplementario = 0;    
    
    /**
     * @ORM\Column(name="ingreso", type="string", length=1)
     */
    private $ingreso = ' ';    

    /**
     * @ORM\Column(name="retiro", type="string", length=1)
     */
    private $retiro = ' ';    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuSsoPeriodo", inversedBy="ssoPeriodosEmpleadosSsoPeriodoRel")
     * @ORM\JoinColumn(name="codigo_periodo_fk", referencedColumnName="codigo_periodo_pk")
     */
    protected $ssoPeriodoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="RhuSsoPeriodoDetalle", inversedBy="ssoPeriodosEmpleadosSsoPeriodoDetalleRel")
     * @ORM\JoinColumn(name="codigo_periodo_detalle_fk", referencedColumnName="codigo_periodo_detalle_pk")
     */
    protected $ssoPeriodoDetalleRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuSsoSucursal", inversedBy="ssoPeriodosEmpleadosSsoSucursalRel")
     * @ORM\JoinColumn(name="codigo_sucursal_fk", referencedColumnName="codigo_sucursal_pk")
     */
    protected $ssoSucursalRel;       
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="ssoPeriodosEmpleadosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;      

    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="ssoPeriodosEmpleadosContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;     


    /**
     * Get codigoPeriodoEmpleadoPk
     *
     * @return integer
     */
    public function getCodigoPeriodoEmpleadoPk()
    {
        return $this->codigoPeriodoEmpleadoPk;
    }

    /**
     * Set codigoPeriodoFk
     *
     * @param integer $codigoPeriodoFk
     *
     * @return RhuSsoPeriodoEmpleado
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
     * Set codigoPeriodoDetalleFk
     *
     * @param integer $codigoPeriodoDetalleFk
     *
     * @return RhuSsoPeriodoEmpleado
     */
    public function setCodigoPeriodoDetalleFk($codigoPeriodoDetalleFk)
    {
        $this->codigoPeriodoDetalleFk = $codigoPeriodoDetalleFk;

        return $this;
    }

    /**
     * Get codigoPeriodoDetalleFk
     *
     * @return integer
     */
    public function getCodigoPeriodoDetalleFk()
    {
        return $this->codigoPeriodoDetalleFk;
    }

    /**
     * Set codigoSucursalFk
     *
     * @param integer $codigoSucursalFk
     *
     * @return RhuSsoPeriodoEmpleado
     */
    public function setCodigoSucursalFk($codigoSucursalFk)
    {
        $this->codigoSucursalFk = $codigoSucursalFk;

        return $this;
    }

    /**
     * Get codigoSucursalFk
     *
     * @return integer
     */
    public function getCodigoSucursalFk()
    {
        return $this->codigoSucursalFk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuSsoPeriodoEmpleado
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
     * @return RhuSsoPeriodoEmpleado
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
     * Set dias
     *
     * @param integer $dias
     *
     * @return RhuSsoPeriodoEmpleado
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
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return RhuSsoPeriodoEmpleado
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
     * Set ingreso
     *
     * @param string $ingreso
     *
     * @return RhuSsoPeriodoEmpleado
     */
    public function setIngreso($ingreso)
    {
        $this->ingreso = $ingreso;

        return $this;
    }

    /**
     * Get ingreso
     *
     * @return string
     */
    public function getIngreso()
    {
        return $this->ingreso;
    }

    /**
     * Set retiro
     *
     * @param string $retiro
     *
     * @return RhuSsoPeriodoEmpleado
     */
    public function setRetiro($retiro)
    {
        $this->retiro = $retiro;

        return $this;
    }

    /**
     * Get retiro
     *
     * @return string
     */
    public function getRetiro()
    {
        return $this->retiro;
    }

    /**
     * Set ssoPeriodoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodo $ssoPeriodoRel
     *
     * @return RhuSsoPeriodoEmpleado
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
     * Set ssoPeriodoDetalleRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle $ssoPeriodoDetalleRel
     *
     * @return RhuSsoPeriodoEmpleado
     */
    public function setSsoPeriodoDetalleRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle $ssoPeriodoDetalleRel = null)
    {
        $this->ssoPeriodoDetalleRel = $ssoPeriodoDetalleRel;

        return $this;
    }

    /**
     * Get ssoPeriodoDetalleRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle
     */
    public function getSsoPeriodoDetalleRel()
    {
        return $this->ssoPeriodoDetalleRel;
    }

    /**
     * Set ssoSucursalRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoSucursal $ssoSucursalRel
     *
     * @return RhuSsoPeriodoEmpleado
     */
    public function setSsoSucursalRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoSucursal $ssoSucursalRel = null)
    {
        $this->ssoSucursalRel = $ssoSucursalRel;

        return $this;
    }

    /**
     * Get ssoSucursalRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSsoSucursal
     */
    public function getSsoSucursalRel()
    {
        return $this->ssoSucursalRel;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuSsoPeriodoEmpleado
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
     * @return RhuSsoPeriodoEmpleado
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

    /**
     * Set vrSuplementario
     *
     * @param float $vrSuplementario
     *
     * @return RhuSsoPeriodoEmpleado
     */
    public function setVrSuplementario($vrSuplementario)
    {
        $this->vrSuplementario = $vrSuplementario;

        return $this;
    }

    /**
     * Get vrSuplementario
     *
     * @return float
     */
    public function getVrSuplementario()
    {
        return $this->vrSuplementario;
    }
}
