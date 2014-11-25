<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_puntos_operacion")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TtePuntosOperacionRepository")
 */
class TtePuntosOperacion
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
     * @ORM\OneToMany(targetEntity="TteUsuariosConfiguracion", mappedBy="puntoOperacionRel")
     */
    protected $usuariosConfiguracionRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="TteGuias", mappedBy="puntoOperacionIngresoRel")
     */
    protected $guiasPuntoOperacionIngresoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TteRecogidas", mappedBy="puntoOperacionRel")
     */
    protected $recogidasPuntoOperacionRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TteRecogidasProgramadas", mappedBy="puntoOperacionRel")
     */
    protected $recogidasProgramadasRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TteGuias", mappedBy="puntoOperacionActualRel")
     */
    protected $guiasPuntoOperacionActualRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TteDespachos", mappedBy="puntoOperacionRel")
     */
    protected $despachosPuntoOperacionRel;     

    /**
     * @ORM\OneToMany(targetEntity="TtePlanesRecogidas", mappedBy="puntoOperacionRel")
     */
    protected $planesRecogidasRel;     
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->usuariosConfiguracionRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->guiasPuntoOperacionIngresoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->guiasPuntoOperacionActualRel = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return TtePuntosOperacion
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
     * Set codigoCiudadOrigenFk
     *
     * @param integer $codigoCiudadOrigenFk
     * @return TtePuntosOperacion
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
     * @return TtePuntosOperacion
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
     * Add usuariosConfiguracionRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteUsuariosConfiguracion $usuariosConfiguracionRel
     * @return TtePuntosOperacion
     */
    public function addUsuariosConfiguracionRel(\Brasa\TransporteBundle\Entity\TteUsuariosConfiguracion $usuariosConfiguracionRel)
    {
        $this->usuariosConfiguracionRel[] = $usuariosConfiguracionRel;

        return $this;
    }

    /**
     * Remove usuariosConfiguracionRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteUsuariosConfiguracion $usuariosConfiguracionRel
     */
    public function removeUsuariosConfiguracionRel(\Brasa\TransporteBundle\Entity\TteUsuariosConfiguracion $usuariosConfiguracionRel)
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
     * Add guiasPuntoOperacionIngresoRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuias $guiasPuntoOperacionIngresoRel
     * @return TtePuntosOperacion
     */
    public function addGuiasPuntoOperacionIngresoRel(\Brasa\TransporteBundle\Entity\TteGuias $guiasPuntoOperacionIngresoRel)
    {
        $this->guiasPuntoOperacionIngresoRel[] = $guiasPuntoOperacionIngresoRel;

        return $this;
    }

    /**
     * Remove guiasPuntoOperacionIngresoRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuias $guiasPuntoOperacionIngresoRel
     */
    public function removeGuiasPuntoOperacionIngresoRel(\Brasa\TransporteBundle\Entity\TteGuias $guiasPuntoOperacionIngresoRel)
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
     * @param \Brasa\TransporteBundle\Entity\TteGuias $guiasPuntoOperacionActualRel
     * @return TtePuntosOperacion
     */
    public function addGuiasPuntoOperacionActualRel(\Brasa\TransporteBundle\Entity\TteGuias $guiasPuntoOperacionActualRel)
    {
        $this->guiasPuntoOperacionActualRel[] = $guiasPuntoOperacionActualRel;

        return $this;
    }

    /**
     * Remove guiasPuntoOperacionActualRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuias $guiasPuntoOperacionActualRel
     */
    public function removeGuiasPuntoOperacionActualRel(\Brasa\TransporteBundle\Entity\TteGuias $guiasPuntoOperacionActualRel)
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

    /**
     * Add despachosPuntoOperacionRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteDespachos $despachosPuntoOperacionRel
     * @return TtePuntosOperacion
     */
    public function addDespachosPuntoOperacionRel(\Brasa\TransporteBundle\Entity\TteDespachos $despachosPuntoOperacionRel)
    {
        $this->despachosPuntoOperacionRel[] = $despachosPuntoOperacionRel;

        return $this;
    }

    /**
     * Remove despachosPuntoOperacionRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteDespachos $despachosPuntoOperacionRel
     */
    public function removeDespachosPuntoOperacionRel(\Brasa\TransporteBundle\Entity\TteDespachos $despachosPuntoOperacionRel)
    {
        $this->despachosPuntoOperacionRel->removeElement($despachosPuntoOperacionRel);
    }

    /**
     * Get despachosPuntoOperacionRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDespachosPuntoOperacionRel()
    {
        return $this->despachosPuntoOperacionRel;
    }

    /**
     * Add recogidasPuntoOperacionRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteRecogidas $recogidasPuntoOperacionRel
     * @return TtePuntosOperacion
     */
    public function addRecogidasPuntoOperacionRel(\Brasa\TransporteBundle\Entity\TteRecogidas $recogidasPuntoOperacionRel)
    {
        $this->recogidasPuntoOperacionRel[] = $recogidasPuntoOperacionRel;

        return $this;
    }

    /**
     * Remove recogidasPuntoOperacionRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteRecogidas $recogidasPuntoOperacionRel
     */
    public function removeRecogidasPuntoOperacionRel(\Brasa\TransporteBundle\Entity\TteRecogidas $recogidasPuntoOperacionRel)
    {
        $this->recogidasPuntoOperacionRel->removeElement($recogidasPuntoOperacionRel);
    }

    /**
     * Get recogidasPuntoOperacionRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRecogidasPuntoOperacionRel()
    {
        return $this->recogidasPuntoOperacionRel;
    }

    /**
     * Add recogidasProgramadasRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteRecogidasProgramadas $recogidasProgramadasRel
     * @return TtePuntosOperacion
     */
    public function addRecogidasProgramadasRel(\Brasa\TransporteBundle\Entity\TteRecogidasProgramadas $recogidasProgramadasRel)
    {
        $this->recogidasProgramadasRel[] = $recogidasProgramadasRel;

        return $this;
    }

    /**
     * Remove recogidasProgramadasRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteRecogidasProgramadas $recogidasProgramadasRel
     */
    public function removeRecogidasProgramadasRel(\Brasa\TransporteBundle\Entity\TteRecogidasProgramadas $recogidasProgramadasRel)
    {
        $this->recogidasProgramadasRel->removeElement($recogidasProgramadasRel);
    }

    /**
     * Get recogidasProgramadasRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRecogidasProgramadasRel()
    {
        return $this->recogidasProgramadasRel;
    }

    /**
     * Add planesRecogidasRel
     *
     * @param \Brasa\TransporteBundle\Entity\TtePlanesRecogidas $planesRecogidasRel
     * @return TtePuntosOperacion
     */
    public function addPlanesRecogidasRel(\Brasa\TransporteBundle\Entity\TtePlanesRecogidas $planesRecogidasRel)
    {
        $this->planesRecogidasRel[] = $planesRecogidasRel;

        return $this;
    }

    /**
     * Remove planesRecogidasRel
     *
     * @param \Brasa\TransporteBundle\Entity\TtePlanesRecogidas $planesRecogidasRel
     */
    public function removePlanesRecogidasRel(\Brasa\TransporteBundle\Entity\TtePlanesRecogidas $planesRecogidasRel)
    {
        $this->planesRecogidasRel->removeElement($planesRecogidasRel);
    }

    /**
     * Get planesRecogidasRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlanesRecogidasRel()
    {
        return $this->planesRecogidasRel;
    }
}
