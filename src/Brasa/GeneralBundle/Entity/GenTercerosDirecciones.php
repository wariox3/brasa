<?php

namespace Brasa\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_terceros_direcciones")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenTercerosDireccionesRepository")
 */
class GenTercerosDirecciones
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_direccion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDireccionPk;
    
    /**
     * @ORM\Column(name="nombre_direccion", type="string", length=255)
     */
    private $nombreDireccion;

    /**
     * @ORM\Column(name="direccion", type="string", length=255)
     */
    private $direccion;

    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer")
     */
    private $codigoTerceroFk;

    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer")
     */
    private $codigoCiudadFk;    

    /**
     * @ORM\Column(name="barrio", type="string", length=100)
     */
    private $barrio;    
    
    /**
     * @ORM\Column(name="contacto", type="string", length=255)
     */
    private $contacto;

    /**
     * @ORM\Column(name="telefono", type="string", length=50)
     */
    private $telefono;

    /**
     * @ORM\Column(name="email", type="string", length=100)
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="GenTerceros", inversedBy="tercerosDireccionesRel")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;     

    /**
     * @ORM\ManyToOne(targetEntity="GenCiudades", inversedBy="tercerosDireccionesRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\InventarioBundle\Entity\InvMovimientos", mappedBy="direccionRel")
     */
    protected $movimientosRel;    
    
    public function __construct()
    {        
        $this->movimientosRel = new ArrayCollection();
    }      




    /**
     * Get codigoDireccionPk
     *
     * @return integer 
     */
    public function getCodigoDireccionPk()
    {
        return $this->codigoDireccionPk;
    }

    /**
     * Set nombreDireccion
     *
     * @param string $nombreDireccion
     * @return GenTercerosDirecciones
     */
    public function setNombreDireccion($nombreDireccion)
    {
        $this->nombreDireccion = $nombreDireccion;

        return $this;
    }

    /**
     * Get nombreDireccion
     *
     * @return string 
     */
    public function getNombreDireccion()
    {
        return $this->nombreDireccion;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     * @return GenTercerosDirecciones
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set codigoTerceroFk
     *
     * @param integer $codigoTerceroFk
     * @return GenTercerosDirecciones
     */
    public function setCodigoTerceroFk($codigoTerceroFk)
    {
        $this->codigoTerceroFk = $codigoTerceroFk;

        return $this;
    }

    /**
     * Get codigoTerceroFk
     *
     * @return integer 
     */
    public function getCodigoTerceroFk()
    {
        return $this->codigoTerceroFk;
    }

    /**
     * Set codigoCiudadFk
     *
     * @param integer $codigoCiudadFk
     * @return GenTercerosDirecciones
     */
    public function setCodigoCiudadFk($codigoCiudadFk)
    {
        $this->codigoCiudadFk = $codigoCiudadFk;

        return $this;
    }

    /**
     * Get codigoCiudadFk
     *
     * @return integer 
     */
    public function getCodigoCiudadFk()
    {
        return $this->codigoCiudadFk;
    }

    /**
     * Set barrio
     *
     * @param string $barrio
     * @return GenTercerosDirecciones
     */
    public function setBarrio($barrio)
    {
        $this->barrio = $barrio;

        return $this;
    }

    /**
     * Get barrio
     *
     * @return string 
     */
    public function getBarrio()
    {
        return $this->barrio;
    }

    /**
     * Set contacto
     *
     * @param string $contacto
     * @return GenTercerosDirecciones
     */
    public function setContacto($contacto)
    {
        $this->contacto = $contacto;

        return $this;
    }

    /**
     * Get contacto
     *
     * @return string 
     */
    public function getContacto()
    {
        return $this->contacto;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return GenTercerosDirecciones
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return GenTercerosDirecciones
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set terceroRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTerceros $terceroRel
     * @return GenTercerosDirecciones
     */
    public function setTerceroRel(\Brasa\GeneralBundle\Entity\GenTerceros $terceroRel = null)
    {
        $this->terceroRel = $terceroRel;

        return $this;
    }

    /**
     * Get terceroRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenTerceros 
     */
    public function getTerceroRel()
    {
        return $this->terceroRel;
    }

    /**
     * Set ciudadRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudades $ciudadRel
     * @return GenTercerosDirecciones
     */
    public function setCiudadRel(\Brasa\GeneralBundle\Entity\GenCiudades $ciudadRel = null)
    {
        $this->ciudadRel = $ciudadRel;

        return $this;
    }

    /**
     * Get ciudadRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudades 
     */
    public function getCiudadRel()
    {
        return $this->ciudadRel;
    }

    /**
     * Add movimientosRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimientos $movimientosRel
     * @return GenTercerosDirecciones
     */
    public function addMovimientosRel(\Brasa\InventarioBundle\Entity\InvMovimientos $movimientosRel)
    {
        $this->movimientosRel[] = $movimientosRel;

        return $this;
    }

    /**
     * Remove movimientosRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimientos $movimientosRel
     */
    public function removeMovimientosRel(\Brasa\InventarioBundle\Entity\InvMovimientos $movimientosRel)
    {
        $this->movimientosRel->removeElement($movimientosRel);
    }

    /**
     * Get movimientosRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMovimientosRel()
    {
        return $this->movimientosRel;
    }
}
