<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_tipo_acceso")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuTipoAccesoRepository")
 */
class RhuTipoAcceso
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_tipo_acceso_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoTipoAccesoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=20, nullable=true)
     */    
    private $nombre;        
    
    /**
     * @ORM\OneToMany(targetEntity="RhuHorarioAcceso", mappedBy="tipoAccesoRel")
     */
    protected $horarioAccesoTipoAccesoRel;    

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->horarioAccesoTipoAccesoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoTipoAccesoPk
     *
     * @return integer
     */
    public function getCodigoTipoAccesoPk()
    {
        return $this->codigoTipoAccesoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuTipoAcceso
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

    /**
     * Add horarioAccesoTipoAccesoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso $horarioAccesoTipoAccesoRel
     *
     * @return RhuTipoAcceso
     */
    public function addHorarioAccesoTipoAccesoRel(\Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso $horarioAccesoTipoAccesoRel)
    {
        $this->horarioAccesoTipoAccesoRel[] = $horarioAccesoTipoAccesoRel;

        return $this;
    }

    /**
     * Remove horarioAccesoTipoAccesoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso $horarioAccesoTipoAccesoRel
     */
    public function removeHorarioAccesoTipoAccesoRel(\Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso $horarioAccesoTipoAccesoRel)
    {
        $this->horarioAccesoTipoAccesoRel->removeElement($horarioAccesoTipoAccesoRel);
    }

    /**
     * Get horarioAccesoTipoAccesoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHorarioAccesoTipoAccesoRel()
    {
        return $this->horarioAccesoTipoAccesoRel;
    }
}
