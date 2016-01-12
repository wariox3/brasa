<?php

namespace Brasa\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_asesor")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenAsesorRepository")
 */
class GenAsesor
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_asesor_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */ 
    private $codigoAsesorPk;
    
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */      
    private $nombre;

    /**
     * @ORM\Column(name="direccion", type="string", length=80, nullable=true)
     */
    private $direccion;    

    /**
     * @ORM\Column(name="telefono", type="string", length=20, nullable=true)
     */
    private $telefono;    

    /**
     * @ORM\Column(name="celular", type="string", length=20, nullable=true)
     */
    private $celular;        
    
    /**
     * @ORM\Column(name="email", type="string", length=80, nullable=true)
     */
    private $email;
    
    /**
     * @ORM\OneToMany(targetEntity="GenTercero", mappedBy="asesorRel")
     */
    protected $tercerosRel;

    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tercerosRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoAsesorPk
     *
     * @return integer
     */
    public function getCodigoAsesorPk()
    {
        return $this->codigoAsesorPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return GenAsesor
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
     * Set direccion
     *
     * @param string $direccion
     *
     * @return GenAsesor
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
     * Set telefono
     *
     * @param string $telefono
     *
     * @return GenAsesor
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
     * Set celular
     *
     * @param string $celular
     *
     * @return GenAsesor
     */
    public function setCelular($celular)
    {
        $this->celular = $celular;

        return $this;
    }

    /**
     * Get celular
     *
     * @return string
     */
    public function getCelular()
    {
        return $this->celular;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return GenAsesor
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
     * Add tercerosRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTercero $tercerosRel
     *
     * @return GenAsesor
     */
    public function addTercerosRel(\Brasa\GeneralBundle\Entity\GenTercero $tercerosRel)
    {
        $this->tercerosRel[] = $tercerosRel;

        return $this;
    }

    /**
     * Remove tercerosRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTercero $tercerosRel
     */
    public function removeTercerosRel(\Brasa\GeneralBundle\Entity\GenTercero $tercerosRel)
    {
        $this->tercerosRel->removeElement($tercerosRel);
    }

    /**
     * Get tercerosRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTercerosRel()
    {
        return $this->tercerosRel;
    }
}
