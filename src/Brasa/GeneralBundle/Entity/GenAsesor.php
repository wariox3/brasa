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
     * @ORM\OneToMany(targetEntity="Brasa\CarteraBundle\Entity\CarCliente", mappedBy="asesorRel")
     */
    protected $carClientesAsesorRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\CarteraBundle\Entity\CarCuentaCobrar", mappedBy="asesorRel")
     */
    protected $carCuentasCobrarAsesorRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\CarteraBundle\Entity\CarRecibo", mappedBy="asesorRel")
     */
    protected $carRecibosAsesorRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\AfiliacionBundle\Entity\AfiCliente", mappedBy="asesorRel")
     */
    protected $afiClientesAsesorRel;     
    
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

    /**
     * Add afiClientesAsesorRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCliente $afiClientesAsesorRel
     *
     * @return GenAsesor
     */
    public function addAfiClientesAsesorRel(\Brasa\AfiliacionBundle\Entity\AfiCliente $afiClientesAsesorRel)
    {
        $this->afiClientesAsesorRel[] = $afiClientesAsesorRel;

        return $this;
    }

    /**
     * Remove afiClientesAsesorRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCliente $afiClientesAsesorRel
     */
    public function removeAfiClientesAsesorRel(\Brasa\AfiliacionBundle\Entity\AfiCliente $afiClientesAsesorRel)
    {
        $this->afiClientesAsesorRel->removeElement($afiClientesAsesorRel);
    }

    /**
     * Get afiClientesAsesorRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAfiClientesAsesorRel()
    {
        return $this->afiClientesAsesorRel;
    }

    /**
     * Add carAsesorRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCliente $carAsesorRel
     *
     * @return GenAsesor
     */
    public function addCarAsesorRel(\Brasa\CarteraBundle\Entity\CarCliente $carAsesorRel)
    {
        $this->carAsesorRel[] = $carAsesorRel;

        return $this;
    }

    /**
     * Remove carAsesorRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCliente $carAsesorRel
     */
    public function removeCarAsesorRel(\Brasa\CarteraBundle\Entity\CarCliente $carAsesorRel)
    {
        $this->carAsesorRel->removeElement($carAsesorRel);
    }

    /**
     * Get carAsesorRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCarAsesorRel()
    {
        return $this->carAsesorRel;
    }

    /**
     * Add carClientesAsesorRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCliente $carClientesAsesorRel
     *
     * @return GenAsesor
     */
    public function addCarClientesAsesorRel(\Brasa\CarteraBundle\Entity\CarCliente $carClientesAsesorRel)
    {
        $this->carClientesAsesorRel[] = $carClientesAsesorRel;

        return $this;
    }

    /**
     * Remove carClientesAsesorRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCliente $carClientesAsesorRel
     */
    public function removeCarClientesAsesorRel(\Brasa\CarteraBundle\Entity\CarCliente $carClientesAsesorRel)
    {
        $this->carClientesAsesorRel->removeElement($carClientesAsesorRel);
    }

    /**
     * Get carClientesAsesorRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCarClientesAsesorRel()
    {
        return $this->carClientesAsesorRel;
    }

    /**
     * Add carCuentasCobrarAsesorRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCuentaCobrar $carCuentasCobrarAsesorRel
     *
     * @return GenAsesor
     */
    public function addCarCuentasCobrarAsesorRel(\Brasa\CarteraBundle\Entity\CarCuentaCobrar $carCuentasCobrarAsesorRel)
    {
        $this->carCuentasCobrarAsesorRel[] = $carCuentasCobrarAsesorRel;

        return $this;
    }

    /**
     * Remove carCuentasCobrarAsesorRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCuentaCobrar $carCuentasCobrarAsesorRel
     */
    public function removeCarCuentasCobrarAsesorRel(\Brasa\CarteraBundle\Entity\CarCuentaCobrar $carCuentasCobrarAsesorRel)
    {
        $this->carCuentasCobrarAsesorRel->removeElement($carCuentasCobrarAsesorRel);
    }

    /**
     * Get carCuentasCobrarAsesorRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCarCuentasCobrarAsesorRel()
    {
        return $this->carCuentasCobrarAsesorRel;
    }

    /**
     * Add carRecibosAsesorRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarRecibo $carRecibosAsesorRel
     *
     * @return GenAsesor
     */
    public function addCarRecibosAsesorRel(\Brasa\CarteraBundle\Entity\CarRecibo $carRecibosAsesorRel)
    {
        $this->carRecibosAsesorRel[] = $carRecibosAsesorRel;

        return $this;
    }

    /**
     * Remove carRecibosAsesorRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarRecibo $carRecibosAsesorRel
     */
    public function removeCarRecibosAsesorRel(\Brasa\CarteraBundle\Entity\CarRecibo $carRecibosAsesorRel)
    {
        $this->carRecibosAsesorRel->removeElement($carRecibosAsesorRel);
    }

    /**
     * Get carRecibosAsesorRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCarRecibosAsesorRel()
    {
        return $this->carRecibosAsesorRel;
    }
}
