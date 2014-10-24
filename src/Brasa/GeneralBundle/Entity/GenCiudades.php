<?php

namespace Brasa\GeneralBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_ciudades")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenCiudadesRepository")
 */
class GenCiudades
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_ciudad_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCiudadPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     * @Assert\NotNull()(message="Debe escribir un nombre")
     */
    private $nombre;
   
    /**
     * @ORM\Column(name="codigo_departamento_fk", type="integer")
     */
    private $codigoDepartamentoFk;     

    /**
     * @ORM\Column(name="codigo_ruta_predeterminada_fk", type="integer")
     */
    private $codigoRutaPredeterminadaFk;    
    
    /**
     * @ORM\ManyToOne(targetEntity="GenDepartamentos", inversedBy="ciudadesRel")
     * @ORM\JoinColumn(name="codigo_departamento_fk", referencedColumnName="codigo_departamento_pk")
     */
    protected $departamentoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\TransporteBundle\Entity\TteRutas", inversedBy="ciudadesRel")
     * @ORM\JoinColumn(name="codigo_ruta_predeterminada_fk", referencedColumnName="codigo_ruta_pk")
     */
    protected $rutaRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="GenTercerosDirecciones", mappedBy="ciudadRel")
     */
    protected $tercerosDireccionesRel;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\TransporteBundle\Entity\TteGuias", mappedBy="ciudadOrigenRel")
     */
    protected $guiasCiudadOrigenRel;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\TransporteBundle\Entity\TteGuias", mappedBy="ciudadDestinoRel")
     */
    protected $guiasCiudadDestinoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\TransporteBundle\Entity\TteListasPreciosDetalles", mappedBy="ciudadDestinoRel")
     */
    protected $lpdCiudadDestinoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\TransporteBundle\Entity\TteDespachos", mappedBy="ciudadOrigenRel")
     */
    protected $despachosCiudadOrigenRel;    

    /**
     * @ORM\OneToMany(targetEntity="Brasa\TransporteBundle\Entity\TteDespachos", mappedBy="ciudadDestinoRel")
     */
    protected $despachosCiudadDestinoRel;        

    /**
     * @ORM\OneToMany(targetEntity="Brasa\TransporteBundle\Entity\TtePuntosOperacion", mappedBy="ciudadOrigenRel")
     */
    protected $puntosOperacionCiudadOrigenRel;    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tercerosDireccionesRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->guiasRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->despachosCiudadOrigenRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->despachosCiudadDestinoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoCiudadPk
     *
     * @return integer 
     */
    public function getCodigoCiudadPk()
    {
        return $this->codigoCiudadPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return GenCiudades
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
     * Set codigoDepartamentoFk
     *
     * @param integer $codigoDepartamentoFk
     * @return GenCiudades
     */
    public function setCodigoDepartamentoFk($codigoDepartamentoFk)
    {
        $this->codigoDepartamentoFk = $codigoDepartamentoFk;

        return $this;
    }

    /**
     * Get codigoDepartamentoFk
     *
     * @return integer 
     */
    public function getCodigoDepartamentoFk()
    {
        return $this->codigoDepartamentoFk;
    }

    /**
     * Set codigoRutaPredeterminadaFk
     *
     * @param integer $codigoRutaPredeterminadaFk
     * @return GenCiudades
     */
    public function setCodigoRutaPredeterminadaFk($codigoRutaPredeterminadaFk)
    {
        $this->codigoRutaPredeterminadaFk = $codigoRutaPredeterminadaFk;

        return $this;
    }

    /**
     * Get codigoRutaPredeterminadaFk
     *
     * @return integer 
     */
    public function getCodigoRutaPredeterminadaFk()
    {
        return $this->codigoRutaPredeterminadaFk;
    }

    /**
     * Set departamentoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenDepartamentos $departamentoRel
     * @return GenCiudades
     */
    public function setDepartamentoRel(\Brasa\GeneralBundle\Entity\GenDepartamentos $departamentoRel = null)
    {
        $this->departamentoRel = $departamentoRel;

        return $this;
    }

    /**
     * Get departamentoRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenDepartamentos 
     */
    public function getDepartamentoRel()
    {
        return $this->departamentoRel;
    }

    /**
     * Set rutaRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteRutas $rutaRel
     * @return GenCiudades
     */
    public function setRutaRel(\Brasa\TransporteBundle\Entity\TteRutas $rutaRel = null)
    {
        $this->rutaRel = $rutaRel;

        return $this;
    }

    /**
     * Get rutaRel
     *
     * @return \Brasa\TransporteBundle\Entity\TteRutas 
     */
    public function getRutaRel()
    {
        return $this->rutaRel;
    }

    /**
     * Add tercerosDireccionesRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTercerosDirecciones $tercerosDireccionesRel
     * @return GenCiudades
     */
    public function addTercerosDireccionesRel(\Brasa\GeneralBundle\Entity\GenTercerosDirecciones $tercerosDireccionesRel)
    {
        $this->tercerosDireccionesRel[] = $tercerosDireccionesRel;

        return $this;
    }

    /**
     * Remove tercerosDireccionesRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTercerosDirecciones $tercerosDireccionesRel
     */
    public function removeTercerosDireccionesRel(\Brasa\GeneralBundle\Entity\GenTercerosDirecciones $tercerosDireccionesRel)
    {
        $this->tercerosDireccionesRel->removeElement($tercerosDireccionesRel);
    }

    /**
     * Get tercerosDireccionesRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTercerosDireccionesRel()
    {
        return $this->tercerosDireccionesRel;
    }

    /**
     * Add guiasRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuias $guiasRel
     * @return GenCiudades
     */
    public function addGuiasRel(\Brasa\TransporteBundle\Entity\TteGuias $guiasRel)
    {
        $this->guiasRel[] = $guiasRel;

        return $this;
    }

    /**
     * Remove guiasRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuias $guiasRel
     */
    public function removeGuiasRel(\Brasa\TransporteBundle\Entity\TteGuias $guiasRel)
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
     * Add despachosCiudadOrigenRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteDespachos $despachosCiudadOrigenRel
     * @return GenCiudades
     */
    public function addDespachosCiudadOrigenRel(\Brasa\TransporteBundle\Entity\TteDespachos $despachosCiudadOrigenRel)
    {
        $this->despachosCiudadOrigenRel[] = $despachosCiudadOrigenRel;

        return $this;
    }

    /**
     * Remove despachosCiudadOrigenRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteDespachos $despachosCiudadOrigenRel
     */
    public function removeDespachosCiudadOrigenRel(\Brasa\TransporteBundle\Entity\TteDespachos $despachosCiudadOrigenRel)
    {
        $this->despachosCiudadOrigenRel->removeElement($despachosCiudadOrigenRel);
    }

    /**
     * Get despachosCiudadOrigenRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDespachosCiudadOrigenRel()
    {
        return $this->despachosCiudadOrigenRel;
    }

    /**
     * Add despachosCiudadDestinoRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteDespachos $despachosCiudadDestinoRel
     * @return GenCiudades
     */
    public function addDespachosCiudadDestinoRel(\Brasa\TransporteBundle\Entity\TteDespachos $despachosCiudadDestinoRel)
    {
        $this->despachosCiudadDestinoRel[] = $despachosCiudadDestinoRel;

        return $this;
    }

    /**
     * Remove despachosCiudadDestinoRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteDespachos $despachosCiudadDestinoRel
     */
    public function removeDespachosCiudadDestinoRel(\Brasa\TransporteBundle\Entity\TteDespachos $despachosCiudadDestinoRel)
    {
        $this->despachosCiudadDestinoRel->removeElement($despachosCiudadDestinoRel);
    }

    /**
     * Get despachosCiudadDestinoRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDespachosCiudadDestinoRel()
    {
        return $this->despachosCiudadDestinoRel;
    }

    /**
     * Add guiasCiudadOrigenRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuias $guiasCiudadOrigenRel
     * @return GenCiudades
     */
    public function addGuiasCiudadOrigenRel(\Brasa\TransporteBundle\Entity\TteGuias $guiasCiudadOrigenRel)
    {
        $this->guiasCiudadOrigenRel[] = $guiasCiudadOrigenRel;

        return $this;
    }

    /**
     * Remove guiasCiudadOrigenRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuias $guiasCiudadOrigenRel
     */
    public function removeGuiasCiudadOrigenRel(\Brasa\TransporteBundle\Entity\TteGuias $guiasCiudadOrigenRel)
    {
        $this->guiasCiudadOrigenRel->removeElement($guiasCiudadOrigenRel);
    }

    /**
     * Get guiasCiudadOrigenRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGuiasCiudadOrigenRel()
    {
        return $this->guiasCiudadOrigenRel;
    }

    /**
     * Add guiasCiudadDestinoRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuias $guiasCiudadDestinoRel
     * @return GenCiudades
     */
    public function addGuiasCiudadDestinoRel(\Brasa\TransporteBundle\Entity\TteGuias $guiasCiudadDestinoRel)
    {
        $this->guiasCiudadDestinoRel[] = $guiasCiudadDestinoRel;

        return $this;
    }

    /**
     * Remove guiasCiudadDestinoRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuias $guiasCiudadDestinoRel
     */
    public function removeGuiasCiudadDestinoRel(\Brasa\TransporteBundle\Entity\TteGuias $guiasCiudadDestinoRel)
    {
        $this->guiasCiudadDestinoRel->removeElement($guiasCiudadDestinoRel);
    }

    /**
     * Get guiasCiudadDestinoRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGuiasCiudadDestinoRel()
    {
        return $this->guiasCiudadDestinoRel;
    }

    /**
     * Add puntosOperacionCiudadOrigenRel
     *
     * @param \Brasa\TransporteBundle\Entity\TtePuntosOperacion $puntosOperacionCiudadOrigenRel
     * @return GenCiudades
     */
    public function addPuntosOperacionCiudadOrigenRel(\Brasa\TransporteBundle\Entity\TtePuntosOperacion $puntosOperacionCiudadOrigenRel)
    {
        $this->puntosOperacionCiudadOrigenRel[] = $puntosOperacionCiudadOrigenRel;

        return $this;
    }

    /**
     * Remove puntosOperacionCiudadOrigenRel
     *
     * @param \Brasa\TransporteBundle\Entity\TtePuntosOperacion $puntosOperacionCiudadOrigenRel
     */
    public function removePuntosOperacionCiudadOrigenRel(\Brasa\TransporteBundle\Entity\TtePuntosOperacion $puntosOperacionCiudadOrigenRel)
    {
        $this->puntosOperacionCiudadOrigenRel->removeElement($puntosOperacionCiudadOrigenRel);
    }

    /**
     * Get puntosOperacionCiudadOrigenRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPuntosOperacionCiudadOrigenRel()
    {
        return $this->puntosOperacionCiudadOrigenRel;
    }

    /**
     * Add lpdCiudadDestinoRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteListasPreciosDetalles $lpdCiudadDestinoRel
     * @return GenCiudades
     */
    public function addLpdCiudadDestinoRel(\Brasa\TransporteBundle\Entity\TteListasPreciosDetalles $lpdCiudadDestinoRel)
    {
        $this->lpdCiudadDestinoRel[] = $lpdCiudadDestinoRel;

        return $this;
    }

    /**
     * Remove lpdCiudadDestinoRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteListasPreciosDetalles $lpdCiudadDestinoRel
     */
    public function removeLpdCiudadDestinoRel(\Brasa\TransporteBundle\Entity\TteListasPreciosDetalles $lpdCiudadDestinoRel)
    {
        $this->lpdCiudadDestinoRel->removeElement($lpdCiudadDestinoRel);
    }

    /**
     * Get lpdCiudadDestinoRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLpdCiudadDestinoRel()
    {
        return $this->lpdCiudadDestinoRel;
    }
}
