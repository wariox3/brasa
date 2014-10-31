<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_configuraciones")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteConfiguracionesRepository")
 */
class TteConfiguraciones
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_configuracion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoConfiguracionPk;  
    
    /**
     * @ORM\Column(name="consecutivo_guias", type="integer")
     */
    private $consecutivoGuias = 0; 
    


    /**
     * Get codigoConfiguracionPk
     *
     * @return integer 
     */
    public function getCodigoConfiguracionPk()
    {
        return $this->codigoConfiguracionPk;
    }

    /**
     * Set consecutivoGuias
     *
     * @param integer $consecutivoGuias
     * @return TteConfiguraciones
     */
    public function setConsecutivoGuias($consecutivoGuias)
    {
        $this->consecutivoGuias = $consecutivoGuias;

        return $this;
    }

    /**
     * Get consecutivoGuias
     *
     * @return integer 
     */
    public function getConsecutivoGuias()
    {
        return $this->consecutivoGuias;
    }
}
