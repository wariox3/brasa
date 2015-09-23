<?php

namespace Brasa\GeneralBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_banco")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenBancoRepository")
 */
class GenBanco
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_banco_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoBancoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=60)
     */
    private $nombre;    
    


    /**
     * Get codigoBancoPk
     *
     * @return integer
     */
    public function getCodigoBancoPk()
    {
        return $this->codigoBancoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return GenBanco
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }
}
