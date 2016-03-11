<?php

namespace Brasa\CarteraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="car_cuenta_cobrar")
 * @ORM\Entity(repositoryClass="Brasa\CarteraBundle\Repository\CarCuentaCobrarRepository")
 */
class CarCuentaCobrar
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cuenta_cobrar_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoCuentaCobrarPk;        

    /**
     * @ORM\Column(name="codigo_cuenta_cobrar_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoCuentCobrarTipoFk;
    
    /**
     * @ORM\Column(name="numero_documento", type="string", length=30, nullable=true)
     */    
    private $numero_documento;
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;     

    /**
     * @ORM\Column(name="fecha_vence", type="date", nullable=true)
     */    
    private $fechaVence; 
    
    /**
     * @ORM\Column(name="plazo", type="string", length=10, nullable=true)
     */    
    private $plazo;
    
    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;        
    
    /**
     * @ORM\Column(name="valor_original", type="float")
     */    
    private $valorOriginal = 0;    
    
    /**
     * @ORM\Column(name="abono", type="float")
     */    
    private $abono = 0;
    
    /**
     * @ORM\Column(name="saldo", type="float")
     */    
    private $saldo = 0;        
    
    /**
     * @ORM\Column(name="debitos", type="float")
     */    
    private $debitos = 0;
    
    /**
     * @ORM\Column(name="creditos", type="float")
     */    
    private $creditos = 0;              
    
    /**
     * @ORM\ManyToOne(targetEntity="CarCliente", inversedBy="cuentaCobrarClientesRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="CarCuentaCobrarTipo", inversedBy="cuentasCobrarTiposClienteRel")
     * @ORM\JoinColumn(name="codigo_cuenta_cobrar_tipo_fk", referencedColumnName="codigo_cuenta_cobrar_tipo_pk")
     */
    protected $cuentaCobrarTipoRel;

    
}
