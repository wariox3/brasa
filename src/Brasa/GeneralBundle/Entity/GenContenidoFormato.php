<?php

namespace Brasa\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_contenido_formato")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenContenidoFormatoRepository")
 */
class GenContenidoFormato
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_contenido_formato_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoContenidoFormatoPk;
    
    /**
     * @ORM\Column(name="numero_formato", type="integer", nullable=true)
     */    
    private $numero_formato;    

    /**
     * @ORM\Column(name="titulo", type="string", length=300, nullable=true)
     */    
    private $titulo;     
    
    /**
     * @ORM\Column(name="contenido", type="text", nullable=true)
     */    
    private $contenido;
    
    /**
     * @ORM\Column(name="nombre_formato_iso", type="string", length=300, nullable=true)
     */    
    private $nombreFormatoIso;
    
    /**
     * @ORM\Column(name="version", type="string", length=100, nullable=true)
     */    
    private $version;
    
    /**
     * @ORM\Column(name="fecha_version", type="date", nullable=true)
     */    
    private $fechaVersion;
    
     /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuDisciplinarioTipo", mappedBy="contenidoFormatoRel")
     */
    protected $disciplinariosTiposContenidoFormatoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuCartaTipo", mappedBy="contenidoFormatoRel")
     */
    protected $cartasTiposContenidoFormatoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuContratoTipo", mappedBy="contenidoFormatoRel")
     */
    protected $contratosTiposContenidoFormatoRel;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->disciplinariosTiposContenidoFormatoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cartasTiposContenidoFormatoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contratosTiposContenidoFormatoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoContenidoFormatoPk
     *
     * @return integer
     */
    public function getCodigoContenidoFormatoPk()
    {
        return $this->codigoContenidoFormatoPk;
    }

    /**
     * Set numeroFormato
     *
     * @param integer $numeroFormato
     *
     * @return GenContenidoFormato
     */
    public function setNumeroFormato($numeroFormato)
    {
        $this->numero_formato = $numeroFormato;

        return $this;
    }

    /**
     * Get numeroFormato
     *
     * @return integer
     */
    public function getNumeroFormato()
    {
        return $this->numero_formato;
    }

    /**
     * Set titulo
     *
     * @param string $titulo
     *
     * @return GenContenidoFormato
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set contenido
     *
     * @param string $contenido
     *
     * @return GenContenidoFormato
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
     * Add disciplinariosTiposContenidoFormatoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinarioTipo $disciplinariosTiposContenidoFormatoRel
     *
     * @return GenContenidoFormato
     */
    public function addDisciplinariosTiposContenidoFormatoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDisciplinarioTipo $disciplinariosTiposContenidoFormatoRel)
    {
        $this->disciplinariosTiposContenidoFormatoRel[] = $disciplinariosTiposContenidoFormatoRel;

        return $this;
    }

    /**
     * Remove disciplinariosTiposContenidoFormatoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinarioTipo $disciplinariosTiposContenidoFormatoRel
     */
    public function removeDisciplinariosTiposContenidoFormatoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDisciplinarioTipo $disciplinariosTiposContenidoFormatoRel)
    {
        $this->disciplinariosTiposContenidoFormatoRel->removeElement($disciplinariosTiposContenidoFormatoRel);
    }

    /**
     * Get disciplinariosTiposContenidoFormatoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDisciplinariosTiposContenidoFormatoRel()
    {
        return $this->disciplinariosTiposContenidoFormatoRel;
    }

    /**
     * Add cartasTiposContenidoFormatoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCartaTipo $cartasTiposContenidoFormatoRel
     *
     * @return GenContenidoFormato
     */
    public function addCartasTiposContenidoFormatoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCartaTipo $cartasTiposContenidoFormatoRel)
    {
        $this->cartasTiposContenidoFormatoRel[] = $cartasTiposContenidoFormatoRel;

        return $this;
    }

    /**
     * Remove cartasTiposContenidoFormatoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCartaTipo $cartasTiposContenidoFormatoRel
     */
    public function removeCartasTiposContenidoFormatoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCartaTipo $cartasTiposContenidoFormatoRel)
    {
        $this->cartasTiposContenidoFormatoRel->removeElement($cartasTiposContenidoFormatoRel);
    }

    /**
     * Get cartasTiposContenidoFormatoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCartasTiposContenidoFormatoRel()
    {
        return $this->cartasTiposContenidoFormatoRel;
    }

    /**
     * Add contratosTiposContenidoFormatoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContratoTipo $contratosTiposContenidoFormatoRel
     *
     * @return GenContenidoFormato
     */
    public function addContratosTiposContenidoFormatoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContratoTipo $contratosTiposContenidoFormatoRel)
    {
        $this->contratosTiposContenidoFormatoRel[] = $contratosTiposContenidoFormatoRel;

        return $this;
    }

    /**
     * Remove contratosTiposContenidoFormatoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContratoTipo $contratosTiposContenidoFormatoRel
     */
    public function removeContratosTiposContenidoFormatoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContratoTipo $contratosTiposContenidoFormatoRel)
    {
        $this->contratosTiposContenidoFormatoRel->removeElement($contratosTiposContenidoFormatoRel);
    }

    /**
     * Get contratosTiposContenidoFormatoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosTiposContenidoFormatoRel()
    {
        return $this->contratosTiposContenidoFormatoRel;
    }

    /**
     * Set iso
     *
     * @param string $iso
     *
     * @return GenContenidoFormato
     */
    public function setIso($iso)
    {
        $this->iso = $iso;

        return $this;
    }

    /**
     * Get iso
     *
     * @return string
     */
    public function getIso()
    {
        return $this->iso;
    }

    /**
     * Set version
     *
     * @param string $version
     *
     * @return GenContenidoFormato
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set fechaVersion
     *
     * @param \DateTime $fechaVersion
     *
     * @return GenContenidoFormato
     */
    public function setFechaVersion($fechaVersion)
    {
        $this->fechaVersion = $fechaVersion;

        return $this;
    }

    /**
     * Get fechaVersion
     *
     * @return \DateTime
     */
    public function getFechaVersion()
    {
        return $this->fechaVersion;
    }

    /**
     * Set nombreFormatoIso
     *
     * @param string $nombreFormatoIso
     *
     * @return GenContenidoFormato
     */
    public function setNombreFormatoIso($nombreFormatoIso)
    {
        $this->nombreFormatoIso = $nombreFormatoIso;

        return $this;
    }

    /**
     * Get nombreFormatoIso
     *
     * @return string
     */
    public function getNombreFormatoIso()
    {
        return $this->nombreFormatoIso;
    }
}
