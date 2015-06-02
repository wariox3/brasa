<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_seleccion_referencia")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSeleccionReferenciaRepository")
 */
class RhuSeleccionReferencia
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_referencia_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSeleccionReferenciaPk;            
    
    /**
     * @ORM\Column(name="codigo_seleccion_fk", type="integer")
     */    
    private $codigoSeleccionFk; 
    
    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=true)
     */    
    private $numeroIdentificacion;     
    
    /**
     * @ORM\Column(name="nombre_corto", type="string", length=80, nullable=true)
     */    
    private $nombreCorto;    
    
    /**
     * @ORM\Column(name="telefono", type="string", length=15, nullable=true)
     */    
    private $telefono;    
    
    /**
     * @ORM\Column(name="celular", type="string", length=20, nullable=true)
     */    
    private $celular; 
    
    /**
     * @ORM\Column(name="direccion", type="string", length=30, nullable=true)
     */    
    private $direccion;         
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;    
    
    /**
     * @ORM\Column(name="estado_verificada", type="boolean")
     */    
    private $estadoVerificada = 0;      
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuSeleccion", inversedBy="seleccionesReferenciasSeleccionRel")
     * @ORM\JoinColumn(name="codigo_seleccion_fk", referencedColumnName="codigo_seleccion_pk")
     */
    protected $seleccionRel; 

    /**
     * Get codigoSeleccionReferenciaPk
     *
     * @return integer
     */
    public function getCodigoSeleccionReferenciaPk()
    {
        return $this->codigoSeleccionReferenciaPk;
    }

    /**
     * Set codigoSeleccionFk
     *
     * @param integer $codigoSeleccionFk
     *
     * @return RhuSeleccionReferencia
     */
    public function setCodigoSeleccionFk($codigoSeleccionFk)
    {
        $this->codigoSeleccionFk = $codigoSeleccionFk;

        return $this;
    }

    /**
     * Get codigoSeleccionFk
     *
     * @return integer
     */
    public function getCodigoSeleccionFk()
    {
        return $this->codigoSeleccionFk;
    }

    /**
     * Set nombreCorto
     *
     * @param string $nombreCorto
     *
     * @return RhuSeleccionReferencia
     */
    public function setNombreCorto($nombreCorto)
    {
        $this->nombreCorto = $nombreCorto;

        return $this;
    }

    /**
     * Get nombreCorto
     *
     * @return string
     */
    public function getNombreCorto()
    {
        return $this->nombreCorto;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return RhuSeleccionReferencia
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
     * @return RhuSeleccionReferencia
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
     * Set direccion
     *
     * @param string $direccion
     *
     * @return RhuSeleccionReferencia
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
     * Set seleccionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionRel
     *
     * @return RhuSeleccionReferencia
     */
    public function setSeleccionRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionRel = null)
    {
        $this->seleccionRel = $seleccionRel;

        return $this;
    }

    /**
     * Get seleccionRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion
     */
    public function getSeleccionRel()
    {
        return $this->seleccionRel;
    }

    /**
     * Set estadoVerificada
     *
     * @param boolean $estadoVerificada
     *
     * @return RhuSeleccionReferencia
     */
    public function setEstadoVerificada($estadoVerificada)
    {
        $this->estadoVerificada = $estadoVerificada;

        return $this;
    }

    /**
     * Get estadoVerificada
     *
     * @return boolean
     */
    public function getEstadoVerificada()
    {
        return $this->estadoVerificada;
    }

    /**
     * Set numeroIdentificacion
     *
     * @param string $numeroIdentificacion
     *
     * @return RhuSeleccionReferencia
     */
    public function setNumeroIdentificacion($numeroIdentificacion)
    {
        $this->numeroIdentificacion = $numeroIdentificacion;

        return $this;
    }

    /**
     * Get numeroIdentificacion
     *
     * @return string
     */
    public function getNumeroIdentificacion()
    {
        return $this->numeroIdentificacion;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuSeleccionReferencia
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
}
