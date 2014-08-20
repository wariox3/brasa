<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="inv_descuentos_financieros")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvDescuentosFinancierosRepository")
 */
class InvDescuentosFinancieros
{
    /** 
     * @ORM\Id
     * @ORM\Column(name="codigo_descuento_financiero_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */    
    private $codigoDescuentoFinancieroPk;
        
    /**
     * @ORM\Column(name="nombre", type="string", length=40, nullable=true)
     */      
    private $nombre; 

    /**
     * @ORM\Column(name="porcenaje", type="float")
     */    
    private $porcentaje = 0;    
    
    /**
     * @ORM\Column(name="referencia", type="string", length=40, nullable=true)
     */      
    private $referencia;     
    
    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=20)
     */     
    private $codigoCuentaFk; 
    
    /**
     * @ORM\Column(name="tipo_registro", type="smallint")
     */    
    private $tipoRegistro = 1;     
                 
    
    /**
     * @ORM\OneToMany(targetEntity="InvMovimientosDescuentosFinancieros", mappedBy="descuentoFinancieroRel")
     */
    protected $descuentosFinancierosRel;    
    
    public function __construct()
    {
        $this->descuentosFinancieros = new ArrayCollection();        
    }     
   


    /**
     * Get codigoDescuentoFinancieroPk
     *
     * @return integer 
     */
    public function getCodigoDescuentoFinancieroPk()
    {
        return $this->codigoDescuentoFinancieroPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return InvDescuentosFinancieros
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
     * Set porcentaje
     *
     * @param float $porcentaje
     * @return InvDescuentosFinancieros
     */
    public function setPorcentaje($porcentaje)
    {
        $this->porcentaje = $porcentaje;

        return $this;
    }

    /**
     * Get porcentaje
     *
     * @return float 
     */
    public function getPorcentaje()
    {
        return $this->porcentaje;
    }

    /**
     * Set referencia
     *
     * @param string $referencia
     * @return InvDescuentosFinancieros
     */
    public function setReferencia($referencia)
    {
        $this->referencia = $referencia;

        return $this;
    }

    /**
     * Get referencia
     *
     * @return string 
     */
    public function getReferencia()
    {
        return $this->referencia;
    }

    /**
     * Set codigoCuentaFk
     *
     * @param string $codigoCuentaFk
     * @return InvDescuentosFinancieros
     */
    public function setCodigoCuentaFk($codigoCuentaFk)
    {
        $this->codigoCuentaFk = $codigoCuentaFk;

        return $this;
    }

    /**
     * Get codigoCuentaFk
     *
     * @return string 
     */
    public function getCodigoCuentaFk()
    {
        return $this->codigoCuentaFk;
    }

    /**
     * Set tipoRegistro
     *
     * @param integer $tipoRegistro
     * @return InvDescuentosFinancieros
     */
    public function setTipoRegistro($tipoRegistro)
    {
        $this->tipoRegistro = $tipoRegistro;

        return $this;
    }

    /**
     * Get tipoRegistro
     *
     * @return integer 
     */
    public function getTipoRegistro()
    {
        return $this->tipoRegistro;
    }

    /**
     * Add descuentosFinancierosRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimientosDescuentosFinancieros $descuentosFinancierosRel
     * @return InvDescuentosFinancieros
     */
    public function addDescuentosFinancierosRel(\Brasa\InventarioBundle\Entity\InvMovimientosDescuentosFinancieros $descuentosFinancierosRel)
    {
        $this->descuentosFinancierosRel[] = $descuentosFinancierosRel;

        return $this;
    }

    /**
     * Remove descuentosFinancierosRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimientosDescuentosFinancieros $descuentosFinancierosRel
     */
    public function removeDescuentosFinancierosRel(\Brasa\InventarioBundle\Entity\InvMovimientosDescuentosFinancieros $descuentosFinancierosRel)
    {
        $this->descuentosFinancierosRel->removeElement($descuentosFinancierosRel);
    }

    /**
     * Get descuentosFinancierosRel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDescuentosFinancierosRel()
    {
        return $this->descuentosFinancierosRel;
    }
}
