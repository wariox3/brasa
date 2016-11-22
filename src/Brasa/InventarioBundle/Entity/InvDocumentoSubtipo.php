<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_documento_subtipo")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvDocumentoSubtipoRepository")
 */
class InvDocumentoSubtipo
{   
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_documento_subtipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */ 
    private $codigoDocumentoSubtipoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */        
    private $nombre;
    


    

    /**
     * Get codigoDocumentoSubtipoPk
     *
     * @return integer
     */
    public function getCodigoDocumentoSubtipoPk()
    {
        return $this->codigoDocumentoSubtipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return InvDocumentoSubtipo
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
