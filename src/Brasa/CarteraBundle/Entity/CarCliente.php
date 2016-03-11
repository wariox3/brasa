<?php

namespace Brasa\CarteraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="car_cliente")
 * @ORM\Entity(repositoryClass="Brasa\CarteraBundle\Repository\CarClienteRepository")
 */
class CarCliente
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cliente_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoClientePk;        

    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;    
    
    /**
     * @ORM\OneToMany(targetEntity="CarCuentaCobrar", mappedBy="clienteRel")
     */
    protected $cuentasCobrarTiposClienteRel;
}