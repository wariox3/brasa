<?php

namespace Brasa\CarteraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="car_recibo_caja")
 * @ORM\Entity(repositoryClass="Brasa\CarteraBundle\Repository\CarReciboCajaRepository")
 */
class CarReciboCaja
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_recibo_caja_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoReciboCajaPk;        

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;     

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */     
    private $codigoClienteFk;
    
    /**
     * @ORM\Column(name="codigo_banco_fk", type="integer", nullable=true)
     */    
    private $codigoBancoFk;
    
    /**
     * @ORM\Column(name="codigo_recibo_caja_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoReciboCajaTipoFk;
    
    /**
     * @ORM\Column(name="numero", type="string", length=30, nullable=true)
     */    
    private $numero;
    
    /**
     * @ORM\Column(name="fecha_pago", type="date", nullable=true)
     */    
    private $fechaPago;
    
    /**
     * @ORM\Column(name="valor", type="float")
     */    
    private $valor = 0;      
      
    /**     
     * @ORM\Column(name="estado_impreso", type="boolean")
     */    
    private $estadoImpreso = 0;
    
    /**     
     * @ORM\Column(name="estado_impreso", type="boolean")
     */    
    private $estadoImpreso = 0;
    
    /**     
     * @ORM\Column(name="estado_impreso", type="boolean")
     */    
    private $estadoImpreso = 0;
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenTercero", inversedBy="CarCuentaCobrar")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\InventarioBundle\Entity\InvMovimiento", inversedBy="CarCuentaCobrar")
     * @ORM\JoinColumn(name="codigo_movimiento_fk", referencedColumnName="codigo_movimiento_pk")
     */
    protected $movimientoRel;     
    

}
