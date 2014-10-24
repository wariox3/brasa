<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_negociaciones")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteNegociacionesRepository")
 */
class TteNegociaciones
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_negociacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoNegociacionPk;  
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;
    
    /**
     * @ORM\Column(name="codigo_lista_precios_fk", type="integer", nullable=true)
     */    
    private $codigoListaPreciosFk;
    
    /**
     * @ORM\Column(name="liquidar_automaticamente_flete", type="boolean")
     */    
    private $liquidarAutomaticamenteFlete = 0;     

    /**
     * @ORM\Column(name="liquidar_automaticamente_manejo", type="boolean")
     */    
    private $liquidarAutomaticamenteManejo = 0;         
    
    /**
     * @ORM\Column(name="porcentaje_manejo", type="float")
     */
    private $porcentajeManejo = 0;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TteListasPrecios", inversedBy="negociacionesRel")
     * @ORM\JoinColumn(name="codigo_lista_precios_fk", referencedColumnName="codigo_lista_precios_pk")
     */
    protected $listaPreciosRel;     
    


    /**
     * Get codigoNegociacionPk
     *
     * @return integer 
     */
    public function getCodigoNegociacionPk()
    {
        return $this->codigoNegociacionPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return TteNegociaciones
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
     * Set codigoListaPreciosFk
     *
     * @param integer $codigoListaPreciosFk
     * @return TteNegociaciones
     */
    public function setCodigoListaPreciosFk($codigoListaPreciosFk)
    {
        $this->codigoListaPreciosFk = $codigoListaPreciosFk;

        return $this;
    }

    /**
     * Get codigoListaPreciosFk
     *
     * @return integer 
     */
    public function getCodigoListaPreciosFk()
    {
        return $this->codigoListaPreciosFk;
    }

    /**
     * Set liquidarAutomaticamenteFlete
     *
     * @param boolean $liquidarAutomaticamenteFlete
     * @return TteNegociaciones
     */
    public function setLiquidarAutomaticamenteFlete($liquidarAutomaticamenteFlete)
    {
        $this->liquidarAutomaticamenteFlete = $liquidarAutomaticamenteFlete;

        return $this;
    }

    /**
     * Get liquidarAutomaticamenteFlete
     *
     * @return boolean 
     */
    public function getLiquidarAutomaticamenteFlete()
    {
        return $this->liquidarAutomaticamenteFlete;
    }

    /**
     * Set porcentajeManejo
     *
     * @param float $porcentajeManejo
     * @return TteNegociaciones
     */
    public function setPorcentajeManejo($porcentajeManejo)
    {
        $this->porcentajeManejo = $porcentajeManejo;

        return $this;
    }

    /**
     * Get porcentajeManejo
     *
     * @return float 
     */
    public function getPorcentajeManejo()
    {
        return $this->porcentajeManejo;
    }

    /**
     * Set listaPreciosRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteListasPrecios $listaPreciosRel
     * @return TteNegociaciones
     */
    public function setListaPreciosRel(\Brasa\TransporteBundle\Entity\TteListasPrecios $listaPreciosRel = null)
    {
        $this->listaPreciosRel = $listaPreciosRel;

        return $this;
    }

    /**
     * Get listaPreciosRel
     *
     * @return \Brasa\TransporteBundle\Entity\TteListasPrecios 
     */
    public function getListaPreciosRel()
    {
        return $this->listaPreciosRel;
    }

    /**
     * Set liquidarAutomaticamenteManejo
     *
     * @param boolean $liquidarAutomaticamenteManejo
     * @return TteNegociaciones
     */
    public function setLiquidarAutomaticamenteManejo($liquidarAutomaticamenteManejo)
    {
        $this->liquidarAutomaticamenteManejo = $liquidarAutomaticamenteManejo;

        return $this;
    }

    /**
     * Get liquidarAutomaticamenteManejo
     *
     * @return boolean 
     */
    public function getLiquidarAutomaticamenteManejo()
    {
        return $this->liquidarAutomaticamenteManejo;
    }
}
