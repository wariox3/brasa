<?php

namespace Brasa\GeneralBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_terceros")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenTercerosRepository")
 */
class GenTerceros
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPk;

    /**
     * @ORM\Column(name="nombre_corto", type="string", length=50)
     */
    private $nombreCorto;


    /**
     * Get codigoPk
     *
     * @return integer 
     */
    public function getCodigoPk()
    {
        return $this->codigoPk;
    }

    /**
     * Set nombreCorto
     *
     * @param string $nombreCorto
     * @return GenTerceros
     */
    public function setNombreCorto($nombreCorto)
    {
        $this->nombreCorto = $nombreCorto;

        return $this;
    }

    /**
     * Get nombreCorto
     *
     * @return string 
     */
    public function getNombreCorto()
    {
        return $this->nombreCorto;
    }
}
