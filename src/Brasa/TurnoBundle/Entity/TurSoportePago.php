<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_soporte_pago")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurSoportePagoRepository")
 */
class TurSoportePago
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_soporte_pago_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSoportePagoPk;         
    

    /**
     * Get codigoSoportePagoPk
     *
     * @return integer
     */
    public function getCodigoSoportePagoPk()
    {
        return $this->codigoSoportePagoPk;
    }
}
