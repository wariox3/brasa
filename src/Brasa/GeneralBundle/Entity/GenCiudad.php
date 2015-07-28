<?php

namespace Brasa\GeneralBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_ciudad")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenCiudadRepository")
 */
class GenCiudad
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
     * @ORM\Column(name="codigo_ruta_predeterminada_fk", type="integer", nullable=true)
     */
    private $codigoRutaPredeterminadaFk;    
    
    /**
     * @ORM\Column(name="codigo_interface", type="string", length=15)
     */
    private $codigoInterface;    
    
    /**
     * @ORM\ManyToOne(targetEntity="GenDepartamento", inversedBy="ciudadesRel")
     * @ORM\JoinColumn(name="codigo_departamento_fk", referencedColumnName="codigo_departamento_pk")
     */
    protected $departamentoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\TransporteBundle\Entity\TteRuta", inversedBy="ciudadesRel")
     * @ORM\JoinColumn(name="codigo_ruta_predeterminada_fk", referencedColumnName="codigo_ruta_pk")
     */
    protected $rutaRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="GenTerceroDireccion", mappedBy="ciudadRel")
     */
    protected $tercerosDireccionesRel;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\TransporteBundle\Entity\TteGuia", mappedBy="ciudadOrigenRel")
     */
    protected $guiasCiudadOrigenRel;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\TransporteBundle\Entity\TteGuia", mappedBy="ciudadDestinoRel")
     */
    protected $guiasCiudadDestinoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\TransporteBundle\Entity\TteListaPrecioDetalle", mappedBy="ciudadDestinoRel")
     */
    protected $lpdCiudadDestinoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\TransporteBundle\Entity\TteDespacho", mappedBy="ciudadOrigenRel")
     */
    protected $despachosCiudadOrigenRel;    

    /**
     * @ORM\OneToMany(targetEntity="Brasa\TransporteBundle\Entity\TteDespacho", mappedBy="ciudadDestinoRel")
     */
    protected $despachosCiudadDestinoRel;        

    /**
     * @ORM\OneToMany(targetEntity="Brasa\TransporteBundle\Entity\TtePuntoOperacion", mappedBy="ciudadOrigenRel")
     */
    protected $puntosOperacionCiudadOrigenRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuSeleccion", mappedBy="ciudadRel")
     */
    protected $rhuSeleccionesCiudadRel;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto", mappedBy="ciudadRel")
     */
    protected $rhuCentroCostosCiudadRel;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuSeleccion", mappedBy="ciudadNacimientoRel")
     */
    protected $rhuSeleccionesCiudadNacimientoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuSeleccion", mappedBy="ciudadExpedicionRel")
     */
    protected $rhuSeleccionesCiudadExpedicionRel;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuEmpleado", mappedBy="ciudadRel")
     */
    protected $rhuEmpleadosCiudadRel;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuEmpleado", mappedBy="ciudadNacimientoRel")
     */
    protected $rhuEmpleadosCiudadNacimientoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuEmpleado", mappedBy="ciudadExpedicionRel")
     */
    protected $rhuEmpleadosCiudadExpedicionRel;
    
    /**
     * @ORM\OneToMany(targetEntity="GenBarrio", mappedBy="ciudadRel")
     */
    protected $barriosRel;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio", mappedBy="ciudadSecundariaRel")
     */
    protected $rhuEmpleadoEstudioCiudadSecundariaRel;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio", mappedBy="ciudadTecnicaRel")
     */
    protected $rhuEmpleadoEstudioCiudadTecnicaRel;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio", mappedBy="ciudadTecnologiaRel")
     */
    protected $rhuEmpleadoEstudioCiudadTecnologiaRel;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio", mappedBy="ciudadUniversitarioRel")
     */
    protected $rhuEmpleadoEstudioCiudadUniversitarioRel;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio", mappedBy="ciudadOtro1Rel")
     */
    protected $rhuEmpleadoEstudioCiudadOtro1Rel;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio", mappedBy="ciudadOtro2Rel")
     */
    protected $rhuEmpleadoEstudioCiudadOtro2Rel;
    
   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tercerosDireccionesRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->guiasCiudadOrigenRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->guiasCiudadDestinoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->lpdCiudadDestinoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->despachosCiudadOrigenRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->despachosCiudadDestinoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->puntosOperacionCiudadOrigenRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->rhuSeleccionesCiudadRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->rhuEmpleadosCiudadRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->barriosRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set codigoCiudadPk
     *
     * @param integer $codigoCiudadPk
     *
     * @return GenCiudad
     */
    public function setCodigoCiudadPk($codigoCiudadPk)
    {
        $this->codigoCiudadPk = $codigoCiudadPk;

        return $this;
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
     *
     * @return GenCiudad
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
     *
     * @return GenCiudad
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
     *
     * @return GenCiudad
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
     * @param \Brasa\GeneralBundle\Entity\GenDepartamento $departamentoRel
     *
     * @return GenCiudad
     */
    public function setDepartamentoRel(\Brasa\GeneralBundle\Entity\GenDepartamento $departamentoRel = null)
    {
        $this->departamentoRel = $departamentoRel;

        return $this;
    }

    /**
     * Get departamentoRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenDepartamento
     */
    public function getDepartamentoRel()
    {
        return $this->departamentoRel;
    }

    /**
     * Set rutaRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteRuta $rutaRel
     *
     * @return GenCiudad
     */
    public function setRutaRel(\Brasa\TransporteBundle\Entity\TteRuta $rutaRel = null)
    {
        $this->rutaRel = $rutaRel;

        return $this;
    }

    /**
     * Get rutaRel
     *
     * @return \Brasa\TransporteBundle\Entity\TteRuta
     */
    public function getRutaRel()
    {
        return $this->rutaRel;
    }

    /**
     * Add tercerosDireccionesRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTerceroDireccion $tercerosDireccionesRel
     *
     * @return GenCiudad
     */
    public function addTercerosDireccionesRel(\Brasa\GeneralBundle\Entity\GenTerceroDireccion $tercerosDireccionesRel)
    {
        $this->tercerosDireccionesRel[] = $tercerosDireccionesRel;

        return $this;
    }

    /**
     * Remove tercerosDireccionesRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTerceroDireccion $tercerosDireccionesRel
     */
    public function removeTercerosDireccionesRel(\Brasa\GeneralBundle\Entity\GenTerceroDireccion $tercerosDireccionesRel)
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
     * Add guiasCiudadOrigenRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuia $guiasCiudadOrigenRel
     *
     * @return GenCiudad
     */
    public function addGuiasCiudadOrigenRel(\Brasa\TransporteBundle\Entity\TteGuia $guiasCiudadOrigenRel)
    {
        $this->guiasCiudadOrigenRel[] = $guiasCiudadOrigenRel;

        return $this;
    }

    /**
     * Remove guiasCiudadOrigenRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuia $guiasCiudadOrigenRel
     */
    public function removeGuiasCiudadOrigenRel(\Brasa\TransporteBundle\Entity\TteGuia $guiasCiudadOrigenRel)
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
     * @param \Brasa\TransporteBundle\Entity\TteGuia $guiasCiudadDestinoRel
     *
     * @return GenCiudad
     */
    public function addGuiasCiudadDestinoRel(\Brasa\TransporteBundle\Entity\TteGuia $guiasCiudadDestinoRel)
    {
        $this->guiasCiudadDestinoRel[] = $guiasCiudadDestinoRel;

        return $this;
    }

    /**
     * Remove guiasCiudadDestinoRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuia $guiasCiudadDestinoRel
     */
    public function removeGuiasCiudadDestinoRel(\Brasa\TransporteBundle\Entity\TteGuia $guiasCiudadDestinoRel)
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
     * Add lpdCiudadDestinoRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteListaPrecioDetalle $lpdCiudadDestinoRel
     *
     * @return GenCiudad
     */
    public function addLpdCiudadDestinoRel(\Brasa\TransporteBundle\Entity\TteListaPrecioDetalle $lpdCiudadDestinoRel)
    {
        $this->lpdCiudadDestinoRel[] = $lpdCiudadDestinoRel;

        return $this;
    }

    /**
     * Remove lpdCiudadDestinoRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteListaPrecioDetalle $lpdCiudadDestinoRel
     */
    public function removeLpdCiudadDestinoRel(\Brasa\TransporteBundle\Entity\TteListaPrecioDetalle $lpdCiudadDestinoRel)
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

    /**
     * Add despachosCiudadOrigenRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteDespacho $despachosCiudadOrigenRel
     *
     * @return GenCiudad
     */
    public function addDespachosCiudadOrigenRel(\Brasa\TransporteBundle\Entity\TteDespacho $despachosCiudadOrigenRel)
    {
        $this->despachosCiudadOrigenRel[] = $despachosCiudadOrigenRel;

        return $this;
    }

    /**
     * Remove despachosCiudadOrigenRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteDespacho $despachosCiudadOrigenRel
     */
    public function removeDespachosCiudadOrigenRel(\Brasa\TransporteBundle\Entity\TteDespacho $despachosCiudadOrigenRel)
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
     * @param \Brasa\TransporteBundle\Entity\TteDespacho $despachosCiudadDestinoRel
     *
     * @return GenCiudad
     */
    public function addDespachosCiudadDestinoRel(\Brasa\TransporteBundle\Entity\TteDespacho $despachosCiudadDestinoRel)
    {
        $this->despachosCiudadDestinoRel[] = $despachosCiudadDestinoRel;

        return $this;
    }

    /**
     * Remove despachosCiudadDestinoRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteDespacho $despachosCiudadDestinoRel
     */
    public function removeDespachosCiudadDestinoRel(\Brasa\TransporteBundle\Entity\TteDespacho $despachosCiudadDestinoRel)
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
     * Add puntosOperacionCiudadOrigenRel
     *
     * @param \Brasa\TransporteBundle\Entity\TtePuntoOperacion $puntosOperacionCiudadOrigenRel
     *
     * @return GenCiudad
     */
    public function addPuntosOperacionCiudadOrigenRel(\Brasa\TransporteBundle\Entity\TtePuntoOperacion $puntosOperacionCiudadOrigenRel)
    {
        $this->puntosOperacionCiudadOrigenRel[] = $puntosOperacionCiudadOrigenRel;

        return $this;
    }

    /**
     * Remove puntosOperacionCiudadOrigenRel
     *
     * @param \Brasa\TransporteBundle\Entity\TtePuntoOperacion $puntosOperacionCiudadOrigenRel
     */
    public function removePuntosOperacionCiudadOrigenRel(\Brasa\TransporteBundle\Entity\TtePuntoOperacion $puntosOperacionCiudadOrigenRel)
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
     * Add rhuSeleccionesCiudadRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $rhuSeleccionesCiudadRel
     *
     * @return GenCiudad
     */
    public function addRhuSeleccionesCiudadRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $rhuSeleccionesCiudadRel)
    {
        $this->rhuSeleccionesCiudadRel[] = $rhuSeleccionesCiudadRel;

        return $this;
    }

    /**
     * Remove rhuSeleccionesCiudadRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $rhuSeleccionesCiudadRel
     */
    public function removeRhuSeleccionesCiudadRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $rhuSeleccionesCiudadRel)
    {
        $this->rhuSeleccionesCiudadRel->removeElement($rhuSeleccionesCiudadRel);
    }

    /**
     * Get rhuSeleccionesCiudadRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuSeleccionesCiudadRel()
    {
        return $this->rhuSeleccionesCiudadRel;
    }

    /**
     * Add rhuEmpleadosCiudadRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosCiudadRel
     *
     * @return GenCiudad
     */
    public function addRhuEmpleadosCiudadRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosCiudadRel)
    {
        $this->rhuEmpleadosCiudadRel[] = $rhuEmpleadosCiudadRel;

        return $this;
    }

    /**
     * Remove rhuEmpleadosCiudadRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosCiudadRel
     */
    public function removeRhuEmpleadosCiudadRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosCiudadRel)
    {
        $this->rhuEmpleadosCiudadRel->removeElement($rhuEmpleadosCiudadRel);
    }

    /**
     * Get rhuEmpleadosCiudadRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuEmpleadosCiudadRel()
    {
        return $this->rhuEmpleadosCiudadRel;
    }

    /**
     * Add barriosRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenBarrio $barriosRel
     *
     * @return GenCiudad
     */
    public function addBarriosRel(\Brasa\GeneralBundle\Entity\GenBarrio $barriosRel)
    {
        $this->barriosRel[] = $barriosRel;

        return $this;
    }

    /**
     * Remove barriosRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenBarrio $barriosRel
     */
    public function removeBarriosRel(\Brasa\GeneralBundle\Entity\GenBarrio $barriosRel)
    {
        $this->barriosRel->removeElement($barriosRel);
    }

    /**
     * Get barriosRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBarriosRel()
    {
        return $this->barriosRel;
    }

    /**
     * Add rhuCentroCostosCiudadRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $rhuCentroCostosCiudadRel
     *
     * @return GenCiudad
     */
    public function addRhuCentroCostosCiudadRel(\Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $rhuCentroCostosCiudadRel)
    {
        $this->rhuCentroCostosCiudadRel[] = $rhuCentroCostosCiudadRel;

        return $this;
    }

    /**
     * Remove rhuCentroCostosCiudadRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $rhuCentroCostosCiudadRel
     */
    public function removeRhuCentroCostosCiudadRel(\Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $rhuCentroCostosCiudadRel)
    {
        $this->rhuCentroCostosCiudadRel->removeElement($rhuCentroCostosCiudadRel);
    }

    /**
     * Get rhuCentroCostosCiudadRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuCentroCostosCiudadRel()
    {
        return $this->rhuCentroCostosCiudadRel;
    }

    /**
     * Add rhuSeleccionesCiudadNacimientoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $rhuSeleccionesCiudadNacimientoRel
     *
     * @return GenCiudad
     */
    public function addRhuSeleccionesCiudadNacimientoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $rhuSeleccionesCiudadNacimientoRel)
    {
        $this->rhuSeleccionesCiudadNacimientoRel[] = $rhuSeleccionesCiudadNacimientoRel;

        return $this;
    }

    /**
     * Remove rhuSeleccionesCiudadNacimientoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $rhuSeleccionesCiudadNacimientoRel
     */
    public function removeRhuSeleccionesCiudadNacimientoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $rhuSeleccionesCiudadNacimientoRel)
    {
        $this->rhuSeleccionesCiudadNacimientoRel->removeElement($rhuSeleccionesCiudadNacimientoRel);
    }

    /**
     * Get rhuSeleccionesCiudadNacimientoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuSeleccionesCiudadNacimientoRel()
    {
        return $this->rhuSeleccionesCiudadNacimientoRel;
    }

    /**
     * Add rhuEmpleadosCiudadNacimientoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosCiudadNacimientoRel
     *
     * @return GenCiudad
     */
    public function addRhuEmpleadosCiudadNacimientoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosCiudadNacimientoRel)
    {
        $this->rhuEmpleadosCiudadNacimientoRel[] = $rhuEmpleadosCiudadNacimientoRel;

        return $this;
    }

    /**
     * Remove rhuEmpleadosCiudadNacimientoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosCiudadNacimientoRel
     */
    public function removeRhuEmpleadosCiudadNacimientoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosCiudadNacimientoRel)
    {
        $this->rhuEmpleadosCiudadNacimientoRel->removeElement($rhuEmpleadosCiudadNacimientoRel);
    }

    /**
     * Get rhuEmpleadosCiudadNacimientoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuEmpleadosCiudadNacimientoRel()
    {
        return $this->rhuEmpleadosCiudadNacimientoRel;
    }

    /**
     * Add rhuEmpleadosCiudadExpedicionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosCiudadExpedicionRel
     *
     * @return GenCiudad
     */
    public function addRhuEmpleadosCiudadExpedicionRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosCiudadExpedicionRel)
    {
        $this->rhuEmpleadosCiudadExpedicionRel[] = $rhuEmpleadosCiudadExpedicionRel;

        return $this;
    }

    /**
     * Remove rhuEmpleadosCiudadExpedicionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosCiudadExpedicionRel
     */
    public function removeRhuEmpleadosCiudadExpedicionRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosCiudadExpedicionRel)
    {
        $this->rhuEmpleadosCiudadExpedicionRel->removeElement($rhuEmpleadosCiudadExpedicionRel);
    }

    /**
     * Get rhuEmpleadosCiudadExpedicionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuEmpleadosCiudadExpedicionRel()
    {
        return $this->rhuEmpleadosCiudadExpedicionRel;
    }

    /**
     * Add rhuSeleccionesCiudadExpedicionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $rhuSeleccionesCiudadExpedicionRel
     *
     * @return GenCiudad
     */
    public function addRhuSeleccionesCiudadExpedicionRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $rhuSeleccionesCiudadExpedicionRel)
    {
        $this->rhuSeleccionesCiudadExpedicionRel[] = $rhuSeleccionesCiudadExpedicionRel;

        return $this;
    }

    /**
     * Remove rhuSeleccionesCiudadExpedicionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $rhuSeleccionesCiudadExpedicionRel
     */
    public function removeRhuSeleccionesCiudadExpedicionRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $rhuSeleccionesCiudadExpedicionRel)
    {
        $this->rhuSeleccionesCiudadExpedicionRel->removeElement($rhuSeleccionesCiudadExpedicionRel);
    }

    /**
     * Get rhuSeleccionesCiudadExpedicionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuSeleccionesCiudadExpedicionRel()
    {
        return $this->rhuSeleccionesCiudadExpedicionRel;
    }

    /**
     * Set codigoInterface
     *
     * @param string $codigoInterface
     *
     * @return GenCiudad
     */
    public function setCodigoInterface($codigoInterface)
    {
        $this->codigoInterface = $codigoInterface;

        return $this;
    }

    /**
     * Get codigoInterface
     *
     * @return string
     */
    public function getCodigoInterface()
    {
        return $this->codigoInterface;
    }

    /**
     * Add rhuEmpleadoEstudioCiudadSecundariaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $rhuEmpleadoEstudioCiudadSecundariaRel
     *
     * @return GenCiudad
     */
    public function addRhuEmpleadoEstudioCiudadSecundariaRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $rhuEmpleadoEstudioCiudadSecundariaRel)
    {
        $this->rhuEmpleadoEstudioCiudadSecundariaRel[] = $rhuEmpleadoEstudioCiudadSecundariaRel;

        return $this;
    }

    /**
     * Remove rhuEmpleadoEstudioCiudadSecundariaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $rhuEmpleadoEstudioCiudadSecundariaRel
     */
    public function removeRhuEmpleadoEstudioCiudadSecundariaRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $rhuEmpleadoEstudioCiudadSecundariaRel)
    {
        $this->rhuEmpleadoEstudioCiudadSecundariaRel->removeElement($rhuEmpleadoEstudioCiudadSecundariaRel);
    }

    /**
     * Get rhuEmpleadoEstudioCiudadSecundariaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuEmpleadoEstudioCiudadSecundariaRel()
    {
        return $this->rhuEmpleadoEstudioCiudadSecundariaRel;
    }

    /**
     * Add rhuEmpleadoEstudioCiudadTecnicaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $rhuEmpleadoEstudioCiudadTecnicaRel
     *
     * @return GenCiudad
     */
    public function addRhuEmpleadoEstudioCiudadTecnicaRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $rhuEmpleadoEstudioCiudadTecnicaRel)
    {
        $this->rhuEmpleadoEstudioCiudadTecnicaRel[] = $rhuEmpleadoEstudioCiudadTecnicaRel;

        return $this;
    }

    /**
     * Remove rhuEmpleadoEstudioCiudadTecnicaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $rhuEmpleadoEstudioCiudadTecnicaRel
     */
    public function removeRhuEmpleadoEstudioCiudadTecnicaRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $rhuEmpleadoEstudioCiudadTecnicaRel)
    {
        $this->rhuEmpleadoEstudioCiudadTecnicaRel->removeElement($rhuEmpleadoEstudioCiudadTecnicaRel);
    }

    /**
     * Get rhuEmpleadoEstudioCiudadTecnicaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuEmpleadoEstudioCiudadTecnicaRel()
    {
        return $this->rhuEmpleadoEstudioCiudadTecnicaRel;
    }

    /**
     * Add rhuEmpleadoEstudioCiudadTecnologiaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $rhuEmpleadoEstudioCiudadTecnologiaRel
     *
     * @return GenCiudad
     */
    public function addRhuEmpleadoEstudioCiudadTecnologiaRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $rhuEmpleadoEstudioCiudadTecnologiaRel)
    {
        $this->rhuEmpleadoEstudioCiudadTecnologiaRel[] = $rhuEmpleadoEstudioCiudadTecnologiaRel;

        return $this;
    }

    /**
     * Remove rhuEmpleadoEstudioCiudadTecnologiaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $rhuEmpleadoEstudioCiudadTecnologiaRel
     */
    public function removeRhuEmpleadoEstudioCiudadTecnologiaRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $rhuEmpleadoEstudioCiudadTecnologiaRel)
    {
        $this->rhuEmpleadoEstudioCiudadTecnologiaRel->removeElement($rhuEmpleadoEstudioCiudadTecnologiaRel);
    }

    /**
     * Get rhuEmpleadoEstudioCiudadTecnologiaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuEmpleadoEstudioCiudadTecnologiaRel()
    {
        return $this->rhuEmpleadoEstudioCiudadTecnologiaRel;
    }

    /**
     * Add rhuEmpleadoEstudioCiudadUniversitarioRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $rhuEmpleadoEstudioCiudadUniversitarioRel
     *
     * @return GenCiudad
     */
    public function addRhuEmpleadoEstudioCiudadUniversitarioRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $rhuEmpleadoEstudioCiudadUniversitarioRel)
    {
        $this->rhuEmpleadoEstudioCiudadUniversitarioRel[] = $rhuEmpleadoEstudioCiudadUniversitarioRel;

        return $this;
    }

    /**
     * Remove rhuEmpleadoEstudioCiudadUniversitarioRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $rhuEmpleadoEstudioCiudadUniversitarioRel
     */
    public function removeRhuEmpleadoEstudioCiudadUniversitarioRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $rhuEmpleadoEstudioCiudadUniversitarioRel)
    {
        $this->rhuEmpleadoEstudioCiudadUniversitarioRel->removeElement($rhuEmpleadoEstudioCiudadUniversitarioRel);
    }

    /**
     * Get rhuEmpleadoEstudioCiudadUniversitarioRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuEmpleadoEstudioCiudadUniversitarioRel()
    {
        return $this->rhuEmpleadoEstudioCiudadUniversitarioRel;
    }

    /**
     * Add rhuEmpleadoEstudioCiudadOtro1Rel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $rhuEmpleadoEstudioCiudadOtro1Rel
     *
     * @return GenCiudad
     */
    public function addRhuEmpleadoEstudioCiudadOtro1Rel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $rhuEmpleadoEstudioCiudadOtro1Rel)
    {
        $this->rhuEmpleadoEstudioCiudadOtro1Rel[] = $rhuEmpleadoEstudioCiudadOtro1Rel;

        return $this;
    }

    /**
     * Remove rhuEmpleadoEstudioCiudadOtro1Rel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $rhuEmpleadoEstudioCiudadOtro1Rel
     */
    public function removeRhuEmpleadoEstudioCiudadOtro1Rel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $rhuEmpleadoEstudioCiudadOtro1Rel)
    {
        $this->rhuEmpleadoEstudioCiudadOtro1Rel->removeElement($rhuEmpleadoEstudioCiudadOtro1Rel);
    }

    /**
     * Get rhuEmpleadoEstudioCiudadOtro1Rel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuEmpleadoEstudioCiudadOtro1Rel()
    {
        return $this->rhuEmpleadoEstudioCiudadOtro1Rel;
    }

    /**
     * Add rhuEmpleadoEstudioCiudadOtro2Rel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $rhuEmpleadoEstudioCiudadOtro2Rel
     *
     * @return GenCiudad
     */
    public function addRhuEmpleadoEstudioCiudadOtro2Rel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $rhuEmpleadoEstudioCiudadOtro2Rel)
    {
        $this->rhuEmpleadoEstudioCiudadOtro2Rel[] = $rhuEmpleadoEstudioCiudadOtro2Rel;

        return $this;
    }

    /**
     * Remove rhuEmpleadoEstudioCiudadOtro2Rel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $rhuEmpleadoEstudioCiudadOtro2Rel
     */
    public function removeRhuEmpleadoEstudioCiudadOtro2Rel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio $rhuEmpleadoEstudioCiudadOtro2Rel)
    {
        $this->rhuEmpleadoEstudioCiudadOtro2Rel->removeElement($rhuEmpleadoEstudioCiudadOtro2Rel);
    }

    /**
     * Get rhuEmpleadoEstudioCiudadOtro2Rel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuEmpleadoEstudioCiudadOtro2Rel()
    {
        return $this->rhuEmpleadoEstudioCiudadOtro2Rel;
    }
}
