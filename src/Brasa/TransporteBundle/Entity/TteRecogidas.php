<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_recogidas")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteRecogidasRepository")
 */
class TteRecogidas
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_recogida_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRecogidaPk;      
    
    /**
     * @ORM\Column(name="fecha_anuncio", type="datetime", nullable=true)
     */    
    private $fechaAnuncio;            

    /**
     * @ORM\Column(name="fecha_recogida", type="datetime", nullable=true)
     */    
    private $fechaRecogida;     
    
    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     */    
    private $codigoTerceroFk;          
    
    /**
     * @ORM\Column(name="codigo_punto_operacion_fk", type="integer", nullable=true)
     */    
    private $codigoPuntoOperacionFk;     
       
    /**
     * @ORM\Column(name="ct_unidades", type="integer")
     */
    private $ctUnidades = 0;

    /**
     * @ORM\Column(name="ct_peso_real", type="integer")
     */
    private $ctPesoReal = 0;    

    /**
     * @ORM\Column(name="ct_peso_volumen", type="integer")
     */
    private $ctPesoVolumen = 0;    
    
    /**
     * @ORM\Column(name="ct_peso_liquidar", type="integer")
     */
    private $ctPesoLiquidar = 0;    

    /**
     * @ORM\Column(name="vr_declarado", type="float")
     */
    private $vrDeclarado = 0;               
    
    /**
     * @ORM\Column(name="estado_impreso", type="boolean")
     */    
    private $estadoImpresa = 0;             

    /**
     * @ORM\Column(name="estado_anulada", type="boolean")
     */    
    private $estadoAnulada = 0;                

    /**
     * @ORM\Column(name="Anunciante", type="string", length=80, nullable=true)
     */    
    private $anunciante;     
    
    /**
     * @ORM\Column(name="direccion", type="string", length=80, nullable=true)
     */    
    private $direccion;    
    
    /**
     * @ORM\Column(name="telefono", type="string", length=25, nullable=true)
     */    
    private $telefono;    
    
    /**
     * @ORM\Column(name="contenido", type="string", length=500, nullable=true)
     */    
    private $contenido;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=500, nullable=true)
     */    
    private $comentarios;    
        
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenTerceros", inversedBy="recogidasRel")
     * @ORM\JoinColumn(name="codigo_tercero_fk", referencedColumnName="codigo_tercero_pk")
     */
    protected $terceroRel;      
    
    /**
     * @ORM\ManyToOne(targetEntity="TtePuntosOperacion", inversedBy="recogidasPuntoOperacionRel")
     * @ORM\JoinColumn(name="codigo_punto_operacion_fk", referencedColumnName="codigo_punto_operacion_pk")
     */
    protected $puntoOperacionRel;     
     

    /**
     * Get codigoRecogidaPk
     *
     * @return integer 
     */
    public function getCodigoRecogidaPk()
    {
        return $this->codigoRecogidaPk;
    }

    /**
     * Set fechaAnuncio
     *
     * @param \DateTime $fechaAnuncio
     * @return TteRecogidas
     */
    public function setFechaAnuncio($fechaAnuncio)
    {
        $this->fechaAnuncio = $fechaAnuncio;

        return $this;
    }

    /**
     * Get fechaAnuncio
     *
     * @return \DateTime 
     */
    public function getFechaAnuncio()
    {
        return $this->fechaAnuncio;
    }

    /**
     * Set codigoTerceroFk
     *
     * @param integer $codigoTerceroFk
     * @return TteRecogidas
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
     * Set codigoPuntoOperacionFk
     *
     * @param integer $codigoPuntoOperacionFk
     * @return TteRecogidas
     */
    public function setCodigoPuntoOperacionFk($codigoPuntoOperacionFk)
    {
        $this->codigoPuntoOperacionFk = $codigoPuntoOperacionFk;

        return $this;
    }

    /**
     * Get codigoPuntoOperacionFk
     *
     * @return integer 
     */
    public function getCodigoPuntoOperacionFk()
    {
        return $this->codigoPuntoOperacionFk;
    }

    /**
     * Set ctUnidades
     *
     * @param integer $ctUnidades
     * @return TteRecogidas
     */
    public function setCtUnidades($ctUnidades)
    {
        $this->ctUnidades = $ctUnidades;

        return $this;
    }

    /**
     * Get ctUnidades
     *
     * @return integer 
     */
    public function getCtUnidades()
    {
        return $this->ctUnidades;
    }

    /**
     * Set ctPesoReal
     *
     * @param integer $ctPesoReal
     * @return TteRecogidas
     */
    public function setCtPesoReal($ctPesoReal)
    {
        $this->ctPesoReal = $ctPesoReal;

        return $this;
    }

    /**
     * Get ctPesoReal
     *
     * @return integer 
     */
    public function getCtPesoReal()
    {
        return $this->ctPesoReal;
    }

    /**
     * Set ctPesoVolumen
     *
     * @param integer $ctPesoVolumen
     * @return TteRecogidas
     */
    public function setCtPesoVolumen($ctPesoVolumen)
    {
        $this->ctPesoVolumen = $ctPesoVolumen;

        return $this;
    }

    /**
     * Get ctPesoVolumen
     *
     * @return integer 
     */
    public function getCtPesoVolumen()
    {
        return $this->ctPesoVolumen;
    }

    /**
     * Set ctPesoLiquidar
     *
     * @param integer $ctPesoLiquidar
     * @return TteRecogidas
     */
    public function setCtPesoLiquidar($ctPesoLiquidar)
    {
        $this->ctPesoLiquidar = $ctPesoLiquidar;

        return $this;
    }

    /**
     * Get ctPesoLiquidar
     *
     * @return integer 
     */
    public function getCtPesoLiquidar()
    {
        return $this->ctPesoLiquidar;
    }

    /**
     * Set vrDeclarado
     *
     * @param float $vrDeclarado
     * @return TteRecogidas
     */
    public function setVrDeclarado($vrDeclarado)
    {
        $this->vrDeclarado = $vrDeclarado;

        return $this;
    }

    /**
     * Get vrDeclarado
     *
     * @return float 
     */
    public function getVrDeclarado()
    {
        return $this->vrDeclarado;
    }

    /**
     * Set estadoImpresa
     *
     * @param boolean $estadoImpresa
     * @return TteRecogidas
     */
    public function setEstadoImpresa($estadoImpresa)
    {
        $this->estadoImpresa = $estadoImpresa;

        return $this;
    }

    /**
     * Get estadoImpresa
     *
     * @return boolean 
     */
    public function getEstadoImpresa()
    {
        return $this->estadoImpresa;
    }

    /**
     * Set estadoAnulada
     *
     * @param boolean $estadoAnulada
     * @return TteRecogidas
     */
    public function setEstadoAnulada($estadoAnulada)
    {
        $this->estadoAnulada = $estadoAnulada;

        return $this;
    }

    /**
     * Get estadoAnulada
     *
     * @return boolean 
     */
    public function getEstadoAnulada()
    {
        return $this->estadoAnulada;
    }

    /**
     * Set fechaRecogida
     *
     * @param \DateTime $fechaRecogida
     * @return TteRecogidas
     */
    public function setFechaRecogida($fechaRecogida)
    {
        $this->fechaRecogida = $fechaRecogida;

        return $this;
    }

    /**
     * Get fechaRecogida
     *
     * @return \DateTime 
     */
    public function getFechaRecogida()
    {
        return $this->fechaRecogida;
    }

    /**
     * Set contenido
     *
     * @param string $contenido
     * @return TteRecogidas
     */
    public function setContenido($contenido)
    {
        $this->contenido = $contenido;

        return $this;
    }

    /**
     * Get contenido
     *
     * @return string 
     */
    public function getContenido()
    {
        return $this->contenido;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     * @return TteRecogidas
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string 
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Set terceroRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenTerceros $terceroRel
     * @return TteRecogidas
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
     * Set puntoOperacionRel
     *
     * @param \Brasa\TransporteBundle\Entity\TtePuntosOperacion $puntoOperacionRel
     * @return TteRecogidas
     */
    public function setPuntoOperacionRel(\Brasa\TransporteBundle\Entity\TtePuntosOperacion $puntoOperacionRel = null)
    {
        $this->puntoOperacionRel = $puntoOperacionRel;

        return $this;
    }

    /**
     * Get puntoOperacionRel
     *
     * @return \Brasa\TransporteBundle\Entity\TtePuntosOperacion 
     */
    public function getPuntoOperacionRel()
    {
        return $this->puntoOperacionRel;
    }

    /**
     * Set anunciante
     *
     * @param string $anunciante
     * @return TteRecogidas
     */
    public function setAnunciante($anunciante)
    {
        $this->anunciante = $anunciante;

        return $this;
    }

    /**
     * Get anunciante
     *
     * @return string 
     */
    public function getAnunciante()
    {
        return $this->anunciante;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     * @return TteRecogidas
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
     * @return TteRecogidas
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
}
