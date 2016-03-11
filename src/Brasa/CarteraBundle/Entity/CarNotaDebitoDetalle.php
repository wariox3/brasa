<?php

namespace Brasa\CarteraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="car_nota_debito_detalle")
 * @ORM\Entity(repositoryClass="Brasa\CarteraBundle\Repository\CarNotaDebitoDetalleRepository")
 */
class CarNotaDebitoDetalle
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_nota_debito_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoNotaDebitoDetallePk;        

    /**
     * @ORM\Column(name="codigo_nota_debito_fk", type="integer", nullable=true)
     */     
    private $codigoNotaDebitoFk;
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;     

    /**
     * @ORM\Column(name="codigo_cuenta_cobrar_fk", type="integer", nullable=true)
     */     
    private $codigoCuentaCobrarFk;
    
    /**
     * @ORM\Column(name="valor", type="float")
     */    
    private $valor = 0;  

    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */    
    private $usuario;
    
    /**
     * @ORM\ManyToOne(targetEntity="CarNotaDebito", inversedBy="notasDebitosDetallesNotaDebitoRel")
     * @ORM\JoinColumn(name="codigo_nota_debito_fk", referencedColumnName="codigo_nota_debito_pk")
     */
    protected $notaDebitoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="CarCuentaCobrar", inversedBy="notasDebitosDetallesCuentaCobrarRel")
     * @ORM\JoinColumn(name="codigo_cuenta_cobrar_fk", referencedColumnName="codigo_cuenta_cobrar_pk")
     */
    protected $cuentaCobrarRel;

    

    /**
     * Get codigoNotaDebitoDetallePk
     *
     * @return integer
     */
    public function getCodigoNotaDebitoDetallePk()
    {
        return $this->codigoNotaDebitoDetallePk;
    }

    /**
     * Set codigoNotaDebitoFk
     *
     * @param integer $codigoNotaDebitoFk
     *
     * @return CarNotaDebitoDetalle
     */
    public function setCodigoNotaDebitoFk($codigoNotaDebitoFk)
    {
        $this->codigoNotaDebitoFk = $codigoNotaDebitoFk;

        return $this;
    }

    /**
     * Get codigoNotaDebitoFk
     *
     * @return integer
     */
    public function getCodigoNotaDebitoFk()
    {
        return $this->codigoNotaDebitoFk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return CarNotaDebitoDetalle
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
     * @return CarNotaDebitoDetalle
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
     * Set valor
     *
     * @param float $valor
     *
     * @return CarNotaDebitoDetalle
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
     * Set notaDebitoRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarNotaDebito $notaDebitoRel
     *
     * @return CarNotaDebitoDetalle
     */
    public function setNotaDebitoRel(\Brasa\CarteraBundle\Entity\CarNotaDebito $notaDebitoRel = null)
    {
        $this->notaDebitoRel = $notaDebitoRel;

        return $this;
    }

    /**
     * Get notaDebitoRel
     *
     * @return \Brasa\CarteraBundle\Entity\CarNotaDebito
     */
    public function getNotaDebitoRel()
    {
        return $this->notaDebitoRel;
    }

    /**
     * Set cuentaCobrarRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCuentaCobrar $cuentaCobrarRel
     *
     * @return CarNotaDebitoDetalle
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
     * @return CarNotaDebitoDetalle
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
