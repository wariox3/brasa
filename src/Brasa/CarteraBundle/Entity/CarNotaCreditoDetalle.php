<?php

namespace Brasa\CarteraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="car_nota_credito_detalle")
 * @ORM\Entity(repositoryClass="Brasa\CarteraBundle\Repository\CarNotaCreditoDetalleRepository")
 */
class CarNotaCreditoDetalle
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_nota_credito_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoNotaCreditoDetallePk;        

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;     

    /**
     * @ORM\Column(name="codigo_cuenta_cobrar_fk", type="integer", nullable=true)
     */     
    private $codigoCuentaCobrarFk;
    
    /**
     * @ORM\Column(name="codigo_nota_credito_fk", type="integer", nullable=true)
     */     
    private $codigoNotaCreditoFk;
    
    /**
     * @ORM\Column(name="valor", type="float")
     */    
    private $valor = 0;
    
    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */    
    private $usuario;
    
    /**
     * @ORM\ManyToOne(targetEntity="CarNotaCredito", inversedBy="notasCreditosDetallesNotaCreditoRel")
     * @ORM\JoinColumn(name="codigo_nota_credito_fk", referencedColumnName="codigo_nota_credito_pk")
     */
    protected $notaCreditoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="CarCuentaCobrar", inversedBy="notasCreditosDetallesCuentaCobrarRel")
     * @ORM\JoinColumn(name="codigo_cuenta_cobrar_fk", referencedColumnName="codigo_cuenta_cobrar_pk")
     */
    protected $cuentaCobrarRel;

    

    /**
     * Get codigoNotaCreditoDetallePk
     *
     * @return integer
     */
    public function getCodigoNotaCreditoDetallePk()
    {
        return $this->codigoNotaCreditoDetallePk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return CarNotaCreditoDetalle
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set codigoCuentaCobrarFk
     *
     * @param integer $codigoCuentaCobrarFk
     *
     * @return CarNotaCreditoDetalle
     */
    public function setCodigoCuentaCobrarFk($codigoCuentaCobrarFk)
    {
        $this->codigoCuentaCobrarFk = $codigoCuentaCobrarFk;

        return $this;
    }

    /**
     * Get codigoCuentaCobrarFk
     *
     * @return integer
     */
    public function getCodigoCuentaCobrarFk()
    {
        return $this->codigoCuentaCobrarFk;
    }

    /**
     * Set codigoNotaCreditoFk
     *
     * @param integer $codigoNotaCreditoFk
     *
     * @return CarNotaCreditoDetalle
     */
    public function setCodigoNotaCreditoFk($codigoNotaCreditoFk)
    {
        $this->codigoNotaCreditoFk = $codigoNotaCreditoFk;

        return $this;
    }

    /**
     * Get codigoNotaCreditoFk
     *
     * @return integer
     */
    public function getCodigoNotaCreditoFk()
    {
        return $this->codigoNotaCreditoFk;
    }

    /**
     * Set valor
     *
     * @param float $valor
     *
     * @return CarNotaCreditoDetalle
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set notaCreditoRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarNotaCredito $notaCreditoRel
     *
     * @return CarNotaCreditoDetalle
     */
    public function setNotaCreditoRel(\Brasa\CarteraBundle\Entity\CarNotaCredito $notaCreditoRel = null)
    {
        $this->notaCreditoRel = $notaCreditoRel;

        return $this;
    }

    /**
     * Get notaCreditoRel
     *
     * @return \Brasa\CarteraBundle\Entity\CarNotaCredito
     */
    public function getNotaCreditoRel()
    {
        return $this->notaCreditoRel;
    }

    /**
     * Set cuentaCobrarRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCuentaCobrar $cuentaCobrarRel
     *
     * @return CarNotaCreditoDetalle
     */
    public function setCuentaCobrarRel(\Brasa\CarteraBundle\Entity\CarCuentaCobrar $cuentaCobrarRel = null)
    {
        $this->cuentaCobrarRel = $cuentaCobrarRel;

        return $this;
    }

    /**
     * Get cuentaCobrarRel
     *
     * @return \Brasa\CarteraBundle\Entity\CarCuentaCobrar
     */
    public function getCuentaCobrarRel()
    {
        return $this->cuentaCobrarRel;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     *
     * @return CarNotaCreditoDetalle
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
}
