<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_licencia_registro_pago")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuLicenciaRegistroPagoRepository")
 */
class RhuLicenciaRegistroPago
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_licencia_registro_pago_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoLicenciaRegistroPagoPk;                                  
    
    /**
     * @ORM\Column(name="cantidad", type="integer")
     */
    private $cantidad = 0;                  
        
    /**
     * @ORM\Column(name="codigo_licencia_fk", type="integer", nullable=true)
     */    
    private $codigoLicenciaFk;                  
    
    /**
     * @ORM\Column(name="codigo_programacion_pago_fk", type="integer", nullable=true)
     */    
    private $codigoProgramacionPagoFk;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuLicencia", inversedBy="licenciasRegistrosPagosLicenciaRel")
     * @ORM\JoinColumn(name="codigo_licencia_fk", referencedColumnName="codigo_licencia_pk")
     */
    protected $licenciaRel;            

    /**
     * @ORM\ManyToOne(targetEntity="RhuProgramacionPago", inversedBy="licenciasRegistrosPagosProgramacionPagoRel")
     * @ORM\JoinColumn(name="codigo_programacion_pago_fk", referencedColumnName="codigo_programacion_pago_pk")
     */
    protected $programacionPagoRel;    
  

    /**
     * Get codigoLicenciaRegistroPagoPk
     *
     * @return integer
     */
    public function getCodigoLicenciaRegistroPagoPk()
    {
        return $this->codigoLicenciaRegistroPagoPk;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return RhuLicenciaRegistroPago
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set codigoLicenciaFk
     *
     * @param integer $codigoLicenciaFk
     *
     * @return RhuLicenciaRegistroPago
     */
    public function setCodigoLicenciaFk($codigoLicenciaFk)
    {
        $this->codigoLicenciaFk = $codigoLicenciaFk;

        return $this;
    }

    /**
     * Get codigoLicenciaFk
     *
     * @return integer
     */
    public function getCodigoLicenciaFk()
    {
        return $this->codigoLicenciaFk;
    }

    /**
     * Set codigoProgramacionPagoFk
     *
     * @param integer $codigoProgramacionPagoFk
     *
     * @return RhuLicenciaRegistroPago
     */
    public function setCodigoProgramacionPagoFk($codigoProgramacionPagoFk)
    {
        $this->codigoProgramacionPagoFk = $codigoProgramacionPagoFk;

        return $this;
    }

    /**
     * Get codigoProgramacionPagoFk
     *
     * @return integer
     */
    public function getCodigoProgramacionPagoFk()
    {
        return $this->codigoProgramacionPagoFk;
    }

    /**
     * Set licenciaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLicencia $licenciaRel
     *
     * @return RhuLicenciaRegistroPago
     */
    public function setLicenciaRel(\Brasa\RecursoHumanoBundle\Entity\RhuLicencia $licenciaRel = null)
    {
        $this->licenciaRel = $licenciaRel;

        return $this;
    }

    /**
     * Get licenciaRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuLicencia
     */
    public function getLicenciaRel()
    {
        return $this->licenciaRel;
    }

    /**
     * Set programacionPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago $programacionPagoRel
     *
     * @return RhuLicenciaRegistroPago
     */
    public function setProgramacionPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago $programacionPagoRel = null)
    {
        $this->programacionPagoRel = $programacionPagoRel;

        return $this;
    }

    /**
     * Get programacionPagoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago
     */
    public function getProgramacionPagoRel()
    {
        return $this->programacionPagoRel;
    }
}
