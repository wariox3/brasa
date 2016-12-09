<?php

namespace Brasa\RecursoHumanoBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_parametro_prestacion")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuParametroPrestacionRepository")
 */
class RhuParametroPrestacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_parametro_prestacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoParametroPrestacionPk;
    


    /**
     * Get codigoParametroPrestacionPk
     *
     * @return integer
     */
    public function getCodigoParametroPrestacionPk()
    {
        return $this->codigoParametroPrestacionPk;
    }
}
