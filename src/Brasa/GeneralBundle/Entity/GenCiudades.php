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
     * @ORM\ManyToOne(targetEntity="Brasa\LogisticaBundle\Entity\LogRutas", inversedBy="ciudadesRel")
     * @ORM\JoinColumn(name="codigo_ruta_predeterminada_fk", referencedColumnName="codigo_ruta_pk")
     */
    protected $rutaRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="GenTercerosDirecciones", mappedBy="ciudadRel")
     */
    protected $tercerosDireccionesRel;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\LogisticaBundle\Entity\LogGuias", mappedBy="ciudadDestinoRel")
     */
    protected $guiasRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\LogisticaBundle\Entity\LogDespachos", mappedBy="ciudadOrigenRel")
     */
    protected $despachosCiudadOrigenRel;    

    /**
     * @ORM\OneToMany(targetEntity="Brasa\LogisticaBundle\Entity\LogDespachos", mappedBy="ciudadDestinoRel")
     */
    protected $despachosCiudadDestinoRel;        

    
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
     * @param \Brasa\LogisticaBundle\Entity\LogRutas $rutaRel
     * @return GenCiudades
     */
    public function setRutaRel(\Brasa\LogisticaBundle\Entity\LogRutas $rutaRel = null)
    {
        $this->rutaRel = $rutaRel;

        return $this;
    }

    /**
     * Get rutaRel
     *
     * @return \Brasa\LogisticaBundle\Entity\LogRutas 
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
     * @param \Brasa\LogisticaBundle\Entity\LogGuias $guiasRel
     * @return GenCiudades
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
     * Add despachosCiudadOrigenRel
     *
     * @param \Brasa\LogisticaBundle\Entity\LogDespachos $despachosCiudadOrigenRel
     * @return GenCiudades
     */
    public function addDespachosCiudadOrigenRel(\Brasa\LogisticaBundle\Entity\LogDespachos $despachosCiudadOrigenRel)
    {
        $this->despachosCiudadOrigenRel[] = $despachosCiudadOrigenRel;

        return $this;
    }

    /**
     * Remove despachosCiudadOrigenRel
     *
     * @param \Brasa\LogisticaBundle\Entity\LogDespachos $despachosCiudadOrigenRel
     */
    public function removeDespachosCiudadOrigenRel(\Brasa\LogisticaBundle\Entity\LogDespachos $despachosCiudadOrigenRel)
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
     * @param \Brasa\LogisticaBundle\Entity\LogDespachos $despachosCiudadDestinoRel
     * @return GenCiudades
     */
    public function addDespachosCiudadDestinoRel(\Brasa\LogisticaBundle\Entity\LogDespachos $despachosCiudadDestinoRel)
    {
        $this->despachosCiudadDestinoRel[] = $despachosCiudadDestinoRel;

        return $this;
    }

    /**
     * Remove despachosCiudadDestinoRel
     *
     * @param \Brasa\LogisticaBundle\Entity\LogDespachos $despachosCiudadDestinoRel
     */
    public function removeDespachosCiudadDestinoRel(\Brasa\LogisticaBundle\Entity\LogDespachos $despachosCiudadDestinoRel)
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
}
