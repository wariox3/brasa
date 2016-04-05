<?php

namespace Brasa\CarteraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="car_configuracion")
 * @ORM\Entity(repositoryClass="Brasa\CarteraBundle\Repository\CarConfiguracionRepository")
 */
class CarConfiguracion
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_configuracion_pk", type="integer")
     */
    private $codigoConfiguracionPk;
        
    
}
