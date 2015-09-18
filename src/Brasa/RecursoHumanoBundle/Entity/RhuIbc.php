<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_ibc")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuIbcRepository")
 */
class RhuIbc
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_ibc_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoIbcPk;    
    


    /**
     * Get codigoIbcPk
     *
     * @return integer
     */
    public function getCodigoIbcPk()
    {
        return $this->codigoIbcPk;
    }
}
