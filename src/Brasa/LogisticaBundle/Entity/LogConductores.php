<?php

namespace Brasa\LogisticaBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="log_conductores")
 * @ORM\Entity(repositoryClass="Brasa\LogisticaBundle\Repository\LogConductoresRepository")
 */
class LogConductores
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_conductor_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoConductorPk;  
    

    /**
     * Get codigoConductorPk
     *
     * @return integer 
     */
    public function getCodigoConductorPk()
    {
        return $this->codigoConductorPk;
    }
}
