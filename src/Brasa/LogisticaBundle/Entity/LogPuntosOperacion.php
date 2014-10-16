<?php

namespace Brasa\LogisticaBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="log_puntos_operacion")
 * @ORM\Entity(repositoryClass="Brasa\LogisticaBundle\Repository\LogPuntosOperacionRepository")
 */
class LogPuntosOperacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_punto_operacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPuntoOperacionPk;  
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;    

    /**
     * @ORM\Column(name="codigo_ciudad_origen_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadOrigenFk;     
    
        /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudades", inversedBy="puntosOperacionCiudadOrigenRel")
     * @ORM\JoinColumn(name="codigo_ciudad_origen_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadOrigenRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="LogUsuariosConfiguracion", mappedBy="puntoOperacionRel")
     */
    protected $usuariosConfiguracionRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="LogGuias", mappedBy="puntoOperacionIngresoRel")
     */
    protected $guiasPuntoOperacionIngresoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="LogGuias", mappedBy="puntoOperacionActualRel")
     */
    protected $guiasPuntoOperacionActualRel;    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->usuariosConfiguracionRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPuntoOperacionPk
     *
     * @return integer 
     */
    public function getCodigoPuntoOperacionPk()
    {
        return $this->codigoPuntoOperacionPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return LogPuntosOperacion
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
     * Add usuariosConfiguracionRel
     *
     * @param \Brasa\LogisticaBundle\Entity\LogUsuariosConfiguracion $usuariosConfiguracionRel
     * @return LogPuntosOperacion
     */
    public function addUsuariosConfiguracionRel(\Brasa\LogisticaBundle\Entity\LogUsuariosConfiguracion $usuariosConfiguracionRel)
    {
        $this->usuariosConfiguracionRel[] = $usuariosConfiguracionRel;

        return $this;
    }

    /**
     * Remove usuariosConfiguracionRel
     *
     * @param \Brasa\LogisticaBundle\Entity\LogUsuariosConfiguracion $usuariosConfiguracionRel
     */
    public function removeUsuariosConfiguracionRel(\Brasa\LogisticaBundle\Entity\LogUsuariosConfiguracion $usuariosConfiguracionRel)
    {
        $this->usuariosConfiguracionRel->removeElement($usuariosConfiguracionRel);
    }

    /**
     * Get usuariosConfiguracionRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsuariosConfiguracionRel()
    {
        return $this->usuariosConfiguracionRel;
    }

    /**
     * Set codigoCiudadOrigenFk
     *
     * @param integer $codigoCiudadOrigenFk
     * @return LogPuntosOperacion
     */
    public function setCodigoCiudadOrigenFk($codigoCiudadOrigenFk)
    {
        $this->codigoCiudadOrigenFk = $codigoCiudadOrigenFk;

        return $this;
    }

    /**
     * Get codigoCiudadOrigenFk
     *
     * @return integer 
     */
    public function getCodigoCiudadOrigenFk()
    {
        return $this->codigoCiudadOrigenFk;
    }

    /**
     * Set ciudadOrigenRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudades $ciudadOrigenRel
     * @return LogPuntosOperacion
     */
    public function setCiudadOrigenRel(\Brasa\GeneralBundle\Entity\GenCiudades $ciudadOrigenRel = null)
    {
        $this->ciudadOrigenRel = $ciudadOrigenRel;

        return $this;
    }

    /**
     * Get ciudadOrigenRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudades 
     */
    public function getCiudadOrigenRel()
    {
        return $this->ciudadOrigenRel;
    }

    /**
     * Add guiasPuntoOperacionIngresoRel
     *
     * @param \Brasa\LogisticaBundle\Entity\LogGuias $guiasPuntoOperacionIngresoRel
     * @return LogPuntosOperacion
     */
    public function addGuiasPuntoOperacionIngresoRel(\Brasa\LogisticaBundle\Entity\LogGuias $guiasPuntoOperacionIngresoRel)
    {
        $this->guiasPuntoOperacionIngresoRel[] = $guiasPuntoOperacionIngresoRel;

        return $this;
    }

    /**
     * Remove guiasPuntoOperacionIngresoRel
     *
     * @param \Brasa\LogisticaBundle\Entity\LogGuias $guiasPuntoOperacionIngresoRel
     */
    public function removeGuiasPuntoOperacionIngresoRel(\Brasa\LogisticaBundle\Entity\LogGuias $guiasPuntoOperacionIngresoRel)
    {
        $this->guiasPuntoOperacionIngresoRel->removeElement($guiasPuntoOperacionIngresoRel);
    }

    /**
     * Get guiasPuntoOperacionIngresoRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGuiasPuntoOperacionIngresoRel()
    {
        return $this->guiasPuntoOperacionIngresoRel;
    }

    /**
     * Add guiasPuntoOperacionActualRel
     *
     * @param \Brasa\LogisticaBundle\Entity\LogGuias $guiasPuntoOperacionActualRel
     * @return LogPuntosOperacion
     */
    public function addGuiasPuntoOperacionActualRel(\Brasa\LogisticaBundle\Entity\LogGuias $guiasPuntoOperacionActualRel)
    {
        $this->guiasPuntoOperacionActualRel[] = $guiasPuntoOperacionActualRel;

        return $this;
    }

    /**
     * Remove guiasPuntoOperacionActualRel
     *
     * @param \Brasa\LogisticaBundle\Entity\LogGuias $guiasPuntoOperacionActualRel
     */
    public function removeGuiasPuntoOperacionActualRel(\Brasa\LogisticaBundle\Entity\LogGuias $guiasPuntoOperacionActualRel)
    {
        $this->guiasPuntoOperacionActualRel->removeElement($guiasPuntoOperacionActualRel);
    }

    /**
     * Get guiasPuntoOperacionActualRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGuiasPuntoOperacionActualRel()
    {
        return $this->guiasPuntoOperacionActualRel;
    }
}
