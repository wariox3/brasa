<?php

namespace Brasa\LogisticaBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="log_rutas")
 * @ORM\Entity(repositoryClass="Brasa\LogisticaBundle\Repository\LogRutasRepository")
 */
class LogRutas
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_ruta_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRutaPk;  
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;    

    /**
     * @ORM\OneToMany(targetEntity="Brasa\GeneralBundle\Entity\GenCiudades", mappedBy="rutaRel")
     */
    protected $ciudadesRel;

    /**
     * @ORM\OneToMany(targetEntity="LogGuias", mappedBy="rutaRel")
     */
    protected $guiasRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="LogDespachos", mappedBy="rutaRel")
     */
    protected $despachosRel;     
    
    /**
     * Get codigoRutaPk
     *
     * @return integer 
     */
    public function getCodigoRutaPk()
    {
        return $this->codigoRutaPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return LogRutas
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
     * Constructor
     */
    public function __construct()
    {
        $this->ciudadesRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add ciudadesRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudades $ciudadesRel
     * @return LogRutas
     */
    public function addCiudadesRel(\Brasa\GeneralBundle\Entity\GenCiudades $ciudadesRel)
    {
        $this->ciudadesRel[] = $ciudadesRel;

        return $this;
    }

    /**
     * Remove ciudadesRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudades $ciudadesRel
     */
    public function removeCiudadesRel(\Brasa\GeneralBundle\Entity\GenCiudades $ciudadesRel)
    {
        $this->ciudadesRel->removeElement($ciudadesRel);
    }

    /**
     * Get ciudadesRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCiudadesRel()
    {
        return $this->ciudadesRel;
    }

    /**
     * Add guiasRel
     *
     * @param \Brasa\LogisticaBundle\Entity\LogGuias $guiasRel
     * @return LogRutas
     */
    public function addGuiasRel(\Brasa\LogisticaBundle\Entity\LogGuias $guiasRel)
    {
        $this->guiasRel[] = $guiasRel;

        return $this;
    }

    /**
     * Remove guiasRel
     *
     * @param \Brasa\LogisticaBundle\Entity\LogGuias $guiasRel
     */
    public function removeGuiasRel(\Brasa\LogisticaBundle\Entity\LogGuias $guiasRel)
    {
        $this->guiasRel->removeElement($guiasRel);
    }

    /**
     * Get guiasRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGuiasRel()
    {
        return $this->guiasRel;
    }

    /**
     * Add despachosRel
     *
     * @param \Brasa\LogisticaBundle\Entity\LogDespachos $despachosRel
     * @return LogRutas
     */
    public function addDespachosRel(\Brasa\LogisticaBundle\Entity\LogDespachos $despachosRel)
    {
        $this->despachosRel[] = $despachosRel;

        return $this;
    }

    /**
     * Remove despachosRel
     *
     * @param \Brasa\LogisticaBundle\Entity\LogDespachos $despachosRel
     */
    public function removeDespachosRel(\Brasa\LogisticaBundle\Entity\LogDespachos $despachosRel)
    {
        $this->despachosRel->removeElement($despachosRel);
    }

    /**
     * Get despachosRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDespachosRel()
    {
        return $this->despachosRel;
    }
}
