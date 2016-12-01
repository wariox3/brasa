<?php

namespace Brasa\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_tercero_direccion")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenTerceroDireccionRepository")
 */
class GenTerceroDireccion
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
     *
     * @return GenTerceroDireccion
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
     *
     * @return GenTerceroDireccion
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
     *
     * @return GenTerceroDireccion
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
     *
     * @return GenTerceroDireccion
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
     *
     * @return GenTerceroDireccion
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
     *
     * @return GenTerceroDireccion
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
     *
     * @return GenTerceroDireccion
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
     *
     * @return GenTerceroDireccion
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
}
